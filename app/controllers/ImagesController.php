<?php

class ImagesController extends BaseController
{
	public function __construct(Image $image)
	{
		$this->image = $image;
	}
	
	public function deliver($id, $width, $height, $mode)
	{
		return $this->image->deliver($id, $width, $height, $mode);
	}
}