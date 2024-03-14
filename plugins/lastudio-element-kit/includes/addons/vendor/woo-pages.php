<?php

/**
 * Class: LaStudioKit_Woo_Pages
 * Name: WooCommerce Pages
 * Slug: lakit-woopages
 */

namespace Elementor;

if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Woo_Pages Widget
 */
class LaStudioKit_Woo_Pages extends LaStudioKit_Base {

  protected function enqueue_addon_resources() {
    if ( ! lastudio_kit_settings()->is_combine_js_css() ) {
      $this->add_style_depends( 'lastudio-kit-woocommerce' );
      $this->add_script_depends( 'lastudio-kit-base' );
    }
  }

  public function get_name() {
    return 'lakit-woopages';
  }

  public function get_categories() {
    return [ 'lastudiokit-woocommerce' ];
  }

  public function get_keywords() {
    return [
      'woocommerce',
      'shop',
      'store',
      'cart',
      'checkout',
      'account',
      'order tracking',
      'shortcode',
      'product',
    ];
  }

  protected function get_widget_title() {
    return esc_html__( 'WooCommerce Pages', 'lastudio-kit' );
  }

  public function get_icon() {
    return 'eicon-product-pages';
  }

  public function add_product_post_class( $classes ) {
    $classes[] = 'product';

    return $classes;
  }

  public function add_products_post_class_filter() {
    add_filter( 'post_class', [ $this, 'add_product_post_class' ] );
  }

  public function remove_products_post_class_filter() {
    remove_filter( 'post_class', [ $this, 'add_product_post_class' ] );
  }

