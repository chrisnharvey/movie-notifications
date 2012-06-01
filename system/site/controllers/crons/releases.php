<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Releases extends Cron
{
	private $_rt = NULL;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('movie_m');
		$this->load->model('system_m');
		$this->load->library('rt');
		$this->load->library('TMDb');
	}
	
	function monthly()
	{	
		echo "Attempting to find dates that could not be found previously, starting with theater dates...\n\n";
		
		$query = $this->db->get_where("movies_meta", array("key" => "no_theater_date", "value" => "Y"));
		foreach($query->result() as $movie)
		{
			$movie_id = $movie->movie_id;
			$imdb = $this->movie_m->meta($movie_id, "imdb_id");
			if(substr($imdb, 0, 2) == "tt")
			{
				if(strlen($imdb) > 2)
				{
					$imdb_id = substr($imdb, 2);
					$this->_rt = $this->rt->call("movie_alias", array("type" => "imdb", "id" => $imdb_id));
					echo "Processing theatrical release for movie: ".$movie_id."\n";
					
					$this->_process_by_type($movie_id, 'Theaters');
				}	
			}	
		}
		
		echo "\nNow finding DVD dates that could not be found previously...\n\n";
		
		$query = $this->db->get_where("movies_meta", array("key" => "no_dvd_date", "value" => "Y"));
		foreach($query->result() as $movie)
		{
			$movie_id = $movie->movie_id;
			$imdb = $this->movie_m->meta($movie_id, "imdb_id");
			if(substr($imdb, 0, 2) == "tt")
			{
				if(strlen($imdb) > 2)
				{
					$imdb_id = substr($imdb, 2);
					$this->_rt = $this->rt->call("movie_alias", array("type" => "imdb", "id" => $imdb_id));
					echo "Processing DVD release for movie: ".$movie_id."\n";
					
					$this->_process_by_type($movie_id, 'DVD');
				}	
			}	
		}
		
		echo "\nSo thats that... Now on to the latest releases...\n\n";
		
		$n = 1;
		
		while($n <= 4)
		{
			echo "Processing attempt ".$n."\n\n";
			echo "Getting the last date added from the database...\n";
			$last_date = $this->system_m->meta("last_date_added");
			echo "Grabbing the latest releases from TMDb... Starting at: ".$last_date."\n\n";
			$tmdb = $this->tmdb2->call("Movie.browse", "?order_by=release&order=asc&per_page=50&release_min=".strtotime($last_date)."&release_max=".strtotime("+2 months", strtotime($last_date)));
			//$tmdb = $tmdb[0];
		
			$i=0;
		
			foreach($tmdb as $movie)
			{
				//print_r($movie);
			
				$imdb = $movie->imdb_id;
				if(substr($imdb, 0, 2) == "tt")
				{
					if(strlen($imdb) > 2)
					{
						$imdb_id = substr($imdb, 2);
						$rt = $this->rt->call("movie_alias", array("type" => "imdb", "id" => $imdb_id));
							
						// If this movie has already been released on both platforms then continue because its a mistake
						if((!isset($rt->release_dates->theater) OR strtotime($rt->release_dates->theater) < time()) AND (!isset($rt->release_dates->dvd) OR strtotime($rt->release_dates->dvd) < time()))
						{
							continue;
						}
						
						if($movie_id = $this->movie_m->add($imdb))
						{
							echo "Processing theatrical release for movie: ".$movie_id."\n";
							if(isset($rt->release_dates->theater))
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
						
							if(isset($theater))
							{
								$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => "Theaters"));
								if($query->num_rows() > 0)
								{
									if($query->row()->date != $theater)
									{
										$this->db->where("movie_id", $movie_id);
										$this->db->where("type", "Theaters");
										$this->db->update("releases", array("date" => $theater));
									}
								}
								else
								{
									$this->db->insert("releases", array("date" => $theater, "movie_id" => $movie_id, "type" => "Theaters"));
								}
							}
							else
							{
								$this->movie_m->add_meta($movie_id, "no_theater_date", "Y");
								$theater_calls = $this->movie_m->meta($movie_id, "theater_calls");
								
								if($theater_calls)
								{
									$theater_calls++;
								}
								else
								{
									$theater_calls = 1;
								}
								
								$this->movie_m->add_meta($movie_id, "theater_calls", $theater_calls);
								
								if($theater_calls > 30)
								{
									mail("flux@b2tm.com", "Movie ".$movie_id." has exceeded 30 Theater calls", "The movie ".$movie_id." has exceeded 30 calls for checking for a Theater release date. Perhaps this film is not going to be released at the theater");
								}
							
								echo "Theater release not found for movie: ".$movie_id.". A total of ".$theater_calls." calls now\n";
							}
						
							if(isset($rt->release_dates->dvd))
							{
								echo "Processing DVD release for movie: ".$movie_id."\n";
							
								$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => "DVD"));
								if($query->num_rows() > 0)
								{
									if($query->row()->date != $rt->release_dates->dvd)
									{
										$this->db->where("movie_id", $movie_id);
										$this->db->where("type", "DVD");
										$this->db->update("releases", array("date" => $rt->release_dates->dvd));
									}
								}
								else
								{
									$this->db->insert("releases", array("date" => $rt->release_dates->dvd, "movie_id" => $movie_id, "type" => "DVD"));
								}
							}
							else
							{
								$this->movie_m->add_meta($movie_id, "no_dvd_date", "Y");
								$dvd_calls = $this->movie_m->meta($movie_id, "dvd_calls");
								if($dvd_calls)
								{
									$dvd_calls++;
								}
								else
								{
									$dvd_calls = 1;
								}
								$this->movie_m->add_meta($movie_id, "dvd_calls", $dvd_calls);
								if($dvd_calls > 30)
								{
									mail("flux@b2tm.com", "Movie ".$movie_id." has exceeded 30 DVD calls", "The movie ".$movie_id." has exceeded 30 calls for checking for a DVD release date. Perhaps this film is not going to be released on DVD");
								}
							
								echo "DVD release not found for movie: ".$movie_id.". A total of ".$dvd_calls." calls now\n";
							}
						
							if(isset($theater))
							{
								$this->system_m->add_meta("last_date_added", $theater);
								echo "Last date added is now: ".$theater."\n\n";
							}
							else
							{
								echo "\n";
							}
						
						}
					}
				}
			}
		$n++;
		}
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
			$query = $this->db->get_where("releases", array("movie_id" => $movie_id, "type" => $type));
			if($query->num_rows())
			{
				if($query->row()->date != $date)
				{
					$this->db->where("movie_id", $movie_id);
					$this->db->where("type", "Theaters");
					$this->db->update("releases", array("date" => $date));
				}
			}
			else
			{
				$this->db->insert("releases", array("date" => $date, "movie_id" => $movie_id, "type" => $type));
			}
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
				mail("flux@b2tm.com", "Movie ".$movie_id." has exceeded 30 ".$type." calls", "The movie ".$movie_id." has exceeded 30 calls for checking for a ".$type." release date. Perhaps this film is not going to be released at the ".$type);
			}
		
			echo $type." release not found for movie: ".$movie_id.". A total of ".$calls." calls now\n\n";
		}
	}
}
