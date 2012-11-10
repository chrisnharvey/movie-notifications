<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("movie_m");
	}

	public function index()
	{	
		$query = (!$this->input->get('q')) ? NULL : strip_tags($this->input->get("q"));
		
		$this->page->title = ($query !== NULL) ? "Search Results > ".$query : 'No results';
		
		$data['header'] = $this->page->title;
		
		$this->page->set("query", $query);
		
		if($query !== NULL)
		{
			$this->page->set("results", $this->movie_m->search($query));
		}
		
		$this->page->show('search', $data);
	}
	
	function app()
	{
		$this->page->json($this->movie_m->search($this->input->get("q")));
	}
}

/* End of file */