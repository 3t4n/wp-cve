jQuery(document).ready(function($) {
	$( '#the-list #captcha-booster-plugin-disable-link' ).click(function(e) {
		e.preventDefault();

		var reason = $( '#captcha-booster-feedback-content .captcha-booster-reason' ),
			deactivateLink = $( this ).attr( 'href' );

	    $( "#captcha-booster-feedback-content" ).dialog({
	    	title: 'Quick Feedback Form',
	    	dialogClass: 'captcha-booster-feedback-form',
	      	resizable: false,
	      	minWidth: 430,
	      	minHeight: 300,
	      	modal: true,
	      	buttons: {
	      		'go' : {
		        	text: 'Continue',
        			icons: { primary: "dashicons dashicons-update" },
		        	id: 'captcha-booster-feedback-dialog-continue',
					class: 'button',
		        	click: function() {
		        		var dialog = $(this),
		        			go = $('#captcha-booster-feedback-dialog-continue'),
		          			form = dialog.find('form').serializeArray(),
							result = {};
						$.each( form, function() {
							if ( '' !== this.value )
						    	result[ this.name ] = this.value;
						});
							if ( ! jQuery.isEmptyObject( result ) ) {
								result.action = 'post_user_feedback_captcha_booster';
							    $.ajax({
							        url: post_feedback.admin_ajax,
							        type: 'POST',
							        data: result,
							        error: function(){},
							        success: function(msg){},
							        beforeSend: function() {
							        	go.addClass('captcha-booster-ajax-progress');
							        },
							        complete: function() {
							        	go.removeClass('captcha-booster-ajax-progress');
							            dialog.dialog( "close" );
							            location.href = deactivateLink;
							        }
							    });
							}
		        	},
	      		},
	      		'cancel' : {
		        	text: 'Cancel',
		        	id: 'captcha-booster-feedback-cancel',
		        	class: 'button button-primary',
		        	click: function() {
		          		$( this ).dialog( "close" );
		        	}
	      		},
	      		'skip' : {
		        	text: 'Skip',
		        	id: 'captcha-booster-feedback-dialog-skip',
		        	click: function() {
		          		$( this ).dialog( "close" );
		          		location.href = deactivateLink;
		        	}
	      		},
	      	}
	    });
	});
});
