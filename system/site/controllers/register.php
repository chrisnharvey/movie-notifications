<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct('/notifications');
		$this->load->config('recaptcha');
		$this->load->helper('recaptcha');
	}

	public function index()
	{
		login_redirect(); // Redirects the user to their desired URL if logged in
		
		// Load in requirements
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		$this->form_validation->set_rules('username', 'username', 'required|is_unique[users.username]|max_length[20]|alpha_dash');
		$this->form_validation->set_rules('email', 'email address', 'required|is_unique[users.email]|valid_email|max_length[256]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[5]');
		$this->form_validation->set_rules('confirm_password', 'confirmation password', 'required|min_length[5]|matches[password]');
		$this->form_validation->set_rules('recaptcha_response_field', 'reCAPTCHA', 'required|callback__check_recaptcha');
		
		$this->form_validation->set_message('is_unique', 'The %s you entered is already in use.');
		
		if ($this->form_validation->run() === TRUE AND $this->user_m->register($this->input->post('username'), $this->input->post('email'), $this->input->post('password')))
		{
			$this->page->title = 'Registration Complete';
			$this->page->show('register/complete');
		}
		else
		{
			$data['recaptcha_html'] = $this->config->item('recaptcha_config').recaptcha_get_html($this->config->item('recaptcha_public_key'));
			
			$this->page->title = 'Register';
			$this->page->show('register/main', $data);
		}
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
	
	public function verify($method = NULL)
	{
		check_login(); // The user must be logged
		
		if($this->session->userdata('verified') == '1')
		{
			show_404();
		}
		elseif($method === NULL)
		{
			$this->page->show('errors/email_not_verified', NULL, TRUE);
		}
		elseif($method == 'resend')
		{
			if($this->user_m->send_verification_email())
			{
				$this->page->title = 'Verification Email Sent';
				$this->page->show('register/verification/resent');
			}
			else
			{
				$this->page->title = 'Verification Email Failed';
				$this->page->show('register/verification/resend_failed');
			}
		}
		else
		{
			// Assume that the method is the verification hash from the email
			if($this->user_m->check_verification_hash($method))
			{
				$this->page->title = 'Your email address has been verified';
				$this->page->show('register/verification/verified');
			}
			else
			{
				$this->page->title = 'Your email address could not be verified';
				$this->page->show('register/verification/failed');
			}
		}
	}
}

/* End of file */