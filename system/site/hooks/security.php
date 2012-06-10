<?php
function security(){
	$CI =& get_instance();

	if (is_logged_in())
	{
	
		$user = $CI->db->get_where("users", array("id" => $CI->session->userdata("user_id")));
		
		if ($user->row()->email != $CI->session->userdata("email") || $user->row()->password != $CI->session->userdata("password") || $user->row()->country != $CI->session->userdata('country') || $user->row()->verified != $CI->session->userdata('verified'))
		{
			$CI->session->set_userdata('email', $user->row()->email);
			$CI->session->set_userdata('password', $user->row()->password);
			$CI->session->set_userdata('country', $user->row()->country);
			$CI->session->set_userdata('verified', $user->row()->verified);
			
			//redirect(current_url()); // Refresh the page, the users details have changed
		}
		
	}
	
	if (!$CI->input->cookie('country'))
	{
		if ($CI->input->get('country') == 'UK')
		{
			$CI->input->set_cookie('country', 'UK', 0, ".".$CI->config->item('site_domain'));
		}
		elseif($CI->input->server('HTTP_CF_IPCOUNTRY') != 'US' || $CI->input->server('HTTP_CF_IPCOUNTRY') != 'XX')
		{
			$CI->input->set_cookie('country', $CI->input->server('HTTP_CF_IPCOUNTRY'), 0, ".".$CI->config->item('site_domain'));
		}
	}
	
	if (!$CI->input->cookie("full_site"))
	{
		if(isset($_GET['full']))
		{
			$CI->input->set_cookie("full_site", TRUE, 0, ".".$CI->config->item("site_domain"));
		}
		else
		{
			$CI->load->library('user_agent');
			if ($CI->agent->is_mobile() && end($CI->uri->rsegment_array()) != 'app')
			{
				redirect($CI->config->item("mobile_site"));
			}
		}
	}
	
}
/* End of file */