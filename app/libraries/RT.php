<?php

use Guzzle\Http\Client;

class RT
{
	private static $api_url = 'http://api.rottentomatoes.com/api/public/v1.0/';

	private static function get($param)
	{
		return property_exists('RT', $param) ? static::$$param : Config::get("rt.{$param}");
	}
	
	public static function call($method, $params = NULL)
	{
		$params['apikey'] = static::get('api_key');

		return (new Client(static::get('api_url')))
					->get($method . '?' . http_build_query($params))
					->setHeader('Accept', 'application/json')
					->send()
					->json();
	}
}