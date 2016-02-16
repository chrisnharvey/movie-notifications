<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movie_m extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file')); // We use a lot of cacheing, lets load the cacheing driver
	}
	
	// Use Rotten Tomatoes to get the latest box office statistics
	function box_office($limit = 10, $country = NULL)
	{
		if($country === NULL)
			$country = $this->system_m->current_country(TRUE, TRUE);

		if(!$data = $this->cache->get("mn_box_office_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$box_office = $this->rt->call("lists/movies/box_office", array("limit" => $limit, "country" => $country)); // Call RT
			
			$rank = 1; // Set the rank to one, the array should be in order so its safe to just add one each time
			
			foreach($box_office->movies as $movie)
			{
				if(isset($movie->alternate_ids->imdb))
				{
					$this->db->where("movies_meta.key", "imdb_id");
					$this->db->where('movies_meta.value', "tt".$movie->alternate_ids->imdb);
				}
				else
				{
					$this->db->where("movies_meta.key", "rt_id");
					$this->db->where('movies_meta.value', $movie->id);
				}
				
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$query = $this->db->get("movies");
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "rank" => $rank, "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id));
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					if(isset($movie->alternate_ids->imdb))
					{
						// We have the IMDb ID, use that one.
						$row = array("rank" => $rank, "title" => $movie->title, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					}
					else
					{
						// We don't have the IMDb ID, use RT instead
						$row = array("rank" => $rank, "title" => $movie->title, "url" => site_url("movie/add/rt".$movie->id));
					}
				}
				
				array_push($data, $row);
				
				$rank++; // Add to the rank
			}
			
			$this->cache->save("mn_box_office_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}
	
	// Use Rotten Tomatoes to get the latest dvd releases
	function new_dvds($limit = 10, $country = NULL)
	{
		if($country === NULL)
			$country = $this->system_m->current_country(TRUE, TRUE);
		
		if(!$data = $this->cache->get("mn_new_dvds_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$dvds = $this->rt->call("lists/dvds/new_releases", array("page_limit" => $limit, "country" => $country)); // Call RT
	
			foreach($dvds->movies as $movie)
			{
				if(isset($movie->alternate_ids->imdb))
				{
					$this->db->where("movies_meta.key", "imdb_id");
					$this->db->where('movies_meta.value', "tt".$movie->alternate_ids->imdb);
				}
				else
				{
					$this->db->where("movies_meta.key", "rt_id");
					$this->db->where('movies_meta.value', $movie->id);
				}
				
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$query = $this->db->get("movies");
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id));
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					if(isset($movie->alternate_ids->imdb))
					{
						// We have the IMDb ID, use that one.
						$row = array("alt_id" => 'tt'.$movie->alternate_ids->imdb, "title" => $movie->title, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					}
					else
					{
						// We don't have the IMDb ID, use RT instead
						$row = array("alt_id" => 'rt'.$movie->id, "title" => $movie->title, "url" => site_url("movie/add/rt".$movie->id));
					}
				}
				
				array_push($data, $row);
			}
			
			$this->cache->save("mn_new_dvds_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}

	// Use Rotten Tomatoes to get the opening movies
	function opening($limit = 10, $country = NULL)
	{
		if($country === NULL)
			$country = $this->system_m->current_country(TRUE, TRUE);
		
		if(!$data = $this->cache->get("mn_opening_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$opening = $this->rt->call("lists/movies/opening", array("limit" => $limit, "country" => $country)); // Call RT
	
			foreach($opening->movies as $movie)
			{
				if(isset($movie->alternate_ids->imdb))
				{
					$this->db->where("movies_meta.key", "imdb_id");
					$this->db->where('movies_meta.value', "tt".$movie->alternate_ids->imdb);
				}
				else
				{
					$this->db->where("movies_meta.key", "rt_id");
					$this->db->where('movies_meta.value', $movie->id);
				}
				
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$query = $this->db->get("movies");
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "synopsis" => $this->meta($query->row()->id, "synopsis"), "poster_url" => $this->poster($query->row()->id), "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id));
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					if(isset($movie->alternate_ids->imdb))
					{
						// We have the IMDb ID, use that one.
						$row = array("alt_id" => 'tt'.$movie->alternate_ids->imdb, "synopsis" => $movie->synopsis, "poster_url" => $movie->posters->profile, "title" => $movie->title, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					}
					else
					{
						// We don't have the IMDb ID, use RT instead
						$row = array("alt_id" => 'rt'.$movie->id, "synopsis" => $movie->synopsis, "poster_url" => $movie->posters->profile, "title" => $movie->title, "url" => site_url("movie/add/rt".$movie->id));
					}
				}
				
				array_push($data, $row);
			}
			
			$this->cache->save("mn_new_dvds_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}
	
	// Use Rotten Tomatoes to get the movies in theaters
	function in_theaters($limit = 10, $country = NULL)
	{
		if($country === NULL)
			$country = $this->system_m->current_country(TRUE, TRUE);
		
		if(!$data = $this->cache->get("mn_in_theaters_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$in_theaters = $this->rt->call("lists/movies/in_theaters", array("page_limit" => $limit, "country" => $country)); // Call RT
	
			foreach($in_theaters->movies as $movie)
			{
				if(isset($movie->alternate_ids->imdb))
				{
					$this->db->where("movies_meta.key", "imdb_id");
					$this->db->where('movies_meta.value', "tt".$movie->alternate_ids->imdb);
				}
				else
				{
					$this->db->where("movies_meta.key", "rt_id");
					$this->db->where('movies_meta.value', $movie->id);
				}
				
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$query = $this->db->get("movies");
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "alt_id" => isset($movie->alternate_ids->imdb) ? 'tt'.$movie->alternate_ids->imdb : 'rt'.$movie->id, "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id));
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					if(isset($movie->alternate_ids->imdb))
					{
						// We have the IMDb ID, use that one.
						$row = array("alt_id" => 'tt'.$movie->alternate_ids->imdb, "title" => $movie->title, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					}
					else
					{
						// We don't have the IMDb ID, use RT instead
						$row = array("alt_id" => 'rt'.$movie->id, "title" => $movie->title, "url" => site_url("movie/add/rt".$movie->id));
					}
				}
				
				array_push($data, $row);
			}
			
			$this->cache->save("mn_in_theaters_".$limit."_".$country, $data, 86400); // We're done, save this to the cache for a day
		}

		return $data; // Return the data
	}
	
	// Use Rotten Tomatoes to get the top rentals
	function top_rentals($limit = 10, $country = NULL)
	{
		if($country === NULL)
			$country = $this->system_m->current_country(TRUE, TRUE);
			
		if(!$data = $this->cache->get("mn_top_rentals_".$limit."_".$country)) // Check the data is not already cached
		{
			$this->load->library("rt"); // Load the Rotten Tomatoes library
			
			$data = array(); // Set an empty $data array for the results to be pushed to
			
			$in_theaters = $this->rt->call("lists/dvds/top_rentals", array("limit" => $limit, "country" => $country)); // Call RT
	
			foreach($in_theaters->movies as $movie)
			{
				if(isset($movie->alternate_ids->imdb))
				{
					$this->db->where("movies_meta.key", "imdb_id");
					$this->db->where('movies_meta.value', "tt".$movie->alternate_ids->imdb);
				}
				else
				{
					$this->db->where("movies_meta.key", "rt_id");
					$this->db->where('movies_meta.value', $movie->id);
				}
				
				// Is the movie already in the database?
				$this->db->select("movies_meta.key, movies_meta.value, title, movies.id");
				$this->db->join("movies_meta", "movies.id = movies_meta.movie_id", 'left');
				$query = $this->db->get("movies");
				
				if($query->num_rows())
				{
					// It is, lets add this to our array
					$row = array("id" => $query->row()->id, "title" => $query->row()->title, "url" => site_url("movie/".$query->row()->id));
				}
				else
				{
					// It's not, lets add it to our array, this time with the URL to add the movie to the database
					if(isset($movie->alternate_ids->imdb))
					{
						// We have the IMDb ID, use that one.
						$row = array("alt_id" => 'tt'.$movie->alternate_ids->imdb, "title" => $movie->title, "url" => site_url("movie/add/tt".$movie->alternate_ids->imdb));
					}
					else
					{
						// We don't have the IMDb ID, use RT instead
						$row = array("alt_id" => 'rt'.$movie->id, "title" => $movie->title, "url" => site_url("movie/add/rt".$movie->id));
					}
				}
				
				array_push($data, $row);
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
	
	function data($id, $country = NULL)
	{
		if($country === NULL)
			$country = $this->session->userdata('country');
		
		$id = (int)$id;
		
		if((!$data = $this->cache->get('mn_movie_'.$id.'_'.$country)))
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
					
					if (empty($data['youtube_id']) AND isset($data['tmdb_id']))
					{
						$trailers = $this->tmdb->movie_trailers($data['tmdb_id']);

						if (isset($trailers->youtube) AND !empty($trailers->youtube))
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
					if ($call->posters->profile != 'http://images.rottentomatoescdn.com/images/redesign/poster_default.gif')
					{
		    		    $data['poster'] = TRUE;
		    		    $data['poster_url'] = $call->posters->profile;
						$this->add_meta($movie_id, "poster_url", $call->posters->detailed);
					}

					if ($call->posters->detailed != 'http://images.rottentomatoescdn.com/images/redesign/poster_default.gif')
					{
						$data['poster_url_large'] = $call->posters->detailed;
						$this->add_meta($movie_id, "poster_url_large", $call->posters->detailed);
					}
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
				$theatrical = $this->db->get_where('releases', array('movie_id' => $movie_id, "type" => "Theaters", 'country_id' => $country), 1);
				$dvd = $this->db->get_where('releases', array('movie_id' => $movie_id, "type" => "DVD", 'country_id' => $country), 1);

				/*if($country != 226 AND (isset($data['tmdb_id']) || isset($data['imdb_id'])))
				{
					if($country == 225)
					{
						// TODO: Process UK dates.
					}
					
					if(!isset($data['release_date']) OR empty($data['release_date']))
					{
						$tmdb_releases = $this->tmdb->movie_releases(isset($data['tmdb_id']) ? $data['tmdb_id'] : $data['imdb_id']);
						
						$countries = array();
						
						foreach($this->system_m->countries() as $sys_country)
						{
							if($sys_country['id'] == $country)
							{
								$country_iso = $sys_country['iso'];
								break;
							}
						}
						
						if(isset($country_iso))
						{
							foreach($tmdb_releases->countries as $release)
							{
								if($release->iso_3166_1 == $country_iso)
								{
									$this->db->insert("releases", array("movie_id" => $movie_id, "date" => $release->release_date, "type" => "Theaters", 'country_id' => $country));
									$data['release_date'] = $release->release_date;
									
									break;
								}
							}
						}
					}
				}
				else*/

				if(isset($data['imdb_id']))
				{
					$this->load->library("rt");
					$rt_call = $this->rt->call("movie_alias", array("type" => "imdb", "id" => substr($data['imdb_id'], 2)));
				
					if(empty($theatrical->row()->date) AND $country == 226)
					{
						if(isset($rt_call->release_dates->theater))
						{
							$this->db->insert("releases", array("movie_id" => $movie_id, "date" => $rt_call->release_dates->theater, "type" => "Theaters"));
							$data['release_date'] = $rt_call->release_dates->theater;
						}
					}
					elseif ( ! empty($theatrical->row()->date))
					{
						if(isset($rt_call->release_dates->theater) AND $country == 226)
						{
							$this->db->update("releases", array("date" => $rt_call->release_dates->theater), array("movie_id" => $movie_id, "country_id" => 226, "type" => "Theaters"));
							$data['release_date'] = $rt_call->release_dates->theater;
						}
						else
						{
							$data['release_date'] = $theatrical->row()->date;
						}
					}
				
					if(empty($dvd->row()->date) AND $country == 226)
					{
						if(isset($rt_call->release_dates->dvd))
						{
							$this->db->insert("releases", array("movie_id" => $movie_id, "date" => $rt_call->release_dates->dvd, "type" => "DVD"));
							$data['dvd_release_date'] = $rt_call->release_dates->dvd;
						}
					}
					elseif ( ! empty($dvd->row()->date))
					{
						if(isset($rt_call->release_dates->dvd) AND $country == 226)
						{
							$this->db->insert("releases", array("movie_id" => $movie_id, "date" => $rt_call->release_dates->dvd, "type" => "DVD"));
							$data['dvd_release_date'] = $rt_call->release_dates->dvd;
						}
						else
						{
							$data['dvd_release_date'] = $dvd->row()->date;
						}
					}
				}
				
				if(empty($data['release_date']))
					$data['release_date'] = NULL;
				
				if(empty($data['release_date']))
					$data['dvd_release_date'] = NULL;
				
				
				// BUY LINKS
				if (isset($data['imdb_id']))
				{
					$this->load->library("IVA");
					$this->load->config("affiliate");
					
					$data['buy_links'] = array();
				
					$iva_id = $this->meta($movie_id, "iva_id");
					
					if ( ! $iva_id)
					{
						$iva = $this->iva->call("Video/Pinpoint", array("IdType" => "12", "SearchTerm" => $data['imdb_id']));
						if ( ! isset($iva->item->Error) && isset($iva->item))
						{
							$iva_id = $iva->item->PublishedId;
							$this->add_meta($movie_id, "iva_id", $iva_id);
						}
						else
						{
							$iva_id = NULL;
						}
					}
							
					if ($iva_id)
					{
						$iva = $this->iva->call("Common/Shopping", array("idtypes" => "13:".$this->config->item("amazon").",18:".$this->config->item("fandango"), "searchterm" => $iva_id));
						
						foreach ($iva->item as $item)
						{
							if ($item->IdType == 18)
							{
								if ( ! isset($buy_tickets))
								{
									$type = 'tickets';
								}
								else
								{
									continue;
								}
							}
							elseif ($item->IdType == 13)
							{
								if ($item->Binding == "DVD")
								{
									if ( ! isset($buy_dvd))
									{
										$type = 'dvd';
									}
									else
									{
										continue;
									}
								}
								elseif ($item->Binding == "Blu-ray")
								{
									if ( ! isset($buy_blu_ray))
									{
										$type = 'blu-ray';
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

							$data['buy_links'][$type] = $url;
						}
					}
					
				}
				
				if($tmdb OR $rt)
					$this->cache->save('mn_movie_'.$id.'_'.$country, $data, 86400);
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
		elseif(substr($id, 0, 2) != "rt")
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

				if (isset($call->status_code))
					return FALSE;
			}
		}
		else
		{
			$rt_id = TRUE;
		}
		
		/*if(isset($call))
		{
			$call = $call[0];
		}*/
		
		if(((isset($call->status_code) OR ! $call) AND $call_id_used == "imdb") || isset($rt_id))
		{
			// Fall back to RT
			$add = "rt";
			
			$this->load->library("rt");
			
			if(isset($rt_id))
			{
				$call = $this->rt->call("movies/".substr($id, 2));
			}
			else
			{
				$call = $this->rt->call("movie_alias", array("type" => "imdb", "id" => substr($id, 2)));
			}
						
			if(!$call || !is_object($call) || isset($call->error))
			{
				return FALSE;
			}
		}
		elseif ( ! $call OR !is_object($call) OR isset($call->status_code))
		{
			return FALSE;
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
			if(isset($call->alternate_ids->imdb))
			{
				$imdb = $this->db->get_where("movies_meta", array("key" => "imdb_id", "value" => $call->alternate_ids->imdb));
			}
			
			$rt_query = $this->db->get_where("movies_meta", array("key" => "rt_id", "value" => $call->id));
		}
		
		if(isset($imdb) AND $imdb->num_rows())
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
		elseif($rt_query->num_rows())
		{
			return $rt_query->row()->movie_id;
		}
		
		if($add == "tmdb")
		{
		    $title = $call->title;
			$synopsis = $call->overview;
			$imdb_id = $call->imdb_id;
			
			if(strlen($imdb_id) <= 2)
				$imdb_id = NULL;
			
			$tmdb_id = $call->id;
			$rt_id = NULL;
		}
		elseif($add == "rt")
		{
			$title = $call->title;
			$synopsis = $call->synopsis;
			$rt_id = $call->id;
			$imdb_id = isset($call->alternate_ids->imdb) ? "tt".$call->alternate_ids->imdb : NULL;
			$tmdb_id = NULL;
		}
		
		if($add)
		{
			$this->db->trans_begin();
		    $this->db->insert("movies", array("title" => $title));
		    $movie_id = $this->db->insert_id();
			
			$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "synopsis", "value" => $synopsis));
			
			if($add == "rt")
			{
				$poster_url = $call->posters->profile;
				$poster_url_large = $call->posters->detailed;
				
				if ($poster_url != 'http://images.rottentomatoescdn.com/images/redesign/poster_default.gif' AND $poster_url_large != 'http://images.rottentomatoescdn.com/images/redesign/poster_default.gif')
				{
					$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url", "value" => $poster_url));
					$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url_large", "value" => $poster_url_large));
				}
			}
			else
			{
				if ($call->poster_path != NULL)
				{
					$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url", "value" => $this->config->item('image_base_url').'w92'.$call->poster_path));
					$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "poster_url_large", "value" => $this->config->item('image_base_url').'w185'.$call->poster_path));
				}
			/*
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
			*/
			}
			
			if(!empty($rt_id) || !is_null($rt_id))
		    	$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "rt_id", "value" => $rt_id));
			
		    if(!empty($imdb_id) || !is_null($imdb_id))
		    	$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "imdb_id", "value" => $imdb_id));
		    
			if(!empty($tmdb_id) || !is_null($tmdb_id))
		    	$this->db->insert("movies_meta", array("movie_id" => $movie_id, "key" => "tmdb_id", "value" => $call->id));
			
		    if((empty($tmdb_id) || is_null($tmdb_id)) AND (empty($imdb_id) || is_null($imdb_id)) AND (empty($rt_id) || is_null($rt_id)) || $this->db->trans_status() === FALSE)
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
	
	function search($query)
	{
		$this->load->library("rt");
		
		$search = $this->rt->call('movies', array(
			'q' => urlencode($query),
			'page_limit' => 15,
			'page' => $this->input->get('page') ? $this->input->get('page') : 1
		));

		$results = array();
		
		if (empty($search->movies))
			return array();
		
		foreach($search->movies as $result)
		{
			if ( ! isset($result->alternate_ids->imdb))
				continue;

			//$info = $this->tmdb->movie_info($result->id);
			$this->db->select("movies_meta.key, movies_meta.value, movies.id, movies.title");
			$this->db->from("movies_meta");
			$this->db->where("movies_meta.key", "tmdb_id");
			$this->db->where("movies_meta.value", 'tt'.$result->alternate_ids->imdb);
			$this->db->join('movies', 'movies.id = movies_meta.movie_id', "inner");
			$query = $this->db->get();
		
			if ($query->num_rows())
			{
				$id = $query->row()->id;
				$title = $query->row()->title;
				
				$release = $this->db->get_where('releases', array('movie_id' => $id, 'type' => 'Theaters', 'country_id' => 226));
				
				if ($release->num_rows())
				{
					$release_time = strtotime($release->row()->date);

					if ($release_time < strtotime('-5 years'))
						continue;

					$release_year = date('Y', $release_time);
				}
				else
				{
					$release_year = NULL;
				}
				
				array_push($results, array("id" => $id, "title" => $title, "url" => site_url("movie/".$id), "poster_url" => $this->poster($id), "synopsis" => $this->meta($id, 'synopsis'), "year" => $release_year));
			}
			else
			{
				$alt_id = 'tt'.$result->alternate_ids->imdb;
				$title = $result->title;
				$poster = '/images/noposter.png';
				
				if($result->posters->profile != NULL AND $result->posters->profile != 'http://images.rottentomatoescdn.com/images/redesign/poster_default.gif')
					$poster = $result->posters->profile;

				$release_time = isset($result->release_dates->theater) ? strtotime($result->release_dates->theater) : NULL;

				if ($release_time < strtotime('-5 years') AND ! empty($release_time))
					continue;

				$year = ! empty($release_time) ? date('Y', $release_time) : NULL;

				array_push($results, array("alt_id" => $alt_id, "title" => $title, "poster_url" => $poster, "url" => site_url("movie/add/".$alt_id), "synopsis" => $result->synopsis, "year" => $year));
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
