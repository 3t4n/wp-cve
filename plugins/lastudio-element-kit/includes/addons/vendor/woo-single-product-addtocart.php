<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_AddToCart
 * Name: Product AddToCart
 * Slug: lakit-wooproduct-addtocart
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_AddToCart extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-addtocart';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Add To Cart', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-add-to-cart';
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

//        if( !$product->is_purchasable()){
//            return;
//        }
        ?>

        <div class="elementor-add-to-cart elementor-product-<?php echo esc_attr( $product->get_type() ); ?>">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>

        <?php
        // On render widget from Editor - trigger the init manually.
        if ( wp_doing_ajax() ) {
            ?>
            <script>
                jQuery(document).trigger('lastudiokit/woocommerce/single/add-to-cart lastudio-kit/woocommerce/single/add-to-cart');
            </script>
            <?php
        }
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
            'section_atc_button_style',
            [
                'label' => esc_html__( 'Button', 'lastudio-kit' ),
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
                'prefix_class' => 'elementor-add-to-cart%s--align-',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .cart button:not(.qty-btn)',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .cart button:not(.qty-btn)',
                'exclude' => [ 'color' ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab( 'button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn)' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn):hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn):hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn):hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_transition',
            [
                'label' => esc_html__( 'Transition Duration', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.2,
                ],
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cart button:not(.qty-btn)' => 'transition: all {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_atc_quantity_style',
            [
                'label' => esc_html__( 'Quantity', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'custom' ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .quantity + .button' => 'margin-left: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .quantity + .button' => 'margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'quantity_typography',
                'selector' => '{{WRAPPER}} .quantity .qty',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'quantity_border',
                'selector' => '{{WRAPPER}} .quantity .qty',
                'exclude' => [ 'color' ],
            ]
        );

        $this->add_control(
            'quantity_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wrap-cart-cta .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'quantity_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wrap-cart-cta .quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'quantity_style_tabs' );

        $this->start_controls_tab( 'quantity_style_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'quantity_text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .quantity' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wrap-cart-cta .quantity' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wrap-cart-cta .quantity' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'quantity_style_focus',
            [
                'label' => esc_html__( 'Focus', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'quantity_text_color_focus',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wrap-cart-cta .quantity:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_bg_color_focus',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wrap-cart-cta .quantity:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_border_color_focus',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wrap-cart-cta .quantity:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_transition',
            [
                'label' => esc_html__( 'Transition Duration', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.2,
                ],
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'transition: all {{SIZE}}s',
                    '{{WRAPPER}} .wrap-cart-cta .quantity' => 'transition: all {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_atc_variations_style',
            [
                'label' => esc_html__( 'Variations', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'variations_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'em', 'custom' ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} form.cart .variations' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'variations_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} form.cart .variations' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_variations_label_style',
            [
                'label' => esc_html__( 'Label', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_label_color_focus',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'variations_label_typography',
                'selector' => '{{WRAPPER}} form.cart table.variations label',
            ]
        );

        $this->add_control(
            'heading_variations_select_style',
            [
                'label' => esc_html__( 'Select field', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_select_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value:before' => 'border: 1px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'variations_select_typography',
                'selector' => '{{WRAPPER}} form.cart table.variations td.value select, div.product.elementor{{WRAPPER}} form.cart table.variations td.value:before',
            ]
        );

        $this->add_control(
            'variations_select_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value:before' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render_plain_content() {}

}