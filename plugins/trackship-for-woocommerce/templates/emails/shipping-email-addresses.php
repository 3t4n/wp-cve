<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();
$class = $ts4wc_preview ? 'hide' : '';
?>
<div class="ts4wc_shipping_address <?php echo !$wcast_show_shipping_address ? esc_html($class) : ''; ?>">
	<?php
	if ( !empty($shipping) ) { 
		$shipping_address_label = get_option( 'shipping_address_label', __( 'Shipping address', 'trackship-for-woocommerce' ) );
		?>
		<h2 class="shipment_email_shipping_address_label" style="text-align:<?php echo esc_html( $text_align ); ?>"><?php esc_html_e( $shipping_address_label ); ?></h2>
		<address class="address" style="border:0;padding:0;" ><?php echo wp_kses_post( $shipping ); ?></address>
	<?php } ?>
</div>
<?php if ( !$ts4wc_preview ) { ?>
	<style>
		.ts4wc_shipping_address {
			display: <?php echo $wcast_show_shipping_address ? 'block' : 'none'; ?>;
		}
	</style>
<?php } ?>
