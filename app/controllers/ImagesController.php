<?php

class ImagesController extends BaseController
{
	public function __construct(MovieData $movie)
	{
		$this->movie = $movie;
	}
	
	public function deliver($id, $width = 100, $height = 100, $mode = null)
	{

	}
}