<h2>Your email address is not verified</h2>

<p>
	Your email address has not been verified. Don't worry, this is a simple process and 
	shouldn't take any longer than a few minutes.
</p>

<p>
	We sent an email to <strong><?=$this->session->userdata('email')?></strong> with a link that will verify the email 
	address. If you did not receive this email, then please click the link below to resend it. If you would like to 
	use a different email address for Movie Notifications then please click the other link to change it.
</p>

<p>
	<button type="button" id="link" href="/register/verify/resend"><span><em><b>resend verification email</b></em></span></button>
	<button type="button" id="link" href="/settings"><span><em><b>change email address</b></em></span></button>
</p>