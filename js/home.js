$(document).ready(function() {
	// Ratings slider
	var delay = 5000;
	var count = 30;
	var showing = 5;
	var i = 0;
	function move(i) {
	  return function() {
	    $('#rating'+i).remove().css('display', 'none').prependTo('#ratings');
	  }
	}
	function shift() {
	  var toShow = (i + showing) % count;
	  $('#rating'+toShow).slideDown(1000, move(i));
	  $('#rating'+i).slideUp(1000, move(i));
	  i = (i + 1) % count;
	  setTimeout('shift()', delay);
	}
	
	setTimeout('shift()', delay);

	// Featured movies
	$.featureList(
		$("#tabs li a"),
		$("#output li"), {
			start_item	:	0
		}
	);
	
	//fade effect for img
	$("ul#output li img").fadeIn();
});