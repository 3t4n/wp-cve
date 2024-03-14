<?php
/**
 * Order again button
 *
 */

defined('ABSPATH') || exit;

$order_again = shop_ready_gl_get_setting('woo_ready_enable_thankyou_order_again_button', 'yes') == 'yes' ? true : false;

if (!$order_again) {
	return;
}

?>

<p class="order-again">
	<a href="<?php echo esc_attr(esc_url($order_again_url)); ?>" class="button"><?php esc_html_e('Order again', 'shopready-elementor-addon'); ?></a>
</p>