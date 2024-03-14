<?php

namespace Element_Ready\Modules\Menu_Builder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Element_Ready\Modules\Menu_Builder\Base\Offcanvas_Nav_Walker as Offcanvas_Nav_Walker;
use Element_Ready\Widget_Controls\Box_Style as Style_Box;

if (!defined('ABSPATH'))
    exit;

require_once(ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php');
require_once(ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php');

class Menu extends Widget_Base
{

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_Ready_Position_Style;
    use Style_Box;

    public $base;

    public function get_name()
    {
        return 'element-ready-menu';
    }
    public function get_keywords()
    {
        return ['element ready', 'menu', 'nav', 'navigation'];
    }
    public function get_title()
    {
        return esc_html__('ER Menu', 'element-ready-lite');
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

        wp_register_style('er-round-menu', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-round-menu.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-offcanvas-min-menu', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-offcanvas-menu.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-offcanvas-slide-menu', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-offcanvas-slide.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-standard-menu', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-standard-menu.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-standard-round', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-standard-round.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-standard-5-menu', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-standard-5-menu.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-standard-offcanvas', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-standard-offcanvas.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-mobile-menu', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-mobile-menu.css', false, ELEMENT_READY_VERSION);
        wp_register_style('er-menu-off-canvas', ELEMENT_READY_MEGA_MENU_MODULE_URL . 'assets/css/er-menu-off-canvas.css', false, ELEMENT_READY_VERSION);

        if (isset($_REQUEST['elementor-preview'])) {

            return [
                'er-round-menu',
                'er-offcanvas-min-menu',
                'er-offcanvas-slide-menu',
                'er-standard-menu',
                'er-standard-round',
                'er-standard-5-menu',
                'er-standard-offcanvas',
                'er-mobile-menu',
                'er-menu-off-canvas',
                'stellarnav'
            ];
        }

        return [];
    }
    public function get_script_depends()
    {

        if (isset($_REQUEST['elementor-preview'])) {
            return [
                'element-ready-menu-frontend-script'
            ];
        }
        return [];
    }

    public function layout()
    {

        return [

            'style1' => esc_html__('Offcanvas', 'element-ready-lite'),
            'style2' => esc_html__('Offcanvas slide', 'element-ready-lite'),
            'style3' => esc_html__('Standard', 'element-ready-lite'),
            'style4' => esc_html__('Standard Round', 'element-ready-lite'),
            'style5' => esc_html__('Standard 5', 'element-ready-lite'),

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
                'label' => esc_html__('Layout', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_style',
            [
                'label' => esc_html__('Style', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => $this->layout()
            ]
        );

        $this->end_controls_section();

        $this->box_css(['selector' => '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav, {{ WRAPPER }} .stellarnav.mobile.right > ul', 'slug' => 'mobile_menu_container', 'title' => 'Mobile Menu Container']);

        $this->start_controls_section(
            'header_logo_section',
            [
                'label' => esc_html__('Header logo', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style1', 'style2', 'style3', 'style4', 'style6', 'style7']
                ],
            ]
        );
        $this->add_control(
            'header_logo_enable',
            [
                'label' => esc_html__('Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'header_logo_type',
            [
                'label' => esc_html__('Logo Type', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'logo',
                'options' => [
                    'logo' => esc_html__('Image Logo', 'element-ready-lite'),
                    'text' => esc_html__('Text Logo', 'element-ready-lite'),
                    'svg' => esc_html__('SVG Logo', 'element-ready-lite'),
                ]
            ]
        );

        $this->add_control(
            'header_logo',
            [
                'label' => esc_html__('Choose logo', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => ELEMENT_READY_ROOT_IMG . '/logo.png',
                ],
                'condition' => [
                    'header_logo_type' => ['logo']
                ],
            ]
        );

        $this->add_control(
            'header_svg_logo',
            [
                'label' => esc_html__('Svg logo', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'header_logo_type' => ['svg']
                ],
            ]
        );

        $this->add_control(
            'header_text_logo',
            [
                'label' => esc_html__('Text Logo', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Logo', 'element-ready-lite'),
                'placeholder' => esc_html__('Type your title here', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'header_website_link',
            [
                'label' => esc_html__('Link', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-ready-lite'),
                'default' => [
                    'url' => home_url('/'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Settings', 'element-ready-lite'),
            ]
        );

        $this->start_controls_tabs(
            'menu_type_tabs'
        );

        $this->start_controls_tab(
            'style_main_menu_tab',
            [
                'label' => esc_html__('Main Menu', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'enable_mega_menu_content',
            [
                'label' => esc_html__('Mega Menu Content', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [

                    'menu_style!' => ['style2']
                ]
            ]
        );

        $this->add_control(
            'enable_meta_content',
            [
                'label' => esc_html__('Meta Content', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'enable_mega_menu_content' => [''],
                    'menu_style' => ['style1']
                ]
            ]
        );


        $this->add_control(
            'menu_selected',
            [
                'label' => esc_html__('Menu', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->menu_list()
            ]
        );


        $this->add_control(
            'menu_depth',
            [
                'label' => esc_html__('Nested Depth', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'before_menu_drop_icon',
            [
                'label' => esc_html__('Before Item Icons', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICON,

            ]
        );
        $this->add_control(
            'menu_drop_icon',
            [
                'label' => esc_html__('After Item Icons', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-angle-down',
            ]
        );

        $this->add_control(
            'submenu_indecator_icon',
            [
                'label' => esc_html__('Submenu Icons', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-angle-right',
            ]
        );

        $this->add_control(
            'custom_continer_element_popover-toggle',
            [
                'label' => esc_html__('Extra option', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'wrapper_tag_type',
            [
                'label' => esc_html__('Container Tag', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'description' => esc_html__('Menu Main Wrapper container Html tag', 'element-ready-lite'),
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    'div' => esc_html__('div', 'element-ready-lite'),
                    'p' => esc_html__('p', 'element-ready-lite'),
                    'span' => esc_html__('span', 'element-ready-lite'),
                    'i' => esc_html__('i', 'element-ready-lite'),
                    's' => esc_html__('s', 'element-ready-lite'),
                    'b' => esc_html__('b', 'element-ready-lite'),
                    'p' => esc_html__('P', 'element-ready-lite'),
                    'ul' => esc_html__('ul', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'anchore_wrapper_tag_before_type',
            [
                'label' => esc_html__('Link Wrapper Before', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'description' => esc_html__('Menu Link Html tag', 'element-ready-lite'),
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '<div>' => esc_html__('div', 'element-ready-lite'),
                    '<p>' => esc_html__('p', 'element-ready-lite'),
                    '<span>' => esc_html__('span', 'element-ready-lite'),
                    '<i>' => esc_html__('i', 'element-ready-lite'),
                    '<s>' => esc_html__('s', 'element-ready-lite'),
                    '<b>' => esc_html__('b', 'element-ready-lite'),
                    '<p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'anchore_wrapper_tag_after_type',
            [
                'label' => esc_html__('Link Wrapper After', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '</div>' => esc_html__('div', 'element-ready-lite'),
                    '</p>' => esc_html__('p', 'element-ready-lite'),
                    '</span>' => esc_html__('span', 'element-ready-lite'),
                    '</i>' => esc_html__('i', 'element-ready-lite'),
                    '/<s>' => esc_html__('s', 'element-ready-lite'),
                    '</b>' => esc_html__('b', 'element-ready-lite'),
                    '</p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'anchore_text_before_tag_type',
            [
                'label' => esc_html__(' Link Text before', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '<div>' => esc_html__('div', 'element-ready-lite'),
                    '<p>' => esc_html__('p', 'element-ready-lite'),
                    '<span>' => esc_html__('span', 'element-ready-lite'),
                    '<i>' => esc_html__('i', 'element-ready-lite'),
                    '<s>' => esc_html__('s', 'element-ready-lite'),
                    '<b>' => esc_html__('b', 'element-ready-lite'),
                    '<p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'anchore_text_after_tag_type',
            [
                'label' => esc_html__('Link Text after', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '</div>' => esc_html__('div', 'element-ready-lite'),
                    '</p>' => esc_html__('p', 'element-ready-lite'),
                    '</span>' => esc_html__('span', 'element-ready-lite'),
                    '</i>' => esc_html__('i', 'element-ready-lite'),
                    '</s>' => esc_html__('s', 'element-ready-lite'),
                    '</b>' => esc_html__('b', 'element-ready-lite'),
                    '</p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->end_popover();

        $this->add_control(
            'custom_element_popover-toggle',
            [
                'label' => esc_html__('Custom Element Attribute', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'menu_container_custom_class',
            [
                'label' => esc_html__('Container Class', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('.custom-container custom-size', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_container_custom_id',
            [
                'label' => esc_html__('Container Id', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('custom-id', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_custom_class',
            [
                'label' => esc_html__('Menu Class', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('.menu-custom .type', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_custom_id',
            [
                'label' => esc_html__('Menu Id', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('custom-id', 'element-ready-lite'),
            ]
        );

        $this->end_popover();

        $this->add_responsive_control(
            'main_menu_titlesdsd_align',
            [
                'label' => esc_html__('Alignment', 'element-ready-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [

                    'margin-left:auto!important' => [

                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'fa fa-align-right',

                    ],
                    'margin:auto!important' => [

                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'fa fa-align-center',

                    ],
                    'margin-right:auto!important' => [

                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'fa fa-align-left',

                    ],

                    'margin:auto!important' => [

                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon' => 'fa fa-align-justify',

                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready--menu--list' => '{{VALUE}};',
                ],
            ]
        ); //Responsive control end


        $this->end_controls_tab();


        $this->start_controls_tab(
            'offcanvas_style_main_menu_tab',
            [
                'label' => esc_html__('OffCanvas', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style7']
                ]
            ]
        );

        $this->add_control(
            'offcanvas_enable',
            [
                'label' => esc_html__('Offcanvas Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
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
            'offcanvas_template_id',
            [
                'label' => esc_html__('Select Content Template', 'appscred-essential'),
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
            ]
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'style_mobile_menu_tab',
            [
                'label' => esc_html__('Mobile Menu', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style2', 'style3', 'style4', 'style6', 'style7']
                ],
            ]
        );



        $this->add_control(
            'mobile_menu_selected',
            [
                'label' => esc_html__('Menu', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->menu_list()
            ]
        );



        $this->add_control(
            'mobile_menu_depth',
            [
                'label' => esc_html__('Nested Depth', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );
        $this->add_control(
            'mobile_custom_element_main_popover-toggle',
            [
                'label' => esc_html__('Extra option', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'mobile_wrapper_tag_type',
            [
                'label' => esc_html__('Container Tag', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    'div' => esc_html__('div', 'element-ready-lite'),
                    'p' => esc_html__('p', 'element-ready-lite'),
                    'span' => esc_html__('span', 'element-ready-lite'),
                    'i' => esc_html__('i', 'element-ready-lite'),
                    's' => esc_html__('s', 'element-ready-lite'),
                    'b' => esc_html__('b', 'element-ready-lite'),
                    'p' => esc_html__('P', 'element-ready-lite'),
                    'ul' => esc_html__('ul', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'mobile_anchore_wrapper_tag_before_type',
            [
                'label' => esc_html__('Link Wrapper Before', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '<div>' => esc_html__('div', 'element-ready-lite'),
                    '<p>' => esc_html__('p', 'element-ready-lite'),
                    '<span>' => esc_html__('span', 'element-ready-lite'),
                    '<i>' => esc_html__('i', 'element-ready-lite'),
                    '<s>' => esc_html__('s', 'element-ready-lite'),
                    '<b>' => esc_html__('b', 'element-ready-lite'),
                    '<p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'mobile_anchore_wrapper_tag_after_type',
            [
                'label' => esc_html__('Link Wrapper After', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '</div>' => esc_html__('div', 'element-ready-lite'),
                    '</p>' => esc_html__('p', 'element-ready-lite'),
                    '</span>' => esc_html__('span', 'element-ready-lite'),
                    '</i>' => esc_html__('i', 'element-ready-lite'),
                    '/<s>' => esc_html__('s', 'element-ready-lite'),
                    '</b>' => esc_html__('b', 'element-ready-lite'),
                    '</p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'mobile_anchore_text_before_tag_type',
            [
                'label' => esc_html__(' Link Text before', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '<div>' => esc_html__('div', 'element-ready-lite'),
                    '<p>' => esc_html__('p', 'element-ready-lite'),
                    '<span>' => esc_html__('span', 'element-ready-lite'),
                    '<i>' => esc_html__('i', 'element-ready-lite'),
                    '<s>' => esc_html__('s', 'element-ready-lite'),
                    '<b>' => esc_html__('b', 'element-ready-lite'),
                    '<p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'mobile_anchore_text_after_tag_type',
            [
                'label' => esc_html__('Link Text after', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    '</div>' => esc_html__('div', 'element-ready-lite'),
                    '</p>' => esc_html__('p', 'element-ready-lite'),
                    '</span>' => esc_html__('span', 'element-ready-lite'),
                    '</i>' => esc_html__('i', 'element-ready-lite'),
                    '</s>' => esc_html__('s', 'element-ready-lite'),
                    '</b>' => esc_html__('b', 'element-ready-lite'),
                    '</p>' => esc_html__('P', 'element-ready-lite'),
                ],
            ]
        );

        $this->end_popover();

        $this->add_control(
            'mobile_custom_element_popover-toggle',
            [
                'label' => esc_html__('Extra Element', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'mobile_menu_container_custom_class',
            [
                'label' => esc_html__('Container Class', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('.prefix-custom-container', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'mobile_menu_container_custom_id',
            [
                'label' => esc_html__('Container Id', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('custom-id', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'mobile_menu_custom_class',
            [
                'label' => esc_html__('Menu Class', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('.custom-menu-cls .type', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'mobile_menu_custom_id',
            [
                'label' => esc_html__('Menu Id', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('custom-id', 'element-ready-lite'),
            ]
        );

        $this->end_popover();

        $this->add_responsive_control(
            'mobile_menu_align',
            [
                'label' => esc_html__('Alignment', 'element-ready-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [

                    'left' => [

                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'fa fa-align-left',

                    ],
                    'center' => [

                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'fa fa-align-center',

                    ],
                    'right' => [

                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'fa fa-align-right',

                    ],

                    'justify' => [

                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon' => 'fa fa-align-justify',

                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} ul li' => 'text-align: {{VALUE}};',
                ],
            ]
        ); //Responsive control end


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'header_button_section',
            [
                'label' => esc_html__('Header button', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style3', 'style4']
                ],
            ]
        );

        $this->add_control(
            'header_button_enable',
            [
                'label' => esc_html__('Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'header_button_text',
            [

                'label' => esc_html__('Text', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Get Strted', 'element-ready-lite'),
                'default' => esc_html__('Get Started', 'element-ready-lite')

            ]
        );

        $this->add_control(
            'header_button_link',
            [
                'label' => esc_html__('Link', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-ready-lite'),
                'default' => [
                    'url' => home_url('/'),
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'header_search_section',
            [
                'label' => esc_html__('Header Search', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style4']
                ],
            ]
        );



        $this->add_control(
            'header_search_enable',
            [
                'label' => esc_html__('Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'custom_search_templte',
            [
                'label' => esc_html__('Custom _template', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'popup_search_template_id',
            [
                'label' => esc_html__('Select Content Template', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => element_ready_elementor_template(),
                'condition' => [
                    'custom_search_templte' => ['yes']
                ],
                'description' => esc_html__('Please select elementor templete from here, if not create elementor template from menu', 'element-ready-lite')

            ]
        );

        $this->add_control(
            'header_search_text',
            [

                'label' => esc_html__('Text', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Search', 'element-ready-lite'),
                'default' => esc_html__('Search', 'element-ready-lite')

            ]
        );

        $this->add_control(
            'header_search_icon',
            [
                'label' => esc_html__('Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICONS,

            ]
        );

        $this->add_control(
            'header_search_logo',
            [
                'label' => esc_html__('Choose logo', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => ELEMENT_READY_ROOT_IMG . '/logo.png',
                ],

            ]
        );
        $this->end_controls_section();

        $this->box_css(
            array(
                'title' => esc_html__('Mega Menu', 'element-ready-lite'),
                'slug' => '_mega_menu_box_pos_style',
                'element_name' => 'mega_menu_box_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-megamenu-section',
                'condition' => [
                    'enable_mega_menu_content' => ['yes'],
                    'menu_style!' => ['style2', 'style1']
                ],
            )
        );

        $this->start_controls_section(
            'Bmain_section',
            [
                'label' => esc_html__('Main Section', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'main_section_container_disable',
            [
                'label' => esc_html__('Disable Container', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-ready-lite'),
                'label_off' => esc_html__('No', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'main_container_fluid_enable',
            [
                'label' => esc_html__('Container Fluid', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-ready-lite'),
                'label_off' => esc_html__('No', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'main_section_container_disable' => ['yes']
                ],
            ]
        );

        $this->add_responsive_control(
            'er__header_nav_main_container',
            [
                'label' => esc_html__('Container Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .container' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .container-fluid' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'main_section_container_disable' => ['yes']
                ],
            ]
        );

        $this->add_responsive_control(
            'main_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .main-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],


                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'main_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .main-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'main_section_background',
                'label' => esc_html__('Background', 'element-ready-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .main-section',

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'er_main_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .main-section',

            ]
        );

        $this->add_control(
            'er_main_section_popover_container_position',
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
            'er_main_section__container_t_position_type',
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
                    '{{WRAPPER}} .main-section' => 'position: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'er_main_section_container_r_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'er_main_section_conainer_r_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'er_main_box_popover_section_sizen',
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
            'er_main_section__width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'er_main_section_container_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();


        $this->end_controls_section();

        $this->start_controls_section(
            'Bma_incontainer_section',
            [
                'label' => esc_html__('Menu Container Section', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'menu_style' => ['style4', 'style5', 'style6', 'style7']
                ],
            ]
        );

        $this->add_responsive_control(
            'main_container_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .main-section .navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'main_ontainer_section_ssmargin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .main-section .navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mmain_ontainer_sectioni__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'main_header_section_background',
                'label' => esc_html__('Background', 'element-ready-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .main-section .navigation',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'main_container_header_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .main-section .navigation',
            ]
        );

        $this->add_responsive_control(
            'header_container_nav_position_type',
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
                    '{{WRAPPER}} .main-section .navigation' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_container_nav_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_conainer_nav_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_container_nav_position_bottom',
            [
                'label' => esc_html__('Position bottom', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_container_nav_position_right',
            [
                'label' => esc_html__('Position right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'main_container_section_offcanavs_nav_sec',
            [
                'label' => esc_html__('Offcanvas', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'menu_style' => ['style1']
                ],
            ]
        );

        $this->add_control(
            'style1_offcanvas_float',
            [
                'label' => esc_html__('Align', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'description' => esc_html__('Float left right', 'element-ready-lite'),
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    'right' => esc_html__('Right', 'element-ready-lite'),
                    'left' => esc_html__('Left', 'element-ready-lite'),

                ],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-fs-menu-wrapper' => 'float: {{VALUE}} !important;',
                ],

            ]
        );

        $this->add_control(
            'er_main_offcanvas_popover_section_sizen',
            [
                'label' => esc_html__('PopUp Box Size', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'er_main_style1_offcanavs_section__width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu .fsmenu--container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'er_main_style1_offcanvas_section_container_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu .fsmenu--container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();

        $this->add_responsive_control(
            'main_container_section_offcanavs_nav_bar_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .fsmenu .fsmenu--container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .fsmenu .fsmenu--container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'main_nav_bar_offcanvas_header_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu',
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
                    '{{WRAPPER}} .fsmenu' => 'position: {{VALUE}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu' => 'left: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu' => 'top: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu' => 'bottom: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'er_main_offcanvas_inner_popover_section_sizen',
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
            'er_main_style1_inner_offcanavs_section__width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'er_main_style1_inner_offcanvas_section_container_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();



        $this->end_controls_section();
        $this->text_css(
            array(
                'title' => esc_html__('Mobile menu Item Icon', 'element-ready-lite'),
                'slug' => 'mobile_menu_yem_text_style',
                'element_name' => '_mobile_menu_icon_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-navbar > li > a i',
                'hover_selector' => '{{WRAPPER}} .element-ready-navbar > li > a:hover i',
                'condition' => [
                    'menu_style' => ['style2']
                ],
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Mobile menu close text', 'element-ready-lite'),
                'slug' => 'mobile_menu_close_yem_text_style',
                'element_name' => '_mobile_menu_close_icon_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-navbar .close-menu',
                'hover_selector' => false,
                'condition' => [
                    'menu_style' => ['style2']
                ],
            )
        );

        $this->text_css(
            array(
                'title' => esc_html__('Mobile menu close icon', 'element-ready-lite'),
                'slug' => 'mobile_menu_close__icons_text_style',
                'element_name' => '_mobile_menu_close__icon_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-style2 .stellarnav.light .icon-close:after,{{WRAPPER}} .stellarnav.light .icon-close:before',
                'hover_selector' => false,
                'condition' => [
                    'menu_style' => ['style2']
                ],
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Mobile Menu Item list', 'element-ready-lite'),
                'slug' => 'mobile_menu_item_li_style',
                'element_name' => 'mobile_menu_item_li_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-style2 .element-ready-navbar > li',
                'condition' => [
                    'menu_style' => ['style2']
                ],

            )
        );
        $this->text_css(
            array(
                'title' => esc_html__('Mobile Menu Item text', 'element-ready-lite'),
                'slug' => 'mobile_menu_item_li_tex_style',
                'element_name' => 'mobile_menu_item_li_text_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-style2 .element-ready-navbar > li a',
                'condition' => [
                    'menu_style' => ['style2']
                ],

            )
        );
        $this->start_controls_section(
            'menu_search_popup_icons_section',
            [
                'label' => esc_html__('Search', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'menu_style' => ['style7']
                ],
            ]
        );

        $this->add_control(
            'menu_search_popup_icon_popover-toggle',
            [
                'label' => esc_html__('Icon option', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );
        $this->start_popover();

        $this->add_control(
            '__search_menu_icon_color_',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-open i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-search-open span' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            '__search_menu_icon_hovern_color_',
            [

                'label' => esc_html__('Hover Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-search-open:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-search-open:hover span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '__search_menu_i_icon_bgcolor_',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-open' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            '__search_menu_i_hover_icon_bgcolor_',
            [

                'label' => esc_html__('Hover Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-open:hover' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            '__search_menu_icon__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-search-open' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => '__search_menu_icon__section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-search-open',
            ]
        );



        $this->add_responsive_control(
            '__search_menu_iccon_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-search-open' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            '__search_menu_icon_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-open i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '__search_menu_contentpo_i_icon_li_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-search-open i,{{WRAPPER}} .element-ready-search-open span',

            ]
        );


        $this->end_popover();

        $this->add_control(
            '__search_menu_container_po_popover-toggle',
            [
                'label' => esc_html__('Container Box', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            '__search_menu_container_po_bgcolor_',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-box' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => '__search_menu_container_po_n_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-search-box',
            ]
        );

        $this->start_popover();

        $this->end_popover();

        $this->add_control(
            '__search_menu_close_icon_bgcolor_',
            [

                'label' => esc_html__('Close Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-box .search-header .search-close button span' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-search-box .search-header .search-close button' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            '__search_box_menu_iqwert_icon_bgcolor_',
            [

                'label' => esc_html__('Search Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-box .search-form i' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            '__search_box_menu_iqwert_icon_bgcolor_font_size',
            [
                'label' => esc_html__('Icon Font Size', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'REM', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-search-box .search-form i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '__search_box_menu_iqwert_input_color_',
            [

                'label' => esc_html__('Input Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-box .search-body .search-form input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-search-box .search-body .search-form input::placeholder' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '__search_menu_conteo_i_input_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-search-box .search-body .search-form input,{{WRAPPER}} .element-ready-search-box .search-body .search-form input::placeholder',

            ]
        );

        $this->add_control(
            '__search_box_menu_iqwert_input_bgcolor_',
            [

                'label' => esc_html__('Input Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-search-box .search-body .search-form input' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'menu_search_popup_i_popover_content-toggle',
            [
                'label' => esc_html__('Advanced', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();



        $this->add_responsive_control(
            '__search_box_menu_iqwert_input__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-search-box .search-body .search-form input' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => '__search_box_menu_iqwert_input_section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-search-box .search-body .search-form input',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_ite__search_box_iqwert_inputbox_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-search-box .search-body .search-form input',
            ]
        );




        $this->end_popover();




        $this->end_controls_section();
        $this->start_controls_section(
            'menu_item_humberger_mobile_lis_section',
            [
                'label' => esc_html__('Mobile Hamburger', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'style2_reverse_menu',
            [
                'label' => esc_html__('Menu Right', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-ready-lite'),
                'label_off' => esc_html__('No', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'menu_style' => ['style2']
                ],
            ]
        );

        $this->add_responsive_control(
            'main_menu_container_align',
            [
                'label' => esc_html__('Alignment', 'element-ready-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [

                    'flex-start' => [

                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'fa fa-align-left',

                    ],
                    'center' => [

                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'fa fa-align-center',

                    ],
                    'flex-end' => [

                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'fa fa-align-right',

                    ],

                    'justify' => [

                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon' => 'fa fa-align-justify',

                    ],
                ],

                'condition' => [
                    'menu_style!' => ['style4', 'style5', 'style6']
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-menu.main-menu-style-2' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .main-section .navigation .navbar' => 'justify-content: {{VALUE}};',
                ],
            ]
        ); //Responsive control end




        $this->add_control(
            '__mobile_humberger_icon_color_',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-toggler .toggler-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-hamburger i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-hamburger span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars svg path' => 'fill: {{VALUE}};',


                ],
            ]
        );

        $this->add_control(
            '__mobile_humberger_hover_icon_color_',
            [

                'label' => esc_html__('Hover Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler .toggler-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-hamburger:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-hamburger:hover span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '__mobile_humberger_ho_icon_bgcolor_',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-hamburger' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '__mobile_humberger_hover_icon_bgcolor_',
            [

                'label' => esc_html__('Hover Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-hamburger:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '__mobile_humberger__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .hamburger .hamburger--container .hamburger--bars' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .h4-menu-bar .h4-menu-show' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => '__mobile_humbergeri__section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler,{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle, {{WRAPPER}} .hamburger .hamburger--container .hamburger--bars,{{WRAPPER}} .h4-menu-bar .h4-menu-show',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '__mobile_humberger_icon_li_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler,{{WRAPPER}} .element-ready-hamburger i,{{WRAPPER}} .h4-menu-bar .h4-menu-show,{{WRAPPER}} .main-menu-style-2.stellarnav.light.right .menu-toggle',
                'condition' => [
                    'menu_style!' => ['style3']
                ],
            ]
        );

        $this->add_responsive_control(
            '__mobile_humberger_icon_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .h4-menu-bar .h4-menu-show' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            '__mobile_humberger_icon_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
                'condition' => [
                    'menu_style' => ['style3', 'style4']
                ],
            ]
        );


        $this->add_control(
            'hamberger_style4_popover_dsdpoistion_sizen',
            [
                'label' => esc_html__('Position', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
                'condition' => [
                    'menu_style' => ['style4']
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_menu_dsdssdscontainer_nav_position_type',
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
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_menu_dsd_container_nav_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_menu_dsds_container_nav_position_right',
            [
                'label' => esc_html__('Position right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_menu_sdsd_conainer_nav_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();



        $this->start_popover();



        $this->add_control(
            'hamberger_popover_number_sizen',
            [
                'label' => esc_html__('Box Size', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
                'condition' => [
                    'menu_style' => ['style1', 'style3']
                ],
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'header_hamberger_container_number_width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .hamburger' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_humberger_conainer_number_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .hamburger' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-toggler' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'menu_style' => ['style3']
                ],
            ]
        );

        $this->add_responsive_control(
            'header_hamberger_container_riner_icon_width',
            [
                'label' => esc_html__('Icon Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [

                    '{{WRAPPER}} .main-section .navigation .navbar .navbar-toggler span' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'menu_style' => ['style3']
                ],
            ]
        );

        $this->add_responsive_control(
            'header_humberger_conainer_innericnon_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [

                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-toggler span' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'menu_style' => ['style3']
                ],
            ]
        );


        $this->end_popover();


        $this->end_controls_section();

        $this->start_controls_section(
            'menu_item_list_section',
            [
                'label' => esc_html__('Nav Item', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'menu_item_li_after_fld_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .navigation .navbar .navbar-nav .nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_lfl_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',



                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'd_menu_item_fld_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li' => 'background: {{VALUE}};',


                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'menu_item_lis_section',
            [
                'label' => esc_html__('Nav text', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );


        $this->start_controls_tabs(
            'menu_drop_down_sec_type_tabs'
        );

        $this->start_controls_tab(
            'menu_drop_down_sec_beforemenu_tab',
            [
                'label' => esc_html__('Before icon', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_item_li_dropdown_before_icon_heading',
            [
                'label' => esc_html__('Indicator Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'menu_item_li_dropdown_before_icon_color',
            [

                'label' => esc_html__('icon Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-d-icon-before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li a::before' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_item_li_before_dropdown_typho',
                'label' => esc_html__('Font', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-before ,{{WRAPPER}} .element-ready-navbar > li a::before,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a i.element-ready-d-icon-before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_dropdown_before_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li a::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a i.element-ready-d-icon-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_before_dropdown_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li a::before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a i.element-ready-d-icon-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'menu_drop_down_sec_menu_tab',
            [
                'label' => esc_html__('After icon', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_item_li_dropdown_after_icon_heading',
            [
                'label' => esc_html__('Indicator Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'menu_item_li_dropdown_after_icon_color',
            [

                'label' => esc_html__('Dropdown icon Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li a::after' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_item_li_after_dropdown_typho',
                'label' => esc_html__('Dropdown Arrow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-after ,{{WRAPPER}} .element-ready-navbar > li a::after,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a i.element-ready-d-icon-after',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_after_dropdown_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li a::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a i.element-ready-d-icon-after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_after_dropdown_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar > li i.element-ready-d-icon-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a i.element-ready-d-icon-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'menu_item_li_dropdown_item_heading',
            [
                'label' => esc_html__('Item text', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'menu_items_tabs'
        );

        $this->start_controls_tab(
            'style_main_menu_animated_item_tab',
            [
                'label' => esc_html__('Amimated', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style1']
                ],
            ]
        );

        $this->add_control(
            'animated_menu_item_li_color',
            [

                'label' => esc_html__('Animate Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'menu_style' => ['style1']
                ],
                'selectors' => [

                    '{{WRAPPER}} .fsmenu--list-element .fsmenu--scrolling-text span' => 'color: {{VALUE}} !important;',


                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'animated_menu_item_li_typho',
                'label' => esc_html__('Text Typography', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style1']
                ],
                'selector' => '{{WRAPPER}} .fsmenu--list-element .fsmenu--scrolling-text span',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'animated_menu_item_li_text_shadow',
                'label' => esc_html__('Text Shadow', 'element-ready-lite'),
                'condition' => [
                    'menu_style' => ['style1']
                ],
                'selector' => '{{WRAPPER}} .fsmenu--list-element .fsmenu--scrolling-text span',
            ]
        );

        $this->add_control(
            'animated_menu_item_li_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'menu_style' => ['style1']
                ],
                'selectors' => [
                    '{{WRAPPER}} .fsmenu--list-element .fsmenu--scrolling-text span' => 'background: {{VALUE}};',


                ],
            ]
        );
        $this->add_responsive_control(
            'animated_menu_item_li_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .fsmenu--list-element .fsmenu--scrolling-text span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
                'condition' => [
                    'menu_style' => ['style1']
                ],
            ]
        );

        $this->add_responsive_control(
            'animated_menu_item_li_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'menu_style' => ['style1']
                ],
                'selectors' => [
                    '{{WRAPPER}}  .fsmenu--list-element .fsmenu--scrolling-text span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_main_menu_item_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            '_custom_continer_element_popover-toggle',
            [
                'label' => esc_html__('Menu Pointer', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
        $this->add_control(
            'menu_item_li_menu_pointer',
            [
                'label' => esc_html__('Menu Pointer', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [

                    'element-ready-underline' => esc_html__('Underline', 'element-ready-lite'),
                    'element-ready-doubleline' => esc_html__('Doubleline', 'element-ready-lite'),
                    'element-ready-background' => esc_html__('Background', 'element-ready-lite'),
                    '' => esc_html__('None', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'menu_item_li_menu_hover_pointer',
            [
                'label' => esc_html__('Hover Pointer Effect', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [

                    'grow-hover' => esc_html__('Grow', 'element-ready-lite'),
                    'fade-hover' => esc_html__('Fade', 'element-ready-lite'),
                    'slide-hover' => esc_html__('Slide', 'element-ready-lite'),
                    '' => esc_html__('None', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            '_menu_item_li_popup_iuty_position',
            [
                'label' => esc_html__('Advanced', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
                'condition' => [
                    'menu_item_li_menu_pointer!' => ''
                ],
            ]
        );

        $this->start_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_hover_animqwer_li_background',
                'label' => esc_html__('Background', 'element-ready-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element-ready-navbar .element-ready-underline > a::before',

            ]
        );

        $this->add_responsive_control(
            'menu_item_hover_aminoiur_height',
            [
                'label' => esc_html__('Height', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                    '{{WRAPPER}} .element-ready-navbar .element-ready-underline > a::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_hover_amin_position_bottom',
            [
                'label' => esc_html__('Position Bottom', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar .element-ready-underline > a::before' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_hover_amin_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar .element-ready-underline > a::before' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_hover_amin_position_top',
            [
                'label' => esc_html__('Position top', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar .element-ready-underline > a::before' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();
        $this->end_popover();

        $this->add_control(
            'menu_item_li_color',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .fsmenu--text-container .fsmenu--list .fsmenu--list-element > a span' => 'color: {{VALUE}};',

                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li > a' => 'color: {{VALUE}}',


                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_item_li_typho',
                'label' => esc_html__('Text Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li > a, {{WRAPPER}} .element-ready-navbar > li,{{WRAPPER}} .element-ready-navbar > li > a,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a > span',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'menu_item_lisdsd_text_shadow',
                'label' => esc_html__('Text Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li > a,{{WRAPPER}} .element-ready-navbar > li > a,{{WRAPPER}} .element-ready-navbar > li > a,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element a > span',
            ]
        );

        $this->add_control(
            'menu_item_li_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu--text-container .fsmenu--list > .fsmenu--list-element' => 'background: {{VALUE}};',


                ],
                'condition' => [
                    'menu_style!' => ['style5']
                ],
            ]
        );

        $this->add_control(
            'menu_item_li_style5_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li > a' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'menu_style' => ['style5']
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_itemasdasd_li_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu--text-container .fsmenu--list > .fsmenu--list-element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_li__section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item > a,{{WRAPPER}} .element-ready-navbar > li > a,{{WRAPPER}} .fsmenu--text-container .fsmenu--list > .fsmenu--list-element',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_li__section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-navbar > li > a,{{WRAPPER}} .fsmenu--text-container .fsmenu--list > .fsmenu--list-element > a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_menu_item_tab',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_item_li_hover_color',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li:hover a > span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover a > span' => 'color: {{VALUE}};',


                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'menu_item_li_hover_text_shadow',
                'label' => esc_html__('Text Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover > a ,{{WRAPPER}} .element-ready-navbar > li:hover > a,{{WRAPPER}} .element-ready-navbar > li:hover a > span ,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover a > span ',
            ]
        );

        $this->add_control(
            'menu_item_li_hover_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar > li:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover' => 'background: {{VALUE}};',

                ],
                'condition' => [
                    'menu_style!' => ['style5']
                ],
            ]
        );

        $this->add_control(
            'menu_item_li_hover_style5_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover > a' => 'background: {{VALUE}};',


                ],
                'condition' => [
                    'menu_style' => ['style5']
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_item_li_hover_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover > a,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover a > span , {{WRAPPER}} .element-ready-navbar > li:hover a,{{WRAPPER}} .element-ready-navbar > li:hover a > span',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_hover_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar > li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_hover_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav > li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar > li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_hover_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar > li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_li_hover_section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-navbar > li:hover,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_li_hover_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-navbar > li:hover,{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'menu_dropdown_drop_down_lis_section',
            [
                'label' => esc_html__('SubMenu Container', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    //'menu_style!' => ['style1']
                ],
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_dropdown_icon_heading',
            [
                'label' => esc_html__('Dropdown Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'menu_style!' => ['style1']
                ],
            ]
        );


        $this->add_control(
            'menu_dropdown_item_li_dropdown_icon_color',
            [

                'label' => esc_html__('Dropdown icon Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar li .sub-menu a::after' => 'color: {{VALUE}};',


                ],
                'condition' => [
                    'menu_style!' => ['style1']
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'condition' => [
                    'menu_style!' => ['style1']
                ],
                'name' => 'menu_dropdown_item_li_dropdown_typho',
                'label' => esc_html__('Dropdown Arrow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-navbar li .sub-menu i ,{{WRAPPER}} .element-ready-navbar li .sub-menu a::after,{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu>li a i',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_dropdown_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
                'condition' => [
                    'menu_style!' => ['style1']
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_dropdown_margin',
            [
                'label' => esc_html__('Icon Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu i , .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu>li a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',


                ],
                'separator' => 'before',
                'condition' => [
                    'menu_style!' => ['style1']
                ],
            ]
        );



        $this->add_control(
            '_sub_menu_box_popover_section_sizen',
            [
                'label' => esc_html__('Container', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-ready-lite'),
                'label_on' => esc_html__('Custom', 'element-ready-lite'),
                'return_value' => 'yes',
                'condition' => [
                    'menu_style!' => ['style1']
                ],
            ]
        );

        $this->start_popover();

        $this->add_control(
            '_sub_menumain_sectiona_auto_width',
            [
                'label' => esc_html__('Auto Width', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'auto' => esc_html('Yes', 'element-ready-lite'),
                    '' => esc_html('No', 'element-ready-lite')
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sub-menu' => 'width: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu' => 'width: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'width: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_sub_menumain_section_min_width',
            [
                'label' => esc_html__('Min Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'er_sub_menu__dropdown_yui_heading',
            [
                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'er_sub_menu_main_section_background',
                'label' => esc_html__('Background', 'element-ready-lite'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu,{{WRAPPER}} .element-ready-sub,{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu',
            ]
        );

        $this->add_responsive_control(
            'er_sub_menu_uiy_main_section_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'er_sub_menu_uiy_main_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-sub-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'er_sub_menu_uiy_main_sectionewrt__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar li .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'element_ready_sub_menu_uiy_main_sectionewrts_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu,{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu,{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu',
            ]
        );


        $this->end_popover();

        $this->end_controls_section();

        $this->start_controls_section(
            'menu_dropdown_sub_item_pos_section',
            [
                'label' => esc_html__('Sub menu Position', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    //'menu_style!' => ['style1']
                ],
            ]
        );


        $this->add_responsive_control(
            'element_ready_sub_menu_uyi_section__container_t_position_type',
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
                    '{{WRAPPER}} .element-ready-sub-menu' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_ready_sub_menu_uyi_section_container_r_position_left',
            [
                'label' => esc_html__('Position Left', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2100,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sub-menu' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover ul.element-ready-sub-menu' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_ready_sub_menu_uyi_conainer_r_position_top',
            [
                'label' => esc_html__('Position Top', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2100,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .element-ready-sub-menu' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover ul.element-ready-sub-menu' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'element_ready_sub_menu_uyi_conainer_r_position_right',
            [
                'label' => esc_html__('Position Right', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2100,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .fsmenu--text-container .fsmenu--list .fsmenu--list-element:hover ul.element-ready-sub-menu' => 'right: {{SIZE}}{{UNIT}};',

                ],
                'condition' => [
                    'menu_style' => ['style1']
                ],
            ]
        );




        $this->end_controls_section();

        $this->start_controls_section(
            'menu_dropdown_sub_item_lis_style_section',
            [
                'label' => esc_html__('Submenu Item', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'menu_style' => ['style3']
                ],
            ]
        );


        $this->add_control(
            'menu_dropdown_item_li_dropdown_item_heading_styl3',
            [
                'label' => esc_html__('Item', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'sub_menu_items_tabs'
        );

        $this->start_controls_tab(
            'style_main_sub_menu_dropdown_item_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_color_styl3',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [


                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li a' => 'color: {{VALUE}};',


                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_dropdown_item_li_typho_styl3',
                'label' => esc_html__('Text Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li a',
            ]
        );


        $this->add_control(
            'menu_dropdown_item_li_ho_bgcolor_styl3',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_padding_styl3',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_padding_text_styl3',
            [
                'label' => esc_html__('Menu Text Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_section_margin_styl3',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );


        $this->add_responsive_control(
            'menu_dropdown_item_li__border_radius_styl3',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_dropdown_item_li__section_border_styl3',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li__section_box_shadow_styl3',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_menu_dropdown_item_tab_styl3',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_hover_colo_styl3r',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li a:hover' => 'color: {{VALUE}};',

                ],
            ]
        );


        $this->add_control(
            'menu_dropdown_item_li_hover_bgcolor_styl3',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li:hover' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_dropdown_item_hli_border_styl3',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element-ready-style-3.element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu > li:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();


        $this->end_controls_section();


        $this->start_controls_section(
            'menu_dropdown_sub_item_lis_section',
            [
                'label' => esc_html__('Submenu Item', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'menu_style!' => ['style3']
                ],
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_dropdown_item_heading',
            [
                'label' => esc_html__('Item text', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'sub_menu_items_tabs_style3'
        );

        $this->start_controls_tab(
            'style_main_sub_menu_dropdown_item_tab_2',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_color',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu a' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_dropdown_item_li_typho',
                'label' => esc_html__('Text Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li > a span,{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li a,{{WRAPPER}} .element-ready-navbar li .sub-menu li > a,{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li a',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li_text_shadow',
                'label' => esc_html__('Text Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu > a span, {{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li a,{{WRAPPER}} .element-ready-navbar li .sub-menu li > a,{{WRAPPER}} .element-ready-navbar li .sub-menu li,{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li a',
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_ho_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu > li' => 'background: {{VALUE}};',


                ],
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_text_section_margin',
            [
                'label' => esc_html__('Text Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_dropdown_item_li__section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li > a,{{WRAPPER}} .element-ready-navbar li .sub-menu li,{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li__section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu > li ,{{WRAPPER}} .element-ready-navbar li .sub-menu > li, {{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu > li',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_menu_dropdown_item_tab',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_hover_color',
            [

                'label' => esc_html__('Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .element-ready-navbar li .sub-menu li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar .sub-menu li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover > a span' => 'color: {{VALUE}};',

                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li_hover_text_shadow',
                'label' => esc_html__('Text Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover > a span,{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover a, {{WRAPPER}} .element-ready-navbar .sub-menu li:hover a, {{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover a',
            ]
        );

        $this->add_control(
            'menu_dropdown_item_li_hover_bgcolor',
            [

                'label' => esc_html__('Background', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [

                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-navbar .sub-menu li:hover > a' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover' => 'background: {{VALUE}};',

                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_dropdown_item_hli_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover,{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover, {{WRAPPER}} .sub-menu li:hover, {{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_dropdown_item_li_hover_typho',
                'label' => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover a > span,{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover a,{{WRAPPER}} .element-ready-navbar .sub-menu li:hover > a,{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover a',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_hover_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover a > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar .sub-menu li:hover > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_hover_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [

                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar .sub-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-header-nav .navigation .navbar .navbar-nav .nav-item .sub-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_hover_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready-navbar .element-ready-sub-menu li:hover > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li_hover_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}}  .fsmenu .fsmenu--container .fsmenu--text-block .fsmenu--text-container .fsmenu--list .fsmenu--list-element .element-ready-sub-menu li:hover,{{WRAPPER}} .element-ready-navbar .sub-menu li:hover,{{WRAPPER}} .element-ready--menu--list li .element-ready-sub-menu li:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'mobile_menu_content_section',
            [
                'label' => esc_html__('Mobile Menu', 'element-ready-lite'),

            ]
        );

        $this->add_control(
            'enable_mobile_menu',
            [
                'label' => esc_html__('Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'menu_style' => ['style3', 'style5', 'style4', 'style6', 'style7']
                ]
            ]
        );

        $this->add_control(
            'mobile_menu_icon',
            [
                'label' => esc_html__('Hamburger Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICONS,

            ]
        );

        $this->add_control(
            'mobile_menu_breakpoint',
            [
                'label' => esc_html__('Menu Breakpoint', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'navbar-expand-lg',
                'description' => esc_html__('Mobile menu breakpoint', 'element-ready-lite'),
                'options' => [
                    '' => esc_html__('none', 'element-ready-lite'),
                    'navbar-expand-sm' => esc_html__('Small Device', 'element-ready-lite'),
                    'navbar-expand-md' => esc_html__('Medium', 'element-ready-lite'),
                    'navbar-expand-lg' => esc_html__('Large', 'element-ready-lite'),
                    'navbar-expand-xl' => esc_html__('Extra large', 'element-ready-lite'),
                ],
                'condition' => [
                    'menu_style' => ['style3', 'style4', 'style5', 'style6', 'style7']
                ]
            ]
        );

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
                    '{{WRAPPER}}  .element-ready-canvas-bar i' => 'color: {{VALUE}};',
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

                    '{{WRAPPER}}  .element-ready-canvas-bar:hover i' => 'color: {{VALUE}};',
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
                        'min' => -2500,
                        'max' => 2500,
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
                        'min' => -2500,
                        'max' => 2500,
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
                        'min' => -2500,
                        'max' => 2500,
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
                'condition' => [
                    'menu_style' => ['style8', 'style9', 'style7']
                ],
            ]
        );

        $this->add_control(
            '_offcanvas_overlay_popover____amm',
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
                    '{{WRAPPER}} .off_canvars_overlay' => 'background: {{VALUE}};',

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
                    '{{WRAPPER}} .off_canvars_overlay' => 'opacity: {{SIZE}};',

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
                    '{{WRAPPER}} .off_canvars_overlay' => 'z-index: {{SIZE}};',

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
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .off_canvars_overlay' => 'width: {{SIZE}}{{UNIT}};',
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
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .off_canvars_overlay' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .off_canvars_overlay' => 'position: {{VALUE}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .off_canvars_overlay' => 'left: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .off_canvars_overlay' => 'right: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .off_canvars_overlay' => 'top: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .offcanvas_menu_wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'offcanvas_overlay_content_section_box_shadow',
                'label' => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .offcanvas_menu_wrapper',
            ]
        );

        $this->add_responsive_control(
            'offcanvas_overlay_content__border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'offcanvas_overlay_content___section_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .offcanvas_menu_wrapper',
            ]
        );

        $this->add_responsive_control(
            'fcanvas_overlay_contentr_section_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'fcanvas_overlay_contentr_section_padding',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',


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
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_popover();

        $this->add_control(
            'offcanvas_overlay_content_style_popover_ddssd_poistion_sin',
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
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'position: {{VALUE}};',
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
                        'min' => -2500,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'left: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'right: {{SIZE}}{{UNIT}};',
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
                        'min' => -2500,
                        'max' => 2500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .offcanvas_menu_wrapper' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();
        $this->end_controls_section();
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

    protected function render()
    {

        $widget_id = 'element-ready-' . $this->get_id() . '-';
        $settings = $this->get_settings();
        $menu_id = $settings['menu_selected'];
        $mobile_menu_id = $settings['mobile_menu_selected'];
        $mobile_menu = [];

        if ($mobile_menu_id == '') {

            $mobile_menu = $this->_get_menu_array($menu_id, false);
        } else {
            $mobile_menu = $this->_get_menu_array($mobile_menu_id, false);
        }

        $nav_walker_default = [
            'mega_menu_content' => $settings['enable_mega_menu_content'],
            'enable_meta_content' => ''
        ];

        if ($settings['before_menu_drop_icon'] != '') {
            $nav_walker_default['before_menu_drop_icon'] = $settings['before_menu_drop_icon'];
        }

        if ($settings['menu_drop_icon'] != '') {
            $nav_walker_default['menu_drop_icon'] = $settings['menu_drop_icon'];
        }

        if ($settings['submenu_indecator_icon'] != '') {
            $nav_walker_default['submenu_indicator_icon'] = $settings['submenu_indecator_icon'];
        }

        $args = [
            'menu' => $menu_id,
            'container' => $settings['wrapper_tag_type'] != '' ? $settings['wrapper_tag_type'] : false,
            'container_id' => $settings['menu_container_custom_id'] != '' ? $settings['menu_container_custom_id'] : false,
            'container_class' => $settings['menu_container_custom_class'] != '' ? $settings['menu_container_custom_class'] : false,
            'menu_class' => $settings['menu_custom_class'] == '' ? ' ' : $settings['menu_custom_class'],
            'depth' => $settings['menu_depth'],
            'walker' => new Offcanvas_Nav_Walker($nav_walker_default),

        ];

        if ($settings['menu_custom_id'] != '') {
            $args['menu_id'] = $settings['menu_custom_id'];
        }

        $link_before_after = element_ready_menu_html_tag_validate($settings['anchore_text_before_tag_type'], $settings['anchore_text_after_tag_type']);

        if ($link_before_after) {
            $args['link_before'] = $link_before_after['start'];
            $args['link_after'] = $link_before_after['end'];
        }

        $before_after = element_ready_menu_html_tag_validate($settings['anchore_wrapper_tag_before_type'], $settings['anchore_wrapper_tag_after_type']);

        if ($before_after) {

            $args['before'] = $before_after['start'];
            $args['after'] = $before_after['end'];
        }

        $nav_walker_default['layout'] = $settings['menu_style'];
        $nav_walker_default['mega_menu_cls'] = 'mega-menu';
        $nav_walker_default['first_li_menu_pointer'] = $settings['menu_item_li_menu_pointer'];
        $nav_walker_default['first_li_menu_hover_pointer'] = $settings['menu_item_li_menu_hover_pointer'];

        ?>
        <!--====== Header START ======-->
        <?php if ($settings['menu_style'] == 'style1'): ?>
            <?php
            wp_enqueue_style('er-offcanvas-min-menu');
            wp_enqueue_script('element-ready-menu-frontend-script');
            $menu_class = 'fsmenu--list element-ready--menu--list element-ready-navbar';
            if ($settings['menu_custom_class'] != '') {
                $menu_class = $menu_class . $settings['menu_custom_class'];
            }
            $args['menu_class'] = $menu_class;
            ?>
            <?php $nav_walker_default['menu_list_item_cls'] = 'fsmenu--list-element'; ?>
            <?php $nav_walker_default['sub_menu_ul_cls'] = 'sub-menu element-ready-sub-menu'; ?>
            <?php $nav_walker_default['enable_meta_content'] = $settings['enable_meta_content']; ?>
            <?php $args['walker'] = new Offcanvas_Nav_Walker($nav_walker_default) ?>
            <?php include('layout/menu/style1.php'); ?>
        <?php endif; ?>
        <?php if ($settings['menu_style'] == 'style2'): ?>
            <?php

            wp_enqueue_style('er-offcanvas-slide-menu');
            wp_enqueue_style('stellarnav');
            wp_enqueue_script('element-ready-menu-frontend-script');
            wp_enqueue_script('stellarnav');

            $nav_walker_default['mega_menu_content'] = '';
            $nav_walker_default['menu_list_item_cls'] = 'nav-item ';
            $nav_walker_default['menu_list_item_cls'] = 'nav-item ' ?>

            <?php $nav_walker_default['sub_menu_ul_cls'] = 'sub-menu element-ready-sub-menu'; ?>
            <?php $args['menu_class'] = $settings['menu_custom_class'] == '' ? 'menu-item element-ready--menu--list element-ready-navbar ' : 'menu-item element-ready--menu--list element-ready-navbar ' . $settings['menu_custom_class']; ?>
            <?php $args['walker'] = new Offcanvas_Nav_Walker($nav_walker_default) ?>
            <?php include('layout/menu/style2.php'); ?>
        <?php endif; ?>
        <?php if ($settings['menu_style'] == 'style3'): ?>
            <?php
            //wp_enqueue_style('er-standard-menu');
            $nav_walker_default['menu_list_item_cls'] = 'nav-item ' . $settings['enable_mega_menu_content'] == 'yes' ? 'element-ready-mega-menu-item' : ''; ?>
            <?php $nav_walker_default['sub_menu_ul_cls'] = 'sub-menu element-ready-sub-menu'; ?>
            <?php $args['walker'] = new Offcanvas_Nav_Walker($nav_walker_default); ?>
            <?php $args['menu_class'] = $settings['menu_custom_class'] == '' ? 'navbar-nav element-ready--menu--list element-ready-navbar ' : 'navbar-nav element-ready--menu--list element-ready-navbar ' . $settings['menu_custom_class']; ?>
            <?php include('layout/menu/style3.php'); ?>
        <?php endif; ?>
        <?php if ($settings['menu_style'] == 'style4'): ?>
            <?php
            wp_enqueue_style('er-standard-round');
            $nav_walker_default['menu_list_item_cls'] = 'nav-item ' . $settings['enable_mega_menu_content'] == 'yes' ? 'element-ready-mega-menu-item' : ''; ?>
            <?php $nav_walker_default['sub_menu_ul_cls'] = 'sub-menu element-ready-sub-menu'; ?>
            <?php $args['walker'] = new Offcanvas_Nav_Walker($nav_walker_default); ?>
            <?php $args['menu_class'] = $settings['menu_custom_class'] == '' ? 'navbar-nav element-ready--menu--list element-ready-navbar ' : 'navbar-nav element-ready--menu--list element-ready-navbar ' . $settings['menu_custom_class']; ?>
            <?php include('layout/menu/style4.php'); ?>
        <?php endif; ?>
        <?php if ($settings['menu_style'] == 'style5'): ?>
            <?php
            wp_enqueue_style('er-standard-5-menu');
            $nav_walker_default['menu_list_item_cls'] = 'nav-item ' . $settings['enable_mega_menu_content'] == 'yes' ? 'element-ready-mega-menu-item' : ''; ?>
            <?php $nav_walker_default['sub_menu_ul_cls'] = 'sub-menu element-ready-sub-menu'; ?>
            <?php $args['walker'] = new Offcanvas_Nav_Walker($nav_walker_default); ?>
            <?php $args['menu_class'] = $settings['menu_custom_class'] == '' ? 'navbar-nav element-ready--menu--list element-ready-navbar ' : 'navbar-nav element-ready--menu--list element-ready-navbar ' . $settings['menu_custom_class']; ?>
            <?php include('layout/menu/style5.php'); ?>
        <?php endif; ?>
        <!--====== PART ENDS ======-->
        <?php
    }
    protected function content_template()
    {
    }
}