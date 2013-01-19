<?php

// Bind our injectable classes into the IoC container
App::bind('Movie', 'TrMovie');
App::bind('Image', 'TrImage');

Route::get('/', 'HomeController@index');

Route::get('login', ['before' => 'guest', 'as' => 'login', function()
{
	return 'dsf';
}]);

Route::get('movie/{movie}', ['as' => 'movie', 'uses' => 'HomeController@index']);

Route::group(array('domain' => 'api.movienotifications.com'), function()
{
	// Resourceful controller shit for the API!
	Route::get('user/{id}', function($account, $id)
	{
		//
	});
});

Route::group(array('domain' => 'assets.movienotifications.com'), function()
{
	Route::get('images/{id}-{width}x{height}-{mode}.png', ['as' => 'image', 'uses' => 'ImagesController@deliver']);
});