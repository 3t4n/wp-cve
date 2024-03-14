<?php
/**
 * Logo Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Logo_Carousel extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Logo Carousel', 'skt-addons-elementor' );
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
		return 'skti skti-logo-carousel';
	}

	public function get_keywords() {
		return [ 'logo', 'carousel', 'brand' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__logo_content_controls();
		$this->__settings_content_controls();
	}

	protected function __logo_content_controls() {

        $this->start_controls_section(
            '_section_content_image',
            [
                'label' => __( 'Logo', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'logo_image',
            [
                'label' => __( 'Logo', 'skt-addons-elementor' ),
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
			'link',
			[
				'label' => __( 'Website Url', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://example.com', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

        $repeater->add_control(
            'name',
            [
                'label' => __( 'Brand Name', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Company Name', 'skt-addons-elementor' ),
                'description' => __( 'Add relevant name of the logo, for better SEO.', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'logo_items',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '<# print(name || "Carousel Item"); #>',
                'default' => [
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
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
            'center',
            [
                'label' => __( 'Center Mode?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'description' => __( 'Best works with odd number of slides (Slides To Show) and loop (Infinite Loop)', 'skt-addons-elementor' ),
                'frontend_available' => true,
                'style_transfer' => true,
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
                'desktop_default' => 4,
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
		$this->__logo_style_controls();
		$this->__arrow_style_controls();
		$this->__dots_style_controls();
	}

	protected function __logo_style_controls() {

		$this->start_controls_section(
			'_section_hover_box_style',
			[
				'label' => __( 'Logo', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 600,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-logo-carousel-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_resize_image',
			[
				'label' => __( 'Resize Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 400,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-logo-carousel-item img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-slick-slide' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 200,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-carousel-item' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .skt-logo-carousel-item',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .skt-logo-carousel-item',
			]
		);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background_image',
                'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'classic' => 'image'
				],
                'selector' => '{{WRAPPER}} .skt-logo-carousel-item',
            ]
        );

		$this->start_controls_tabs(
			'_tabs_item_image_effects',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'_tab_item_image_effects_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'item_image_opacity',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-logo-carousel-item > img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'item_image_css_filters',
				'selector' => '{{WRAPPER}} .skt-logo-carousel-item > img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-logo-carousel-item:hover > img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .skt-logo-carousel-item:hover > img',
			]
		);

		$this->add_control(
			'image_bg_hover_transition',
			[
				'label' => __( 'Transition Duration', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-logo-carousel-item:hover > img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

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

        if ( empty( $settings['logo_items'] ) ) {
            return;
        }
		?>

        <div class="skt-logo-carousel-wrap">
            <?php
            foreach ( $settings['logo_items'] as $index => $logo ) :
                $image = wp_get_attachment_image_url( $logo['logo_image']['id'], $settings['thumbnail_size'] );
                if ( ! $image ) {
                    $image = $logo['image']['url'];
                }

				// link
				$repeater_key = 'logo_items' . $index;
				$this->add_render_attribute( $repeater_key, 'class', 'skt-logo-carousel-item' );
				if ( ! empty( $logo['link']['url'] ) ) {
					$this->add_link_attributes( $repeater_key, $logo['link'] );
				}
                ?>
				<div class="skt-slick-slide">
					<?php if ( !empty( $logo['link']['url'] ) ) : ?>

						<a <?php $this->print_render_attribute_string( $repeater_key ); ?>>
							<img
								src="<?php echo esc_url( $image ); ?>"
								class="elementor-animation-<?php echo esc_attr( $settings['hover_animation'] ); ?>"
								title="<?php echo esc_attr( $logo['name'] ); ?>"
								alt="<?php echo esc_attr( $logo['name'] ); ?>"
							>
						</a>

					<?php else: ?>

						<div class="skt-logo-carousel-item">
							<img
								src="<?php echo esc_url( $image ); ?>"
								class="elementor-animation-<?php echo esc_attr( $settings['hover_animation'] ); ?>"
								title="<?php echo esc_attr( $logo['name'] ); ?>"
								alt="<?php echo esc_attr( $logo['name'] ); ?>"
							>
						</div>

					<?php endif; ?>
                </div>

                <?php
            endforeach;
            ?>
        </div>

        <?php
	}
}