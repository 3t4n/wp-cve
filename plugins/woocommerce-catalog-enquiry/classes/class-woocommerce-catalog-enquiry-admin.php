<?php

class Woocommerce_Catalog_Enquiry_Admin {

    public $settings;

    public function __construct() {
        $this->load_class('settings');
        $this->settings = new Woocommerce_Catalog_Enquiry_Settings();
        $this->init_product_settings();
    }

    function load_class($class_name = '') {
        global $Woocommerce_Catalog_Enquiry;
        if ('' != $class_name) {
            require_once ($Woocommerce_Catalog_Enquiry->plugin_path . '/admin/class-' . esc_attr($Woocommerce_Catalog_Enquiry->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

    public function init_product_settings() {
        global $Woocommerce_Catalog_Enquiry;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $options_button_appearence_settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        if (isset($settings['is_enable']) && mvx_catalog_get_settings_value($settings['is_enable'], 'checkbox') == "Enable") {
            if (isset($options_button_appearence_settings['button_type']) && mvx_catalog_get_settings_value($options_button_appearence_settings['button_type'], 'select') == 3) {
                add_filter('woocommerce_product_data_tabs', array($this, 'catalog_product_data_tabs'), 99);
                add_action('woocommerce_product_data_panels', array($this, 'catalog_product_data_panel'));
                add_action('woocommerce_process_product_meta_simple', array($this, 'save_catalog_data'));
                add_action('woocommerce_process_product_meta_grouped', array($this, 'save_catalog_data'));
                add_action('woocommerce_process_product_meta_external', array($this, 'save_catalog_data'));
                add_action('woocommerce_process_product_meta_variable', array($this, 'save_catalog_data'));
            }
        }
    }

    public function catalog_product_data_tabs($tabs) {
        $tabs['woocommerce_catalog_enquiry'] = array(
            'label' => __('Catalog Enquiry', 'woocommerce-catalog-enquiry'),
            'target' => 'woocommerce-catalog-enquiry-product-data',
            'class' => array(''),
        );
        return $tabs;
    }

    /**
     * Save meta.
     *
     * Save the product catalog enquiry meta data.
     *
     * @since 1.0.0
     *
     * @param int $post_id ID of the post being saved.
     */
    public function save_catalog_data($post_id) {
        update_post_meta($post_id, 'woocommerce_catalog_enquiry_product_link', esc_url($_POST['woocommerce_catalog_enquiry_product_link']));
    }

    /**
     * Output catalog individual product link.
     *
     * Output settings to the product link tab.
     *
     * @since 1.0.0
     */
    public function catalog_product_data_panel() {
        ?><div id="woocommerce-catalog-enquiry-product-data" class="panel woocommerce_options_panel"><?php
        woocommerce_wp_text_input(array(
            'id' => 'woocommerce_catalog_enquiry_product_link',
            'label' => __('Enter product external link', 'woocommerce-catalog-enquiry'),
            'placeholder' => __('https://www.google.com', 'woocommerce-catalog-enquiry')
        ));
        ?></div><?php
        }
    }

    

    