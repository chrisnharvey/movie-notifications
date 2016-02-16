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
            &copy; Movie Notifications 2012&nbsp;<!-- &nbsp; <a href="/privacy">Privacy Policy</a>-->
          </div>
          <!-- .social-services -->
          <ul class="social-services">
          	<li><a href="http://twitter.com/movienotifs"><img src="/images/icon-twitter.jpg" alt="" /></a></li>
            <li><a href="http://www.facebook.com/MovieNotifications"><img src="/images/icon-facebook.jpg" alt="" /></a></li>
          </ul><br><br>
          <!-- /.social-services -->
          <div class="data-by">Movie data provided by <a href="http://themoviedb.org">TMDb</a>, <a href="http://rottentomatoes.com/">RT</a> &amp; <a href="http://filmdates.co.uk">FilmDates.co.uk</a></div>
        </div>
      </div>
    </div>
  </div>
  <?=$meta['footer']?>
  <script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/jhm9anP4d0MXvOi5QzlYxw.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>
</body>
</html>