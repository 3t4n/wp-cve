<?php

namespace Shop_Ready\extension\elewidgets\document;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Core\Base\Document;
use Shop_Ready\base\elementor\controls\Custom_Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/* 
 * Site Common Settings
 * @since 1.0 
 */

class Common_Settings extends Tab_Base
{

    public function get_id()
    {
        return 'shop-ready-common';
    }

    public function get_title()
    {
        return esc_html__('ShopReady General & PopUp', 'shopready-elementor-addon');
    }

    public function get_group()
    {
        return 'settings';
    }

    public function get_icon()
    {
        return 'eicon-woo-settings';
    }

    public function get_help_url()
    {
        return 'quomodosoft.com';
    }

    function masking_image_shapes($path = 'gif')
    {

        $widgets_modules = [];
        $dir_path = SHOP_READY_ELEWIDGET_PATH . "assets/img/" . $path;
        $url_path = SHOP_READY_ELEWIDGET_MODULE_URL . "assets/img/" . $path;
        $dir = new \DirectoryIterator($dir_path);

        foreach ($dir as $fileinfo) {

            if (!$fileinfo->isDot()) {
                $file_name = explode('.', $fileinfo->getFilename());
                $widgets_modules[$url_path . '/' . $fileinfo->getFilename()] = [
                    'title' => $file_name[0],
                    'width' => '33%',
                    'imagelarge' => $url_path . '/' . $fileinfo->getFilename(),
                    'imagesmall' => $url_path . '/' . $fileinfo->getFilename(),
                ];

            }
        }

        return $widgets_modules;
    }

    protected function register_tab_controls()
    {

        $this->theme_fixing();
        $this->global_wc_notice();
        $this->common_button();
        $this->empty_cart();

        $this->modal_common_popup();
        $this->modal_wc_popup();
        $this->product_compare();

        $this->modal_wishlist_popup();
        $this->product_wishlist();

        $this->modal_quickview_popup();
        $this->product_quickview();

        $this->quick_checkout_popup();

        do_action('shop_ready_newslatter_popup', $this, $this->get_id());
        do_action('shop_ready_common/settings/style', $this, $this->get_id());


    }

    public function quick_checkout_popup()
    {

        $this->start_controls_section(
            'woo_ready_wc_global_quickcheckout_modal_popup',
            [
                'label' => esc_html__('Quick Checkout', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'shop_ready_product_quick_checkout_preloader',
            [
                'label' => esc_html__('Loader Types', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'shopready-elementor-addon'),
                    'custom' => esc_html__('custom', 'shopready-elementor-addon'),
                    'none' => esc_html__('None', 'shopready-elementor-addon'),
                ],

            ]
        );

        $this->add_control(
            'shop_ready_product_quick_checkout_preloader_icon',
            [
                'label' => esc_html__('Gif Loader', 'shopready-elementor-addon'),
                'type' => Custom_Controls_Manager::WRRADIOIMAGE,
                'default' => '',
                'options' => $this->masking_image_shapes(),
                'condition' => [
                    'shop_ready_product_quick_checkout_preloader' => ['default']
                ]

            ]
        );

        $this->add_control(
            'shop_ready_product_quick_checkout_preloader_custom',
            [
                'label' => esc_html__('Choose GIF', 'shopready-elementor-addon'),
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition' => [
                    'shop_ready_product_quick_checkout_preloader' => 'custom',
                ],


            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop_ready_product_quick_checkout_preloader__background',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient'],

                'selector' => 'body .shop-ready-pro-quick-checkout-popup-modal::before',
            ]
        );


