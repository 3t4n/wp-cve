jQuery(document).ready(function() {
	setTimeout(function () {
		jQuery('#fake-intercom-bubble-chat').css('opacity', 1);
		jQuery('#support-launcher').css('opacity', 1);
	}, 2000);

	jQuery(document)
		.on('click', '#enable-support-wrapper .dismiss', function () {
			jQuery("#enable-support").modal('hide');
			lasso_lite_helper.set_local_storage('lasso_lite_should_open_support_modal', 0);
		})
		.on('click', '#btn-save-support', save_support )
		.on('click', '#fake-intercom-bubble-chat', function () {
			jQuery("#enable-support").modal('show');
		})
		.on('click', '#support-launcher', support_launcher )
		.on('click', '#flow-confirm-button', customer_flow_confirm )
		.on('click', '#customer-flow-confirm .no-thanks-button', customer_flow_no_thanks )
		.on('click', '#customer-flow .support-option', intercom_support )
		.ready(function () {
			update_support();
		});

	function save_support() {
		let btn_save = jQuery('#btn-save-support');
		let js_error = jQuery('#enable-support-wrapper .js-error');
		let email = jQuery('#enable-support-wrapper #email').val().trim();
		let is_subscribe = jQuery('#enable-support-wrapper #subscribe').prop("checked");
		if ( email !== '' ) {
			jQuery.ajax({
				url: lassoLiteOptionsData.ajax_url,
				type: 'post',
				data: {
					action: 'lasso_lite_save_support',
					nonce: lassoLiteOptionsData.optionsNonce,
					email: email,
					is_subscribe: is_subscribe,
				},
				beforeSend: function() {
					jQuery('#enable-support-wrapper #email').removeClass('invalid-field');
					js_error.css('display', 'none');
					lasso_lite_helper.add_loading_button(btn_save);
				}
			}).done(function(res) {
				lasso_lite_helper.add_loading_button(btn_save, 'Enable Support', false);
				let data = res.data;
				if ( data.success ) {
					jQuery("#enable-support").modal('hide');

					// Auto open customization display helper after enabling support
					if ( 'undefined' !== typeof window.need_more_customization_flag ) {
						lasso_lite_helper.set_local_storage('lasso_lite_open_need_more_customization', 1);
					}

					// Go to next step if we are in Welcome page
					if ( 'surl-onboarding' === lasso_lite_helper.get_page_name() ) {
						go_to_next_step_action(btn_save);
					} else {
						location.reload();
					}
				} else {
					jQuery('#enable-support-wrapper #email').addClass('invalid-field');
					js_error.text(data.msg);
					js_error.css('display', 'block');
				}
			})
		}
	}

	function update_support() {
		jQuery('input[name="enable_support"]').change(lasso_lite_helper.debounce(function(e) {
			let enable_support = jQuery('input[name="enable_support"]').prop('checked');
			jQuery.ajax({
				url: lassoLiteOptionsData.ajax_url,
				type: 'post',
				data: {
					action: 'lasso_lite_update_support',
					nonce: lassoLiteOptionsData.optionsNonce,
					enable_support: enable_support,
				}
			});
		}, 1000, null, true));
	}

	function support_launcher() {
		let $el_support_launcher         = jQuery('#support-launcher');
		let $customer_flow_confirm_modal = jQuery('#customer-flow-confirm');

		if ( ! $el_support_launcher.data('active') ) {
			jQuery('#support-launcher .icon-default').css({'transform': 'rotate(180deg) scale(.5)', 'opacity': '0'});
			jQuery('#support-launcher .icon-close').css({'transform': 'rotate(0deg) scale(1)', 'opacity': '1'});
			jQuery('.support-wrap').css({'opacity': 1, 'height': 'auto'});
			$el_support_launcher.data('active', true);
		} else {
			jQuery('#support-launcher .icon-default').css({'transform': 'rotate(0deg) scale(1)', 'opacity': '1'});
			jQuery('#support-launcher .icon-close').css({'transform': 'rotate(-180deg) scale(.5)', 'opacity': '0'});
			jQuery('.support-wrap').css({'opacity': 0, 'height': '0'});
			$el_support_launcher.data('active', false);
			Intercom('hide');

			if ( $customer_flow_confirm_modal.hasClass('show') ) {
				$customer_flow_confirm_modal.modal('hide');
			}
		}
	}

	function customer_flow_confirm() {
		let $customer_flow_confirm_btn = jQuery('#flow-confirm-button');
		let customer_flow_confirm      = 1;

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_update_customer_flow_enabled',
				nonce: lassoLiteOptionsData.optionsNonce,
				customer_flow_enabled: customer_flow_confirm,
			},
			beforeSend: function() {
				$customer_flow_confirm_btn.html(lasso_lite_helper.get_loading_image_small())
			}
		}).done(function(res) {
				if ( res.success ) {
					jQuery('#customer-flow').data('customer-flow-enabled', customer_flow_confirm);
					jQuery('#customer-flow-confirm').modal('hide');
					Intercom('show');
				}
		});
	}

	function customer_flow_no_thanks() {
		let $el_support_launcher = jQuery('#support-launcher');

		jQuery('#support-launcher .icon-default').css({'transform': 'rotate(0deg) scale(1)', 'opacity': '1'});
		jQuery('#support-launcher .icon-close').css({'transform': 'rotate(-180deg) scale(.5)', 'opacity': '0'});
		jQuery('.support-wrap').css({'opacity': 0, 'height': '0'});
		$el_support_launcher.data('active', false);
	}

	function intercom_support() {
		let customer_flow_enabled = jQuery('#customer-flow').data('customer-flow-enabled');

		if ( ! customer_flow_enabled ) {
			jQuery('#customer-flow-confirm').modal('show');
		} else {
			Intercom('show');
			jQuery('.support-wrap').css({'opacity': 0, 'height': '0'});
		}
	}
});
