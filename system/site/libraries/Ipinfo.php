<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ipinfo
{
	protected $ci;
	protected $api_key;
	protected $url = 'http://api.ipinfodb.com/v3/';

	protected $_cache = array();

	public function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->config('ipinfo');
		$this->api_key = $this->ci->config->item('ipinfo_api_key');

		$this->ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file')); // We use a lot of cacheing, lets load the cacheing driver
	}

	public function country($ip = FALSE)
	{
		$ip = $ip OR $this->ci->input->ip_address();

		if ( ! $response = $this->ci->cache->get('mn_ip_country_' . $ip))
		{
			$response = $this->_call('ip-country', array('ip' => $ip));

			$this->ci->cache->save('mn_ip_country_' . $ip, $response, (86400 * 30));
		}

		if ($response AND $response->statusCode == 'OK')
		{
			return $response->countryCode == 'UK' ? 'GB' : $response->countryCode;
		}
	}

	protected function _call($uri, $params = array())
	{
		$params['key']    = $this->api_key;
		$params['format'] = 'json';
		$params = '?' . http_build_query($params);

		$url = $this->url . $uri . $params;

		if (isset($this->_cache[$url]))
		{
			$cache = $this->_cache[$url];
		}
		else
		{
			$cache = FALSE;
		}

		if ( ! $response = $cache)
		{
			// Use cURL to call API and receive response
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$response = curl_exec($ch);

			curl_close ($ch);
			
			$response = json_decode($response); // Decode the JSON response into an array

			$this->_cache[$url] = $response;
		}

		return $response;
	}
}