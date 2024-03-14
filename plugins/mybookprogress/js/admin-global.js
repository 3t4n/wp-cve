
/*---------------------------------------------------------*/
/* Tracking                                                */
/*---------------------------------------------------------*/

function mbp_track_event(event_name, instance, after) {
	if(typeof after === 'undefined' && _.isFunction(instance)) { after = instance; instance = false; }
	if(!_.isObject(instance)) { instance = false; }
	var jqxhr = jQuery.post(ajaxurl, {action: 'mbp_track_event', event_name: event_name, instance: JSON.stringify(instance)});
	if(typeof after !== 'undefined') { jqxhr.always(after); }
}

jQuery(document).ready(function() {
	jQuery('.authormedia-insert-shortcode-button').on('click', function() {
		mbp_track_event('authormedia_shortcode_inserter_open');
	});
	setTimeout(function() {
		if(window.authormedia_shortcode_form_events) {
			window.authormedia_shortcode_form_events.on('insert', function(shortcode) {
				mbp_track_event('authormedia_shortcode_insert', {shortcode: shortcode});
			});
		}
	}, 0);
});
