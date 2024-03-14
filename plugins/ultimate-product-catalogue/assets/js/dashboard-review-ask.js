jQuery( document ).ready( function( $ ) {
	jQuery( '.ewd-upcp-main-dashboard-review-ask' ).css( 'display', 'block' );

  jQuery(document).on( 'click', '.ewd-upcp-main-dashboard-review-ask .notice-dismiss', function( event ) {
		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_hide_review_ask';
		params.ask_review_time = 7;

		var data = jQuery.param( params );
jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-upcp-review-ask-yes' ).on( 'click', function() {
		jQuery( '.ewd-upcp-review-ask-feedback-text' ).removeClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-starting-text' ).addClass( 'ewd-upcp-hidden' );

		jQuery( '.ewd-upcp-review-ask-no-thanks' ).removeClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-review' ).removeClass( 'ewd-upcp-hidden' );

		jQuery( '.ewd-upcp-review-ask-not-really' ).addClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-yes' ).addClass( 'ewd-upcp-hidden' );

		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_hide_review_ask';
		params.ask_review_time = 7;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-upcp-review-ask-not-really' ).on( 'click', function() {
		jQuery( '.ewd-upcp-review-ask-review-text' ).removeClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-starting-text' ).addClass( 'ewd-upcp-hidden' );

		jQuery( '.ewd-upcp-review-ask-feedback-form' ).removeClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-actions' ).addClass( 'ewd-upcp-hidden' );

		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_hide_review_ask';
		params.ask_review_time = 1000;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-upcp-review-ask-no-thanks' ).on( 'click', function() {
		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_hide_review_ask';
		params.ask_review_time = 1000;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );

		jQuery( '.ewd-upcp-main-dashboard-review-ask' ).css( 'display', 'none' );
	});

	jQuery( '.ewd-upcp-review-ask-review' ).on( 'click', function() {
		jQuery( '.ewd-upcp-review-ask-feedback-text' ).addClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-thank-you-text' ).removeClass( 'ewd-upcp-hidden' );

		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_hide_review_ask';
		params.ask_review_time = 1000;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-upcp-review-ask-send-feedback' ).on( 'click', function() {
		var feedback = jQuery( '.ewd-upcp-review-ask-feedback-explanation textarea' ).val();
		var email_address = jQuery( '.ewd-upcp-review-ask-feedback-explanation input[name="feedback_email_address"]' ).val();

		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_send_feedback';
		params.feedback = feedback;
		params.email_address = email_address;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );

		var params = {};

		params.nonce  = ewd_upcp_review_ask.nonce;
		params.action = 'ewd_upcp_hide_review_ask';
		params.ask_review_time = 1000;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );

		jQuery( '.ewd-upcp-review-ask-feedback-form' ).addClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-review-text' ).addClass( 'ewd-upcp-hidden' );
		jQuery( '.ewd-upcp-review-ask-thank-you-text' ).removeClass( 'ewd-upcp-hidden' );
	});
});