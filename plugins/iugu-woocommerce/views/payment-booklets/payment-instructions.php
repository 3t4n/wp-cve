<?php

/**
 * Credit Card - Payment instructions.
 *
 * @author  Iugu
 * @package Iugu_WooCommerce/Templates
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

?>

<div class="woocommerce-message">
	<span>
		<?php echo sprintf(__('Payment successfully made using payment booklets in %s.', IUGU), '<strong>' . $installments . 'x</strong>'); ?>
	</span>
	<br />
	<span><a class="button" href="<?php echo esc_url($secure_url); ?>" target="_blank">
			<?php _e('Pay the bank slip', IUGU); ?>
		</a>
		<?php _e('Please click in the following button to view your bank slip.', IUGU); ?>
		<br />
		<?php _e('You can print and pay it on your internet banking or in a lottery retailer.', IUGU); ?>
		<br />
		<?php _e('After we receive the bank slip payment confirmation, your order will be processed.', IUGU); ?>
	</span>
	<br />
	<span>
		<?php echo sprintf(__('Other invoices available in "%s".', IUGU), '<a href="' . wc_get_account_endpoint_url('orders') . '">' . __('Orders', 'woocommerce') . '</a>'); ?>
	</span>
	<?php ?>
</div>