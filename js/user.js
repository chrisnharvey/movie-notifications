var notifFound = function(data) {
	
    $.each(data, function() {
        $.pnotify({
					pnotify_title: 'Notification',
					pnotify_text: this.notification,
					pnotify_type: 'notice',
					pnotify_shadow: true,
					pnotify_history: false
				});
    });
    // do more processing
    setTimeout(getNotifs, 30000);
};
 
var getNotifs = function() {
    $.getJSON('/notifications/ajax', {}, notifFound, 'json');
};
 
$(document).ready(getNotifs);