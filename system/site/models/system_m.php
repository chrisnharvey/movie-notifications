<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_m extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	
	public function meta($meta_key)
	{
		$meta_key = xss_clean($meta_key);
		
		if(!$data = $this->cache->get("mn_meta_".$meta_key)) // Check the data is not already cached
		{
			$query = $this->db->get_where('meta', array('key' => $meta_key));
			
			if(!$query->num_rows() OR $query->row()->value == NULL)
			{
				return FALSE;
			}
			else
			{
				$data = $query->row()->value;
			}
			
			$this->cache->save("mn_meta_".$meta_key, $data, 86400); // We're done, save this to the cache for a day
		}
		
		return $data;
	}
	
	public function add_meta($meta_key, $meta_value)
	{
		$insert_array = array("key" => $meta_key, "value" => $meta_value);
		$update_array = array("value" => $meta_value);

		$query = $this->db->get_where('meta', array('key' => $meta_key));
				
		if(!$query->num_rows())
		{
			$this->db->insert("meta", $insert_array);
		}
		else
		{
			$this->db->where("key", $meta_key);
			$this->db->update("meta", $update_array);
		}
		
		$this->cache->delete("mn_meta_".$meta_key); // Delete the cached file (if any)
		
		return TRUE;
	}
	
	public function get_countries($enabled_only = TRUE)
	{
		$cache = 'mn_countries';
		
		if($enabled_only)
			$cache .= '_eo';
		
		if(!$data = $this->cache->get($cache))
		{
			if($enabled_only)
				$this->db->where('enabled', 1);
			
			$query = $this->db->get('countries');
			
			foreach($query->result() as $row)
			{
				$data[$row->short] = $row->country_id;
			}
			
			$this->cache->save($cache, $data, 86400);
		}
		
		return $data;
	}
	
	public function countries()
	{
		return array(
			0 => array(
				'id' => 225,
				'name' => 'United Kingdom',
				'iso' => 'GB'
			),
			1 => array(
				'id' => 226,
				'name' => 'United States',
				'iso' => 'US'
			)
		);
	}

	public function current_country($iso = FALSE, $uk_for_gb = FALSE)
	{
		$current_id = $this->session->userdata('country');
		if(!$iso)
			return $current_id;
		
		$search = array_search($current_id, $this->get_countries());
		
		if($search)
		{
			if($search == 'GB' AND $uk_for_gb)
				return 'UK';
			
			return $search;
		}
		
		return FALSE;
	}
}
