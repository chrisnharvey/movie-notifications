<?php namespace Images

interface Images
{
	public function deliver($id, $width = 81, $height = 120, $mode = null);

	public static function url($id, $width = null, $height = null, $mode = null);
}