jQuery(document).ready(function ($) {
	/**
	** Update store costs on select store and updated_checkout
	**/
	$(document).on('change', 'select.wps-costs-per-store', function () {
		var id = $('#shipping-pickup-store-select option:selected').data('id');
		wps_js_update_store_costs($(this).find('option:selected').data('cost'));
	});

	$(document).on('updated_checkout', function () {
		var id = $('#shipping-pickup-store-select option:selected').data('id');
		var country = $('#shipping-pickup-store-select option:selected').data('country');
		wps_get_store_data_by_id(id, country);

		if ($('#store_shipping_cost').length) {
			if ($('#store_shipping_cost').val() == '' && $('select.wps-costs-per-store').val() > 0) {
				wps_js_update_store_costs($('select.wps-costs-per-store').find('option:selected').data('cost'));
			}
		}

		wps_load_select2();
	});

	$(document).on('change', '#shipping-pickup-store-select', function () {
		$('body').trigger('update_checkout');
	});

	function wps_js_update_store_costs(cost) {
		$('#store_shipping_cost').val(cost);
		$('body').trigger('update_checkout');
	}

	/**
	** Load select2
	**/
	function wps_load_select2() {
		if (wps_ajax.disable_select2 != undefined && wps_ajax.disable_select2 == 1)
			$('select#shipping-pickup-store-select').select2();
	}
	wps_load_select2();

	/**
	** Set store data on Checkout page
	**/
	function wps_get_store_data_by_id(_id, _country) {
		if ($('.store-template').length && (_country != undefined || _country != '')) {
			var post_template = wp.template('wps-store-details');

			if (wps_ajax.stores[_country] !== undefined) {
				var store = wps_ajax.stores[_country][_id];
				var template_data = {};
				$.each(store, function (index, value) {
					template_data[value.key] = {
						key: value.key,
						value: value.value
					};
				});

				var html = post_template(template_data);

				$('.shipping-pickup-store .store-template').html(html);
				$(document).trigger('pickup_store_selected', [_id]);
			}
		}
	}

	/**
	** Get default shipping country
	** Deprecated since version 1.6.0
	**/
});