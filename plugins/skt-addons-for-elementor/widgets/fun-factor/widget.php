<?php
/**
 * Fun Factor widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || die();

class Fun_Factor extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Fun Factor', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-cross-game';
	}

	public function get_keywords() {
		return ['fun', 'factor', 'animation', 'info', 'box', 'number', 'animated'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__content_controls();
		$this->__option_content_controls();
	}

	protected function __content_controls() {

		$this->start_controls_section(
			'_section_contents',
			[
				'label' => __('Contents', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'media_type',
			[
				'label'          => __('Media Type', 'skt-addons-elementor'),
				'type'           => Controls_Manager::CHOOSE,
				'label_block'    => false,
				'options'        => [
					'icon'  => [
						'title' => __('Icon', 'skt-addons-elementor'),
						'icon'  => 'eicon-star',
					],
					'image' => [
						'title' => __('Image', 'skt-addons-elementor'),
						'icon'  => 'eicon-image',
					],
				],
				'default'        => 'icon',
				'toggle'         => false,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => __('Image', 'skt-addons-elementor'),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'media_type' => 'image'
				],
				'dynamic'   => [
					'active' => true,
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'medium_large',
				'separator' => 'none',
				'exclude'   => [
					'full',
					'custom',
					'large',
					'shop_catalog',
					'shop_single',
					'shop_thumbnail'
				],
				'condition' => [
					'media_type' => 'image'
				]
			]
		);

		$this->add_control(
			'icons',
			[
				'label'      => __('Icons', 'skt-addons-elementor'),
				'type'       => Controls_Manager::ICONS,
				'show_label' => true,
				'default'    => [
					'value'   => 'far fa-user',
					'library' => 'solid',
				],
				'condition'  => [
					'media_type' => 'icon',
				],

			]
		);

		$this->add_control(
			'image_icon_position',
			[
				'label'          => __('Position', 'skt-addons-elementor'),
				'type'           => Controls_Manager::CHOOSE,
				'label_block'    => false,
				'options'        => [
					'left'  => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon'  => 'eicon-h-align-left',
					],
					'top'   => [
						'title' => __('Top', 'skt-addons-elementor'),
						'icon'  => 'eicon-v-align-top',
					],
					'right' => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'toggle'         => false,
				'default'        => 'top',
				'prefix_class'   => 'skt-ff-icon--',
				'style_transfer' => true,
			]
		);

		/*
		 * number section
		 */

		$this->add_control(
			'fun_factor_number',
			[
				'label'     => __('Number', 'skt-addons-elementor'),
				'type'      => Controls_Manager::TEXT,
				'default'   => '507',
				'dynamic'   => [
					'active' => true,
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'fun_factor_number_prefix',
			[
				'label'     => __('Number Prefix', 'skt-addons-elementor'),
				'type'      => Controls_Manager::TEXT,
				'placeholder'   => '1',
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'fun_factor_number_suffix',
			[
				'label'     => __('Number Suffix', 'skt-addons-elementor'),
				'type'      => Controls_Manager::TEXT,
				'placeholder'   => '+',
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'fun_factor_title',
			[
				'label'   => __('Title', 'skt-addons-elementor'),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __('SKT Clients', 'skt-addons-elementor'),
			]
		);

		$this->add_control(
			'animate_number',
			[
				'label'        => __('Animate', 'skt-addons-elementor'),
				'description'  => __('Only number is animatable'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('Yes', 'skt-addons-elementor'),
				'label_off'    => __('No', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes'
			]
		);

		$this->add_control(
			'animate_duration',
			[
				'label'     => __('Duration', 'skt-addons-elementor'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 100,
				'max'       => 10000,
				'step'      => 10,
				'default'   => 500,
				'condition' => [
					'animate_number!' => ''
				],
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __option_content_controls() {

		// options section in contents tab

		$this->start_controls_section(
			'_section_options',
			[
				'label' => __('Options', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'divider_show_hide',
			[
				'label'        => __('Show Divider', 'skt-addons-elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'skt-addons-elementor'),
				'label_off'    => __('Hide', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'       => __('Text Alignment', 'skt-addons-elementor'),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'left'   => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon'  => 'eicon-text-align-right',
					],
					// 'justify'  => [
					// 	'title' => __('Justify', 'skt-addons-elementor'),
					// 	'icon'  => 'eicon-text-align-justify',
					// ],
				],
				'toggle'      => false,
				// 'selectors_dictionary' => [
                //     'left' => 'text-align: left; justify-content: flex-start;',
                //     'center' => 'text-align: center; justify-content: center;',
                //     'right' => 'text-align: right; justify-content: flex-end;',
                // ],
				'selectors_dictionary' => [
                    'left' => '--skt-ff-align: left; --skt-ff-number-align: flex-start;',
                    'center' => '--skt-ff-align: center; --skt-ff-number-align: center;',
                    'right' => '--skt-ff-align: right; --skt-ff-number-align: flex-end;',
                ],
				'selectors'   => [
					'{{WRAPPER}}.skt-fun-factor ' => '{{VALUE}};',
					'{{WRAPPER}} .skt-fun-factor__wrap, {{WRAPPER}} .skt-fun-factor__media--image, {{WRAPPER}} .skt-fun-factor__content, {{WRAPPER}} .skt-fun-factor__content' => 'text-align:var(--skt-ff-align);',
					'{{WRAPPER}} .skt-fun-factor__content-number-wrap' => 'justify-content:var(--skt-ff-number-align);',
				],
				// 'selectors'   => [
				// 	'{{WRAPPER}} .skt-fun-factor__wrap, {{WRAPPER}} .skt-fun-factor__media--image, {{WRAPPER}} .skt-fun-factor__content, {{WRAPPER}} .skt-fun-factor__content-number-wrap' => '{{VALUE}};',
				// ],
				'default'     => 'center',
				// 'render_type' => 'template',
				// 'prefix_class' => 'skt-fun-factor-align-',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => __('Title HTML Tag', 'skt-addons-elementor'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'h1' => [
						'title' => __('H1', 'skt-addons-elementor'),
						'icon'  => 'eicon-editor-h1'
					],
					'h2' => [
						'title' => __('H2', 'skt-addons-elementor'),
						'icon'  => 'eicon-editor-h2'
					],
					'h3' => [
						'title' => __('H3', 'skt-addons-elementor'),
						'icon'  => 'eicon-editor-h3'
					],
					'h4' => [
						'title' => __('H4', 'skt-addons-elementor'),
						'icon'  => 'eicon-editor-h4'
					],
					'h5' => [
						'title' => __('H5', 'skt-addons-elementor'),
						'icon'  => 'eicon-editor-h5'
					],
					'h6' => [
						'title' => __('H6', 'skt-addons-elementor'),
						'icon'  => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle'  => false,
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__icon_image_style_controls();
		$this->__number_title_style_controls();
		$this->__divider_style_controls();
	}

	protected function __icon_image_style_controls() {

		$this->start_controls_section(
			'_section_style_icon_image',
			[
				'label' => __('Icon / Image', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => __('Width', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min' => 150,
						'max' => 500,
					],
					'%'  => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}}.skt-ff-icon--top .skt-fun-factor__media--image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}:not(.skt-ff-icon--top) .skt-fun-factor__media--image' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'media_type' => 'image',
				]
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => __('Height', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min' => 150,
						'max' => 1024,
					],
					'%'  => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__media--image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'media_type' => 'image',
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'           => __('Size', 'skt-addons-elementor'),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => ['px'],
				'range'           => [
					'px' => [
						'min'  => 6,
						'max'  => 300,
					],
				],
				'selectors'       => [
					'{{WRAPPER}} .skt-fun-factor__media--icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'media_type' => 'icon',
				],
				'render_type'     => 'template',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __('Icon Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__media--icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'media_type' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'media_padding',
			[
				'label'      => __('Padding', 'skt-addons-elementor'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__media--image img, {{WRAPPER}} .skt-fun-factor__media--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'media_border',
				'selector'  => '{{WRAPPER}} .skt-fun-factor__media--image img, {{WRAPPER}} .skt-fun-factor__media--icon',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'media_border_radius',
			[
				'label'      => __('Border Radius', 'skt-addons-elementor'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__media--image img, {{WRAPPER}} .skt-fun-factor__media--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'media_box_shadow',
				'selector' => '{{WRAPPER}} .skt-fun-factor__media--image img, {{WRAPPER}} .skt-fun-factor__media--icon',
			]
		);

		$this->add_responsive_control(
			'icon_image_bottom_spacing',
			[
				'label'     => __('Bottom Spacing', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__media--icon, {{WRAPPER}} .skt-fun-factor__media--image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'     => __('Background Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__media--icon, {{WRAPPER}} .skt-fun-factor__media--image' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'media_type' => 'icon'
				]
			]
		);

		$this->add_control(
			'offset_toggle',
			[
				'label'        => __('Offset', 'skt-addons-elementor'),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __('No', 'skt-addons-elementor'),
				'label_on'     => __('Yes', 'skt-addons-elementor'),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'media_offset_x',
			[
				'label'      => __('Offset Left', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition'  => [
					'offset_toggle' => 'yes'
				],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
			]
		);

		$this->add_responsive_control(
			'media_offset_y',
			[
				'label'      => __('Offset Top', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition'  => [
					'offset_toggle' => 'yes'
				],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],

				'selectors' => [
					// Left image position styles
					'(desktop){{WRAPPER}}.skt-ff-icon--left .skt-fun-factor__content'                               => 'margin-left: {{media_offset_x.SIZE || 0}}{{UNIT}}; max-width: calc((100% - {{image_width.SIZE || 50}}{{image_width.UNIT}}) + (-1 * {{media_offset_x.SIZE || 0}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.skt-ff-icon--left .skt-fun-factor__content'                                => 'margin-left: {{media_offset_x_tablet.SIZE || 0}}{{UNIT}}; max-width: calc((100% - {{image_width_tablet.SIZE || 50}}{{image_width_tablet.UNIT}}) + (-1 * {{media_offset_x_tablet.SIZE || 0}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.skt-ff-icon--left .skt-fun-factor__content'                                => 'margin-left: {{media_offset_x_mobile.SIZE || 0}}{{UNIT}}; max-width: calc((100% - {{image_width_mobile.SIZE || 50}}{{image_width_mobile.UNIT}}) + (-1 * {{media_offset_x_mobile.SIZE || 0}}{{UNIT}}));',
					// Image right position styles
					'(desktop){{WRAPPER}}.skt-ff-icon--right .skt-fun-factor__content'                              => 'margin-right: calc(-1 * {{media_offset_x.SIZE || 0}}{{UNIT}}); max-width: calc((100% - {{image_width.SIZE || 50}}{{image_width.UNIT}}) + {{media_offset_x.SIZE || 0}}{{UNIT}});',
					'(tablet){{WRAPPER}}.skt-ff-icon--right .skt-fun-factor__content'                               => 'margin-right: calc(-1 * {{media_offset_x_tablet.SIZE || 0}}{{UNIT}}); max-width: calc((100% - {{image_width_tablet.SIZE || 50}}{{image_width_tablet.UNIT}}) + {{media_offset_x_tablet.SIZE || 0}}{{UNIT}});',
					'(mobile){{WRAPPER}}.skt-ff-icon--right .skt-fun-factor__content'                               => 'margin-right: calc(-1 * {{media_offset_x_mobile.SIZE || 0}}{{UNIT}}); max-width: calc((100% - {{image_width_mobile.SIZE || 50}}{{image_width_mobile.UNIT}}) + {{media_offset_x_mobile.SIZE || 0}}{{UNIT}});',
					// Image translate styles
					'(desktop){{WRAPPER}} .skt-fun-factor__media--icon, {{WRAPPER}} .skt-fun-factor__media--image' => '-ms-transform: translate({{media_offset_x.SIZE || 0}}{{UNIT}}, {{media_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{media_offset_x.SIZE || 0}}{{UNIT}}, {{media_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{media_offset_x.SIZE || 0}}{{UNIT}}, {{media_offset_y.SIZE || 0}}{{UNIT}});',
					'(tablet){{WRAPPER}} .skt-fun-factor__media--icon, {WRAPPER}} .skt-fun-factor__media--image'   => '-ms-transform: translate({{media_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{media_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{media_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{media_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{media_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{media_offset_y_tablet.SIZE || 0}}{{UNIT}});',
					'(mobile){{WRAPPER}} .skt-fun-factor__media--icon, {{WRAPPER}} .skt-fun-factor__media--image'  => '-ms-transform: translate({{media_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{media_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{media_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{media_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{media_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{media_offset_y_mobile.SIZE || 0}}{{UNIT}});',
					// Fun Factor body styles
					'{{WRAPPER}}.skt-ff-icon--top .skt-fun-factor__content'                                         => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_popover();

		$this->end_controls_section();
	}

	protected function __number_title_style_controls() {

		$this->start_controls_section(
			'_section_style_number_title',
			[
				'label' => __('Number & Title', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'     => __('Padding', 'skt-addons-elementor'),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'fun_factor_number_heading',
			[
				'label' => __('Number', 'skt-addons-elementor'),
				'type'  => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'fun_factor_number_bottom_spacing',
			[
				'label'      => __('Bottom Spacing', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__content-number-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'fun_factor_number_color',
			[
				'label'     => __('Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__content-number-prefix' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .skt-fun-factor__content-number' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .skt-fun-factor__content-number-suffix' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'number_typography',
				'label'    => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .skt-fun-factor__content-number-prefix, {{WRAPPER}} .skt-fun-factor__content-number, {{WRAPPER}} .skt-fun-factor__content-number-suffix',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'fun_factor_number_shadow',
				'label'    => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-fun-factor__content-number-prefix, {{WRAPPER}} .skt-fun-factor__content-number, {{WRAPPER}} .skt-fun-factor__content-number-suffix',
			]
		);

		/*
		 * Title section
		 */

		$this->add_control(
			'content_title_heading_style',
			[
				'label'     => __('Title', 'skt-addons-elementor'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'fun_factor_content_bottom_spacing',
			[
				'label'      => __('Bottom Spacing', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__content-text' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'fun_factor_content_color',
			[
				'label'     => __('Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__content-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .skt-fun-factor__content-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'fun_factor_content_shadow',
				'label'    => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-fun-factor__content-text',
			]
		);

		$this->end_controls_section();
	}

	protected function __divider_style_controls() {

		$this->start_controls_section(
			'_section_divider',
			[
				'label'     => __('Divider', 'skt-addons-elementor'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'divider_show_hide' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label'      => __('Width', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range'      => [
					'%' => [
						'min' => 10,
						'max' => 100
					],
				],
				'default'    => [
					'unit' => '%'
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__divider' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'divider_height',
			[
				'label'      => __('Height', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'px' => 1
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__divider' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'divider_border_radius',
			[
				'label'     => __('Border Radius', 'skt-addons-elementor'),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => __('Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-fun-factor__divider' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'divider_bottom_spacing',
			[
				'label'      => __('Bottom Spacing', 'skt-addons-elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .skt-fun-factor__divider' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute('fun_factor_number', 'class', 'skt-fun-factor__content-number');
		$number           = $settings['fun_factor_number'];
		$fun_factor_title = $settings['fun_factor_title'];

		if ($settings['animate_number']) {
			$data = [
				'toValue'  => intval($settings['fun_factor_number']),
				'duration' => intval($settings['animate_duration']),
			];
			$this->add_render_attribute('fun_factor_number', 'data-animation', wp_json_encode($data));
			$number = 0;
		}
		?>

		<div class="skt-fun-factor__wrap">
            <?php if (!empty($settings['icons']['value'])) : ?>
                <div class="skt-fun-factor__media skt-fun-factor__media--icon">
                    <?php Icons_Manager::render_icon( $settings['icons'], ['aria-hidden' => 'true'] ); ?>
                </div>
            <?php elseif ( $settings['image']['url'] || $settings['image']['id'] ) : ?>
                <div class="skt-fun-factor__media skt-fun-factor__media--image">
                    <?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
                </div>
            <?php endif; ?>

            <div class="skt-fun-factor__content">
				<div class="skt-fun-factor__content-number-wrap">
					<?php if ( $settings['fun_factor_number_prefix'] ) : ?>
						<span class="skt-fun-factor__content-number-prefix"><?php esc_html_e( $settings['fun_factor_number_prefix'] ); ?></span>
					<?php endif; ?>
	                <span <?php $this->print_render_attribute_string( 'fun_factor_number' ); ?> ><?php echo esc_html( $number ); ?></span>
					<?php if ( $settings['fun_factor_number_suffix'] ) : ?>
						<span class="skt-fun-factor__content-number-suffix"><?php esc_html_e( $settings['fun_factor_number_suffix'] ); ?></span>
					<?php endif; ?>
				</div>
                <?php if ( 'yes' === $settings['divider_show_hide'] ) : ?>
                    <span class="skt-fun-factor__divider skt-fun-factor__divider-align-<?php echo esc_attr( $settings['text_align'] ); ?>"></span>
                <?php endif; ?>
                <?php printf( '<%1$s class="skt-fun-factor__content-text">%2$s</%1$s>',
                    skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
                    skt_addons_elementor_kses_basic( $fun_factor_title )
                ); ?>
            </div>
        </div>
		<?php
	}
}