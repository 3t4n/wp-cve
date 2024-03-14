<?php

/**
 * Fired during plugin activation
 */

class WooAmcActivator {

    public static function activate() {

        $default = array(
            'enabled' => 'yes',
            'cart_type' => 'right',
            'button_icon_color' => '#1c8bdf',
            'button_bg_color' => '#dddddd',
            'button_border_radius' => 3,
            'button_position' => 'right-bottom-fixed',
            'button_count_bg' => '#000000',
            'button_count_color' => '#ffffff',
            'bg_color' => '#0a0a0a',
            'bg_opacity' => 60,
            'cart_bg' => '#ffffff',
            'cart_loader_color' => '#1c8bdf',
            'cart_header_bg' => '#fcfcfc',
            'cart_header_title' => 'Shopping Cart',
            'cart_header_title_size' => 24,
            'cart_header_title_color' => '#5b5b5b',
            'cart_header_close_color' => '#dd3333',
            'cart_item_bg' => '#fcfcfc',
            'cart_item_border_width' => 1,
            'cart_item_border_color' => '#f2f2f2',
            'cart_item_border_radius' => 0,
            'cart_item_padding' => 15,
            'cart_item_close_color' => '#dd3333',
            'cart_item_title_color' => '#51b500',
            'cart_item_title_size' => 18,
            'cart_item_text_color' => '#232323',
            'cart_item_text_size' => 16,
            'cart_item_old_price_color' => '#3f3f3f',
            'cart_item_price_color' => '#1c8bdf',
            'cart_item_quantity_buttons_color' => '#ff2828',
            'cart_item_quantity_color' => '#000000',
            'cart_item_quantity_bg' => '#ffffff',
            'cart_item_quantity_border_radius' => 3,
            'cart_item_big_price_size' => 18,
            'cart_item_big_price_color' => '#1c8bdf',
            'cart_footer_bg' => '#1c8bdf',
            'cart_footer_products_size' => 16,
            'cart_footer_products_label' => 'Products',
            'cart_footer_products_label_color' => '#ffffff',
            'cart_footer_products_count_color' => '#ffffff',
            'cart_footer_total_size' => 18,
            'cart_footer_total_label' => 'Total',
            'cart_footer_total_label_color' => '#ffffff',
            'cart_footer_total_price_color' => '#ffffff',
            'cart_footer_link_text' => 'Cart',
            'cart_footer_link_size' => 26,
            'cart_footer_link_color' => '#ffffff'
        );
        $old = get_option('woo_amc_options');
        if (!$old){
            add_option('woo_amc_options',$default);
        }

    }

}