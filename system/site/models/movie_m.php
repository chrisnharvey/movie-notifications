<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movie_m extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file')); // We use a lot of cacheing, lets load the cacheing driver
	}
	
	// Use Rotten Tomatoes to get the latest box office statistics
	function box_office($limit = 10, $country = "us")
	{
		if(!$data = $this->cache->get("mn_box_office_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$box_office = $this->rt->call("lists/movies/box_office", array("limit" => $limit, "country" => $country)); // Call RT
			
			$rank = 1; // Set the rank to one, the array should be in order so its safe to just add one each time
			
			foreach($box_office->movies as $movie)
			{
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$this->db->where("movies_meta.key", "imdb_id");
				$query = $this->db->get_where("movies", array("movies_meta.value" => "tt".$movie->alternate_ids->imdb));
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "rank" => $rank, "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id));
					array_push($data, $row);
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					$row = array("rank" => $rank, "title" => $movie->title, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					array_push($data, $row);
				}
				
				$rank++; // Add to the rank
			}
			
			$this->cache->save("mn_box_office_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}
	
	// Use Rotten Tomatoes to get the latest dvd releases
	function new_dvds($limit = 10, $country = "us")
	{
		if(!$data = $this->cache->get("mn_new_dvds_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$dvds = $this->rt->call("lists/dvds/new_releases", array("page_limit" => $limit, "country" => $country)); // Call RT
	
			foreach($dvds->movies as $movie)
			{
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$this->db->where("movies_meta.key", "imdb_id");
				$query = $this->db->get_where("movies", array("movies_meta.value" => "tt".$movie->alternate_ids->imdb));
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "title" => $query->row()->title, "poster_url" => $this->poster($query->row()->id), "url" => site_url("movie/".$query->row()->id));
					array_push($data, $row);
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					$row = array("alt_id" => 'tt'.$movie->alternate_ids->imdb, "title" => $movie->title, "poster_url" => $movie->posters->profile, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					array_push($data, $row);
				}
			}
			
			$this->cache->save("mn_new_dvds_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}

	// Use Rotten Tomatoes to get the opening movies
	function opening($limit = 10, $country = "us")
	{
		if(!$data = $this->cache->get("mn_opening_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$opening = $this->rt->call("lists/movies/opening", array("limit" => $limit, "country" => $country)); // Call RT
	
			foreach($opening->movies as $movie)
			{
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$this->db->where("movies_meta.key", "imdb_id");
				$query = $this->db->get_where("movies", array("movies_meta.value" => "tt".$movie->alternate_ids->imdb));
				
				if($query->num_rows())
				{
					// It is, lets add it to our array
					$row = array("id" => $query->row()->id, "synopsis" => $this->meta($query->row()->id, "synopsis"), "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id), "poster_url" => $this->poster($query->row()->id));
					array_push($data, $row);
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					$row = array("title" => $movie->title, "synopsis" => $movie->synopsis, "poster_url" => $movie->posters->profile, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					array_push($data, $row);
				}
			}
			
			$this->cache->save("mn_new_dvds_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}
	
	// Use Rotten Tomatoes to get the movies in theaters
	function in_theaters($limit = 10, $country = "us")
	{
		if(!$data = $this->cache->get("mn_in_theaters_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$in_theaters = $this->rt->call("lists/movies/in_theaters", array("page_limit" => $limit, "country" => $country)); // Call RT
	
			foreach($in_theaters->movies as $movie)
			{
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$this->db->where("movies_meta.key", "imdb_id");
				$query = $this->db->get_where("movies", array("movies_meta.value" => "tt".$movie->alternate_ids->imdb));
				
				if($query->num_rows())
				{
					// It is, lets add it to our array
					$row = array("id" => $query->row()->id, "alt_id" => "tt".$movie->alternate_ids->imdb, "synopsis" => $this->meta($query->row()->id, "synopsis"), "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id), "poster_url" => $this->poster($query->row()->id));
					array_push($data, $row);
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					$row = array("alt_id" => "tt".$movie->alternate_ids->imdb, "title" => $movie->title, "synopsis" => $movie->synopsis, "poster_url" => $movie->posters->profile, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					array_push($data, $row);
				}
			}
			
			$this->cache->save("mn_in_theaters_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}
	
	// Use Rotten Tomatoes to get the top rentals
	function top_rentals($limit = 10, $country = "us")
	{
		if(!$data = $this->cache->get("mn_top_rentals_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$in_theaters = $this->rt->call("lists/dvds/top_rentals", array("limit" => $limit, "country" => $country)); // Call RT
	
			foreach($in_theaters->movies as $movie)
			{
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$this->db->where("movies_meta.key", "imdb_id");
				$query = $this->db->get_where("movies", array("movies_meta.value" => "tt".$movie->alternate_ids->imdb));
				
				if($query->num_rows())
				{
					// It is, lets add it to our array
					$row = array("id" => $query->row()->id, "alt_id" => "tt".$movie->alternate_ids->imdb, "synopsis" => $this->meta($query->row()->id, "synopsis"), "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id), "poster_url" => $this->poster($query->row()->id));
					array_push($data, $row);
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					$row = array("alt_id" => "tt".$movie->alternate_ids->imdb, "title" => $movie->title, "synopsis" => $movie->synopsis, "poster_url" => $movie->posters->profile, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					array_push($data, $row);
				}
			}
			
			$this->cache->save("mn_top_rentals_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}

	// Use TMDb to get a list of images for a specific TMDb ID or IMDb ID
	function get_images($id, $limit, $type = "backdrops")
	{
		if(!$data = $this->cache->get("mn_movie_images_".$id."_".$limit."_".$type))
		{
			$this->load->library("TMDb");
			
			$data = array();
			
			$images = $this->tmdb->movie_images($id);
			if(!empty($images))
			{
				$data = array();
			
				$count = 1;

				if(isset($images->$type)) // Check that we have that type
				{
					foreach($images->$type as $image)
					{
						
						if($count < $limit)
						{
							//$image = $image->image;
							
							//if($image->size == "poster")
							//{
								$thumb = $this->config->item('image_base_url').'w300'.$image->file_path;
								$large = $this->config->item('image_base_url').'w780'.$image->file_path;								
								
								array_push($data, array("large" => $large, "thumb" => $thumb));
								$count++;
							//}
						}
						else
						{
							break;
						}
					}
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
			
			
			$this->cache->save("mn_movie_images_".$id."_".$limit."_".$type, $data, 86400);
		}

		return $data;
	}
	
	function poster($id)
	{
		if($poster = $this->meta($id, "poster_url"))
		{
			return $poster;
		}
		else
		{
			return site_url("/images/noposter.png");
		}
	}

	function meta($id, $meta_key)
	{
		$id = (int)$id;
		$meta_key = xss_clean($meta_key);
		
		if($this->exists($id))
		{
			if(!$data = $this->cache->get("mn_movie_meta_".$id."_".$meta_key)) // Check the data is not already cached
			{
				$this->db->where("key", $meta_key);
				$query = $this->db->get_where('movies_meta', array('movie_id' => $id));
				
				if(!$query->num_rows())
				{
					return FALSE;
				}
				else
				{
					$data = $query->row()->value;
					$this->cache->save("mn_movie_meta_".$id."_".$meta_key, $data, 86400); // We're done, save this to the cache for a day
				}
			}
			
			
			
			return $data;
		}
		else
		{
			return FALSE;
		}
	}
	
	function exists($id)
	{
		$id = (int)$id;

		if(!$this->cache->get("mn_movie_exists_".$id))
		{
			$movie = $this->db->get_where("movies", array("id" => $id))->num_rows();
			if($movie)
			{
				$this->cache->save("mn_movie_exists_".$id, TRUE, 86400);
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	function data($id)
	{
		$id = (int)$id;
			
		if((!$data = $this->cache->get('mn_movie_'.$id)))
		{	
			$movie_id = $id;
			
			$query = $this->db->get_where('movies', array('id' => $movie_id), 1);
			if($query->num_rows()){
				$data['id'] = $query->row()->id;
				$data['title'] = $query->row()->title;
			
				// METADATA
				$query = $this->db->get_where('movies_meta', array('movie_id' => $movie_id));
			
				foreach($query->result() as $row)
					$data[$row->key] = $row->value;
			
				if(empty($data['runtime']))
					$data['runtime'] = NULL;
			
				if(empty($data['synopsis']))
					$data['synopsis'] = NULL;
			
				if(empty($data['tagline']))
					$data['tagline'] = NULL;
			
				// Set default rating and votes to 0 before calling TMDb (in case the movie can't be found on TMDb)
				$data['rating']['rating'] = 0;
				$data['rating']['votes'] = 0;
			
				$data['poster'] = FALSE;
				$data['poster_url'] = '/images/noposter.png';
				$data['poster_url_large'] = '/images/noposter.png';
				
				$this->load->library("TMDb");
				
				// Call TMDb API
				if(isset($data['tmdb_id']))
				{
					$tmdb = $this->tmdb->movie_info($data['tmdb_id']); // Use TMDb ID from previous call to retrieve more info
					//$tmdb = $tmdb[0];
					if(isset($tmdb->status_code))
						$tmdb = FALSE;
				}
				elseif(isset($data['imdb_id']))
				{
					$tmdb = $this->tmdb->movie_info($data['imdb_id']); // Get film by IMDb ID
					//$tmdb = $tmdb[0];
					if(isset($tmdb->status_code))
						$tmdb = FALSE;
				}
				else
				{
					$tmdb = FALSE;
				}
				
				if($tmdb)
				{
					if(empty($data['runtime']))
					{
						if(isset($data['tmdb_id']) || isset($data['imdb_id']))
						{
							$data['runtime'] = @$tmdb->runtime;
						}
						else
						{
							$data['runtime'] = NULL;
						}
					}
				
					if((isset($data['tmdb_id']) || isset($data['imdb_id']) AND !empty($tmdb->overview)))
					{
						$data['synopsis'] = $tmdb->overview;
						$this->add_meta($movie_id, "synopsis", $tmdb->overview);
					}
					else
					{
						$data['synopsis'] = NULL;
					}
				
					if(empty($data['tagline']))
					{
						if((isset($data['tmdb_id']) || isset($data['imdb_id'])) AND !empty($tmdb->tagline))
						{
							$data['tagline'] = $tmdb->tagline;
						}
						else
						{
							$data['tagline'] = NULL;
						}
					}
				
					if(isset($data['tmdb_id']) || isset($data['imdb_id']))
					{
						$data['rating']['rating'] = @$tmdb->rating;
						$data['rating']['votes'] = @$tmdb->votes;
				    }
				    else
				    {
				    	$data['rating']['rating'] = 0;
				    	$data['rating']['votes'] = 0;
				    }
					
					if(empty($data['youtube_id']))
					{
						$trailers = $this->tmdb->movie_trailers($data['tmdb_id']);

						if(isset($trailers->youtube) AND !empty($trailers->youtube))
						{
							$data['youtube_id'] = $trailers->youtube[0]->source;
							$this->add_meta($id, 'youtube_id', $data['youtube_id']);
						}
					}
				
					// POSTERS
					if(isset($data['tmdb_id']) OR isset($data['imdb_id']))
					{
						if($tmdb->poster_path != NULL)
						{
							$data['poster_url'] = $this->config->item('image_base_url').'w92'.$tmdb->poster_path;
							$this->add_meta($movie_id, "poster_url", $data['poster_url']);
							
							$data['poster_url_large'] = $this->config->item('image_base_url').'w185'.$tmdb->poster_path;
							$this->add_meta($movie_id, "poster_url_large", $this->config->item('image_base_url').'w185'.$tmdb->poster_path);
						}
					}
			
				}
				elseif(isset($data['imdb_id']))
				{
					$rt = TRUE;
					
					$this->load->library('rt');
					$call = $this->rt->call('movie_alias', array('id' => substr($data['imdb_id'], 2), 'type' => 'imdb'));

					if(empty($data['runtime'])){
						$data['runtime'] = @$call->runtime;
					}
				
					$data['synopsis'] = @$call->synopsis;
					$this->add_meta($movie_id, "synopsis", @$call->synopsis);
				
					if(empty($data['tagline'])){
						$data['tagline'] = NULL;
					}
					
			    	$data['rating']['rating'] = 0;
			    	$data['rating']['votes'] = 0;
				
					// POSTER
	    		    $data['poster'] = TRUE;
	    		    $data['poster_url'] = $call->posters->profile;
					$this->add_meta($movie_id, "poster_url", $call->posters->detailed);
					
					$data['poster_url_large'] = $call->posters->detailed;
					$this->add_meta($movie_id, "poster_url_large", $call->posters->detailed);
					
				}
				
				// If the RT variable is empty, set it to FALSE.
				if(!isset($rt))
					$rt = FALSE;
			
				// APPEND MINUTES TO RUNTUME
				if(!empty($data['runtime']))
				{
					$data['runtime'] .= " minutes";
				
				}
				else
				{
					$data['runtime'] = NULL;
				}
			
				// RELEASE DATE
				$theatrical = $this->db->get_where('releases', array('movie_id' => $movie_id, "type" => "Theaters"), 1);
				$dvd = $this->db->get_where('releases', array('movie_id' => $movie_id, "type" => "DVD"), 1);
			
				if(($theatrical->num_rows() < 1) || ($dvd->num_rows() < 1))
				{
					if(isset($data['imdb_id']))
					{
						$this->load->library("rt");
						$rt_call = $this->rt->call("movie_alias", array("type" => "imdb", "id" => substr($data['imdb_id'], 2)));
					
						if(empty($theatrical->row()->date))
						{
							if(isset($rt_call->release_dates->theater))
							{
								$this->db->insert("releases", array("movie_id" => $movie_id, "date" => $rt_call->release_dates->theater, "type" => "Theaters"));
								$data['release_date'] = $rt_call->release_dates->theater;
							}
						}
						else
						{
							$data['release_date'] = $theatrical->row()->date;
						}
					
						if(empty($dvd->row()->date))
						{
							if(isset($rt_call->release_dates->dvd))
							{
								$this->db->insert("releases", array("movie_id" => $movie_id, "date" => $rt_call->release_dates->dvd, "type" => "DVD"));
								$data['dvd_release_date'] = $rt_call->release_dates->dvd;
							}
						}
						else
						{
							$data['dvd_release_date'] = $dvd->row()->date;
						}
					}
				}
				else
				{
					$data['release_date'] = $theatrical->row()->date;
					$data['dvd_release_date'] = $dvd->row()->date;
				}
				
				if(empty($data['release_date']))
					$data['release_date'] = NULL;
				
				/*
				// BUY LINKS
				if(isset($data['imdb_id']))
				{
					$this->load->library("IVA");
					$this->load->config("affiliate");
					
					$data['buy_links'] = array();
				
					$iva_id = $this->meta($movie_id, "iva_id");
					
					
					if(!$iva_id)
					{
						$iva = $this->iva->call("Video/Pinpoint", array("IdType" => "12", "SearchTerm" => $data['imdb_id']));
						if((!isset($iva->item->Error) && isset($iva->item)))
						{
							$iva_id = $iva->item->PublishedId;
							$this->add_meta($movie_id, "iva_id", $iva_id);
						}
						else
						{
							$iva_id = NULL;
						}
					}
							
					if($iva_id)
					{
						$iva = $this->iva->call("Common/Shopping", array("idtypes" => "13:".$this->config->item("amazon").",18:".$this->config->item("fandango"), "searchterm" => $iva_id));
						
						foreach($iva->item as $item)
						{
							if($item->IdType == 18)
							{
								if(!isset($buy_tickets))
								{
									$name = "Buy Tickets";
									$url = $item->PurchaseUrl->asXML();
									$buy_tickets = TRUE;
								}
								else
								{
									continue;
								}
							}
							elseif($item->IdType == 13)
							{
								if($item->Binding == "DVD")
								{
									if(!isset($buy_dvd))
									{
										$name = "Buy DVD";
										$buy_dvd = TRUE;
									}
									else
									{
										continue;
									}
								}
								elseif($item->Binding == "Blu-ray")
								{
									if(!isset($buy_blu_ray))
									{
										$name = "Buy Blu-ray";
										$buy_blu_ray = TRUE;
									}
									else
									{
										continue;
									}
								}
								else
								{
									continue;
								}
								$url = $item->PurchaseUrl->asXML();
							}
							else
							{
								continue;
							}

							$url = substr($url, 13);
							$url = substr($url, 0, -14);

							array_push($data['buy_links'], array('name' => $name, "url" => $url));
						}
					}
					
				}*/
				
				if($tmdb OR $rt)
					$this->cache->save('mn_movie_'.$id, $data, 86400);
			}	
		}
		
		return $data;
	}
	
	function add($id)
	{
		$this->load->library("TMDb");
		$this->load->config("tmdb");
				
		if(substr($id, 0, 2) == "tt") // Check if this is an IMDb ID
		{
			if(strlen($id) > 2)
			{
				$query = $this->db->get_where("movies_meta", array("key" => "imdb_id", "value" => $id));
				if($query->num_rows != 0)
				{
					return $query->row()->movie_id;
				}
				else
				{
					$call = $this->tmdb->movie_info($id);
					$call_id_used = "imdb"; // Set the type of ID used, if its IMDb and the call fails, fallback to RT
				}
			}
		}
		else
		{
			$query = $this->db->get_where("movies_meta", array("key" => "tmdb_id", "value" => $id));
			if($query->num_rows != 0)
			{
				return $query->row()->movie_id;
			}
			else
			{
				$call = $this->tmdb->movie_info($id);
				$call_id_used = "tmdb";
				if(strlen($call[0]->imdb_id) <= 2)
				{
					$call = NULL;
				}
			}
		}
		
		/*if(isset($call))
		{
			$call = $call[0];
		}*/
		
		if((isset($call->status_code)) AND $call_id_used == "imdb")
		{
			// Fall back to RT
			$add = "rt";
			
			$this->load->library("rt");
			
			$call = $this->rt->call("movie_alias", array("type" => "imdb", "id" => substr($id, 2)));
			
			if(!$call || !is_object($call))
			{
				return FALSE;
			}
		}
		else
		{
			$add = "tmdb";
		}
		
		if($add == "tmdb")
		{
			$imdb = $this->db->get_where("movies_meta", array("key" => "imdb_id", "value" => $call->imdb_id));
			$tmdb = $this->db->get_where("movies_meta", array("key" => "tmdb_id", "value" => $call->id));
		}
		else
		{
			$imdb = $this->db->get_where("movies_meta", array("key" => "imdb_id", "value" => $call->alternate_ids->imdb));
		}
		
		if($imdb->num_rows())
		{
		    return $imdb->row()->movie_id;
		}
		elseif($add == "tmdb")
	    {
	    	if($tmdb->num_rows())
			{
	    		return $tmdb->row()->movie_id;
			}
	    }
		
		if($add == "tmdb")
		{
		    $title = $call->title;
			$synopsis = $call->overview;
			$imdb_id = $call->imdb_id;
			
			if(strlen($imdb_id) <= 2)
			{
				$imdb_id = NULL;
			}
			
			$tmdb_id = $call->id;
		}
		elseif($add == "rt")
		{
			$title = $call->title;
			$synopsis = $call->synopsis;
			$imdb_id = "tt".$call->alternate_ids->imdb;
			$tmdb_id = NULL;
		}
		
		if($add)
		{
			$this->db->trans_start();
		    $this->db->insert("movies", array("title" => $title));
		    $movie_id = $this->db->insert_id();
			
			$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "synopsis", "value" => $synopsis));
			
			if($add == "rt")
			{
				$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url", "value" => $call->posters->profile));
				$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url_large", "value" => $call->posters->detailed));
			}
			else
			{
				// Get small poster
				foreach($call->posters as $poster)
				{
					if($poster->image->size == "thumb" && $poster->image->type == "poster")
					{
						$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url", "value" => $poster->image->url));
						break;
					}
				}
				
				// Get large poster
				foreach($call->posters as $poster)
				{
					if($poster->image->size == "cover" && $poster->image->type == "poster")
					{
						$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url_large", "value" => $poster->image->url));
						break;
					}
				}
			}
			
		    if(!empty($imdb_id) OR !is_null($imdb_id))
		    	$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "imdb_id", "value" => $imdb_id));
		    
			if(!empty($tmdb_id) || !is_null($tmdb_id))
		    	$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "tmdb_id", "value" => $call->id));
			
		    if((empty($tmdb_id) OR is_null($tmdb_id)) && (empty($imdb_id) OR is_null($imdb_id)) OR $this->db->trans_status() === FALSE)
			{
			    $this->db->trans_rollback();
			}
			else
			{
			    $this->db->trans_commit();
			}
			
			return $movie_id;
		}
	}
	
	function add_meta($movie_id, $meta_key, $meta_value, $update = TRUE)
	{
    
		if($this->exists($movie_id))
		{
			$insert_array = array("key" => $meta_key, "value" => $meta_value, "movie_id" => $movie_id);
			$update_array = array("value" => $meta_value);
    
			if($update === FALSE)
			{
				$this->db->insert("movies_meta", $insert_array);
			}
			else
			{
				$query = $this->db->where("key", $meta_key);
				$query = $this->db->get_where('movies_meta', array('movie_id' => $movie_id));
    
				if($query->num_rows() < 1)
				{
					$this->db->insert("movies_meta", $insert_array);
				}
				else
				{
					$this->db->where("movie_id", $movie_id);
					$this->db->where("key", $meta_key);
					$this->db->update("movies_meta", $update_array);
				}
				return TRUE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function can_notify($id, $type = 'theaters')
	{
		if($type == 'theaters')
		{
			$type = 'Theaters';
		}
		else
		{
			$type = 'DVD';
		}
		
		$movie = $this->db->get_where('releases', array('movie_id' => $id, 'type' => $type));
		
		if(!$movie->num_rows())
		{
			if($type == 'DVD')
			{
				$theaters = $this->db->get_where('releases', array('movie_id' => $id, 'type' => 'Theaters'));
				if($theaters->num_rows())
				{
					if(strtotime($theaters->row()->date) < strtotime('-2 years'))
					{
						return FALSE;
					}
				}
			}
			else
			{
				$dvd = $this->db->get_where('releases', array('movie_id' => $id, 'type' => 'DVD'));
				if($dvd->num_rows())
				{
					return FALSE;
				}
			}
			
			return TRUE;
		}

		$date = strtotime($movie->row()->date);
		$today = time();
		
		if($date < $today)
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	function search($query)
	{
		$this->load->library("TMDb");
		$this->load->config("tmdb");
		
		$query = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);
		$query = str_replace(" ", "+", $query);
		
		// TODO: Make this use Rotten Tomatoes instead of TMDb... TMDb requires two calls for searching to get the data we need
		$search = $this->tmdb->search_movies($query);

		$results = array();
		
		if(empty($search->results))
			return array();
		
		foreach($search->results as $result)
		{
			//$info = $this->tmdb->movie_info($result->id);
			
			$this->db->select("movies_meta.key, movies_meta.value, movies.id, movies.title");
			$this->db->from("movies_meta");
			$this->db->where("movies_meta.key", "imdb");
			$this->db->where("movies_meta.value", $info->imdb_id);
			$this->db->join('movies', 'movies.id = movies_meta.movie_id', "INNER");
			$query = $this->db->get();
		
			if($query->num_rows())
			{
				$id = $query->row()->id;
				$title = $query->row()->title;
				
				$release = $this->db->get_where('releases', array('movie_id' => $id, 'type' => 'Theaters'));
				
				$release_year = date('Y', strtotime($release->row()->date));
				
				array_push($results, array("id" => $id, "title" => $title, "url" => site_url("movie/".$id), "poster_url" => $this->poster($id), "synopsis" => $this->meta($id, 'synopsis'), "year" => $release_year));
			}
			else
			{
				$alt_id = $result->id;
				$title = $result->title;
				$poster = '/images/noposter.png';
				
				if($result->poster_path != NULL)
					$poster = $this->config->item('image_base_url').'w93'.$result->poster_path;
				
				array_push($results, array("alt_id" => $alt_id, "title" => $title, "poster_url" => $poster, "url" => site_url("movie/add/".$alt_id), "synopsis" => $result->overview, "year" => date('Y', strtotime($result->released))));
			}
		}
		
		if(!empty($results))
		{
			return $results;
		}
		else
		{
			return array();
		}
	}
}
