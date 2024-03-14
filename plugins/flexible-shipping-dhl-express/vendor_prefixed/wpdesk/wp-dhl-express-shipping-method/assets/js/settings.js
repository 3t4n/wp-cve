jQuery(document).ready(function () {

	let is_dutiable_field = jQuery('#woocommerce_flexible_shipping_dhl_express_is_dutiable');
	let countries_select_field = jQuery('#woocommerce_flexible_shipping_dhl_express_dutiable_selected_countries');

	if ( is_dutiable_field.length && countries_select_field.length ) {

		function show_or_hide_selected_countries() {
			let is_dutiable_value = is_dutiable_field.val();
			let show_countries_select = is_dutiable_value === 'selected_countries' || is_dutiable_value === 'except_selected_countries';

			countries_select_field.closest( 'tr' ).toggle( show_countries_select );
		}

		is_dutiable_field.change(function () {
			show_or_hide_selected_countries();
		});

		show_or_hide_selected_countries();
	}

});
