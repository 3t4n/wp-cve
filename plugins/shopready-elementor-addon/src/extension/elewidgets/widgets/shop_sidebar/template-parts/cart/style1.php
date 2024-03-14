<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * ShopSidebar Cart Content | default
 * @since 1.0
 */
if (shop_ready_is_elementor_mode() && $settings['disable_cart_notice'] != 'yes' && isset(WC()->cart) && WC()->cart->get_cart_contents_count() == 0) {
    echo wp_kses_post('<div class="elementor-alert elementor-alert-info" role="alert">
    <span class="elementor-alert-title">Editor Mode Notice</span>
                    <span class="elementor-alert-description"> Add product to cart .</span>
                                <button type="button" class="elementor-alert-dismiss">
            <span aria-hidden="true">×</span>
            <span class="elementor-screen-only">Dismiss alert</span>
        </button>
            </div>');

}
echo wp_kses_post('<div class="widget_shopping_cart_content"></div>');