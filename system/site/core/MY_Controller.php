<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller extends CI_Controller
{
	public function __construct($return_route = TRUE)
	{
		parent::__construct();
		
		if($return_route === TRUE)
		{
			if(!$this->input->is_ajax_request())
			{
				$this->session->set_flashdata('return_route', current_url()); // Set the return route to the current url
			}
		}
		elseif(is_string($return_route))
		{
			$this->session->set_flashdata('return_route', $return_route); // Set the return route to the defined url
		}
		else
		{
			$this->session->keep_flashdata('return_route');
		}
	}
}

class Restricted extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		check_login();
	}
}

class Cron extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		if(!$this->input->is_cli_request())
		{
			show_404();
		}
		else
		{
			$this->load->library('CLI');
		}
	}
}
