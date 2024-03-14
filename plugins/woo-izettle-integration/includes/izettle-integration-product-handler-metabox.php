<?php

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Product_Metabox', false)) {

    class WC_iZettle_Integration_Product_Metabox
    {

        public function __construct()
        {
            if ('yes' == get_option('izettle_show_product_metabox')) {
                add_action('add_meta_boxes', array($this, 'create_izettle_meta_box'));
            }
        }

        public function render_izettle_meta_box($post)
        {

            if (!($updated_time = get_post_meta($post->ID, '_izettle_updated', true))) {
                echo '<p>' . __('Product not updated in Zettle', 'woo-izettle-integration') . '</p>';
            } else {
                echo '<p>' . sprintf(__('Updated: %s', 'woo-izettle-integration'), date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $updated_time + (get_option('gmt_offset') * HOUR_IN_SECONDS))) . '</p>';
                echo '<p>' . sprintf(__('UUID: %s', 'woo-izettle-integration'), get_post_meta($post->ID, 'woocommerce_izettle_product_uuid', true)) . '</p>';
            }

            echo '<input type="hidden" name="izettle_product_field_nonce" value="' . wp_create_nonce() . '">';
        }

        public function create_izettle_meta_box()
        {
            global $post;

            $screen = get_current_screen();
            $post_types = array('product');

            if (in_array($screen->id, $post_types, true) && in_array($post->post_type, $post_types, true)) {
                add_meta_box(
                    'izettle_product_meta_box',
                    __('Zettle', 'woo-izettle-integration'),
                    array($this, 'render_izettle_meta_box'),
                    'product',
                    'side',
                    'default'
                );
            }
        }
    }

    new WC_iZettle_Integration_Product_Metabox();

}
