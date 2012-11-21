<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images extends Controller
{
	public $url = 'http://cf2.imgobject.com/t/p/';

	function __construct()
	{
		parent::__construct();

		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
	}
	
	function _remap($method)
	{
		$segs = array_slice($this->uri->rsegment_array(), 1);
		$uri  = implode('/', $segs);

		$url = $this->url . '/' . $uri;
		$hash = md5($url);

		if ( ! $file = $this->cache->file->get('image_' . $hash))
		{
			$file = file_get_contents($url);

			if ($file)
			{
				$this->cache->file->save('image_' . $hash, $file, 86400);

				$type = 'image/jpeg';
				header('Content-Type:'.$type);

				echo $file;
			}
			else
			{
				show_404();
			}
		}
		else
		{
			$type = 'image/jpeg';
			header('Content-Type:'.$type);
			echo $file;
		}
			
	}
}