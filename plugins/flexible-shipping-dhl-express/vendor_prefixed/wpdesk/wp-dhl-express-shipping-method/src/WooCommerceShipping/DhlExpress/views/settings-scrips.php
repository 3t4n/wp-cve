<script type="text/javascript">
	jQuery( function($) {
		let $checkbox_field = jQuery('#woocommerce_flexible_shipping_dhl_express_enable_shipping_method');
		let description = $checkbox_field.data( 'description' );
		if ( description ) {
			$checkbox_field.parent().parent().append("<p class=\"description\">" + description + "</p>");
		}

		let $api_type_field = jQuery('#woocommerce_flexible_shipping_dhl_express_api_type');
		function on_api_type_change() {
			let api_type = $api_type_field.val();
			let $api_fields = jQuery('.dhl-api-field');
			let $headers = jQuery('h3.dhl-api-field');
			$headers.hide().next().hide();
			$api_fields.closest('tr').hide();
			$api_fields.prop('required',false);
			$api_fields = jQuery('.dhl-api-field-'+api_type);
			$api_fields.closest('tr').show();
			$api_fields.prop('required',true);
			$headers= jQuery('h3.dhl-api-field-'+api_type);
			$headers.show().next().show();
		}

		$api_type_field.on('change',on_api_type_change);
		on_api_type_change();

	} );
</script>

