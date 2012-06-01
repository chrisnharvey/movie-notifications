$(document).ready(function()
{
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
		})
	});
	
	$('[name="notify_start"]').change();
});
