<?php if ( ! defined('BASEPATH')) exit('No direct script access');

function theaters_button($movie_id)
{
	$CI =& get_instance();
	
	if(!$CI->session->userdata('logged_in'))
	{
		return NULL;
	}
	
	$CI->load->model('movie_m');
	
	if(!$CI->movie_m->exists($movie_id) || !$CI->movie_m->can_notify($movie_id, 'theaters'))
	{
		return NULL;
	}
	
	if($CI->user_m->notif_for($movie_id, 'theaters'))
	{
		$selected = ' selected';
	}
	else
	{
		$selected = '';
	}
	
	return '<a href="'.site_url('movie/'.$movie_id.'/notify/theaters').'" class="movie_link'.$selected.'"><em><b>notify theaters</b></em></a>';
}

function dvd_button($movie_id)
{
	$CI =& get_instance();
	
	if(!$CI->session->userdata('logged_in'))
	{
		return NULL;
	}
	
	$CI->load->model('movie_m');
	
	if(!$CI->movie_m->exists($movie_id) || !$CI->movie_m->can_notify($movie_id, 'dvd') || $CI->session->userdata('country') == 225 /* UK */)
	{
		return NULL;
	}
	
	if($CI->user_m->notif_for($movie_id, 'dvd'))
	{
		$selected = ' selected';
	}
	else
	{
		$selected = '';
	}
	
	return '<a href="'.site_url('movie/'.$movie_id.'/notify/dvd').'" class="movie_link'.$selected.'"><em><b>notify dvd</b></em></a>';
}