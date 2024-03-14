<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_Stock
 * Name: Product Stock
 * Slug: lakit-wooproduct-stock
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_Stock extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-stock';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Stock', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-stock';
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
            'section_stock_setting',
            [
                'label' => esc_html__( 'Preview', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'use_sample',
            [
                'label'        => esc_html__( 'Use Sample', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

        $this->_add_control(
            'use_sample_outofstock',
            [
                'label'        => esc_html__( 'Display OutOfStock', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

         $this->start_controls_section(
            'section_product_stock_style',
            [
                'label' => esc_html__( 'In Stock', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_advanced_icon_control(
            'instock_icon',
            [
                'label' => esc_html__( 'Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'skin'             => 'inline',
                'label_block'      => false,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .stock' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .stock .stock--icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .stock',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .stock .stock--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_v',
            [
                'label' => esc_html__( 'Icon Vertical Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .stock .stock--icon' => 'top: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_outofstock_style',
            [
                'label' => esc_html__( 'Out Of Stock', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_advanced_icon_control(
            'outofstock_icon',
            [
                'label' => esc_html__( 'Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'skin'             => 'inline',
                'label_block'      => false,
            ]
        );

        $this->add_control(
            'outofstock_text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .out-of-stock' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'outofstock_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .out-of-stock .stock--icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'outofstock_text_typography',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .out-of-stock',
            ]
        );

        $this->add_responsive_control(
            'outofstock_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .out-of-stock .stock--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'outofstock_icon_v',
            [
                'label' => esc_html__( 'Icon Vertical Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .out-of-stock .stock--icon' => 'top: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {

        if( lastudio_kit()->elementor()->editor->is_edit_mode() && $this->get_settings_for_display('use_sample') == 'yes' ){
            $instock_icon = $this->_get_icon( 'instock_icon', '<span class="stock--icon">%1$s</span>' );
            $outofstock_icon = $this->_get_icon( 'outofstock_icon', '<span class="stock--icon">%1$s</span>' );
            $stock_html = sprintf('<p class="stock %1$s">%3$s<span class="stock--text">%2$s</span></p>', 'in-stock', 'In stock', $instock_icon);
            if( $this->get_settings_for_display('use_sample_outofstock') == 'yes' ){
                $stock_html .= sprintf('<p class="stock %1$s">%3$s<span class="stock--text">%2$s</span></p>', 'out-of-stock', 'Out of stock', $outofstock_icon);
            }
            echo $stock_html;
        }
        else{
	        $_product_id = $this->get_settings_for_display('product_id');
	        $product_id = !empty($_product_id) ? $_product_id : false;

	        global $product;
	        $product = wc_get_product( $product_id );
            if ( empty( $product ) ) {
                return;
            }
	        $this->add_render_attribute('_wrapper', 'data-product_id', $product->get_id());
            $instock_icon = $this->_get_icon( 'instock_icon', '<span class="stock--icon">%1$s</span>' );
            $outofstock_icon = $this->_get_icon( 'outofstock_icon', '<span class="stock--icon">%1$s</span>' );
            $availability = $product->get_availability();

            if(empty($availability['availability'])){
                return '';
            }

            if($availability['class'] == 'out-of-stock'){
                $stock_icon = $outofstock_icon;
            }
            else{
                $stock_icon = $instock_icon;
            }
            $stock_html = sprintf('<p class="stock %1$s">%3$s<span class="stock--text">%2$s</span></p>', $availability['class'], $availability['availability'], $stock_icon);
            echo apply_filters( 'woocommerce_get_stock_html', $stock_html, $product );
        }
    }

    public function render_plain_content() {}

}