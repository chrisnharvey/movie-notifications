<?php if ( ! defined('BASEPATH')) exit('No direct script access');

class MY_Exceptions extends CI_Exceptions {

    public function show_404()
    {
    	$CI =& get_instance();
		
		$CI->page->title = "Page Not Found";
		$CI->page->show("errors/404", NULL, TRUE);
		
		echo $CI->output->get_output();
		exit;
    }
}