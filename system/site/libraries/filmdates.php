<?php

class Filmdates
{
	public $url = 'http://www.filmdates.co.uk/api/get/?release-dates&json';
	
	public function get_dates()
	{
		$call = $this->_call_api();

		if ( ! isset($call->error))
		{
			return $call;
		}

		return FALSE;
	}
	
	protected function _call_api()
	{
		// Use cURL to call API and receive response
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($ch);

		curl_close ($ch);
		
		$response = json_decode($response); // Decode the JSON response into an array
		
		return $response; // Return the array
	}
}
