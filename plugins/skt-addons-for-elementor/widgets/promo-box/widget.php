<?php
/**
 * Promo Box widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Promo_Box extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Promo Box', 'skt-addons-elementor' );
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
		return 'skti skti-promo';
	}

	public function get_keywords() {
		return [ 'promo', 'box', 'promo box', 'advertise', 'adds' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__img_title_desc_content_controls();
		$this->__btn_badge_content_controls();
		$this->__settings_content_controls();
	}

	protected function __img_title_desc_content_controls() {

		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Image, Title & Description', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
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

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'medium',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'before_title',
			[
				'label' => __( 'Before Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Summer Deal', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'promo_link',
			[
				'label' => __( 'Promo Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'after_title',
			[
				'label' => __( 'After Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => __( 'Limited Stock. Grab your copy now', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __btn_badge_content_controls() {

		$this->start_controls_section(
			'_section_button',
			[
				'label' => __( 'Button & Badge', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_heading_content',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Buy Now', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'badge_heading_content',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'badge_text_offer',
			[
				'label' => __( 'Offer', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '50%', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$this->add_control(
			'badge_text_detail',
			[
				'label' => __( 'Detail', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'OFF', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
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
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
						'title' => __( 'H6', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
			]
		);

		$this->add_control(
			'image_position',
			[
				'label' => __( 'Image Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-arrow-up',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					]
				],
				'selectors_dictionary' => [
					'top'		=> 'flex-direction: column-reverse',
					'center'	=> 'flex-direction: column',
				],
				'default' => 'center',
				'toggle' => false,
				'prefix_class' => 'skt-promo-image-',
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-header' => '{{VALUE}};'
				]
			]
		);

		$this->add_control(
			'text_align',
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
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-inner-wrap' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'badge_position',
			[
				'label' => __( 'Badge Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'toggle' => false,
				'options' => [
					'top-left' => [
						'title' => __( 'Top Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-arrow-left',
					],
					'top-right' => [
						'title' => __( 'top Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-arrow-right',
					],
					'bottom-left' => [
						'title' => __( 'Bottom Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-arrow-left',
					],
					'bottom-right' => [
						'title' => __( 'Bottom Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-arrow-right',
					],
				],
				'prefix_class' => 'skt-promo-box-',
				'default' => 'bottom-right'
			]
		);

		$this->add_control(
			'badge_animation',
			[
				'label' => __( 'Badge Animation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'skt-badge-animation-',
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__title_style_controls();
		$this->__img_desc_style_controls();
		$this->__btn_badge_style_controls();
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
			'outer_spacing',
			[
				'label' => __( 'Outer Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'common_background_image',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-promo-container',
			]
		);

		$this->add_control(
			'common_background_overlay',
			[
				'label' => __( 'Background Overlay', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'common_background_image_background' => 'classic'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-container:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'glassy_background',
			[
				'label' => __( 'Glassy Background', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'skt-glassy-',
				'condition' => [
					'common_background_image_background' => 'classic'
				],
			]
		);

		$this->add_responsive_control(
			'inner_spacing',
			[
				'label' => __( 'Inner Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-inner-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'inner_border_radius',
			[
				'label' => __( 'Inner Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'inner_border',
				'selector' => '{{WRAPPER}} .skt-promo-box-inner-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'inner_box_shadow',
				'selector' => '{{WRAPPER}} .skt-promo-box-inner-wrap',
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_style_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'before_title_heading',
			[
				'label' => __( 'Before Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'before_title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-before-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'before_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-promo-box-before-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'before_title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-before-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-promo-box-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Hover Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'promo_link[url]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'after_title_heading',
			[
				'label' => __( 'After Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'after_title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-after-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'after_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-promo-box-after-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'after_title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-after-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __img_desc_style_controls() {

		$this->start_controls_section(
			'_section_style_image_description',
			[
				'label' => __( 'Image & Description', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_heading',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'image_resize',
			[
				'label' => __( 'Resize', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-thumb img' => 'width: {{SIZE}}%;',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-promo-box-description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __btn_badge_style_controls() {

		$this->start_controls_section(
			'_section_style_button_badge',
			[
				'label' => __( 'Button & Badge', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .skt-promo-box-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .skt-promo-box-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-promo-box-btn',
			]
		);

		$this->start_controls_tabs( '_tabs_button' );

		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-promo-box-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .skt-promo-box-btn:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-btn:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .skt-promo-box-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'badge_heading',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'badge_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-badge' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'badge_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'selector' => '{{WRAPPER}} .skt-promo-badge',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .skt-promo-box-badge',
			]
		);

		$this->add_control(
			'badge_position_toggle',
			[
				'label' => __( 'offset', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_position_y',
			[
				'label' => __( 'Vertical', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'badge_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-badge' => 'top: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'badge_position_x',
			[
				'label' => __( 'Horizontal', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'badge_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-badge' => 'left: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'badge_text_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-promo-badge span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-promo-box-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'description', 'intermediate' );
		$this->add_render_attribute( 'description', 'class', 'skt-promo-box-description' );

		// promo link
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'promo_link', $settings['promo_link'] );
		}

		// button link
		$this->add_render_attribute( 'button_link', 'class', 'skt-promo-box-btn' );
		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button_link', $settings['button_link'] );
		}
		?>

		<div class="skt-promo-container">
			<div class="skt-promo-box-inner-wrap">

				<div class="skt-promo-box-header">
					<div class="skt-promo-box-title-wrap">
						<?php if ( !empty( $settings['before_title'] ) ) : ?>
							<div class="skt-promo-box-before-title"><?php echo esc_html( $settings['before_title'] ); ?></div>
						<?php endif; ?>

						<?php if ( !empty( $settings['title'] ) ) : ?>
							<<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'] )); ?> class="skt-promo-box-title">

								<?php if ( !empty( $settings['promo_link']['url'] ) ) : ?>
									<a <?php $this->print_render_attribute_string( 'promo_link' ); ?>>
										<?php echo esc_html( $settings['title'] ); ?>
									</a>
								<?php
								else:
									echo esc_html( $settings['title'] );
								 endif;
								 ?>

							</<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'] )); ?>>
						<?php endif; ?>

						<?php if ( !empty( $settings['after_title'] ) ) : ?>
							<div class="skt-promo-box-after-title"><?php echo esc_html( $settings['after_title'] ); ?></div>
						<?php endif; ?>
					</div>

					<?php
					if ( $settings['image']['url'] || $settings['image']['id'] ) :
						$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
						$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
						$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
					?>
						<div class="skt-promo-box-thumb">
							<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="skt-promo-box-footer">
					<?php if ( ! empty( $settings['description'] ) )  : ?>
						<p <?php $this->print_render_attribute_string( 'description' ); ?>>
							<?php echo wp_kses_post($settings['description']); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<a <?php $this->print_render_attribute_string( 'button_link' ); ?>>
							<?php echo esc_html( $settings['button_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

			<?php if ( !empty( $settings['badge_text_offer'] ) || ! empty( $settings['badge_text_detail'] ) ) : ?>
				<div class="skt-promo-box-badge">
					<div class="skt-promo-badge">
						<?php if ( !empty( $settings['badge_text_offer'] ) ) : ?>
							<span><?php echo esc_html($settings['badge_text_offer']); ?></span>
						<?php endif; ?>

						<?php if ( !empty( $settings['badge_text_detail'] ) ) : ?>
							<span><?php echo esc_html($settings['badge_text_detail']); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<?php
	}
}