jQuery(document).ready(function ($) {
	$(function () {
		var $dialog = $('<div></div>')
		.html('<p style="text-align:center;">loading track....</p>')
		.dialog({
			dialogClass: 'wp-dialog',
			autoOpen: false,
			title: 'Preview Track',
			modal: 'true',
			zIndex: 9999,
			close: function(event, ui) { $dialog.html('<p style="text-align:center;">loading track....</p>'); } //need this because I found that the previous error message wasn't clearing
		});

		//open dialog first with the ...loading message
		$( 'body' ).on( 'click', '#scu_track_preview', function() {
			preview_track_link = $(this);
			preview_track_link.attr("disabled", true);
			$dialog.dialog('open');
			preview_track_link.attr("disabled", false);
			// prevent the default action, e.g., following a link
			//return false;
		});
		
		$( 'body' ).on( 'click', '#scu_track_preview', function(evt) {
			evt.preventDefault();
			var preview_track_link = $(this);
			var scu_track_url = preview_track_link.attr('href'),
				nonce = SCU_JS.scu_nonce,
				postUrl = SCU_JS.ajaxurl;
			$.ajax({
		         type : "post",
		         dataType : "json",
		         url : postUrl,
		         data : {action: 'scu_ajax_notification', track_url: scu_track_url, nonce: nonce},
		         success: function(response) {
		        	 if(response.status == "success") {
		        		 $dialog.html(response.output)
							.dialog('open');
		        	 } else if(response.status == "error") {
		        		 $dialog.html(response.output)
							.dialog('open');
		        	 }
		         }
		      });
			return false;
		});
	});
});