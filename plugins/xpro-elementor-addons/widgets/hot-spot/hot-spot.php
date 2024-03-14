<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Hot_Spot extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-hot-spot';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Hotspot', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-hot-spot xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'xpro', 'hot', 'spot', 'spots' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_hotspot',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => __( 'Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => array(
					'hover' => __( 'On Hover', 'xpro-elementor-addons' ),
					'click' => __( 'On Click', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'media_thumbnail',
				'default'   => 'full',
				'separator' => 'none',
				'exclude'   => array(
					'custom',
				),
			)
		);

		// repeater
		$repeater = new Repeater();

		$repeater->add_control(
			'hot_media_type',
			array(
				'label'       => __( 'Media Type', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-star-o',
					),
					'image' => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
				),
				'default'     => 'icon',
				'toggle'      => false,
			)
		);

		$repeater->add_control(
			'hot_icon',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'hot_media_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'spots_image',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'hot_media_type' => 'image',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'spots_thumbnail',
				'default'   => 'full',
				'separator' => 'none',
				'exclude'   => array(
					'custom',
				),
				'condition' => array(
					'hot_media_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'hot_offset_toggle',
			array(
				'label'        => __( 'Offset', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$repeater->start_popover();

		$repeater->add_responsive_control(
			'hot_offset_x',
			array(
				'label'      => __( 'Offset Left', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => - 1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'hot_offset_toggle' => 'yes',
				),
			)
		);

		$repeater->add_responsive_control(
			'hot_offset_y',
			array(
				'label'      => __( 'Offset Top', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => - 1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper {{CURRENT_ITEM}}' => ' top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'hot_offset_toggle' => 'yes',
				),
			)
		);

		$repeater->end_popover();

		$repeater->add_control(
			'show_tooltip',
			array(
				'label'        => __( 'Show Tooltip ', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes',
			)
		);

		$repeater->add_responsive_control(
			'position',
			array(
				'label'                => __( 'Position', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'top',
				'tablet_default'       => 'bottom',
				'mobile_default'       => 'bottom',
				'options'              => array(
					'top'    => __( 'Top', 'xpro-elementor-addons' ),
					'right'  => __( 'Right', 'xpro-elementor-addons' ),
					'bottom' => __( 'Bottom', 'xpro-elementor-addons' ),
					'left'   => __( 'Left', 'xpro-elementor-addons' ),
				),
				'selectors_dictionary' => array(
					'top'    => '--xpro-hotspot-tooltip-top:auto; --xpro-hotspot-tooltip-right:auto; --xpro-hotspot-tooltip-bottom:100%; --xpro-hotspot-tooltip-left:50%; --xpro-hotspot-tooltip-transform-x: -50%; --xpro-hotspot-tooltip-transform-y: 0; --xpro-hotspot-tooltip-margin: 0 0 10px 0;
                    --xpro-hotspot-tooltip-before-top:auto; --xpro-hotspot-tooltip-before-right:auto; --xpro-hotspot-tooltip-before-left: 50%; --xpro-hotspot-tooltip-before-bottom: -5px; --xpro-hotspot-tooltip-before-transform-x: -50%; --xpro-hotspot-tooltip-before-transform-y: 0;',
					'right'  => '--xpro-hotspot-tooltip-bottom:auto; --xpro-hotspot-tooltip-right:auto; --xpro-hotspot-tooltip-left:100%; --xpro-hotspot-tooltip-top:50%; --xpro-hotspot-tooltip-transform-y: -50%; --xpro-hotspot-tooltip-transform-x: 0; --xpro-hotspot-tooltip-margin: 0 0 0 10px;
                    --xpro-hotspot-tooltip-before-bottom:auto; --xpro-hotspot-tooltip-before-right:auto; --xpro-hotspot-tooltip-before-top: 50%; --xpro-hotspot-tooltip-before-left: -5px; --xpro-hotspot-tooltip-before-transform-y: -50%; --xpro-hotspot-tooltip-before-transform-x: 0;',
					'bottom' => '--xpro-hotspot-tooltip-bottom:auto; --xpro-hotspot-tooltip-right:auto; --xpro-hotspot-tooltip-top:100%; --xpro-hotspot-tooltip-left:50%; --xpro-hotspot-tooltip-transform-x: -50%; --xpro-hotspot-tooltip-transform-y: 0; --xpro-hotspot-tooltip-margin: 10px 0 0 0;
                    --xpro-hotspot-tooltip-before-bottom:auto; --xpro-hotspot-tooltip-before-right:auto; --xpro-hotspot-tooltip-before-left: 50%; --xpro-hotspot-tooltip-before-top: -5px; --xpro-hotspot-tooltip-before-transform-x: -50%; --xpro-hotspot-tooltip-before-transform-y: 0;',
					'left'   => '--xpro-hotspot-tooltip-bottom:auto; --xpro-hotspot-tooltip-left:auto; --xpro-hotspot-tooltip-right:100%; --xpro-hotspot-tooltip-top:50%; --xpro-hotspot-tooltip-transform-y: -50%; --xpro-hotspot-tooltip-transform-x: 0; --xpro-hotspot-tooltip-margin: 0 10px 0 0;
                    --xpro-hotspot-tooltip-before-bottom:auto; --xpro-hotspot-tooltip-before-left:auto; --xpro-hotspot-tooltip-before-top: 50%; --xpro-hotspot-tooltip-before-right: -5px; --xpro-hotspot-tooltip-before-transform-y: -50%; --xpro-hotspot-tooltip-before-transform-x: 0;',
				),
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hotspot-tooltip-text,
                    {{WRAPPER}} {{CURRENT_ITEM}} .xpro-hotspot-tooltip-text:before' => '{{VALUE}};',
				),
				'condition'            => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'tooltip_text',
			array(
				'label'       => __( 'Tooltip Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => __( 'Tooltip Content', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Type tooltip text here.', 'xpro-elementor-addons' ),
				'condition'   => array(
					'show_tooltip' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'show_default_tooltip',
			array(
				'label'        => __( 'Default Active Tooltip ', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'hotspot_items',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'show_label'  => false,
				'separator'   => 'before',
				'render_type' => 'template',
				'default'     => array(
					array(
						'hot_icon'     => array(
							'value'   => 'fas fa-plus',
							'library' => 'fa-solid',
						),
						'tooltip_text' => __( 'Tooltip Content', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_style_hot_image',
			array(
				'label' => __( 'Image', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'hot_image_width_size',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hot_image_height_size',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'_hot_object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-image > img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'hot_image_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-image > img',
			)
		);

		$this->add_responsive_control(
			'hot_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hot_image_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-image > img',
			)
		);

		$this->add_responsive_control(
			'hot_image_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-image > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* Spot */
		$this->start_controls_section(
			'section_style_spot',
			array(
				'label' => __( 'Spot', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'spot_font_size',
			array(
				'label'      => __( 'Media Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item .xpro-hotspot-item-wrap > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item .xpro-hotspot-item-wrap > img' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item .xpro-hotspot-item-wrap > svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'spot_width_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'spot_hot' );

		$this->start_controls_tab(
			'spots_hot_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'spot_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item .xpro-hotspot-item-wrap > i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'spot_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spot_hot_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'spot_hvr_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item:hover .xpro-hotspot-item-wrap > i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'spot_bg_hvr_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'spot_hvr_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'spot_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item',
			)
		);

		$this->add_responsive_control(
			'spot_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item,
					{{WRAPPER}} .xpro-hotspot-item-wrap:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'spot_image_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .xpro-hotspot-wrapper .xpro-hotspot-item',
			)
		);

		$this->end_controls_section();

		/* Tooltip */
		$this->start_controls_section(
			'section_style_tooltip',
			array(
				'label' => __( 'Tooltip', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'tooltip_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-tooltip-text' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tooltip_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hotspot-tooltip-text, {{WRAPPER}} .xpro-hotspot-tooltip-text > *',
			)
		);

		$this->add_responsive_control(
			'tooltip_width_size',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-tooltip-text' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-tooltip-text, {{WRAPPER}} .xpro-hotspot-tooltip-text > *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hotspot-tooltip-text,
                    {{WRAPPER}} .xpro-hotspot-tooltip-text:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-tooltip-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-hotspot-tooltip-text',
			)
		);

		$this->add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hotspot-tooltip-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'hot-spot/layout/frontend.php';
	}
}
