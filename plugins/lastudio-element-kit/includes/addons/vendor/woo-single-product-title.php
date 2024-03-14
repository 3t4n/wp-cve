<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_Title
 * Name: Product Title
 * Slug: lakit-wooproduct-title
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_Title extends LaStudioKit_Post_Title {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-title';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'title', 'heading', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Title', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-title';
    }

    protected function register_controls() {
        parent::register_controls();


        $this->update_control(
            'html_tag',
            [
                'default' => 'h1',
            ]
        );
    }

}