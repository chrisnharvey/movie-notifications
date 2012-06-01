$(document).ready(function()
{
	$("input#link, button#link").click(function(e)
	{
		window.location.href = $(this).attr('href');
	});
	
	$(".movie_link").click(function(e)
	{
		e.preventDefault();
		
		var href = $(this).attr('href');
		
		var button = $(this);

		$.ajax({
			type: "GET",
			url: href,
			success: function(data) {
					var response = jQuery.parseJSON(data);
					if(response.status_code == 200)
					{
						if(button.hasClass('selected'))
						{
							button.removeClass('selected');
						}
						else
						{
							button.addClass('selected');
						}
					}
					else
					{
						$.pnotify({
							pnotify_title: 'Error',
							pnotify_text: response.message,
							pnotify_type: 'error',
							pnotify_shadow: true,
							pnotify_history: false
						});
					}
			},
			error:function (xhr, ajaxOptions, thrownError){
				$.pnotify({
					pnotify_title: 'Error',
					pnotify_text: 'An error occurred whilst trying to update your notification settings for this movie',
					pnotify_type: 'error',
					pnotify_shadow: true,
					pnotify_history: false
				});
            }
		})
	});
});