<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends Controller
{
	function error_404($scope = "site")
	{
		$this->output->set_header("HTTP/1.1 404 Not Found");
		if($scope == "app")
		{
			$data['error'] = 404;
			$data['message'] = "Page Not Found";
			$this->page->json($data);
		}
		elseif($this->input->is_cli_request())
		{
			$this->load->library('CLI');
			$this->cli->write("Page Not Found");
		}
		else
		{
			// This code should also be copied to MY_Exceptions.php
			$this->page->title = "Page Not Found";
			$this->page->show("errors/404", NULL, TRUE);
		}
	}
}
