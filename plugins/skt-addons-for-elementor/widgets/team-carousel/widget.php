<?php
/**
 * Team Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Team_Carousel extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Team Carousel', 'skt-addons-elementor' );
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
        return 'skti skti-team-carousel';
    }

    public function get_keywords() {
        return [ 'team', 'member', 'carousel', 'crew', 'staff', 'person' ];
    }

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__information_content_controls();
		$this->__settings_content_controls();
	}

	/**
     * Register widget style controls
     */
	protected function __information_content_controls() {

		$this->start_controls_section(
			'_section_info',
			[
				'label' => __( 'Information', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new Repeater();

        $repeater->start_controls_tabs('_tab_members' );
        $repeater->start_controls_tab(
            '_tab_info',
            [
                'label' => __( 'Information', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Photo', 'skt-addons-elementor' ),
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
            'title',
            [
                'label' => __( 'Name', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'SKT Member Name', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type Member Name', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'job_title',
            [
                'label' => __( 'Job Title', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'SKT Officer', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type Member Job Title', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'bio',
            [
                'label' => __( 'Short Bio', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Write something amazing about the skt member', 'skt-addons-elementor' ),
                'rows' => 5,
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            '_tab_social',
            [
                'label' => __( 'Links', 'skt-addons-elementor' ),
            ]
        );

		$repeater->add_control(
			'show_options',
			[
				'label' => __( 'Show Links', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'style_transfer' => true,
			]
		);

        $repeater->add_control(
            'website', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Website Address', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your profile link', 'skt-addons-elementor' ),
                'input_type' => 'url',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'email', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Email', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your Email address', 'skt-addons-elementor' ),
                'input_type' => 'email',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'facebook', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Facebook', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your Facebook address', 'skt-addons-elementor' ),
                'input_type' => 'url',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'twitter', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Twitter', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your Twitter address', 'skt-addons-elementor' ),
                'input_type' => 'url',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'instagram', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Instagram', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your Instagram address', 'skt-addons-elementor' ),
                'input_type' => 'url',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'github', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Github', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your Github address', 'skt-addons-elementor' ),
                'input_type' => 'url',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'linkedin', [
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'label' => __( 'LinkedIn', 'skt-addons-elementor' ),
                'placeholder' => __( 'Add your LinkedIn address', 'skt-addons-elementor' ),
                'input_type' => 'url',
				'condition' => [
					'show_options' => 'yes'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'members',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => __( 'SKT Member Name', 'skt-addons-elementor' ),
                        'job_title' => __( 'SKT Officer', 'skt-addons-elementor' ),
                        'facebook' => 'https://facebook.com',
                        'twitter' => 'https://twitter.com',
                    ],
                    [
                        'title' => __( 'SKT Member Name', 'skt-addons-elementor' ),
                        'job_title' => __( 'SKT Officer', 'skt-addons-elementor' ),
                        'facebook' => 'https://facebook.com',
                        'github' => 'https://github.com',
                        'twitter' => 'https://twitter.com',
                    ],
                    [
                        'title' => __( 'SKT Member Name', 'skt-addons-elementor' ),
                        'job_title' => __( 'SKT Officer', 'skt-addons-elementor' ),
                        'website' => 'https://example.com',
                        'linkedin' => 'https://linkedin.com',
                    ],
                    [
                        'title' => __( 'SKT Member Name', 'skt-addons-elementor' ),
                        'job_title' => __( 'SKT Officer', 'skt-addons-elementor' ),
                        'email' =>  'example@example.com',
                        'instagram' => 'https://instagram.com',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
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
                    ],
                    'justify' => [
                        'title' => __( 'Justify', 'skt-addons-elementor' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
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
     * Register styles related controls
     */
    protected function register_style_controls() {
		$this->__item_style_controls();
		$this->__photo_style_controls();
		$this->__name_title_bio_style_controls();
		$this->__social_style_controls();
		$this->__nav_arrow_style_controls();
		$this->__nav_dot_style_controls();
    }

	protected function __item_style_controls() {
        $this->start_controls_section(
            '_section_style_item',
            [
                'label' => __( 'Carousel Item', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_responsive_control(
			'item_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-team-carousel-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'item_spacing',
			[
				'label' => __( 'Space Between items', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 20,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
					],
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
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 20,
					],
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-team-carousel-item' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .skt-team-carousel-item'
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-team-carousel-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-member-figure img' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .skt-team-carousel-item',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'classic' => 'image'
				],
				'selector' => '{{WRAPPER}} .skt-team-carousel-item',
			]
		);

        $this->end_controls_section();
	}

	protected function __photo_style_controls() {

        $this->start_controls_section(
            '_section_style_image',
            [
                'label' => __( 'Photo', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 700,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-figure' => 'width: {{SIZE}}{{UNIT}};',
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
                        'min' => 100,
                        'max' => 700,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-figure > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .skt-member-figure > img'
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-figure > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .skt-member-figure > img'
            ]
        );

        $this->end_controls_section();

	}

	protected function __name_title_bio_style_controls() {

        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __( 'Name, Job Title & Bio', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Name', 'skt-addons-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .skt-member-name',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .skt-member-name',
            ]
        );

        $this->add_control(
            '_heading_job_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Job Title', 'skt-addons-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'job_title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_title_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-position' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_title_typography',
                'selector' => '{{WRAPPER}} .skt-member-position',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'job_title_text_shadow',
                'selector' => '{{WRAPPER}} .skt-member-position',
            ]
        );

        $this->add_control(
            '_heading_bio',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Short Bio', 'skt-addons-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'bio_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bio_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-bio' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bio_typography',
                'selector' => '{{WRAPPER}} .skt-member-bio',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'bio_text_shadow',
                'selector' => '{{WRAPPER}} .skt-member-bio',
            ]
        );

        $this->end_controls_section();

	}

	protected function __social_style_controls() {

        $this->start_controls_section(
            '_section_style_social',
            [
                'label' => __( 'Social Icons', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'links_spacing',
            [
                'label' => __( 'Right Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'links_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'links_icon_size',
            [
                'label' => __( 'Icon Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'links_border',
                'selector' => '{{WRAPPER}} .skt-member-links > a'
            ]
        );

        $this->add_responsive_control(
            'links_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( '_tab_links_colors' );
        $this->start_controls_tab(
            '_tab_links_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'links_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'links_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            '_tab_links_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'links_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a:hover, {{WRAPPER}} .skt-member-links > a:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'links_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a:hover, {{WRAPPER}} .skt-member-links > a:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'links_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-member-links > a:hover, {{WRAPPER}} .skt-member-links > a:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'links_border_border!' => '',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

	}

	protected function __nav_arrow_style_controls() {

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

	protected function __nav_dot_style_controls() {

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

        if ( ! is_array( $settings['members'] ) ) {
            return;
        }
        ?>

        <div class="skt-team-carousel-wrap">
            <?php foreach ( $settings['members'] as $index => $member ) :

                $title = $this->get_repeater_setting_key( 'title', 'members', $index );
                $this->add_render_attribute( $title, 'class', 'skt-member-name' );

                $job_title = $this->get_repeater_setting_key( 'job_title', 'members', $index );
                $this->add_render_attribute( $job_title, 'class', 'skt-member-position' );

                $bio = $this->get_repeater_setting_key( 'bio', 'members', $index );
                $this->add_render_attribute( $bio, 'class', 'skt-member-bio' );

                $image = wp_get_attachment_image_url( $member['image']['id'], $settings['thumbnail_size'] );
                if ( ! $image ) {
                    $image = $member['image']['url'];
                }
                ?>
                <div class="skt-slick-slide">
                    <div class="skt-team-carousel-item">

                        <?php if ( $image ) : ?>
                            <figure class="skt-member-figure">
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $member['title'] ); ?>">
                            </figure>
                        <?php endif; ?>

                        <div class="skt-member-body">
                            <?php if ( $member['title'] ) :
                                printf( '<%1$s %2$s>%3$s</%1$s>',
                                    skt_addons_elementor_escape_tags( $settings['title_tag'] ),
                                    $this->get_render_attribute_string( $title ),
                                    esc_html( $member['title'] )
                                );
                            endif; ?>

                            <?php if ( $member['job_title' ] ) : ?>
                                <div <?php echo wp_kses_post($this->get_render_attribute_string( $job_title )); ?>>
                                    <?php echo esc_html( $member['job_title' ] ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $member['bio'] ) : ?>
                                <div <?php echo wp_kses_post($this->get_render_attribute_string( $bio )); ?>>
                                    <p><?php echo wp_kses_post($member['bio']); ?></p>
                                </div>
                            <?php endif; ?>

							<?php if ( $member['show_options'] === 'yes' ) : ?>
								<div class="skt-member-links">
									<?php
									if ( !empty( ['website'] ) ) {
										$website = $this->get_repeater_setting_key('website', 'members', $index );
										$this->add_render_attribute( $website, 'class', 'skt-link' );
									}

									if ( !empty( $member['website'] ) ) :
										$website_address =  esc_url( $member['website'] );
										printf('<a href="%1$s" %2$s><i class="fa fa-globe"></i></a>',
											$website_address,
											$this->get_render_attribute_string( $website )
										);
									endif;

									if ( !empty( $member['email'] ) ) {
										$email = $this->get_repeater_setting_key( 'email', 'members', $index );
										$this->add_render_attribute( $email, 'class', 'skt-email' );
									}

									if ( !empty( $member['email'] ) ) :
										$email_address =  esc_html(antispambot( $member['email'] ));
										printf( '<a href="mailto: %1$s" %2$s><i class="fa fa-envelope"></i></a>',
											$email_address,
											$this->get_render_attribute_string( $email )
										);
									endif;

									if ( !empty( $member['facebook'] ) ) {
										$facebook = $this->get_repeater_setting_key('facebook', 'members', $index );
										$this->add_render_attribute( $facebook, 'class', 'skt-facebook' );
									}

									if ( !empty( $member['facebook'] ) ) :
										$facebook_address =  esc_url( $member['facebook'] );
										printf('<a href="%1$s" %2$s><i class="fa fa-facebook"></i></a>',
											$facebook_address,
											$this->get_render_attribute_string( $facebook )
										);
									endif;

									if ( !empty( $member['twitter'] ) ) {
										$twitter = $this->get_repeater_setting_key('twitter', 'members', $index );
										$this->add_render_attribute( $twitter, 'class', 'skt-twitter' );
									}

									if ( !empty( $member['twitter'] ) ) :
										$twitter_address =  esc_url( $member['twitter'] );
										printf('<a href="%1$s" %2$s><i class="fa fa-twitter"></i></a>',
											$twitter_address,
											$this->get_render_attribute_string( $twitter )
										);
									endif;

									if ( !empty( $member['instagram'] ) ) {
										$instagram = $this->get_repeater_setting_key('instagram', 'members', $index );
										$this->add_render_attribute( $twitter, 'class', 'skt-instagram' );
									}

									if ( !empty( $member['instagram'] ) ) :
										$instagram_address =  esc_url( $member['instagram'] );
										printf('<a href="%1$s" %2$s><i class="fa fa-instagram"></i></a>',
											$instagram_address,
											$this->get_render_attribute_string( $instagram )
										);
									endif;

									if ( !empty( $member['github'] ) ) {
										$github = $this->get_repeater_setting_key('github', 'members', $index );
										$this->add_render_attribute( $github, 'class', 'skt-github' );
									}

									if ( !empty( $member['github'] ) ) :
										$github_address =  esc_url( $member['github'] );
										printf('<a href="%1$s" %2$s><i class="fa fa-github"></i></a>',
											$github_address,
											$this->get_render_attribute_string( $github )
										);
									endif;

									if ( !empty( $member['linkedin'] ) ) {
										$linkedin = $this->get_repeater_setting_key('linkedin', 'members', $index );
										$this->add_render_attribute( $linkedin, 'class', 'skt-linkedin' );
									}

									if ( !empty( $member['linkedin'] ) ) :
										$linkedin_address =  esc_url( $member['linkedin'] );
										printf('<a href="%1$s" %2$s><i class="fa fa-linkedin"></i></a>',
											$linkedin_address,
											$this->get_render_attribute_string( $linkedin )
										);
									endif;
									?>
								</div>
							<?php endif; ?>

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