$(function()
{
	$.gritter.add({title: 'Life of Pi',text: 'Hi Hope, the movie Life of Pi opens in theaters in 6 days (20th Dec 2012). You can buy the DVD at <a href="test">http://example.com</a>',image:'http://content7.flixster.com/movie/11/16/60/11166061_pro.jpg', sticky: true,time: ''});
	$('aside ol li').click(function()
	{
		window.location.href = $(this).find('a').attr('href');
	})
})