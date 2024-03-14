jQuery(document).ready(function($) {
	$( '#the-list #captcha-bank-plugin-disable-link' ).click(function(e) {
		e.preventDefault();

		var reason = $( '#captcha-bank-feedback-content .captcha-bank-reason' ),
			deactivateLink = $( this ).attr( 'href' );

	    $( "#captcha-bank-feedback-content" ).dialog({
	    	title: 'Quick Feedback Form',
	    	dialogClass: 'captcha-bank-feedback-form',
	      	resizable: false,
	      	minWidth: 430,
	      	minHeight: 300,
	      	modal: true,
	      	buttons: {
	      		'go' : {
		        	text: 'Continue',
        			icons: { primary: "dashicons dashicons-update" },
		        	id: 'captcha-bank-feedback-dialog-continue',
					class: 'button',
		        	click: function() {
		        		var dialog = $(this),
		        			go = $('#captcha-bank-feedback-dialog-continue'),
		          			form = dialog.find('form').serializeArray(),
							result = {};
						$.each( form, function() {
							if ( '' !== this.value )
						    	result[ this.name ] = this.value;
						});
							if ( ! jQuery.isEmptyObject( result ) ) {
								result.action = 'post_user_feedback_captcha_bank';
							    $.ajax({
							        url: post_feedback.admin_ajax,
							        type: 'POST',
							        data: result,
							        error: function(){},
							        success: function(msg){},
							        beforeSend: function() {
							        	go.addClass('captcha-bank-ajax-progress');
							        },
							        complete: function() {
							        	go.removeClass('captcha-bank-ajax-progress');
							            dialog.dialog( "close" );
							            location.href = deactivateLink;
							        }
							    });
								}
	        	},
      		},
      		'cancel' : {
	        	text: 'Cancel',
	        	id: 'captcha-bank-feedback-cancel',
	        	class: 'button button-primary',
	        	click: function() {
	          		$( this ).dialog( "close" );
	        	}
      		},
      		'skip' : {
	        	text: 'Skip',
	        	id: 'captcha-bank-feedback-dialog-skip',
	        	click: function() {
	          		$( this ).dialog( "close" );
	          		location.href = deactivateLink;
	        	}
      		},
      	}
	    });
	});
});
