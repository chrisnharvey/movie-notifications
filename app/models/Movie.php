<?php

interface Movie
{
	public function __construct(Image $image);

	public function boxOffice($limit = 10, $country);

	public function newDvds($limit = 10, $country);

	public function opening($limit = 10, $country);

	public function inTheaters($limit = 10, $country);

	public function topRentals($limit = 10, $country);
}