<?php if ( ! defined('BASEPATH')) exit('No direct script access');

function user_pic($id, $width, $height)
{
	if(!is_int($id)) // Check if its a user ID or a username that is being passed
	{
		$username = $id;
	}
	
	return NULL;
}

// Redirects the user to the desired page when login is successful
function login_redirect($url = '/notifications')
{
	$CI =& get_instance();
	
	if (is_logged_in())
	{
		redirect($return = $CI->session->flashdata('return_route') ? $return : $url);
	}
}

function check_login()
{
	$CI =& get_instance();
	
	if (!is_logged_in())
	{
		if ($CI->input->is_app_request() || $CI->input->is_ajax_request())
		{
			$CI->output->set_header('HTTP/1.0 401 Unauthorized');
		}
		else
		{
			redirect('/login');
		}
	}
}


// A simple function that checks if the user is logged in
function is_logged_in()
{
	$CI =& get_instance();
	
	if ($CI->session->userdata('logged_in'))
	{
		return TRUE;
	}
	elseif ($CI->input->server('PHP_AUTH_USER') AND $CI->input->server('PHP_AUTH_PW'))
	{
		if ($CI->user_m->login($CI->input->server('PHP_AUTH_USER'), $CI->input->server('PHP_AUTH_PW')) === TRUE)
		{
			return TRUE;
		}
	}

	return FALSE;
}

function generate_password($length = 8)
{

	// start with a blank password
	$password = "";
	
	// define possible characters - any character in this string can be
	// picked for use in the password, so if you want to put vowels back in
	// or add special characters such as exclamation marks, this is where
	// you should do it
	$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
	
	// we refer to the length of $possible a few times, so let's grab it now
	$maxlength = strlen($possible);
	  
	// check for length overflow and truncate if necessary
	if ($length > $maxlength)
	{
		$length = $maxlength;
	}
		
	// set up a counter for how many characters are in the password so far
	$i = 0; 
	    
	// add random characters to $password until $length is reached
	while ($i < $length)
	{ 
		
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, $maxlength-1), 1);
		    
		// have we already used this character in $password?
		if (!strstr($password, $char))
		{ 
			// no, so it's OK to add it onto the end of whatever we've already got...
			$password .= $char;
			// ... and increase the counter by one
			$i++;
		}
	
	}
	
	// done!
	return $password;

}
