$(document).ready(function()
{
	// Simply alert the user with a message prompting to verify the email
    $.pnotify({
				pnotify_title: 'Email address not verified',
				pnotify_text: 'Before you can receive notifications you must verify your email address. For more information <a href="/register/verify">click here</a>',
				pnotify_type: 'error',
				pnotify_shadow: true,
				pnotify_hide: false,
				pnotify_history: false
			});
});