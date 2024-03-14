<?php

namespace DarklupLite\Admin;
/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.1.2
 * @author
 * @Websites:
 *
 */
class Woocommerce_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([
            'title' => esc_html__('WOOCOMMERCE SETTINGS', 'darklup-lite'),
            'class' => 'darkluplite-woocommerce-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/woocommerce.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/woocommerce-white.svg'),
            'id' => 'darkluplite_woocommerce_settings'
        ]);

        $this->Multiple_select_box([
            'title' => esc_html__('Exclude WooCommerce Products', 'darklup-lite'),
            'sub_title' => esc_html__('Select the products where you don\'t want to show the dark mode switch', 'darklup-lite'),
            'name' => 'exclude_woo_products',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getWooProducts()
        ]);
        $this->Multiple_select_box([
            'title' => esc_html__('Include WooCommerce Products', 'darklup'),
            'sub_title' => esc_html__('Select the products where you want to show the dark mode switch except all other products', 'darklup'),
            'name' => 'include_woo_products',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getWooProducts()
        ]);
        $this->Multiple_select_box([
            'title' => esc_html__('Exclude WooCommerce Categories', 'darklup'),
            'sub_title' => esc_html__('Select the categories where you don\'t want to show the dark mode switch', 'darklup'),
            'name' => 'exclude_woo_categories',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getWooCategories()
        ]);
        $this->Multiple_select_box([
            'title' => esc_html__('Include WooCommerce Categories', 'darklup'),
            'sub_title' => esc_html__('Select the categories where you want to show the dark mode switch except all other categories', 'darklup'),
            'name' => 'include_woo_categories',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getWooCategories()
        ]);        

        $this->end_fields_section(); // End fields section

    }


}

new Woocommerce_Settings_Tab();