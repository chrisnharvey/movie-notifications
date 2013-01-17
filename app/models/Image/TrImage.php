<?php

use Gregwar\Image\Image as ImageMan;

class TrImage implements Image
{
	public function __construct()
	{
		$this->image = new ImageMan;
		$this->image->setCacheDir(Config::get('images.cache_dir'));
	}

	public function deliver($id, $width = 81, $height = 120, $mode = null)
	{
		return $this->image
			->fromFile('http://cf2.imgobject.com/t/p/w185/28XfzAEs4QbOhWOTLgZxlE2L0en.jpg')
			->cropResize($width, $height)
			->png();
	}

	public function insert($url)
	{

	}

	public static function url($identifier, $width = null, $height = null, $mode = null)
	{
		// Select the image where the id or url equals $identifier
	}
}