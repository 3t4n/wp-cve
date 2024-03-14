<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_Datatabs
 * Name: Product Data Tabs
 * Slug: lakit-wooproduct-datatabs
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_Datatabs extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-datatabs';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'data', 'product', 'tabs' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Data Tabs', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-tabs';
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
            'section_product_tabs_setting',
            [
                'label' => esc_html__( 'Settings', 'lastudio-kit' ),
            ]
        );

        if( !lastudio_kit()->get_theme_support('lastudio-kit')) {
            $this->add_control(
                'wc_style_warning',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'lastudio-kit' ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
            $this->add_control(
                'layout_type',
                [
                    'label' => esc_html__( 'Layout', 'lastudio-kit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => esc_html__('Default', 'lastudio-kit'),
                    ],
                    'default' => 'default',
                ]
            );
            $this->add_control(
                'accordion_icon',
                [
                    'label' => esc_html__( 'Accordion Icon', 'lastudio-kit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'plus'   => esc_html__('Plus/Minus Icon', 'lastudio-kit'),
                    ],
                    'default' => 'plus',
                ]
            );
        }
        else{
            $this->add_control(
                'layout_type',
                [
                    'label' => esc_html__( 'Layout', 'lastudio-kit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default'   => esc_html__('Default', 'lastudio-kit'),
                        'tab_left'  => esc_html__('Tab left', 'lastudio-kit'),
                        'tab_right' => esc_html__('Tab Right', 'lastudio-kit'),
                        'accordion' => esc_html__('Accordion', 'lastudio-kit'),
                    ],
                    'default' => 'default',
                ]
            );

            $this->add_control(
                'accordion_icon',
                [
                    'label' => esc_html__( 'Accordion Icon', 'lastudio-kit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'plus'   => esc_html__('Plus/Minus Icon', 'lastudio-kit'),
                        'arrow'  => esc_html__('Up/Down Icon', 'lastudio-kit'),
                    ],
                    'default' => 'plus',
                ]
            );
        }

        $this->_add_responsive_control(
            'tabs_controls_width',
            array(
                'label'      => esc_html__( 'Tabs Controls Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%', 'em', 'vw', 'vh',
                ),
                'condition' => array(
                    'layout_type' => array( 'tab_left', 'tab_right' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--singleproduct-datatab-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->end_controls_section();

        $this->_start_controls_section(
            'section_tabs_control_style',
            array(
                'label'      => esc_html__( 'Tabs Control', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_control(
            'tabs_controls_alignment',
            array(
                'label'   => esc_html__( 'Tabs Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'condition' => array(
                    'layout_type' => 'default',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls' => 'justify-content: {{VALUE}};'
                )
            )
        );

        $this->_add_control(
            'tabs_controls_width_auto',
            array(
                'label'        => esc_html__( 'Auto Width', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'Off', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'lakit-tab-auto-with-',
                'condition' => array(
                    'layout_type' => 'default',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_wrapper_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_padding',
            array(
                'label'      =>esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_margin',
            array(
                'label'      =>esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_wrapper_border',
                'selector'    => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs, {{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab',
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs, {{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab'
            )
        );
        
        $this->end_controls_section();

        $this->_start_controls_section(
            'section_tabs_control_item_style',
            array(
                'label'      => esc_html__( 'Tabs Control Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_control_styles' );
        $this->_start_controls_tab(
            'tabs_control_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );
        $this->_add_responsive_control(
            'tabs_control_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wc-tab-title a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'tabs_control_text_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wc-tab-title a' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_text_typography',
                'selector' => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li, {{WRAPPER}} .wc-tab-title a'
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wc-tab-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wc-tab-title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wc-tab-title a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border',
                'selector'  => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li, {{WRAPPER}} .wc-tab-title a',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li, {{WRAPPER}} .wc-tab-title a',
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tabs_control_hover',
            array(
                'label' => esc_html__( 'Hover & Active', 'lastudio-kit' ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_text_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'tabs_control_text_bgcolor_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_text_typography_hover',
                'selector' => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover,{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active, {{WRAPPER}} .active .wc-tab-title a'
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding_hover',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin_hover',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_border_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border_hover',
                'selector'  => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover,{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active,{{WRAPPER}} .active .wc-tab-title a',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li:hover,{{WRAPPER}} .lakit-product-tabs .wc-tabs-wrapper .lakit-wc-tabs--controls ul.wc-tabs li.active,{{WRAPPER}} .active .wc-tab-title a',
            )
        );

        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->end_controls_section();

        $this->_start_controls_section(
            'section_tabs_content_style',
            array(
                'label'      => esc_html__( 'Tabs Content', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'tab_content_width',
            [
                'label' => esc_html__( 'Content Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tab-content' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_content_typography',
                'selector' => '{{WRAPPER}} .tab-content'
            )
        );
        $this->_add_control(
            'tabs_content_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_heading_color',
            array(
                'label'  => esc_html__( 'Heading Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content h1,{{WRAPPER}} .tab-content h2,{{WRAPPER}} .tab-content h3,{{WRAPPER}} .tab-content h4,{{WRAPPER}} .tab-content h5,{{WRAPPER}} .tab-content h6' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_link_color',
            array(
                'label'  => esc_html__( 'Link Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_link_hover_color',
            array(
                'label'  => esc_html__( 'Link Hover Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content a:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_content_background',
                'selector' => '{{WRAPPER}} .tab-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_content_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_content_border',
                'selector'  => '{{WRAPPER}} .tab-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_content_box_shadow',
                'selector' => '{{WRAPPER}} .tab-content',
            )
        );

        $this->_end_controls_section();

        do_action('lastudiokit/woocommerce/single/setting/product-tabs', $this);
        do_action('lastudio-kit/woocommerce/single/setting/product-tabs', $this);
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

        setup_postdata( $product->get_id() );

        $layout_type = $this->get_settings_for_display('layout_type');
        $accordion_icon = $this->get_settings_for_display('accordion_icon');

        echo '<div class="lakit-product-tabs layout-type-'.esc_attr($layout_type).' lakiticon-type-'.esc_attr($accordion_icon).'">';

        wc_get_template( 'single-product/tabs/tabs.php' );

        echo '</div>';

        // On render widget from Editor - trigger the init manually.
        if ( wp_doing_ajax() ) {
            ?>
            <script>
                jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
                jQuery(document).trigger('lastudiokit/woocommerce/single/product-tabs lastudio-kit/woocommerce/single/product-tabs');
            </script>
            <?php
        }
    }

    public function render_plain_content() {}

}