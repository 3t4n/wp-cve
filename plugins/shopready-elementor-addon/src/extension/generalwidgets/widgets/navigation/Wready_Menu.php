<?php
namespace Shop_Ready\extension\generalwidgets\widgets\navigation;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;
use Shop_Ready\base\Nav_Walker;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wready_Menu extends \Shop_Ready\extension\generalwidgets\Widget_Base {

  
    private function get_available_menus() {

		$menus     = wp_get_nav_menus();
		$menulists = [];
        foreach ( $menus as $menu ) {
            $menulists[ $menu->slug ] = $menu->name;
        }
        return $menulists;

    }

    protected function register_controls() {

        /*------------------------
			MENU CONTENT SOURCE
        -------------------------*/
        $this->start_controls_section(
            'inline_menu_content',
            [
                'label' => esc_html__( 'Select Navigation & Style', 'shopready-elementor-addon' ),
            ]
        );
            $this->add_control(
                'inline_menu_style',
                [
                    'label'   => esc_html__( 'Style', 'shopready-elementor-addon' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'      => esc_html__( 'Style One', 'shopready-elementor-addon' ),
                        '2'      => esc_html__( 'Style Two', 'shopready-elementor-addon' ),
                        '3'      => esc_html__( 'Style Three', 'shopready-elementor-addon' ),
                        '4'      => esc_html__( 'Badge Menu', 'shopready-elementor-addon' ),
                        'custom' => esc_html__( 'Custom Style', 'shopready-elementor-addon' ),
                    ],
                ]
            );
            if ( ! empty( $this->get_available_menus() ) ) {
                $this->add_control(
                    'inline_menu_id',
                    [
                        'label'        => esc_html__( 'Menu', 'shopready-elementor-addon' ),
                        'type'         => Controls_Manager::SELECT,
                        'options'      => $this->get_available_menus(),
                        'default'      => array_keys( $this->get_available_menus() )[0],
                        'save_default' => true,
                        'description'  => sprintf( esc_html__( 'Go to the <a href="%s" target="_blank">Menus Option</a> to manage your menus.', 'shopready-elementor-addon' ), admin_url( 'nav-menus.php' ) ),
                        'separator'    => 'before',
                    ]
                );
            } else {
                $this->add_control(
                    'inline_menu_id',
                    [
                        'type'      => Controls_Manager::RAW_HTML,
                        'raw'       => sprintf( esc_html__( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Option</a> to create one.', 'shopready-elementor-addon' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                        'separator' => 'before',
                    ]
                );
            }
             
        $this->end_controls_section();
        /*------------------------
			MENU CONTENT SOURCE END
        -------------------------*/

        /*------------------------
			MENU ITEMS STYLE
        -------------------------*/
        $this->start_controls_section(
            'inline_menu_style_section',
            [
                'label' => esc_html__( 'Menu Items', 'shopready-elementor-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'menu_items_display',
                [
                    'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'block',
                    'options' => [
                        'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
                        'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
                        'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
                        'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
                        'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
                        'inherit'      => esc_html__( 'Inherit', 'shopready-elementor-addon' ),
                        'none'         => esc_html__( 'None', 'shopready-elementor-addon' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu' => 'display: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'menu_items_width',
                [
                    'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'menu_items_height',
                [
                    'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'menu_items_float',
                [
                    'label'   => esc_html__( 'Float', 'shopready-elementor-addon' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'left'    => esc_html__( 'Left', 'shopready-elementor-addon' ),
                        'right'   => esc_html__( 'Right', 'shopready-elementor-addon' ),
                        'none'    => esc_html__( 'None', 'shopready-elementor-addon' ),
                        'inherit' => esc_html__( 'Inherit', 'shopready-elementor-addon' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu' => 'float:{{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'menu_items_list_style',
                [
                    'label'   => esc_html__( 'List Style', 'shopready-elementor-addon' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'                 => esc_html__('None','shopready-elementor-addon'),
                        'disc'                 => esc_html__('Disc','shopready-elementor-addon'),
                        'circle'               => esc_html__('Circle','shopready-elementor-addon'),
                        'square'               => esc_html__('Square','shopready-elementor-addon'),
                        'decimal'              => esc_html__('Decimal','shopready-elementor-addon'),
                        'decimal-leading-zero' => esc_html__('Decimal-leading-zero','shopready-elementor-addon'),
                        'lower-roman'          => esc_html__('Lower Roman','shopready-elementor-addon'),
                        'upper-roman'          => esc_html__('Upper Roman','shopready-elementor-addon'),
                        'lower-greek'          => esc_html__('Lower Greek','shopready-elementor-addon'),
                        'lower-latin'          => esc_html__('Lower Latin','shopready-elementor-addon'),
                        'upper-latin'          => esc_html__('Upper Latin','shopready-elementor-addon'),
                        'armenian'             => esc_html__('Armenian','shopready-elementor-addon'),
                        'georgian'             => esc_html__('Georgian','shopready-elementor-addon'),
                        'lower-alpha'          => esc_html__('Lower Alpha','shopready-elementor-addon'),
                        'upper-alpha'          => esc_html__('Upper Alpha','shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu' => 'list-style: {{VALUE}} !important;',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'menu_items_align',
                [
                    'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
                            'icon'  => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu' => 'text-align: {{VALUE}};',
                    ],
                    'default'   => '',
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'menu_items_margin',
                [
                    'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
        $this->end_controls_section();
        /*------------------------
			MENU ITEMS STYLE
        -------------------------*/

        /*------------------------
			MENU ITEM STYLE
        -------------------------*/
        $this->start_controls_section(
            'inline_menu_item_style_section',
            [
                'label' => esc_html__( 'Single Menu Item', 'shopready-elementor-addon' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            // Menu Normal Tab
            $this->start_controls_tabs( 'menu_style_tabs' );

                $this->start_controls_tab(
                    'menu_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'shopready-elementor-addon' ),
                    ]
                );
                    $this->add_control(
                        'menu_normal_color',
                        [
                            'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'menu_normal_background',
                            'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'menu_typography',
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a',
                        ]
                    );        
                    $this->add_responsive_control(
                        'menu_display',
                        [
                            'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'block',
                            'options' => [
                                'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
                                'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
                                'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
                                'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
                                'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li'   => 'display: {{VALUE}};',
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'display: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_position',
                        [
                            'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'relative',
                            'options' => [
                                'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
                                'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
                                'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'position: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'      => 'menu_normal_border',
                            'label'     => esc_html__( 'Border', 'shopready-elementor-addon' ),
                            'selector'  => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_normal_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'      => 'menu_normal_box_shadow',
                            'label'     => esc_html__( 'Box Shadow', 'shopready-elementor-addon' ),
                            'selector'  => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Text_Shadow:: get_type(),
                        [
                            'name'     => 'menu_normal_text_shadow',
                            'label'    => esc_html__( 'Text Shadow', 'shopready-elementor-addon' ),
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a',
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_item_width',
                        [
                            'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li'   => 'width: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_item_height',
                        [
                            'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li'   => 'height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_normal_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_normal_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                 // Menu Hover Tab
                $this->start_controls_tab(
                    'menu_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'shopready-elementor-addon' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_hover_color',
                        [
                            'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu > li:hover > a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'menu_hover_background',
                            'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu > li:hover > a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'menu_hover_border',
                            'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu > li:hover > a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_hover_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu > li:hover > a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab();

                // Menu Active Tab
                $this->start_controls_tab(
                    'menu_style_active_tab',
                    [
                        'label' => esc_html__( 'Active', 'shopready-elementor-addon' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_active_color',
                        [
                            'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li.current-menu-item a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'menu_active_background',
                            'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li.current-menu-item a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'menu_active_border',
                            'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
                            'selector' => '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li.current-menu-item a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_active_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav ul.woo__ready__menu li.current-menu-item a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*-------------------------
			MENU ITEM STYLE END
        --------------------------*/

      
    }

    protected function html( ) {

        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();
        
        $this->add_render_attribute( 'woo_ready_menu_attr', 'class', 'woo__ready__menu__area woo__ready__menu__style__'.$settings['inline_menu_style'] );
        
        $menuargs = [
            'echo'        => false,
            'menu'        => isset( $settings['inline_menu_id'] ) ? $settings['inline_menu_id'] : 0,
            'menu_class'  => 'woo__ready__menu',
            'menu_id'     => 'menu-'. $id,
            'fallback_cb' => '__return_empty_string',
            'container'   => '',
            'depth'       => 1
        ];

        // General Menu.
        $menu_html = wp_nav_menu( $menuargs );

        ?>
            <div <?php echo wp_kses_post($this->get_render_attribute_string('woo_ready_menu_attr')); ?> >
                <nav class="single__menu__nav">
                    <?php
                        if( !empty( $menu_html ) ){
                            echo wp_kses_post($menu_html);
                        }
                    ?>
                </nav>
            </div>
        <?php
    }
}