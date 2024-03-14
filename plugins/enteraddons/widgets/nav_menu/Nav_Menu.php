<?php
namespace Enteraddons\Widgets\Nav_Menu;

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
class Nav_Menu extends Widget_Base {

	public function get_name() {
		return 'enteraddons-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'enteraddons' );
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

        $this->end_controls_section();

        $this->start_controls_section(
			'nav_menu_wrapper_style_section',
			[
				'label' => esc_html__( 'Nav Menu Wrapper Style', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'nav_menu_wrap_width',
			[
				'label' => esc_html__( 'Wrapper Width', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'nav_menu_wrap_height',
			[
				'label' => esc_html__( 'Wrapper Height', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
        $this->add_responsive_control(
            'nav_menu_wrap_horizontal_alignment',
            [
                'label' => esc_html__( 'Horizontal Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner' => 'justify-content: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_menu_wrap_vertical_alignment',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner' => 'align-items: {{VALUE}} !important',
                ],
            ]
        );
		$this->add_responsive_control(
            'nav_menu_wrap_wrap_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_menu_wrap_wrap_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'nav_menu_wrap_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper',
            ]
        );
        $this->add_responsive_control(
            'nav_menu_wrap_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_menu_wrap_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_menu_wrap_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper',
            ]
        );


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
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper .ea-desktop-nav ul li a',
            ]
        );
        $this->add_responsive_control(
            'menu_item_gap',
            [
                'label' => esc_html__( 'Menu Item Gap', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '10',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-desktop-nav .ea-nav-menu-items' => 'gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li.menu-item-has-children .ea-submenu-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'nav_menu_item_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper ul li',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_menu_item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper ul li',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_menu_item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper ul li',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li.current-menu-item a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-nav-menu-wrapper li.menu-item-has-children:hover .ea-submenu-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'nav_menu_item_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper ul li:hover',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper ul li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_menu_item_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper ul li:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_menu_item_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper ul li:hover',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        
        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        //------------------------------ Dropdown Menu Item Style ------------------------------
        $this->start_controls_section(
			'dropdown_menu_item_style_section',
			[
				'label' => esc_html__( 'Dropdown Menu Style', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'dropdown_menu_options',
			[
				'label' => esc_html__( 'Dropdown Area Style', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
        $this->add_responsive_control(
            'dropdown_menu_item_alignment',
            [
                'label' => esc_html__( 'Item Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
		$this->add_responsive_control(
            'dropdown_area_width',
            [
                'label' => esc_html__( 'Dropdown Width', 'enteraddons' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_area_height',
            [
                'label' => esc_html__( 'Dropdown Height', 'enteraddons' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_area_top_position',
            [
                'label' => esc_html__( 'Dropdown Top Position', 'enteraddons' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_area_left_position',
            [
                'label' => esc_html__( 'Dropdown Left Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_menu_area_margin',
            [
                'label' => esc_html__( 'Dropdown Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_menu_area_padding',
            [
                'label' => esc_html__( 'Dropdown Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'dropdown_menu_area_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu',
            ]
        );
        $this->add_responsive_control(
            'dropdown_menu_area_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_menu_area_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_menu_area_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu',
            ]
        );


        /**** dropdown menu item ****/

        $this->add_control(
			'dropdown_menu_item_options',
			[
				'label' => esc_html__( 'Dropdown Menu Item Style', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'dropdown_menu_item_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_menu_item_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li a',
            ]
        );
        $this->start_controls_tabs( 'dropdown_menu_item_tab_wrapper' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'dropdown_menu_item_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'dropdown_menu_item_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_menu_item_margin',
            [
                'label' => esc_html__( 'Menu Item Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_menu_item_padding',
            [
                'label' => esc_html__( 'Menu Item Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
       
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'dropdown_menu_item_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li',
            ]
        );
        $this->add_responsive_control(
            'dropdown_menu_item_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_menu_item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_menu_item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li',
            ]
        );


        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'dropdown_menu_item_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'dropdown_menu_item_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li.current-menu-item a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'dropdown_menu_item_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li:hover',
            ]
        );
        $this->add_responsive_control(
            'dropdown_menu_item_hover_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_menu_item_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_menu_item_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper li .sub-menu li:hover',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

		//------------------------------ Menu item Before/After Style ------------------------------
        $this->start_controls_section(
			'menu_item_ba_style_section',
			[
				'label' => esc_html__( 'Menu Item Before/After Style', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'line_animation',
			[
				'label' => esc_html__( 'Line Animation', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'line-default-animation',
				'options' => [
					'line-default-animation'  => esc_html__( 'Default', 'enteraddons' ),
					'line-animation-1' => esc_html__( 'Animation 1', 'enteraddons' ),
					'line-animation-2' => esc_html__( 'Animation 2', 'enteraddons' ),
					'line-animation-3' => esc_html__( 'Animation 3', 'enteraddons' ),
					'line-animation-4' => esc_html__( 'Animation 4', 'enteraddons' ),
				]
			]
		);
        $this->add_control(
			'active_menu_before',
			[
				'label' => esc_html__( 'Active Menu Before Line', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'enteraddons' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

        $this->add_responsive_control(
            'menu_item_before_width',
            [
                'label' => esc_html__( 'Line Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-before-line li:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_item_before_height',
            [
                'label' => esc_html__( 'Line Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-before-line li:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_before_bottom_position',
            [
                'label' => esc_html__( 'Top Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-before-line li:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_item_before_left_position',
            [
                'label' => esc_html__( 'Left Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-before-line li:before' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_before_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper .active-before-line li:before',
            ]
        );
        /***** After Line Style *****/
		$this->add_control(
			'menu_after_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

        $this->add_control(
			'active_menu_after',
			[
				'label' => esc_html__( 'Active Menu After Line', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'enteraddons' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

        $this->add_responsive_control(
            'menu_item_after_width',
            [
                'label' => esc_html__( 'Line Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-after-line li:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_item_after_height',
            [
                'label' => esc_html__( 'Line Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-after-line li:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_after_bottom_position',
            [
                'label' => esc_html__( 'Bottom Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-after-line li:after' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_item_after_left_position',
            [
                'label' => esc_html__( 'Left Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .active-after-line li:after' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_after_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper .active-after-line li:after',
            ]
        );

        $this->end_controls_section();

        //------------------------------ Hamburger Style ------------------------------
        $this->start_controls_section(
			'hamburger_menu_style_section',
			[
				'label' => esc_html__( 'Hamburger Menu Style', 'enteraddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->start_controls_tabs(
            'hamburger_menus_tabs'
        );
        $this->start_controls_tab(
            'hamburger_menu_button_tab',
            [
                'label' => esc_html__( 'Menu Button', 'enteraddons' ),
            ]
        );

		$this->add_control(
            'menu_icon',
            [
                'label' => esc_html__( 'Menu Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bars'
                ],
            ]
        );
        $this->add_control(
            'menu_close_icon',
            [
                'label' => esc_html__( 'Menu Close Icons', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times'
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
                    '{{WRAPPER}} .ea-hamburger-menu i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper' => 'text-align: {{VALUE}} !important',
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
                    '{{WRAPPER}} .ea-hamburger-menu' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-hamburger-menu' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-hamburger-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-hamburger-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-hamburger-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hamburger_menu_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-hamburger-menu',
            ]
        );
        //
                
        $this->add_control(
            'hamburger_menu_button_normal_style',
            [
                'label' => esc_html__( 'Hamburger Menu Button Normal Style', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-hamburger-menu i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'hamburger_menu_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-hamburger-menu',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hamburger_menu_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-hamburger-menu',
            ]
        );

        $this->end_popover();


        $this->add_control(
            'hamburger_menu_button_hover_style',
            [
                'label' => esc_html__( 'Hamburger Menu Button Hover Style', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-hamburger-menu:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'hamburger_hover_menu_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-hamburger-menu:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hamburger_hover_menu_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-hamburger-menu:hover',
            ]
        );

        $this->end_popover();

        $this->end_controls_tab(); //

        // Hamburger Dropdown Area
        
        $this->start_controls_tab(
            'hamburger_dropdown_area_tab',
            [
                'label' => esc_html__( 'Dropdown', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'hamburger_menu_dropdown_area',
            [
                'label' => esc_html__( 'Hamburger Dropdown Area', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'hamburger_menu_dropdown_hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_dropdown_item_alignment',
            [
                'label' => esc_html__( 'Menu Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-hamburger-nav-wrap' => 'text-align: {{VALUE}} !important',
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-hamburger-nav-wrap .sub-menu' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_dropdown_area_top_position',
            [
                'label' => esc_html__( 'Dropdown Top/Bottom Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '95',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_dropdown_area_position',
            [
                'label' => esc_html__( 'Left/Right Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_dropdown_area_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
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
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_dropdown_area_height',
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
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hamburger_menu_dropdown_area_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap>ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'hamburger_menu_dropdown_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap',
            ]
        );
        
        $this->add_responsive_control(
            'hamburger_menu_dropdown_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'hamburger_menu_dropdown_area_bg',
            [
                'label' => esc_html__( 'Hamburger Dropdown Area Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hamburger_menu_dropdown_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap',
            ]
        );

        $this->add_control(
            'hamburger_dropdown_sub_menu_area',
            [
                'label' => esc_html__( 'Hamburger Dropdown Sub Menu Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hamburger_menu_dropdown_sub_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-nav-menu-wrapper .ea-nav-wrap-inner.ea-hamburger-nav-wrap .sub-menu',
            ]
        );


        $this->end_controls_tab();//
        //
        $this->start_controls_tab(
            'hamburger_dropdown_menu_item_tab',
            [
                'label' => esc_html__( 'Menu Item', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'hamburger_dropdown_menu_item_color',
            [
                'label' => esc_html__( 'Menu Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-nav-wrap .ea-nav-menu-items li a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-hamburger-nav-wrap li.menu-item-has-children .ea-submenu-button' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'hamburger_dropdown_menu_item_hover_color',
            [
                'label' => esc_html__( 'Menu Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-hamburger-nav-wrap .ea-nav-menu-items li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hamburger_dropdown_menu_item_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-hamburger-nav-wrap .ea-nav-menu-items li a',
            ]
        );
        $this->add_responsive_control(
            'hamburger_dropdown_menu_item_gap',
            [
                'label' => esc_html__( 'Menu Item Gap', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '10',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-nav-menu-wrapper .ea-hamburger-nav-wrap .ea-nav-menu-items' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();//

        $this->end_controls_tabs();//

        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Flip Card template render
        $obj = new Nav_Menu_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }

}