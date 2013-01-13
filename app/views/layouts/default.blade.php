<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	@yield('meta')

	<link rel="stylesheet" href="css/960.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/gritter.css">
	
	@yield('css')
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

	<!--[if (gte IE 6)&(lte IE 8)]>
	  <script type="text/javascript" src="js/selectivizr.js"></script>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<script src="js/gritter.js"></script>
	<script src="js/global.js"></script>

	@yield('js')

	<link rel="shortcut icon" href="images/favicon.ico">
</head>
<body>

	<div class="container_12" id="main">
		<header>
			<h1><a href="/" class="logo">Movie Notifications</a></h1>
			
			<nav id="head_user">
				<ul>
					<li><a href="/register">Register</a><span>|</span></li>
					<li><a href="/login">Login</a></li>
				</ul>
			</nav>
			
			<form id="search" method="post" action="/search">
				<input type="text" placeholder="Search...">
				<input type="submit" value="">
			</form>
			
			<nav id="head_nav">
				<ul>
					<li><a class="current" href="http://google.com/">Home</a></li>
					<li><a href="http://google.com/">In Theaters</a></li>
				</ul>
			</nav>
		</header>
		
		<div class="content">
			@yield('content')
		</div>

		<footer>
			<div class="grid_6 left">
				<nav id="foot_nav">
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="/theaters">In Theaters</a></li>
					</ul>
				</nav>
				<nav id="foot_user">
					<ul>
						<li><a href="/">Register</a><span>|</span></li>
						<li><a href="/theaters">Login</a></li>
					</ul>
				</nav>
				<div id="copyright">
					Copyright &copy; Movie Notifications 2012
				</div>
			</div>
			<div class="grid_6 right">
				<div id="social">
					<a target="_blank" class="twitter" href="http://twitter.com/MovieNotifs">Twitter</a>
					<a target="_blank" class="facebook" href="http://facebook.com/MovieNotifications">Facebook</a>
				</div>
				<div id="data">
					Movie data by <a target="_blank" href="http://www.themoviedb.org/">TMDb</a>,
					<a target="_blank" href="http://www.rottentomatoes.com/">RT</a> &amp;
					<a target="_blank" href="http://www.filmdates.co.uk/">FilmDates</a>
				</div>
			</div>
		</footer>
	</div>

</body>
</html>