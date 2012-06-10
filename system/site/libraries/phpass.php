<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.3/PasswordHash.php');

class Phpass extends PasswordHash
{
	public function __construct()
	{
		$_CI =& get_instance();
		$_CI->load->config('phpass');
		
		$this->PasswordHash($_CI->config->item('phpass_iterations'), $_CI->config->item('phpass_portable'));
	}
}
