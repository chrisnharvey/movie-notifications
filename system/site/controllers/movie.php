<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movie extends Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('movie_m');
	}
	
	function _remap($method)
	{
		$segs = array_slice($this->uri->rsegment_array(), 1);
		if(isset($segs[0])) // Do we have a first segment?
		{
			$this->load->model("movie_m"); // Load in the movie model
			
			if(is_numeric($segs[0]) && $this->movie_m->exists($segs[0])) // Is it numeric and does the movie exist?
			{
				if(isset($segs[1]))
				{
					$params = array_slice($segs, 2);
			
					$array = array();
					$array[0] = $segs[0];
					$i = 1;
					foreach($params as $param)
					{
						$array[$i] = $param;
						$i++;
					}
			
					if(method_exists($this, $segs[1]))
					{
						return call_user_func_array(array($this, $segs[1]), $array);
					}
					else
					{
						show_404();
					}
				}
				else
				{
					return call_user_func_array(array($this, 'index'), $segs);
				}
			}
			elseif($segs[0] == "index")
			{
				redirect("/theaters");
			}
			elseif(method_exists($this, $segs[0]))
			{
				$method = $segs[0];
				$segs = array_slice($segs, 1);
				
				return call_user_func_array(array($this, $method), $segs);
			}
			else
			{
				show_404();
			}
		}
	}
	
	public function index($id)
	{
		$data = $this->movie_m->data($id);

		$this->page->title = $data['title'];
		
		$this->page->append_meta(js('jquery.fancybox.pack.js'), 'footer');
		$this->page->append_meta(css('jquery.fancybox_dark.css'));
		$this->page->append_meta(js('movie.js'), 'footer');
		
		$this->page->show('movie', $data);
	}
	
	public function notify($id, $type = 'theaters')
	{
		check_login();
		
		if($type == 'theaters' || $type == 'dvd')
		{	
			if($add = $this->user_m->notif_for($id, $type))
			{
				$query = $this->user_m->remove_notify($id, $type);
			}
			else
			{
				$query = $this->user_m->add_notify($id, $type);
			}
			
			if($query)
			{
				// Success, the notification was added/removed
				$data['status_code'] = 200;
				$data['message'] = $query;
			}
			else
			{
				// Failure, the notification was not added/removed
				$data['status_code'] = 500;
				$data['message'] = $query;
			}
			
			if($this->input->is_ajax_request())
			{
				$this->page->json($data);
			}
			else
			{
				redirect(site_url('movie/'.$id));
			}
			
		}
		else
		{
			show_404();
		}
	}
	
	public function add($id)
	{
		$this->load->model("movie_m");
		
		$add = $this->movie_m->add($id);
		
		if($add)
		{
			if($this->input->is_ajax_request())
			{
				$data['movie'] = $this->movie_m->data($add);
				
				$this->load->helper('text');
				$data['movie']['synopsis'] = word_limiter($data['movie']['synopsis'], 50);
				
				$data['echo'] = json_encode($data);
				$this->load->view("assets/echo", $data);
			}
			else
			{
				redirect("movie/".$add, 'location', 301);
			}
		}
		else
		{
			show_404();
		}
	}
	
	public function tickets($id)
	{
		// Redirect to fandango
	}
	
	public function dvd($id)
	{
		// Redirect to amazon
	}
}

/* End of file */