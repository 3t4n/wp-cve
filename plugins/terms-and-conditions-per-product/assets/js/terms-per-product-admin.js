/*! Terms & Conditions Per Product Admin
 */

/**
 * @summary     Terms & Conditions Per Product Admin
 * @description This plugin allows you to set custom Terms and Conditions per WooCommerce product.
 * @version     1.0.0
 * @file        terms-per-product-admin
 * @author      Giannis Kipouros
 * @contact     https://tacpp-pro.com
 *
 */


(function ($) {
	'use strict';

	// On load
	$(document).ready(function ($) {

		/**
		 * Run AJAX request when the user clicks on hide admin notice.
		 */
		$(document).on('click touched', '.tacpp-admin-notice .notice-dismiss, .tacpp-admin-notice .close-notice', function (e) {

			e.preventDefault();
			let noticeID = $(this).closest('.tacpp-admin-notice').attr('id');

			let data = {
				'action': 'tacpp_hide_notice',
				'nonce': tacppObj.ajaxNonce,
				'noticeID': noticeID
			};

			$.post(ajaxurl, data, function (response) {
				$('#' + noticeID).hide();
			});
		});

		$('#tacpp_terms_in_modal.disabled').on('click touched', function (e) {
			console.log('disabled');
		});

		/**
		 * Settings page tooltip
		 */
		// Show tooltip
		if ( $('.forminp .description').length > 0) {
			tippy('.setting-info',	{
				'placement': 'bottom',
				content(reference) {
					let html = reference.closest('fieldset').querySelector('.description').innerHTML;
					return html;
				},
				allowHTML: true
			});
		}

	}); // End document ready
})(jQuery);
