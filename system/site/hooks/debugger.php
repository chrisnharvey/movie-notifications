<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function check_debug()
{
	if(isset($_GET["debug"]) && ENVIRONMENT == "development")
	{
		$CI =& get_instance();
		$CI->output->enable_profiler(TRUE);
	}
}
