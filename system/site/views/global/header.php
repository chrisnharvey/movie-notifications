<?=doctype($page['doctype'])?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://ogp.me/ns/fb#">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
<title><?=$page['title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />

<? if ($this->uri->segment(1) == 'movie' AND isset($title) AND ! empty($title)): ?>
	<meta property="fb:app_id" content="390345654352496" /> 
	<meta property="og:type" content="movie" /> 
	<meta property="og:site_name" content="Movie Notifications" />
	<meta property="og:url" content="<?=current_url()?>" /> 
	<meta property="og:title" content="<?=$title?>" />
	<? if ($poster_url != '/images/noposter.png'): ?>
		<meta property="og:image" content="<?=$poster_url?>" />
	<? endif; ?>
	<? if ( ! empty($synopsis)): ?>
		<meta property="og:description" content="<?=word_limiter($synopsis, 50)?>" />
	<? endif; ?>
<? endif; ?>

<? if ($this->uri->segment(1) == 'theaters'): ?>
	<meta name="description" content="Free notifications for theatrical releases. Receive a text message, email or iOS notification when a movie you want to see is released."/>
	<meta name="keywords" content="Theatrical Notifications, Theatrical Release Notifications, Movie Notifications, Movie Release Notifications, Film Notifications, Film Release Notifications, DVD Notifications, DVD Release Notifications"/>
<? elseif ($this->uri->segment(1) == 'dvd'): ?>
	<meta name="description" content="Free notifications for DVD releases. Receive a text message, email or iOS notification when a DVD you want to see is released."/>
	<meta name="keywords" content="DVD Notifications, DVD Release Notifications, Movie Notifications, Movie Release Notifications, Film Notifications, Film Release Notifications, Theatrical Notifications, Theatrical Release Notifications"/>
<? elseif ($this->uri->segment(1) == 'register'): ?>
	<meta name="description" content="Register for free notifications for movie releases. Receive a text message, email or iOS notification when a movie you want to see is released."/>
	<meta name="keywords" content="Movie Notifications, Movie Release Notifications, Film Notifications, Film Release Notifications, DVD Notifications, DVD Release Notifications, Theatrical Notifications, Theatrical Release Notifications"/>
<? elseif ($this->uri->segment(1) == 'movie' AND isset($title) AND ! empty($title)): ?>
	<meta name="description" content="Free notifications for <?=$title?>. Receive a text message, email or iOS notification when <?=$title?> is released."/>
	<meta name="keywords" content="<?=$title?>, <?=$title?> Notifications, <?=$title?> DVD Notification, <?=$title?> Release Notification, <?=$title?> Theatrical Notification, Movie Release Notifications, Film Notifications, Film Release Notifications, DVD Notifications, DVD Release Notifications, Theatrical Notifications, Theatrical Release Notifications"/>
<? else: ?>
	<meta name="description" content="Free notifications for movie releases. Receive a text message, email or iOS notification when a movie you want to see is released."/>
	<meta name="keywords" content="Movie Notifications, Movie Release Notifications, Film Notifications, Film Release Notifications, DVD Notifications, DVD Release Notifications, Theatrical Notifications, Theatrical Release Notifications"/>
<? endif; ?>

<meta http-equiv="cache-control" content="Public"/>

<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.8/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
<link href="/css/jquery.pnotify.default.css" rel="stylesheet" type="text/css" />
<link href="/css/jquery.pnotify.default.icons.css" rel="stylesheet" type="text/css" />

<link href="/favicon.ico" rel="icon" type="image/x-icon" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.pnotify.min.js"></script>
<script type="text/javascript" src="/js/global.js"></script>
<script src="//connect.facebook.net/en_US/all.js#xfbml=1&appId=390345654352496"></script>

<? if(is_logged_in()): ?>
	<script type="text/javascript" src="/js/user.js"></script>
	<? if($this->session->flashdata('alert_verified') === '0'): ?>
		<script type="text/javascript" src="/js/verify_alert.js"></script>
	<? endif; ?>
<? endif; ?>
<?=$meta['header']?>
<!--[if lt IE 7]>
	<link href="/css/ie_style.css" rel="stylesheet" type="text/css" />
   <script type="text/javascript" src="js/ie_png.js"></script>
   <script type="text/javascript">
       ie_png.fix('.png, .header-box .left-top-corner, .header-box .right-top-corner, .header-box .border-top, .header-box .indent, .description');
   </script>
<![endif]-->
</head>

<body id="home">
  <div id="fb-root"></div>
  <div id="main">