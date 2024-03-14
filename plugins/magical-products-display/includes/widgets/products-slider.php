<?php

/**
 * Slider widget class
 *
 * @package Magical addons
 */

defined('ABSPATH') || die();

use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;


class mgProducts_slider extends \Elementor\Widget_Base
{

    use mpdProHelpLink;
    /**
     * Get widget name.
     *
     * Retrieve Blank widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'mg_products_slider';
    }

    /**
     * Get widget title.
     *
     * Retrieve Blank widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return __('MPD Product Slider', 'magical-products-display');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Blank widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-post-slider';
    }

    public function get_keywords()
    {
        return ['slider', 'Product', 'woo', 'carousel', 'mpd'];
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Blank widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['mpd-productwoo'];
    }
    /**
     * Retrieve the list of scripts the image comparison widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return [
            'mpd-swiper',
            'mgproducts-slider'
        ];
    }

    /**
     * Retrieve the list of styles the image comparison widget depended on.
     *
     * Used to set styles dependencies required to run the widget.
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     */
    public function get_style_depends()
    {
        return [
            'mgproducts-style',
            'mpd-swiper',
        ];
    }


    /**
     * Register Blank widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Register Blank widget content ontrols.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    function register_content_controls()
    {
        $this->start_controls_section(
            'mgps_slider_section',
            [
                'label' => __('Slides', 'magical-products-display'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'mgps_spid',
            [
                'label' => __('Select Product for slide', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'options' => mgproducts_display_product_name(),

            ]
        );
        $repeater->add_control(
            'mgpdeg_pimg_show',
            [
                'label'     => __('Show Product Image', 'magical-products-display'),
                'description'     => __('You can show or hide product image.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $repeater->add_control(
            'mgps_img_position',
            [
                'label' => __('Product Image Position', 'magical-products-display'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default'     => 'right',


            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $repeater->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'mgps_bg_color',
                    'label' => esc_html__('Background', 'magical-products-display'),
                    'selector' => '{{WRAPPER}} .mgpds-item{{CURRENT_ITEM}}',
                ]
            );
        } else {
            $repeater->add_control(
                'mgps_bgtype_color',
                [
                    'label' => __('Background Color', 'magical-products-display'),
                    'description' => esc_html__('Background image and gradient support in the pro version', 'magical-products-display'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .mgpds-item{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                    ],

                ]
            );
        }
        $repeater->add_control(
            'mgps_extra_cattext',
            [
                'label'  => sprintf('%s %s', __('Extra Category Text', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'       => __('Keep empty for the original Product category', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'label_block'  => true,
                'default'     => '',

            ]
        );
        $repeater->add_control(
            'mgps_extra_title',
            [
                'label'  => sprintf('%s %s', __('Extra Title', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'       => __('Keep empty for the original Product title', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'label_block'  => true,
                'default'     => '',

            ]
        );

        $repeater->add_control(
            'mgps_extra_desc',
            [
                'label'  => sprintf('%s %s', __('Extra description', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'  => __('Keep empty for the original Product description', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 5,
                'label_block'  => true,
                'default'     => '',

            ]
        );
        $repeater->add_control(
            'mgps_custom_url',
            [
                'label'  => sprintf('%s %s', __('Custom URL', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'       => __('Keep empty for the original Product URL', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'url',
                'label_block'  => true,
                'default'     => '',

            ]
        );

        $repeater->add_responsive_control(
            'mgps_content_align',
            [
                'label' => __('Alignment', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-text-align-right',
                    ],

                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mgpds-item{{CURRENT_ITEM}} .mgpds-sliderbg .mgpds-ptext' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgps_slides',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '<# print("Product slide item"); #>',
                'default' => []
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'vsk_slider_content_sec',
            [
                'label' => __('Slider content', 'magical-products-display'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mgps_content_show',
            [
                'label' => __('Show Slider content?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mgps_title_cat',
            [
                'label' => __('Show Category?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'mgps_content_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgps_title_show',
            [
                'label' => __('Show title?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'mgps_content_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpg_crop_title',
            [
                'label'   => __('Crop Title By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 5,
                'condition' => [
                    'mgps_title_show' => 'yes',
                    'mgps_content_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgps_desc_show',
            [
                'label' => __('Show Description?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'mgps_content_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgpg_crop_desc',
            [
                'label'   => __('Crop Description By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 20,
                'condition' => [
                    'mgps_desc_show' => 'yes',
                    'mgps_content_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgps_btn_show',
            [
                'label' => __('Show Button?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'mgps_content_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mgps_btn_text',
            [
                'label' => __('Button text', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Buy Now', 'magical-products-display'),
                'condition' => [
                    'mgps_content_show' => 'yes',
                    'mgps_btn_show' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mgps_navdots_section',
            [
                'label' => __('Nav & Dots', 'magical-products-display'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mgps_dots',
            [
                'label' => __('Slider Dots?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'mgps_navigation',
            [
                'label' => __('Slider Navigation?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgps_nav_prev_icon',
            [
                'label' => __('Choose Prev Icon', 'magical-products-display'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-left',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'arrow-alt-circle-left',
                        'arrow-circle-left',
                        'arrow-left',
                        'long-arrow-alt-left',
                        'angle-left',
                        'chevron-circle-left',
                        'fa-chevron-left',
                        'angle-double-left',
                    ],
                    'fa-regular' => [
                        'hand-point-left',
                        'arrow-alt-circle-left',
                        'caret-square-left',
                    ],
                ],
                'condition' => [
                    'mgps_navigation' => 'yes',
                ],

            ]
        );
        $this->add_control(
            'mgps_nav_next_icon',
            [
                'label' => __('Choose Next Icon', 'magical-products-display'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'arrow-alt-circle-right',
                        'arrow-circle-right',
                        'arrow-right',
                        'long-arrow-alt-right',
                        'angle-right',
                        'chevron-circle-right',
                        'fa-chevron-right',
                        'angle-double-right',
                    ],
                    'fa-regular' => [
                        'hand-point-right',
                        'arrow-alt-circle-right',
                        'caret-square-right',
                    ],
                ],
                'condition' => [
                    'mgps_navigation' => 'yes',
                ],

            ]
        );


        $this->end_controls_section();
        $this->start_controls_section(
            'mgps_settings_section',
            [
                'label' => __('Settings', 'magical-products-display'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $seffects = [
                'fade' => __('fade', 'magical-products-display'),
                'slide' => __('Slide', 'magical-products-display'),
                'cube' => __('Cube', 'magical-products-display'),
                'flip' => __('Flip', 'magical-products-display'),
                'coverflow' => __('Coverflow', 'magical-products-display'),
            ];
            $stext_ffects = [
                'n' => __('No Animation', 'magical-products-display'),
                'y' => __('Animation One', 'magical-products-display'),
                'x' => __('Animation Two', 'magical-products-display'),
                'scale' => __('Animation Three', 'magical-products-display'),
                'opacity' => __('Animation Four', 'magical-products-display'),
            ];
            $simg_ffects = [
                'n' => __('No Animation', 'magical-products-display'),
                'x' => __('Animation One', 'magical-products-display'),
                'y' => __('Animation Two', 'magical-products-display'),
                'scale' => __('Animation Three', 'magical-products-display'),
                'opacity' => __('Animation Four', 'magical-products-display'),
            ];
        } else {
            $seffects = [
                'fade' => __('fade', 'magical-products-display'),
                'slide' => __('Slide', 'magical-products-display'),
                'slide' => sprintf('%s %s', __('cube', 'magical-products-display'), mpd_display_pro_only_text()),
                'slide' => sprintf('%s %s', __('Flip', 'magical-products-display'), mpd_display_pro_only_text()),
                'slide' => sprintf('%s %s', __('coverflow', 'magical-products-display'), mpd_display_pro_only_text()),

            ];
            $stext_ffects = [
                'n' => __('No Animation', 'magical-products-display'),
                'y' => __('Animation One', 'magical-products-display'),
                'x'
                => sprintf('%s %s', __('Animation Two', 'magical-products-display'), mpd_display_pro_only_text()),
                'scale' =>
                sprintf('%s %s', __('Animation Three', 'magical-products-display'), mpd_display_pro_only_text()),
                'opacity' =>
                sprintf('%s %s', __('Animation Four', 'magical-products-display'), mpd_display_pro_only_text()),
            ];
            $simg_ffects = [
                'n' => __('No Animation', 'magical-products-display'),
                'x' => __('Animation One', 'magical-products-display'),
                'y'
                => sprintf('%s %s', __('Animation Two', 'magical-products-display'), mpd_display_pro_only_text()),
                'scale' =>
                sprintf('%s %s', __('Animation Three', 'magical-products-display'), mpd_display_pro_only_text()),
                'opacity' =>
                sprintf('%s %s', __('Animation Four', 'magical-products-display'), mpd_display_pro_only_text()),
            ];
        }
        $this->add_control(
            'mgps_slide_effect',
            [
                'label' => __('Slide Effect', 'magical-products-display'),
                'type' => Controls_Manager::SELECT,
                'options' => $seffects,
                'default' => 'fade',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );
        $this->add_control(
            'mgps_animation_speed',
            [
                'label' => __('Animation Speed', 'magical-products-display'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 1,
                'max' => 10000,
                'default' => 1000,
                'description' => __('Slide speed in milliseconds', 'magical-products-display'),
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'mgps_text_effect',
            [
                'label' => __('Text Animation', 'magical-products-display'),
                'type' => Controls_Manager::SELECT,
                'options' => $stext_ffects,
                'default' => 'y',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );
        $this->add_control(
            'mgps_pimg_effect',
            [
                'label' => __('Product Image Animation', 'magical-products-display'),
                'type' => Controls_Manager::SELECT,
                'options' => $simg_ffects,
                'default' => 'x',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $sdirection = [
                'horizontal' => __('Horizontal', 'magical-products-display'),
                'vertical' => __('vertical', 'magical-products-display'),
            ];
        } else {
            $sdirection = [
                'horizontal' => __('Horizontal', 'magical-products-display'),
                'horizont' => sprintf('%s %s', __('vertical', 'magical-products-display'), mpd_display_pro_only_text()),

            ];
        }
        $this->add_control(
            'mgps_slide_direction',
            [
                'label' => __('Slide Direction', 'magical-products-display'),
                'type' => Controls_Manager::SELECT,
                'options' => $sdirection,
                'default' => 'horizontal',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'mgps_autoplay',
            [
                'label' => __('Autoplay?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'mgps_autoplay_delay',
            [
                'label' => __('Autoplay Delay', 'magical-products-display'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 1,
                'max' => 50000,
                'default' => 2500,
                'description' => __('Autoplay Delay in milliseconds', 'magical-products-display'),
                'frontend_available' => true,
                'condition' => [
                    'mgps_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mgps_autoplay_speed',
            [
                'label' => __('Autoplay Speed', 'magical-products-display'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'max' => 10000,
                'default' => 3000,
                'description' => __('Autoplay speed in milliseconds', 'magical-products-display'),
                'condition' => [
                    'autoplay' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'mgps_loop',
            [
                'label' => __('Infinite Loop?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'mgps_grab_cursor',
            [
                'label' => __('Grab Cursor?', 'magical-products-display'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );



        $this->end_controls_section();
        $this->link_pro_added();
    }

    /**
     * Register Blank widget style ontrols.
     *
     * Adds different input fields in the style tab to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_style_controls()
    {
        $this->start_controls_section(
            'mgps_style_section',
            [
                'label' => __('Slider Item', 'magical-products-display'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'mgps_slide_height',
            [
                'label' => __('Slider Height', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpd-slider, {{WRAPPER}} .swiper-container-vertical' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'mgps_item_border',
                'selector' => '{{WRAPPER}} .mgpd-slider .mgpds-item',
            ]
        );

        $this->add_responsive_control(
            'mgps_item_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpd-slider .mgpds-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgps_item_shadow',
                'selector' => '{{WRAPPER}} .mgpd-slider',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mgpdeg_img_style',
            [
                'label' => __('Image style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_width_set',
            [
                'label' => __('Width', 'magical-products-display'),
                'type' =>  \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'desktop_default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-img-after,{{WRAPPER}} .mgpds-img-before' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'my_slider',
            [
                'label' => esc_html__('Product Image Opacity', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-img-after img,{{WRAPPER}} .mgpds-img-before img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-products-display'),
                'label_off' => __('Off', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpdeg_img_height',
            [
                'label' => __('Image Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'mgpdeg_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-img-after img,{{WRAPPER}} .mgpds-img-before img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-img-after,{{WRAPPER}} .mgpds-img-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_img_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-img-after,{{WRAPPER}} .mgpds-img-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-img-after,{{WRAPPER}} .mgpds-img-before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdeg_img_bgcolor',
                'label' => esc_html__('Background', 'magical-products-display'),
                //'types' => [ 'classic', 'gradient' ],

                'selector' => '{{WRAPPER}} .mgpds-img-after,{{WRAPPER}} .mgpds-img-before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_img_border',
                'selector' => '{{WRAPPER}} .mgpds-img-after,{{WRAPPER}} .mgpds-img-before',
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'mgps_style_content',
            [
                'label' => __('Slider Content style', 'magical-products-display'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgps_content_padding',
            [
                'label' => __('Content Padding', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content.mgpds-pdetails .mgpds-ptext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgps_content_margin',
            [
                'label' => __('Content Margin', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content.mgpds-pdetails .mgpds-ptext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mgps_content_background',
                'selector' => '{{WRAPPER}} .mgpds-content.mgpds-pdetails .mgpds-ptext',
                'exclude' => [
                    'image'
                ]
            ]
        );
        $this->add_responsive_control(
            'mgps_content_radius',
            [
                'label' => __('Content Border Radius', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content.mgpds-pdetails .mgpds-ptext' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'mgps_cat_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Category', 'magical-products-display'),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'mgps_cat_spacing',
            [
                'label' => __('Bottom Spacing', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} span.slide-cat' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_cat_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.slide-cat a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mgps_cat_background',
                'selector' => '{{WRAPPER}} span.slide-cat a',
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'mgps_cat_typo',
                'selector' => '{{WRAPPER}} span.slide-cat a',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'mgps_cat_shadow',
                'label' => __('Title Text Shadow', 'plugin-domain'),
                'selector' => '{{WRAPPER}} span.slide-cat a',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'mgps_cat_border',
                'selector' => '{{WRAPPER}} span.slide-cat a',
            ]
        );
        $this->add_responsive_control(
            'mgps_cat_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.slide-cat a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgps_cat_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.slide-cat a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgps_heading_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Title', 'magical-products-display'),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'mgps_title_spacing',
            [
                'label' => __('Bottom Spacing', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content .mgps-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_title_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content .mgps-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'mgps_title_typo',
                'selector' => '{{WRAPPER}} .mgpds-content .mgps-title',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'mgps_title_shadow',
                'label' => __('Title Text Shadow', 'plugin-domain'),
                'selector' => '{{WRAPPER}} .mgpds-content .mgps-title',
            ]
        );

        $this->add_control(
            'mgps_heading_subtitle',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Subtitle', 'magical-products-display'),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'mgps_subtitle_spacing',
            [
                'label' => __('Bottom Spacing', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content .mgps-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_subtitle_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpds-content .mgps-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'mgps_subtitle',
                'selector' => '{{WRAPPER}} .mgpds-content .mgps-subtitle',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'mgps_subtitle_shadow',
                'label' => __('Title Text Shadow', 'plugin-domain'),
                'selector' => '{{WRAPPER}} .mgpds-content .mgps-subtitle',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mgps_btn_style',
            [
                'label' => __('Slider Button', 'magical-products-display'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgps_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgps_btn_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgps_btn_typography',
                'selector' => '{{WRAPPER}} a.btn.vbs-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgps_btn_border',
                'selector' => '{{WRAPPER}} a.btn.vbs-btn',
            ]
        );

        $this->add_control(
            'mgps_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgps_btn_box_shadow',
                'selector' => '{{WRAPPER}} a.btn.vbs-btn',
            ]
        );
        $this->add_control(
            'mgps_btn_color',
            [
                'label' => __('Button color', 'magical-products-display'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('mgps_btn_tabs');

        $this->start_controls_tab(
            'mgps_btn_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgps_text_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgps_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgps_btn_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgps_btn_hover_boxshadow',
                'selector' => '{{WRAPPER}} a.btn.vbs-btn:hover',
            ]
        );

        $this->add_responsive_control(
            'mgps_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn:hover, {{WRAPPER}} a.btn.vbs-btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn:hover, {{WRAPPER}} a.btn.vbs-btn:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'mgps_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} a.btn.vbs-btn:hover, {{WRAPPER}} a.btn.vbs-btn:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'mgps_section_style_arrow',
            [
                'label' => __('Navigation - Arrow', 'magical-products-display'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mgps_arrow_position_toggle',
            [
                'label' => __('Position', 'magical-products-display'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'magical-products-display'),
                'label_on' => __('Custom', 'magical-products-display'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'mgps_arrow_positiony',
            [
                'label' => __('Vertical', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                // 'condition' => [
                //     'arrow_position_toggle' => 'yes'
                // ],
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 500,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next,{{WRAPPER}} .swiper-button-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_arrow_position_x',
            [
                'label' => __('Horizontal', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                // 'condition' => [
                //     'arrow_position_toggle' => 'yes'
                // ],
                'range' => [
                    'px' => [
                        'min' => -10,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-container-rtl .swiper-button-next' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-button-next,{{WRAPPER}} .swiper-container-rtl .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();
        $this->add_responsive_control(
            'mgps_arrow_icon_size',
            [
                'label' => __('Nav Icon Size', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpd-slider .swiper-button-next i,{{WRAPPER}} .mgpd-slider .swiper-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mgpd-slider .swiper-button-next svg,{{WRAPPER}} .mgpd-slider .swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgps_arrow_border',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};width:inherit;height:inherit',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'mgps_arrow_border',
                'selector' => '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev',
            ]
        );

        $this->add_responsive_control(
            'mgps_arrow_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs('mgps_tabs_arrow');

        $this->start_controls_tab(
            'mgps_tab_arrow_normal',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgps_arrow_color',
            [
                'label' => __('Icon Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev i, {{WRAPPER}} .swiper-button-next i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mgpd-slider .swiper-button-next svg,{{WRAPPER}} .mgpd-slider .swiper-button-prev svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_arrow_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgps_tab_arrow_hover',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgps_arrow_hover_color',
            [
                'label' => __('Icon Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover i, {{WRAPPER}} .swiper-button-next:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mgpd-slider .swiper-button-next:hover svg,{{WRAPPER}} .mgpd-slider .swiper-button-prev:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgps_arrow_hover_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgps_arrow_hover_border_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'arrow_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'mgps_section_style_dots',
            [
                'label' => __('Navigation - Dots', 'magical-products-display'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgps_dots_position_y',
            [
                'label' => __('Vertical Position', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-horizontal>.swiper-pagination-bullets, {{WRAPPER}} .swiper-pagination-custom, {{WRAPPER}} .swiper-pagination-fraction' => 'bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-container-vertical>.swiper-pagination-bullets' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_dots_spacing',
            [
                'label' => __('Spacing', 'magical-products-display'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .swiper-container-vertical>.swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgps_dots_nav_align',
            [
                'label' => __('Alignment', 'magical-products-display'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'mgps_slide_direction' => 'horizontal',
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-horizontal>.swiper-pagination-bullets, .swiper-pagination-custom, {{WRAPPER}} .swiper-pagination-fraction' => 'text-align: {{VALUE}}'
                ]
            ]
        );
        $this->add_control(
            'mgps_dots_width',
            [
                'label' => __('Dots Width', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgps_dots_height',
            [
                'label' => __('Dots Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgps_dots_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->start_controls_tabs('mgps_tabs_dots');
        $this->start_controls_tab(
            'mgps_tab_dots_normal',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgps_dots_nav_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgps_tab_dots_hover',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgps_dots_nav_hover_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgps_tab_dots_active',
            [
                'label' => __('Active', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgps_dots_nav_active_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render Blank widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $mgps_slides = $this->get_settings('mgps_slides');
        $mgps_autoplay = $settings['mgps_autoplay'] ? 'true' : 'false';
        $mgps_loop = $settings['mgps_loop'] ? 'true' : 'false';
        $mgps_grab_cursor = $settings['mgps_grab_cursor'] ? 'true' : 'false';
        $mgps_dots = $settings['mgps_dots'] ? 'true' : 'false';
        $mgps_navigation = $settings['mgps_navigation'] ? 'true' : 'false';
        $mgps_slide_direction = $settings['mgps_slide_direction'];
        if ($mgps_slide_direction == 'horizontal' || $mgps_slide_direction == 'vertical') {
            $mgps_slide_direction = $mgps_slide_direction;
        } else {
            $mgps_slide_direction = 'horizontal';
        }
?>

        <div class="mgpd-slider swiper-container" data-loop="<?php echo esc_attr($mgps_loop); ?>" data-effect="<?php echo esc_attr($settings['mgps_slide_effect']); ?>" data-direction="<?php echo esc_attr($mgps_slide_direction); ?>" data-speed="<?php echo esc_attr($settings['mgps_animation_speed']); ?>" data-autoplay="<?php echo esc_attr($mgps_autoplay); ?>" data-auto-delay="<?php echo esc_attr($settings['mgps_autoplay_delay']); ?>" data-grab-cursor="<?php echo esc_attr($mgps_grab_cursor); ?>" data-nav="<?php echo esc_attr($mgps_navigation); ?>" data-dots="<?php echo esc_attr($mgps_dots); ?>">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">

                <?php
                // Query Argument
                $args = array(
                    'post_type'             => 'product',
                    'post_status'           => 'publish',
                    'ignore_sticky_posts'   => 1,
                    'posts_per_page'        => 1,
                );



                if ($mgps_slides) :
                    foreach ($mgps_slides as $slide) :

                        // Query Argument
                        $args['p'] = $slide['mgps_spid'];

                        $mgpdslide_posts = new WP_Query($args);

                ?>


                        <?php
                        while ($mgpdslide_posts->have_posts()) : $mgpdslide_posts->the_post();

                            $terms = get_the_terms(get_the_ID(), 'product_cat');
                            if ($terms) {
                                $mgshop_sp_cat = $terms[mt_rand(0, count($terms) - 1)];
                            } else {
                                $mgshop_sp_cat = '';
                            }

                        ?>

                            <!-- Slides -->
                            <div class="swiper-slide mgpd-slide mgpds-item elementor-repeater-item-<?php echo esc_attr($slide['_id']) ?>">
                                <div class="mgpds-sliderbg">

                                    <?php if ($settings['mgps_content_show'] || has_post_thumbnail()) : ?>
                                        <div class="mgpds-content mgpds-pdetails">
                                            <?php if (has_post_thumbnail() && ($slide['mgps_img_position'] == 'left') && $slide['mgpdeg_pimg_show']) : ?>
                                                <div class="mgpds-img-after" data-swiper-parallax-<?php echo esc_attr($settings['mgps_pimg_effect']); ?>="300" data-swiper-parallax-duration="600">
                                                    <?php the_post_thumbnail($settings['thumbnail_size'], array('class' => 'mgpds-img')); ?>
                                                </div>
                                            <?php endif; // image check 
                                            ?>

                                            <?php if ($settings['mgps_content_show']) : ?>
                                                <div class="mgpds-ptext">
                                                    <?php if ($slide['mgps_extra_cattext'] && get_option('mgppro_is_active') == 'yes') : ?>
                                                        <span class="slide-cat" data-swiper-parallax-<?php echo esc_attr($settings['mgps_text_effect']); ?>="-300" data-swiper-parallax-duration="400">
                                                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                                                <?php echo esc_html($slide['mgps_extra_cattext']); ?>
                                                            </a>
                                                        </span>
                                                    <?php elseif ($mgshop_sp_cat && $settings['mgps_title_cat']) : ?>
                                                        <span class="slide-cat" data-swiper-parallax-<?php echo esc_attr($settings['mgps_text_effect']); ?>="-300" data-swiper-parallax-duration="400">
                                                            <a href="<?php echo esc_url(get_category_link($mgshop_sp_cat)); ?>">
                                                                <?php echo esc_html($mgshop_sp_cat->name); ?>
                                                            </a>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($settings['mgps_title_show']) : ?>
                                                        <h2 class="mgps-title" data-swiper-parallax-<?php echo esc_attr($settings['mgps_text_effect']); ?>="-300" data-swiper-parallax-duration="600">
                                                            <?php
                                                            if ($slide['mgps_extra_title'] && get_option('mgppro_is_active') == 'yes') {
                                                                echo esc_html($slide['mgps_extra_title']);
                                                            } else {
                                                                echo esc_html(wp_trim_words(get_the_title(), $settings['mgpg_crop_title'], ''));
                                                            }

                                                            ?>
                                                        </h2>
                                                    <?php endif; //title end 
                                                    ?>
                                                    <?php if ($settings['mgps_desc_show']) : ?>
                                                        <p class="mgps-subtitle" data-swiper-parallax-<?php echo esc_attr($settings['mgps_text_effect']); ?>="-300" data-swiper-parallax-duration="800">
                                                            <?php
                                                            if ($slide['mgps_extra_desc'] && get_option('mgppro_is_active') == 'yes') {
                                                                echo esc_html($slide['mgps_extra_desc']);
                                                            } else {
                                                                echo esc_html(wp_trim_words(get_the_content(), $settings['mgpg_crop_desc'], ''));
                                                            }

                                                            ?></p>
                                                    <?php endif; //subtitle end 
                                                    ?>
                                                    <?php
                                                    if ($settings['mgps_btn_show']) :
                                                        if ($slide['mgps_custom_url'] && get_option('mgppro_is_active') == 'yes') {
                                                            $btnurl = $slide['mgps_custom_url'];
                                                        } else {
                                                            $btnurl = get_the_permalink();
                                                        }

                                                    ?>
                                                        <div data-swiper-parallax-<?php echo esc_attr($settings['mgps_text_effect']); ?>="-300" data-swiper-parallax-duration="1000">
                                                            <a href="<?php echo esc_url($btnurl); ?>" class="btn vbs-btn"><?php echo esc_html($settings['mgps_btn_text']); ?></a>
                                                        </div>
                                                    <?php endif; //button ene 
                                                    ?>
                                                </div>
                                            <?php endif; //mgpds-ptext end 
                                            ?>
                                            <?php if (has_post_thumbnail() && ($slide['mgps_img_position'] == 'right') && $slide['mgpdeg_pimg_show']) : ?>
                                                <div class="mgpds-img-before" data-swiper-parallax-<?php echo esc_attr($settings['mgps_pimg_effect']); ?>="-300" data-swiper-parallax-duration="600">
                                                    <?php the_post_thumbnail($settings['thumbnail_size'], array('class' => 'mgpds-img')); ?>
                                                </div>
                                            <?php endif; // image check 
                                            ?>
                                        </div>
                                    <?php endif; //content end 
                                    ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_query();
                        wp_reset_postdata();
                    endforeach;
                    ?>


            </div>


            <?php if ($settings['mgps_dots']) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>

            <?php if ($settings['mgps_navigation']) : ?>
                <div class="swiper-button-prev">
                    <?php \Elementor\Icons_Manager::render_icon($settings['mgps_nav_prev_icon']); ?>
                </div>
                <div class="swiper-button-next">
                    <?php \Elementor\Icons_Manager::render_icon($settings['mgps_nav_next_icon']); ?>
                </div>
            <?php endif; ?>
        <?php
                else : // loop $arg check
        ?>
            <div class="alert alert-danger text-center">
                <?php echo esc_html('Please select products for display the Slider.'); ?>
            </div>
        <?php
                endif; // loop $arg check
        ?>

        <!-- If we need scrollbar 
    <div class="swiper-scrollbar"></div>
    -->
        </div>
<?php



    }

    public function content_template()
    {
    }
}
