<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter IVA (Internet Video Archive) API Library
 * 
 * Author: Chris Harvey (Back2theMovies)
 * Website: http://www.b2tm.com
 * Email: chrish@b2tm.com
 *
 * Originally developed for Back2theMovies (http://www.b2tm.com)
 * 
 **/
 
class IVA {

	private $_api_url = 'http://api.internetvideoarchive.com/';
	
	function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->config("iva");
	}

	public function call($method, $params = array())
	{
		$api_key = $this->_CI->config->item("iva_key");
				
		$params_string = $method.'.aspx?developerid='.$api_key;
		
		foreach($params as $param => $value)
		{
			$params_string .= "&".$param."=".$value;
		}
		
		// Use the SimpleXML to decode
		
		$xml = simplexml_load_file($this->_api_url.$params_string, 'SimpleXMLElement', LIBXML_NOERROR);

		return $xml;
		
	}
}

/* End of file */