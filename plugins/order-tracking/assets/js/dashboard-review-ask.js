jQuery( document ).ready( function( $ ) {

	jQuery( '.ewd-otp-main-dashboard-review-ask' ).css( 'display', 'block' );

  jQuery(document).on( 'click', '.ewd-otp-main-dashboard-review-ask .notice-dismiss', function( event ) {

  	var params = {
		ask_review_time: '7',
		nonce: ewd_otp_review_ask.nonce,
		action: 'ewd_otp_hide_review_ask'
	};

	var data = jQuery.param( params );
	
    jQuery.post( ajaxurl, data, function() {} );
  });

	jQuery( '.ewd-otp-review-ask-yes' ).on( 'click', function() {
		
		jQuery( '.ewd-otp-review-ask-feedback-text' ).removeClass( 'ewd-otp-hidden' );
		jQuery( '.ewd-otp-review-ask-starting-text' ).addClass( 'ewd-otp-hidden' );

		jQuery( '.ewd-otp-review-ask-no-thanks' ).removeClass( 'ewd-otp-hidden' );
		jQuery( '.ewd-otp-review-ask-review' ).removeClass( 'ewd-otp-hidden' );

		jQuery( '.ewd-otp-review-ask-not-really' ).addClass( 'ewd-otp-hidden' );
		jQuery( '.ewd-otp-review-ask-yes' ).addClass( 'ewd-otp-hidden' );

		var params = {
			ask_review_time: '7',
			nonce: ewd_otp_review_ask.nonce,
			action: 'ewd_otp_hide_review_ask'
		};

		var data = jQuery.param( params );
    
    	jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-otp-review-ask-not-really' ).on( 'click', function() {

		jQuery( '.ewd-otp-review-ask-review-text' ).removeClass( 'ewd-otp-hidden' );
		jQuery( '.ewd-otp-review-ask-starting-text' ).addClass( 'ewd-otp-hidden' );

		jQuery( '.ewd-otp-review-ask-feedback-form' ).removeClass( 'ewd-otp-hidden' );
		jQuery( '.ewd-otp-review-ask-actions' ).addClass( 'ewd-otp-hidden' );

		var params = {
			ask_review_time: '1000',
			nonce: ewd_otp_review_ask.nonce,
			action: 'ewd_otp_hide_review_ask'
		};

		var data = jQuery.param( params );
    
    jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-otp-review-ask-no-thanks' ).on( 'click', function() {

		var data = 'ask_review_time=1000&action=ewd_otp_hide_review_ask';
    
    jQuery.post( ajaxurl, data, function() {} );

    jQuery( '.ewd-otp-main-dashboard-review-ask' ).css( 'display', 'none' );
	});

	jQuery( '.ewd-otp-review-ask-review' ).on( 'click', function() {

		jQuery( '.ewd-otp-review-ask-feedback-text' ).addClass( 'ewd-otp-hidden' );
		jQuery( '.ewd-otp-review-ask-thank-you-text' ).removeClass( 'ewd-otp-hidden' );

		var params = {
			ask_review_time: '1000',
			nonce: ewd_otp_review_ask.nonce,
			action: 'ewd_otp_hide_review_ask'
		};

		var data = jQuery.param( params );

    jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-otp-review-ask-send-feedback' ).on( 'click', function() {

		var feedback = jQuery( '.ewd-otp-review-ask-feedback-explanation textarea' ).val();
		var email_address = jQuery( '.ewd-otp-review-ask-feedback-explanation input[name="feedback_email_address"]' ).val();
		var data = 'feedback=' + feedback + '&email_address=' + email_address + '&action=ewd_otp_send_feedback';
        jQuery.post( ajaxurl, data, function() {} );

        var params = {
					ask_review_time: '1000',
					nonce: ewd_otp_review_ask.nonce,
					action: 'ewd_otp_hide_review_ask'
				};

				var data = jQuery.param( params );

        jQuery.post( ajaxurl, data, function() {} );

        jQuery( '.ewd-otp-review-ask-feedback-form' ).addClass( 'ewd-otp-hidden' );
        jQuery( '.ewd-otp-review-ask-review-text' ).addClass( 'ewd-otp-hidden' );
        jQuery( '.ewd-otp-review-ask-thank-you-text' ).removeClass( 'ewd-otp-hidden' );
	});
});