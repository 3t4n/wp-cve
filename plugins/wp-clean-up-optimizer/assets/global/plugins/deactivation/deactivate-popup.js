jQuery(document).ready(function($) {
	$( '#the-list #clean-up-optimizer-plugin-disable-link' ).click(function(e) {
		e.preventDefault();

		var reason = $( '#clean-up-optimizer-feedback-content .clean-up-optimizer-reason' ),
			deactivateLink = $( this ).attr( 'href' );

	    $( "#clean-up-optimizer-feedback-content" ).dialog({
	    	title: 'Quick Feedback Form',
	    	dialogClass: 'clean-up-optimizer-feedback-form',
	      	resizable: false,
	      	minWidth: 430,
	      	minHeight: 300,
	      	modal: true,
	      	buttons: {
	      		'go' : {
		        	text: 'Continue',
        			icons: { primary: "dashicons dashicons-update" },
		        	id: 'clean-up-optimizer-feedback-dialog-continue',
					class: 'button',
		        	click: function() {
		        		var dialog = $(this),
		        			go = $('#clean-up-optimizer-feedback-dialog-continue'),
		          			form = dialog.find('form').serializeArray(),
							result = {};
						$.each( form, function() {
							if ( '' !== this.value )
						    	result[ this.name ] = this.value;
						});
            if ( ! jQuery.isEmptyObject( result ) ) {
                result.action = 'post_user_feedback_clean_up_optimizer';
                $.ajax({
                    url: post_feedback.admin_ajax,
                    type: 'POST',
                    data: result,
                    error: function(){},
                    success: function(msg){},
                    beforeSend: function() {
                        go.addClass('clean-up-optimizer-ajax-progress');
                    },
                    complete: function() {
                        go.removeClass('clean-up-optimizer-ajax-progress');
                        dialog.dialog( "close" );
                        location.href = deactivateLink;
                    }
                });
                }
		        	},
	      		},
	      		'cancel' : {
		        	text: 'Cancel',
		        	id: 'clean-up-optimizer-feedback-cancel',
		        	class: 'button button-primary',
		        	click: function() {
		          		$( this ).dialog( "close" );
		        	}
	      		},
	      		'skip' : {
		        	text: 'Skip',
		        	id: 'clean-up-optimizer-feedback-dialog-skip',
		        	click: function() {
		          		$( this ).dialog( "close" );
		          		location.href = deactivateLink;
		        	}
	      		},
	      	}
	    });
	});
});
