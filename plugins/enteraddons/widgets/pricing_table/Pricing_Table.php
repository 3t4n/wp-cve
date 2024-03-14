<?php
namespace Enteraddons\Widgets\Pricing_Table;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Enteraddons elementor Pricing Table widget.
 *
 * @since 1.0
 */

class Pricing_Table extends Widget_Base {

	public function get_name() {
		return 'enteraddons-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'Pricing Table', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-pricing-plan';
	}

    public function get_keywords() {
        return [ 'price', 'pricing', 'pricing table'];
    }

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();
        // ---------------------------------------- Pricing Header content ------------------------------
        $this->start_controls_section(
            'enteraddons_pricing_header',
            [
                'label' => esc_html__( 'Header', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'STANDARD PLAN', 'enteraddons' )
            ]
        );
        $this->add_control(
            'sub_title',
            [
                'label' => esc_html__( 'Sub Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Build with cool features', 'enteraddons' )
            ]
        );
        $this->end_controls_section(); // End
        // ---------------------------------------- Pricing content ------------------------------
        $this->start_controls_section(
            'enteraddons_pricing_table_pricing',
            [
                'label' => esc_html__( 'Pricing', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'regular_price',
            [
                'label' => esc_html__( 'Regular Price', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( '', 'enteraddons' )
            ]
        );
        $this->add_control(
            'price',
            [
                'label' => esc_html__( 'Sale Price', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( '49', 'enteraddons' )
            ]
        );
        $this->add_control(
            'currency',
            [
                'label' => esc_html__( 'Currency', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'options' => \Enteraddons\Classes\Helper::getCurrencyList(),
                'default' => 'dollar'
            ]
        );
        $this->add_control(
            'custom_currency',
            [
                'label' => esc_html__( 'Custom Currency', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [ 'currency' => 'custom' ],
                'label_block' => true
            ]
        );
        $this->add_control(
            'duration',
            [
                'label' => esc_html__( 'Duration', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( '/Month', 'enteraddons' )
            ]
        );
        $this->end_controls_section(); // End
        // ---------------------------------------- Badge content ------------------------------
        $this->start_controls_section(
            'enteraddons_pricing_table_badge',
            [
                'label' => esc_html__( 'Badge', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'show_badge',
            [
                'label' => esc_html__( 'Show Badge', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'badge_style',
            [
                'label' => esc_html__( 'Badge Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'badge-style-1',
                'options' => [
                    'badge-style-1' => esc_html__( 'Style 1', 'enteraddons' ),
                    'badge-style-2'  => esc_html__( 'Style 2', 'enteraddons' ),
                    'badge-style-3'  => esc_html__( 'Style 3', 'enteraddons' ),
                ]
            ]
        );
        $this->add_control(
            'badge_text',
            [
                'label' => esc_html__( 'Badge Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Popular', 'enteraddons' )
            ]
        );
        $this->end_controls_section(); // End
        // ---------------------------------------- Pricing Table Features content ------------------------------
        $this->start_controls_section(
            'enteraddons_pricing_features',
            [
                'label' => esc_html__( 'Features', 'enteraddons' ),
            ]
        );

        $repeater->add_control(
            'name', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Run your apps offline' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'solid',
                ]
            ]
        );
        $repeater->add_control(
            'list_text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
                ],
            ]
        );
        $repeater->add_control(
            'list_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body {{CURRENT_ITEM}} i' => 'color: {{VALUE}}'
                ],
            ]
        );     
        $this->add_control(
            'pricing_features',
            [
                'label' => esc_html__( 'Add Features', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'name'   => esc_html__( 'Run your apps offline', 'enteraddons' ),
                    ],
                    [
                        'name'   => esc_html__( 'Professional Services ', 'enteraddons' ),
                    ],
                    [
                        'name'   => esc_html__( 'AppSheet community', 'enteraddons' ),
                    ],
                    [
                        'name'   => esc_html__( 'Add security filters to app', 'enteraddons' ),
                    ],
                    
                ]
            ]
        );

        $this->end_controls_section(); // End
        // ---------------------------------------- Button content ------------------------------
        $this->start_controls_section(
            'enteraddons_pricing_button',
            [
                'label' => esc_html__( 'Button', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'show_btn',
            [
                'label' => esc_html__( 'Show Button', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Buy Now', 'enteraddons' )
            ]
        );
        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ]
            ]
        );
        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Buton Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inherit',
                'options' => [
                    'inherit'      => esc_html__( 'Left', 'enteraddons' ),
                    'row-reverse'  => esc_html__( 'Right', 'enteraddons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Icon Spacing', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '14',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section(); // End

        /**
         * Style Tab
         * ------------------------------ Content wrapper area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_wrapper_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table' => 'text-align: {{VALUE}} !important',
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body ul li' => 'justify-content: {{VALUE}} !important',
                ]
            ]
        );
        $this->add_responsive_control(
            'pricing_table_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pricing_table_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        //  Controls tab start
        $this->start_controls_tabs( 'pricing_table_wrapper_tabs_start' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'pricing_table_wrapper_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'pricing_table_wrapper_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-pricing-table',
            ]
        );
        $this->add_responsive_control(
            'pricing_table_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pricing_table_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pricing_table_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'pricing_table_wrapper_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'table_hover_effect',
            [
                'label' => esc_html__( 'Active Table Hover Effect', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pricing_table_wrapper_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .active-table-hover-effect:hover',
                'condition' => [ 'table_hover_effect' => 'yes' ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'pricing_table_wrapper_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-pricing-table:hover',
            ]
        );
        $this->add_responsive_control(
            'pricing_table_wrapper_hover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pricing_table_wrapper_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table:hover',
            ]
        );
        $this->end_controls_tab(); 
        $this->end_controls_tabs(); 
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Title Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_heading_settings', [
                'label' => esc_html__( 'Table Head', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'table_heading_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_heading_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-head' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'table_heading_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-head',
            ]
        );
        $this->add_responsive_control(
            'table_heading_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-head' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'table_heading_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-head',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'table_heading_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-head',
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Title Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_title_settings', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Hover Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-title' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title',
            ]
        );
        $this->add_responsive_control(
            'pricing_table_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pricing_table_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pricing_table_title_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title',
            ]
        );
        $this->add_responsive_control(
            'pricing_table_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pricing_table_title_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pricing_table_title_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-title',
            ]
        );

        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Sub title Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_sub_title_settings', [
                'label' => esc_html__( 'Sub Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'sub_title_color',
                [
                    'label' => esc_html__( 'Title Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'sub_title_hover_color',
                [
                    'label' => esc_html__( 'Hover Title Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-info' => 'color: {{VALUE}}',
                    ],
                    'condition' => [ 'table_hover_effect' => 'yes' ],
                    'default' => '#fff'
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'sub_title_typography',
                    'label' => esc_html__( 'Typography', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info',
                ]
            );
            $this->add_responsive_control(
                'pricing_table_sub_title_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'pricing_table_sub_title_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'pricing_table_sub_title_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info',
                ]
            );
            $this->add_responsive_control(
                'pricing_table_sub_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'pricing_table_sub_title_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_table_sub_title_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-pricing-table .enteraddons-pt-info',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Pricing Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_pricing_settings', [
                'label' => esc_html__( 'Pricing', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'pricing_table_price_area_heading',
            [
                'label' => esc_html__( 'Pricing Area', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'price_area_display',
            [
                'label' => esc_html__( 'Display', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block'  => esc_html__( 'Block', 'enteraddons' ),
                    'inline-flex' => esc_html__( 'Inline Block', 'enteraddons' ),
                    'none'   => esc_html__( 'None', 'enteraddons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'horizontal_align',
            [
                'label' => esc_html__( 'Horizontal Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'vertical_align',
            [
                'label' => esc_html__( 'Vertical Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'align-items: {{VALUE}};',
                ],
                'condition' => [ 'price_area_display' => 'inline-flex' ]
            ]
        );
        $this->add_responsive_control(
            'price_area_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'price_area_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'price_area_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'price_area_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'price_area_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price',
            ]
        );
        $this->add_responsive_control(
            'price_area_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'price_area_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'price_area_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pt-head .enteraddons-pt-price',
            ]
        );
        //
        $this->add_control(
            'pricing_table_regular_price_heading',
            [
                'label' => esc_html__( 'Regular Price', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'price_regular_color',
            [
                'label' => esc_html__( 'Regular Price Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .ea-pt-regular-price' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'hover_regular_price_color',
            [
                'label' => esc_html__( 'Regular Price Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-price .ea-pt-regular-price' => 'color: {{VALUE}} !important',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'regular_price_typography',
                'label' => esc_html__( 'Regular Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-price .ea-pt-regular-price',
            ]
        );
        $this->add_responsive_control(
            'regular_price_margin',
            [
                'label' => esc_html__( 'Regular Price Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .ea-pt-regular-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //
        $this->add_control(
            'pricing_table_price_heading',
            [
                'label' => esc_html__( 'Sale Price', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .price' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'hover_price_color',
            [
                'label' => esc_html__( 'Hover Price Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-price .price' => 'color: {{VALUE}} !important',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-price .price',
            ]
        );
        //
        $this->add_control(
            'pricing_table_currency_heading',
            [
                'label' => esc_html__( 'Currency', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'currency_color',
            [
                'label' => esc_html__( 'Currency Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .currency' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'hover_currency_color',
            [
                'label' => esc_html__( 'Hover Currency Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-price .currency' => 'color: {{VALUE}} !important',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'currency_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-price .currency',
            ]
        );
        $this->add_responsive_control(
            'currency_align',
            [
                'label' => esc_html__( 'Vertical Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'super' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'baseline' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'sub' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .currency' => 'vertical-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'currency_margin',
            [
                'label' => esc_html__( 'Currency Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //
        $this->add_control(
            'pricing_table_duration_heading',
            [
                'label' => esc_html__( 'Duration', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'duration_color',
            [
                'label' => esc_html__( 'Duration Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .duration' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'hover_duration_color',
            [
                'label' => esc_html__( 'Hover Duration Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-price .duration' => 'color: {{VALUE}} !important',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'duration_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-price sub.duration',
            ]
        );
        $this->add_responsive_control(
            'duration_vertical_align',
            [
                'label' => esc_html__( 'Vertical Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'super' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'baseline' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'sub' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price sub.duration' => 'vertical-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'duration_position',
            [
                'label' => esc_html__( 'Duration Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'inline-block' => [
                        'title' => esc_html__( 'Beside Price', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'block' => [
                        'title' => esc_html__( 'Below Price', 'enteraddons' ),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'inline-block',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .duration-wrapper' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'duration_margin',
            [
                'label' => esc_html__( 'Duration Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-price .duration-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Pricing Badge Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_badge_settings', [
                'label' => esc_html__( 'Badge', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'badge_text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-price-badge',
            ]
        );
        $this->add_control(
            'badge_text_color',
            [
                'label' => esc_html__( 'Badge Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-price-badge' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-price-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'badge_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-price-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'badge_bg_color',
                'label' => esc_html__( 'Badge Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-price-badge',
            ]
        );
        $this->add_responsive_control(
            'badge_width',
            [
                'label' => esc_html__( 'Badge Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-price-badge' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'badge_height',
            [
                'label' => esc_html__( 'Badge Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-price-badge' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Pricing Features Wrapper Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_features_wrapper_settings', [
                'label' => esc_html__( 'Features Area Wrapper', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'features_wrapper_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body ul li' => 'justify-content: {{VALUE}} !important'
                ],
            ]
        );
        $this->add_responsive_control(
            'features_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'features_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'features_wrapper_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body',
            ]
        );
        $this->add_responsive_control(
            'features_wrapper_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'features_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'features_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body',
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Pricing Features Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_features_list_settings', [
                'label' => esc_html__( 'Features List', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'features_list_icon_options',
            [
                'label' => esc_html__( 'Features Icon Style Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'features_list_icon_color',
            [
                'label' => esc_html__( 'List Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'hover_features_list_icon_color',
            [
                'label' => esc_html__( 'Hover List Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-body li i' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'
            ]
        );
        $this->add_responsive_control(
            'features_list_icon_margin',
            [
                'label' => esc_html__( 'List Item Icon Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'features_list_options',
            [
                'label' => esc_html__( 'Features List Style Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'features_list_color',
            [
                'label' => esc_html__( 'List Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'hover_features_list_color',
            [
                'label' => esc_html__( 'Hover List Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-pt-body li' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'default' => '#fff'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'features_list_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li',
            ]
        );
        $this->add_responsive_control(
            'features_list_padding',
            [
                'label' => esc_html__( 'List Item Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'features_list_margin',
            [
                'label' => esc_html__( 'List Item Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'features_list_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li',
            ]
        );
        $this->add_responsive_control(
            'features_list_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'features_list_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'features_list_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-body li',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Pricing Table Footer Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_footer_settings', [
                'label' => esc_html__( 'Footer', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'features_footer_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'features_footer_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'features_footer_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-footer',
            ]
        );
        $this->add_responsive_control(
            'features_footer_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-footer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'features_footer_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-footer',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'features_footer_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pricing-table .enteraddons-pt-footer',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Pricing Table Button Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_btn_settings', [
                'label' => esc_html__( 'Button', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //  Controls tab start
        $this->start_controls_tabs( 'btn_tabs_start' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'btn_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'btn_text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg_color',
                'label' => esc_html__( 'Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'btn_hover_normal',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn:hover' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'table_hover_effect' => '' ]
            ]
        );
        // Table Hover Style
        $this->add_control(
            'table_hover_btn_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-btn' => 'color: {{VALUE}}',
                ],
                'default' => '#000',
                'condition' => [ 'table_hover_effect' => 'yes' ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'table_hover_btn_bg_color',
                'label' => esc_html__( 'Hover Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-btn',
                'condition' => [ 'table_hover_effect' => 'yes' ],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#fff',
                    ],
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_hover_border',
                'label' => esc_html__( 'Hover Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn:hover',
            ]
        );
        $this->add_responsive_control(
            'btn_hover_radius',
            [
                'label' => esc_html__( 'Hover Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_bg_color',
                'label' => esc_html__( 'Hover Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn:hover',
                'condition' => [ 'table_hover_effect' => '' ]
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Pricing Table Button Icon Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_pricing_table_btn_icon_settings', [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        //  Controls tab start
        $this->start_controls_tabs( 'btn_icon_tabs_start' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'btn_icon_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

            $this->add_control(
            'btn_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn i' => 'color: {{VALUE}}',
                ],
            ]
            );
            $this->add_responsive_control(
            'btn_icon_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_icon_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i',
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_icon_bg_color',
                'label' => esc_html__( 'Hover Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn i'
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'btn_icon_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'table_hover_btn_icon_color',
            [
                'label' => esc_html__( 'Hover Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active-table-hover-effect:hover .enteraddons-btn i' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'table_hover_effect' => 'yes' ],
            ]
        );
        $this->add_control(
            'btn_hover_icon_color',
            [
                'label' => esc_html__( 'Hover Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-pt-footer .enteraddons-btn:hover i' => 'color: {{VALUE}}',
                ],
                'condition' => [ 'table_hover_effect' => '' ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_icon_hover_bg_color',
                'label' => esc_html__( 'Hover Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-btn:hover i'
            ]
        );
        $this->end_controls_tab(); 
        $this->end_controls_tabs(); 
        $this->end_controls_section();

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        // Testimonial template render
        $obj = new Pricing_Table_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
        
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }
	

}
