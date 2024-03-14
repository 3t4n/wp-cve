<?php
/**
 * Advanced Tabs
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Advanced_Tabs extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Tabs', 'skt-addons-elementor' );
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
		return 'skti skti-tab';
	}

	public function get_keywords() {
		return [ 'tabs', 'section', 'advanced', 'toggle' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__tabs_content_controls();
		$this->__options_content_controls();
	}

	protected function __tabs_content_controls() {

		$this->start_controls_section(
			'_section_tabs',
			[
				'label' => __( 'Tabs', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'default' => __( 'Tab Title', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Tab Title', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'icon',
			[
				'type' => Controls_Manager::ICONS,
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'source',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Content Source', 'skt-addons-elementor' ),
				'default' => 'editor',
				'separator' => 'before',
				'options' => [
					'editor' => __( 'Editor', 'skt-addons-elementor' ),
					'template' => __( 'Template', 'skt-addons-elementor' ),
				]
			]
		);

		$repeater->add_control(
			'editor',
			[
				'label' => __( 'Content Editor', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::WYSIWYG,
				'condition' => [
					'source' => 'editor',
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'template',
			[
				'label' => __( 'Section Template', 'skt-addons-elementor' ),
				'placeholder' => __( 'Select a section template for as tab content', 'skt-addons-elementor' ),
				'description' => sprintf( __( 'Wondering what is section template or need to create one? Please click %1$shere%2$s ', 'skt-addons-elementor' ),
					'<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
					'</a>'
				),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => sktaddonselementorextra_get_section_templates(),
				'condition' => [
					'source' => 'template',
				]
			]
		);

		$this->add_control(
			'tabs',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{title}}',
				'default' => [
					[
						'title' => 'Tab 1',
						'source' => 'editor',
						'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore <br><br>et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
					],
					[
						'title' => 'Tab 2',
						'source' => 'editor',
						'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore <br><br>et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
					]
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __options_content_controls() {

		$this->start_controls_section(
			'_section_options',
			[
				'label' => __( 'Options', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'_heading_tab_title',
			[
				'label' => __( 'Tab Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'nav_position',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'description' => __( 'Only applicable for desktop', 'skt-addons-elementor' ),
				'default' => 'top',
				'toggle' => false,
				'options' => [
					'left' => [
						'title' =>  __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' =>  __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'nav_align_x',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'default' => 'x-left',
				'toggle' => false,
				'options' => [
					'x-left' => [
						'title' =>  __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'x-center' => [
						'title' =>  __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'x-justify' => [
						'title' =>  __( 'Stretch', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					],
					'x-right' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-top > .skt-tabs__nav' => 'justify-content: {{VALUE}};'
				],
				'selectors_dictionary' => [
					'x-left' => 'flex-start',
					'x-right' => 'flex-end',
					'x-center' => 'center',
					'x-justify' => 'space-evenly'
				],
				'condition' => [
					'nav_position' => ['top', 'bottom'],
				],
				'style_transfer' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'nav_align_y',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'default' => 'y-top',
				'toggle' => false,
				'options' => [
					'y-top' => [
						'title' =>  __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'y-center' => [
						'title' =>  __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'y-bottom' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-left > .skt-tabs__nav' => 'justify-content: {{VALUE}};',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-right > .skt-tabs__nav' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'y-top' => 'flex-start',
					'y-center' => 'center',
					'y-bottom' => 'flex-end',
				],
				'condition' => [
					'nav_position' => ['left', 'right'],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'nav_text_align',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Text Alignment', 'skt-addons-elementor' ),
				'default' => 'center',
				'toggle' => false,
				'options' => [
					'left' => [
						'title' =>  __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' =>  __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title--desktop' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'_heading_tab_icon',
			[
				'label' => __( 'Tab Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_icon_position',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'default' => 'left',
				'toggle' => false,
				'options' => [
					'left' => [
						'title' =>  __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' =>  __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' =>  __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'right' => [
						'title' =>  __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__tab_title_style_controls();
		$this->__tab_icon_style_controls();
		$this->__tab_content_style_controls();
	}

	protected function __tab_title_style_controls() {

		$this->start_controls_section(
			'_section_tab_nav',
			[
				'label' => __( 'Tab Title', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_margin_x',
			[
				'label' => __( 'Horizontal Margin (px)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'step' => 'any',
				'selectors' => [
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-top > .skt-tabs__nav > .skt-tab__title:not(:last-child)' => 'margin-right: {{VALUE}}px;',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-bottom > .skt-tabs__nav > .skt-tab__title:not(:last-child)' => 'margin-right: {{VALUE}}px;',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-left > .skt-tabs__nav > .skt-tab__title' => 'margin-right: {{VALUE}}px;',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-right > .skt-tabs__nav > .skt-tab__title' => 'margin-left: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'nav_margin_y',
			[
				'label' => __( 'Vertical Margin (px)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'step' => 'any',
				'selectors' => [
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-top > .skt-tabs__nav > .skt-tab__title' => 'margin-bottom: {{VALUE}}px;',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-bottom > .skt-tabs__nav > .skt-tab__title' => 'margin-top: {{VALUE}}px;',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-left > .skt-tabs__nav > .skt-tab__title:not(:last-child)' => 'margin-bottom: {{VALUE}}px;',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--nav-right > .skt-tabs__nav > .skt-tab__title:not(:last-child)' => 'margin-bottom: {{VALUE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'nav_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nav_typography',
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'nav_text_shadow',
				'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title',
			]
		);

		$this->start_controls_tabs( '_tab_nav_stats' );
		$this->start_controls_tab(
			'_tab_nav_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'nav_border_style',
			[
				'label' => __( 'Border Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'skt-addons-elementor' ),
					'solid' => __( 'Solid', 'skt-addons-elementor' ),
					'double' => __( 'Double', 'skt-addons-elementor' ),
					'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
					'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
				],
				'default' => 'none',
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'nav_border_width',
			[
				'label' => __( 'Border Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'condition' => [
					'nav_border_style!' => 'none'
				],
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'nav_border_style!' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title' => 'border-style: {{nav_border_style.VALUE}}; border-color: {{VALUE}};',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title' => 'border-width: {{nav_border_width.TOP}}px {{nav_border_width.RIGHT}}px {{nav_border_width.BOTTOM}}px {{nav_border_width.LEFT}}px;',
				],
				'default' => '#e8e8e8',
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'nav_text_gradient',
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav .skt-tab__title-text, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav .skt-tab__title-icon, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content .skt-tab__title-text, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content .skt-tab__title-icon',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'nav_bg',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav .skt-tab__title, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content .skt-tab__title',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_nav_active',
			[
				'label' => __( 'Active', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'nav_active_border_style',
			[
				'label' => __( 'Border Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'skt-addons-elementor' ),
					'solid' => __( 'Solid', 'skt-addons-elementor' ),
					'double' => __( 'Double', 'skt-addons-elementor' ),
					'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
					'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
				],
				'default' => 'none',
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'nav_active_border_width',
			[
				'label' => __( 'Border Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'condition' => [
					'nav_active_border_style!' => 'none'
				],
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'nav_active_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'nav_active_border_style!' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title.skt-tab--active, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title.skt-tab--active' => 'border-style: {{nav_active_border_style.VALUE}}; border-color: {{VALUE}};',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title.skt-tab--active' => 'border-width: {{nav_active_border_width.TOP}}px {{nav_active_border_width.RIGHT}}px {{nav_active_border_width.BOTTOM}}px {{nav_active_border_width.LEFT}}px;',
				],
				'default' => '#e8e8e8',
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'nav_active_text_gradient',
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab--active .skt-tab__title-text, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab--active .skt-tab__title-icon, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab--active .skt-tab__title-text',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'nav_active_bg',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav > .skt-tab__title.skt-tab--active, {{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__title.skt-tab--active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __tab_icon_style_controls() {

		$this->start_controls_section(
			'_section_nav_icon',
			[
				'label' => __( 'Tab Icon', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_icon_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--icon-left > .skt-tabs__nav .skt-tab__title-icon' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--icon-right > .skt-tabs__nav .skt-tab__title-icon' => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--icon-top > .skt-tabs__nav .skt-tab__title-icon' => 'margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .skt-tabs-{{ID}}.skt-tabs--icon-bottom > .skt-tabs__nav .skt-tab__title-icon' => 'margin-top: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'nav_icon_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__nav .skt-tab__title-icon' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __tab_content_style_controls() {

		$this->start_controls_section(
			'_section_tab_content',
			[
				'label' => __( 'Tab Content', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_border_style',
			[
				'label' => __( 'Border Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'skt-addons-elementor' ),
					'solid' => __( 'Solid', 'skt-addons-elementor' ),
					'double' => __( 'Double', 'skt-addons-elementor' ),
					'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
					'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
				],
				'default' => 'none',
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'content_border_width',
			[
				'label' => __( 'Border Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'condition' => [
					'content_border_style!' => 'none'
				],
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'content_border_style!' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content' => 'border-style: {{content_border_style.VALUE}}; border-color: {{VALUE}};',
					'(tablet+){{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content' => 'border-width: {{content_border_width.TOP}}px {{content_border_width.RIGHT}}px {{content_border_width.BOTTOM}}px {{content_border_width.LEFT}}px;',
				],
				'default' => '#e8e8e8',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-tabs-{{ID}} > .skt-tabs__content > .skt-tab__content',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs = (array) $settings['tabs'];
		$id_int = substr( $this->get_id_int(), 0, 3 );

		$this->add_render_attribute( 'tabs_wrapper', 'class', [
			'skt-tabs-' . $this->get_id(),
			'skt-tabs',
			'skt-tabs--nav-' . $settings['nav_position'],
			in_array( $settings['nav_position'], ['top', 'bottom'] ) ? 'skt-tabs--nav-' . $settings['nav_align_x'] : '',
			in_array( $settings['nav_position'], ['left', 'right'] ) ? 'skt-tabs--nav-' . $settings['nav_align_y'] : '',
			'skt-tabs--icon-' . $settings['nav_icon_position'],
		] );

		$this->add_render_attribute( 'tabs_wrapper', 'role', 'tablist' );
		?>
		<div <?php $this->print_render_attribute_string( 'tabs_wrapper' ); ?>>
			<div class="skt-tabs__nav">
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;

					$tab_title_setting_key = $this->get_repeater_setting_key( 'title', 'tabs', $index );
					$tab_content_id = 'skt-tab__content-' . $id_int . $tab_count;

					$this->add_render_attribute( $tab_title_setting_key, [
						'id' => 'skt-tab-title-' . $id_int . $tab_count,
						'class' => [ 'skt-tab__title', 'skt-tab__title--desktop', 'elementor-repeater-item-' . $item['_id'] ],
						'data-tab' => $tab_count,
						'role' => 'tab',
						'aria-controls' => $tab_content_id,
					] );
					?>
					<div <?php echo wp_kses_post($this->get_render_attribute_string( $tab_title_setting_key )); ?>>
						<?php if ( ! empty( $item['icon'] ) && ! empty( $item['icon']['value'] ) ) : ?>
						<span class="skt-tab__title-icon"><?php skt_addons_elementor_render_icon( $item, false, 'icon' ); ?></span>
						<?php endif; ?>
						<span class="skt-tab__title-text"><?php echo esc_html(skt_addons_elementor_kses_basic( $item['title'] )); ?></span></div>
				<?php endforeach; ?>
			</div>
			<div class="skt-tabs__content">
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;

					if ( $item['source'] === 'editor' ) {
						$tab_content_setting_key = $this->get_repeater_setting_key( 'editor', 'tabs', $index );
						$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
					} else {
						$tab_content_setting_key = $this->get_repeater_setting_key( 'section', 'tabs', $index );
					}

					$tab_title_mobile_setting_key = $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $tab_count );

					$this->add_render_attribute( $tab_content_setting_key, [
						'id' => 'skt-tab-content-' . $id_int . $tab_count,
						'class' => [ 'skt-tab__content', 'skt-clearfix', 'elementor-repeater-item-' . $item['_id'] ],
						'data-tab' => $tab_count,
						'role' => 'tabpanel',
						'aria-labelledby' => 'skt-tab-title-' . $id_int . $tab_count,
					] );

					$this->add_render_attribute( $tab_title_mobile_setting_key, [
						'class' => [ 'skt-tab__title', 'skt-tab__title--mobile', 'elementor-repeater-item-' . $item['_id'] ],
						'data-tab' => $tab_count,
						'role' => 'tab',
					] );
					?>
					<div <?php echo wp_kses_post($this->get_render_attribute_string( $tab_title_mobile_setting_key )); ?>>
						<?php if ( ! empty( $item['icon'] ) && ! empty( $item['icon']['value'] ) ) : ?>
						<span class="skt-tab__title-icon"><?php skt_addons_elementor_render_icon( $item, false, 'icon' ); ?></span>
						<?php endif; ?>
						<span class="skt-tab__title-text"><?php echo esc_html(skt_addons_elementor_kses_basic( $item['title'] )); ?></span>
					</div>
					<div <?php echo wp_kses_post($this->get_render_attribute_string( $tab_content_setting_key )); ?>>
						<?php
						if ( $item['source'] === 'editor' ) :
							echo wp_kses_post($this->parse_text_editor( $item['editor'] ));
						elseif ( $item['source'] === 'template' && $item['template'] ) :
							echo skt_addons_elementor()->frontend->get_builder_content_for_display( $item['template'] );
						endif;
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}