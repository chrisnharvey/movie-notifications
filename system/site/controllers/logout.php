<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends Restricted {
	
	public function __construct()
	{
		parent::__construct(FALSE); // Construct the parent, without setting the return route
	}
	
	public function index()
	{
		$this->session->sess_destroy();
		
		redirect('/login');
	}
}

/* End of file */