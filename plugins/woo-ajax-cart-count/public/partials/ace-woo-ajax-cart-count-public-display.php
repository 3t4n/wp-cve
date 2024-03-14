<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/acewebx/#content-plugins
 * @since      1.0.0
 *
 * @package    Ace_Woo_Ajax_Cart_Count
 * @subpackage Ace_Woo_Ajax_Cart_Count/public/partials
 */
if (version_compare(WOOCOMMERCE_VERSION, '2.5.2', '>=')) {
    $cartURL = wc_get_cart_url();
} else {
    $cartURL = $woocommerce->cart->get_cart_url();
}
?>
<a class="cart-customlocation imsAjaxCartCount" style="font-size: <?php echo get_option('imsAjaxCartCount_optionFontSize'); ?>px; color:<?php echo get_option('imsAjaxCartCount_optionColor'); ?>" href="<?php echo $cartURL; ?>" title="<?php _e('View Shopping Cart', 'ace-woo-ajax-cart-count'); ?>">
    <i style="padding: 10px" class="fa <?php echo get_option('imsAjaxCartCount_optionIcon'); ?>" aria-hidden="true"></i>
    <?php echo sprintf(_n('%d item ', '%d items ', $woocommerce->cart->cart_contents_count, 'ace-woo-ajax-cart-count'), $woocommerce->cart->cart_contents_count); ?>&nbsp;<?php echo $woocommerce->cart->get_cart_total(); ?>
</a>