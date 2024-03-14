<?php

namespace Element_Ready\Modules\Menu_Builder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use \Elementor\Plugin;
use Elementor\Icons_Manager;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/content_controls/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php' );
/**
 * WooCommerce Category | Vartical Menu
 * @see https://docs.woocommerce.com/document/woocommerce_breadcrumb/
 * @author quomodosoft.com
 */
class Element_Ready_Vertical_Menu extends Widget_Base {
	
    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;

    public function get_name() {
        return 'element-ready-vartical-mega-menu';
    }
    public function get_keywords() {
		return ['element ready','menu','nav','category menu','vartical menu'];
	}
    public function get_title() {
        return esc_html__( 'ER Vartical Menu', 'element-ready-lite' );
    }

    public function get_icon() { 
        return 'eicon-menu-toggle';
    }
    public function get_script_depends() {
       
        return [
           'element-ready-vartical-menu'
        ];
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }

	protected function register_controls() { 

        $this->start_controls_section(
			'woo_ready_category_vertical_options',
			[
				'label' => __( 'Vertical Menu Options', 'element-ready-lite' ),
			]
		);

        $this->add_control(
            'woo_ready_category_vertical_menu_layout_type',
            [
                'label' => __('Layout Type', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'always-open' => __('Always Open', 'element-ready-lite'),
                    'open-close'  => __('Open to Close', 'element-ready-lite'),
                    'close-open'  => __('Close to Open', 'element-ready-lite'),
                ],
                'default' => 'always-open',
            ]
        );

        $this->add_control(
            'woo_ready_category_vertical_heading_menu_icon',
            [
                'label' => esc_html__('Icon', 'element-ready-lite'),
                'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-bars',
					'library' => 'solid',
				],
            ]
        );

