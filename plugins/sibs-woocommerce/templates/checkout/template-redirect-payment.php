<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

?>
<style>
	body, button.wpwl-button-brand {
		display: none;
		background: white;
	}
</style>
<script type="text/javascript">
	var wpwlOptions = {
		onReady: function() {
			jQuery( ".wpwl-form" ).submit();
		}
	}
</script>
<input type="submit" value="Submit" style="display:none" />
<form action="<?php echo esc_attr( $url_config['return_url'] ); ?>" class="paymentWidgets"><?php echo esc_attr( $payment_parameters['payment_brand'] ); ?></form>
