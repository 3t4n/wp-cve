jQuery(document).ready(function() {
	jQuery(document)
		.on('click', '#btn-save-settings-general', save_setting_general);

	function save_setting_general( event ) {
		lasso_lite_helper.setProgressZero();
		lasso_lite_helper.scrollTop();

		let general_disable_tooltip              = jQuery('#general_disable_tooltip').prop("checked") ? 1 : 0;
		let general_disable_amazon_notifications = jQuery('#general_disable_amazon_notifications').prop("checked") ? 1 : 0;
		let general_disable_notification         = jQuery('#general_disable_notification').prop("checked") ? 1 : 0;
		let general_enable_new_ui                = jQuery('#general_enable_new_ui').prop("checked") ? 1 : 0;
		let btn_save                             = jQuery('#btn-save-settings-general');
		let lasso_lite_update_popup              = jQuery('#url-save');

		lasso_lite_helper.add_loading_button( btn_save );
		event.preventDefault();
		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				'action'                               : 'lasso_lite_save_settings_general',
				'nonce'                                : lassoLiteOptionsData.optionsNonce,
				'general_disable_tooltip'              : general_disable_tooltip,
				'general_disable_amazon_notifications' : general_disable_amazon_notifications,
				'general_disable_notification'         : general_disable_notification,
				'general_enable_new_ui'                : general_enable_new_ui,
			},
			beforeSend: function (xhr) {
				// Collapse current error + success notifications
				jQuery(".alert.red-bg.collapse").collapse('hide');
				jQuery(".alert.green-bg.collapse").collapse('hide');
				lasso_lite_update_popup.modal('show');
				lasso_lite_helper.set_progress_bar( 98, 20 );
			},
		})
		.done(function(res) {
			if ( res.success ) {
				lasso_lite_helper.do_notification(res.data.msg, 'green', 'default-template-notification' );
				lasso_lite_helper.add_loading_button( btn_save, 'Save Changes', false );

				if ( res.data.redirect_url !== undefined ) {
					window.location.replace(res.data.redirect_url);
				}
			} else {
				lasso_lite_helper.do_notification("Unexpected error!", 'red', 'default-template-notification' );
			}

			// Refresh setup process data
			refresh_setup_progress();
		})
		.always(function() {
			lasso_lite_helper.set_progress_bar_complete();
			setTimeout(function() {
				// Hide update popup by setTimeout to make sure this run after lasso_update_popup.modal('show')
				lasso_lite_update_popup.modal('hide');
			}, 1000);
		});

	}
});
