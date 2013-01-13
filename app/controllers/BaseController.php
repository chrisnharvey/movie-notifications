<?php

class BaseController extends Controller
{

	private $returnRoute = true;
	public $layout = 'layouts.default';

	public function __construct()
	{
		if ($this->returnRoute === true)
		{
			if ( ! Request::ajax())
			{
				Session::put('redirect_to', URL::base());
			}
		}
		elseif (is_string($this->returnRoute))
		{
			Session::put('redirect_to', $this->returnRoute);
		}
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}