<?php
/**
 * Testimonial Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Testimonial_Carousel extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Testimonial Carousel', 'skt-addons-elementor' );
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
        return 'skti skti-testimonial-carousel';
    }

    public function get_keywords() {
        return [ 'testimonial', 'carousel', 'review', 'feedback' ];
    }

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__testimonial_content_controls();
		$this->__settings_content_controls();
	}

	protected function __testimonial_content_controls() {

        $this->start_controls_section(
            '_section_testimonial',
            [
                'label' => __( 'Testimonial Carousel', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => __( 'Name', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'SKT', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type Reviewer Name', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'CMO, SKT Addons', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type reviewer title', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'testimonial_content',
            [
                'label' => __( 'Testimonial', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Testimonial contents', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type testimonial', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'name' => __( 'SKT', 'skt-addons-elementor' ),
                        'title' => __( 'CEO SKT Addons', 'skt-addons-elementor' ),
                        'testimonial_content' => __( 'Testimonial Content', 'skt-addons-elementor' ),
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'name' => __( 'SKT', 'skt-addons-elementor' ),
                        'title' => __( 'CEO SKT Addons', 'skt-addons-elementor' ),
                        'testimonial_content' => __( 'Testimonial Content', 'skt-addons-elementor' ),
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'name' => __( 'SKT', 'skt-addons-elementor' ),
                        'title' => __( 'CEO SKT Addons', 'skt-addons-elementor' ),
                        'testimonial_content' => __( 'Testimonial Content', 'skt-addons-elementor' ),
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'name' => __( 'SKT', 'skt-addons-elementor' ),
                        'title' => __( 'CEO SKT Addons', 'skt-addons-elementor' ),
                        'testimonial_content' => __( 'Testimonial Content', 'skt-addons-elementor' ),
                    ],
                ],
            ]
        );

        $this->add_control(
            '_design',
            [
                'label' => __( 'Design', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'separator' => 'before',
                'options' => [
                    'basic' => __( 'Default', 'skt-addons-elementor' ),
                    'bubble' => __( 'Bubble', 'skt-addons-elementor' ),
                ],
                'default' => 'bubble',
                'prefix_class' => 'skt-testimonial-carousel--',
                'style_transfer' => true,
            ]
        );

		$this->add_control(
			'equal_height',
			[
				'label' => __( 'Equal Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'skt-equal-height-',
                'style_transfer' => true,
			]
		);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
                'exclude' => ['custom'],
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __( 'Testimonial Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'skt-addons-elementor' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'skt-addons-elementor' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'toggle' => false,
                'default' => 'top',
                'prefix_class' => 'skt-testimonial-carousel--',
                'selectors_dictionary' => [
                    'bottom' => 'flex-direction: column-reverse',
                    'top' => 'flex-direction: column',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__item' => '{{VALUE}}'
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'default' => 'left',
                'prefix_class' => 'skt-testimonial-carousel--',
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
	}

	protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __( 'Animation Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'default' => 800,
                'description' => __( 'Slide speed in milliseconds', 'skt-addons-elementor' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __( 'Autoplay Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'max' => 10000,
                'default' => 2000,
                'description' => __( 'Autoplay speed in milliseconds', 'skt-addons-elementor' ),
                'condition' => [
                    'autoplay' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Infinite Loop?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'vertical',
            [
                'label' => __( 'Vertical Mode?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __( 'Navigation', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'arrow' => __( 'Arrow', 'skt-addons-elementor' ),
                    'dots' => __( 'Dots', 'skt-addons-elementor' ),
                    'both' => __( 'Arrow & Dots', 'skt-addons-elementor' ),
                ],
                'default' => 'arrow',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __( 'Slides To Show', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => __( '1 Slide', 'skt-addons-elementor' ),
                    2 => __( '2 Slides', 'skt-addons-elementor' ),
                    3 => __( '3 Slides', 'skt-addons-elementor' ),
                    4 => __( '4 Slides', 'skt-addons-elementor' ),
                    5 => __( '5 Slides', 'skt-addons-elementor' ),
                    6 => __( '6 Slides', 'skt-addons-elementor' ),
                ],
                'desktop_default' => 3,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__testimonial_style_controls();
		$this->__image_style_controls();
		$this->__reviewer_style_controls();
		$this->__arrow_style_controls();
		$this->__dots_style_controls();
	}

    protected function __common_style_controls() {

        $this->start_controls_section(
            '_section_style_common',
            [
                'label' => __( 'Common', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Space Between items', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                '%' => [
                    'min' => 0,
                    'max' => 20,
                ],
                'px' => [
                    'min' => 0,
                    'max' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel-slick-slide' => 'padding: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

		$this->add_responsive_control(
			'testimonial_item_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial-carousel__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .skt-testimonial-carousel__item',
                'condition' => [
                     '_design' => 'basic'
                ]
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    '_design' => 'basic'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'condition' => [
                    '_design' => 'basic'
                ],
                'selector' => '{{WRAPPER}} .skt-testimonial-carousel__item',
            ]
        );

		$this->add_control(
			'testimonial_item_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'_design' => 'basic'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial-carousel__item' => 'background-color: {{VALUE}};'
				],
			]
		);

        $this->end_controls_section();
	}

    protected function __testimonial_style_controls() {

        $this->start_controls_section(
            '_section_style_testimonial',
            [
                'label' => __( 'Testimonial', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'testimonial_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'condition' => [
					'_design' => 'bubble'
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'testimonial_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}}.skt-testimonial-carousel--top .skt-testimonial-carousel__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-testimonial-carousel--bottom .skt-testimonial-carousel__content' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'testimonial_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'testimonial_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
				'condition' => [
					'_design' => 'bubble'
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .skt-testimonial-carousel__content:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'testimonial_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-testimonial-carousel__content',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_responsive_control(
            'testimonial_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'condition' => [
					'_design' => 'bubble'
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'testimonial_box_shadow',
                'condition' => [
                    '_design' => 'bubble'
                ],
                'selector' => '{{WRAPPER}} .skt-testimonial-carousel__content',
            ]
        );

        $this->end_controls_section();
	}

    protected function __image_style_controls() {

		$this->start_controls_section(
            '_section_style_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__reviewer-thumb' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-testimonial--left .skt-testimonial-carousel__content:after' => 'left: calc(({{SIZE}}{{UNIT}} / 2) - 18px);',
                    '{{WRAPPER}}.skt-testimonial--right .skt-testimonial-carousel__content:after' => 'right: calc(({{SIZE}}{{UNIT}} / 2) - 18px);',
                ],
            ]
        );

		$this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__reviewer-thumb' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}}.skt-testimonial-carousel--left .skt-testimonial-carousel__reviewer-thumb' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-testimonial-carousel--right .skt-testimonial-carousel__reviewer-thumb' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-testimonial-carousel--center .skt-testimonial-carousel__reviewer-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .skt-testimonial-carousel__reviewer-thumb img',
            ]
		);

		$this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__reviewer-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '.skt-testimonial-carousel__reviewer-thumb img',
            ]
        );

		$this->end_controls_section();
	}

    protected function __reviewer_style_controls() {

		$this->start_controls_section(
            '_section_style_reviewer',
            [
                'label' => __( 'Reviewer', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

        $this->add_control(
            'reviewer_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    '_display' => 'basic'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_name',
            [
                'label' => __( 'Name', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_control(
            'name_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__reviewer-name' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-testimonial-carousel__reviewer-name',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
		);

		$this->add_responsive_control(
            'name_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__reviewer-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

		$this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-testimonial-carousel__reviewer-title' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-testimonial-carousel__reviewer-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
		);

		$this->end_controls_section();
	}

    protected function __arrow_style_controls() {

        $this->start_controls_section(
            '_section_style_arrow',
            [
                'label' => __( 'Navigation - Arrow', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_position_toggle',
            [
                'label' => __( 'Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

		$this->start_popover();

		$this->add_control(
			'arrow_sync_position',
			[
				'label' => __( 'Sync Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'yes' => [
						'title' => __( 'Yes', 'skt-addons-elementor' ),
						'icon' => 'eicon-sync',
					],
					'no' => [
						'title' => __( 'No', 'skt-addons-elementor' ),
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

		$this->add_control(
			'sync_position_alignment',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'condition' => [
					'arrow_position_toggle' => 'yes',
					'arrow_sync_position' => 'yes'
				],
				'default' => 'center',
				'toggle' => false,
				'selectors_dictionary' => [
					'left' => 'left: 0',
					'center' => 'left: 50%',
					'right' => 'left: 100%',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => '{{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'arrow_position_y',
			[
				'label' => __( 'Vertical', 'skt-addons-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'top: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'arrow_position_x',
			[
				'label' => __( 'Horizontal', 'skt-addons-elementor' ),
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
					'{{WRAPPER}}.skt-arrow-sync-no .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-arrow-sync-no .slick-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-arrow-sync-yes .slick-next, {{WRAPPER}}.skt-arrow-sync-yes .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_spacing',
			[
				'label' => __( 'Space between Arrows', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes',
					'arrow_sync_position' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-arrow-sync-yes .slick-next' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Background Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 70,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_font_size',
			[
				'label' => __( 'Font Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next',
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( '_tabs_arrow' );

        $this->start_controls_tab(
            '_tab_arrow_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_arrow_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'arrow_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __dots_style_controls() {

        $this->start_controls_section(
            '_section_style_dots',
            [
                'label' => __( 'Navigation - Dots', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dots_nav_position_y',
            [
                'label' => __( 'Vertical Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( '_tabs_dots' );
        $this->start_controls_tab(
            '_tab_dots_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

		$this->add_control(
			'dots_nav_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'dots_nav_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_nav_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:hover:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_active',
            [
                'label' => __( 'Active', 'skt-addons-elementor' ),
            ]
        );

		$this->add_control(
			'dots_nav_active_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li.slick-active button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'dots_nav_active_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots .slick-active button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

        <div class="skt-testimonial-carousel__wrap">

            <?php
            foreach ( $settings['testimonials'] as $index => $testimonial ) :
                // testimonial content
                $testimonial_content = $this->get_repeater_setting_key( 'testimonial_content', 'testimonials', $index );
                $this->add_render_attribute( $testimonial_content, 'class', 'skt-testimonial-carousel__content' );

                // name
                $name = $this->get_repeater_setting_key( 'name', 'testimonials', $index );
                $this->add_render_attribute( $name, 'class', 'skt-testimonial-carousel__reviewer-name' );

                // title
                $title = $this->get_repeater_setting_key( 'title', 'testimonials', $index );
                $this->add_render_attribute( $title, 'class', 'skt-testimonial-carousel__reviewer-title' );

                // image
                $image = wp_get_attachment_image_url( $testimonial['image']['id'], $settings['thumbnail_size'] );
                if ( ! $image ) {
                    $image = $testimonial['image']['url'];
                }
            ?>
            <div class="skt-testimonial-carousel-slick-slide">
                <div class="skt-testimonial-carousel__item">
                    <div <?php echo wp_kses_post($this->get_render_attribute_string( $testimonial_content )); ?>>
                        <?php echo wp_kses_post($testimonial['testimonial_content']); ?>
                    </div>
                    <div class="skt-testimonial-carousel__reviewer">
                        <?php if ( ! empty( $image ) ) : ?>
                            <div class="skt-testimonial-carousel__reviewer-thumb">
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                            </div>
                        <?php endif; ?>

                        <div class="skt-testimonial-carousel__reviewer-meta">
                            <div <?php echo wp_kses_post($this->get_render_attribute_string( $name )); ?>>
                                <?php echo wp_kses_post($testimonial['name']); ?>
                            </div>
                            <div <?php echo wp_kses_post($this->get_render_attribute_string( $title )); ?>>
                                <?php echo wp_kses_post($testimonial['title']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            endforeach;
            ?>
        </div>
	    <?php
	}
}