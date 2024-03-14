jQuery(document).ready(function($) {
	jQuery('.ulb-main-dashboard-review-ask').css('display', 'block');

  jQuery(document).on('click', '.ulb-main-dashboard-review-ask .notice-dismiss', function(event) {

  	var params = {
			ask_review_time: '7',
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_hide_review_ask'
		};

		var data = jQuery.param( params );

    jQuery.post(ajaxurl, data, function() {});
  });

	jQuery('.ulb-review-ask-yes').on('click', function() {

		jQuery('.ulb-review-ask-feedback-text').removeClass('ulb-hidden');
		jQuery('.ulb-review-ask-starting-text').addClass('ulb-hidden');

		jQuery('.ulb-review-ask-no-thanks').removeClass('ulb-hidden');
		jQuery('.ulb-review-ask-review').removeClass('ulb-hidden');

		jQuery('.ulb-review-ask-not-really').addClass('ulb-hidden');
		jQuery('.ulb-review-ask-yes').addClass('ulb-hidden');

		var params = {
			ask_review_time: '7',
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_hide_review_ask'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function() {});
	});

	jQuery('.ulb-review-ask-not-really').on('click', function() {

		jQuery('.ulb-review-ask-review-text').removeClass('ulb-hidden');
		jQuery('.ulb-review-ask-starting-text').addClass('ulb-hidden');

		jQuery('.ulb-review-ask-feedback-form').removeClass('ulb-hidden');
		jQuery('.ulb-review-ask-actions').addClass('ulb-hidden');

		var params = {
			ask_review_time: '1000',
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_hide_review_ask'
		};

		var data = jQuery.param( params );
		
		jQuery.post(ajaxurl, data, function() {});
	});

	jQuery('.ulb-review-ask-no-thanks').on('click', function() {

		var params = {
			ask_review_time: '1000',
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_hide_review_ask'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function() {});

    jQuery('.ulb-main-dashboard-review-ask').css('display', 'none');
	});

	jQuery('.ulb-review-ask-review').on('click', function() {

		jQuery('.ulb-review-ask-feedback-text').addClass('ulb-hidden');
		jQuery('.ulb-review-ask-thank-you-text').removeClass('ulb-hidden');

		var params = {
			ask_review_time: '1000',
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_hide_review_ask'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function() {});
	});

	jQuery('.ulb-review-ask-send-feedback').on('click', function() {
		
		var feedback = jQuery('.ulb-review-ask-feedback-explanation textarea').val();
		var email_address = jQuery('.ulb-review-ask-feedback-explanation input[name="feedback_email_address"]').val();
		
		var params = {
			feedback: feedback,
			email_address: email_address,
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_send_feedback'
		};

		var data = jQuery.param( params );
		
		jQuery.post(ajaxurl, data, function() {});

    var params = {
			ask_review_time: '1000',
			nonce: ewd_ulb_review_ask.nonce,
			action: 'ulb_hide_review_ask'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function() {});

    jQuery('.ulb-review-ask-feedback-form').addClass('ulb-hidden');
    jQuery('.ulb-review-ask-review-text').addClass('ulb-hidden');
    jQuery('.ulb-review-ask-thank-you-text').removeClass('ulb-hidden');
	});
});