        $this->end_controls_section();

    }

    public function modal_common_popup()
    {

        $this->start_controls_section(
            'woo_ready_wc_global_commmon_modal_popup',
            [
                'label' => esc_html__('PopUp Overlay', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'woo_ready_wc_modal_pop_overlay_color',
            [
                'label' => __('Overlay Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,

                'selectors' => [
                    'body .wready-md-overlay' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

    }
    public function product_compare()
    {

        if (!shop_ready_sysytem_module_options_is_active('product_comparison')) {
            return;
        }

        $this->start_controls_section(
            'woo_ready_wc_product_compares',
            [
                'label' => esc_html__('Product Compare', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->start_controls_tabs(
            'shop_ready_product_compare_modal_pop_up_gen_con'
        );

        $this->start_controls_tab(
            'shop_ready_wc_compare_popup_modal_normal',
            [
                'label' => esc_html__('Content', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_product_compare_icon',
            [
                'label' => __('Compare Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-retweet',
                    'library' => 'solid',
                ],
                'condition' => [
                    'woo_ready_enable_product_compare_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_compare_text',
            [
                'label' => __('Compare Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Compare', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_enable_product_compare_close_button',
            [
                'label' => __('Close?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'shopready-elementor-addon'),
                'label_off' => __('Hide', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'woo_ready_product_compare_close_text',
            [
                'label' => __('Close Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Close', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_product_compare_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_compare_close_icon',
            [
                'label' => __('Close Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'solid',
                ],
                'condition' => [
                    'woo_ready_enable_product_compare_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_enable_product_compare_show_heading',
            [
                'label' => __('Show Heading?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'shopready-elementor-addon'),
                'label_off' => __('Hide', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );


        $this->add_control(
            'woo_ready_product_compare_heading',
            [
                'label' => __('Heading', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Products Compare', 'shopready-elementor-addon'),
                'placeholder' => __('Products Compare', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_product_compare_show_heading' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_compare_modal_animation',
            [
                'label' => __('Animation', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide-in-bottom',
                'options' => [
                    'slide-in-bottom' => __('Slide In Bottom', 'shopready-elementor-addon'),
                    'fade-in-scale' => __('Fade Scale', 'shopready-elementor-addon'),
                    'slide-in-right' => __('Slide Right', 'shopready-elementor-addon'),
                    'newspaper' => __('Newspaper', 'shopready-elementor-addon'),
                    'fall' => __('Fall', 'shopready-elementor-addon'),
                    'slide-fall-in' => __('SLide Fall In', 'shopready-elementor-addon'),
                    'slide-in-top-stick' => __('Slide In Top', 'shopready-elementor-addon'),
                    'super-scaled' => __('Super Scale', 'shopready-elementor-addon'),
                    'just-me' => __('Just Me', 'shopready-elementor-addon'),
                    'blur' => __('Blur', 'shopready-elementor-addon'),
                    'slide-in-bottom-perspective' => __('Slide Bottom Perspective', 'shopready-elementor-addon'),
                    'slide-in-right-prespective' => __('Slide Right Perspective', 'shopready-elementor-addon'),
                    'slip-in-top-perspective' => __('Slip Perspective', 'shopready-elementor-addon'),
                    'threed-flip-horizontal' => __('3D Flip Horizontal', 'shopready-elementor-addon'),
                    'threed-flip-vertical' => __('3D Flip Vertical', 'shopready-elementor-addon'),
                    'threed-sign' => __('3d Sign', 'shopready-elementor-addon'),
                    'threed-slit' => __('3D Slit', 'shopready-elementor-addon'),
                    'threed-rotate-bottom' => __('3D Rotate Bottom', 'shopready-elementor-addon'),
                    'threed-rotate-in-left' => __('3D Rotate Left', 'shopready-elementor-addon'),
                ],

            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'shop_ready__wc_compare_popup_modal_preloader_',
            [
                'label' => esc_html__('Preloader', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'shop_ready_product_compare_preloader',
            [
                'label' => esc_html__('Loader Types', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'shopready-elementor-addon'),
                    'custom' => esc_html__('custom', 'shopready-elementor-addon'),
                    'none' => esc_html__('None', 'shopready-elementor-addon'),
                ],

            ]
        );

        $this->add_control(
            'shop_ready_product_compare_preloader_icon',
            [
                'label' => esc_html__('Gif Loader', 'shopready-elementor-addon'),
                'type' => Custom_Controls_Manager::WRRADIOIMAGE,
                'default' => '',
                'options' => $this->masking_image_shapes(),
                'condition' => [
                    'shop_ready_product_compare_preloader' => ['default']
                ]

            ]
        );

        $this->add_control(
            'shop_ready_product_compare_preloader_custom',
            [
                'label' => esc_html__('Choose GIF', 'shopready-elementor-addon'),
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition' => [
                    'shop_ready_product_compare_preloader' => 'custom',
                ],


            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop_ready_product_compare_preloader__background',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient'],

                'selector' => 'body .sr-compare-overly-preloading::before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'shop_ready__wc_compare_popup_modal_styles_',
            [
                'label' => esc_html__('Size', 'shopready-elementor-addon'),
            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_width',
            [
                'label' => __('Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 90,
                ],
                'selectors' => [
                    'body .woo-ready-product-compare-modal' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_min_width',
            [
                'label' => __('Minimum Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 320,
                ],
                'selectors' => [
                    'body .woo-ready-product-compare-modal' => 'min-width: {{SIZE}}{{UNIT}} !important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_max_width',
            [
                'label' => __('Max Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-compare-modal' => 'max-width: {{SIZE}}{{UNIT}} !important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_height',
            [
                'label' => __('Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-compare-modal' => 'height: {{SIZE}}{{UNIT}} !important;',

                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_min_height',
            [
                'label' => __('Minimum Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    '{{WRAPPER}} .woo-ready-product-compare-modal' => 'min-height: {{SIZE}}{{UNIT}} !important;',
                ],

            ]
        );

        $this->add_control(
            'woo_ready_product_compare_modal_overflow_y',
            [
                'label' => __('Overflow Vertical', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'hidden' => __('None', 'shopready-elementor-addon'),
                    'scroll' => __('Scroll', 'shopready-elementor-addon'),

                ],
                'selectors' => [
                    'body .woo-ready-product-compare-modal' => 'overflow-y: {{VALUE}} !important;',
                ],

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    /**
     * Product Compare Style
     **/
    public function modal_wc_popup()
    {

        if (!shop_ready_sysytem_module_options_is_active('product_comparison')) {
            return;
        }

        $this->start_controls_section(
            'woo_ready_wc_global_modal_popup',
            [
                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('product Compare Style Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );



        $this->start_controls_tabs(
            'woo_ready_wc_modal_pop_up_gen_con'
        );


        $this->start_controls_tab(
            'woo_ready_wc_popup_modal_heading',
            [
                'label' => esc_html__('Heading', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_ctext_align',
            [
                'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '.woo-ready-product-compare-modal .woo-ready-product-compare-content .wready-md-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_color',
            [
                'label' => esc_html__('Text Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-compare-modal .woo-ready-product-compare-content .wready-md-title h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_pop_up_title_font',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-compare-modal .wready-md-title h3',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_pop_up_title_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woo-ready-product-compare-modal .woo-ready-product-compare-content .wready-md-title',
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-compare-modal .woo-ready-product-compare-content .wready-md-title h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->start_controls_tab(
            'woo_ready_wc_popup_modal_body',
            [
                'label' => esc_html__('Body', 'shopready-elementor-addon'),
            ]
        );


        $this->add_control(
            'woo_ready_pop_up_container__margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-compare-modal .woo-ready-product-compare-content .wready-md-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_S_hlky_min_width',
            [
                'label' => __('Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-compare-modal .woo-ready-product-compare-content' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_cmp_modal_S_hlky_min_height',
            [
                'label' => __('Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 98,
                ],
                'selectors' => [
                    'body .woo-ready-product-compare-modal .woo-ready-product-compare-content' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'woo_ready_product_compare_modal__body_overflow_y',
            [
                'label' => __('Overflow Vertical', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'hidden' => __('None', 'shopready-elementor-addon'),
                    'scroll' => __('Scroll', 'shopready-elementor-addon'),
                ],
                'selectors' => [
                    'body .woo-ready-product-compare-modal .woo-ready-product-compare-content' => 'overflow-y: {{VALUE}};',
                ],

            ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_wc_popup_close_btn',
            [
                'label' => esc_html__('Close Button', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btn_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btn_hover_color',
            [
                'label' => esc_html__('Hover Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_pop_up_close_btn_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-compare-modal .wready-md-close',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'woo_ready_pop_up_close_btn_box_shadow',
                'label' => __('Box Shadow', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-compare-modal .wready-md-close',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'woo_ready_pop_up_close_btn_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-compare-modal .wready-md-close',
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btnborder__radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btn_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btn_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_pop_up_close_btn_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woo-ready-product-compare-modal .wready-md-close',
            ]
        );

        $this->add_responsive_control(
            'woo_ready_pop_up_close_btn__position_left',
            [
                'label' => esc_html__('Position Left', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'left: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'woo_ready_pop_up_close_btn__r_position_top',
            [
                'label' => esc_html__('Position Top', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'top: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'woo_ready_pop_up_close_btn__r_position_bottom',
            [
                'label' => esc_html__('Position Bottom', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2100,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'bottom: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'woo_ready_pop_up_close_btn__r_position_right',
            [
                'label' => esc_html__('Position Right', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-compare-modal .wready-md-close' => 'right: {{SIZE}}{{UNIT}};',

                ],
            ]
        );



        $this->end_controls_tab();


        $this->end_controls_tabs();


        $this->end_controls_section();
    }

    public function product_wishlist()
    {

        if (!shop_ready_sysytem_module_options_is_active('wishlist')) {
            return;
        }
        $this->start_controls_section(
            'woo_ready_wc_product_wishlist',
            [
                'label' => esc_html__('Shop WishList', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->start_controls_tabs(
            'shop_ready_product_wishlist_modal_pop_up_gen_con'
        );

        $this->start_controls_tab(
            'shop_ready_wc_wishlist_popup_modal_normal',
            [
                'label' => esc_html__('Content', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_button_text',
            [
                'label' => __('PopUp Page Button Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('WishList', 'shopready-elementor-addon'),
                'placeholder' => __('WishList', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_enable_product_wishlist_return_button',
            [
                'label' => __('Shop Return Button', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_button_shop_text',
            [
                'label' => __('Shop Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Return Shop', 'shopready-elementor-addon'),
                'placeholder' => __('Return Shop', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_icon',
            [
                'label' => __('Wishlist Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-heart',
                    'library' => 'solid',
                ],
                'condition' => [
                    'woo_ready_enable_product_wishlist_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_add_to_cart_text',
            [
                'label' => __('Add To Cart', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Add To Cart', 'shopready-elementor-addon'),
                'placeholder' => __('Add ', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_text',
            [
                'label' => __('Wishlist Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Wishlist', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_enable_product_wishlist_close_button',
            [
                'label' => __('Close?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'shopready-elementor-addon'),
                'label_off' => __('Hide', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_close_text',
            [
                'label' => __('Close Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Close', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_product_wishlist_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_close_icon',
            [
                'label' => __('Close Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'solid',
                ],
                'condition' => [
                    'woo_ready_enable_product_wishlist_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_enable_product_wishlist_show_heading',
            [
                'label' => __('Show Heading?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'shopready-elementor-addon'),
                'label_off' => __('Hide', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );


        $this->add_control(
            'woo_ready_product_wishlist_heading',
            [
                'label' => __('Heading', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Wishlist', 'shopready-elementor-addon'),
                'placeholder' => __('Products wishlist', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_product_wishlist_show_heading' => [
                        'yes',
                    ],

                ]
            ]
        );


        $this->add_control(
            'woo_ready_product_wishlist_modal_animation',
            [
                'label' => __('Animation', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide-in-bottom',
                'options' => [
                    'slide-in-bottom' => __('Slide In Bottom', 'shopready-elementor-addon'),
                    'fade-in-scale' => __('Fade Scale', 'shopready-elementor-addon'),
                    'slide-in-right' => __('Slide Right', 'shopready-elementor-addon'),
                    'newspaper' => __('Newspaper', 'shopready-elementor-addon'),
                    'fall' => __('Fall', 'shopready-elementor-addon'),
                    'slide-fall-in' => __('SLide Fall In', 'shopready-elementor-addon'),
                    'slide-in-top-stick' => __('Slide In Top', 'shopready-elementor-addon'),
                    'super-scaled' => __('Super Scale', 'shopready-elementor-addon'),
                    'just-me' => __('Just Me', 'shopready-elementor-addon'),
                    'blur' => __('Blur', 'shopready-elementor-addon'),
                    'slide-in-bottom-perspective' => __('Slide Bottom Perspective', 'shopready-elementor-addon'),
                    'slide-in-right-prespective' => __('Slide Right Perspective', 'shopready-elementor-addon'),
                    'slip-in-top-perspective' => __('Slip Perspective', 'shopready-elementor-addon'),
                    'threed-flip-horizontal' => __('3D Flip Horizontal', 'shopready-elementor-addon'),
                    'threed-flip-vertical' => __('3D Flip Vertical', 'shopready-elementor-addon'),
                    'threed-sign' => __('3d Sign', 'shopready-elementor-addon'),
                    'threed-slit' => __('3D Slit', 'shopready-elementor-addon'),
                    'threed-rotate-bottom' => __('3D Rotate Bottom', 'shopready-elementor-addon'),
                    'threed-rotate-in-left' => __('3D Rotate Left', 'shopready-elementor-addon'),
                ],

            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'shop__ready__wc_wishlist_popup_modal_preloader_',
            [
                'label' => esc_html__('Preloader', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'shop_ready_product_wishlist_preloader',
            [
                'label' => esc_html__('Loader Types', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'shopready-elementor-addon'),
                    'custom' => esc_html__('custom', 'shopready-elementor-addon'),
                    'none' => esc_html__('None', 'shopready-elementor-addon'),
                ],

            ]
        );

        $this->add_control(
            'shop_ready_product_wishlist_preloader_icon',
            [
                'label' => esc_html__('GIF Loader', 'shopready-elementor-addon'),
                'type' => Custom_Controls_Manager::WRRADIOIMAGE,
                'default' => '',
                'options' => $this->masking_image_shapes(),
                'condition' => [
                    'shop_ready_product_wishlist_preloader' => ['default']
                ]
            ]
        );

        $this->add_control(
            'shop_ready_product_wishlist_preloader_custom',
            [
                'label' => esc_html__('Choose GIF', 'shopready-elementor-addon'),
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition' => [
                    'shop_ready_product_wishlist_preloader' => 'custom',
                ],


            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop__ready_product_wishlist_preloader__background',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => 'body .woo-ready-product-wishlist-modal-overlay::before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'shop_ready__wc_wishlist_popup_modal_preloader_',
            [
                'label' => esc_html__('Size', 'shopready-elementor-addon'),
            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_wishlist_modal_width',
            [
                'label' => __('Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 90,
                ],
                'selectors' => [
                    'body .woo-ready-product-wishlist-modal' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_wishlist_modal_min_width',
            [
                'label' => __('Minimum Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 320,
                ],
                'selectors' => [
                    'body .woo-ready-product-wishlist-modal' => 'min-width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_wishlist_modal_max_width',
            [
                'label' => __('Max Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 90,
                ],
                'selectors' => [
                    'body .woo-ready-product-wishlist-modal' => 'max-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_wishlist_modal_height',
            [
                'label' => __('Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'default' => [
                    'unit' => '%',
                    'size' => 90,
                ],

                'selectors' => [
                    'body .woo-ready-product-wishlist-modal' => 'height: {{SIZE}}{{UNIT}};',

                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_wishlist_modal_min_height',
            [
                'label' => __('Minimum Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 90,
                ],
                'selectors' => [
                    'body .woo-ready-product-wishlist-modal' => 'min-height: {{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_control(
            'woo_ready_product_wishlist_modal_overflow_y',
            [
                'label' => __('Overflow Vertical', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'hidden' => __('None', 'shopready-elementor-addon'),
                    'scroll' => __('Scroll', 'shopready-elementor-addon'),

                ],
                'selectors' => [
                    'body .woo-ready-product-wishlist-modal' => 'overflow-y: {{VALUE}};',
                ]

            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function modal_wishlist_popup()
    {

        if (!shop_ready_sysytem_module_options_is_active('wishlist')) {
            return;
        }
        $this->start_controls_section(
            'woo_ready_wc_global_modal_wishlist_popup',
            [

                'label' => esc_html__('WishList Style', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->start_controls_tabs(
            'woo_ready_wc_modal_pop_up_gen_wishlist_con'
        );


        $this->start_controls_tab(
            'woo_ready_wc_popup_modalw_heading',
            [
                'label' => esc_html__('Heading', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_wtext_align',
            [
                'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '.woo-ready-product-wishlist-modal .woo-ready-product-wishlist-content .wready-md-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_upw_title_color',
            [
                'label' => esc_html__('Text Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-wishlist-modal .woo-ready-product-wishlist-content .wready-md-title h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_pop_up_titlew_font',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-wishlist-modal .wready-md-title h3',
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_titlew_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-wishlist-modal .wready-md-title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_wishlist_popupw_close_btn',
            [
                'label' => esc_html__('Close Button', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btn_wishlist_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-wishlist-modal .wready-md-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btnw_hoverw_color',
            [
                'label' => esc_html__('Hover Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-wishlist-modal .wready-md-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_closew_btn_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-wishlist-modal .wready-md-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_pop_upw_close_btn_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woo-ready-product-wishlist-modal .wready-md-close',
            ]
        );

        $this->end_controls_tab();


        $this->end_controls_tabs();


        $this->end_controls_section();
    }
    public function modal_quickview_popup()
    {

        if (!shop_ready_sysytem_module_options_is_active('quick_view')) {
            return;
        }

        $this->start_controls_section(
            'woo_ready_wc_global_modal_quickview_popup',
            [

                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('product Quickview Style Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );


        $this->start_controls_tabs(
            'woo_ready_wc_modal_pop_up_gen_quickview_con'
        );


        $this->start_controls_tab(
            'woo_ready_wc_popup_modalwquickview_heading',
            [
                'label' => esc_html__('Heading', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_quickviewtext_align',
            [
                'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'shopready-elementor-addon'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_upquickview_title_color',
            [
                'label' => esc_html__('Text Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-title h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_pop_up_titlequickview_font',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-quickview-modal .wready-md-title h3',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_pop_up_title_quickview_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-title',
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_quickview_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_title_quickviewpadding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-title h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->start_controls_tab(
            'woo_ready_wc_popup_modal_quickviewbody',
            [
                'label' => esc_html__('Body', 'shopready-elementor-addon'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_pop_up_container_quickview_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-body',
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_container_quickviewmargin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_container_quickviewpadding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .woo-ready-product-quickview-content .wready-md-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_quickview_popupquickviewclose_btn',
            [
                'label' => esc_html__('Close Button', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btn_quickview_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_close_btnw_hoverquickview_color',
            [
                'label' => esc_html__('Hover Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_pop_up_closew_btquickview_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woo-ready-product-quickview-modal .wready-md-close',
            ]
        );


        $this->add_control(
            'woo_ready_pop_up_closew_btnborder_quickview_radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_closew_btnquickview_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_pop_up_closequickview_btn_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_pop_upquickview_close_btn_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woo-ready-product-quickview-modal .wready-md-close',
            ]
        );

        $this->add_responsive_control(
            'woo_ready_pop_up_closew_btn_quickview_position_left',
            [
                'label' => esc_html__('Position Left', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'left: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'woo_ready_pop_up_closew_btn_wish_quickview_position_top',
            [
                'label' => esc_html__('Position Top', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'top: {{SIZE}}{{UNIT}};',

                ],
            ]
        );


        $this->add_responsive_control(
            'woo_ready_pop_up_closew_btn__quickview_position_right',
            [
                'label' => esc_html__('Position Right', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1600,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    '.woo-ready-product-quickview-modal .wready-md-close' => 'right: {{SIZE}}{{UNIT}};',

                ],
            ]
        );



        $this->end_controls_tab();


        $this->end_controls_tabs();


        $this->end_controls_section();
    }
    public function product_quickview()
    {

        if (!shop_ready_sysytem_module_options_is_active('quick_view')) {
            return;
        }

        $this->start_controls_section(
            'woo_ready_wc_product_quickview',
            [
                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('product QuickView Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );
        $this->start_controls_tabs(
            'shop_ready_product_quickview_modal_pop_up_gen_con'
        );

        $this->start_controls_tab(
            'shop_ready_wc_quickview_popup_modal_normal',
            [
                'label' => esc_html__('Content', 'shopready-elementor-addon'),
            ]
        );
        $this->add_control(
            'woo_ready_product_quickview_icon',
            [
                'label' => esc_html__('QuickView Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-eye',
                    'library' => 'solid',
                ],
                'condition' => [
                    'woo_ready_enable_product_quickview_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_add_to_cart_text',
            [
                'label' => esc_html__('Add To Cart', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Add To Cart', 'shopready-elementor-addon'),
                'placeholder' => esc_html__('Add ', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_text',
            [
                'label' => esc_html__('Quickview Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Quickview', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_enable_product_quickview_close_button',
            [
                'label' => __('Close?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'shopready-elementor-addon'),
                'label_off' => __('Hide', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_close_text',
            [
                'label' => __('Close Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Close', 'shopready-elementor-addon'),
                'placeholder' => __('Close', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_product_quickview_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_close_icon',
            [
                'label' => __('Close Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'solid',
                ],
                'condition' => [
                    'woo_ready_enable_product_quickview_close_button' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_enable_product_quickview_show_heading',
            [
                'label' => __('Show Heading?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'shopready-elementor-addon'),
                'label_off' => __('Hide', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_heading',
            [
                'label' => __('Heading', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Products quickview', 'shopready-elementor-addon'),
                'placeholder' => __('Products quickview', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_product_quickview_show_heading' => [
                        'yes',
                    ],

                ]
            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_modal_animation',
            [
                'label' => __('Animation', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide-in-bottom',
                'options' => [
                    'slide-in-bottom' => __('Slide In Bottom', 'shopready-elementor-addon'),
                    'fade-in-scale' => __('Fade Scale', 'shopready-elementor-addon'),
                    'slide-in-right' => __('Slide Right', 'shopready-elementor-addon'),
                    'newspaper' => __('Newspaper', 'shopready-elementor-addon'),
                    'fall' => __('Fall', 'shopready-elementor-addon'),
                    'slide-fall-in' => __('SLide Fall In', 'shopready-elementor-addon'),
                    'slide-in-top-stick' => __('Slide In Top', 'shopready-elementor-addon'),
                    'super-scaled' => __('Super Scale', 'shopready-elementor-addon'),
                    'just-me' => __('Just Me', 'shopready-elementor-addon'),
                    'blur' => __('Blur', 'shopready-elementor-addon'),
                    'slide-in-bottom-perspective' => __('Slide Bottom Perspective', 'shopready-elementor-addon'),
                    'slide-in-right-prespective' => __('Slide Right Perspective', 'shopready-elementor-addon'),
                    'slip-in-top-perspective' => __('Slip Perspective', 'shopready-elementor-addon'),
                    'threed-flip-horizontal' => __('3D Flip Horizontal', 'shopready-elementor-addon'),
                    'threed-flip-vertical' => __('3D Flip Vertical', 'shopready-elementor-addon'),
                    'threed-sign' => __('3d Sign', 'shopready-elementor-addon'),
                    'threed-slit' => __('3D Slit', 'shopready-elementor-addon'),
                    'threed-rotate-bottom' => __('3D Rotate Bottom', 'shopready-elementor-addon'),
                    'threed-rotate-in-left' => __('3D Rotate Left', 'shopready-elementor-addon'),
                ],

            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'shop_ready_wc_quickview_popup_modal_preloader',
            [
                'label' => esc_html__('Preloader', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'shop_ready_product_quickview_preloader',
            [
                'label' => esc_html__('Loader Types', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'shopready-elementor-addon'),
                    'custom' => esc_html__('custom', 'shopready-elementor-addon'),
                    'none' => esc_html__('None', 'shopready-elementor-addon'),
                ],

            ]
        );

        $this->add_control(
            'shop_ready_product_quickview_preloader_icon',
            [
                'label' => esc_html__('Gif Loader', 'shopready-elementor-addon'),
                'type' => Custom_Controls_Manager::WRRADIOIMAGE,
                'default' => '',
                'options' => $this->masking_image_shapes(),
                'condition' => [
                    'shop_ready_product_quickview_preloader' => [
                        'default'
                    ]
                ]
            ]
        );

        $this->add_control(
            'shop_ready_product_quickview_preloader_custom',
            [
                'label' => esc_html__('Choose GIF', 'shopready-elementor-addon'),
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition' => [
                    'shop_ready_product_quickview_preloader' => 'custom',
                ],


            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop_ready_product_quickview_preloader__background',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient'],
                'selector' => 'body .woo-ready-product-quickview-modal-preloader-overlay::before',
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'shop_ready_wc_quickview_popup_modal_size',
            [
                'label' => esc_html__('Size', 'shopready-elementor-addon'),
            ]
        );
        $this->add_responsive_control(
            'woo_ready_product_quickview_modal_width',
            [
                'label' => __('Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-quickview-modal' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_quickview_modal_min_width',
            [
                'label' => __('Minimum Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 320,
                ],
                'selectors' => [
                    'body .woo-ready-product-quickview-modal' => 'min-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_quickview_modal_max_width',
            [
                'label' => __('Max Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-quickview-modal' => 'max-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_quickview_modal_height',
            [
                'label' => __('Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-quickview-modal' => 'height: {{SIZE}}{{UNIT}};',

                ],

            ]
        );

        $this->add_responsive_control(
            'woo_ready_product_quickview_modal_min_height',
            [
                'label' => __('Minimum Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
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
                    'body .woo-ready-product-quickview-modal' => 'min-height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'woo_ready_product_quickview_modal_overflow_y',
            [
                'label' => __('Overflow Vertical', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'hidden' => __('None', 'shopready-elementor-addon'),
                    'scroll' => __('Scroll', 'shopready-elementor-addon'),

                ],
                'selectors' => [
                    'body .woo-ready-product-quickview-modal' => 'overflow-y: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function theme_fixing()
    {
        $this->start_controls_section(
            'shop_ready_theme_template_override_section',
            [
                'label' => esc_html__('Theme Template Override', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'shop_ready_theme_template_override_enable',
            [
                'label' => esc_html__('Enable?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }


    /**
     * WooCommerce global Notice 
     * Cart , Checkout where missing notice widgets by Shop ready
     * @see https://docs.woocommerce.com/document/woocommerce-shortcodes/#section-21
     */
    public function global_wc_notice()
    {

        $this->start_controls_section(
            'woo_ready_wc_global_gen_notice',
            [
                'label' => esc_html__('WooCommerce Notice', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );
        // .woocommerce-notices-wrapper
        // .woocommerce-error
        // .woocommerce-message

        $this->start_controls_tabs(
            'woo_ready_notice_glb_style_tab'
        );

        $this->start_controls_tab(
            'woo_ready_notice_success_message_tab',
            [
                'label' => esc_html__('Success', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_glolal_wc_icon_color_mtd_color',
            [
                'label' => esc_html__('Icon Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce-message::before' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->add_control(
            'woo_ready_glolal_wc_mtd_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce-message' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_glolal_wc_mtd__link_color',
            [
                'label' => esc_html__('link Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce-message a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_content_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-message',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-message',
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd__border__radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.woocommerce-message' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd__margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woocommerce-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd__padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woocommerce-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woocommerce-message',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'woo_ready_global_wc_succ_content_box_shadow',
                'label' => esc_html__('Box Shadow', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-message',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_notice_success_button_message_tab',
            [
                'label' => esc_html__('Button', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_glolal_wc_mtd_button_link_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link' => 'color: {{VALUE}}',
                ],
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_content_button_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_button_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link',
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd__border_button_radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd_button_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd_button_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_button_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_notice_success_button_message_hover_tab',
            [
                'label' => esc_html__('Button Hover', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_glolal_wc_mtd_button_hover_link_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_content_button_hover_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_button_hover_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover',
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd__border_button_hover_radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd_button_hover_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd_button_hover_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_hover_button_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_notice_eror_list_tab',
            [
                'label' => esc_html__('Error msg', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_glolal_wc_err_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce-error' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'woo_ready_global_wc_err_content_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-error',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'woo_ready_global_wc_err_content_box_shadow',
                'label' => __('Box Shadow', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-error',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'woo_ready_global_wc_err_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-error',
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_err__border__radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.woocommerce-error' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_err__margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woocommerce-error' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd_err_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woocommerce-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtderr_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woocommerce-error',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_notice_wrappers___tab',
            [
                'label' => esc_html__('Wrapper', 'shopready-elementor-addon'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'woo_ready_global_wc_wrapper_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-notices-wrapper:not(:empty)',
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_wrapper__border__radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.woocommerce-notices-wrapper:not(:empty)' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_wrapper__margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woocommerce-notices-wrapper:not(:empty)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_ready_global_wc_mtd_wrapper_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '.woocommerce-notices-wrapper:not(:empty)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'woo_ready_global_wc_mtd_wrapper_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '.woocommerce-notices-wrapper:not(:empty)',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'woo_ready_global_wc_wrapper_content_box_shadow',
                'label' => esc_html__('Box Shadow', 'shopready-elementor-addon'),
                'selector' => '.woocommerce-notices-wrapper:not(:empty)',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }
    public function common_button()
    {

        $this->start_controls_section(
            'shop_ready_wc_comon_style',
            [
                'label' => esc_html__('Buttons', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );


        $this->start_controls_tabs(
            'shop_ready_common_settings___style_tab'
        );

        $this->start_controls_tab(
            'shop_ready_common_button_n_style_tab',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );


        $this->add_control(
            'shop_ready_common_wc_button_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link , .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'shop_ready_common_button_mtd_content_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'shop_ready_common_button_wc_mtd_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button',
            ]
        );

        $this->add_control(
            'shop_ready_common_button__border__radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_button_mtd__margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_button_wc_mtd__padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop_ready_common_button_wc_mtd_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'shop_ready_common_button_wc_succ_content_box_shadow',
                'label' => esc_html__('Box Shadow', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link,.woocommerce input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'shop_ready_common_button_button_hover_tab',
            [
                'label' => esc_html__('Hover', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'shop_ready_common_wc_mtd_button_link_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover' => 'color: {{VALUE}}',
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'shop_ready_common_wc_mtd_content_button_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'shop_ready_common_wc_wc_mtd_button_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover',
            ]
        );

        $this->add_control(
            'shop_ready_common_wcwc_mtd__border_button_radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_wc_mtd_button_margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_wc_mtd_button_padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop_ready_common_wc_mtd_button_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => 'body .woocommerce-product-page-notice-wrapper .woocommerce-message .shop-rady-cart-view-link:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }
    public function empty_cart()
    {

        $this->start_controls_section(
            'shop_ready_wc_comon_empty_cart_style',
            [
                'label' => esc_html__('Empty Cart', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );


        $this->start_controls_tabs(
            'shop_ready_common_settings_empty_cart__style_tab'
        );

        $this->start_controls_tab(
            'shop_ready_common_empty_cart_icon_n_style_tab',
            [
                'label' => esc_html__('Icon & Text', 'shopready-elementor-addon'),
            ]
        );


        $this->add_control(
            'shop_ready_common_empty_cart_icon_color',
            [
                'label' => esc_html__('Icon Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info::before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_empty_cart_text_color',
            [
                'label' => esc_html__('Text Color', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'shop_ready_common_cart_empty_text_mtd_content_typography',
                'label' => esc_html__('Typography', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce .cart-empty.woocommerce-info',
            ]
        );

        $this->add_responsive_control(
            'shop_ready_common_cart_empty_text_mtd_content__text_element',
            [
                'label' => esc_html__('Text Alignment', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [

                    'left' => esc_html__('Left', 'shopready-elementor-addon'),
                    'right' => esc_html__('Right', 'shopready-elementor-addon'),
                    'center' => esc_html__('Center', 'shopready-elementor-addon'),
                    'inherit' => esc_html__('Inherit', 'shopready-elementor-addon'),

                ],

                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info' => 'text-align: {{VALUE}};',

                ],
            ]

        );

        $this->add_responsive_control(
            'shop_ready_common_cart_empty_text_mtd_content_left_icon_position',
            [
                'label' => esc_html__('Icon Position Left', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info::before' => 'left: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'shop_ready_common_cart_empty_text_mtd_content_right_icon_position',
            [
                'label' => esc_html__('Icon Position Right', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info::before' => 'right: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'shop_ready_common_cart_empty_text_mtd_content_top_icon_position',
            [
                'label' => esc_html__('Icon Position Top', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -3000,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info::before' => 'top: {{SIZE}}{{UNIT}};',

                ],
            ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab(
            'shop_ready_common_empty_cart_container_n_style_tab',
            [
                'label' => esc_html__('Container', 'shopready-elementor-addon'),
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'shop_ready_common_empty_cart_container_bgcolor',
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => 'body .woocommerce .cart-empty.woocommerce-info',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'shop_ready_common_empty_cart_containerbos_shadowr',
                'label' => esc_html__('Box Shadow', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce .cart-empty.woocommerce-info',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'shop_ready_common_empty_cart_etxt_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => 'body .woocommerce .cart-empty.woocommerce-info',
            ]
        );

        $this->add_control(
            'shop_ready_common_empty_cart_text_border__radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info' => 'border-radius : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_empty_cart_etxt_mtd__margin',
            [
                'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shop_ready_common_empty_cart_text_wc_mtd__padding',
            [
                'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    'body .woocommerce .cart-empty.woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }
    /**
     * Should check for the current action to avoid infinite loop
     * when updating options like: "wr_login_redirect" and "wr_login_redirect_enable".
     */
    public function on_save($data)
    {

        if (
            !isset($data['settings'])
        ) {
            return;
        }

        $template_override = 'shop_ready_theme_template_override_enable';

        if (isset($data['settings'][$template_override])) {
            update_option($template_override, 'no');
        } else {
            update_option($template_override, 'yes');
        }

    }

    public function get_additional_tab_content()
    {
    }


}