        $this->add_control(
            'woo_ready_category_vertical_heading_menu_title',
            [
                'label' => esc_html__('Category Heading', 'element-ready-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('All Categories', 'element-ready-lite'),
                'dynamic' => ['active' => true],
            ]
        );

		$this->end_controls_section();

        $this->start_controls_section(
            'woo_ready_category_vertical_content_settings',
            [
                'label' => esc_html__('Vertical Menu Content', 'element-ready-lite'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'woo_ready_category_vertical_show_icon',
            [
                'label' => esc_html__('Show Icon', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'woo_ready_category_vertical_menu_title_icon',
            [
                'label' => esc_html__('Icon', 'element-ready-lite'),
                'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
                'condition' => [
                    'woo_ready_category_vertical_show_icon' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'woo_ready_category_vertical_show_menu_custom_category',
            [
                'label' => esc_html__('Custom Title', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
               
            ]
        );
        
        $repeater->add_control(
            'woo_ready_category_vertical_menu_custom_title',
            [
                'label' => esc_html__('Tab Title', 'element-ready-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'element-ready-lite'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'woo_ready_category_vertical_show_menu_custom_category' => 'yes',
                ],
            ]
        );

        $product_categories = element_ready_get_post_cat();
        $blog_categories = element_ready_get_post_category();
          
        
        $repeater->add_control(
            'er_source_content',
            [
                'label' => __('Content Source', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'wc_cat'   => __('WooCommmerce category', 'element-ready-lite'),
                    'blog_cat' => __('Blog Category', 'element-ready-lite'),
                    
                ],
                'default' => 'content',
                
            ]
        );

        $repeater->add_control(
            'woo_ready_category_vertical_menu_category',
            [
                'label' => esc_html__('Product Category', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $product_categories,
                'condition' => [
                    'woo_ready_category_vertical_show_menu_custom_category!' => 'yes',
                    'er_source_content!' => 'blog_cat',
                ],
            ]
        ); 
        
        $repeater->add_control(
            'woo_ready_category_vertical_menu_blog_category',
            [
                'label' => esc_html__('Blog Category', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $blog_categories,
                'condition' => [
                    'er_source_content' => 'blog_cat',
                ],
            ]
        );


        $repeater->add_control(
            'woo_ready_category_vertical_menu_content_type',
            [
                'label' => __('Content Type', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => __('Content', 'element-ready-lite'),
                    'template' => __('Saved Templates', 'element-ready-lite'),
                ],
                'default' => 'content',
            ]
        );

        $repeater->add_control(
            'woo_ready_category_vertical_menu_template',
            [
                'label' => __('Choose Template', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => element_ready_get_elementor_templates(),
                'condition' => [
                    'woo_ready_category_vertical_menu_content_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'woo_ready_category_vertical_menu_content',
            [
                'label' => esc_html__('Tab Content', 'element-ready-lite'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'condition' => [
                    'woo_ready_category_vertical_menu_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_category_vertical_menu',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['tab_title' => esc_html__('Fashion Accesories', 'element-ready-lite')],
                    ['tab_title' => esc_html__('Consumer Electronics', 'element-ready-lite')],
                    ['tab_title' => esc_html__('Phone & Telecom', 'element-ready-lite')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{woo_ready_category_vertical_menu_custom_title}}',
            ]
        );
        $this->end_controls_section();

        $this->box_css(
            [
                'title'          => esc_html__('Main Wrapper','element-ready-lite'),
                'slug'           => 'wready_product_category_main_wrapper',
                'element_name'   => '_wready_product_category_main_wrapper',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu',
            ]
        );

        $this->box_css(
            [
                'title'          => esc_html__('Vertical Menu Container','element-ready-lite'),
                'slug'           => 'wready_product_category_vertical_menu_wrapper',
                'element_name'   => '_wready_product_category_vertical_menu_wrapper',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .element-ready-header-box',
            ]
        );

        $this->box_css(
            [
                'title'          => esc_html__('Heading Container','element-ready-lite'),
                'slug'           => 'wready_product_category_heading_wrapper',
                'element_name'   => '_wready_product_category_heading_wrapper',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .element-ready-header-box .element-ready-widget-title',
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Heading Icon','element-ready-lite'),
                'slug'           => 'wready_product_category_menu_heading_icon',
                'element_name'   => '_wready_product_category_menu_heading_icon',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .element-ready-header-box .element-ready-widget-title .vertical-menu-heading-icon i',
                'hover_selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .element-ready-header-box .element-ready-widget-title:hover .vertical-menu-heading-icon i',
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Heading Title','element-ready-lite'),
                'slug'           => 'wready_product_category_menu_heading_title',
                'element_name'   => '_wready_product_category_menu_heading_title',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .element-ready-header-box .element-ready-widget-title .vertical-category-menu-title',
                'hover_selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .element-ready-header-box .element-ready-widget-title .vertical-category-menu-title:hover',
            ]
        );

        $this->box_css(
            [
                'title'          => esc_html__('Menu Box','element-ready-lite'),
                'slug'           => 'wready_product_category_vertical_menu_box_wrapper',
                'element_name'   => '_wready_product_category_vertical_menu_box_wrapper',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu',
            ]
        );

        $this->box_css(
            [
                'title'          => esc_html__('Menu Item Box','element-ready-lite'),
                'slug'           => 'wready_product_category_vertical_menu_item_box',
                'element_name'   => '_wready_product_category_vertical_menu_item_box',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu > li',
            ]
        );

        $this->box_css(
            [
                'title'          => esc_html__('Menu Icon Box','element-ready-lite'),
                'slug'           => 'wready_product_category_menu_icon_box',
                'element_name'   => '_wready_product_category_menu_icon_box',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li .icon',
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Menu Icon','element-ready-lite'),
                'slug'           => 'wready_product_category_menu_icon',
                'element_name'   => '_wready_product_category_menu_icon',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li .icon i',
                'hover_selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li:hover .icon i',
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Menu Title','element-ready-lite'),
                'slug'           => 'wready_product_category_menu_title',
                'element_name'   => '_wready_product_category_menu_title',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li > a',
                'hover_selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li:hover > a',
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('After Title Icon','element-ready-lite'),
                'slug'           => 'wready_product_category_menu_after_title_icon',
                'element_name'   => '_wready_product_category_menu_after_title_icon',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li .after-category-name-icon i',
                'hover_selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li:hover .after-category-name-icon i',
            ]
        );

        $this->box_css(
            [
                'title'          => esc_html__('Sub Menu Box','element-ready-lite'),
                'slug'           => 'wready_product_category_sub_menu_box',
                'element_name'   => '_wready_product_category_sub_menu_box',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li .wooready-sub-menu',
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Sub Menu Style','element-ready-lite'),
                'slug'           => 'wready_product_category_sub_menu_style',
                'element_name'   => '_wready_product_category_sub_menu_style',
                'selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li .wooready-sub-menu',
                'hover_selector'       => '{{WRAPPER}} .woo-ready-product-vertical-menu .wooready-vertical-menu .wooready-menu-vertical-menu li .wooready-sub-menu:hover',
            ]
        );


	}

	/**
	 * Override By elementor render method
	 * @return void
	 * 
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
     
        $this->add_render_attribute(
            'woo_ready_product_vertical_menu_wrapper',
            [
                'id' => "woo-ready-product-vertical-menu-{$this->get_id()}",
                'class' => ['woo-ready-product-vertical-menu', 'wooready-header-area', 'woo-ready-product-vertical-menu-'.$settings['woo_ready_category_vertical_menu_layout_type']],
                'data-layout' => esc_attr( $settings['woo_ready_category_vertical_menu_layout_type']) ,
                'data-tabid' => $this->get_id(),
            ]
        );

       ?>
        <style id="er-vartical-menu-pro-feature-in-free">
        .wooready-vertical-menu {
            position: absolute;
            width: 100%;
            background: #fff;
            border-top: 0;
            border-radius: 0 0 6px 6px;
            -webkit-border-radius: 0 0 6px 6px;
            -moz-border-radius: 0 0 6px 6px;
            -ms-border-radius: 0 0 6px 6px;
            -o-border-radius: 0 0 6px 6px;
            box-shadow: 0 0 10px 1px rgba(143, 143, 143, 0.1);
        }

        .wooready-vertical-menu.wooready-open-1 {
            display: none;
        }

        .woo-ready-product-vertical-menu-close-open .wooready-vertical-menu {
            display: none;
        }

        .wooready-vertical-menu {
            border-radius: 0 0 5px 5px;
            border: 1px solid #cecfdb;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu {
            border-top: 0;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li {
            position: relative;
            padding: 12px;
            border-bottom: 1px solid #cecfdb;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li:last-child {
            border-bottom: 0;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            align-items: center;
            color: #09114a;
            font-size: 16px;
            position: relative;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .after-category-name-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #09114a;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a i {
                transform: rotate(90deg);
            }
        }

        @media (max-width: 767px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a i {
                transform: rotate(90deg);
            }
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .icon {
            height: 36px;
            width: 36px;
            background: #fff1ef;
            text-align: center;
            line-height: 32px;
            border-radius: 50%;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .icon svg path {
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .icon i {
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a span {
            margin-left: 8px;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon {
            background: #ff4b34;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon svg path {
            fill: #fff;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon i {
            color: #fff;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon i.fas {
            line-height: -1;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li:last-child a {
            border-bottom: 0;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
            position: absolute;
            left: 110%;
            top: 0;
            padding: 50px;
            box-shadow: 0 0 10px 1px rgba(143, 143, 143, 0.1);
            width: 760px;
            opacity: 0;
            visibility: hidden;
            -webkit-transition: all 0.5s ease-out 0s;
            -moz-transition: all 0.5s ease-out 0s;
            -ms-transition: all 0.5s ease-out 0s;
            -o-transition: all 0.5s ease-out 0s;
            transition: all 0.5s ease-out 0s;
        }

        @media only screen and (min-width: 992px) and (max-width: 1200px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                width: 620px;
                padding: 30px;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                left: 0;
                top: 100%;
                width: 100%;
                z-index: 99;
                background: #fff;
                padding: 30px;
            }
        }

        @media (max-width: 767px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                left: 0;
                top: 100%;
                width: 100%;
                z-index: 99;
                background: #fff;
                padding: 30px;
            }
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small {
            position: absolute;
            left: 110%;
            top: 0;
            padding: 0px;
            box-shadow: 0 0 10px 1px rgba(143, 143, 143, 0.1);
            min-width: 300px;
            opacity: 0;
            visibility: hidden;
            background: #fff;
            -webkit-transition: all 0.5s ease-out 0s;
            -moz-transition: all 0.5s ease-out 0s;
            -ms-transition: all 0.5s ease-out 0s;
            -o-transition: all 0.5s ease-out 0s;
            transition: all 0.5s ease-out 0s;
            z-index: 10;
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small {
                left: 0;
                top: 100%;
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small {
                left: 0;
                top: 100%;
                width: 100%;
            }
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul {
            background: #fff;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul li a {
            padding: 0px 30px;
            border-bottom: 1px solid #ccc;
            display: block;
            line-height: 60px;
            color: #09114a;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul li a:hover {
            color: #ff4b34;
            padding-left: 40px;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul li:last-child a {
            border-bottom: 0;
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu {
            left: 100%;
            visibility: visible;
            opacity: 1;
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu {
                left: 0;
            }
        }

        @media (max-width: 767px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu {
                left: 0;
            }
        }

        .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu-small {
            left: 100%;
            visibility: visible;
            opacity: 1;
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu-small {
                left: 0;
            }
        }

        @media (max-width: 767px) {
            .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu-small {
                left: 0;
            }
        }
       </style>    
        <section <?php echo $this->get_render_attribute_string( 'woo_ready_product_vertical_menu_wrapper' )?>>
           <div class="element-ready-header-box">
                <h3 class="element-ready-widget-title">
                    <span class="vertical-menu-heading-icon">
                        <?php 
                            if ($settings['woo_ready_category_vertical_heading_menu_icon']) {
                                Icons_Manager::render_icon( $settings['woo_ready_category_vertical_heading_menu_icon'] ); 
                            } else { ?>
                                <i class="fa fa-bars"></i>
                            <?php } 
                        ?>
                    </span>
                    <span class="vertical-category-menu-title"><?php echo esc_html($settings['woo_ready_category_vertical_heading_menu_title']) ?></span>
                </h3>
                <div class="wooready-vertical-menu">
                    <ul class="wooready-menu-vertical-menu">
                        <?php foreach ($settings['woo_ready_category_vertical_menu'] as $menu) : 
                            if($menu['er_source_content'] == 'wc_cat'): 
                                $product_cat_name = $menu['woo_ready_category_vertical_menu_category'] ? element_ready_get_product_category_name_from_id($menu['woo_ready_category_vertical_menu_category']) : $menu['woo_ready_category_vertical_menu_custom_title'] ;
                                $link = get_category_link($menu['woo_ready_category_vertical_menu_category']);
                            else:
                                $product_cat_name = $menu['woo_ready_category_vertical_menu_blog_category'] ? element_ready_get_blog_category_name_from_id($menu['woo_ready_category_vertical_menu_blog_category']) : $menu['woo_ready_category_vertical_menu_custom_title'] ;
                                $link = get_category_link($menu['woo_ready_category_vertical_menu_blog_category']);
                            endif; 
                            if($menu['woo_ready_category_vertical_show_menu_custom_category'] == 'yes'){
                                $product_cat_name = $menu['woo_ready_category_vertical_menu_custom_title'];
                            }
                        ?>
                        <li>
                            <a href="<?php echo esc_url($link); ?>">
                                <?php if (($menu['woo_ready_category_vertical_show_icon'] === 'yes') && ($menu['woo_ready_category_vertical_menu_title_icon']['value'] !== '') ) : ?>
                                    <div class="icon">
                                        <?php if ( $menu['woo_ready_category_vertical_menu_title_icon'] ) {
                                                Icons_Manager::render_icon( $menu['woo_ready_category_vertical_menu_title_icon'] );
                                            }
                                        ?>
                                    </div>
                                <?php endif; 
                                    if ( $product_cat_name ) { ?>
                                    <span class="category-menu-item-name">
                                        <?php echo esc_html($product_cat_name); ?>
                                    </span>
                                <?php } 
                                if ( $menu['woo_ready_category_vertical_menu_content'] || $menu['woo_ready_category_vertical_menu_template']) {?>
                                    <span class="after-category-name-icon"><i class="fa fa-angle-right"></i></span>
                                <?php } ?>
                            </a>
                            <?php if ( $menu['woo_ready_category_vertical_menu_content'] || $menu['woo_ready_category_vertical_menu_template']) {?>
                                <div class="wooready-sub-menu wooready-shop-page-menu">
                                    <?php if ( 'content' === $menu['woo_ready_category_vertical_menu_content_type']) : ?>
                                        <?php echo do_shortcode($menu['woo_ready_category_vertical_menu_content']); ?>
                                    <?php elseif ( 'template' === $menu['woo_ready_category_vertical_menu_content_type']) : ?>
                                        <?php if (!empty($menu['woo_ready_category_vertical_menu_template'])) {
                                            echo Plugin::$instance->frontend->get_builder_content($menu['woo_ready_category_vertical_menu_template'], true);
                                        } ?>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
       </section>
	<?php }

}