function shopup_venipak_shipping_dispatch_order_by_id({ id, newDispatch }) {
	const packs = [];
	jQuery('#packages-table tr.venipak-pack').each(function () {
		const row = jQuery(this);
		const packageData = {
			width: row.find(".venipak-pack-width").val(),
			length: row.find(".venipak-pack-length").val(),
			height: row.find(".venipak-pack-height").val(),
			weight: row.find(".venipak-pack-weight").val(),
			description: row.find(".venipak-pack-description").val(),
		}
		packs.push(packageData);
	});
	const data = {
		action: newDispatch ? 'woocommerce_shopup_venipak_shipping_dispatch_force' : 'woocommerce_shopup_venipak_shipping_dispatch',
		order_id: id,
		packs,
	}
	if (jQuery('#shopup_venipak_shipping_global').is(':checked')) {
		data.is_global = true;
	}
	jQuery('#shopup_venipak_shipping_wrapper_order_' + id).html('<span style="visibility: visible;" class="spinner"></span>');
	jQuery.post(ajaxurl, data, function(response) {
		jQuery('#shopup_venipak_shipping_wrapper_order_' + id).html(response);
	});
}
