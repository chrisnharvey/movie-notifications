<?php

class User
{

	public static function country($iso = false)
	{
		// Is the user logged in?
		if (Auth::check())
		{
			return Auth::user()->country;
		}
		elseif ( ! $country = Session::get('country'))
		{
			if ($country = Country::get()) 
			{
				$country = $country == 'XX' ? 'US' : $country;
			}

			Session::put('country', $country);
		}
		
		return $country;
	}

}