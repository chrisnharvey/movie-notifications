<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ratings_m extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
	}
	
	function recent($limit = 10)
	{
		if(!$data = $this->cache->get("mn_ratings_recent_".$limit))
		{
			$this->db->select("ratings_recent.id, movies.id as movie_id, movies.title, users.id as user_id, users.username, ratings_recent.rating");
			$this->db->join("movies", "movies.id = ratings_recent.movie_id", "left");
			$this->db->join("users", "users.id = ratings_recent.user_id", "left");
			$query = $this->db->get("ratings_recent");
			
			$data = array();
			
			if($query->num_rows())
			{
				foreach($query->result() as $row)
				{
					$rating['id'] = $row->id;
					$rating['rating'] = $row->rating;
					
					$rating['movie']['id'] = $row->movie_id;
					$rating['movie']['title'] = $row->title;
					$rating['movie']['url'] = site_url("movie/".$rating['movie']['id']);
					
					$rating['user']['id'] = $row->user_id;
					$rating['user']['username'] = $row->username;
					$rating['user']['profile_url'] = site_url("user/".$rating['user']['username']);
					
					array_push($data, $rating);
					
				}
			}

			if(!empty($data))
			{
				$this->cache->save("mn_ratings_recent_".$limit, $data, 3600);
			}
		}
		
		return $data;
	}
}
