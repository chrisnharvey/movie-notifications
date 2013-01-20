<?php

class TrMovie implements Movie
{
	public function __construct(Image $image)
	{
		$this->image = $image;
		$this->rt    = new RT;
		$this->tmdb  = new TMDb;
	}

	public function boxOffice($limit = 10, $country = false)
	{
		return $this->call("lists/movies/box_office", array('limit' => $limit, 'country' => $country ?: static::country()));
	}

	public function newDvds($limit = 10, $country = false)
	{
		return $this->call('lists/dvds/new_releases', ['page_limit' => $limit, 'country' => $country ?: static::country()]);
	}

	public function opening($limit = 10, $country = false)
	{
		return $this->call('lists/movies/opening', ['limit' => $limit, 'country' => $country ?: static::country()]);
	}

	public function inTheaters($limit = 10, $country = false)
	{
		return $this->call('lists/movies/in_theaters', ['limit' => $limit, 'country' => $country ?: static::country()]);
	}

	public function topRentals($limit = 10, $country = false)
	{
		return $this->call('lists/dvds/top_rentals', ['limit' => $limit, 'country' => $country ?: static::country()]);
	}

	public function poster($movieId, $width = 81, $height = 120)
	{

	}

	protected function call($uri, $params = [], $ttl = 1440)
	{
		ksort($params);

		$key = md5($uri.serialize($params));

		return Cache::remember($key, $ttl, function() use($uri, $params)
		{
			$movies = RT::call($uri, $params);
			$data   = [];

			foreach ($movies['movies'] as $movie)
			{
				$query = DB::table('movies')
					->leftJoin('movies_meta', 'movies.id', '=', 'movies_meta.movie_id');

				if (isset($movie['alternate_ids']['imdb']))
				{
					$query->where('movies_meta.key', '=', 'imdb_id')
						->where('movies_meta.value', '=', "tt{$movie['alternate_ids']['imdb']}");
				}
				else
				{
					$query->where('movies_meta.key', '=', 'rt_id')
						->where('movies_meta.value', '=', $movie['id']);
				}

				if ($row = $query->first(['movies_meta.key', 'movies_meta.value', 'title', 'movies.id']))
				{
					$data[] = [
						'id'     => $row->id,
						'title'  => $row->title,
						'url'    => static::url($row->id),
						'poster' => $this->poster($row->id)
					];
				}
				else
				{
					$data[] = [
						'id'     => isset($movie['alternate_ids']['imdb']) ? "tt{$movie['alternate_ids']['imdb']}" : "rt{$movie['id']}",
						'title'  => $movie['title'],
						'url'    => static::url($movie),
						'poster' => Image::url($movie['posters']['profile'])
					];
				}
			}

			return $data;
		});
	}

	public static function country()
	{
		$country = User::country(true);

		// Set GB to UK for Rotten Tomatoes... Stupid!
		return $country == 'GB' ? 'UK' : $country;
	}
	
	public static function url($movie)
	{
		if (is_array($movie))
		{
			if (isset($movie['alternate_ids']['imdb']))
			{
				return route('movie', ['movie' => "tt{$movie['alternate_ids']['imdb']}"]);
			}
			else
			{
				return route('movie', ['movie' => "rt{$movie['id']}"]);
			}
		}
		else
		{
			return route('movie', ['movie' => $movie]);
		}
	}
}