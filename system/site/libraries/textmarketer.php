<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter TextMarketer Library
 * 
 * Author: Chris Harvey (Movie Notifications)
 * Website: http://movienotifications.com
 * Email: chris.harvey@movienotifications.com
 *
 * Originally developed for Movie Notification (http://movienotifications.com)
 * 
 **/
 
class Textmarketer {
	private $URL = "http://www.textmarketer.biz/gateway/"; 
	private $SHORT_CODE="88802";
	private $using_short_code=false;
 	private $my_url;
	private $error;
	private $remaining_credits,$credits_used;
	private $transaction_id;
 
	
	function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->config("textmarketer");
		
		$this->my_url = $this->URL."?username=".$this->_CI->config->item('api_username')."&password=".$this->_CI->config->item('api_password')."&option=xml";
	}

	
	public function send($number,$message,$originator = NULL)
	{
		$originator = ($originator === NULL) ? $this->_CI->config->item('default_from') : $originator;	


		$this->error = null;

		$query_string = "&number=".$number;
		$query_string .= "&message=".urlencode($message);

		$query_string .= "&orig=".urlencode($originator);

		$fp =fopen($this->my_url.$query_string,"r");
		$response = fread($fp,1024);

		return $this->process_response($response);
	}


	
	public function get_error()
	{
		// returns an array of error messages	
		$arr = each($this->error);
		return $arr['value'];
	}
	
	
	public function remaining_credits()
	{
		// the total of credits you have left in your account
		return $this->ramaining_credits;
	}
	
	public function transaction_id()
	{
		// This is the unique code that represents your send, you need this code to match up delivery reports
		return $this->transaction_id;
	}

	public function credits_used()
	{
		// how many credits were used for the send, a message that uses more than 160 characters will use more credits. 1 CR = 160 characters
		return $this->credits_used;
	}
	
	//////// PRIVATE FUNCTIONS
	
	private function process_response($r)
	{
		$xml=simplexml_load_string($r);
		if($xml['status']=="failed"){
			foreach($xml->reason as $index => $reason) $this->error[] = $reason; /// parse the errors into an array
			return FALSE;
		}
		else{
			$this->transaction_id = $xml['id'];
			$this->remaining_credits = $xml->credits;
			$this->credits_used = $xml->credits_used;
			return TRUE;
		}
		
	}
}