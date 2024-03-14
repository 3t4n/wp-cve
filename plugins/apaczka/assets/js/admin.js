jQuery(document).ready(function () {
	jQuery('.apaczka_service_select').on('change', function() {
		apaczka_order_meta_settings.change_service(this.value);
	});

	jQuery('.parcel_dimensions_template').on('change', function() {
		apaczka_order_meta_settings.change_parcel_template(this.value);
	});
});

apaczka_order_meta_settings = {
	parcel_type: 'a',

	change_service: function (service_name) {
		if ( 'PACZKOMAT' === service_name ) {
			jQuery('input.package_width, input.package_depth, input.package_height').attr('readonly', true);
			apaczka_order_meta_settings.parcel_type = jQuery('select.parcel_dimensions_template').val();
			apaczka_order_meta_settings.set_parcel_dimensions(apaczka_order_meta_settings.parcel_type);
		} else {
			jQuery('input.package_width, input.package_depth, input.package_height').attr('readonly', false);
			apaczka_order_meta_settings.set_parcel_default_dimensions();
		}
	},

	change_parcel_template: function (parcel_type) {
		apaczka_order_meta_settings.set_parcel_dimensions(parcel_type);
	},

	set_parcel_dimensions: function (parcel_type) {
		jQuery('input.package_width').val(parcel_machine_parcel_sizes[parcel_type]['width']);
		jQuery('input.package_depth').val(parcel_machine_parcel_sizes[parcel_type]['depth']);
		jQuery('input.package_height').val(parcel_machine_parcel_sizes[parcel_type]['height']);
		jQuery('input.package_weight').attr('max', parcel_machine_parcel_sizes[parcel_type]['max_weight']);
	},

	set_parcel_default_dimensions: function() {
		jQuery('input.package_width').val(jQuery('.default_package_width').val());
		jQuery('input.package_depth').val(jQuery('.default_package_depth').val());
		jQuery('input.package_height').val(jQuery('.default_package_height').val());
	}
}