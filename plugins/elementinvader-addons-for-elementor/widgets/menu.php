<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliMenu extends Elementinvader_Base {

    public $view_folder = 'menu';
    protected $nav_menu_index = 1;
    public $inline_css = '';
    public $inline_css_tablet = '';
    public $inline_css_mobile = '';

    public function __construct($data = array(), $args = null) {
        wp_enqueue_style('elementinvader_addons_for_elementor-main', plugins_url('/assets/css/main.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-menu';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Menu', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-slider-full-screen';
    }

    public function on_export($element) {
        unset($element['settings']['menu']);

        return $element;
    }

    protected function get_nav_menu_index() {
        return $this->nav_menu_index++;
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();

        $options = [];

        foreach ($menus as $menu) {
            $options[$menu->slug] = $menu->name;
        }

        return $options;
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {

        \Elementor\Controls_Manager::add_tab(
                'config_tab',
                esc_html__('Config', 'elementinvader-addons-for-elementor')
        );



        $this->start_controls_section(
                'section_layout',
                [
                    'label' => esc_html__('Menu Configuration', 'elementinvader-addons-for-elementor'),
                    'tab' => 'config_tab',
                ]
        );

        $menus = $this->get_available_menus();

        if (!empty($menus)) {
            $this->add_control(
                    'menu',
                    [
                        'label' => esc_html__('Menu from Dashboard', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'options' => $menus,
                        'save_default' => true,
                        'separator' => 'after',
                        'description' => sprintf(__('<a href="%s" target="_blank">Open menus in Dashboard to Edit</a>', 'elementinvader-addons-for-elementor'), admin_url('nav-menus.php')),
                    ]
            );
        } else {
            $this->add_control(
                    'menu',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        'raw' => '<strong>' . esc_html__('There are no menus in your site.', 'elementinvader-addons-for-elementor') . '</strong><br>' . sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'elementinvader-addons-for-elementor'), admin_url('nav-menus.php?action=edit&menu=0')),
                        'separator' => 'after',
                        'content_classes' => 'wl-panel-alert wl-panel-alert-warning',
                    ]
            );
        }


        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_main-menu',
                [
                    'label' => esc_html__('Main Styles', 'elementinvader-addons-for-elementor'),
                    'condition' => [
                        'layout!' => 'dropdown',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'menu_typography',
                    'selector' => '{{WRAPPER}} .wl-nav-menu .wl-item',
                ]
        );

        $this->start_controls_tabs('tabs_menu_item_style');

        $this->start_controls_tab(
                'tab_menu_item_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_menu_item',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--main .wl-item' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_menu_item',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--main .wl-item' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_control(
                'indicator_color',
                [
                    'label' => esc_html__('SubMenu Indicator Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--main .menu-item.menu-item-has-children > .wl-item:after' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_control(
            'mask_color',
            [
                'label' => esc_html__('Color of mask for mobile view menu', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_nav_mask' => 'background: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_menu_item_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_menu_item_hover',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--main .wl-item:hover' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_menu_item_hover',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--main .wl-item:hover' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_control(
                'indicator_color_hover',
                [
                    'label' => esc_html__('SubMenu Indicator Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .menu-item.menu-item-has-children > .wl-item:hover:after' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_menu_item_active',
                [
                    'label' => esc_html__('Active', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_menu_item_active',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .current-menu-parent > .wl-item' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .current-menu-item > .wl-item' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .menu-item.active > .wl-item' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_menu_item_active',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [ 
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .current-menu-parent > .wl-item' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .current-menu-item > .wl-item' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .menu-item.active > .wl-item' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_control(
                'indicator_color_active',
                [
                    'label' => esc_html__('SubMenu Indicator Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .menu-item.menu-item-has-children.current-menu-parent > .wl-item:hover:after' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .menu-item.menu-item-has-children.current-menu-item > .wl-item:hover:after' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .menu-item.active > .wl-item:hover:after' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        /* This control is required to handle with complicated conditions */
        $this->add_control(
                'hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
        );

        $this->add_responsive_control(
                'padding_horizontal_menu_item',
                [
                    'label' => esc_html__('Horizontal Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_responsive_control(
                'padding_vertical_menu_item',
                [
                    'label' => esc_html__('Vertical Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_responsive_control(
                'menu_space_between',
                [
                    'label' => esc_html__('Space Between', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        'body:not(.rtl) {{WRAPPER}}  .elementinvader-addons-for-elementor .wl-nav-menu--layout-horizontal .wl-nav-menu > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--layout-horizontal .wl-nav-menu > li:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--main:not(.wl-nav-menu--layout-horizontal) .wl-nav-menu > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->end_controls_section();
        

        $this->start_controls_section(
                'section_layout_text_static',
                [
                    'label' => esc_html__('Static Text', 'elementinvader-addons-for-elementor'),
                ]
        );
        $this->add_control(
                'menu_text',
                [
                    'label' => esc_html__('Hamburger Text', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
                'section_layout_s',
                [
                    'label' => esc_html__('Standard Menu', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'layout',
                [
                    'label' => esc_html__('Orientation', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'horizontal',
                    'options' => [
                        'horizontal' => esc_html__('Horizontal', 'elementinvader-addons-for-elementor'),
                        'vertical' => esc_html__('Vertical', 'elementinvader-addons-for-elementor'),
                        'dropdown' => esc_html__('Dropdown', 'elementinvader-addons-for-elementor'),
                    ],
                    'frontend_available' => true,
                ]
        );

        $this->add_control(
                'align_items',
                [
                    'label' => esc_html__('Align', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__('Left', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__('Right', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-right',
                        ],
                        'stretch' => [
                            'title' => esc_html__('Stretch', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-stretch',
                        ],
                    ],
                    'render_type' => 'template',
                    'default' => 'flex-start',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main.wl-nav-menu--layout-horizontal' => 'justify-content: {{VALUE}};',
                    ],
                    'condition' => [
                        'layout!' => 'dropdown',
                    ],
                ]
        );

        $this->add_control(
                'indicator',
                [
                    'label' => esc_html__('Submenu Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'classic',
                    'options' => [
                        'none' => esc_html__('None', 'elementinvader-addons-for-elementor'),
                        'classic' => esc_html__('Classic', 'elementinvader-addons-for-elementor'),
                        'chevron' => esc_html__('Chevron', 'elementinvader-addons-for-elementor'),
                        'angle' => esc_html__('Angle', 'elementinvader-addons-for-elementor'),
                        'plus' => esc_html__('Plus', 'elementinvader-addons-for-elementor'),
                    ],
                    'prefix_class' => 'wl-nav-menu--indicator-',
                ]
        );

        $this->add_control(
                'heading_mobile_dropdown',
                [
                    'label' => esc_html__('Mobile Dropdown', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'layout!' => 'dropdown',
                    ],
                ]
        );

        $this->add_control(
                'dropdown',
                [
                    'label' => esc_html__('Breakpoint', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'tablet',
                    'options' => [
                        /* translators: %d: Breakpoint number. */
                        'mobile' => sprintf(__('Mobile (< %dpx)', 'elementinvader-addons-for-elementor'), Plugin::$instance->breakpoints->get_device_min_breakpoint( Breakpoints_Manager::BREAKPOINT_KEY_TABLET ) ),
                        /* translators: %d: Breakpoint number. */
                        'tablet' => sprintf(__('Tablet (< %dpx)', 'elementinvader-addons-for-elementor'), Plugin::$instance->breakpoints->get_device_min_breakpoint( Breakpoints_Manager::BREAKPOINT_KEY_DESKTOP )),
                        'none' => esc_html__('None', 'elementinvader-addons-for-elementor'),
                    ],
                    'prefix_class' => 'wl-nav-menu--dropdown-',
                    'condition' => [
                        'layout!' => 'dropdown',
                    ],
                ]
        );

        $this->add_control(
                'absolute_position',
                [
                    'label' => esc_html__('Overflow', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => esc_html__('Show over page content.', 'elementinvader-addons-for-elementor'),
                    'prefix_class' => 'wl-nav-menu--',
                    'return_value' => 'absolute',
                    'frontend_available' => true,
                    'condition' => [
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->add_control(
                'full_width',
                [
                    'label' => esc_html__('Fixed Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 30,
                            'max' => 1500,
                        ],
                        '%' => [
                            'min' => 15,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 100,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.wl-nav-menu--stretch .elementinvader-addons-for-elementor .wl-nav-menu__container.wl-nav-menu--dropdown' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->add_control(
                'full_width_mobile_menu',
                [
                    'label' => esc_html__('Width Mobile Menu', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 30,
                            'max' => 1500,
                        ],
                        '%' => [
                            'min' => 15,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 30,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.wl-nav-menu--toggle .elementinvader-addons-for-elementor .wl-menu-toggle:not(.wl-active) + .wl-nav-menu__container' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_responsive_control(
            'mobile_container',
            [
                    'label' => esc_html__( 'Padding Mobile Menu Container', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .eli-container .wl-nav-menu--dropdown.wl-nav-menu__container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
            ]
        );

        
        $this->add_control(
            'mobile_container_bckg',
            [
                'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_control(
                'text_align_but_h',
                [
                    'label' => esc_html__('Toogle', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'text_align',
                [
                    'label' => esc_html__('Align', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'aside',
                    'options' => [
                        'aside' => esc_html__('Aside', 'elementinvader-addons-for-elementor'),
                        'center' => esc_html__('Center', 'elementinvader-addons-for-elementor'),
                    ],
                    'prefix_class' => 'wl-nav-menu__text-align-',
                    'separator' => 'before',
                    'condition' => [
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->add_control(
                'toggle',
                [
                    'label' => esc_html__('Toggle Button', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'burger',
                    'options' => [
                        '' => esc_html__('None', 'elementinvader-addons-for-elementor'),
                        'burger' => esc_html__('Hamburger', 'elementinvader-addons-for-elementor'),
                    ],
                    'prefix_class' => 'wl-nav-menu--toggle wl-nav-menu--',
                    'render_type' => 'template',
                    'frontend_available' => true,
                    'condition' => [
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->add_control(
                'toggle_align',
                [
                    'label' => esc_html__('Toggle Align', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'center',
                    'options' => [
                        'left' => [
                            'title' => esc_html__('Left', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__('Right', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'selectors_dictionary' => [
                        'left' => 'justify-content: flex-start;',
                        'center' => 'justify-content: center;',
                        'right' => 'justify-content: flex-end;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-menu-toggle' => '{{VALUE}}',
                    ],
                    'condition' => [
                        'toggle!' => '',
                        'dropdown!' => 'none',
                    ],
                    'label_block' => false,
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_dropdown',
                [
                    'label' => esc_html__('Dropdown', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->add_control(
                'dropdown_description',
                [
                    'raw' => esc_html__('Visible on submenu and on mobiles', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'wl-descriptor',
                ]
        );

        $this->start_controls_tabs('tabs_dropdown_item_style');

        $this->start_controls_tab(
                'tab_dropdown_item_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_dropdown_item',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_dropdown_item',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu--dropdown a' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item',
                'selector' => 
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu--dropdown a,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu .sub-menu .menu-item>a,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu--dropdown .sub-menu .menu-item>a,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .wl-nav-menu >.menu-item>a',
            ]
        );
    
        $this->add_responsive_control(
                'dropdown_item_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu--dropdown a,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu .sub-menu .menu-item>a,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--main .wl-nav-menu--dropdown .sub-menu .menu-item>a,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .wl-nav-menu >.menu-item>a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_dropdown_item_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_dropdown_item_hover',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a:focus,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active:focus,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item.active>a,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .sub-menu .menu-item.active>a,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.highlighted' => 'color: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'background_color_dropdown_item_hover',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                       '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a:focus,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .sub-menu .menu-item.active>a,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item.active>a:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item.active>a:focus,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item>a:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item.active>a,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active:focus,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.highlighted' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );
 
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item_hover',
                'selector' => 
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a:hover,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item>a:hover,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .sub-menu .menu-item>a:hover',

                'separator' => 'before',
            ]
        );
    
        $this->add_responsive_control(
                'dropdown_item_radius_hover',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu .sub-menu .menu-item>a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .sub-menu .menu-item>a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_dropdown_item_active',
                [
                    'label' => esc_html__('Active', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_dropdown_item_active',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .current-menu-parent > a.wl-item' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_dropdown_item_active', 
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .current-menu-parent > a.wl-item' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item_active',
                'selector' => 
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown .current-menu-parent > a.wl-item',
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active',

                'separator' => 'before',
            ]
        );
    
        $this->add_responsive_control(
                'dropdown_item_radius_active',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a.wl-item-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'drop_box_padding',
            [
                    'label' => esc_html__( 'Drop List Padding', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
            ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'dropdown_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'exclude' => ['line_height'],
                    'selector' => '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a',
                  
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'dropdown_border',
                    'selector' => '{{WRAPPER}} .wl-nav-menu--dropdown',
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'dropdown_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown li:last-child a' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'dropdown_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .wl-nav-menu--main .wl-nav-menu--dropdown, {{WRAPPER}} .wl-nav-menu__container.wl-nav-menu--dropdown',
                ]
        );

        $this->add_responsive_control(
                'padding_horizontal_dropdown_item',
                [
                    'label' => esc_html__('Horizontal Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'padding_vertical_dropdown_item',
                [
                    'label' => esc_html__('Vertical Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--dropdown a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'heading_dropdown_divider',
                [
                    'label' => esc_html__('Divider', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'dropdown_divider',
                    'selector' => '{{WRAPPER}} .wl-nav-menu--dropdown li:not(:last-child)',
                    'exclude' => ['width'],
                ]
        );

        $this->add_control(
                'dropdown_divider_width',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-nav-menu--dropdown li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'dropdown_divider_border!' => '',
                    ],
                ]
        );

        $this->add_responsive_control(
                'dropdown_top_distance',
                [
                    'label' => esc_html__('Distance', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-nav-menu--main > .wl-nav-menu > li > .wl-nav-menu--dropdown, {{WRAPPER}} .wl-nav-menu__container.wl-nav-menu--dropdown' => 'margin-top: {{SIZE}}{{UNIT}} !important',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_section();

        /* submenu on mobile */
        $this->start_controls_section(
                'section_style_dropdown_mobile',
                [
                    'label' => esc_html__('Submenu On Mobiles', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->add_control(
                'dropdown_description_mobile',
                [
                    'raw' => esc_html__('Visible on submenu of mobiles', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'wl-descriptor',
                ]
        );

        $this->start_controls_tabs('tabs_dropdown_item_style_mobiles');

        $this->start_controls_tab(
                'tab_dropdown_item_normal_mobile',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_dropdown_item_mobile',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_dropdown_item_mobile',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item_mobile',
                'selector' => 
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu  a,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu .menu-item>a',
            ]
        );
    
        $this->add_responsive_control(
                'dropdown_item_radius_mobile_f',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu  a,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu .menu-item>a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_dropdown_item_hover_mobile_f',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_dropdown_item_hover_f',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                       '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a:focus,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active:focus,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu li.menu-item.active > a:focus,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu li.menu-item.active > a:hover,
                        {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.highlighted' => 'color: {{VALUE}}',
                    ],
                ]
        );
        $this->add_control(
                'background_color_dropdown_item_hover_mobile_f',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [ 
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a:focus,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu li.menu-item.active > a:focus,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu li.menu-item.active > a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active:focus,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.highlighted' => 'background: {{VALUE}}',
                     ],
                    'separator' => 'none',
                ]
        );
 
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item_hover_mobile_f',
                'selector' => 
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a:hover,
                                {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu .menu-item>a:hover',

                'separator' => 'before',
            ]
        );
    
        $this->add_responsive_control(
                'dropdown_item_radius_hover_mobile_f',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu .menu-item>a:hover,
                         {{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu .menu-item>a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_dropdown_item_active_mobile_f',
                [
                    'label' => esc_html__('Active', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'color_dropdown_item_active_mobile_f',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_control(
                'background_color_dropdown_item_active_mobile_f',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'none',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item_active_mobile',
                'selector' => 
                               '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active',

                'separator' => 'before',
            ]
        );
    
        $this->add_responsive_control(
                'dropdown_item_radius_active_mobile_f',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a.wl-item-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'drop_box_padding_mobile_subbox',
            [
                    'label' => esc_html__( 'Sub menu Padding', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
            ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'dropdown_border_mobile_subbox',
                    'selector' => '{{WRAPPER}} .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu',
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'dropdown_border_radius_mobile_subbox',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'padding_horizontal_dropdown_item_mobile_subbox',
                [
                    'label' => esc_html__('Horizontal Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'padding_vertical_dropdown_item_mobile_subbox',
                [
                    'label' => esc_html__('Vertical Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu  a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'heading_dropdown_divider_mobile_subbox',
                [
                    'label' => esc_html__('Divider', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'dropdown_divider_mobile_subbox',
                    'selector' => '{{WRAPPER}} .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu  li:not(:last-child)',
                    'exclude' => ['width'],
                ]
        );

        $this->add_control(
                'dropdown_divider_width_mobile_subbox',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'dropdown_divider_border!' => '',
                    ],
                ]
        );

        $this->add_control(
            'color_dropdown_item_active_mobile_f_mobile_subbox',
            [
                'label' => esc_html__('Bacground Color', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-nav-menu--dropdown.wl-nav-menu__container .sub-menu' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section('style_toggle',
                [
                    'label' => esc_html__('Toggle Button', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'toggle!' => '',
                        'dropdown!' => 'none',
                    ],
                ]
        );

        $this->start_controls_tabs('tabs_toggle_style');

        $this->start_controls_tab(
                'tab_toggle_style_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'toggle_text_typo',
                    'selector' => '{{WRAPPER}} .elementinvader-addons-for-elementor  div.wl-menu-toggle .wl-screen-only',
                ]
        );

        $this->add_control(
                'toggle_color',
                [
                    'label' => esc_html__('Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  div.wl-menu-toggle' => 'color: {{VALUE}}', // Harder selector to override text color control
                    ],
                ]
        );

        $this->add_control(
                'toggle_background_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl-menu-toggle' => 'background-color: {{VALUE}}',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_toggle_style_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );


        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'toggle_text_typo_hover',
                    'selector' => '{{WRAPPER}} .elementinvader-addons-for-elementor  div.wl-menu-toggle:hover .wl-screen-only',
                ]
        );

        $this->add_control(
                'toggle_color_hover',
                [
                    'label' => esc_html__('Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  div.wl-menu-toggle:hover' => 'color: {{VALUE}}', // Harder selector to override text color control
                    ],
                ]
        );

        $this->add_control(
                'toggle_background_color_hover',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-menu-toggle:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
                'toggle_size',
                [
                    'label' => esc_html__('Size', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 15,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-menu-toggle' => 'font-size: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'toggle_border_width',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 10,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-menu-toggle' => 'border-width: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'toggle_width',
                [
                    'label' => esc_html__('Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-menu-toggle' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'toggle_height',
                [
                    'label' => esc_html__('Height', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-menu-toggle' => 'height: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        $this->add_control(
                'toggle_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor  .wl-menu-toggle' => 'border-radius: {{SIZE}}{{UNIT}}',
                    ],
                ]
        );

        /* icon toogle button */

        $this->add_control(
            'toggle_icon',
            [
                'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'eicon-menu-bar',
                    'library' => 'solid',
                ],
            ]
        );

        $this->end_controls_section();

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        parent::render();
        $settings = $this->get_settings();

        $available_menus = $this->get_available_menus();

        if (!$available_menus) {
            return;
        }

        $menus = $this->get_available_menus();

        $menu_id = $settings['menu']; 

        if(empty($menu_id)) {
            $menu_id = NULL;
            $locations = get_nav_menu_locations();
            if(isset($locations['main_menu'])) {
                $menu_id = $locations['main_menu'];
            }
    
            if (empty($menu_id)) {
                if($menus = wp_get_nav_menus())
                    $menu_id =(int)$menus[0]->term_id;
            }
        }

        if(empty($menu_id)) {
            $menu_id = array_keys($menus)[0];
        }

        $args = [
            'echo' => false,
            'menu' => $menu_id,
            'menu_class' => 'wl-nav-menu wl-nav-menu',
            'menu_id' => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
            'fallback_cb' => '__return_empty_string',
        ];

        if ('vertical' === $settings['layout']) {
            $args['menu_class'] .= ' sm-vertical';
        }

        // Add custom filter to handle Nav Menu HTML output.
        add_filter('nav_menu_link_attributes', [$this, 'handle_link_classes'], 10, 4);
        add_filter('nav_menu_submenu_css_class', [$this, 'handle_sub_menu_classes']);
        add_filter('nav_menu_item_id', '__return_empty_string');

        // General Menu.
        $menu_html = wp_nav_menu($args);

        // Dropdown Menu.
       // $args['menu_id'] = 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id();
        $dropdown_menu_html =  $menu_html;

        // Remove all our custom filters.
        remove_filter('nav_menu_link_attributes', [$this, 'handle_link_classes']);
        remove_filter('nav_menu_submenu_css_class', [$this, 'handle_sub_menu_classes']);
        remove_filter('nav_menu_item_id', '__return_empty_string');

        if (empty($menu_html)) {
            return;
        }

        $this->add_render_attribute('menu-toggle', [
            'class' => 'wl-menu-toggle',
            'role' => 'button',
            'tabindex' => '0',
            'aria-label' => esc_html__('Menu Toggle', 'elementinvader-addons-for-elementor'),
            'aria-expanded' => 'false',
        ]);

        if (Plugin::$instance->editor->is_edit_mode()) {
            $this->add_render_attribute('menu-toggle', [
                'class' => 'elementor-clickable',
            ]);
        }

        $this->add_render_attribute('main-menu', 'role', 'navigation');
        ?>

        <?php

        if (Plugin::$instance->editor->is_edit_mode()) {
            $output = $this->view('menu_layout', ['dropdown_menu_html'=>$dropdown_menu_html,'menu_html'=>$menu_html,'settings'=>$settings, 'is_edit_mode' => true]);
        } else {
            $output = $this->view('menu_layout', ['dropdown_menu_html'=>$dropdown_menu_html,'menu_html'=>$menu_html,'settings'=>$settings]);
        }
        
        
        if (Plugin::$instance->editor->is_edit_mode()):?>
            <script>
                jQuery('document').ready(function($){
                    $('.eli-menu .wl-menu-toggle,.wl_close-menu,.elementinvader-addons-for-elementor .wl_nav_mask').off().on('click', function (e) {
                        e.preventDefault();
                    
                        var menu_widg = $(this).closest('.elementor-widget-eli-menu');
                        menu_widg.toggleClass('wl_nav_show');
                    });
                });
            </script>
        <?php endif;

        echo wp_kses_post($output);
    }

    public function handle_link_classes($atts, $item, $args, $depth) {
        $classes = $depth ? 'wl-sub-item' : 'wl-item';
        $is_anchor = false !== strpos($atts['href'], '#');

        if (!$is_anchor && in_array('current-menu-item', $item->classes)) {
            $classes .= ' wl-item-active';
        }

        if ($is_anchor) {
            $classes .= ' wl-item-anchor';
            if (Plugin::$instance->editor->is_edit_mode()) {
                $classes .= ' elementor-clickable';
            }
        }


        if (empty($atts['class'])) {
            $atts['class'] = $classes;
        } else {
            $atts['class'] .= ' ' . $classes;
        }

        return $atts;
    }

    public function handle_sub_menu_classes($classes) {
        $classes[] = 'wl-nav-menu--dropdown';

        return $classes;
    }

}
