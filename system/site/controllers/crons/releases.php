<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Releases extends Cron
{
	private $_rt = NULL;
	public $notify_calls = 100;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array('movie_m', 'system_m'));
		$this->load->library(array('rt', 'TMDb', 'tmdb2', 'filmdates', 'CLI'));
	}
	
	function monthly()
	{
		$this->cli->clear_screen();

		$this->cli->write("Attempting to find dates that could not be found previously, starting with theater dates...");
		
		$query = $this->db->get_where("movies_meta", array("key" => "no_theater_date", "value" => "Y"));
		foreach ($query->result() as $movie)
		{
			$movie_id = $movie->movie_id;
			$imdb = $this->movie_m->meta($movie_id, "imdb_id");
			if (substr($imdb, 0, 2) == "tt")
			{
				if (strlen($imdb) > 2)
				{
					$imdb_id = substr($imdb, 2);
					$this->_rt = $this->rt->call("movie_alias", array("type" => "imdb", "id" => $imdb_id));
					$this->cli->write("Processing theatrical release for movie: ".$movie_id);
					
					$this->_process_by_type($movie_id, 'Theaters');
				}	
			}	
		}
		
		$this->cli->clear_screen();
		$this->cli->write("Now finding DVD dates that could not be found previously...");
		
		$query = $this->db->get_where("movies_meta", array("key" => "no_dvd_date", "value" => "Y"));
		foreach ($query->result() as $movie)
		{
			$movie_id = $movie->movie_id;
			$imdb = $this->movie_m->meta($movie_id, "imdb_id");
			if (substr($imdb, 0, 2) == "tt")
			{
				if (strlen($imdb) > 2)
				{
					$imdb_id = substr($imdb, 2);
					$this->_rt = $this->rt->call("movie_alias", array("type" => "imdb", "id" => $imdb_id));

					$this->cli->write("Processing DVD release for movie: ".$movie_id);
					
					$this->_process_by_type($movie_id, 'DVD');
				}	
			}	
		}
		
		$this->cli->write("So thats that... Now on to the latest releases...");
		
		$n = 1;
		
		while($n <= 4)
		{
			$this->cli->clear_screen();
			$this->cli->write("Processing attempt ".$n);

			$this->cli->write("Getting the last date added from the database...");

			$last_date = $this->system_m->meta("last_date_added");

			$this->cli->write("Grabbing the latest releases from TMDb... Starting at: ".$last_date);

			$tmdb = $this->tmdb2->call("Movie.browse", "?order_by=release&order=asc&per_page=50&release_min=".strtotime('-2 months', strtotime($last_date))."&release_max=".strtotime("+2 months", strtotime($last_date)));

			$i=0;

			foreach ($tmdb as $movie)
			{
				$imdb = $movie->imdb_id;
				if (substr($imdb, 0, 2) == "tt")
				{
					if (strlen($imdb) > 2)
					{
						$imdb_id = substr($imdb, 2);
						$rt = $this->rt->call("movie_alias", array("type" => "imdb", "id" => $imdb_id));
							
						// If this movie has already been released on both platforms then continue because its a mistake
						if (( ! isset($rt->release_dates->theater) OR strtotime($rt->release_dates->theater) < time()) AND ( ! isset($rt->release_dates->dvd) OR strtotime($rt->release_dates->dvd) < time()))
						{
							continue;
						}
						
						if ($movie_id = $this->movie_m->add($imdb))
						{
							$this->cli->write("Processing theatrical release for movie: ".$movie_id);
							if (isset($rt->release_dates->theater))
							{
								$theater = $rt->release_dates->theater;
							}
							else
							{
								$theater = $movie->released;
							//	echo "Theatrical release not found for movie: ".$movie_id.". The release date according to TMDb is: ".$theater."\n";
							//	mail("flux@b2tm.com", "RT does not have the theatrical date for movie ".$movie_id, "When scanning TMDb for movies released in the future, the movie ".$movie_id." was returned but RT did not return a release date for this movie, TMDb has a release date of: ".$theater.". This is an unexpected error.");
								unset($theater);
							}
						
							if (isset($theater))
							{
								$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => "Theaters", 'country_id' => 226));
								if ($query->num_rows() > 0)
								{
									if ($query->row()->date != $theater)
									{
										$this->db->where("movie_id", $movie_id);
										$this->db->where("type", "Theaters");
										$this->db->where('country_id', 226);
										$this->db->update("releases", array("date" => $theater));
									}
								}
								else
								{
									$this->db->insert("releases", array("date" => $theater, "movie_id" => $movie_id, "type" => "Theaters", 'country_id' => 226));
								}

								$this->cli->write("Theater date added for movie: ".$movie_id, 'green');
							}
							else
							{
								$this->movie_m->add_meta($movie_id, "no_theater_date", "Y");
								$theater_calls = $this->movie_m->meta($movie_id, "theater_calls");
								
								if ($theater_calls)
								{
									$theater_calls++;
								}
								else
								{
									$theater_calls = 1;
								}
								
								$this->movie_m->add_meta($movie_id, "theater_calls", $theater_calls);
								
								if ($theater_calls > $this->notify_calls)
								{
									mail("flux@b2tm.com", "Movie ".$movie_id." has exceeded ".$this->notify_calls." Theater calls", "The movie ".$movie_id." has exceeded ".$this->notify_calls." calls for checking for a Theater release date. Perhaps this film is not going to be released at the theater");
								}
							
								$this->cli->error("Theater release not found for movie: ".$movie_id.". A total of ".$theater_calls." calls now");
							}
						
							if (isset($rt->release_dates->dvd))
							{
								$this->cli->write("Processing DVD release for movie: ".$movie_id);
							
								$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => "DVD", 'country_id' => 226));
								if ($query->num_rows() > 0)
								{
									if ($query->row()->date != $rt->release_dates->dvd)
									{
										$this->db->where("movie_id", $movie_id);
										$this->db->where("type", "DVD");
										$this->db->where('country_id', 226);
										$this->db->update("releases", array("date" => $rt->release_dates->dvd));
									}
								}
								else
								{
									$this->db->insert("releases", array("date" => $rt->release_dates->dvd, "movie_id" => $movie_id, "type" => "DVD", 'country_id' => 226));
								}

								$this->cli->write("DVD date added for movie: ".$movie_id, 'green');
							}
							else
							{
								$this->movie_m->add_meta($movie_id, "no_dvd_date", "Y");
								$dvd_calls = $this->movie_m->meta($movie_id, "dvd_calls");
								if ($dvd_calls)
								{
									$dvd_calls++;
								}
								else
								{
									$dvd_calls = 1;
								}
								$this->movie_m->add_meta($movie_id, "dvd_calls", $dvd_calls);
								if ($dvd_calls > 30)
								{
									mail("flux@b2tm.com", "Movie ".$movie_id." has exceeded ".$this->notify_calls." DVD calls", "The movie ".$movie_id." has exceeded ".$this->notify_calls." calls for checking for a DVD release date. Perhaps this film is not going to be released on DVD");
								}
							
								$this->cli->error("DVD release not found for movie: ".$movie_id.". A total of ".$dvd_calls." calls now");
							}
						
							if (isset($theater) AND strtotime($theater) > time())
							{
								$this->system_m->add_meta("last_date_added", $theater);
								$this->cli->write("Last date added is now: ".$theater, 'blue');
							}
							else
							{
								$this->system_m->add_meta("last_date_added", date('Y-m-d'));
								$this->cli->write("Last date added is now: ".date('Y-m-d'), 'blue');
							}
						
						}
					}
				}
			}
			$n++;
		}

		$this->cli->clear_screen();
		$this->cli->write('Cron complete', 'green');

	}

	private function _process_by_type($movie_id, $type = 'Theaters')
	{
		if($type == 'Theaters')
		{
			if(isset($this->_rt->release_dates->theater))
			{
				$date = $this->_rt->release_dates->theater;
			}
		}
		else
		{
			$type = 'DVD';
			
			if(isset($this->_rt->release_dates->dvd))
			{
				$date = $this->_rt->release_dates->dvd;
			}
		}
	
		if(isset($date))
		{
			$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => $type, 'country_id' => 226));
			if($query->num_rows())
			{
				if($query->row()->date != $date)
				{
					$this->db->where("movie_id", $movie_id);
					$this->db->where("type", "Theaters");
					$this->db->where('country_id', 226);
					$this->db->update("releases", array("date" => $date));
				}
			}
			else
			{
				$this->db->insert("releases", array("date" => $date, "movie_id" => $movie_id, "type" => $type, 'country_id' => 226));
			}

			if ($type == 'theaters')
			{
				$this->movie_m->add_meta($movie_id, 'no_theater_date', NULL);
			}
			else
			{
				$this->movie_m->add_meta($movie_id, 'no_dvd_date', NULL);
			}

			$this->cli->write($type." release added for movie: ".$movie_id, 'green');
		}
		else
		{
			if($type == 'Theaters')
			{
				$add_meta = 'no_theater_date';
				$calls_meta = 'theater_calls';
			}
			else
			{
				$add_meta = 'no_dvd_date';
				$calls_meta = 'dvd_calls';
			}
			
			$this->movie_m->add_meta($movie_id, $add_meta, "Y");
			$calls = $this->movie_m->meta($movie_id, $calls_meta);
			
			if($calls)
			{
				$calls++;
			}
			else
			{
				$calls = 1;
			}
			
			$this->movie_m->add_meta($movie_id, $calls_meta, $calls);
			if($calls > 30)
			{
				mail("flux@b2tm.com", "Movie ".$movie_id." has exceeded ".$this->notify_calls." ".$type." calls", "The movie ".$movie_id." has exceeded ".$this->notify_calls." calls for checking for a ".$type." release date. Perhaps this film is not going to be released at the ".$type);
			}
		
			$this->cli->error($type." release not found for movie: ".$movie_id.". A total of ".$calls." calls now");
		}
	}

	public function uk()
	{
		$this->cli->clear_screen();
				
		$this->cli->write('Getting dates for the UK');
		$dates = $this->filmdates->get_dates();
		
		if ($dates)
		{
			foreach ($dates as $date)
			{
				$this->cli->write('Processing movie: '.$date->name);
				
				if (substr($date->url, -1, 1) == '/')
				{
					$imdb_id = substr($date->url, -10, -1);
				}
				else
				{
					$imdb_id = substr($date->url, -9);
				}
							
				if ($imdb_id)
				{
					$this->cli->write('IMDb ID: '.$imdb_id);
					if ($movie_id = $this->movie_m->add($imdb_id))
					{
						$date = $date->date;
						
						$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => "Theaters", "country_id" => 225));
						if($query->num_rows())
						{
							if($query->row()->date != $date)
							{
								$this->db->where("movie_id", $movie_id);
								$this->db->where("type", "Theaters");
								$this->db->where("country_id", 225);
								$this->db->update("releases", array("date" => $date));
							}
						}
						else
						{
							$this->db->insert("releases", array("date" => $date, "movie_id" => $movie_id, "type" => "Theaters", "country_id" => 225));
						}
					}
				}
			}
		}
		else
		{
			$this->cli->error('Error calling the FilmDates API');
		}
	}
}
