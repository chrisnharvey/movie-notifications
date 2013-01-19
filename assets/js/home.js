$(function()
{
	$('#slider').nivoSlider({
		controlNavThumbs: true,
		pauseTime: 6000,
		effect: 'sliceUp',
		afterLoad: function()
		{
			$('.nivoSlider img').not('.nivo-main-image').each(function(index, image)
			{
				$('.nivo-controlNav').find('a[rel="' + index + '"]').append('<span>' + $(image).attr('data-title') + '</span>');
			});
		}
	});
})