  protected function register_controls() {
    $this->_start_controls_section(
      'section_product',
      [
        'label' => esc_html__( 'Type', 'lastudio-kit' ),
      ]
    );

    $page_options = [
      ''                           => '— ' . esc_html__( 'Select', 'lastudio-kit' ) . ' —',
      'woocommerce_cart'           => esc_html__( 'Cart Page', 'lastudio-kit' ),
//            'product_page' => esc_html__( 'Single Product Page', 'lastudio-kit' ),
      'woocommerce_checkout'       => esc_html__( 'Checkout Page', 'lastudio-kit' ),
      'woocommerce_order_tracking' => esc_html__( 'Order Tracking Form', 'lastudio-kit' ),
      'woocommerce_my_account'     => esc_html__( 'My Account', 'lastudio-kit' ),
    ];

    if ( lastudio_kit()->get_theme_support( 'lastudio-kit' ) ) {
      $page_options['la_wishlist'] = esc_html__( 'LaStudio Wishlist', 'lastudio-kit' );
      $page_options['la_compare']  = esc_html__( 'LaStudio Compare', 'lastudio-kit' );
    }

    $this->add_control(
      'element',
      [
        'label'   => esc_html__( 'Page', 'lastudio-kit' ),
        'type'    => Controls_Manager::SELECT,
        'options' => $page_options,
      ]
    );

    $this->add_control(
      'product_id',
      [
        'label'        => esc_html__( 'Product', 'lastudio-kit' ),
        'type'         => 'lastudiokit-query',
        'options'      => [],
        'label_block'  => true,
        'autocomplete' => [
          'object' => 'post',
          'query'  => [
            'post_type' => [ 'product' ],
          ],
        ],
        'condition'    => [
          'element' => [ 'product_page' ],
        ],
      ]
    );

    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_general',
      [
        'label' => esc_html__( 'General', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'text_typography',
        'selector' => '{{WRAPPER}} .woocommerce',
      ]
    );
    $this->add_control(
      'text_color',
      [
        'label'     => esc_html__( 'Text Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'link_color',
      [
        'label'     => esc_html__( 'Link Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce a' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'link_hover_color',
      [
        'label'     => esc_html__( 'Link Hover Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce a:hover' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_heading',
      [
        'label' => esc_html__( 'Heading', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_control(
      'heading_color',
      [
        'label'     => esc_html__( 'Heading Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce h3, {{WRAPPER}} .woocommerce h2' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'heading_typography',
        'selector' => '{{WRAPPER}} .woocommerce h3, {{WRAPPER}} .woocommerce h2',
      ]
    );
    $this->add_responsive_control(
      'heading_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} .woocommerce h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'heading_margin',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} .woocommerce h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'heading_border',
        'selector' => '{{WRAPPER}} .woocommerce h3, {{WRAPPER}} .woocommerce h2',
      ]
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'heading_shadow',
        'selector' => '{{WRAPPER}} .woocommerce h3, {{WRAPPER}} .woocommerce h2',
      ]
    );
    $this->_end_controls_section();
    $this->_start_controls_section(
      'section_tabs',
      [
        'label' => esc_html__( 'Tabs', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_responsive_control(
      'tabs_gap',
      array(
        'label'      => esc_html__( 'Items gap', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ 'px', 'em', 'vh', 'vw' ],
        'default'    => [
          'size' => 0,
          'unit' => 'px'
        ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation ul'  => 'gap: {{SIZE}}{{UNIT}};',
        ]
      )
    );
    $this->add_control(
      'tabs_bgcolor',
      [
        'label'     => esc_html__( 'Background Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation' => 'background-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tabs_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'tabs_margin',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->start_controls_tabs( 'section_tabs__tabs' );
    $this->start_controls_tab( 'section_tabs__tab_normal', [
      'label' => esc_html__( 'Normal', 'lastudio-kit' ),
    ] );
    $this->add_control(
      'tab_color',
      [
        'label'     => esc_html__( 'Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation a' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'tab_bgcolor',
      [
        'label'     => esc_html__( 'Background Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation a' => 'background-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_font',
        'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation a',
      ]
    );
    $this->add_responsive_control(
      'tab_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->end_controls_tab();

    $this->start_controls_tab( 'section_tabs__tab_active', [
      'label' => esc_html__( 'Active', 'lastudio-kit' ),
    ] );
    $this->add_control(
      'tab_active_color',
      [
        'label'     => esc_html__( 'Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation li:hover a' => 'color: {{VALUE}};',
          '{{WRAPPER}} .woocommerce-MyAccount-navigation li.is-active a' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'tab_active_bgcolor',
      [
        'label'     => esc_html__( 'Background Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation li:hover a' => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .woocommerce-MyAccount-navigation li.is-active a' => 'background-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tab_active_font',
        'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation li:hover a,{{WRAPPER}} .woocommerce-MyAccount-navigation li.is-active a',
      ]
    );
    $this->add_responsive_control(
      'tab_active_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} .woocommerce-MyAccount-navigation li:hover a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} .woocommerce-MyAccount-navigation li.is-active a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->end_controls_tab();
    $this->end_controls_tabs();


    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_form',
      [
        'label' => esc_html__( 'Form', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_control(
      'section_form__label',
      [
        'label' => esc_html__( 'Label', 'lastudio-kit' ),
        'type'  => Controls_Manager::HEADING,
      ]
    );
    $this->add_control(
      'label_color',
      [
        'label'     => esc_html__( 'Label Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form .form-row > label:not(.checkbox)' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'label_r_color',
      [
        'label'     => esc_html__( 'Require Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form .form-row > label:not(.checkbox) .required' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'label_typography',
        'selector' => '{{WRAPPER}} form .form-row > label:not(.checkbox)',
      ]
    );
    $this->add_responsive_control(
      'label_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} form .form-row > label:not(.checkbox)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'label_margin',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} form .form-row > label:not(.checkbox)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'label_border',
        'selector' => '{{WRAPPER}} form .form-row > label:not(.checkbox)',
      ]
    );

    $this->add_control(
      'section_form__input',
      [
        'label'     => esc_html__( 'Input', 'lastudio-kit' ),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );
    $this->add_control(
      'input_color',
      [
        'label'     => esc_html__( 'Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form .input-text'                      => 'color: {{VALUE}};',
          '{{WRAPPER}} .select2-container .select2-selection' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'input_bgcolor',
      [
        'label'     => esc_html__( 'Background Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form .input-text'                      => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .select2-container .select2-selection' => 'background-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'input_focus_color',
      [
        'label'     => esc_html__( 'Focus Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form .input-text:focus'                      => 'color: {{VALUE}};',
          '{{WRAPPER}} .select2-container:hover .select2-selection' => 'color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'input_focus_bgcolor',
      [
        'label'     => esc_html__( 'Focus Background Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form .input-text:focus'                      => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .select2-container:hover .select2-selection' => 'background-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'input_typography',
        'selector' => '{{WRAPPER}} form .input-text, {{WRAPPER}} .select2-container .select2-selection',
      ]
    );
    $this->add_responsive_control(
      'input_height',
      array(
        'label'      => esc_html__( 'Input Height', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ 'px', 'em', 'vh', 'vw' ],
        'default'    => [
          'size' => 50,
          'unit' => 'px'
        ],
        'selectors'  => [
          '{{WRAPPER}} form'                                       => '--input-height: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} form .input-text'                           => 'height: {{SIZE}}{{UNIT}}',
          '{{WRAPPER}} form .select2-container .select2-selection' => 'height: {{SIZE}}{{UNIT}}',
        ]
      )
    );
    $this->add_responsive_control(
      'input_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} form .input-text'                           => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} form .select2-container .select2-selection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'input_margin',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} form .input-text'                           => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} form .select2-container .select2-selection' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_responsive_control(
      'input_radius',
      [
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
        'selectors'  => [
          '{{WRAPPER}} form'                                       => '--input-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} form .input-text'                           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} form .select2-container .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'input_border',
        'selector' => '{{WRAPPER}} form .input-text, {{WRAPPER}} .select2-container .select2-selection',
      ]
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'input_shadow',
        'selector' => '{{WRAPPER}} form .input-text, {{WRAPPER}} .select2-container .select2-selection',
      ]
    );

    $this->add_control(
      'section_form__checkbox',
      [
        'label'     => esc_html__( 'Radio / Checkbox', 'lastudio-kit' ),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'label_cb_color',
      [
        'label'     => esc_html__( 'Label Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form input[type=radio]+label'    => 'color: {{VALUE}};',
          '{{WRAPPER}} form input[type=checkbox]+label' => 'color: {{VALUE}};',
          '{{WRAPPER}} form label.checkbox'             => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'label_cb_border_color',
      [
        'label'     => esc_html__( 'Normal Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form' => '--cb-border-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'label_cbc_border_color',
      [
        'label'     => esc_html__( 'Checked Color', 'elementor' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} form' => '--cb-checked-border-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'label_cb_typography',
        'selector' => '{{WRAPPER}} form input[type=radio]+label, {{WRAPPER}} input[type=checkbox]+label,{{WRAPPER}} form label.checkbox',
      ]
    );
    $this->end_controls_section();

    $this->start_controls_section(
      'section_button',
      [
        'label' => esc_html__( 'Form Button', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->start_controls_tabs( 'section_button_tabs' );
    $this->start_controls_tab( 'section_button_tab__normal', [
      'label' => esc_html__( 'Normal', 'lastudio-kit' ),
    ] );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'button_font',
        'selector' => '{{WRAPPER}} form .button:not(.alt)',
      ]
    );
    $this->add_control(
      'button_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button:not(.alt)' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'button_bgcolor',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button:not(.alt)' => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->add_responsive_control(
      'button_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button:not(.alt)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_responsive_control(
      'button_margin',
      array(
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button:not(.alt)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'button_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} form .button:not(.alt)',
      ]
    );

    $this->_add_responsive_control(
      'button_radius',
      array(
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button:not(.alt)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'button_shadow',
        'selector' => '{{WRAPPER}} form .button:not(.alt)',
      ]
    );
    $this->end_controls_tab();

    $this->start_controls_tab( 'section_button_tab__hover', [
      'label' => esc_html__( 'Hover', 'lastudio-kit' ),
    ] );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'button_hover_font',
        'selector' => '{{WRAPPER}} form .button:not(.alt):hover',
      ]
    );
    $this->add_control(
      'button_hover_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button:not(.alt):hover' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'button_hover_bgcolor',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button:not(.alt):hover' => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->add_responsive_control(
      'button_hover_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button:not(.alt):hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_responsive_control(
      'button_hover_margin',
      array(
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button:not(.alt):hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'button_hover_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} form .button:not(.alt):hover',
      ]
    );

    $this->_add_responsive_control(
      'button_hover_radius',
      array(
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button:not(.alt):hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'button_hover_shadow',
        'selector' => '{{WRAPPER}} form .button:not(.alt):hover',
      ]
    );
    $this->end_controls_tab();
    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->start_controls_section(
      'section_altbutton',
      [
        'label' => esc_html__( 'Form Alt Button', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->start_controls_tabs( 'section_altbutton_tabs' );
    $this->start_controls_tab( 'section_altbutton_tab__normal', [
      'label' => esc_html__( 'Normal', 'lastudio-kit' ),
    ] );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'altbutton_font',
        'selector' => '{{WRAPPER}} form .button.alt',
      ]
    );
    $this->add_control(
      'altbutton_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button.alt' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'altbutton_bgcolor',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button.alt' => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->add_responsive_control(
      'altbutton_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button.alt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_responsive_control(
      'altbutton_margin',
      array(
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button.alt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'altbutton_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} form .button.alt',
      ]
    );

    $this->_add_responsive_control(
      'altbutton_radius',
      array(
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button.alt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'altbutton_shadow',
        'selector' => '{{WRAPPER}} form .button.alt',
      ]
    );
    $this->end_controls_tab();

    $this->start_controls_tab( 'section_altbutton_tab__hover', [
      'label' => esc_html__( 'Hover', 'lastudio-kit' ),
    ] );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'altbutton_hover_font',
        'selector' => '{{WRAPPER}} form .button.alt:hover',
      ]
    );
    $this->add_control(
      'altbutton_hover_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button.alt:hover' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'altbutton_hover_bgcolor',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} form .button.alt:hover' => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->add_responsive_control(
      'altbutton_hover_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button.alt:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_responsive_control(
      'altbutton_hover_margin',
      array(
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button.alt:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'altbutton_hover_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} form .button.alt:hover',
      ]
    );

    $this->_add_responsive_control(
      'altbutton_hover_radius',
      array(
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} form .button.alt:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'altbutton_hover_shadow',
        'selector' => '{{WRAPPER}} form .button.alt:hover',
      ]
    );
    $this->end_controls_tab();
    $this->end_controls_tabs();

    $this->end_controls_section();

    $this->_start_controls_section(
      'section_table',
      [
        'label' => esc_html__( 'Table', 'lastudio-kit' ),
        'tab'       => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'table_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} table',
      ]
    );
    $this->add_control(
      'section_table__title',
      [
        'label' => esc_html__( 'Titles & Totals', 'lastudio-kit' ),
        'type'  => Controls_Manager::HEADING,
      ]
    );
    $this->add_control(
      'table_title_color',
      array(
        'label'     => esc_html__( 'Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} table th' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'table_title_font',
        'selector' => '{{WRAPPER}} table th',
      ]
    );
    $this->add_control(
      'section_table__items',
      [
        'label' => esc_html__( 'Items', 'lastudio-kit' ),
        'type'  => Controls_Manager::HEADING,
      ]
    );
    $this->add_control(
      'table_item_color',
      array(
        'label'     => esc_html__( 'Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} table td' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'table_item_link_color',
      array(
        'label'     => esc_html__( 'Link Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} .woocommerce td a' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'table_item_font',
        'selector' => '{{WRAPPER}} table td',
      ]
    );
    $this->add_responsive_control(
      'table_item_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_control(
      'section_table__buttons',
      [
        'label' => esc_html__( 'Buttons', 'lastudio-kit' ),
        'type'  => Controls_Manager::HEADING,
      ]
    );
    $this->start_controls_tabs( 'section_tblbutton_tabs' );
    $this->start_controls_tab( 'section_tblbutton_tab__normal', [
      'label' => esc_html__( 'Normal', 'lastudio-kit' ),
    ] );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tblbutton_font',
        'selector' => '{{WRAPPER}} .woocommerce table .button',
      ]
    );
    $this->add_control(
      'tblbutton_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} .woocommerce table .button' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'tblbutton_bgcolor',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} .woocommerce table .button' => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->add_responsive_control(
      'tblbutton_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_responsive_control(
      'tblbutton_margin',
      array(
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'tblbutton_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} .woocommerce table .button',
      ]
    );

    $this->_add_responsive_control(
      'tblbutton_radius',
      array(
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'tblbutton_shadow',
        'selector' => '{{WRAPPER}} .woocommerce table .button',
      ]
    );
    $this->end_controls_tab();

    $this->start_controls_tab( 'section_tblbutton_tab__hover', [
      'label' => esc_html__( 'Hover', 'lastudio-kit' ),
    ] );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'tblbutton_hover_font',
        'selector' => '{{WRAPPER}} .woocommerce table .button:hover',
      ]
    );
    $this->add_control(
      'tblbutton_hover_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} .woocommerce table .button:hover' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'tblbutton_hover_bgcolor',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} .woocommerce table .button:hover' => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->add_responsive_control(
      'tblbutton_hover_padding',
      array(
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table .button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_responsive_control(
      'tblbutton_hover_margin',
      array(
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table .button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'tblbutton_hover_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} .woocommerce table .button:hover',
      ]
    );

    $this->_add_responsive_control(
      'tblbutton_hover_radius',
      array(
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} .woocommerce table .button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'tblbutton_hover_shadow',
        'selector' => '{{WRAPPER}} .woocommerce table .button:hover',
      ]
    );
    $this->end_controls_tab();
    $this->end_controls_tabs();
    $this->end_controls_section();
  }

  private function get_shortcode() {
    $settings = $this->get_settings();

    switch ( $settings['element'] ) {
      case '':
        return '';
        break;

      case 'product_page':
        if ( ! empty( $settings['product_id'] ) ) {
          $product_data = get_post( $settings['product_id'] );
          $product      = ! empty( $product_data ) && in_array( $product_data->post_type, [
            'product',
            'product_variation'
          ] ) ? wc_setup_product_data( $product_data ) : false;
        }

        if ( empty( $product ) && current_user_can( 'manage_options' ) ) {
          return esc_html__( 'Please set a valid product', 'lastudio-kit' );
        }

        $this->add_render_attribute( 'shortcode', 'id', $settings['product_id'] );
        break;

      case 'woocommerce_cart':
      case 'woocommerce_checkout':
      case 'woocommerce_order_tracking':
        break;
    }

    $shortcode = sprintf( '[%s %s]', $settings['element'], $this->get_render_attribute_string( 'shortcode' ) );

    return $shortcode;
  }

  private function render_myaccount_html_editor() {
    $pages = wc_get_account_menu_items();
    if ( isset( $pages['customer-logout'] ) ) {
      unset( $pages['customer-logout'] );
    }
    ob_start();
    ?>
    <div class="woocommerce" data-subkey="my_account">
      <?php
      wc_get_template( 'myaccount/navigation.php' );
      foreach ( $pages as $key => $val ) {
        ?>
        <div class="woocommerce-MyAccount-content" data-tkey="<?php echo esc_attr( $key ); ?>">
          <?php
          if ( $key === 'dashboard' ) {
            wc_get_template(
              'myaccount/dashboard.php',
              array( 'current_user' => get_user_by( 'id', get_current_user_id() ) )
            );
          } else {
            do_action( 'woocommerce_account_' . $key . '_endpoint', '' );
          }
          ?>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
    $html = ob_get_clean();

    return $html;
  }

  protected function render() {
    $shortcode = $this->get_shortcode();

    if ( empty( $shortcode ) ) {
      return;
    }

    $element = $this->get_settings_for_display( 'element' );

    if ( $element == 'woocommerce_cart' ) {
      $this->add_render_attribute( '_wrapper', 'class', 'woocommerce-cart' );
    }

    $this->add_products_post_class_filter();

    if ( $element !== 'woocommerce_my_account' ) {
      $html = do_shortcode( $shortcode );
    } else {
      if ( ! lastudio_kit()->elementor()->editor->is_edit_mode() ) {
        $html = do_shortcode( $shortcode );
      } else {
        $html = $this->render_myaccount_html_editor();
      }
    }

    $html = str_replace( [ 'woocommerce-form__label-for-checkbox' ], [ 'woocommerce-form__label-for-checkbox checkbox' ], $html );

    if ( 'woocommerce_checkout' === $this->get_settings_for_display( 'element' ) && ( '<div class="woocommerce"></div>' === $html || '<div class="woocommerce"><div class="woocommerce-notices-wrapper"></div></div>' === $html ) ) {
      $html = '<div class="woocommerce">';
      ob_start();
      wc_get_template( 'cart/cart-empty.php' );
      $html .= ob_get_clean();
      $html .= '</div>';
    }

    echo $html;

    $this->remove_products_post_class_filter();
  }

  public function render_plain_content() {
    echo $this->get_shortcode();
  }

}