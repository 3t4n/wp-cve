<?php
/**
 * Class: LaStudioKit_Slides
 * Name: Slides
 * Slug: lakit-slides
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LaStudioKit_Slides extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
        if(!lastudio_kit()->is_optimized_css_mode()){
          wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/slides.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
          $this->add_style_depends( $this->get_name() );
        }
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_widget_css_config($widget_name){
      $file_url = lastudio_kit()->plugin_url( 'assets/css/addons/slides.min.css' );
      $file_path = lastudio_kit()->plugin_path( 'assets/css/addons/slides.min.css' );
      return [
        'key' => $widget_name,
        'version' => lastudio_kit()->get_version(true),
        'file_path' => $file_path,
        'data' => [
          'file_url' => $file_url
        ]
      ];
    }

    public function get_name() {
        return 'lakit-slides';
    }

    public function get_widget_title() {
        return esc_html__( 'Slides', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'lastudio-kit-icon-slides';
    }

    public function get_keywords() {
        return [ 'slides', 'carousel', 'image', 'title', 'slider' ];
    }

    protected function register_controls() {
        $this->_start_controls_section(
            'section_slides',
            [
                'label' => __( 'Slides', 'lastudio-kit' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'slides_repeater' );

        $repeater->start_controls_tab( 'background', [ 'label' => __( 'Background', 'lastudio-kit' ) ] );

        $repeater->add_control(
            'background_color',
            [
                'label' => __( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#bbbbbb',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-wrapbg' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'background_image',
            [
                'label' => _x( 'Image', 'Background Control', 'lastudio-kit' ),
                'type' => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-bg' => 'background-image: url({{URL}})',
                ],
            ]
        );

        $repeater->add_control(
            'background_size',
            [
                'label' => _x( 'Size', 'Background Control', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => _x( 'Cover', 'Background Control', 'lastudio-kit' ),
                    'contain' => _x( 'Contain', 'Background Control', 'lastudio-kit' ),
                    'auto' => _x( 'Auto', 'Background Control', 'lastudio-kit' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-bg' => 'background-size: {{VALUE}}',
                ],
                'condition' => [
                    'background_image[url]!' => '',
                ],
            ]
        );

        $repeater->add_control(
            'background_ken_burns',
            [
                'label' => __( 'Ken Burns Effect', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'separator' => 'before',
                'condition' => [
                    'background_image[url]!' => '',
                ],
            ]
        );

        $repeater->add_control(
            'zoom_direction',
            [
                'label' => __( 'Zoom Direction', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'in',
                'options' => [
                    'in' => __( 'In', 'lastudio-kit' ),
                    'out' => __( 'Out', 'lastudio-kit' ),
                ],
                'condition' => [
                    'background_ken_burns!' => '',
                ],
            ]
        );

        $repeater->add_control(
            'background_overlay',
            [
                'label' => __( 'Background Overlay', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'separator' => 'before',
                'condition' => [
                    'background_image[url]!' => '',
                ],
            ]
        );

	    $repeater->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'background_overlay_color',
			    'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay',
			    'condition' => [
				    'background_overlay' => 'yes',
			    ],
		    )
	    );

        $repeater->add_control(
            'background_overlay_blend_mode',
            [
                'label' => __( 'Blend Mode', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => lastudio_kit_helper()->get_blend_mode_options(),
                'condition' => [
                    'background_overlay' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'lastudio-kit' ) ] );

        $repeater->add_control(
            'subheading',
            [
                'label' => __( 'Sub Title', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Slide Sub-Heading', 'lastudio-kit' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'heading',
            [
                'label' => __( 'Title', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Slide Heading', 'lastudio-kit' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'lastudio-kit' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subdescription1',
            [
                'label' => __( 'Sub-Description 1', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'lastudio-kit' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' ),
            ]
        );

        $repeater->add_control(
            'link_click',
            [
                'label' => __( 'Apply Link On', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => __( 'Whole Slide', 'lastudio-kit' ),
                    'button' => __( 'Button Only', 'lastudio-kit' ),
                ],
                'default' => 'slide',
                'condition' => [
                    'link[url]!' => '',
                ],
            ]
        );

        $repeater->add_control(
            'subdescription2',
            [
                'label' => __( 'Sub-Description 2', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'lastudio-kit' ) ] );

        $repeater->add_control(
            'el_class',
            [
                'label' => __( 'Item CSS Class', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'custom_style',
            [
                'label' => __( 'Custom', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => __( 'Set custom style that will only affect this specific slide.', 'lastudio-kit' ),
            ]
        );

        $repeater->add_control(
            'bg_h_position',
            [
                'label' => __( 'Background Position', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide' => 'justify-content: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        if( lastudio_kit()->get_theme_support('elementor::slides-v2') ){

            $repeater->add_responsive_control(
                'content_width',
                [
                    'label' => __( 'Content Width', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => '--slide-content-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'content_horizontal',
                [
                    'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => is_rtl() ? 'right' : 'left',
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'toggle' => false,
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'content_offset_x',
                [
                    'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-content' => 'left: initial; right: initial;{{content_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'content_vertical',
                [
                    'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => esc_html__( 'Top', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'top',
                    'toggle' => false,
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'content_offset_y',
                [
                    'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-content' => 'top: initial; bottom: initial;{{content_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );
        }
        else{
            $repeater->add_control(
                'horizontal_position',
                [
                    'label' => __( 'Horizontal Position', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-content' => '{{VALUE}}',
                    ],
                    'selectors_dictionary' => [
                        'left' => 'margin-right: auto',
                        'center' => 'margin: 0 auto',
                        'right' => 'margin-left: auto',
                    ],
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );
            $repeater->add_control(
                'vertical_position',
                [
                    'label' => __( 'Vertical Position', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'top' => [
                            'title' => __( 'Top', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'middle' => [
                            'title' => __( 'Middle', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner' => 'align-items: {{VALUE}}',
                    ],
                    'selectors_dictionary' => [
                        'top' => 'flex-start',
                        'middle' => 'center',
                        'bottom' => 'flex-end',
                    ],
                    'condition' => [
                        'custom_style' => 'yes',
                    ],
                ]
            );
        }

        $repeater->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--slide-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'content_margin',
            [
                'label' => __( 'Content Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--slide-content-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'text_align',
            [
                'label' => __( 'Text Align', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner' => 'text-align: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'content_color',
            [
                'label' => __( 'Content Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-subheading' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-heading' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-description' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-subdescription' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-button:not(:hover)' => 'color: {{VALUE}}; border-color: {{VALUE}}; background-color: transparent',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'subtitle_color',
            [
                'label' => __( 'Sub-Title Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-subheading' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                    'content_color' => '',
                ],
            ]
        );

        $repeater->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-heading' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                    'content_color' => '',
                ],
            ]
        );

        $repeater->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                    'content_color' => '',
                ],
            ]
        );

        $repeater->add_control(
            'button_color',
            [
                'label' => __( 'Button Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-button:not(:hover)' => 'color: {{VALUE}}; border-color: {{VALUE}}; background-color: transparent',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                    'content_color' => '',
                ],
            ]
        );

        $repeater->add_control(
            'subdesc1_color',
            [
                'label' => __( 'Sub-Description1 Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-subdescription1' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                    'content_color' => '',
                ],
            ]
        );

        $repeater->add_control(
            'subdesc2_color',
            [
                'label' => __( 'Sub-Description2 Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-slide-inner .lakit-slide-subdescription2' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                    'content_color' => '',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->_add_control(
            'slides',
            [
                'label' => __( 'Slides', 'lastudio-kit' ),
                'type' => Controls_Manager::REPEATER,
                'show_label' => true,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'heading' => __( 'Slide 1 Heading', 'lastudio-kit' ),
                        'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'lastudio-kit' ),
                        'button_text' => __( 'Click Here', 'lastudio-kit' ),
                        'background_color' => '#833ca3',
                    ],
                    [
                        'heading' => __( 'Slide 2 Heading', 'lastudio-kit' ),
                        'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'lastudio-kit' ),
                        'button_text' => __( 'Click Here', 'lastudio-kit' ),
                        'background_color' => '#4054b2',
                    ],
                    [
                        'heading' => __( 'Slide 3 Heading', 'lastudio-kit' ),
                        'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'lastudio-kit' ),
                        'button_text' => __( 'Click Here', 'lastudio-kit' ),
                        'background_color' => '#1abc9c',
                    ],
                ],
                'title_field' => '{{{ heading }}}',
            ]
        );

        $this->_add_responsive_control(
            'slides_height',
            [
                'label' => __( 'Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                    ]
                ],
                'default' => [
                    'size' => 400,
                ],
                'size_units' => [ 'px', 'vh', 'vw', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--slide-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        if( lastudio_kit()->get_theme_support('elementor::slides-v2') ) {
          $this->add_control(
            'ignore_height',
            [
              'label'     => __( 'Ignore Height on Mobile', 'lastudio-kit' ),
              'type'      => Controls_Manager::SWITCHER,
              'default'   => '',
              'prefix_class' => 'ls-ignore-height-',
            ]
          );
        }

        $this->add_control(
            'content_animation',
            [
                'label' => __( 'Content Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fadeInUp',
                'options' => [
                    '' => __( 'None', 'lastudio-kit' ),
                    'fadeInDown' => __( 'Down', 'lastudio-kit' ),
                    'fadeInUp' => __( 'Up', 'lastudio-kit' ),
                    'fadeInRight' => __( 'Right', 'lastudio-kit' ),
                    'fadeInLeft' => __( 'Left', 'lastudio-kit' ),
                    'zoomIn' => __( 'Zoom', 'lastudio-kit' ),
                ],
            ]
        );

        $this->add_control(
            'carousel_columns',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '1',
            ]
        );

        $this->_end_controls_section();

        $this->register_carousel_section([], 'carousel_columns', false);

        $this->_start_controls_section(
            'section_style_slides',
            [
                'label' => __( 'Slides', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'slide_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-wrapbg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'slidebg_width',
            [
                'label' => __( 'Background Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 2560,
                    ]
                ],
                'size_units' => [ '%', 'px' ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slides' => '--slide-bg-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-slides .swiper-cube-shadow' => 'display: none',
                    '{{WRAPPER}} .lakit-slides .swiper-slide-shadow-left' => 'display: none',
                    '{{WRAPPER}} .lakit-slides .swiper-slide-shadow-right' => 'display: none',
                ]
            ]
        );

        $this->_add_control(
            'slide_h_position',
            [
                'label' => __( 'Horizontal Position', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'justify-content: {{VALUE}}'
                ]
            ]
        );

        $this->_end_controls_section();

        if( lastudio_kit()->get_theme_support('elementor::slides-v2') ){
            $this->_start_controls_section('section_style_content_wrap', [
                'label' => __('Wrapper Content', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);

            $this->_add_responsive_control('content_wrap_width', [
                'label' => __('Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]);

            $this->_add_responsive_control(
                'content_wrap_padding',
                [
                    'label' => __( 'Padding', 'lastudio-kit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} .lakit-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->_end_controls_section();
        }

        $this->_start_controls_section(
            'section_style_content',
            [
                'label' => __( 'Content', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->_add_responsive_control(
            'content_max_width',
            [
                'label' => __( 'Content Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--slide-content-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-slides:not(.lakit-slides-v2) .lakit-slide-content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        if( lastudio_kit()->get_theme_support('elementor::slides-v2') ){
            $this->_add_control(
                'slide_content_horizontal',
                [
                    'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => is_rtl() ? 'right' : 'left',
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'toggle' => false,
                ]
            );

            $this->_add_responsive_control(
                'slide_content_offset_x',
                [
                    'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom'],
                    'selectors' => [
                        '{{WRAPPER}} .lakit-slide-content' => '{{slide_content_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->_add_control(
                'slide_content_vertical',
                [
                    'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => esc_html__( 'Top', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'top',
                    'toggle' => false,
                ]
            );

            $this->_add_responsive_control(
                'slide_content_offset_y',
                [
                    'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} .lakit-slide-content' => '{{slide_content_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
        }

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_bg',
                'selector' => '{{WRAPPER}} .lakit-slide-content',
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'content_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit'),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .lakit-slide-content',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_shadow',
                'selector' => '{{WRAPPER}} .lakit-slide-content',
            )
        );

        $this->_add_responsive_control(
            'slides_padding',
            [
                'label' => __( 'Content Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--slide-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'slides_margin',
            [
                'label' => __( 'Content Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--slide-content-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        if( !lastudio_kit()->get_theme_support('elementor::slides-v2') ){
            $this->_add_control(
                'slides_horizontal_position',
                [
                    'label' => __( 'Horizontal Position', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'lastudio-kit' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'prefix_class' => 'elementor--h-position-',
                ]
            );
            $this->_add_control(
                'slides_vertical_position',
                [
                    'label' => __( 'Vertical Position', 'lastudio-kit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'top' => [
                            'title' => __( 'Top', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'middle' => [
                            'title' => __( 'Middle', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'lastudio-kit' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'prefix_class' => 'elementor--v-position-',
                ]
            );
        }

        $this->_add_responsive_control(
            'slides_text_align',
            [
                'label' => __( 'Text Align', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_style_subtitle',
            [
                'label' => __( 'Sub Title', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'subheading_spacing',
            [
                'label' => __( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner .lakit-slide-subheading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'subheading_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-subheading' => 'color: {{VALUE}}',

                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subheading_typography',
                'selector' => '{{WRAPPER}} .lakit-slide-subheading',
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'heading_spacing',
            [
                'label' => __( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner .lakit-slide-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'heading_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-heading' => 'color: {{VALUE}}',

                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .lakit-slide-heading',
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_style_description',
            [
                'label' => __( 'Description', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'description_spacing',
            [
                'label' => __( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner .lakit-slide-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-description' => 'color: {{VALUE}}',

                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .lakit-slide-description',
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_style_subdescription1',
            [
                'label' => __( 'Sub Description 1', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'subdescription1_spacing',
            [
                'label' => __( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner .lakit-slide-subdescription1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'subdescription1_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-subdescription1' => 'color: {{VALUE}}',

                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subdescription1_typography',
                'selector' => '{{WRAPPER}} .lakit-slide-subdescription1',
            ]
        );

        $this->_end_controls_section();


        $this->_start_controls_section(
            'section_style_subdescription2',
            [
                'label' => __( 'Sub Description 2', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'subdescription2_spacing',
            [
                'label' => __( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-inner .lakit-slide-subdescription2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'subdescription2_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-subdescription2' => 'color: {{VALUE}}',

                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subdescription2_typography',
                'selector' => '{{WRAPPER}} .lakit-slide-subdescription2',
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_style_button',
            [
                'label' => __( 'Button', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .lakit-slide-button'
            ]
        );

        $this->_add_icon_control(
            'btn_icon',
            [
                'label'       => __( 'Add Icon', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'file'        => '',
                'skin'        => 'inline',
                'label_block' => false
            ]
        );

        $this->_add_control(
            'btn_icon_position',
            array(
                'label'     => esc_html__( 'Icon Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'row-reverse'       => esc_html__( 'Before Text', 'lastudio-kit' ),
                    'row'               => esc_html__( 'After Text', 'lastudio-kit' ),
                    'column-reverse'    => esc_html__( 'Top', 'lastudio-kit' ),
                    'column'            => esc_html__( 'Bottom', 'lastudio-kit' ),
                ),
                'default'   => 'row',
                'selectors' => array(
                    '{{WRAPPER}} .lakit-slide-button' => 'flex-direction: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'btn_icon__size',
            [
                'label' => __( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'btn_icon__gap',
            [
                'label' => __( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'button_border_width',
            [
                'label' => __( 'Border Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-slide-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );

        $this->_add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );
        $this->_add_responsive_control(
            'button_pd',
            [
                'label'      => esc_html__( 'Padding', 'lastudio-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );

        $this->_add_responsive_control(
            'button_margin',
            [
                'label'      => esc_html__( 'Margin', 'lastudio-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-slide-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );

        $this->_start_controls_tabs( 'button_tabs' );

        $this->_start_controls_tab( 'normal', [ 'label' => __( 'Normal', 'lastudio-kit' ) ] );

        $this->_add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'button_border_color',
            [
                'label' => __( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_shadow',
                'selector' => '{{WRAPPER}} .lakit-slide-button',
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab( 'hover', [ 'label' => __( 'Hover', 'lastudio-kit' ) ] );

        $this->_add_control(
            'button_hover_text_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'button_hover_background_color',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-slide-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .lakit-slide-button:hover',
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->register_carousel_arrows_dots_style_section();
    }

    public function get_advanced_carousel_options( $carousel_columns = false, $widget_id = '', $settings = null ) {
        $opts = parent::get_advanced_carousel_options($carousel_columns, $widget_id, $settings);
        $content_animation = $this->get_settings_for_display('content_animation');
        $opts = array_merge([
            'content_selector' => '.lakit-slide-content',
            'content_effect_in' => $content_animation,
            'content_effect_out' => str_replace('In','Out', $content_animation),
        ], $opts);
        return $opts;
    }

    protected function render() {
        $settings = $this->get_settings();

        if ( empty( $settings['slides'] ) ) {
            return;
        }

        $carousel_effect = $this->get_settings_for_display('carousel_effect');

        $this->add_render_attribute( 'button', 'class', [ 'elementor-button', 'lakit-slide-button' ] );

        $slides = [];
        $slide_count = 0;

        $btn_icon =  $this->_get_icon_setting( $this->get_settings_for_display('selected_btn_icon'), '<span class="elementor-button-icon">%s</span>' );

        foreach ( $settings['slides'] as $slide ) {
            $slide_html = '';
            $btn_attributes = '';
            $slide_attributes = '';
            $slide_element = 'div';
            $btn_element = 'div';
            $slide_url = $slide['link']['url'];

            $link_instance = 'slide_link_' . $slide_count;

            $tmp_html = '';

            if ( ! empty( $slide_url ) ) {

	            $this->_add_link_attributes( $link_instance, $slide['link'] );

                if ( 'button' === $slide['link_click'] ) {
                    $btn_element = 'a';
                    $btn_attributes = $this->get_render_attribute_string( $link_instance);
                    $tmp_html = '<a '.$btn_attributes.'>'.$slide['heading'].'</a>';
                } else {
                    $slide_element = 'a';
                    $slide_attributes = $this->get_render_attribute_string( $link_instance );
                }
            }

            $extra_bg_parallax = '';
            if(in_array($carousel_effect, ['slide', 'cube', 'coverflow'])){
                $extra_bg_parallax = ' data-swiper-parallax="-10%"';
            }

            $slide_html .= '<div class="lakit-slide-content">';

            if ( $slide['subheading'] ) {
                $slide_html .= '<div class="lakit-slide-subheading" data-swiper-parallax="-300" data-swiper-parallax-opacity="0">' . $slide['subheading'] . '</div>';
            }

            if ( $slide['heading'] ) {
                $slide_html .= '<div class="lakit-slide-heading"  data-swiper-parallax="-250" data-swiper-parallax-opacity="0">' . $slide['heading'] . '</div>';
            }

            if ( $slide['description'] ) {
                $slide_html .= '<div class="lakit-slide-description"  data-swiper-parallax="-200" data-swiper-parallax-opacity="0">' . $slide['description'] . '</div>';
            }

            if ( $slide['subdescription1'] ) {
                $slide_html .= '<div class="lakit-slide-subdescription lakit-slide-subdescription1"  data-swiper-parallax="-150" data-swiper-parallax-opacity="0">' . $slide['subdescription1'] . '</div>';
            }

            if ( $slide['button_text'] ) {
                $btn_text = '<span class="elementor-button-text">'.$slide['button_text'].'</span>';
                $slide_html .= sprintf('<%1$s data-swiper-parallax="-100" data-swiper-parallax-opacity="0" %3$s>%2$s</%1$s>',
                    $btn_element,
                    $btn_text . $btn_icon,
                    $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' )
                );
            }

            if ( $slide['subdescription2'] ) {
                $slide_html .= '<div class="lakit-slide-subdescription lakit-slide-subdescription2"  data-swiper-parallax="-50" data-swiper-parallax-opacity="0">' . $slide['subdescription2'] . '</div>';
            }

            $ken_class = '';

            if ( '' != $slide['background_ken_burns'] ) {
                $ken_class = ' elementor-ken-' . $slide['zoom_direction'];
            }

            $slide_html .= '</div>';
            $slide_bg = '<div class="lakit-slide-bg' . $ken_class . ' parallax-bg"'.$extra_bg_parallax.'>'.$tmp_html.'</div>';
            $slide_bg = '<div class="lakit-slide-wrapbg" data-swiper-parallax="30%" data-swiper-parallax-opacity="0">'.$slide_bg.'</div>';
            if ( 'yes' === $slide['background_overlay'] ) {
                $slide_bg .= '<div class="elementor-background-overlay"></div>';
            }
            $slide_html = $slide_bg . '<' . $slide_element . ' ' . $slide_attributes . ' class="lakit-slide-inner">' . $slide_html . '</' . $slide_element . '>';
            $slides[] = '<div class="elementor-repeater-item-' . $slide['_id'] . (isset($slide['el_class']) ? ' ' . $slide['el_class'] : '')  . ' swiper-slide">' . $slide_html . '</div>';
            $slide_count++;
        }

        $is_rtl = is_rtl();
        $direction = $is_rtl ? 'rtl' : 'ltr';

        $carousel_classes = [ 'lakit-carousel lakit-slides' ];

        if( lastudio_kit()->get_theme_support('elementor::slides-v2') ){
            $carousel_classes[] = 'lakit-slides-v2';
        }

        $this->add_render_attribute( 'slides', [
            'class' => $carousel_classes,
            'data-slider_options' => htmlspecialchars( json_encode( $this->get_advanced_carousel_options() ) ),
            'dir' => $direction
        ] );

        $carousel_id = $this->get_settings_for_display('carousel_id');
        if(empty($carousel_id)){
            $carousel_id = 'lakit_carousel_' . $this->get_id();
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'slides' ); ?>>
            <div class="lakit-carousel-inner">
                <div class="swiper-container" id="<?php echo esc_attr($carousel_id); ?>">
                    <div class="swiper-wrapper">
                        <?php echo implode( '', $slides ); ?>
                    </div>
                </div>
            </div>
            <?php
            if ( filter_var(  $this->get_settings_for_display( 'carousel_dots' ), FILTER_VALIDATE_BOOLEAN ) ) {
                echo '<div class="lakit-carousel__dots lakit-carousel__dots_'.$this->get_id().' swiper-pagination"></div>';
            }
            if ( filter_var(  $this->get_settings_for_display( 'carousel_arrows' ), FILTER_VALIDATE_BOOLEAN ) ) {
                echo sprintf( '<div class="lakit-carousel__prev-arrow-%s lakit-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'carousel_prev_arrow', '%s', '', false ) );
                echo sprintf( '<div class="lakit-carousel__next-arrow-%s lakit-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'carousel_next_arrow', '%s', '', false ) );
            }
            if ( filter_var(  $this->get_settings_for_display( 'carousel_scrollbar' ), FILTER_VALIDATE_BOOLEAN ) ) {
                echo sprintf('<div class="lakit-carousel__scrollbar swiper-scrollbar lakit-carousel__scrollbar_%1$s"></div>', $this->get_id());
            }
            ?>
        </div>
        <?php
    }

}
