$(document).ready(function()
{
	var selected_end = $('[name="notify_end"]').val();
	var options = $('[name="notify_end"]').children('option').detach();
	
	$('[name="notify_start"]').change(function()
	{
		var selected = parseInt($(this).val());
		
		options.appendTo('[name="notify_end"]');
		
		$('[name="notify_end"]').children('option').each(function() 
		{
			if(parseInt($(this).val()) <= selected)
			{
				$(this).detach();
			}
			
			if(selected_end == $(this).val())
			{
				$(this).attr('selected', 'selected');
			}
		})
	});
	
	$('[name="notify_end"]').change(function()
	{
		selected_end = $(this).val();
	})
	
	$('[name="notify_start"]').change();
});
