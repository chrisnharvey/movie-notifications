<ul class="adv-nav">
	<? if(is_logged_in()): ?>
		<li><a href="/notifications">Notifications</a>|</li>
		<li><a href="/settings">Settings</a>|</li>
	  	<li><a href="/logout">Logout</a></li>
	<? else: ?>
		<li><a href="/register">Register</a>|</li>
	  	<li><a href="/login">Login</a></li>
	<? endif; ?>
</ul>