/** Terms and Conditions per product
 * Adds a modal to checkout that embeds the terms URL in an iFrame
 * @since 1.2.0
 */

(function ($) {
	'use strict';

	// On load
	$(document).ready(function ($) {

		function openModal(URL) {

			// Set up terms modal - Template HTML
			let termsModal = tacppModalObj.modal_html.replace('[TERMS_URL]', URL);
			$('body').append(termsModal);
			$('#product-terms-modal').modal({
				fadeDuration: 300
			});
		}

		$(document).on('click touched', '.extra-terms a', function (e) {

			// Bailout if custom terms modal is not enabled
			if(!tacppModalObj.terms_modal) {
				return;
			}

			e.preventDefault();

			// Get URL
			let URL = $(this).attr('href').trim();
			// Manually remove focus from clicked link.
			this.blur();
			// Open modal with URL
			openModal(URL);
		});

		$(document).on('click touched', '.woocommerce-privacy-policy-text .woocommerce-privacy-policy-link', function (e) {

			// Bailout if custom terms modal is not enabled
			if(!tacppModalObj.wc_terms_modal) {
				return;
			}

			e.preventDefault();

			// Get URL
			let URL = $(this).attr('href').trim();
			// Manually remove focus from clicked link.
			this.blur();
			// Open modal with URL
			openModal(URL);
		});

		// Scrap modal on close
		$(document).on($.modal.BEFORE_CLOSE, '#product-terms-modal', function (event, modal) {
			$('#product-terms-modal').remove();
		});


	});
})
(jQuery);
