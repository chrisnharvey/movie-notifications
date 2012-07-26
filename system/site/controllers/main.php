<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Controller {

	public function index()
	{
		//$this->output->enable_profiler(TRUE);	
		$this->load->helper("text"); // word_limiter for the synopsis'
		$this->load->helper("user");
		
		// Ratings (Coming Soon)
		//$this->load->model("ratings_m"); // We show the ratings on this page, load the model
		//$data['ratings'] = $this->ratings_m->recent(30); // Recent ratings for the slider on the left
		
		// Movies
		$this->load->model("movie_m"); // Load the movie model, there is a lot of movie data on this page
		$data['box_office'] = $this->movie_m->box_office(); // Box office, for the left
		$data['new_dvds'] = $this->movie_m->new_dvds(5); // New dvd's, for the bottom
		$data['opening'] = $this->movie_m->opening(4); // Opening movies, for the middle of the page
		$data['top_rentals'] = $this->movie_m->top_rentals(7); // Top rentals, for the bottom of the page
		
		$country = $this->system_m->current_country(TRUE, TRUE);
		
		if(!$data['in_theaters'] = $this->cache->get("mn_home_in_theaters_".$country))
		{
			// Data for the slider, shows the movies that are in theaters
			$in_theaters = $this->movie_m->in_theaters(10, $country); // We only show 4 movies in the slider, we request 10 just in case TMDb doesn't have the images
			
			$count = 1;
			
			foreach($in_theaters as $key => $movie)
			{
				if($count <= 4) // Have we already already got our 4 movies?
				{
					if($images = $this->movie_m->get_images($movie['alt_id'], 100))
					{
						foreach($images as $image)
						{
							$size = getimagesize($image['large']);
							$width = $size[0];
							$height = $size[1];
							
				//			if($width == 780 && $height == 439)
				//			{
								$in_theaters[$key]['image']['thumb'] = $image['thumb'];
								$in_theaters[$key]['image']['large'] = $image['large'];
								$count++;
								break;
			//				}
						}
						
						//$in_theaters[$key]['poster_url'] = current($images); // Images were found, get the first one's url
					}
					else
					{
						unset($in_theaters[$key]); // No image for this movie, unset the array key
					}
				}
				else
				{
					break; // We already have our 4 movies, no need for any more, exit the loop
				}
			}

			$data['in_theaters'] = array();
			
			$count = 1;
			
			foreach($in_theaters as $movie)
			{
				if($count <= 4)
				{
					if(array_key_exists('image', $movie)) // Check that the images are set
					{
						array_push($data['in_theaters'], $movie);
						$count++;
					}
				}
				else
				{
					break;
				}
			}
			
			$this->cache->save("mn_home_in_theaters_".$country, $data['in_theaters'], 86400);
		}
		
		$this->page->full_title = SITE_TITLE . ' | Movie Release Notifications';
		$this->page->append_meta(js(array("jquery.featureList-1.0.0.js", "home.js")), "footer");
		$this->page->show('main', $data);
	}

	function app()
	{
		redirect("http://movienotifications.com/apps");
	}
}

/* End of file */