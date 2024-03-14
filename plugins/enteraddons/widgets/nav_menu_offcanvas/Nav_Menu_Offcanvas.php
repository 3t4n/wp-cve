<?php
namespace Enteraddons\Widgets\Nav_Menu_Offcanvas;

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
 * Enteraddons elementor Nav menu widget.
 *
 * @since 1.0
 */
class Nav_Menu_Offcanvas extends Widget_Base {

	public function get_name() {
		return 'enteraddons-nav-menu-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu Offcanvas', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-nav-menu';
    }

	public function get_categories() {
		return [ 'enteraddons-header-footer-category' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'nav_menu_section',
			[
				'label' => esc_html__( 'Nav Menu', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

        $menus = \Enteraddons\Classes\Helper::menu_list();

		if( !empty( $menus ) ) {
	        $this->add_control(
				'nav_menu_select',
				[
					'label'     	=> __( 'Select Menu', 'enteraddons' ),
					'type'      	=> Controls_Manager::SELECT,
					'options'   	=> $menus,
					'description' 	=> sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'enteraddons' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'no_nav_menu',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> '<strong>' . __( 'There are no menus in your site.', 'enteraddons' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'enteraddons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' 		=> 'after',
					'content_classes' 	=> 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
        }
        $this->add_control(
            'offcanvas_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true
            ]
        );
        $this->add_control(
            'logo_img',
            [
                'label' => esc_html__( 'Logo', 'enteraddons' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'offcanvas_btn_section',
			[
				'label' => esc_html__( 'Off-Canvas Button', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'menu_icon',
            [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bars'
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
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
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_button_alignment',
            [
                'label' => esc_html__( 'Button Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-offcanvas-nav-button-wrapper' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_width',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
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
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
                
        $this->add_responsive_control(
            'hamburger_menu_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hamburger_menu_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hamburger_menu_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-hamburger-offcanvas-menu',
            ]
        );
        //
                
        $this->add_control(
            'hamburger_menu_button_normal_style',
            [
                'label' => esc_html__( 'Button Normal Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hamburger-menu-normal-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Menu Button Normal', 'enteraddons' ),
                'label_off' => esc_html__( 'Default', 'enteraddons' ),
                'label_on' => esc_html__( 'Custom', 'enteraddons' ),
                'return_value' => 'yes',
            ]
        );
        $this->start_popover();

        $this->add_control(
            'hamburger_menu_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'hamburger_menu_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-hamburger-offcanvas-menu',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hamburger_menu_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-hamburger-offcanvas-menu',
            ]
        );

        $this->end_popover();


        $this->add_control(
            'hamburger_menu_button_hover_style',
            [
                'label' => esc_html__( 'Button Hover Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hamburger-menu-hover-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Menu Button Hover', 'enteraddons' ),
                'label_off' => esc_html__( 'Default', 'enteraddons' ),
                'label_on' => esc_html__( 'Custom', 'enteraddons' ),
                'return_value' => 'yes',
            ]
        );
        $this->start_popover();

        $this->add_control(
            'hamburger_menu_hover_icon_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-offcanvas-menu:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'hamburger_hover_menu_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-hamburger-offcanvas-menu:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hamburger_hover_menu_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-hamburger-offcanvas-menu:hover',
            ]
        );

        $this->end_popover();

        $this->end_controls_section();

        //------------------------------ Offcanvas Panel ------------------------------
        $this->start_controls_section(
            'offcanvas_panel_section',
            [
                'label' => esc_html__( 'OffCanvas Panel', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // popover for Offcanvas panel
        $this->add_control(
            'offcanvas_style',
            [
                'label' => esc_html__( 'OffCanvas Panel Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'offcanvas-panel-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'OffCanvas Panel Settings', 'enteraddons' ),
                'label_off' => esc_html__( 'Default', 'enteraddons' ),
                'label_on' => esc_html__( 'Custom', 'enteraddons' ),
                'return_value' => 'yes',
            ]
        );
        $this->start_popover();

        $this->add_responsive_control(
            'offcanvas_panel_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'offcanvas_panel_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-offcanvas-nav-panel .panel',
            ]
        );
        $this->add_responsive_control(
            'offcanvas_panel_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'offcanvas_panel_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-offcanvas-nav-panel .panel',
            ]
        );


        $this->end_popover();


        // popover for OffCanvas Panel Header
        $this->add_control(
            'offcanvas_panel_header_style',
            [
                'label' => esc_html__( 'OffCanvas Panel Header Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'offcanvas-panel-header-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'OffCanvas Panel Header Settings', 'enteraddons' ),
                'label_off' => esc_html__( 'Default', 'enteraddons' ),
                'label_on' => esc_html__( 'Custom', 'enteraddons' ),
                'return_value' => 'yes',
            ]
        );
        $this->start_popover();

        $this->add_responsive_control(
            'offcanvas_panel_header_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'offcanvas_panel_header_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'offcanvas_panel_header_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-header',
            ]
        );
        $this->add_responsive_control(
            'offcanvas_panel_header_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'offcanvas_panel_header_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-header',
            ]
        );


        $this->end_popover();


        // popover for Close button
        $this->add_control(
            'offcanvas_close_btn_style',
            [
                'label' => esc_html__( 'OffCanvas Close Button Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'offcanvas-close-btn-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'OffCanvas Close Button Settings', 'enteraddons' ),
                'label_off' => esc_html__( 'Default', 'enteraddons' ),
                'label_on' => esc_html__( 'Custom', 'enteraddons' ),
                'return_value' => 'yes',
            ]
        );
        $this->start_popover();

        $this->add_responsive_control(
            'offcanvas_close_btn_panel_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'offcanvas_close_btn_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-close span:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .offcanvas-close span:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );


        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'offcanvas_close_btn_panel_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-close',
            ]
        );
        $this->add_responsive_control(
            'offcanvas_close_btn_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'offcanvas_close_btn_panel_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-offcanvas-nav-panel .offcanvas-close',
            ]
        );

        $this->end_popover();

        $this->end_controls_section();

        //------------------------------ Menu Item Style ------------------------------
        $this->start_controls_section(
			'menu_item_style_section',
			[
				'label' => esc_html__( 'Menu Item Style', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_item_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a',
            ]
        );
        $this->add_responsive_control(
            'nav_menu_item_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_menu_item_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_menu_item_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'menu_item_tab_wrapper' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'menu_item_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'nav_menu_item_color',
            [
                'label' => esc_html__( 'Menu Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'nav_menu_item_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a',
            ]
        );
        $this->add_responsive_control(
            'nav_menu_item_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_menu_item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_menu_item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a',
            ]
        );


        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'menu_item_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'nav_menu_item_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a:hover' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'nav_menu_item_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a:hover',
            ]
        );
        $this->add_responsive_control(
            'nav_menu_item_hover_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_menu_item_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_menu_item_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .offcanvas-content ul.ea-nav-menu-items li a:hover',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        
        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        $obj = new Nav_Menu_Offcanvas_Template();
        $obj::setDisplaySettings( $settings );
        $obj::setWidgetObject( $this );
        $obj->renderTemplate();

        $this->offcanvas_markup();
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }

    public function offcanvas_markup() {

        $settings = $this->get_settings_for_display();

            echo '<div id="mobile_menu_'.esc_attr( $this->get_id() ).'" class="ea-offcanvas-nav-panel mobile-menu-panel ea-offcanvas-nav-menu-wrapper">';
                echo '<div class="offcanvas-overlay"></div>';
                echo '<div class="panel ps">';
                    echo '<div class="offcanvas-header">';
                        echo '<div class="offcanvas-header-logo-area">';

                            if( !empty( $settings['logo_img']['url'] ) ) {
                                echo '<a href="'.esc_url( home_url('/') ).'" class="logo">';
                                    echo '<img src="'.esc_url( $settings['logo_img']['url'] ).'" />';
                                echo '</a>';
                            }

                            if( !empty( $settings['offcanvas_title'] ) ) {
                                echo '<span class="tagline">';
                                    echo esc_html( $settings['offcanvas_title'] );
                                echo '</span>';
                            }

                        echo '</div>';
                        echo '<div class="offcanvas-close">';
                            echo '<span></span>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="offcanvas-content">';
                        echo '<nav class="mobile_menu offcanvas-menu offcanvas-mobile">';

                            wp_nav_menu( array(
                                'menu'              => esc_html( $settings['nav_menu_select'] ),
                                'menu_class'        => "ea-nav-menu-items",
                                'container' => "" 
                            ) );

                        echo '</nav>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

    }


}