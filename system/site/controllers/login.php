<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Controller {
	
	public function index()
	{
		login_redirect();
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('identity', 'Email/username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		//$this->load->model('user_m');
		
		if($this->user_m->exceeded_login_attempts())
		{
			$data['show_recaptcha'] = TRUE;
			
			$this->form_validation->set_rules('recaptcha_response_field', 'reCAPTCHA', 'required|callback__check_recaptcha');
			
			$this->load->config('recaptcha');
			$this->load->helper('recaptcha');
			
			$data['recaptcha_html'] = $this->config->item('recaptcha_config').recaptcha_get_html($this->config->item('recaptcha_public_key'));
		}
		else
		{
			$data['show_recaptcha'] = FALSE;
		}
		
		if ($this->form_validation->run() !== FALSE)
		{
			if($login = $this->user_m->login($this->input->post('username'), $this->input->post('password')) === TRUE)
			{
				$this->session->set_flashdata('alert_verified', $this->session->userdata('verified'));
				
				if ($return_route = $this->session->flashdata('return_route'))
				{
					redirect($return_route);
				}
				else
				{
					redirect('/notifications');
				}
			}
			elseif(is_string($login))
			{
				$data['error'] = $login;
			}
			else
			{
				$data['error'] = "The username or password you entered is incorrect";
			}
		}
		else
		{
			$this->session->keep_flashdata('return_route');
		}
		
		$this->page->title = 'Login';
		$this->page->show('login/main', $data);
	}

	public function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key'),
				$this->input->ip_address(),
				$this->input->post('recaptcha_challenge_field'),
				$this->input->post('recaptcha_response_field'));

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', 'The words you entered did not match the image.');
			return FALSE;
		}

		return TRUE;
	}
	
	public function app()
	{
		if ( !$this->input->server('PHP_AUTH_USER') || !$this->input->server('PHP_AUTH_PW') )
        {
            $this->output->set_header('WWW-Authenticate: Basic realm="Movie Notifications App"');
            $this->output->set_header('HTTP/1.0 401 Unauthorized');
            $data['error'] = 'Your username and/or password was blank';
			$data['status'] = '401'; 
        }
        else
        {
            $this->load->model('user_m');
			if( $login = $this->user_m->login($this->input->server('PHP_AUTH_USER'), $this->input->server('PHP_AUTH_PW')) === TRUE )
			{
				$data = array(); //$this->user_m->data(); // Return the logged in users data
			}
			elseif($login === FALSE)
			{
				$this->output->set_header('HTTP/1.0 401 Unauthorized');
	            $data['error'] = 'Login failed';
				$data['status'] = '401';
			}
			else
			{
				$this->output->set_header('HTTP/1.0 401 Unauthorized');
	            $data['error'] = $login;
				$data['status'] = '401';
			}
        }
		
		$this->page->json($data);
	}
}

/* End of file */