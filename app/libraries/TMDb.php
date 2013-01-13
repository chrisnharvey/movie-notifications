<?php

use Guzzle\Http\Client;

class TMDb
{
	private static $api_url = 'http://api.themoviedb.org/3/';

	/********************************
	 * Functions that set variables *
	 ********************************/
	
	public function setLanguage($iso_code)
	{
		static::$language = $iso_code;
	}
	
	public function setKey($key)
	{
		static::$api_key = $key;
	}

/////////////////////////////////////////////////////////////////////////////////////
	
	/********************************
	 *		  	 Search		 		*
	 ********************************/
	
	public function searchMovies($query, $page = 1, $include_adult = FALSE)
	{
		$params = array(
			'query'			=> urlencode($query),
			'page'			=> $page,
			'include_adult'	=> $include_adult
		);
		
		return static::_call('search/movie', $params);
	}
	
	public function searchPeople($query, $page = 1, $include_adult = FALSE)
	{
		$params = array(
			'query'			=> $query,
			'page'			=> $page,
			'include_adult'	=> $include_adult
		);
		
		return static::_call('search/person', $params);
	}

/////////////////////////////////////////////////////////////////////////////////////

	/********************************
	 *		  	 Movies		 		*
	 ********************************/
	
	public function collection($id)
	{
		return static::_call('collection/' . $id);
	}
	
	public static function movieInfo($id)
	{
		return static::_call('movie/' . $id);
	}
	
	public function movieAlternateTitles($id, $country = NULL)
	{
		$params = array(
			'country' => $country
		);
		
		return static::_call('movie/' . $id . '/alternative_titles', $params);
	}
	
	public function movieCasts($id)
	{
		return static::_call('movie/' . $id . '/casts');
	}
	
	public function movieImages($id, $country = NULL)
	{
		$params = array(
			'country' => $country
		);
		
		return static::_call('movie/' . $id . '/images', $params);
	}
	
	public function movieKeywords($id)
	{
		return static::_call('movie/' . $id . '/keywords');
	}
	
	public function movieReleases($id)
	{
		return static::_call('movie/' . $id . '/releases');
	}

	public function movieTrailers($id, $language = NULL)
	{
		$params = array(
			'language' => $language
		);
		
		return static::_call('movie/' . $id . '/trailers', $params);
	}
	
	public function movieTranslations($id, $language = NULL)
	{
		return static::_call('movie/' . $id . '/translations');
	}
	
	public function movieSimilar($id, $page = 1, $language = NULL)
	{
		$params = array(
			'page'		=> $page,
			'language'	=> $language
		);
		
		return static::_call('movie/' . $id . '/similar_movies', $params);
	}
	
/////////////////////////////////////////////////////////////////////////////////////
	
	/********************************
	 *		  	 People		 		*
	 ********************************/
	 
	public function personInfo($id)
	{
		return static::_call('person/' . $id);
	}
	
	public function personCredits($id, $language = NULL)
	{
		$params = array(
			'language' => $language
		);
		
		return static::_call('person/' . $id . '/credits', $params);
	}
	
	public function personImages($id)
	{
		return static::_call('person/' . $id . '/images');
	}
	
	
/////////////////////////////////////////////////////////////////////////////////////
	
	/********************************
	 *		  	 Companies	 		*
	 ********************************/
	 
	public function companyInfo($id)
	{
		return static::_call('company/' . $id);
	}
	
	public function companyMovies($id, $page = 1, $language = NULL)
	{
		$params = array(
			'page'		=> $page,
			'language'	=> $language
		);
		
		return static::_call('company/' . $id . '/movies', $params);
	}
	
/////////////////////////////////////////////////////////////////////////////////////
	
	/********************************
	 *		   	   Misc			 	*
	 ********************************/
	 
	public function latestMovie()
	{
		return static::_call('latest/movie');
	}

	public function nowPlaying($page = 1, $language = NULL)
	{
		$params = array(
			'page'		=> $page,
			'language'	=> $language
		);
		
		return static::_call('movie/now-playing', $params);
	}
	
	public function popularMovies($page = 1, $language = NULL)
	{
		$params = array(
			'page'		=> $page,
			'language'	=> $language
		);
		
		return static::_call('movie/popular', $params);
	}
	
	public function topRated($page = 1, $language = NULL)
	{
		$params = array(
			'page'		=> $page,
			'language'	=> $language
		);
		
		return static::_call('movie/top-rated', $params);
	}
	
/////////////////////////////////////////////////////////////////////////////////////
	
	/********************************
	 * 		Private functions 		*
	 ********************************/

	private static function get($param)
	{
		return property_exists('RT', $param) ? static::$$param : Config::get("tmdb.{$param}");
	}
	
	private static function _call($method, $params = NULL)
	{
		$params['api_key']	= static::get('api_key');

		return (new Client(static::get('api_url')))
					->get($method . '?' . http_build_query($params))
					->setHeader('Accept', 'application/json')
					->send()
					->json();
	}
}