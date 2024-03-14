(function($) {

	// Set variables
	var singleVariation, priceContainer, defaultPrice, previousPrice, visible;
	singleVariation = $('.product-type-variable .single_variation_wrap .single_variation');
	priceContainer = $('.product-type-variable .summary .price');
	defaultPrice = previousPrice = priceContainer.html();

	/**
	 * Triggered on show_variation and hide_variation
	 */
	function updatePrice(newPrice) {
		if (previousPrice === newPrice) return;
		priceContainer.fadeOut(200, function () {
			if (newPrice !== defaultPrice) {
				priceContainer.addClass('variation-price');
			} else {
				priceContainer.removeClass('variation-price');
			}
			priceContainer.html(newPrice).fadeIn(200);
			previousPrice = newPrice;
		});
	}

	$(".single_variation_wrap").on("show_variation", function (event, variation) {

		var newPrice = $(variation.price_html).html();
		updatePrice(newPrice);

		// Hide default price
		$('.product-type-variable .single_variation_wrap .woocommerce-variation-price').css('display', 'none');

		// Hide variation container if empty
		if ($.trim(singleVariation.find('.woocommerce-variation-description').html()).length == 0 &&
			$.trim(singleVariation.find('.woocommerce-variation-availability').html()).length == 0) {
			singleVariation.stop().removeAttr('style').css('display', 'none');
		} else {
			singleVariation.stop().removeAttr('style');
		}



	});

	$(".single_variation_wrap").on("hide_variation", function(event) {
		updatePrice(defaultPrice);
	});

})(jQuery);
