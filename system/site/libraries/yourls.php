<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yourls
{
	protected $ci;
	protected $signature;
	protected $api_url;

	public function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->config->load('yourls');

		$this->signature = $this->ci->config->item('yourls_signature');
		$this->api_url   = $this->ci->config->item('yourls_api_url');
	}

	public function shorten($url)
	{
		$params = array(
			'signature' => $this->signature,
			'action'    => 'shorturl',
			'format'    => 'json',
			'url'       => $url
		);

		$call_url = $this->api_url . '?' . http_build_query($params);

		$data = json_decode(file_get_contents($call_url));

		if (isset($data->shorturl))
		{
			return $data->shorturl;
		}

		return FALSE;
	}
}