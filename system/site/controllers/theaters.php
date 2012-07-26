<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theaters extends Controller {
	
	function _remap($method)
	{
		$param_offset = 2;

		if(!method_exists($this, $method))
		{
			// We need one more param
			$param_offset = 1;
			$method = 'index';
		}
		
		$params = array_slice($this->uri->rsegment_array(), $param_offset);

		return call_user_func_array(array($this, $method), $params);
	}
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('movie_m');
	}
	
	public function index()
	{
		// First we need the country
		$country = $this->session->userdata('country');
		
		if($this->uri->segment(2))
		{
			if($this->uri->segment(2) < date("Y"))
			{
				show_404();
			}
			elseif(($this->uri->segment(2) == date("Y")) && ($this->uri->segment(3) < date("m")))
			{
				show_404();
			}
			elseif($this->uri->segment(3) > 12)
			{
				show_404();
			}
			elseif(($this->uri->segment(4)) && ($this->uri->segment(4) > cal_days_in_month(0, $this->uri->segment(3), $this->uri->segment(2))))
			{
				show_404();
			}
			else
			{
				$all_good = TRUE;
			}
		}
		else
		{
			$all_good = TRUE;
		}
		
		if(isset($all_good))
		{
		
			if($this->uri->segment(2))
			{
				$year = (int)$this->uri->segment(2);
			}
			else
			{
				$date = date("Y-m-d");
				$year = date("Y");
			}
		
			if($this->uri->segment(3))
			{
				$month = (int)$this->uri->segment(3);
			}
			else
			{
				if($this->uri->segment(2))
				{
					$month = 01;
				}
				else
				{
					$month = date("m", strtotime($date));
				}
			}
		
			if($this->uri->segment(4))
			{
				$day = (int)$this->uri->segment(4);
			}
		
			$prefs = array("show_next_prev" => TRUE, 'next_prev_url'   => site_url("theaters/"));
		
			$prefs['template'] = '

			   {table_open}<table class="calendar" border="0" cellpadding="4" cellspacing="0">{/table_open}

			   {heading_row_start}<tr>{/heading_row_start}

			   {heading_previous_cell}
			   ';
		
		
			$this->db->order_by("date", "asc");
			$this_month = $this->db->get_where("releases", array("YEAR(date)" => $year, "MONTH(date)" => $month, 'country_id' => $country), 1);
			
			if($this_month->num_rows() < 1)
			{
				show_404();
			}
			else
			{
			
				$this_month = $this_month->row()->date;
		
				$this->db->select("MONTH(date) AS month, YEAR(date) AS year");
				$this->db->order_by("date", "desc");
				$this->db->where("date <", $this_month);
				$days = $this->db->get_where("releases", array("type" => "Theaters", 'country_id' => $country), 1);
		
				if($days->num_rows() > 0)
				{
					if(($year == date("Y")) && ($month == date("m")))
					{
						$prefs['template'] .= '<th></th>';
					}
					else
					{
						$prefs['template'] .= '<th>'.anchor(site_url("theaters/".$days->row()->year."/".$days->row()->month), '&lt;&lt;').'</th>';
					}
				}
				else
				{
					$prefs['template'] .= '<th></th>';
				}
		
				$prefs['template'] .= '{/heading_previous_cell}
				   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
				   {heading_next_cell}
				   ';
		
				$this->db->order_by("date", "desc");
				$this_month = $this->db->get_where("releases", array("YEAR(date)" => $year, "MONTH(date)" => $month, 'country_id' => $country), 1);
				$this_month = $this_month->row()->date;
		
				$this->db->select("MONTH(date) AS month, YEAR(date) AS year");
				$this->db->order_by("date", "asc");
				$this->db->where("date >", $this_month);
				$days = $this->db->get_where("releases", array("type" => "Theaters", 'country_id' => $country), 1);
		
				if($days->num_rows() > 0)
				{
					$prefs['template'] .= '<th>'.anchor(site_url("theaters/".$days->row()->year."/".$days->row()->month), '&gt;&gt;').'</th>';
				}
				else
				{
					$prefs['template'] .= '<th></th>';
				}
		
				$prefs['template'] .= '
			
				   {/heading_next_cell}
				   {heading_row_end}</tr>{/heading_row_end}

				   {week_row_start}<tr>{/week_row_start}
				   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
				   {week_row_end}</tr>{/week_row_end}

				   {cal_row_start}<tr>{/cal_row_start}
				   {cal_cell_start}<td>{/cal_cell_start}

				   {cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
				   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

				   {cal_cell_no_content}{day}{/cal_cell_no_content}
				   {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

				   {cal_cell_blank}&nbsp;{/cal_cell_blank}

				   {cal_cell_end}</td>{/cal_cell_end}
				   {cal_row_end}</tr>{/cal_row_end}

				   {table_close}</table>{/table_close}
				';
				
				$this->load->library('calendar', $prefs);
		
				$this->db->select("DISTINCT(DAY(date)) AS day");
				$days = $this->db->get_where("releases", array("YEAR(date)" => $year, "MONTH(date)" => $month, "type" => "Theaters", 'country_id' => $country));
	
				$cal_data = array();

				foreach($days->result() as $cal_day)
				{
					$cal_data[$cal_day->day] = site_url("theaters/".$year."/".$month."/".$cal_day->day);
				}

				$data['calendar'] = $this->calendar->generate($year, $month, $cal_data);

				if(isset($day))
				{
				
					if(!array_key_exists($day, $cal_data))
					{
						$this->router->show_404();
					}
					else
					{

						$this->db->order_by("date");
						$query = $this->db->join('movies', 'releases.movie_id = movies.id', 'inner');
						$query = $this->db->get_where("releases", array("YEAR(date)" => $year, "MONTH(date)" => $month, "DAY(date)" => $day, "type" => "Theaters", 'country_id' => $country));
						
						$movies = array();
            	
						foreach($query->result() as $movie)
						{
							array_push($movies, array('id' => $movie->movie_id, "synopsis" => $this->movie_m->meta($movie->movie_id, "synopsis"), "title" => $movie->title, "url" => site_url("movie/".$movie->movie_id), "poster_url" => $this->movie_m->poster($movie->movie_id)));
						}
	
						$data['title'] = "Movies released on ".date("jS F Y", strtotime($query->row()->date));
						$data['movies'] = $movies;
					}
				}
				else
				{
						$this->db->order_by("date");
						$this->db->join('movies', 'releases.movie_id = movies.id');
						$query = $this->db->get_where("releases", array("YEAR(date)" => $year, "MONTH(date)" => $month, "type" => "Theaters", 'country_id' => $country), 10);

						$movies = array();
            	
						foreach($query->result() as $movie)
						{
							array_push($movies, array('id' => $movie->movie_id, "synopsis" => $this->movie_m->meta($movie->movie_id, "synopsis"), "title" => $movie->title, "url" => site_url("movie/".$movie->movie_id), "poster_url" => $this->movie_m->poster($movie->movie_id)));
						}
				
						$data['title'] = "Movies released in ".date("F Y", strtotime($query->row()->date));
						$data['movies'] = $movies;
				}
			}
		}

		$this->page->title = "In Theaters";
		$this->page->show('theaters', $data);
	}
	
	public function app()
	{
		$this->page->json($this->movie_m->in_theaters(10));
	}
}

/* End of file */