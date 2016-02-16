<div id="footer">
    	<div class="indent">
      	<div class="wrapper">
        	<div class="fleft">
          	<!-- .nav -->
          	<ul class="nav">
            	<li><a href="/">Home</a></li>
              <li><a href="/theaters">In Theaters</a></li>
              <? if($this->session->userdata('country') != 225): ?>
                <li><a href="/dvd">On DVD</a></li>
              <? endif; ?>
            </ul>
          	<!-- /.nav -->
            <!-- .users-links -->
            <ul class="users-links">
              <? if(is_logged_in()): ?>
                <li><a href="/notifications">Notifications</a>|</li>
                <li><a href="/settings">Settings</a>|</li>
                <li><a href="/logout">Logout</a></li>
              <? else: ?>
                <li><a href="/register">Register</a>|</li>
                <li><a href="/login">Login</a></li>
              <? endif; ?>
            </ul>
            <!-- /.users-links -->
            &copy; Movie Notifications <?=date('Y')?>&nbsp;<!-- &nbsp; <a href="/privacy">Privacy Policy</a>-->
          </div>
          <!-- .social-services -->
          <ul class="social-services">
          	<li><a href="http://twitter.com/movienotifs"><img src="/images/icon-twitter.jpg" alt="" /></a></li>
            <li><a href="http://www.facebook.com/MovieNotifications"><img src="/images/icon-facebook.jpg" alt="" /></a></li>
          </ul><br><br>
          <!-- /.social-services -->
          <div class="data-by">Movie data provided by <a href="http://themoviedb.org">TMDb</a>, <a href="http://rottentomatoes.com/">RT</a> &amp; <a href="http://filmdates.co.uk">FilmDates</a></div>
        </div>
      </div>
    </div>
  </div>
  <a href="https://github.com/chrisnharvey/movie-notifications"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://camo.githubusercontent.com/c6625ac1f3ee0a12250227cf83ce904423abf351/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f677261795f3664366436642e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_left_gray_6d6d6d.png"></a>
  <?=$meta['footer']?>
</body>
</html>
