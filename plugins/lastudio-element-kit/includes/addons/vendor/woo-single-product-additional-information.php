<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_Additional_Information
 * Name: Product Additional Information
 * Slug: lakit-wooproduct-additional-information
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_Additional_Information extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-additional-information';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Additional Information', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-info';
    }

    protected function register_controls() {

	    $this->start_controls_section(
		    'section_product',
		    [
			    'label' => esc_html__( 'Product', 'lastudio-kit' ),
		    ]
	    );
	    $this->add_control(
		    'wc_product_warning',
		    [
			    'type' => Controls_Manager::RAW_HTML,
			    'raw' => esc_html__( 'Leave a blank to get the data for current product.', 'lastudio-kit' ),
			    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		    ]
	    );
	    $this->add_control(
		    'product_id',
		    [
			    'label' =>  esc_html__( 'Product', 'lastudio-kit' ),
			    'type' => 'lastudiokit-query',
			    'options' => [],
			    'label_block' => true,
			    'autocomplete' => [
				    'object' => 'post',
				    'query' => [
					    'post_type' => [ 'product' ],
				    ],
			    ],
		    ]
	    );
	    $this->end_controls_section();

        $this->start_controls_section( 'section_additional_info_style', [
            'label' => esc_html__( 'General', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'lastudio-kit' ),
                'label_off' => esc_html__( 'Hide', 'lastudio-kit' ),
                'render_type' => 'ui',
                'return_value' => 'yes',
                'default' => 'yes',
                'prefix_class' => 'elementor-show-heading-',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} h2' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '.woocommerce {{WRAPPER}} h2',
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '.woocommerce {{WRAPPER}} .shop_attributes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
	    $_product_id = $this->get_settings_for_display('product_id');
	    $product_id = !empty($_product_id) ? $_product_id : false;

	    global $product;
	    $product = wc_get_product( $product_id );

        if ( empty( $product ) ) {
            return;
        }

	    $this->add_render_attribute('_wrapper', 'data-product_id', $product->get_id());

        wc_get_template( 'single-product/tabs/additional-information.php' );
    }

    public function render_plain_content() {}

}