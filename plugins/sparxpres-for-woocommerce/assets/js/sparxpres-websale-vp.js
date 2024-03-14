/**
 * Add jQuery hook on variation_id change
 */
jQuery(document).ready(function ($) {
	$('input.variation_id').change(function () {
		if ('' !== $('input.variation_id').val()) {
			setTimeout(function () {
				let price = $('div.single_variation_wrap div.woocommerce-variation-price ins span.woocommerce-Price-amount.amount').text();
				if (!price) price = $('div.single_variation_wrap div.woocommerce-variation-price span.woocommerce-Price-amount.amount').text();

				if (price) {
					const priceMatch = /(\d{1,3}[ .,]?\d{3})[.,]?(\d{0,2})/;
					const matches = price.match(priceMatch);
					if (matches) {
						const _price = Math.ceil(Number(matches[1].replace(/[ ,.]/g, '') + '.' + matches[2]));
						window.dispatchEvent(new CustomEvent("sparxpresRuntimeRecalculate", {detail: {price: _price}}));
					}
				}
			}, 400);
		}
	});
});
