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

Route::get('images/{id}/{width?}/{height?}/{mode?}', ['as' => 'image', 'uses' => 'ImagesController@deliver']);