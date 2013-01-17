<?php

class HomeController extends BaseController
{

	public function __construct(Movie $movie)
	{
		$this->movie = $movie;
	}

	public function index()
	{
		return View::make('home')
			->with('boxOffice', $this->movie->boxOffice())
			->with('opening', $this->movie->opening())
			->with('newDvds', $this->movie->newDvds(5))
			->with('topRentals', $this->movie->topRentals(7));
	}

}