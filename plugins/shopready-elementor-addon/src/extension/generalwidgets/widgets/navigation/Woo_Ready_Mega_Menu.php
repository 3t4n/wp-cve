<?php

namespace Shop_Ready\extension\generalwidgets\widgets\navigation;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Shop_Ready\extension\generalwidgets\deps\Mega_Menu_Nav_Walker as Mega_Menu_Nav_Walker;

if (!defined('ABSPATH'))
    exit;

class Woo_Ready_Mega_Menu extends \Shop_Ready\extension\generalwidgets\Widget_Base
{

    public function layout()
    {
        return [
            'style1' => esc_html__('Menu 1', 'shopready-elementor-addon'),
        ];
    }
    public function menu_list()
    {

        $return_menus = [];
        $menus = wp_get_nav_menus();
        if (is_array($menus)) {
            foreach ($menus as $menu) {
                $return_menus[$menu->term_id] = $menu->name;
            }
        }
        return $return_menus;
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'menu_layout',
            [
                'label' => esc_html__('Layout', 'shopready-elementor-addon'),
            ]
        );
        $this->add_control(
            'menu_style',
            [
                'label' => esc_html__('Style', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => $this->layout()
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Settings', 'shopready-elementor-addon'),
            ]
        );

        $this->start_controls_tabs(
            'menu_type_tabs'
        );

        $this->start_controls_tab(
            'style_main_menu_tab',
            [
                'label' => esc_html__('Main Menu', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'enable_mega_menu_content',
            [
                'label' => esc_html__('Mega Menu Content', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'menu_style' => ['style1']
                ]
            ]
        );



        $this->add_control(
            'menu_selected',
            [
                'label' => esc_html__('Menu', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->menu_list()
            ]
        );


        $this->add_control(
            'menu_depth',
            [
                'label' => esc_html__('Nested Depth', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'bedge_enable',
            [
                'label' => esc_html__('Bedge?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );


        $this->add_control(
            'first_label_indicator_icon_enable',
            [
                'label' => esc_html__('First lavel Indicator', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'first_label_indicator_icon',
            [
                'label' => esc_html__('First Label Indicator', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'first_label_indicator_icon_enable' => ['yes']
                ]
            ]
        );

        $this->add_control(
            'second_label_indicator_icon_enable',
            [
                'label' => esc_html__('Second lavel Indicator', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'second_label_indicator_icon',
            [
                'label' => esc_html__('Second Label Indicator', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'second_label_indicator_icon_enable' => ['yes']
                ]
            ]
        );


        $this->add_responsive_control(
            'main_menu_titlesdsd_align',
            [
                'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [

                    'display:flex;justify-content:left;' => [

                        'title' => esc_html__('Left', 'shopready-elementor-addon'),
                        'icon' => 'eicon-chevron-left',

                    ],
                    'display:flex;justify-content:center;' => [

                        'title' => esc_html__('Center', 'shopready-elementor-addon'),
                        'icon' => 'eicon-text-align-center',

                    ],
                    'display:flex;justify-content:right;' => [

                        'title' => esc_html__('Right', 'shopready-elementor-addon'),
                        'icon' => 'eicon-chevron-right',

                    ],

                    '' => [

                        'title' => esc_html__('Justified', 'shopready-elementor-addon'),
                        'icon' => 'eicon-text-align-justify',

                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .woo-ready-wapper' => '{{VALUE}};',
                ],
            ]
        ); //Responsive control end


        $this->end_controls_tab();



        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->box_css(
            array(
                'title' => esc_html__('Manu Wrapper', 'shopready-elementor-addon'),
                'slug' => 'ul_mega_menu_box_pos_style',
                'element_name' => 'ulmega_menu_box_woo_ready_',
                'selector' => '{{WRAPPER}} .wooready-main-menu',
                'condition' => [
                    'enable_mega_menu_content' => ['yes'],
                    'menu_style' => ['style2', 'style1']
                ],
                'disable_controls' => [
                    'display',
                    'position',
                    'alignment',
                    'size'
                ],
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Mega Menu', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_box_pos_style',
                'element_name' => 'mega_menu_box_woo_ready_',
                'selector' => '{{WRAPPER}} .wooready-main-menu div.woo-ready-megamenu-submenu',
                'condition' => [
                    'enable_mega_menu_content' => ['yes'],
                    'menu_style' => ['style2', 'style1']
                ],
                'disable_controls' => [
                    'bg',
                    'border',
                    'alignment',
                    'display'
                ],
            )
        );

        $this->text_minimum_css(
            array(
                'title' => esc_html__('Menu Bedge', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_item_begd',
                'element_name' => 'mega_menu_item_bedge',
                'selector' => '{{WRAPPER}} .wooready-main-menu > li .badge',
                'hover_selector' => '{{WRAPPER}} .wooready-main-menu > li .badge:hover',
                'condition' => [

                    'bedge_enable' => ['yes']
                ],
            )
        );

        $this->text_minimum_css(
            array(
                'title' => esc_html__('Menu Item', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_item_',
                'element_name' => 'mega_menu_item_',
                'selector' => '{{WRAPPER}} .wooready-main-menu > li > a',
                'hover_selector' => '{{WRAPPER}} .wooready-main-menu > li:hover > a',
                'condition' => [

                    'menu_style' => ['style2', 'style1']
                ],
                'disable_controls' => [
                    'display'
                ],
            )
        );

        $this->text_minimum_css(
            array(
                'title' => esc_html__('First Lavel Indicator', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_item_indicator',
                'element_name' => 'mega_menu_item_findicator',
                'selector' => '{{WRAPPER}} .wooready-main-menu > li .wr-first-label-indicator',
                'hover_selector' => '{{WRAPPER}} .wooready-main-menu > li .wr-first-label-indicator:hover',
                'disable_controls' => [
                    'display',
                ],
            )
        );

        $this->box_minimum_css(
            array(
                'title' => esc_html__('DropDown Container', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_item_dropdown',
                'element_name' => 'mega_menu_item_dropdown',
                'selector' => '{{WRAPPER}} .wooready-main-menu > li > .woo-ready-megamenu-submenu',

                'condition' => [
                    'menu_style' => ['style2', 'style1']
                ],
            )
        );

        $this->text_minimum_css(
            array(
                'title' => esc_html__('Menu Nested Item', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_item_nested',
                'element_name' => 'mega_menu_item_nested',
                'selector' => '{{WRAPPER}} .wooready-main-menu ul.woo-ready-megamenu-submenu > li > a',
                'hover_selector' => '{{WRAPPER}} .wooready-main-menu ul.woo-ready-megamenu-submenu > li:hover > a',
                'condition' => [

                    'menu_style' => ['style2', 'style1']
                ],
            )
        );

        $this->text_minimum_css(
            array(
                'title' => esc_html__('Nested Indicator', 'shopready-elementor-addon'),
                'slug' => '_mega_menu_item_nested_indicator',
                'element_name' => 'mega_menu_item_nested_findicator',
                'selector' => '{{WRAPPER}} .woo-ready-dropdown li a .wr-nested-label-indicator',
                'hover_selector' => '{{WRAPPER}} .woo-ready-dropdown li a .wr-nested-label-indicator:hover',
                'disable_controls' => [
                    'display',
                ],
            )
        );
    } //Register control end

    function _get_menu_array($current_menu, $nested = true)
    {

        $array_menu = wp_get_nav_menu_items($current_menu);
        $menu = array();
        if (!is_array($array_menu)) {
            return [];
        }

        foreach ($array_menu as $m) {

            if (empty($m->menu_item_parent)) {
                $menu[$m->ID] = array();
                $menu[$m->ID]['ID'] = $m->ID;
                $menu[$m->ID]['title'] = $m->title;
                $menu[$m->ID]['url'] = $m->url;
                $menu[$m->ID]['children'] = array();
            }
        }

        if ($nested):
            $submenu = array();
            foreach ($array_menu as $m) {
                if ($m->menu_item_parent) {
                    $submenu[$m->ID] = array();
                    $submenu[$m->ID]['ID'] = $m->ID;
                    $submenu[$m->ID]['title'] = $m->title;
                    $submenu[$m->ID]['url'] = $m->url;
                    $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
                }
            }
        endif;
        return $menu;
    }

    protected function html()
    {

        $widget_id = 'element-ready-' . $this->get_id() . '-';
        $settings = $this->get_settings();
        $menu_id = $settings['menu_selected'];

        $nav_walker_default = [

            'w_menu_mega_menu_active' => $settings['enable_mega_menu_content'] == 'yes' ? true : false,
            'first_label_indicator_icon_enable' => $settings['first_label_indicator_icon_enable'] == 'yes' ? true : false,
            'second_label_indicator_icon_enable' => $settings['second_label_indicator_icon_enable'] == 'yes' ? true : false,
            'bedge_enable' => $settings['bedge_enable'] == 'yes' ? true : false,
        ];

        $args = [
            'menu' => $menu_id,
            'container' => 'div',
            'container_id' => false,
            'container_class' => 'woo-ready-wapper',
            'menu_class' => 'wooready-main-menu',
            'depth' => $settings['menu_depth'],
        ];

        $nav_walker_default['layout'] = $settings['menu_style'];
        $nav_walker_default['first_label_indicator_icon'] = isset($settings['first_label_indicator_icon']['library']) && $settings['first_label_indicator_icon']['library'] !== '' ? $settings['first_label_indicator_icon'] : '';
        $nav_walker_default['second_label_indicator_icon'] = isset($settings['second_label_indicator_icon']['library']) && $settings['second_label_indicator_icon']['library'] !== '' ? $settings['second_label_indicator_icon'] : '';


        ?>

        <!--====== Header START ======-->
        <?php if ($settings['menu_style'] == 'style1'): ?>

            <?php $args['walker'] = new Mega_Menu_Nav_Walker($nav_walker_default) ?>
            <?php include('layout/style1.php'); ?>

        <?php endif; ?>


        <?php

    }

    protected function content_template()
    {
    }
}
