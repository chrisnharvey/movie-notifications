<?php

class Country
{
	protected static $default = 'US';

	public static function get($ip = null)
	{
		$ip = $ip ?: Request::getClientIp();

		return Cache::remember("ip_{$ip}", 1440, function() use($ip)
		{
			$response = @file_get_contents("http://api.hostip.info/country.php?ip={$ip}");

			if ($response)
			{
				return $response == 'XX' ? static::$default : $response;
			}

			return static::$default;
		});
	}
}