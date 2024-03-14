<?php
/**
 * Flip Box widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Flip_Box extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Flip Box', 'skt-addons-elementor' );
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
        return 'skti skti-flip-card1';
    }

    public function get_keywords() {
        return [ 'flip', 'box', 'info', 'content', 'animation' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__font_side_content_controls();
		$this->__back_side_content_controls();
		$this->__settings_content_controls();
	}

    protected function __font_side_content_controls() {

        $this->start_controls_section(
            '_section_front',
            [
                'label' => __( 'Front Side', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'front_icon_type',
            [
                'label' => __( 'Media Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'icon',
                'options' => [
                    'none' => [
                        'title' => __( 'None', 'skt-addons-elementor' ),
                        'icon' => 'eicon-close',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'skt-addons-elementor' ),
                        'icon' => 'eicon-star',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'skt-addons-elementor' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'toggle' => false,
                'style_transfer' => true,
            ]
        );

        if ( skt_addons_elementor_is_elementor_version( '<', '2.6.0' ) ) {
            $this->add_control(
                'front_icon',
                [
                    'label' => __( 'Icon', 'skt-addons-elementor' ),
                    'type' => Controls_Manager::ICON,
                    'options' => skt_addons_elementor_get_skt_addons_elementor_icons(),
                    'default' => 'fa fa-home',
                    'condition' => [
                        'front_icon_type' => 'icon'
                    ],
                ]
            );
        } else {
            $this->add_control(
                'front_selected_icon',
                [
                    'label' => __( 'Icon', 'skt-addons-elementor' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'front_icon',
                    'label_block' => true,
                    'default' => [
                        'value' => 'skti skti-home',
                        'library' => 'skt-icons',
                    ],
                    'condition' => [
                        'front_icon_type' => 'icon'
                    ],
                ]
            );
        }

        $this->add_control(
            'front_icon_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'front_icon_thumbnail',
                'default' => 'thumbnail',
                'exclude' => [
                    'full',
                    'shop_catalog',
                    'shop_single',
                ],
                'condition' => [
                    'front_icon_type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'front_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'label_block' => true,
                'separator' => 'before',
                'type' => Controls_Manager::TEXT,
                'default' => 'Start Marketing',
                'placeholder' => __( 'Type Flip Box Title', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

		$this->add_control(
			'front_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				// 'separator' => 'before',
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

        $this->add_control(
            'front_description',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'consectetur adipiscing elit, sed do<br>eiusmod Lorem ipsum dolor sit amet,<br> consectetur.',
                'placeholder' => __( 'Description', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'front_text_align',
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
					]
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .skt-flip-box-front-inner .skt-text' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
	}

    protected function __back_side_content_controls() {

        $this->start_controls_section(
            '_section_back',
            [
                'label' => __( 'Back Side', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'back_icon_type',
            [
                'label' => __( 'Media Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'none',
                'options' => [
                    'none' => [
                        'title' => __( 'None', 'skt-addons-elementor' ),
                        'icon' => 'eicon-close',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'skt-addons-elementor' ),
                        'icon' => 'eicon-star',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'skt-addons-elementor' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'toggle' => false,
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'back_icon_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'back_icon_type' => 'image'
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'back_icon_thumbnail',
                'default' => 'thumbnail',
                'exclude' => [
                    'full',
                    'shop_catalog',
                    'shop_single',
                ],
                'condition' => [
                    'back_icon_type' => 'image'
                ]
            ]
        );

        if ( skt_addons_elementor_is_elementor_version( '<', '2.6.0' ) ) {
            $this->add_control(
                'back_icon',
                [
                    'label' => __( 'Icon', 'skt-addons-elementor' ),
                    'type' => Controls_Manager::ICON,
                    'options' => skt_addons_elementor_get_skt_addons_elementor_icons(),
                    'default' => 'fa fa-home',
                    'condition' => [
                        'back_icon_type' => 'icon'
                    ],
                ]
            );
        } else {
            $this->add_control(
                'back_selected_icon',
                [
                    'label' => __( 'Icon', 'skt-addons-elementor' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'back_icon',
                    'label_block' => true,
                    'default' => [
                        'value' => 'fas fa-smile-wink',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'back_icon_type' => 'icon'
                    ],
                ]
            );
        }

        $this->add_control(
            'back_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'label_block' => true,
                'separator' => 'before',
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Start Marketing', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type Flip Box Title', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

		$this->add_control(
			'back_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				// 'separator' => 'before',
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

        $this->add_control(
            'back_description',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'consectetur adipiscing elit, sed do<br>eiusmod Lorem ipsum dolor sit amet.',
                'placeholder' => __( 'Description', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

		$this->add_control(
			'show_button',
			[
				'label' => __( 'Show Button', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Button Text', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://example.com', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_button' => 'yes'
				]
			]
		);

        $this->add_control(
            'back_text_align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
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
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .icon-wrap' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-text' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn-container' => 'text-align: {{VALUE}}',
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
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
			'animation_type',
			[
				'label' => __( 'Animation Type', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'classic',
				'label_block' => false,
				'options' => [
					'classic' => [
						'title' => __( 'Classic', 'skt-addons-elementor' ),
						'icon' => 'eicon-square',
					],
					'3d' => [
						'title' => __( '3D', 'skt-addons-elementor' ),
						'icon' => 'eicon-animation',
					],
				],
				'toggle' => false,
                'style_transfer' => true,
			]
		);

        $this->add_control(
            'flip_position',
            [
                'label' => __( 'Flip Direction', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left-right',
                'label_block' => false,
                'options' => [
					'top-bottom' => [
						'title' => __( 'Top To Bottom ', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'right-left' => [
						'title' => __( 'Right to Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
                    'bottom-top' => [
                        'title' => __( 'Bottom To Top', 'skt-addons-elementor' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'left-right' => [
                        'title' => __( 'Left To Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'style_transfer' => true,
            ]
        );

		$this->add_control(
			'3d_duration',
			[
				'label' => __( 'Duration', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'condition' => [
					'animation_type'	=> '3d',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-effect-3d .skt-flip-box-front' => 'transition-duration: {{SIZE}}ms;',
					'{{WRAPPER}} .skt-flip-effect-3d .skt-flip-box-back' => 'transition-duration: {{SIZE}}ms;',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'front_transform_offset_toggle',
			[
				'label' => __( 'Front Transform', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'condition' => [
					'animation_type'	=> 'classic',
				],
                'style_transfer' => true,
			]
		);

		$this->start_popover();

		$this->add_control(
			'front_transform_x',
			[
				'label' => __( 'Transform Origin X', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'unit' => 'px',
					'size' => '50'
				],
				'condition' => [
					'animation_type'	=> 'classic',
					'front_transform_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'front_transform_y',
			[
				'label' => __( 'Transform Origin Y', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'unit' => 'px',
					'size' => '50'
				],
				'condition' => [
					'animation_type'	=> 'classic',
					'front_transform_offset_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-effect-classic .skt-flip-box-front' => 'transform-origin: {{front_transform_x.SIZE || 0}}% {{front_transform_y.SIZE || 0}}%;',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'front_duration',
			[
				'label' => __( 'Duration', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
					],
				],
				'condition' => [
					'animation_type'	=> 'classic',
					'front_transform_offset_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-effect-classic .skt-flip-box-front' => 'transition-duration: {{SIZE}}ms;',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'back_transform_offset_toggle',
			[
				'label' => __( 'Back Transform', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'condition' => [
					'animation_type'	=> 'classic',
				],
                'style_transfer' => true,
			]
		);

		$this->start_popover();

		$this->add_control(
			'back_transform_origin_x',
			[
				'label' => __( 'Transform Origin X', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'unit' => 'px',
					'size' => '50'
				],
				'condition' => [
					'animation_type'	=> 'classic',
					'back_transform_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'back_transform_origin_y',
			[
				'label' => __( 'Transform Origin Y', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'unit' => 'px',
					'size' => '50'
				],
				'condition' => [
					'animation_type'	=> 'classic',
					'back_transform_offset_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-effect-classic .skt-flip-box-back' => 'transform-origin: {{back_transform_origin_x.SIZE || 0}}% {{back_transform_origin_y.SIZE || 0}}%;',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'back_duration',
			[
				'label' => __( 'Duration', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
					],
				],
				'condition' => [
					'animation_type'	=> 'classic',
					'back_transform_offset_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-effect-classic .skt-flip-box-back' => 'transition-duration: {{SIZE}}ms;',
				],
			]
		);

		$this->end_popover();

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__font_side_style_controls();
		$this->__back_side_style_controls();
		$this->__back_side_button_style_controls();
	}

    protected function __common_style_controls() {

        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-flip-box-back' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_area_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-flip-box-front:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-flip-box-back' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-flip-box-back:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __font_side_style_controls() {

        $this->start_controls_section(
            '_section_front_style',
            [
                'label' => __( 'Front Side', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'front_content_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'front_border',
                'selector' => '{{WRAPPER}} .skt-flip-box-front',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'front_box_shadow',
                'selector' => '{{WRAPPER}} .skt-flip-box-front',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'front_background_image',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .skt-flip-box-front',
            ]
        );

        $this->add_control(
            'front_background_overlay',
            [
                'label' => __( 'Background Overlay', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_background_image_background' => 'classic'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_icon_heading',
            [
                'label' => __( 'Media Type - Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'front_icon_heading_image',
            [
                'label' => __( 'Media Type - Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'front_icon_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front .skt-flip-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_image_size',
            [
                'label' => __( 'Resize Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_image_fit',
            [
                'label' => __( 'Image Fit', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'contain'  => __( 'Contain', 'skt-addons-elementor' ),
                    'cover' => __( 'Cover', 'skt-addons-elementor' ),
                ],
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_font_size',
            [
                'label' => __( 'Icon Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300
					],
					'em' => [
						'min' => 6,
						'max' => 300
					]
				],
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .skt-flip-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_background_size',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'front_icon_border',
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selector' => '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon',
            ]
        );

        $this->add_control(
            'front_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-flip-box-front-inner .skt-flip-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'front_icon_box_shadow',
                'selector' => '{{WRAPPER}} .skt-flip-box-front-inner .skt-flip-icon',
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
            ]
        );

        $this->add_control(
            'front_icon_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_icon_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .icon-wrap .skt-flip-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_text',
            [
                'label' => __( 'Title & Description', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_tabs_front_text' );
        $this->start_controls_tab(
            '_tab_front_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'front_title_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .skt-flip-box-heading' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'front_title_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-front-inner .skt-flip-box-heading',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'front_title_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-front-inner .skt-flip-box-heading',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_front_description',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
            ]
        );

        $this->add_responsive_control(
            'front_description_space',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .skt-text p' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_description_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-front-inner .skt-text p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'front_description_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-front-inner .skt-text p',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'front_description_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-front-inner .skt-text p',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __back_side_style_controls() {

        $this->start_controls_section(
            '_section_back_text_style',
            [
                'label' => __( 'Back Side', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'back_content_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'back_border',
                'selector' => '{{WRAPPER}} .skt-flip-box-back',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'back_box_shadow',
                'selector' => '{{WRAPPER}} .skt-flip-box-back',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'back_background_image',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .skt-flip-box-back',
            ]
        );

        $this->add_control(
            'back_background_overlay',
            [
                'label' => __( 'Background Overlay', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.27)',
                'condition' => [
                    'back_background_image_background' => 'classic'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_background_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#27374c',
                'condition' => [
                    'back_background_type' => 'color'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_icon_heading',
            [
                'label' => __( 'Media Type - Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
            ]
        );

        $this->add_control(
            'back_icon_heading_image',
            [
                'label' => __( 'Media Type - Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'back_icon_type' => 'image'
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_image_size',
            [
                'label' => __( 'Resize Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'back_icon_type' => 'image'
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'back_icon_image_fit',
            [
                'label' => __( 'Image Fit', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'contain'  => __( 'Contain', 'skt-addons-elementor' ),
                    'cover' => __( 'Cover', 'skt-addons-elementor' ),
                ],
                'condition' => [
                    'back_icon_type' => 'image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .icon-wrap .skt-flip-icon img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_font_size',
            [
                'label' => __( 'Icon Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300
					],
					'em' => [
						'min' => 6,
						'max' => 300
					]
				],
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .icon-wrap .skt-flip-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'back_icon_border',
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .icon-wrap .skt-flip-icon',
            ]
        );

        $this->add_control(
            'back_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image']
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .icon-wrap .skt-flip-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'back_icon_box_shadow',
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image']
                ],
                'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon',
            ]
        );

        $this->add_control(
            'back_icon_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_icon_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .icon-wrap .skt-flip-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_text',
            [
                'label' => __( 'Title & Description', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_tabs_back_text' );
        $this->start_controls_tab(
            '_tab_back_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
            ]
        );

		$this->add_responsive_control(
			'back_title_space',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-box-heading-back' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'back_title_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-box-heading-back' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'back_title_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-box-heading-back',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'back_title_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-box-heading-back',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_back_description',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'back_description_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-flip-box-back-inner .skt-text p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'back_description_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-text p',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'back_description_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-text p',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __back_side_button_style_controls() {

		// back side button
		$this->start_controls_section(
			'_section_back_button_style',
			[
				'label' => __( 'Back Side - Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'note',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<strong>Button</strong> is Hidden from "Back Side" content section', 'skt-addons-elementor' ),
				'condition' => [
					'show_button!' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'back_button_border',
				'condition' => [
					'show_button' => 'yes',
				],
				'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn',
			]
		);

		$this->add_control(
			'back_button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_button_box_shadow',
				'condition' => [
					'show_button' => 'yes',
				],
				'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_button_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( '_tabs_back_button' );
		$this->start_controls_tab(
			'_tab_back_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'back_button_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_button_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_back_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'back_button_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_button_hover_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'back_button_border_border!' => '',
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-flip-box-back-inner .skt-flip-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // icon/image
        if ( isset($settings['front_icon_image']['id']) && isset( $settings['front_icon_image']['url'] ) ) {
            $this->add_render_attribute( 'front_icon_image', 'src', $settings['front_icon_image']['url'] );
            $this->add_render_attribute( 'front_icon_image', 'alt', Control_Media::get_image_alt( $settings['front_icon_image'] ) );
            $this->add_render_attribute( 'front_icon_image', 'title', Control_Media::get_image_title( $settings['front_icon_image'] ) );
        }

        // title & description
        $this->add_render_attribute( 'front_title', 'class', 'skt-flip-box-heading' );
        $this->add_render_attribute( 'back_title', 'class', 'skt-flip-box-heading-back' );
        $this->add_render_attribute( 'front_description', 'class', 'skt-desc' );
        $this->add_render_attribute( 'back_description', 'class', 'skt-desc' );
        $this->add_inline_editing_attributes( 'back_description', 'intermediate' );

		// link
		$this->add_render_attribute('link', 'class', 'skt-flip-btn');
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes('link', $settings['link'] );
		}

        // display type
		if ( $settings['animation_type'] === 'classic' ) {
			$this->add_render_attribute('display', 'class', 'skt-flip-box-container skt-flip-effect-classic');
		} elseif ( $settings['animation_type'] === '3d' ) {
			$this->add_render_attribute('display', 'class', 'skt-flip-box-container skt-flip-effect-3d');
		}

        // flip position
        $this->add_render_attribute( 'flip-position', 'class', 'skt-flip-box-inner' );
        if ( $settings['flip_position'] === 'top-bottom' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'skt-flip-down' );
        } elseif ( $settings['flip_position'] === 'right-left' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'skt-flip-left' );
        } elseif ( $settings['flip_position'] === 'bottom-top' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'skt-flip-up' );
        } elseif ( $settings['flip_position'] === 'left-right' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'skt-flip-right' );
        }
        ?>

        <div <?php $this->print_render_attribute_string( 'display' ); ?>>

            <div <?php $this->print_render_attribute_string( 'flip-position' ); ?>>
                <div class="skt-flip-box-inner-wrapper">
                    <div class="skt-flip-box-front">
                        <div class="skt-flip-box-front-inner">
                            <div class="icon-wrap">
                                <?php if ( ! empty( $settings['front_icon'] ) || ! empty( $settings['front_selected_icon'] ) ) : ?>
                                    <span class="skt-flip-icon icon">
                                        <?php skt_addons_elementor_render_icon( $settings, 'front_icon', 'front_selected_icon' ); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ( $settings['front_icon_image'] ) : ?>
                                    <div class="skt-flip-icon">
                                        <?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'front_icon_thumbnail', 'front_icon_image' )); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="skt-text">
                                <?php
									if ( $settings['front_title'] ) {
										printf( '<%1$s %2$s>%3$s</%1$s>',
											skt_addons_elementor_escape_tags( $settings['front_title_tag'], 'h2' ),
											$this->get_render_attribute_string( 'front_title' ),
											skt_addons_elementor_kses_basic( $settings['front_title'] )
										);
									}
								?>

                                <?php if ( $settings['front_description'] ) : ?>
                                    <p <?php $this->print_render_attribute_string( 'front_description' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['front_description'] )); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="skt-flip-box-back">
                        <div class="skt-flip-box-back-inner">
                            <div class="icon-wrap">
                                <?php if ( ! empty( $settings['back_icon'] ) || ! empty( $settings['back_selected_icon'] ) ) : ?>
                                    <span class="skt-flip-icon icon">
                                        <?php skt_addons_elementor_render_icon( $settings, 'back_icon', 'back_selected_icon' ); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ( $settings['back_icon_image'] ) : ?>
                                    <div class="skt-flip-icon">
                                        <?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'back_icon_thumbnail', 'back_icon_image' )); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="skt-text">
                                <?php
									if ( $settings['back_title'] ) {
										printf( '<%1$s %2$s>%3$s</%1$s>',
											skt_addons_elementor_escape_tags( $settings['back_title_tag'], 'h2' ),
											$this->get_render_attribute_string( 'back_title' ),
											skt_addons_elementor_kses_basic( $settings['back_title'] )
										);
									}
								?>

                                <?php if ( $settings['back_description'] ) : ?>
                                    <p <?php $this->print_render_attribute_string( 'back_description' ) ?>><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['back_description'] )); ?></p>
                                <?php endif; ?>
                            </div>

							<?php if ( !empty( $settings['button_text'] ) ) : ?>
							<div class="skt-flip-btn-container">
								<a <?php $this->print_render_attribute_string( 'link' ); ?>>
									<?php echo esc_html( $settings['button_text'] ); ?>
								</a>
							</div>
							<?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}