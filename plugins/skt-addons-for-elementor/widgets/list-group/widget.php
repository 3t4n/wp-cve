<?php
/**
 * List Group widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class List_Group extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'List Group', 'skt-addons-elementor' );
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
        return 'skti skti-list-group';
    }

    public function get_keywords() {
        return [ 'list', 'group', 'item', 'icon' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__list_items_content_controls();
		$this->__settings_content_controls();
	}

    protected function __list_items_content_controls() {

        $this->start_controls_section(
            '_section_list_group',
            [
                'label' => __( 'List Items', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon_type',
            [
                'label' => __( 'Media Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'icon',
				'options' => [
					'none' => [
						'title' => __( 'None', 'skt-addons-elementor' ),
						'icon' => ' eicon-editor-close',
					],
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'number' => [
						'title' => __( 'Number', 'skt-addons-elementor' ),
						'icon' => 'eicon-number-field',
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

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-smile',
					'library' => 'regular',
				],
				'condition' => [
					'icon_type' => 'icon'
				],
			]
		);

        $repeater->add_control(
            'number',
            [
                'label' => __( 'Item Number', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'List Item Number', 'skt-addons-elementor' ),
                'default' => __( '1', 'skt-addons-elementor' ),
                'condition' => [
                    'icon_type' => 'number'
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image'
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
                'placeholder' => __( 'List Item', 'skt-addons-elementor' ),
                'default' => __( 'Build beautiful websites', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
			'badge_text',
			[
				'label' => __( 'Badge Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( '', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type badge text', 'skt-addons-elementor' ),
				'description' => __( 'Set badge style settings from Style tab', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
				'description' => skt_addons_elementor_get_allowed_html_desc( 'basic' ),
                'label_block' => true,
                'placeholder' => __( 'List Item Description', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

		$repeater->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'skti skti-play-next',
					'library' => 'skt-icons',
				]
			]
		);

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'skt-addons-elementor' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'custom_look',
            [
                'label' => __( 'Custom Look', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
                'style_transfer' => true,
            ]
        );

        $repeater->start_controls_tabs( '_tabs_icon',[
            'condition' => [
                'custom_look' => 'yes',
            ],
        ] );

        $repeater->start_controls_tab(
            '_tab_icon_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-text .skt-list-title' => 'color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'description_color',
            [
                'label' => __( 'Description Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-text .skt-list-detail' => 'color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap' => 'background-color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
			'icon_visibility',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 1,
                        'step' => 0.1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-direction' => 'opacity: {{SIZE}};',
				],
			]
		);

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            '_tab_icon_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'title_hover_color',
            [
                'label' => __( 'Title Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover .skt-text .skt-list-title' => 'color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'description_hover_color',
            [
                'label' => __( 'Description Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover .skt-text .skt-list-detail' => 'color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'background_hover_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'border-color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'border_hover_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'border-color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
			'icon_hover_visibility',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .skt-direction' => 'opacity: {{SIZE}};',
				],
			]
		);

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $repeater->add_control(
            'title_heading',
            [
                'label' => __( 'Direction Arrow Style', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $repeater->start_controls_tabs( '_tabs_direction' );
        $repeater->start_controls_tab(
            '_tab_direction_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'custom_direction_link_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap .skt-direction i' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap .skt-direction svg' => 'fill: {{VALUE}} !important',
                ],
            ]
        );

        $repeater->add_control(
            'custom_direction_link_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap .skt-direction' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            '_tab_direction_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'custom_direction_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap:hover .skt-direction i' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap:hover .skt-direction svg' => 'fill: {{VALUE}} !important',
                ],
            ]
        );

        $repeater->add_control(
            'custom_direction_hover_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap:hover .skt-direction' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $repeater->add_control(
            'custom_direction_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'direction_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-item-wrap:hover .skt-direction' => 'border-color: {{VALUE}} !important',
                ],
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();


        $this->add_control(
            'list_item',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => __( 'Build beautiful websites', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'Floating Effect', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'CSS Transform', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'Fast and Lightweight', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                ],
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
			'title_tag',
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
				'default' => 'h4',
			]
		);

        $this->add_control(
            'mode',
            [
                'label' => __( 'List Mode', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'compact' => [
                        'title' => __( 'Compact', 'skt-addons-elementor' ),
                        'icon' => 'eicon-square',
                    ],
                    'comfy' => [
                        'title' => __( 'Comfy', 'skt-addons-elementor' ),
                        'icon' => 'eicon-menu-bar',
                    ],
                ],
                'toggle' => false,
                'default' => 'compact',
                'prefix_class' => 'skt-mode--',
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'direction_position',
            [
                'label' => __( 'Direction Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'prefix_class' => 'skt-direction--',
                'default' => 'right',
                'selectors_dictionary' => [
                    'left' => 'flex-direction: row-reverse',
                    'right' => 'flex-direction: row',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap' => '{{VALUE}};',
                ],
                'style_transfer' => true,
            ]
        );

		$this->add_control(
			'text_alignment',
			[
				'label' => __( 'Text Alignment', 'skt-addons-elementor' ),
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
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .skt-text'  => 'text-align: {{VALUE}};'
				],
			]
		);

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__media_style_controls();
		$this->__title_desc_style_controls();
		$this->__badge_style_controls();
		$this->__direction_style_controls();
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
            'item_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 200,
                    ]
                ],
                'condition' => [
                     'mode' => 'comfy'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'list_border_type',
            [
                'label' => __( 'Border Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'solid' => __( 'Solid', 'skt-addons-elementor' ),
                    'double' => __( 'Double', 'skt-addons-elementor' ),
                    'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
                    'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}}.skt-mode--compact .skt-list-wrap' => 'border-style: {{VALUE}}',
                    '{{WRAPPER}}.skt-mode--compact .skt-list-item:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
                    '{{WRAPPER}}.skt-mode--comfy .skt-list-item' => 'border-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_border_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'list_border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-mode--compact .skt-list-wrap' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.skt-mode--compact .skt-list-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.skt-mode--comfy .skt-list-item' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'list_border_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'list_border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-mode--compact .skt-list-wrap' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.skt-mode--compact .skt-list-item:not(:last-child)' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.skt-mode--comfy .skt-list-item' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}.skt-mode--compact .skt-list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.skt-mode--comfy .skt-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_box_shadow',
                'selector' => '{{WRAPPER}}.skt-mode--compact .skt-list-wrap, {{WRAPPER}}.skt-mode--comfy .skt-list-item',
            ]
        );

        $this->start_controls_tabs( '_common_colors');


        $this->start_controls_tab(
            '_common_colors_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'common_title_color',
            [
                'label' => __( 'Title Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-text .skt-list-title' => 'color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'common_description_color',
            [
                'label' => __( 'Description Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-text .skt-list-detail' => 'color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'common_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap' => 'background-color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'common_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item' => 'border-color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_common_colors_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'common_title_hover_color',
            [
                'label' => __( 'Title Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:hover .skt-text .skt-list-title' => 'color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'common_description_hover_color',
            [
                'label' => __( 'Description Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:hover .skt-text .skt-list-detail' => 'color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'common_background_hover_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap:hover' => 'background-color: {{VALUE}}',
                    // '{{WRAPPER}} .skt-list-item' => 'border-color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'common_border_hover_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:hover' => 'border-color: {{VALUE}} !important',
                ],
                'style_transfer' => true,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __media_style_controls() {

        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Media Type', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-direction--right .skt-list-item .skt-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-direction--left .skt-list-item .skt-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-icon.icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-list-item .skt-icon.number span' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-list-item .skt-icon.image img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_background_spacing',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .skt-list-item .skt-icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-list-item .skt-icon.image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} .skt-list-item .skt-icon',
            ]
        );

        $this->start_controls_tabs( '_tabs_icon' );
        $this->start_controls_tab(
            '_tab_icon_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item .skt-icon svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item .skt-icon span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_background',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_icon_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:hover .skt-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item:hover .skt-icon svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item:hover .skt-icon span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_background',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item a:hover .skt-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_border',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                     'icon_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item a:hover .skt-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __title_desc_style_controls() {

        $this->start_controls_section(
            '_section_text',
            [
                'label' => __( 'Title & Description', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-text .skt-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .skt-text .skt-list-title',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->start_controls_tabs( '_tabs_title' );
        $this->start_controls_tab(
            '_tab_title_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'title_link_color',
            [
                'label' => __( 'Link Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item a .skt-item-wrap .skt-text .skt-list-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_title_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Link Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item a:hover .skt-item-wrap .skt-text .skt-list-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'description_heading',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-text .skt-list-detail' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .skt-text .skt-list-detail',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->end_controls_section();
	}

    protected function __badge_style_controls() {

        $this->start_controls_section(
			'_section_style_badge',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'badge_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .skt-badge' => 'margin-left: {{SIZE}}{{UNIT}} !important',
				],
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'badge_border',
				'selector' => '{{WRAPPER}} .skt-badge',
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .skt-badge',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-badge',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->end_controls_section();
	}

    protected function __direction_style_controls() {

        $this->start_controls_section(
            '_section_direction',
            [
                'label' => __( 'Direction', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'direction_animation',
			[
				'label' => __( 'Direction Animation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'skt-addons-elementor' ),
				'label_off' => __( 'Off', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'direction_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'selectors' => [
					'{{WRAPPER}}.skt-direction--left .skt-direction' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-direction--right .skt-direction' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'direction_font_size',
            [
                'label' => __( 'Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-direction' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'direction_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-direction' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'direction_border',
                'selector' => '{{WRAPPER}} .skt-list-item  .skt-direction',
            ]
        );

        $this->add_control(
            'direction_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item  .skt-direction' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'direction_box_shadow',
                'selector' => '{{WRAPPER}} .skt-list-item  .skt-direction',
            ]
        );

        $this->add_control(
            'direction_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-direction i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item .skt-direction svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-direction' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs( '_tabs_direction' );
        $this->start_controls_tab(
            '_tab_direction_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'direction_link_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap .skt-direction i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap .skt-direction svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_link_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap .skt-direction' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_direction_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'direction_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap:hover .skt-direction i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap:hover .skt-direction svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_hover_background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap:hover .skt-direction' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'direction_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item .skt-item-wrap:hover .skt-direction' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty($settings['list_item'] ) ) {
            return;
        }

        // Enable Hover Direction animation
        $item_wrap_class = "";
        if( $settings['direction_animation'] == 'yes'){
            $item_wrap_class = "skt-list-item-custom";
        }
        ?>

        <ul class="skt-list-wrap">
            <?php foreach ( $settings['list_item'] as $index => $item ) :
                // title
                $title_key = $this->get_repeater_setting_key( 'title', 'list_item', $index );
                //$this->add_inline_editing_attributes( $title_key, 'basic' );
                $this->add_render_attribute( $title_key, 'class', 'skt-list-title' );

                // description
                $description_key = $this->get_repeater_setting_key( 'description', 'list_item', $index );
                //$this->add_inline_editing_attributes( $description_key, 'basic' );
                $this->add_render_attribute( $description_key, 'class', 'skt-list-detail' );

                // badge
                $badge_key = $this->get_repeater_setting_key( 'badge_text', 'list_item', $index );
                //$this->add_inline_editing_attributes( $badge_key, 'basic' );
                $this->add_render_attribute( $badge_key, 'class', 'skt-badge' );

                // link
                if ( $item['link']['url'] ) {
                    $link_key = $this->get_repeater_setting_key( 'link', 'list_item', $index );
                    $this->add_render_attribute( $link_key, 'class', 'skt-link' );
                    $this->add_link_attributes( $link_key, $item['link'] );
                }

                ?>

                <li class="skt-list-item <?php echo wp_kses_post($item_wrap_class); ?> elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">

                    <?php if ( !empty( $item['link']['url'] ) ) : ?>
                    <a <?php $this->print_render_attribute_string( $link_key ); ?>>
                    <?php endif; ?>

                        <div class="skt-item-wrap">

							<?php if ( ! empty( $item['icon']['value'] ) ) : ?>
                                <div class="skt-icon icon">
									<?php Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </div>

                            <?php elseif( $item['number'] ) : ?>
                                <div class="skt-icon number">
                                    <span><?php echo esc_html( $item['number'] ); ?></span>
                                </div>

                            <?php elseif( $item['image'] ) :

                                $images = wp_get_attachment_image_src( $item['image']['id'], 'thumbnail', false );
                                if($images){
                                    $image = $images[0];
                                }else{
                                    $image = $item['image']['url'];
                                }
                                ?>
                                <div class="skt-icon image">
                                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" />
                                </div>

                            <?php
                            endif;
                            ?>

                            <div class="skt-text">
									<!-- title tag start -->
                                    <<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'], 'h4' )) .' '. wp_kses_post($this->get_render_attribute_string( $title_key )); ?>>
                                        <?php echo wp_kses_post(skt_addons_elementor_kses_basic($item['title'])); ?>
                                        <?php if ( $item['badge_text'] ) : ?>
                                            <span <?php echo wp_kses_post($this->get_render_attribute_string( $badge_key )); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $item['badge_text'] )); ?></span>
                                        <?php endif; ?>
                                    </<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ));?>>
									<!-- title tag end -->

                                <?php if ( $item['description'] ) : ?>
                                    <p <?php $this->print_render_attribute_string( $description_key ); ?>>
                                        <?php echo wp_kses_post(skt_addons_elementor_kses_basic( $item['description'] )); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php if ( $item['direction']['value'] ) : ?>
                                <div class="skt-direction">
									<?php Icons_Manager::render_icon( $item['direction'], [ 'aria-hidden' => 'true' ] ); ?>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php if ( !empty( $item['link']['url'] ) ) : ?>
                    </a>
                    <?php endif; ?>

                </li>

            <?php endforeach; ?>
        </ul>

        <?php
    }
}