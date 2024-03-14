<?php

namespace TABS_RES_PLUGINS\Extension\WooCommerce\Inc;

/**
 *
 * @author biplo
 */
trait MetaData {

    public function save_icon_meta_box_content($post_id) {
        if (!isset($_POST['responsive_tabs_meta_box_icon_nonce'])) {
            return;
        }


        if (!wp_verify_nonce($_POST['responsive_tabs_meta_box_icon_nonce'], 'responsive_tabs_meta_box_icon')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (RESPONSIVE_TABS_WOOCOMMERCE_POST_TYPE != $_POST['post_type']) {
            return;
        }

        if (isset($_POST['responsive-woo-tab-icon']) && !empty($_POST['responsive-woo-tab-icon'])) {
            $icon = $_POST['responsive-woo-tab-icon'];

            update_post_meta($post_id, 'responsive_woo_tabs_icon', $icon);
        }
    }

    public function save_callback_meta_box_content($post_id) {
        if (!isset($_POST['render_meta_box_callback_function_nonce'])) {
            return;
        }


        if (!wp_verify_nonce($_POST['render_meta_box_callback_function_nonce'], 'render_meta_box_callback_function')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (RESPONSIVE_TABS_WOOCOMMERCE_POST_TYPE != $_POST['post_type']) {
            return;
        }

        if (isset($_POST['responsive-woo-tab-callback'])) {
            $callback = $_POST['responsive-woo-tab-callback'];

            update_post_meta($post_id, 'responsive_woo_tabs_callback', $callback);
        }
    }

}
