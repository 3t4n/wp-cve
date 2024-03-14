<?php
/**
 * Info box widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Skt_Addons_Elementor\Elementor\Traits\Button_Renderer;

defined( 'ABSPATH' ) || die();

class InfoBox extends Base {

	use Button_Renderer;

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Info Box', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
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
		return 'skti skti-info';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__media_content_controls();
		$this->__title_desc_content_controls();
		$this->__button_content_controls();
	}

	protected function __media_content_controls() {

		$this->start_controls_section(
			'_section_media',
			[
				'label' => __( 'Icon / Image', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Media Type', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'image' => [
						'title' => __( 'Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-image',
					],
				],
				'default' => 'icon',
				'toggle' => false,
				'style_transfer' => true,
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
				'condition' => [
					'type' => 'image'
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
					'type' => 'image'
				]
			]
		);

		if ( skt_addons_elementor_is_elementor_version( '<', '2.6.0' ) ) {
			$this->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'label_block' => true,
					'type' => Controls_Manager::ICON,
					'options' => skt_addons_elementor_get_skt_addons_elementor_icons(),
					'default' => 'fa fa-rocket',
					'condition' => [
						'type' => 'icon'
					]
				]
			);
		} else {
			$this->add_control(
				'selected_icon',
				[
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'label_block' => true,
					'default' => [
						'value' => 'fa fa-rocket',
						'library' => 'fa-solid',
					],
					'condition' => [
						'type' => 'icon'
					]
				]
			);
		}

		$this->add_control(
			'media_direction',
			[
				'label' => __('Media direction', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => __('Top', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-top',
					],
				],
				'default' => 'top',
				'toggle' => false,
                'style_transfer' => true,
				'prefix_class' => 'skt-infobox-media-dir-',
			]
		);

		$this->add_control(
			'media_v_align',
			[
				'label' => __('Vertical Alignment', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __('Top', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false,
				'condition' => [
					'media_direction' => 'left',
				],
                'style_transfer' => true,
				'selectors_dictionary' => [
                    'center' => '    -webkit-align-self: center; -ms-flex-item-align: center; align-self: center;',
                    'bottom' => '    -webkit-align-self: flex-end; -ms-flex-item-align: end; align-self: flex-end;',
                ],
				'selectors' => [
					'{{WRAPPER}}.skt-infobox-media-dir-left .skt-infobox-figure' => '{{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __title_desc_content_controls() {

		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title & Description', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'SKT Info Box Title', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Info Box Title', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'description' => skt_addons_elementor_get_allowed_html_desc( 'intermediate' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'SKT info box description goes here', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type info box description', 'skt-addons-elementor' ),
				'rows' => 5,
				'dynamic' => [
					'active' => true,
				]
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
				'condition' => [
					'media_direction' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __button_content_controls() {

		$this->start_controls_section(
			'_section_button',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Button Text', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type button text here', 'skt-addons-elementor' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				]
			]
		);

		if ( skt_addons_elementor_is_elementor_version( '<', '2.6.0' ) ) {
			$this->add_control(
				'button_icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'label_block' => true,
					'type' => Controls_Manager::ICON,
					'options' => skt_addons_elementor_get_skt_addons_elementor_icons(),
					'default' => 'fa fa-angle-right',
				]
			);

			$condition = ['button_icon!' => ''];
		} else {
			$this->add_control(
				'button_selected_icon',
				[
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'button_icon',
					'label_block' => true,
				]
			);
			$condition = ['button_selected_icon[value]!' => ''];
		}

		$this->add_control(
			'button_icon_position',
			[
				'label' => __( 'Icon Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'before' => [
						'title' => __( 'Before', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => __( 'After', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'after',
				'toggle' => false,
				'condition' => $condition,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'button_icon_spacing',
			[
				'label' => __( 'Icon Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'condition' => $condition,
				'selectors' => [
					'{{WRAPPER}} .skt-btn--icon-before .skt-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-btn--icon-after .skt-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__media_style_controls();
		$this->__title_style_controls();
		$this->__button_style_controls();
	}

	protected function __media_style_controls() {

		$this->start_controls_section(
			'_section_media_style',
			[
				'label' => __( 'Icon / Image', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					 'type' => 'icon'
				]
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'image'
				]
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
						'min' => 1,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'image'
				]
			]
		);

		$this->add_control(
			'offset_toggle',
			[
				'label' => __( 'Offset', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'media_offset_x',
			[
				'label' => __( 'Offset Left', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'offset_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--skt-infobox-media-offset-x: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'media_offset_y',
			[
				'label' => __( 'Offset Top', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'offset_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--skt-infobox-media-offset-y: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_popover();

		$this->add_responsive_control(
			'media_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}}.skt-infobox-media-dir-top .skt-infobox-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.skt-infobox-media-dir-left .skt-infobox-figure' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'media_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--image img, {{WRAPPER}} .skt-infobox-figure--icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'media_border',
				'selector' => '{{WRAPPER}} .skt-infobox-figure--image img, {{WRAPPER}} .skt-infobox-figure--icon',
			]
		);

		$this->add_responsive_control(
			'media_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-infobox-figure--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'media_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .skt-infobox-figure--image img, {{WRAPPER}} .skt-infobox-figure--icon'
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-figure--icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_bg_rotate',
			[
				'label' => __( 'Background Rotate', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--skt-infobox-media-rotate: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_title_style',
			[
				'label' => __( 'Title & Description', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Content Box Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-infobox-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_control(
			'description_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-infobox-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-infobox-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function __button_style_controls() {

		$this->start_controls_section(
			'_section_style_button',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .skt-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .skt-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-btn',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
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
			'link_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_icon_translate',
			[
				'label' => __( 'Icon Translate X', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-btn .skt-btn-icon' => '--infobox-btn-icon-translate-x: {{SIZE}}{{UNIT}};',
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
			'link_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-btn:hover, {{WRAPPER}} .skt-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-btn:hover, {{WRAPPER}} .skt-btn:focus' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .skt-btn:hover, {{WRAPPER}} .skt-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_icon_translate',
			[
				'label' => __( 'Icon Translate X', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-btn .skt-btn-icon' => '--infobox-btn-icon-translate-x-hover: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'skt-infobox-title' );

		$this->add_inline_editing_attributes( 'description', 'intermediate' );
		$this->add_render_attribute( 'description', 'class', 'skt-infobox-text' );
		?>

		<?php if ( $settings['type'] === 'image' && ( $settings['image']['url'] || $settings['image']['id'] ) ) :
			$settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
			?>
			<figure class="skt-infobox-figure skt-infobox-figure--image">
				<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
			</figure>
		<?php elseif ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
			<figure class="skt-infobox-figure skt-infobox-figure--icon">
				<?php skt_addons_elementor_render_icon( $settings, 'icon', 'selected_icon' ); ?>
			</figure>
		<?php endif; ?>

		<div class="skt-infobox-body">
			<?php
			if ( $settings['title' ] ) :
				printf( '<%1$s %2$s>%3$s</%1$s>',
					skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
					$this->get_render_attribute_string( 'title' ),
					skt_addons_elementor_kses_basic( $settings['title' ] )
				);
			endif;
			?>

			<?php if ( $settings['description'] ) : ?>
				<div <?php $this->print_render_attribute_string( 'description' ); ?>>
					<p><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['description'] )); ?></p>
				</div>
			<?php endif; ?>

			<?php $this->render_icon_button(); ?>
		</div>
		<?php
	}

	public function content_template() {
		?>
		<#
		var iconHTML, migrated;

		if ( haHasIconLibrary() ) {
			iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
		}

		view.addInlineEditingAttributes( 'title', 'basic' );
		view.addRenderAttribute( 'title', 'class', 'skt-infobox-title' );

		view.addInlineEditingAttributes( 'description', 'intermediate' );
		view.addRenderAttribute( 'description', 'class', 'skt-infobox-text' );

		if ( settings.type === 'image' ) {
			if ( settings.image.url ) {
				var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.thumbnail_size,
					model: view.getEditModel()
				};

				var image_url = elementor.imagesManager.getImageUrl( image );
				#>
				<figure class="skt-infobox-figure skt-infobox-figure--image">
					<img src="{{ image_url }}">
				</figure>
			<# }
		} else if ( settings.icon || settings.selected_icon.value ) { #>
			<figure class="skt-infobox-figure skt-infobox-figure--icon">
				<# if ( haHasIconLibrary() && iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
					{{{ iconHTML.value }}}
				<# } else { #>
					<i class="{{ settings.icon }}" aria-hidden="true"></i>
				<# } #>
			</figure>
		<# } #>

		<div class="skt-infobox-body">
			<# if (settings.title) { #>
				<{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>
			<# } #>

			<# if (settings.description) { #>
				<div {{{ view.getRenderAttributeString( 'description' ) }}}>
					<p>{{{ settings.description }}}</p>
				</div>
			<# } #>

			<# print( haGetButtonWithIcon(view) ); #>
		</div>
		<?php
	}
}