<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliSlider extends Elementinvader_Base {

    // Default widget settings
    public $view_folder = 'slider';

    public function __construct($data = array(), $args = null) {

        \Elementor\Controls_Manager::add_tab(
                'tab_settings',
                esc_html__('Settings', 'elementinvader-addons-for-elementor')
        );

        \Elementor\Controls_Manager::add_tab(
                'tab_options',
                esc_html__('Options', 'elementinvader-addons-for-elementor')
        );

        \Elementor\Controls_Manager::add_tab(
                'tab_styles',
                esc_html__('Styles', 'elementinvader-addons-for-elementor')
        );

        \Elementor\Controls_Manager::add_tab(
                'tab_content',
                esc_html__('Main', 'elementinvader-addons-for-elementor')
        );


        wp_enqueue_style('slick', plugins_url('/assets/libs/slick/slick.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        wp_enqueue_style('slick-theme', plugins_url('/assets/libs/slick/slick-theme.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        wp_enqueue_script('slick', plugins_url('/assets/libs/slick/slick.min.js', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
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
        return 'eli-slider';
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
        return esc_html__('Eli Slider', 'elementinvader-addons-for-elementor');
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
        return 'eicon-featured-image';
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

        /* START Section settings_source */
        if(true){
            $this->start_controls_section(
                'tab_settings_section_basic',
                [
                    'label' => esc_html__('Basic', 'elementinvader-addons-for-elementor'),
                    'tab' => '1',
                ]
            );

            $this->add_control(
                't_settings_sec_basic_source',
                [
                    'label' => __( 'Source for Sliders', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'  => __( 'Custom', 'elementinvader-addons-for-elementor' ),
                        'posts' => __( 'Posts', 'elementinvader-addons-for-elementor' ),
                    ],
                    'separator' => 'after',
                ]
            );

            /* Type Posts */
            if(true){
                $this->add_control(
                        'tab_settings_section_basic_header_content',
                        [
                            'label' => esc_html__('Query/Results', 'elementinvader-addons-for-elementor'),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 't_settings_sec_basic_source',
                                        'operator' => '==',
                                        'value' => 'posts',
                                    ]
                                ],
                            ],
                        ]
                );

                $this->add_control(
                        'tab_settings_section_basic_header_content_custom',
                        [
                            'label' => esc_html__('Slides and photos', 'elementinvader-addons-for-elementor'),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 't_settings_sec_basic_source',
                                        'operator' => '==',
                                        'value' => 'custom',
                                    ]
                                ],
                            ],
                        ]
                );

                $this->add_control(
                    't_settings_sec_basic_post_limit',
                    [
                        'label' => __( 'Limit Results', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                        'default' => 6,
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 't_settings_sec_basic_source',
                                    'operator' => '==',
                                    'value' => 'posts',
                                ]
                            ],
                        ],
                    ]
                );

                $this->add_control(
                    't_settings_sec_basic_post_type',
                    [
                        'label'         => __('Post Type', 'elementinvader-addons-for-elementor'),
                        'type'          => Controls_Manager::SELECT2,
                        'options'       => $this->ma_el_get_post_types(),
                        'default'       => 'post',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 't_settings_sec_basic_source',
                                    'operator' => '==',
                                    'value' => 'posts',
                                ]
                            ],
                        ],
                    ]
                );
            
                $this->add_control(
                    't_settings_sec_basic_post_order',
                    [
                        'label'         => __('Post Order', 'elementinvader-addons-for-elementor'),
                        'type'          => Controls_Manager::SELECT,
                        'label_block'   => true,
                        'options'       => [
                            'asc'           => __('Ascending', 'elementinvader-addons-for-elementor'),
                            'desc'          => __('Descending', 'elementinvader-addons-for-elementor')
                        ],
                        'default'       => 'desc',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 't_settings_sec_basic_source',
                                    'operator' => '==',
                                    'value' => 'posts',
                                ]
                            ],
                        ],
                    ]
                );

                $this->add_control(
                    't_settings_sec_basic_post_orderby',
                    [
                        'label'         => __('Order By', 'elementinvader-addons-for-elementor'),
                        'type'          => Controls_Manager::SELECT,
                        'label_block'   => true,
                        'options'       => [
                            'none'  => __('None', 'elementinvader-addons-for-elementor'),
                            'ID'    => __('ID', 'elementinvader-addons-for-elementor'),
                            'author' => __('Author', 'elementinvader-addons-for-elementor'),
                            'title' => __('Title', 'elementinvader-addons-for-elementor'),
                            'name'  => __('Name', 'elementinvader-addons-for-elementor'),
                            'date'  => __('Date', 'elementinvader-addons-for-elementor'),
                            'modified' => __('Last Modified', 'elementinvader-addons-for-elementor'),
                            'rand'  => __('Random', 'elementinvader-addons-for-elementor'),
                            'comment_count' => __('Number of Comments', 'elementinvader-addons-for-elementor'),
                            'menu_order ' => __('Field Order ', 'elementinvader-addons-for-elementor'),
                        ],
                        'default'       => 'date',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 't_settings_sec_basic_source',
                                    'operator' => '==',
                                    'value' => 'posts',
                                ]
                            ],
                        ],
                    ]
                );
            }

            if(true) {

                $repeater = new Repeater();
                
                $repeater->start_controls_tabs( 'sliders' );


                $repeater->add_control(
                    'slider_title',
                    [
                        'label' => __( 'Title', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Default title', 'elementinvader-addons-for-elementor' ),
                        'placeholder' => __( 'Type your title here', 'elementinvader-addons-for-elementor' ),
                    ]
                );

                $repeater->add_control(
                    'slider_description',
                    [
                        'label' => __( 'Description', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Default Description', 'elementinvader-addons-for-elementor' ),
                        'placeholder' => __( 'Type your Description here', 'elementinvader-addons-for-elementor' ),
                    ]
                );

                $repeater->add_control(
                    'slider_url',
                    [
                        'label' => __( 'Url link', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'show_external' => true,
                        'default' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                        'placeholder' => __( 'Type your Url link here', 'elementinvader-addons-for-elementor' ),
                    ]
                );

                        
                $repeater->add_control(
                    'slider_image',
                    [
                        'label' => __( 'Choose Image', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ]
                );

                $repeater->add_control(
                    'position', 
                    [
                        'label' => __( 'Position X', 'elementinvader-addons-for-elementor' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            'left' => [
                                    'title' => esc_html__( 'Left', 'elementinvader-addons-for-elementor' ),
                                    'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                    'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                    'icon' => 'eicon-text-align-center',
                            ],
                            'right' => [
                                    'title' => esc_html__( 'Right', 'elementinvader-addons-for-elementor' ),
                                    'icon' => 'eicon-text-align-right',
                            ],
                        ],
                        'render_type' => 'template',
                    ]
                );

                $repeater->end_controls_tabs();
                
                $this->add_control(
                    't_settings_sec_basic_sliders',
                    [
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                        ],
                        'title_field' => '{{{ slider_title }}}',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 't_settings_sec_basic_source',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ]
                            ],
                        ],
                    ]
                );
                     
                /* end form field content */

            }

            $this->end_controls_section();
        }

        /* START Section t_options_slider */
        if(true){
            $this->start_controls_section(
                'tab_options_slider',
                [
                    'label' => esc_html__('Slider options', 'elementinvader-addons-for-elementor'),
                    'tab' => 'tab_options',
                ]
            );

            $this->add_responsive_control(
                'column_gap',
                [
                    'label' => esc_html__('Columns Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-slide' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                    ],
                ]
            );

            $this->add_control(
                't_options_slider_center',
                [
                    'label' => __( 'Center', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
                    'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_control(
                't_options_slider_variableWidth',
                [
                    'label' => __( 'variableWidth', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
                    'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_control(
                't_options_slider_infinite',
                [
                    'label' => __( 'Infinite', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
                    'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
                    'return_value' => 'true',
                    'default' => 'true',
                ]
            );

            $this->add_control(
                't_options_slider_autoplay',
                [
                    'label' => __( 'Autoplay', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
                    'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
                    'return_value' => 'true',
                    'default' => '',
                ]
            );

            $this->add_control(
                'layout_carousel_columns',
                [
                    'label' => __( 'Count grid', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                't_options_slider_speed',
                [
                    'label' => __( 'Speed', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 100000,
                    'step' => 100,
                    'default' => 500,
                ]
            );

            $this->add_control(
                't_options_slider_animation_style',
                [
                    'label' => __( 'Animation Style', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'fade',
                    'options' => [
                        'slide'  => __( 'Slide', 'elementinvader-addons-for-elementor' ),
                        'fade' => __( 'Fade', 'elementinvader-addons-for-elementor' ),
                        'fade_in_in' => __( 'Fade in', 'elementinvader-addons-for-elementor' ),
                    ],
                ]
            );

            $this->add_control(
                't_options_slider_cssease',
                [
                    'label' => __( 'cssEase ', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'linear',
                    'options' => [
                        'linear'  => __( 'linear', 'elementinvader-addons-for-elementor' ),
                        'ease' => __( 'ease', 'elementinvader-addons-for-elementor' ),
                        'ease-in' => __( 'ease-in', 'elementinvader-addons-for-elementor' ),
                        'ease-out' => __( 'ease-out', 'elementinvader-addons-for-elementor' ),
                        'ease-in-out' => __( 'ease-in-out', 'elementinvader-addons-for-elementor' ),
                        'step-start' => __( 'step-start', 'elementinvader-addons-for-elementor' ),
                        'step-end' => __( 'step-end', 'elementinvader-addons-for-elementor' ),
                    ],
                ]
            );

            $this->end_controls_section();
        }

        /* START Section t_options_slider */
        if(true){
            $this->start_controls_section(
                'tab_styles_image',
                [
                    'label' => esc_html__('Section Image', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_responsive_control(
                't_styles_img_des_type',
                [
                    'label' => __( 'Design type ', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'eli_s_image_cover',
                    'options' => [
                        ''  => __( 'Default Sizes', 'elementinvader-addons-for-elementor' ),
                        'eli_s_size_image_cover' => __( 'Image auto crop/resize', 'elementinvader-addons-for-elementor' ),
                        'eli_s_image_cover' => __( 'Image cover (like background)', 'elementinvader-addons-for-elementor' ),
                    ],
                ]
            );

            $this->add_responsive_control(
                't_styles_img_des_height',
                [
                    'label' => esc_html__('Height', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 300,
                            'max' => 1500,
                        ],
                    ],
                    'render_type' => 'template',
                    'default' => [
                        'size' => 350,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_slider .eli_slider_ini.eli_s_image_cover .slick-list,{{WRAPPER}} .eli_slider .eli_slider_ini:not(.slick-initialized),
                         {{WRAPPER}} .eli_slider .eli_slider_ini.eli_s_size_image_cover .eli_s_item .eli_s_item_thumbnail' => 'height: {{SIZE}}px',
                    ],
                    'separator' => 'after',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 't_styles_img_des_type',
                                'operator' => '==',
                                'value' => 'eli_s_size_image_cover',
                            ],
                            [
                                'name' => 't_styles_img_des_type',
                                'operator' => '==',
                                'value' => 'eli_s_image_cover',
                            ]
                        ],
                    ]
                ]
            );
            $this->end_controls_section();

            $this->start_controls_section(
                'tab_styles_arrows_section',
                [
                    'label' => esc_html__('Section Arrows', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_responsive_control(
                't_styles_arrows_hide',
                [
                        'label' => esc_html__( 'Hide Element', 'elementinvader-addons-for-elementor' ),
                        'type' => Controls_Manager::SWITCHER,
                        'none' => esc_html__( 'Hide', 'elementinvader-addons-for-elementor' ),
                        'block' => esc_html__( 'Show', 'elementinvader-addons-for-elementor' ),
                        'return_value' => 'none',
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .eli_slider .eli_slider_arrows' => 'display: {{VALUE}};',
                        ],
                ]
        );

        
            $this->add_responsive_control(
                't_styles_arrows_position',
                [
                    'label' => __( 'Position ', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'eli_slider_arrows_bottom',
                    'options' => [
                        'eli_slider_arrows_bottom'  => __( 'Bottom', 'elementinvader-addons-for-elementor' ),
                        'eli_slider_arrows_middle' => __( 'Center', 'elementinvader-addons-for-elementor' ),
                        'eli_slider_arrows_top' => __( 'Top', 'elementinvader-addons-for-elementor' ),
                    ],
                ]
            );

            $this->add_responsive_control(
                't_styles_arrows_position_style',
                [
                    'label' => __( 'Position Style', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'eli_slider_arrows_out',
                    'options' => [
                        'eli_slider_arrows_out' => __( 'Out', 'elementinvader-addons-for-elementor' ),
                        'eli_slider_arrows_in' => __( 'In', 'elementinvader-addons-for-elementor' ),
                    ],
                ]
            );

            $this->add_responsive_control(
                't_styles_arrows_align',
                [
                    'label' => __( 'Align', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                                'title' => esc_html__( 'Left', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                                'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                                'title' => esc_html__( 'Right', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                                'title' => esc_html__( 'Justified', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'render_type' => 'template',
                    'selectors_dictionary' => [
                        'left' => 'justify-content: flex-start;',
                        'center' => 'justify-content: center;',
                        'right' => 'justify-content: flex-end;',
                        'justify' => 'justify-content: space-between;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_slider .eli_slider_arrows' => '{{VALUE}};',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 't_styles_img_des_type',
                                'operator' => '==',
                                'value' => 'eli_s_size_image_cover',
                            ],
                            [
                                'name' => 't_styles_img_des_type',
                                'operator' => '==',
                                'value' => 'eli_s_image_cover',
                            ]
                        ],
                    ],
                ]
            );
            
            $this->add_responsive_control(
                't_styles_arrows_icon_left_h',
                [
                    'label' => esc_html__('Arrow left', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_arrows .eli_slider_arrow.eli_s_prev',
            ];
            $this->generate_renders_tabs($object, 't_styles_arrows_s_m_left', ['margin']);

            $this->add_responsive_control(
                't_styles_arrows_icon_left',
                [
                    'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default' => [
                        'value' => 'fa fa-angle-left',
                        'library' => 'solid',
                    ],
                ]
            );
                                
            $this->add_responsive_control(
                't_styles_arrows_icon_right_h',
                [
                    'label' => esc_html__('Arrow right', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_arrows .eli_slider_arrow.eli_s_next',
            ];
            $this->generate_renders_tabs($object, 't_styles_arrows_s_m_next', ['margin']);

            $this->add_responsive_control(
                't_styles_arrows_icon_right',
                [
                    'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default' => [
                        'value' => 'fa fa-angle-right',
                        'library' => 'solid',
                    ],
                ]
            );
            
            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_arrows .eli_slider_arrow',
                'hover'=>'{{WRAPPER}} .eli_slider .eli_slider_arrows .eli_slider_arrow%1$s'
            ];
            $this->generate_renders_tabs($object, 't_styles_arrows_s', ['font-size','color','background','border','border_radius','padding','shadow','transition']);
            
            $object = [
                'hover'=>'{{WRAPPER}} .eli_slider .eli_slider_arrows .eli_slider_arrow%1$s i'
            ];
            $this->generate_renders_tabs($object, 't_styles_arrows_hover_s', ['hover_animation']);

            $this->end_controls_section();

            $this->start_controls_section(
                'tab_styles_dots_section',
                [
                    'label' => esc_html__('Section Dots', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_responsive_control(
                    't_styles_dots_hide',
                    [
                            'label' => esc_html__( 'Hide Element', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'Hide', 'elementinvader-addons-for-elementor' ),
                            'block' => esc_html__( 'Show', 'elementinvader-addons-for-elementor' ),
                            'return_value' => 'none',
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .eli_slider .slick-dots' => 'display: {{VALUE}} !important;',
                            ],
                    ]
            );

            $this->add_responsive_control(
                't_styles_dots_position_style',
                [
                    'label' => __( 'Position Style', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'eli_slider_dots_out',
                    'options' => [
                        'eli_slider_dots_out' => __( 'Out', 'elementinvader-addons-for-elementor' ),
                        'eli_slider_dots_in' => __( 'In', 'elementinvader-addons-for-elementor' ),
                    ],
                ]
            );

            $this->add_responsive_control(
                't_styles_dots_align',
                [
                    'label' => __( 'Position', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                                'title' => esc_html__( 'Left', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                                'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                                'title' => esc_html__( 'Right', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                                'title' => esc_html__( 'Justified', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'render_type' => 'template',
                    'selectors_dictionary' => [
                        'left' => 'justify-content: flex-start;',
                        'center' => 'justify-content: center;',
                        'right' => 'justify-content: flex-end;',
                        'justify' => 'justify-content: space-between;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_slider .slick-dots' => '{{VALUE}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                't_styles_dots_icon',
                [
                    'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default' => [
                        'value' => 'fas fa-circle',
                        'library' => 'solid',
                    ],
                ]
            );

            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .slick-dots li .eli_dot',
                'active' => '{{WRAPPER}} .eli_slider .slick-dots li.slick-active .eli_dot',
                'hover'=>'{{WRAPPER}} .eli_slider .slick-dots li .eli_dot%1$s'
            ];
            $this->generate_renders_tabs($object, 't_styles_dots__s', ['margin','align','font-size','color','background','border','border_radius','padding','shadow','transition']);

            $object = [
                'hover'=>'{{WRAPPER}} .eli_slider .slick-dots li .eli_dot%1$s i'
            ];
            $this->generate_renders_tabs($object, 't_styles_dots_hover_s', ['hover_animation']);

            $this->end_controls_section();

        }


    if(true){
            $this->start_controls_section(
                'tab_content',
                [
                    'label' => esc_html__('Basic', 'elementinvader-addons-for-elementor'),
                    'tab' => 'tab_content',
                ]
            );

            $this->add_responsive_control(
                't_content_basic_position_y',
                [
                    'label' => __( 'Position Y', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                                'title' => esc_html__( 'Top', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                                'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-center',
                        ],
                        'bottom' => [
                                'title' => esc_html__( 'Bottom', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                                'title' => esc_html__( 'Default', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default' => 'left',
                    'render_type' => 'template',
                    'selectors_dictionary' => [
                        'top' => 'justify-content: flex-start;',
                        'center' => 'justify-content: center;',
                        'bottom' => 'justify-content: flex-end;',
                        'justify' => 'justify-content: space-between;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item' => '{{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                't_content_basic_position_x',
                [
                    'label' => __( 'Position X', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                                'title' => esc_html__( 'Left', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                                'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                                'title' => esc_html__( 'Right', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                                'title' => esc_html__( 'Justified', 'elementinvader-addons-for-elementor' ),
                                'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default' => 'left',
                    'render_type' => 'template',
                    'selectors_dictionary' => [
                        'left' => 'align-items: flex-start;',
                        'center' => 'align-items: center;',
                        'right' => 'align-items: flex-end;',
                        'justify' => 'align-items: stretch;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item' => '{{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'tab_content_max_width',
            [
                    'label' => esc_html__( 'Max Width', 'elementinvader-addons-for-elementor' ),
                   'type' => Controls_Manager::SLIDER,
                   'range' => [
                       'px' => [
                           'min' => 10,
                           'max' => 1500,
                       ],
                       'vw' => [
                           'min' => 0,
                           'max' => 100,
                       ],
                       '%' => [
                           'min' => 0,
                           'max' => 100,
                       ],
                   ],
                   'size_units' => [ 'px', 'vw','%' ],
                   'selectors' => [
                        '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line' => 'max-width: {{SIZE}}{{UNIT}}',
                   ],
               ]
           );

            $this->add_responsive_control(
                't_content_basic_link_text',
                [
                    'label' => __( 'Text of Link', 'elementinvader-addons-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'View', 'elementinvader-addons-for-elementor' ),
                ]
            ); 

            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_slider_mask',
            ];
            $this->generate_renders_tabs($object, 't_content_basic_s', ['background']);


            $this->end_controls_section();

            $this->start_controls_section(
                'tab_content_title',
                [
                    'label' => esc_html__('Title', 'elementinvader-addons-for-elementor'),
                    'tab' => 'tab_content',
                ]
            );
            
            $this->add_responsive_control(
                    'tab_content_title_hide',
                    [
                            'label' => esc_html__( 'Hide Element', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'Hide', 'elementinvader-addons-for-elementor' ),
                            'block' => esc_html__( 'Show', 'elementinvader-addons-for-elementor' ),
                            'return_value' => 'none',
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_title' => 'display: {{VALUE}};',
                            ],
                    ]
            );

            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_title',
                'hover'=>'{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_title%1$s'
            ];
            $this->generate_renders_tabs($object, 'tab_content_title_s', 'full');
            $this->end_controls_section();

            $this->start_controls_section(
                'tab_content_content',
                [
                    'label' => esc_html__('Content', 'elementinvader-addons-for-elementor'),
                    'tab' => 'tab_content',
                ]
            );
            $this->add_responsive_control(
                    'tab_content_content_hide',
                    [
                            'label' => esc_html__( 'Hide Element', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'Hide', 'elementinvader-addons-for-elementor' ),
                            'block' => esc_html__( 'Show', 'elementinvader-addons-for-elementor' ),
                            'return_value' => 'none',
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_content' => 'display: {{VALUE}};',
                            ],
                    ]
            );

            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_content',
                'hover'=>'{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_content%1$s'
            ];
            $this->generate_renders_tabs($object, 'tab_content_content_s', 'full');
            $this->end_controls_section();

            $this->start_controls_section(
                'tab_content_link',
                [
                    'label' => esc_html__('Link', 'elementinvader-addons-for-elementor'),
                    'tab' => 'tab_content',
                ]
            );
            $this->add_responsive_control(
                    'tab_content_link_hide',
                    [
                            'label' => esc_html__( 'Hide Element', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'Hide', 'elementinvader-addons-for-elementor' ),
                            'block' => esc_html__( 'Show', 'elementinvader-addons-for-elementor' ),
                            'return_value' => 'none',
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_link' => 'display: {{VALUE}};',
                            ],
                    ]
            );
            $object = [
                'normal' => '{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_link',
                'hover'=>'{{WRAPPER}} .eli_slider .eli_slider_ini .eli_s_item_box_line .eli_s_item_box_link%1$s'
            ];
            $this->generate_renders_tabs($object, 'tab_content_link_s', 'full');
            $this->end_controls_section();

        }
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
        $id_int = substr($this->get_id_int(), 0, 3);
        $settings = $this->get_settings();


        global $paged;
        $allposts = array( 
            'post_type'           =>  'post',
            'orderby'      =>  $settings['t_settings_sec_basic_post_orderby'],
            'order'      =>  $settings['t_settings_sec_basic_post_type'],
            'post_type'      =>  $settings['t_settings_sec_basic_post_type'],
            'posts_per_page'      =>  $settings['t_settings_sec_basic_post_limit'],
            'post_status'		  => 'publish',	
            'ignore_sticky_posts' => true,
            'paged'			      => $paged,
            'meta_query' => array(
                array(
                 'key' => '_thumbnail_id',
                 'compare' => 'EXISTS'
                ),
            )
        );

        $wp_query = new \WP_Query($allposts); 

        $results =  [] ;
        if($settings['t_settings_sec_basic_source'] == 'posts') {
            while ($wp_query->have_posts()) { $wp_query->the_post();
                $row = [];
                $row ['thumbnail'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
                $row ['title'] = strip_tags(get_the_title());
                $row ['description'] = strip_tags(get_the_excerpt());
                $row ['link'] = get_permalink();
                $results[]=$row;
                # code...
            }
        } elseif($settings['t_settings_sec_basic_source'] == 'custom') {
            if(!empty($settings['t_settings_sec_basic_sliders']))
                foreach($settings['t_settings_sec_basic_sliders'] as $item) {
                    $row = [];
                    $row ['thumbnail'] = $this->_ch($item['slider_image']['url'], ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_URL.'/assets/img/placeholder.jpg');
                    $row ['title'] = $item['slider_title'];
                    $row ['description'] = $item['slider_description'];
                    $row ['_id'] = $item['_id'];
                    $row ['data'] = $item;
                    $row ['link'] = $this->_ch($item['slider_url']['url'], '');
                    $results[]=$row;
                }
        }
        
        //$wp_query->query($allposts);
       // while ($wp_query->have_posts()) : $wp_query->the_post(); 
        
        $object = ['results'=>$results, 'settings'=>$settings,'id_int'=>$id_int];
        $object['is_edit_mode'] = false;          
        if(Plugin::$instance->editor->is_edit_mode())
            $object['is_edit_mode'] = true;
      
        echo $this->view('widget_layout', $object); 
    }

	public static function ma_el_get_post_types()
	{
		$post_type_args = array(
			'public'            => true,
			'show_in_nav_menus' => true
		);

		$post_types = get_post_types($post_type_args, 'objects');
		$post_lists = array();
		foreach ($post_types as $post_type) {
			$post_lists[$post_type->name] = $post_type->labels->singular_name;
		}
		return $post_lists;
	}
}
