<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_Rating
 * Name: Product Rating
 * Slug: lakit-wooproduct-rating
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_Rating extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-rating';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'rating', 'review', 'comments', 'stars', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Rating', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-rating';
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

        $this->start_controls_section(
            'section_product_rating_style',
            [
                'label' => esc_html__( 'Style', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'lastudio-kit' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woocommerce-review-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '.woocommerce {{WRAPPER}} .woocommerce-review-link',
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => esc_html__( 'Star Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '.woocommerce:not(.rtl) {{WRAPPER}} .star-rating' => 'margin-right: {{SIZE}}{{UNIT}}',
                    '.woocommerce.rtl {{WRAPPER}} .star-rating' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors_dictionary' => [
                    'left'    => 'justify-content: flex-start;',
                    'center' => 'justify-content: center;',
                    'right' => 'justify-content: flex-end;',
                    'justify' => 'justify-content: space-between;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-product-rating' => '{{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! post_type_supports( 'product', 'comments' ) ) {
            return;
        }

	    $_product_id = $this->get_settings_for_display('product_id');
	    $product_id = !empty($_product_id) ? $_product_id : false;

	    global $product;
	    $product = wc_get_product( $product_id );

        if ( empty( $product ) ) {
            return;
        }

	    $this->add_render_attribute('_wrapper', 'data-product_id', $product->get_id());

        wc_get_template( 'single-product/rating.php' );
    }

    public function render_plain_content() {}

}