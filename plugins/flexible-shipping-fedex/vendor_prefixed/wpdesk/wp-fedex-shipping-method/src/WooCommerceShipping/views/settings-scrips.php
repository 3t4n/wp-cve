<script type="text/javascript">
	jQuery( function($) {
		let $checkbox_field = jQuery('#woocommerce_flexible_shipping_fedex_enable_shipping_method');
		let description = $checkbox_field.data( 'description' );
		if ( description ) {
			$checkbox_field.parent().parent().append("<p class=\"description\">" + description + "</p>");
		}
	} );
</script>

