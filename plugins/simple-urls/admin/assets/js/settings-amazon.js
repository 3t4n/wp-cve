
jQuery(document).ready(function() {
	jQuery(document)
		.on('click', '.btn-save-settings-amazon', save_setting_amazon)
		.on('change', 'input[name="amazon_tracking_id"]', validate_tracking_id_format);

	function save_setting_amazon( event ) {
		event.preventDefault();
		lasso_lite_helper.setProgressZero();
		lasso_lite_helper.scrollTop();

		let amazon_tracking_id = jQuery('#amazon_tracking_id').val().trim();
		let amazon_access_key_id = jQuery('#amazon_access_key_id').val().trim();
		let amazon_secret_key = jQuery('#amazon_secret_key').val().trim();
		let amazon_default_tracking_country = jQuery('#amazon_default_tracking_country').val().trim();
		let amazon_pricing_daily = jQuery('#amazon_pricing_daily:checked').val();
		let btn_save = jQuery('.btn-save-settings-amazon');
		let is_tracking_id_valid = validate_tracking_id_format();
		let current_page = lasso_lite_helper.get_page_name();

		if ( is_tracking_id_valid ) {
			let lasso_lite_update_popup = jQuery('#url-save');
			lasso_lite_helper.add_loading_button( btn_save );
			jQuery.ajax({
				url: lassoLiteOptionsData.ajax_url,
				type: 'post',
				data: {
					action: 'lasso_lite_save_settings_amazon',
					nonce: lassoLiteOptionsData.optionsNonce,
					amazon_tracking_id: amazon_tracking_id,
					amazon_access_key_id: amazon_access_key_id,
					amazon_secret_key: amazon_secret_key,
					amazon_default_tracking_country: amazon_default_tracking_country,
					amazon_pricing_daily: amazon_pricing_daily,
				},
				beforeSend: function (xhr) {
					// Collapse current error + success notifications
					jQuery(".alert.red-bg.collapse").collapse('hide');
					jQuery(".alert.green-bg.collapse").collapse('hide');
					if ( 'surl-onboarding' !== current_page ) {
						lasso_lite_update_popup.modal('show');
						lasso_lite_helper.set_progress_bar( 98, 20 );
					}
				},
			})
				.done(function(res) {
					if ( res.success ) {
						lasso_lite_helper.do_notification(res.data.msg, 'green', 'default-template-notification-amz' );
						lasso_lite_helper.add_loading_button( btn_save, 'Save Changes', false );
					} else {
						lasso_lite_helper.do_notification("Unexpected error!", 'red', 'default-template-notification-amz' );
					}

					// Refresh setup process data
					refresh_setup_progress();
				})
				.always(function() {
					lasso_lite_helper.set_progress_bar_complete();
					setTimeout(function() {
						// Hide update popup by setTimeout to make sure this run after lasso_update_popup.modal('show')
						if ( 'surl-onboarding' !== current_page ) {
							lasso_lite_update_popup.modal('hide');
						}
					}, 1000);
				});

			// Go to next step if we are in Welcome page
			if ( 'surl-onboarding' === current_page ) {
				go_to_next_step_action(btn_save);
			}
		}
	}

	/**
	 * Validate tracking id format if having the value
	 *
	 * @returns {boolean}
	 */
	function validate_tracking_id_format() {
		let is_valid = true;
		let trackingIdInput = jQuery('input[name="amazon_tracking_id"]');
		let trackingId = trackingIdInput.val() || '';
		let trackingIdInvalidMsg = jQuery('#tracking-id-invalid-msg');

		if ( trackingId !== '' ) {
			let re = new RegExp(lassoLiteOptionsData.amazon_tracking_id_regex, "i");
			is_valid = trackingId.match(re);
		}

		if ( is_valid ) {
			trackingIdInput.removeClass('invalid-field');
			trackingIdInvalidMsg.addClass('d-none');
		} else {
			trackingIdInput.addClass('invalid-field');
			trackingIdInvalidMsg.removeClass('d-none');
			jQuery('html, body').animate({
				scrollTop: jQuery('input[name="amazon_tracking_id"]').offset().top - 80
			}, 100);
		}

		return is_valid;
	}
});
