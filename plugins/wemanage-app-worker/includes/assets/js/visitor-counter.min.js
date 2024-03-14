jQuery(document).ready(function($) {

	"use strict";
	
	$.ajax({
		url: nouvello_visitor_counter.ajax_url,
		type: 'POST',
		data: {
			'action': 'nouvello_update_counter',
			'post_id': nouvello_visitor_counter.post_id,
			'nonce': nouvello_visitor_counter.ajax_nonce
		},
		cache: false,
		success: function(data) {
			console.log(data);
		}
	});

});
