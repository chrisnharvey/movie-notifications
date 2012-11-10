<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacy extends Controller {

	public function index()
	{
		$this->page->title = "Privacy Policy";
		$this->page->show('privacy');
	}
}

/* End of file */