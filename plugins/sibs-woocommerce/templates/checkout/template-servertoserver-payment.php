<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

?>
<style>
	body {
		display: none;
		background: white;
	}
</style>
<script type="text/javascript">
	jQuery( document ).ready( function() {
		jQuery( ".server-to-server" ).submit();
	});
</script>
<form action="<?php echo esc_attr( $url_config['redirect_url'] ); ?>" class='paymentWidgets'>
<?php
foreach ( $payment_parameters as $value ) {
	echo "<input type='hidden' name='" . esc_attr( $value['name'] ) . "' value='" . esc_attr( $value['value'] ) . "' / >";
}
?>
</form>
