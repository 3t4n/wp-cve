<?php

/**
 * Advanced Slider widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Mask_Image;

defined('ABSPATH') || die();

class Advanced_Slider extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Advanced Slider', 'skt-addons-elementor');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-slider';
    }

    public function get_keywords() {
        return ['hero slider', 'advanced', 'slider', 'carousel'];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__slides_content_controls();
		$this->__settings_content_controls();
	}

    protected function __slides_content_controls() {

        $this->start_controls_section(
            '_section_slides',
            [
                'label' => __('Slides', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_type',
            [
                'label' => __('Slider Type', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'single',
                'options' => [
                    'single'  => __('Single', 'skt-addons-elementor'),
                    'multiple' => __('Multiple', 'skt-addons-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => __('Slides Per View', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 2,
                'condition' => [
                    'slider_type' => 'multiple'
                ],
                'frontend_available' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'slider_direction',
            [
                'label' => __('Slider Direction', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal'  => __('Horizontal', 'skt-addons-elementor'),
                    'vertical' => __('Vertical', 'skt-addons-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => __('Slider Effect', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'false',
                'options' => [
                    'false'  => __('Slide', 'skt-addons-elementor'),
                    'fade' => __('Fade', 'skt-addons-elementor'),
                    'cube' => __('Cube', 'skt-addons-elementor'),
                    'flip' => __('Flip', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'slider_type' => 'single'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'effect_multiple',
            [
                'label' => __('Slider Effect', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'false',
                'options' => [
                    'false'  => __('Slide', 'skt-addons-elementor'),
                    'coverflow' => __('Cover Flow', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'slider_type' => 'multiple'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'effect_speed',
            [
                'label' => __('Effect Speed (ms)', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'frontend_available' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'slides_control_separator',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $slides = new \Elementor\Repeater();

        $slides->add_control(
            'content_type',
            [
                'label' => __('Content Type', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default'  => __('Default', 'skt-addons-elementor'),
                    'template' => __('Template', 'skt-addons-elementor'),
                ],
            ]
        );

        $slides->start_controls_tabs(
            'slide_content_tabs'
        );

        $slides->start_controls_tab(
            'slide_background_tabs',
            [
                'label' => __('Background', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
            ]
        );

        $slides->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'slider_background',
                'label' => __('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-slide, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-gallery-slide',
                'separator' => 'before',
                'style_transfer' => true,
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#71D7F7',
                    ],
                ],
                'condition' => [
                    'content_type' => 'default'
                ],
            ]
        );

        $slides->end_controls_tab();

        $slides->start_controls_tab(
            'slide_content_tabs_content',
            [
                'label' => __('Content', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
            ]
        );

        $slides->add_control(
            'slide_content_icon',
            [
                'label' => __('Icon Type', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'skt-addons-elementor'),
                        'icon' => 'eicon-nerd',
                    ],
                    'image' => [
                        'title' => __('Image', 'skt-addons-elementor'),
                        'icon' => 'eicon-image',
                    ],
                ],
                'condition' => [
                    'content_type' => 'default',
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,
            ]
        );

        $slides->add_control(
            'image',
            [
                'label' => __('Image', 'skt-addons-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_icon' => 'image',
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $slides->add_group_control(
            Group_Control_Mask_Image::get_type(),
            [
                'name' => 'image_masking',
                'label' => 'Masking',
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_icon' => 'image',
                ],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-figure--image',
            ]
        );

        $slides->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'separator' => 'none',
                'exclude' => [
                    'full',
                    'custom',
                    'large',
                    'shop_catalog',
                    'shop_single',
                    'shop_thumbnail'
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_icon' => 'image',
                ]
            ]
        );

        $slides->add_control(
            'icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                // 'label_block' => true,
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_icon' => 'icon',
                ],
            ]
        );

        $slides->add_control(
            'slide_content_title',
            [
                'label' => __('Title', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('', 'skt-addons-elementor'),
                'placeholder' => __('Type your title here', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->add_control(
            'slide_content_sub_title',
            [
                'label' => __('Sub Title', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('', 'skt-addons-elementor'),
                'placeholder' => __('Type your sub title here', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->add_control(
            'slide_content_description',
            [
                'label' => __('Description', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('', 'skt-addons-elementor'),
                'placeholder' => __('Type your description here', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->add_control(
            'slide_content_button_1_text',
            [
                'label' => __('Button 1 Text', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->add_control(
            'slide_content_button_1_link',
            [
                'label' => __('Button 1 Link', 'skt-addons-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'skt-addons-elementor'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_button_1_text!' => '',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->add_control(
            'slide_content_button_2_text',
            [
                'label' => __('Button 2 Text', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->add_control(
            'slide_content_button_2_link',
            [
                'label' => __('Button 2 Link', 'skt-addons-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'skt-addons-elementor'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_button_2_text!' => '',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $slides->end_controls_tab();

        $slides->start_controls_tab(
            'slide_content_tabs_style',
            [
                'label' => __('Style', 'skt-addons-elementor'),
                'condition' => [
                    'content_type' => 'default'
                ],
            ]
        );

        $slides->add_control(
            'slide_content_custom',
            [
                'label' => __('Custom', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'description' => __('Set custom style that will only affect this specific slide.', 'skt-addons-elementor'),
                'default' => 'no',
            ]
        );

        $slides->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'slide_content_background',
                'label' => __('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content',
                'separator' => 'before',
                'style_transfer' => true,
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );

        $slides->add_control(
            'slide_content_horizontal_align',
            [
                'label' => __('Horizontal Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );

        $slides->add_control(
            'slide_content_vertical_align',
            [
                'label' => __('Vertical Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Top', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('Bottom', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );

        $slides->add_control(
            'slide_content_text_align',
            [
                'label' => __('Text Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );

        $slides->add_control(
            'slide_content_color',
            [
                'label' => __('Content Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-title, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-sub-title, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );

        $slides->add_control(
            'slide_content_icon_color',
            [
                'label' => __('Icon Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-figure--icon i, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-figure--icon svg' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                    'slide_content_icon' => 'icon',
                ],
            ]
        );

        $slides->add_responsive_control(
            'slide_content_icon_size',
            [
                'label' => __('Icon/ Image Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 60,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-figure' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );

        $slides->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'slide_content_text_shadow',
                'label' => __('Text Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-icon, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-title, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-sub-title, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-content-description, {{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content .skt-slider-button',
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
            ]
        );


        $slides->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'slide_content_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content',
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
			]
		);

        $slides->add_control(
			'slide_content_border_radius',
			[
				'label' => __( 'Bordar radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.skt-slider-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'content_type' => 'default',
                    'slide_content_custom' => 'yes',
                ],
			]
		);

        $slides->end_controls_tab();

        $slides->end_controls_tabs();


        $slides->add_control(
            'slide_content_template',
            [
                'label' => __('Choose Template', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'options' => skt_addons_elementor_pro_get_elementor_templates(),
                'condition' => [
                    'content_type' => 'template'
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __('Slides', 'skt-addons-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slides->get_controls(),
                'default' => [
                    [
                        'content_type' => 'default',
                        'slide_content_title' => __('Advanced Slider 1 Title', 'skt-addons-elementor'),
                        'slide_content_sub_title' => __('Sub Title', 'skt-addons-elementor'),
                        'slide_content_description' => __('Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', 'skt-addons-elementor'),
                        'slider_background_background' => 'classic',
                        'slider_background_color' => '#1F2363',
                        'slide_content_button_1_text' => esc_html__('Button 1', 'skt-addons-elementor'),
                        'slide_content_button_1_link' => ['url' => ''],
                        'slide_content_button_2_text' => esc_html__('Button 2', 'skt-addons-elementor'),
                        'slide_content_button_2_link' => ['url' => ''],
                    ],
                    [
                        'content_type' => 'default',
                        'slide_content_title' => __('Advanced Slider 2 Title', 'skt-addons-elementor'),
                        'slide_content_sub_title' => __('Sub Title', 'skt-addons-elementor'),
                        'slide_content_description' => __('Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', 'skt-addons-elementor'),
                        'slider_background_background' => 'classic',
                        'slider_background_color' => '#5636D1',
                        'slide_content_button_1_text' => esc_html__('Button 1', 'skt-addons-elementor'),
                        'slide_content_button_1_link' => ['url' => ''],
                        'slide_content_button_2_text' => esc_html__('Button 2', 'skt-addons-elementor'),
                        'slide_content_button_2_link' => ['url' => ''],
                    ],
                    [
                        'content_type' => 'default',
                        'slide_content_title' => __('Advanced Slider 3 Title', 'skt-addons-elementor'),
                        'slide_content_sub_title' => __('Sub Title', 'skt-addons-elementor'),
                        'slide_content_description' => __('Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', 'skt-addons-elementor'),
                        'slider_background_background' => 'classic',
                        'slider_background_color' => '#8D0F70',
                        'slide_content_button_1_text' => esc_html__('Button 1', 'skt-addons-elementor'),
                        'slide_content_button_1_link' => ['url' => ''],
                        'slide_content_button_2_text' => esc_html__('Button 2', 'skt-addons-elementor'),
                        'slide_content_button_2_link' => ['url' => ''],
                    ],
                ],
            ]
        );

        $this->add_control(
            'slides_style_control_separator',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __('Height', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0.1,
                        'max' => 16,
                        'step' => 0.1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 60,
                    'unit' => 'vh',
                ],
                'tablet_default' => [
                    'size' => 45,
                    'unit' => 'vh',
                ],
                'mobile_default' => [
                    'size' => 60,
                    'unit' => 'vh',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between_slides',
            [
                'label' => __('Space Between Slides', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => 0,
                'frontend_available' => true,
                'description' => esc_html__('Slides space in pixel(px)', 'skt-addons-elementor'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_slider_settings',
            [
                'label' => __('Slider Settings', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_content_animation',
            [
                'label' => __('Content Animation', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'skt_addons_elementor_fadeInUp',
                'options' => [
                    'none'  => __('None', 'skt-addons-elementor'),
                    'skt_addons_elementor_fadeInUp'  => __('FadeInUp', 'skt-addons-elementor'),
                    'skt_addons_elementor_fadeInDown' => __('FadeInDown', 'skt-addons-elementor'),
                    'skt_addons_elementor_fadeInLeft' => __('FadeInLeft', 'skt-addons-elementor'),
                    'skt_addons_elementor_fadeInRight' => __('FadeInRight', 'skt-addons-elementor'),
                    'skt_addons_elementor_zoomIn' => __('ZoomIn', 'skt-addons-elementor'),
                    'skt_addons_elementor_rollIn' => __('RollIn', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'slider_type!' => 'multiple',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'content_animation_speed',
            [
                'label' => __('Animation Speed (ms)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['ms'],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 5000,
                        'step' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'ms',
                    'size' => 1250,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content' => 'animation-duration: {{SIZE}}{{UNIT}};',
                ],
                'description' => __('Slide speed in miliseconds', 'skt-addons-elementor'),
                'condition' => [
                    'slider_type!' => 'multiple',
                    'slider_content_animation!' => 'none',
                ],

            ]
        );

        $this->add_control(
            'arrow_navigation',
            [
                'label' => __('Arrow Navigation?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'arrow_navigation_prev',
            [
                'label' => __('Previous Icon', 'skt-addons-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'skti skti-play-previous',
                    'library' => 'skt-icons',
                ],
                'condition' => [
                    'arrow_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_navigation_next',
            [
                'label' => __('Next Icon', 'skt-addons-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'skti skti-play-next',
                    'library' => 'skt-icons',
                ],
                'condition' => [
                    'arrow_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => __('Pagination Type', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'  => __('None', 'skt-addons-elementor'),
                    'dots'  => __('Dots', 'skt-addons-elementor'),
                    'numbers' => __('Numbers', 'skt-addons-elementor'),
                    'progressbar' => __('Progressbar', 'skt-addons-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'number_pagination_type',
            [
                'label' => __('Number Type', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bullets',
                'options' => [
                    'bullets'  => __('Bullets', 'skt-addons-elementor'),
                    'fraction' => __('Fraction', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'pagination_type' => 'numbers',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scroll_bar',
            [
                'label' => __('Scroll Bar?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scroll_bar_visibility',
            [
                'label' => __('Scroll Bar', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'false',
                'options' => [
                    'false'  => __('Always show', 'skt-addons-elementor'),
                    'true' => __('Automatic hide', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'scroll_bar' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'thumbs_navigation',
            [
                'label' => __('Thumbnail Navigation?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'space_between_thumbs',
            [
                'label' => __('Space Between Thumbs', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => 10,
                'condition' => [
                    'thumbs_navigation' => 'yes',
                ],
                'frontend_available' => true,
                'description' => esc_html__('Thumbs space in pixel(px)', 'skt-addons-elementor'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'infinity_loop',
            [
                'label' => __('Infinity Loop?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => true,
                'default' => true,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 10000,
                'step' => 5,
                'default' => 5000,
                'description' => __('Autoplay speed in milliseconds', 'skt-addons-elementor'),
                'condition' => [
                    'autoplay' => 'yes',
                ],
                'frontend_available' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__slider_content_style_controls();
		$this->__icon_image_style_controls();
		$this->__title_style_controls();
		$this->__sub_title_style_controls();
		$this->__desc_style_controls();
		$this->__button_style_controls();
		$this->__arrow_style_controls();
		$this->__dots_style_controls();
		$this->__pagination_number_style_controls();
		$this->__pagination_progressbar_style_controls();
		$this->__scroll_bar_style_controls();
		$this->__nav_thumbnails_style_controls();
	}

    protected function __slider_content_style_controls() {

        $this->start_controls_section(
            '_section_slider_style',
            [
                'label' => __('Slider Content', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slider_content_width',
            [
                'label' => __('Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 1500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0.1,
                        'max' => 15,
                        'step' => 0.1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'tablet_default' => [],
                'mobile_default' => [
                    'size' => 70,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_margin',
            [
                'label' => __('Margin', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_content_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slide_content_horizontal_align',
            [
                'label' => __('Horizontal Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slide_content_vertical_align',
            [
                'label' => __('Vertical Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Top', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('Bottom', 'skt-addons-elementor'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slide_content_text_align',
            [
                'label' => __('Text Align', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'slide_content_text_shadow',
                'label' => __('Text Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-slider-content .skt-slider-content-title, {{WRAPPER}} .skt-slider-content .skt-slider-content-sub-title, {{WRAPPER}} .skt-slider-content .skt-slider-content-description, {{WRAPPER}} .skt-slider-content .skt-slider-button',
            ]
        );

        $this->end_controls_section();
	}

    protected function __icon_image_style_controls() {

        $this->start_controls_section(
            '_section_content_icon_style',
            [
                'label' => __('Icon/ Image', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Size (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-figure' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __('Padding (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-figure' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __('Bottom Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 150,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .skt-slider-figure'
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-figure, {{WRAPPER}} .skt-slider-figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .skt-slider-figure'
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-figure' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-figure' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __title_style_controls() {

        $this->start_controls_section(
            '_section_content_title_style',
            [
                'label' => __('Title', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 150,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'tablet_default' => [],
                'mobile_default' => [],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .skt-slider-content-title',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->end_controls_section();
	}

    protected function __sub_title_style_controls() {

        $this->start_controls_section(
            '_section_content_sub_title_style',
            [
                'label' => __('Sub Title', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'sub_title_spacing',
            [
                'label' => __('Bottom Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 150,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'tablet_default' => [],
                'mobile_default' => [],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sub_title_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title',
                'selector' => '{{WRAPPER}} .skt-slider-content-sub-title',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->end_controls_section();
	}

    protected function __desc_style_controls() {

        $this->start_controls_section(
            '_section_content_description_style',
            [
                'label' => __('Description', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => __('Bottom Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 150,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'tablet_default' => [],
                'mobile_default' => [],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-content-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description',
                'selector' => '{{WRAPPER}} .skt-slider-content-description',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->end_controls_section();
	}

    protected function __button_style_controls() {

        $this->start_controls_section(
            '_section_content_button_style',
            [
                'label' => __('Button', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_between_space',
            [
                'label' => __('Button Between Space (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'tablet_default' => [],
                'mobile_default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}} .skt-slider-buttons .button-1' => 'margin: 0 calc({{SIZE}}{{UNIT}}/2) 0 0;',
                    '(desktop){{WRAPPER}} .skt-slider-buttons .button-2' => 'margin: 0 0 0 calc({{SIZE}}{{UNIT}}/2);',
                    '(mobile){{WRAPPER}} .skt-slider-buttons .button-1' => 'margin: 0 0 calc({{SIZE}}{{UNIT}}/2) 0;',
                    '(mobile){{WRAPPER}} .skt-slider-buttons .button-2' => 'margin: calc({{SIZE}}{{UNIT}}/2) 0 0 0;',
                ],
            ]
        );

        $this->add_control(
            'slider_content_button_1_heading',
            [
                'label' => __('Button 1', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'button_1_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_1_border',
                'selector' => '{{WRAPPER}} .skt-slider-buttons .button-1'
            ]
        );

        $this->add_responsive_control(
            'button_1_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_1_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-slider-buttons .button-1',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_1_box_shadow',
                'selector' => '{{WRAPPER}} .skt-slider-buttons .button-1'
            ]
        );

        $this->start_controls_tabs('_tabs_button_1');

        $this->start_controls_tab(
            '_tab_button_1_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'button_1_text_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_1_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tabs_button_1_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'button_1_hover_text_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_1_hover_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#47B7F0',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_1_hover_border_color',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-1:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'button_1_border_border!' => ''
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'slider_content_button_2_heading',
            [
                'label' => __('Button 2', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'button_2_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_2_border',
                'selector' => '{{WRAPPER}} .skt-slider-buttons .button-2'
            ]
        );

        $this->add_responsive_control(
            'button_2_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_2_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-slider-buttons .button-2',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_2_box_shadow',
                'selector' => '{{WRAPPER}} .skt-slider-buttons .button-2'
            ]
        );

        $this->start_controls_tabs('_tabs_button_2');

        $this->start_controls_tab(
            '_tab_button_2_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'button_2_text_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_2_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#943FF8',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tabs_button_2_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'button_2_hover_text_color',
            [
                'label' => __('Text Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_2_hover_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F5E897',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_2_hover_border_color',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-buttons .button-2:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'button_2_border_border!' => ''
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __arrow_style_controls() {

        $this->start_controls_section(
            '_section_navigation_arrow_style',
            [
                'label' => __('Navigation - Arrow', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrow_navigation' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'arrow_position_toggle',
            [
                'label' => __('Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'skt-addons-elementor'),
                'label_on' => __('Custom', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'arrow_sync_position',
            [
                'label' => __('Sync Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'yes' => [
                        'title' => __('Yes', 'skt-addons-elementor'),
                        'icon' => 'eicon-sync',
                    ],
                    'no' => [
                        'title' => __('No', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-stretch',
                    ]
                ],
                'condition' => [
                    'arrow_position_toggle' => 'yes'
                ],
                'default' => 'no',
                'toggle' => false,
                'prefix_class' => 'skt-arrow-sync-'
            ]
        );

        $this->add_responsive_control(
            'arrow_position_y',
            [
                'label' => __('Vertical (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'arrow_position_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-horizontal .skt-slider-prev, {{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-horizontal .skt-slider-next' => 'top: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-vertical .skt-slider-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                    '{{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-vertical .skt-slider-next' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',

                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-horizontal .skt-slider-prev, {{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-horizontal .skt-slider-next' => 'top: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-vertical .skt-slider-prev, {{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-vertical .skt-slider-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_position_x',
            [
                'label' => __('Horizontal (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'arrow_position_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-horizontal .skt-slider-prev' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    '{{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-horizontal .skt-slider-next' => 'right: {{SIZE}}{{UNIT}}; left: auto;',

                    '{{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-vertical .skt-slider-prev, {{WRAPPER}}.skt-arrow-sync-no .skt-slider-direction-vertical .skt-slider-next' => 'left: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-horizontal .skt-slider-prev, {{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-horizontal .skt-slider-next' => 'left: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-vertical .skt-slider-prev, {{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-vertical .skt-slider-next' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_spacing',
            [
                'label' => __('Space Between Arrows (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'arrow_position_toggle' => 'yes',
                    'arrow_sync_position' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-horizontal .skt-slider-next' => 'margin-left: calc({{SIZE}}{{UNIT}}/ 2);',
                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-horizontal .skt-slider-prev' => 'margin-right: calc({{SIZE}}{{UNIT}}/ 2);',

                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-vertical .skt-slider-next' => 'margin-top: calc({{SIZE}}{{UNIT}}/ 2);',
                    '{{WRAPPER}}.skt-arrow-sync-yes .skt-slider-direction-vertical .skt-slider-prev' => 'margin-bottom: calc({{SIZE}}{{UNIT}}/ 2);',
                ],
            ]
        );

        $this->end_popover();

        $this->add_control(
            'arrow_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-next, {{WRAPPER}} .skt-slider-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label' => __('Icon Size (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-slider-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .skt-slider-prev, {{WRAPPER}} .skt-slider-next',
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev, {{WRAPPER}} .skt-slider-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs('_tabs_arrow');

        $this->start_controls_tab(
            '_tab_arrow_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev, {{WRAPPER}} .skt-slider-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev, {{WRAPPER}} .skt-slider-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_arrow_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF96',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev:hover, {{WRAPPER}} .skt-slider-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev:hover, {{WRAPPER}} .skt-slider-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_border_color',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'arrow_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-prev:hover, {{WRAPPER}} .skt-slider-next:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __dots_style_controls() {

        $this->start_controls_section(
            '_section_pagination_dots_style',
            [
                'label' => __('Pagination - Dots', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination_type' => 'dots',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_position_y',
            [
                'label' => __('Vertical Position (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 800,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_spacing',
            [
                'label' => __('Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_size',
            [
                'label' => __('Size (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_align',
            [
                'label' => __('Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination' => 'justify-content: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dots_nav_box_shadow',
                'label' => __('Box Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-slider-pagination span',
            ]
        );

        $this->start_controls_tabs('_tabs_dots');
        $this->start_controls_tab(
            '_tab_dots_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'dots_nav_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'dots_nav_hover_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_active',
            [
                'label' => __('Active', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'dots_nav_active_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __pagination_number_style_controls() {

        $this->start_controls_section(
            '_section_pagination_number_style',
            [
                'label' => __('Pagination - Number', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination_type' => 'numbers',
                ],
            ]
        );


        $this->add_responsive_control(
            'numbers_nav_position_y',
            [
                'label' => __('Vertical Position (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 800,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'numbers_nav_spacing',
            [
                'label' => __('Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'numbers_nav_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
			],
                'selector' => '{{WRAPPER}} .skt-slider-pagination span',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'numbers_nav_box_shadow',
                'label' => __('Box Shadow', 'skt-addons-elementor'),
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
                'selector' => '{{WRAPPER}} .skt-slider-pagination span',
            ]
        );

        $this->add_responsive_control(
            'numbers_nav_align',
            [
                'label' => __('Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination' => 'justify-content: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs('_tabs_numbers');
        $this->start_controls_tab(
            '_tab_numbers_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F5F5F540',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_numbers_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_hover_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_hover_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#3871E8',
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_numbers_active',
            [
                'label' => __('Active', 'skt-addons-elementor'),
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_active_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span.swiper-pagination-bullet-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'numbers_nav_active_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#3871E8',
                'condition' => [
                    'number_pagination_type' => 'bullets'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination span.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __pagination_progressbar_style_controls() {

        $this->start_controls_section(
            '_section_pagination_progressbar_style',
            [
                'label' => __('Pagination - Progressbar', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination_type' => 'progressbar',
                ],
            ]
        );

        $this->add_responsive_control(
            'progressbar_height',
            [
                'label' => __('Height (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination.swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}; width: 100%',
                ],
                'condition' => [
                    'slider_direction' => 'horizontal',
                ],
            ]
        );

        $this->add_responsive_control(
            'progressbar_width',
            [
                'label' => __('Width (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination.swiper-pagination-progressbar' => 'width: {{SIZE}}{{UNIT}}; height: 100%',
                ],
                'condition' => [
                    'slider_direction' => 'vertical',
                ],
            ]
        );

        $this->start_controls_tabs('_tabs_progressbar');
        $this->start_controls_tab(
            '_tab_progressbar_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'progressbar_nav_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_progressbar_active',
            [
                'label' => __('Active', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'progressbar_nav_active_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ECDA6A',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __scroll_bar_style_controls() {

        $this->start_controls_section(
            '_section_scroll_bar_style',
            [
                'label' => __('Scroll Bar', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'scroll_bar' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'scrollbar_height',
            [
                'label' => __('Height (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-scrollbar.swiper-scrollbar' => 'height: {{SIZE}}{{UNIT}}; width: 100%',
                ],
                'condition' => [
                    'slider_direction' => 'horizontal',
                ],
            ]
        );

        $this->add_responsive_control(
            'scrollbar_width',
            [
                'label' => __('Width (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-scrollbar.swiper-scrollbar' => 'width: {{SIZE}}{{UNIT}}; height: 100%',
                ],
                'condition' => [
                    'slider_direction' => 'vertical',
                ],
            ]
        );

        $this->start_controls_tabs('_tabs_scrollbar');
        $this->start_controls_tab(
            '_tab_scrollbar_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'scrollbar_nav_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-scrollbar.swiper-scrollbar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_scrollbar_active',
            [
                'label' => __('Active', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'scrollbar_nav_active_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ECDA6A',
                'selectors' => [
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __nav_thumbnails_style_controls() {

        $this->start_controls_section(
            '_section_thumbs_navigation_style',
            [
                'label' => __('Navigation - Thumbnails', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'thumbs_navigation' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbs_top_spacing',
            [
                'label' => __('Top Spacing (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-gallery-thumbs' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbs_align',
            [
                'label' => __('Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-gallery-thumbs .swiper-wrapper' => 'justify-content: {{VALUE}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'thumbs_height',
            [
                'label' => __('Height', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 500,
                        'step' => 5,
                    ],
                    'em' => [
                        'min' => 0.1,
                        'max' => 16,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-gallery-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbs_width',
            [
                'label' => __('Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0.1,
                        'max' => 16,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-gallery-slide' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'thumbs_border',
                'label' => __('Border', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-slider-gallery-slide',
            ]
        );

        $this->add_control(
            'thumbs_active_border_color',
            [
                'label' => __('Active Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-slider-gallery-slide.swiper-slide-thumb-active' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'thumbs_border_border!' => ''
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $animation_class = (isset($settings['slider_content_animation']) && ($settings['slider_content_animation'] != 'none')) ? $settings['slider_content_animation'] : '';
?>
        <div class="skt-slider-widget-wrapper skt-unique-widget-id-<?php echo esc_attr($this->get_id()); ?> skt-slider-direction-<?php echo esc_attr($settings['slider_direction']); ?>">
            <div class="swiper-container gallery-top skt-slider-container">
                <div class="swiper-wrapper skt-slider-wrapper">
                    <?php if (is_array($settings['slides'])) :
                        foreach ($settings['slides'] as $slide) :
                    ?>
                            <div class="swiper-slide skt-slider-slide elementor-repeater-item-<?php echo esc_attr($slide['_id']); ?>">
                                <?php if ($slide['content_type'] == 'template') :
                                    echo skt_addons_elementor()->frontend->get_builder_content_for_display($slide['slide_content_template']);
                                elseif ($slide['content_type'] == 'default') : ?>
                                    <div class="skt-slider-content-wrapper elementor-repeater-item-<?php echo esc_attr($slide['_id']); ?>">
                                        <div class="skt-slider-content elementor-repeater-item-<?php echo esc_attr($slide['_id']); ?> <?php echo esc_attr($animation_class); ?>">
                                            <?php if ($slide['slide_content_icon'] === 'image' && ($slide['image']['url'] || $slide['image']['id'])) :
                                                $slide['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
                                            ?>
                                                <figure class="skt-slider-figure skt-slider-figure--image">
                                                    <?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($slide, 'thumbnail', 'image')); ?>
                                                </figure>
                                            <?php elseif (!empty($slide['icon']['value'])) : ?>
                                                <figure class="skt-slider-figure skt-slider-figure--icon">
                                                    <?php Icons_Manager::render_icon($slide['icon']); ?>
                                                </figure>
                                            <?php endif; ?>

                                            <?php if (!empty($slide['slide_content_title'])) : ?>
                                                <h2 class="skt-slider-content-title"><?php echo esc_html($slide['slide_content_title']); ?></h2>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['slide_content_sub_title'])) : ?>
                                                <h3 class="skt-slider-content-sub-title"><?php echo esc_html($slide['slide_content_sub_title']); ?></h3>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['slide_content_description'])) : ?>
                                                <div class="skt-slider-content-description"><?php echo esc_html($slide['slide_content_description']); ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['slide_content_button_1_text']) || !empty($slide['slide_content_button_2_text'])) : ?>
                                                <div class="skt-slider-buttons">
                                                    <?php if (!empty($slide['slide_content_button_1_text'])) : ?>
                                                        <a class="skt-slider-button button-1" href="<?php echo esc_url(isset($slide['slide_content_button_1_link']['url']) ? $slide['slide_content_button_1_link']['url'] : ''); ?>" <?php echo esc_attr(($slide['slide_content_button_1_link']['is_external']) ? 'target="_blank"' : ''); ?>><?php echo esc_html($slide['slide_content_button_1_text']); ?></a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($slide['slide_content_button_2_text'])) : ?>
                                                        <a class="skt-slider-button button-2" href="<?php echo esc_url(isset($slide['slide_content_button_2_link']['url']) ? $slide['slide_content_button_2_link']['url'] : ''); ?>" <?php echo esc_attr(($slide['slide_content_button_2_link']['is_external']) ? 'target="_blank"' : ''); ?>><?php echo esc_html($slide['slide_content_button_2_text']); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <?php if (!empty($settings['pagination_type']) && ($settings['pagination_type'] != 'none')) : ?>
                    <div class="swiper-pagination skt-slider-pagination"></div>
                <?php endif; ?>

                <?php if (!empty($settings['arrow_navigation']) && ($settings['arrow_navigation'] == 'yes')) : ?>
                    <div class="skt-slider-prev"><?php Icons_Manager::render_icon($settings['arrow_navigation_prev'], ['aria-hidden' => 'true']); ?></div>
                    <div class="skt-slider-next"><?php Icons_Manager::render_icon($settings['arrow_navigation_next'], ['aria-hidden' => 'true']); ?></div>
                <?php endif; ?>

                <?php if (!empty($settings['scroll_bar']) && ($settings['scroll_bar'] == 'yes')) : ?>
                    <div class="swiper-scrollbar skt-slider-scrollbar"></div>
                <?php endif; ?>

            </div>

            <?php if (!empty($settings['thumbs_navigation']) && ($settings['thumbs_navigation'] == 'yes')) : ?>
                <div class="swiper-container skt-slider-gallery-thumbs">
                    <div class="swiper-wrapper">
                        <?php if (is_array($settings['slides'])) :
                            foreach ($settings['slides'] as $slide) :
                        ?>
                                <div class="swiper-slide skt-slider-gallery-slide elementor-repeater-item-<?php echo esc_attr($slide['_id']); ?>">
                                    <?php if ($slide['content_type'] == 'template') :
                                        echo skt_addons_elementor()->frontend->get_builder_content_for_display($slide['slide_content_template']);
                                    endif; ?>
                                </div>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
<?php
    }
}