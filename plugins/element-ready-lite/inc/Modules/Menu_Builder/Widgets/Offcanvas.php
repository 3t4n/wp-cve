<?php

namespace Element_Ready\Modules\Menu_Builder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Offcanvas extends Widget_Base
{

    public $base;

    public function get_name()
    {
        return 'element-ready-header-offcanvas';
    }
    public function get_keywords()
    {
        return ['active', 'sidebar', 'header', 'offcanvas'];
    }
    public function get_title()
    {
        return esc_html__('ER Offcanvas', 'element-ready-lite');
    }
    public function get_icon()
    {
        return 'eicon-menu-toggle';
    }
    public function get_categories()
    {
        return ['element-ready-addons'];
    }
    public function get_style_depends()
    {
        return [

            'er-menu-off-canvas'
        ];
    }
    public function get_script_depends()
    {
        return [
            'element-ready-menu-frontend-script'
        ];
    }
    public function layout()
    {
        return [

            'style1' => esc_html__('Offcanvas', 'element-ready-lite'),

        ];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'menu_layout',
            [
                'label' => esc_html__('Layout', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            '_style',
            [
                'label' => esc_html__('Style', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => $this->layout()
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Content Settings', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'offcanvas_container_direction',
            [
                'label' => esc_html__('Direction left', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'offcanvas_display',
            [
                'label' => esc_html__('Device Breakpoint', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('No Action', 'element-ready-lite'),
                    'd-xl-none' => esc_html__('Extra Large', 'element-ready-lite'),
                    'd-lg-none' => esc_html__('Large', 'element-ready-lite'),
                    'd-md-none' => esc_html__('Medium', 'element-ready-lite'),
                    'd-sm-none' => esc_html__('Small', 'element-ready-lite'),

                ],

            ]
        );

        $this->add_control(
            'offcanvas_template_id',
            [
                'label' => esc_html__('Select Content Template', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => element_ready_elementor_template(),
                'description' => esc_html__('Please select elementor templete from here, if not create elementor template from menu', 'element-ready-lite')

            ]
        );


        $this->add_control(
            'offcanvas_menu_icon',
            [
                'label' => esc_html__('Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bars',
                    'library' => 'solid',
                ],
            ]
        );


        $this->add_control(
            'offcanvas_text',
            [

                'label' => esc_html__('Text', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Theme & supports', 'element-ready-lite'),
                'default' => esc_html__('Theme Demos', 'element-ready-lite'),
                'condition' => [
                    '_style' => ['style2']
                ],
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'main_container_section_offcanavs_close___sec',
            [
                'label' => esc_html__('Offcanvas close icon', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '__offcanvas_box_menu_iqwert_close_icn_color_',
            [

                'label' => esc_html__('icon Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close i' => 'color: {{VALUE}};',


                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '__offcanvase_conteo_iiocn_close_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close i',

            ]
        );


        $this->add_control(
            'element_ready_main_offcanvas_popover_section_sizen',
            [
                'label' => esc_html__('Box Size', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'element_ready_main_style1_offcanavs_section__width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_ready_main_style1_offcanvas_section_container_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );


        $this->end_popover();

        $this->add_responsive_control(
            'element_ready_main_style1_offcanvas_section_line_height',
            [
                'label' => esc_html__('Line Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
                        'step' => 5,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'main_container_section_offcanavs_nav_bar_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'main_navbar_section_offcanvas__margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'main_header_offcanvas_navbar_section_background',
                'label' => esc_html__('Background', 'element-ready-lite'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'main_nav_bar_offcanvas_header_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close',
            ]
        );

        $this->add_control(
            'header_offcanvas_sjkhr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );


        $this->add_responsive_control(
            'header_offcanvas_navbar_nav_position_type',
            [
                'label' => esc_html__('Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'fixed' => esc_html__('Fixed', 'element-ready-lite'),
                    'absolute' => esc_html__('Absolute', 'element-ready-lite'),
                    'relative' => esc_html__('Relative', 'element-ready-lite'),
                    'sticky' => esc_html__('Sticky', 'element-ready-lite'),
                    'static' => esc_html__('Static', 'element-ready-lite'),
                    'inherit' => esc_html__('inherit', 'element-ready-lite'),
                    '' => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_offcanvas_vbar_nav_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_offcanvas_navbar_nav_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_offcanvas_navbar_nav_position_bottom',
            [
                'label' => esc_html__('Position bottom', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_offcanvas_nabvar_nav_position_right',
            [
                'label' => esc_html__('Position right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu .sidebar-menu-close' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();



        $this->end_controls_section();


        $this->start_controls_section(
            'mobile_menu_offcanvas_icon_ent_section',
            [
                'label' => esc_html__('Offcanvas icon', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'offcanvas___mobile_humberger_icon_color_',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-canvas-bar svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'offcanvas___mobile_humberger_hover_icon_color_',
            [

                'label' => esc_html__('Hover Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-canvas-bar:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-canvas-bar:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_offcanvas__mobile_humberger_ho_icon_bgcolor_',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            '_offcanvas__mobile_humberger_hover_icon_bgcolor_',
            [

                'label' => esc_html__('Hover Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar:hover' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            '_offcanvas__mobile_humberger__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
                'separator' => 'before',

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => '_offcanvas__mobile_humbergeri__section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-canvas-bar',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_offcanvas__mobile_humberger_icon_li_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-canvas-bar i',

            ]
        );

        $this->add_responsive_control(
            '_offcanvas__mobile_humberger_icon_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-canvas-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            '_offcanvas__mobile_humberger_icon_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-canvas-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',

            ]
        );


        $this->add_control(
            '_offcanvas_hamberger_styleuiioio_popover_dsdpoistion_sizen',
            [
                'label' => esc_html__('Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',

            ]
        );
        $this->start_popover();
        $this->add_responsive_control(
            'offcanvas__menu_dsdssdscontainer_nav_position_type',
            [
                'label' => esc_html__('Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'fixed' => esc_html__('Fixed', 'element-ready-lite'),
                    'absolute' => esc_html__('Absolute', 'element-ready-lite'),
                    'relative' => esc_html__('Relative', 'element-ready-lite'),
                    'sticky' => esc_html__('Sticky', 'element-ready-lite'),
                    'static' => esc_html__('Static', 'element-ready-lite'),
                    'inherit' => esc_html__('inherit', 'element-ready-lite'),
                    '' => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas__menu_dsd_container_nav_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas__menu_dsds_container_nav_position_right',
            [
                'label' => esc_html__('Position right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas__menu_sdsd_conainer_nav_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-canvas-bar' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->end_controls_section();
        $this->start_controls_section(
            '__mobile_menu_offcanvas_content_section',
            [
                'label' => esc_html__('Offcanvas Container', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_offcanvas_overlay_popover____er',
            [
                'label' => esc_html__('Overlay', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',

            ]
        );
        $this->start_popover();

        $this->add_control(
            '_offcanvas_overlay_mobile__ho__bgcolor_',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            '_offcanvas_overlay_opacity_ss',
            [
                'label' => esc_html__('Transparent', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'opacity: {{SIZE}};',

                ],

            ]
        );

        $this->add_responsive_control(
            '_offcanvas_overlay___z_index',
            [
                'label' => esc_html__('Z-index', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 2000,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'z-index: {{SIZE}};',

                ],

            ]
        );

        $this->add_control(
            '_offcanvas_overlay__box_popover_section_sizen',
            [
                'label' => esc_html__('Box Size', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            '_offcanvas_overlay__section__width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_offcanvas_overlay__container_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();

        $this->add_control(
            '_offcanvas_overlay__style_popover_ddssd_poistion_sin',
            [
                'label' => esc_html__('Overlay Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',

            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'offcanvas__overlay_menu_dsdssds_container_nav_position_type',
            [
                'label' => esc_html__('Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'fixed' => esc_html__('Fixed', 'element-ready-lite'),
                    'absolute' => esc_html__('Absolute', 'element-ready-lite'),
                    'relative' => esc_html__('Relative', 'element-ready-lite'),
                    'sticky' => esc_html__('Sticky', 'element-ready-lite'),
                    'static' => esc_html__('Static', 'element-ready-lite'),
                    'inherit' => esc_html__('inherit', 'element-ready-lite'),
                    '' => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas__overlay__menu_dsd_container_nav_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas__overlay__menu_dsds_container_nav_position_right',
            [
                'label' => esc_html__('Position right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas__overlay_menu_sdsty_conainer_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-body-overlay' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();
        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'offcanvas_overlay_content_mainsection_background',
                'label' => esc_html__('Background', 'element-ready-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element-ready-sidebar-menu',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'offcanvas_overlay_content_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-sidebar-menu',
            ]
        );

        $this->add_responsive_control(
            'offcanvas_overlay_content__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'offcanvas_overlay_content___section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-sidebar-menu',
            ]
        );

        $this->add_responsive_control(
            'fcanvas_overlay_contentr_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'fcanvas_overlay_contentr_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'offcanvas_overlay_content_popover_section_sizen',
            [
                'label' => esc_html__('Container Size', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'offcanvas_overlay_content_section__width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas_overlay_content_section_container_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();

        $this->add_control(
            'offcanvas_overlay_content_style_popover_ddssd_poistion_sin',
            [
                'label' => esc_html__('Content Container Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',

            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'offcanvas_overlay_content_menu_dsdssds_container_nav_position_type',
            [
                'label' => esc_html__('Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'fixed' => esc_html__('Fixed', 'element-ready-lite'),
                    'absolute' => esc_html__('Absolute', 'element-ready-lite'),
                    'relative' => esc_html__('Relative', 'element-ready-lite'),
                    'sticky' => esc_html__('Sticky', 'element-ready-lite'),
                    'static' => esc_html__('Static', 'element-ready-lite'),
                    'inherit' => esc_html__('inherit', 'element-ready-lite'),
                    '' => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas_overlay_content__menu_dsd_container_nav_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas_overlay_content_overlay__menu_dsds_container_nav_position_right',
            [
                'label' => esc_html__('Position right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'offcanvas_overlay_content_overlay_sdsty_conainer_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 1600,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sidebar-menu' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();
        $this->end_controls_section();

    } //Register control end



    protected function render()
    {



        $settings = $this->get_settings();
        $widget_id = $this->get_id();
        ?>
        <!--====== offcanvas START ======-->
        <?php if ($settings['_style'] == 'style1'): ?>
            <?php include('layout/offcanvas/style1.php'); ?>
        <?php endif; ?>
        <?php if ($settings['_style'] == 'style2'): ?>
            <?php include('layout/offcanvas/style2.php'); ?>
        <?php endif; ?>
        <!--====== offcanvas ENDS ======-->
        <?php
    }
    protected function content_template()
    {
    }
}