<?php
defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
$payment_method = XLWCTY_Compatibility::get_order_data( $order_data, 'payment_method' );
remove_action( 'wp_footer', array( XLWCTY_Core()->public, 'execute_wc_thankyou_hooks' ), 1 );
ob_start();
do_action( 'woocommerce_thankyou', XLWCTY_Compatibility::get_order_id( $order_data ) );
do_action( "woocommerce_thankyou_{$payment_method}", XLWCTY_Compatibility::get_order_id( $order_data ) );
$get_content = ob_get_clean();

/**
 * Checking for the content
 */
$parsed_content = preg_replace( '/(<(script|style)\b[^>]*>).*?(<\/\2>)/is', '', $get_content );
$parsed_content = strip_tags( $parsed_content );
$parsed_content = trim( $parsed_content );
if ( '' !== $parsed_content ) {
	?>
    <div class="xlwcty_Box xlwcty_textBox xlwcty-wc-thankyou"><?php echo $get_content; ?>
    </div>
	<?php
} else {
	?>
    <div style="display: none;"><?php echo $get_content; ?>
    </div>
	<?php
}
