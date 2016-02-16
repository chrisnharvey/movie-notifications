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
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
		
		$response = curl_exec($ch);

		curl_close ($ch);
		
		$response = json_decode($response); // Decode the JSON response into an array
		
		return $response; // Return the array
	}
}
