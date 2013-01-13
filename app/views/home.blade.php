@extends('layouts.default')

@section('meta')
	<title>Movie Notifications | Movie Release Notifications</title>
	<meta name="description" content="Free notifications for movie releases. Receive a text message, email or iOS notification when a movie you want to see is released."/>
	<meta name="keywords" content="Movie Notifications, Movie Release Notifications, Film Notifications, Film Release Notifications, DVD Notifications, DVD Release Notifications, Theatrical Notifications, Theatrical Release Notifications"/>
@stop

@section('css')
	<link rel="stylesheet" href="css/nivo.css">
	<link rel="stylesheet" href="css/slider.css">
@stop

@section('js')
	<script src="js/nivo.js"></script>
	<script src="js/home.js"></script>
@stop

@section('content')
	<aside class="grid_3">
		<h2>Box Office</h2>
		<ol>
			@foreach ($boxOffice as $movie)
				<li><a href="{{ $movie['url'] }}">{{ $movie['title'] }}</a></li>
			@endforeach
		</ol>
	</aside>

	<div class="grid_9 box">
		<div class="slider">
			<div id="slider" class="nivoSlider">
				<img data-title="The Twilight Saga: Breaking Dawn - Part 2" data-thumb="img/slider/slide1_thumb.jpg" src="img/slider/slide1.jpg" alt="" />
				<img data-title="Skyfall" data-thumb="img/slider/slide2_thumb.jpg" src="img/slider/slide2.jpg" alt="" title="#skyfall" />
				<img data-title="Nativity 2" data-thumb="img/slider/slide3_thumb.jpg" src="img/slider/slide3.jpg" alt="" />
				<img data-title="The Silver Linings Playbook" data-thumb="img/slider/slide4_thumb.jpg" src="img/slider/slide4.jpg" alt="" />
			</div>
		</div>

		<div id="skyfall" class="nivo-html-caption">
			<span>sdfsfsdfsdf</span>
			<a class="btn" href="#">More</a>
		</div>
	</div>

	<div class="grid_9 box right">
		<div class="title">
			<h3>Opening This Week</h3>
			<a href="#">all movies</a>
		</div>
		<section>
			<ul class="movies">
				<li>
					<img height="120" alt="" class="img-indent" src="https://www.movienotifications.com/images/w92/wDIdtAj1GnboA8pFf9SxkjV2AS.jpg">
					
					<div>
						<h4><a href="#">The Hobbit: An Unexpected Journey</a></h4>
						<p>Bilbo Baggins, a Hobbit, journeys to the Lonely Mountain accompanied by a group of dwarves to reclaim a treasure taken…</p>
						<a href="#" class="btn">more</a>
					</div>
				</li>
				<li>
					<img height="120" alt="" class="img-indent" src="https://www.movienotifications.com/images/w92/wDIdtAj1GnboA8pFf9SxkjV2AS.jpg">
					
					<div>
						<h4><a href="#">The Hobbit: An Unexpected Journey</a></h4>
						<p>Bilbo Baggins, a Hobbit, journeys to the Lonely Mountain accompanied by a group of dwarves to reclaim a treasure taken…</p>
						<a href="#" class="btn">more</a>
					</div>
				</li>
				<li>
					<img height="120" alt="" class="img-indent" src="https://www.movienotifications.com/images/w92/wDIdtAj1GnboA8pFf9SxkjV2AS.jpg">
					
					<div>
						<h4><a href="#">The Hobbit: An Unexpected Journey</a></h4>
						<p>Bilbo Baggins, a Hobbit, journeys to the Lonely Mountain accompanied by a group of dwarves to reclaim a treasure taken…</p>
						<a href="#" class="btn">more</a>
					</div>
				</li>
				<li>
					<img height="120" alt="" class="img-indent" src="https://www.movienotifications.com/images/w92/wDIdtAj1GnboA8pFf9SxkjV2AS.jpg">
					
					<div>
						<h4><a href="#">The Hobbit: An Unexpected Journey</a></h4>
						<p>Bilbo Baggins, a Hobbit, journeys to the Lonely Mountain accompanied by a group of dwarves to reclaim a treasure taken…</p>
						<a href="#" class="btn">more</a>
					</div>
				</li>
			</ul>
		</section>
	</div>

	<div class="grid_9 double_list right">
		<section class="left">
			<h3>New DVD's This Week</h3>
			<ol>
				@foreach ($newDvds as $movie)
					<li>
						<a href="{{ $movie['url'] }}">{{ $movie['title'] }}</a>
					</li>
				@endforeach
			</ol>
		</section>
		<section class="right">
			<div class="inner_box">
				<h3>Top Rentals</h3>
				<ol>
					@foreach ($topRentals as $movie)
						<li>
							<a href="{{ $movie['url'] }}">{{ $movie['title'] }}</a>
						</li>
					@endforeach
				</ol>
			</div>
		</section>
	</div>
@stop