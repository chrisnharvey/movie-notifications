<?php

class ImagesController extends BaseController
{
	public function __construct(Image $images)
	{
		$this->images = $images;
	}
	
	public function deliver($id, $width, $height, $mode)
	{
		return $this->images->deliver($id, $width, $height, $mode);
	}
}