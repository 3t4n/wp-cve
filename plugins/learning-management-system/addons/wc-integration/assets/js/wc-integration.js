(function ($) {
	/**
	 * Return ajax url.
	 *
	 * @returns string
	 */
	function getAjaxURL() {
		return _MASTERIYO_WC_INTEGRATION_.ajaxUrl;
	}

	function getListCoursesNonce() {
		return _MASTERIYO_WC_INTEGRATION_.nonces.listCourses;
	}

	function getListCoursesAjaxUrl() {
		return (
			getAjaxURL() +
			'?action=masteriyo_wc_integration_list_courses&nonce=' +
			getListCoursesNonce()
		);
	}

	/**
	 * Initialize course select.
	 */
	$('#masteriyo_course_id').select2({
		selectOnClose: true,
		width: '95%',
		ajax: {
			url: getListCoursesAjaxUrl(),
			dataType: 'json',
			data: function (params) {
				var query = {
					search: params.term,
					page: params.page || 1,
					type: params._type,
				};

				return query;
			},
		},
	});

	if ( _MASTERIYO_WC_INTEGRATION_.isWCSubscriptionActive ) {
		$('#masteriyo_course_id').on('select2:select', function (e) {
			var data = e.params.data;
			if (data.hasOwnProperty('access_mode')) {
				var $pricing = $('.options_group.pricing');
				var $subscriptionPricing = $('.options_group.subscription_pricing');

				$subscriptionPricing.find('._subscription_sign_up_fee_field, ._subscription_trial_length_field').hide();

				if ( 'recurring' === data.access_mode ) {
					$pricing.hide();
					$subscriptionPricing.show();
					$('#product-type').val('mto_course_recurring').change();
					$('option[value="mto_course_recurring"]').show();
					$('option[value="mto_course"]').hide();
				} else {
					$pricing.show();
					$subscriptionPricing.hide();
					$('#product-type').val('mto_course').change();
					$('option[value="mto_course_recurring"]').hide();
					$('option[value="mto_course"]').show();
				}
			}
		});
	}
})(jQuery);
