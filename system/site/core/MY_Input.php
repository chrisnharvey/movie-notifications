<?php if ( ! defined('BASEPATH')) exit('No direct script access');

class MY_Input extends CI_Input {

    public function is_app_request()
	{
		$CI =& get_instance();
		
		if(end($CI->uri->rsegment_array()) == 'app')
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}
}