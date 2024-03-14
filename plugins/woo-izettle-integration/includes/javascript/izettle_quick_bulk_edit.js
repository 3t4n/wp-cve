jQuery(function ($) {
	$('#the-list').on('click', '.editinline', function () {

		/**
		 * Extract metadata and put it as the value for the custom field form
		 */
		inlineEditPost.revert();

		var post_id = $(this).closest('tr').attr('id');

		post_id = post_id.replace("post-", "");

		var $izettle_inline_data = $('#izettle_inline_' + post_id),
			$wc_inline_data = $('#woocommerce_inline_' + post_id),
			nosync = $izettle_inline_data.find(".izettle_nosync").text();

		if ('yes' === nosync) {
			$('.izettle_nosync input').prop('checked', true);
		} else {
			$('.izettle_nosync input').prop('checked', false);
		}

		$('input[name="_izettle_cost_price"]', '.inline-edit-row').val($izettle_inline_data.find(".izettle_cost_price").text());
		$('input[name="_izettle_special_price"]', '.inline-edit-row').val($izettle_inline_data.find(".izettle_special_price").text());

		/**
		 * Only show custom field for appropriate types of products (simple and variable)
		 */
		var product_type = $wc_inline_data.find('.product_type').text();

		if (product_type == 'simple' || product_type == 'variable') {
			$('.izettle_nosync', '.izettle_cost_price', '.izettle_special_price', '.inline-edit-row').show();
		} else {
			$('.izettle_nosync', '.izettle_cost_price', '.izettle_special_price', '.inline-edit-row', '.inline-edit-row').hide();
		}

	});
});