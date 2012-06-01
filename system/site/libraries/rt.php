<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Rotten Tomatoes API Library
 * 
 * Author: Chris Harvey (Back2theMovies)
 * Website: http://www.b2tm.com
 * Email: chrish@b2tm.com
 *
 * Originally developed for Back2theMovies (http://www.b2tm.com)
 * 
 **/
 
class Rt {

	private $_api_url = 'http://api.rottentomatoes.com/api/public/v1.0/';
	
	function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->config("rt");
	}

	public function call($method, $params = NULL)
	{
		$api_key = $this->_CI->config->item("rt_api_key");
		
		$params_string = "";
		
		foreach($params as $param => $value)
		{
			$params_string .= "&".$param."=".$value;
		}
		
		// Use cURL to call API and receive response
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->_api_url.$method.".json?apikey=".$api_key.$params_string);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_ENCODING, "");
		
		$response = curl_exec ($ch);

		curl_close ($ch);
		
		$response = json_decode($response); // Decode the JSON response into an array
		$response = $response; // All data is contained in an unneccecary array (removed here)
		
		return $response; // Return the array
		
	}
}

/* End of file */