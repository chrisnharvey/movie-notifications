<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Page Library
 * 
 * Author: Chris Harvey (Movie Notifications)
 * Website: http://www.movienotifications.com/
 * Email: chris.harvey@movienotifications.com
 *
 * This library helps render view files with templates
 * 
 **/
 
class Page {
	
	private $CI = NULL;
	
	private $meta = array("header" => "", "footer" => "");
	
	public $doctype = "xhtml1-strict";
	
	public $title = NULL;
	
	public $append_title = " | Movie Notifications";
	
	public $full_title = NULL;
	
	public $layout = "default";
	
	private $data = array();
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	public function append_meta($data, $location = "header")
	{
		if(array_key_exists($location, $this->meta))
		{
			$this->meta[$location] .= $data;
		}
		else
		{
			$this->meta[$location] = $data;
		}
	}
	
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	public function json($array)
	{
		$data['page']['view'] = json_encode($array);
		$this->CI->load->view("assets/echo", $data);
	}
	
	public function show($page, $data = array(), $root = FALSE)
	{
		$data['page']['doctype'] = $this->doctype;
		$data['page']['title'] = ($this->title !== NULL) ? $this->title . $this->append_title : NULL;
		
		if($this->full_title !== NULL)
		{
			$data['page']['title'] = $this->full_title;
		}
		
		if($root)
		{
			$data['page']['view'] = $page;
		}
		else
		{
			$data['page']['view'] = 'pages/' . $page;
		}
		$data['meta'] = $this->meta;
		
		$data = array_merge($this->data, $data);
		
		$this->CI->load->view("layouts/".$this->layout, $data);
	}
	
}	