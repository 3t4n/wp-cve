<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget for Simple Gallery.
 *
 * @since 1.0.0
 */
class Simple_Gallery extends Widget_Base {

	/**
	 * Default filter is the global filter
	 * and can be overriden from settings
	 *
	 * @var string
	 */
	protected $_default_filter = '*'; //phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-simple-gallery';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Simple Gallery', 'xpro-elementor-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-simple-gallery xpro-widget-label';
	}

	/**
	 * Retrieve the widget keywords.
	 *
	 * @return string[] Widget keywords.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'simple', 'gallery', 'lightbox', 'grid' );
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_style_depends() {

		return array( 'cubeportfolio', 'lightgallery' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_script_depends() {
		return array( 'cubeportfolio', 'lightgallery' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_simple_gallery',
			array(
				'label' => __( 'Gallery', 'xpro-elementor-addons' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'filter',
			array(
				'label'       => __( 'Filter Name', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type gallery filter name', 'xpro-elementor-addons' ),
				'description' => __( 'Filter name must be uinque, Otherwise items show in same filter.', 'xpro-elementor-addons' ),
				'default'     => __( 'Name', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'is_default_filter',
			array(
				'label'        => __( 'Is Default Filter?', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'description'  => __( 'Set this as default active filter.', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'images',
			array(
				'label'       => __( 'Gallery Images', 'xpro-elementor-addons' ),
				'description' => __( 'In Album layout first image will be featured.', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::GALLERY,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'gallery',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'show_label'  => false,
				'title_field' => sprintf(
				/* translators: %s: Title */
					__( 'Filter Group: %1$s', 'xpro-elementor-addons' ),
					'{{filter}}'
				),
				'default'     => array(
					array(
						'filter' => __( 'Filter 1', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'full',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		//Simple Tab
		$this->start_controls_section(
			'section_advance',
			array(
				'label' => __( 'Advanced', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'item_per_row',
			array(
				'label'              => __( 'Items Per Row', 'xpro-elementor-addons' ),
				'description'        => __( 'Adjust items to show in a row.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'placeholder'        => 3,
				'desktop_default'    => 3,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'min'                => 1,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'item_height',
			array(
				'label'              => __( 'Height', 'xpro-elementor-addons' ),
				'description'        => __( 'Adjust the height of gallery items.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', 'vh' ),
				'default'            => array(
					'unit' => 'px',
					'size' => 300,
				),
				'range'              => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .xpro-elementor-gallery-layout-grid .xpro-elementor-gallery-item' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'              => __( 'Margin', 'xpro-elementor-addons' ),
				'description'        => __( 'Adjust the space between items.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'default'            => array(
					'size' => 15,
				),
				'tablet_default'     => array(
					'size' => 15,
				),
				'mobile_default'     => array(
					'size' => 15,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'        => __( 'Icon', 'xpro-elementor-addons' ),
				'description'  => __( 'To show item icon.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'        => __( 'Caption', 'xpro-elementor-addons' ),
				'description'  => __( 'To show image caption.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'        => __( 'Description', 'xpro-elementor-addons' ),
				'description'  => __( 'To show image description.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		//Filter Tab
		$this->start_controls_section(
			'section_filter',
			array(
				'label' => __( 'Filter', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_filter',
			array(
				'label'        => __( 'Show Filter Menu', 'xpro-elementor-addons' ),
				'description'  => __( 'Enable to display filter menu.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'filter_all',
			array(
				'label'        => __( 'Show "All" Filter', 'xpro-elementor-addons' ),
				'description'  => __( 'To Enable to display "All" filter in filter menu.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_all_text',
			array(
				'label'       => __( 'All Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'To Change "All" text in filter menu.', 'xpro-elementor-addons' ),
				'default'     => __( 'All', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_filter' => 'yes',
					'filter_all'  => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_animation',
			array(
				'label'              => __( 'Filter Animation', 'xpro-elementor-addons' ),
				'description'        => __( 'Define animation that show during filter.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3dflip',
				'options'            => array(
					'3dflip'    => __( '3D Flip', 'xpro-elementor-addons' ),
					'quicksand' => __( 'Quick Sand', 'xpro-elementor-addons' ),
					'fadeOut'   => __( 'Fade Out', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'show_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_dropdown',
			array(
				'label'       => __( 'Show Dropdown On', 'xpro-elementor-addons' ),
				'description' => __( 'Select when you want to show dropdown.', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'mobile',
				'options'     => array(
					'tablet' => __( 'Tablet & Mobile', 'xpro-elementor-addons' ),
					'mobile' => __( 'Mobile', 'xpro-elementor-addons' ),
					'none'   => __( 'None', 'xpro-elementor-addons' ),
				),
				'condition'   => array(
					'show_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		//Popup Tab
		$this->start_controls_section(
			'section_popup',
			array(
				'label' => __( 'Popup', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'popup',
			array(
				'label'              => __( 'Popup', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => array(
					'1'    => __( 'Classic', 'xpro-elementor-addons' ),
					'none' => __( 'None', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'thumbnail',
			array(
				'label'              => __( 'Thumbnail', 'xpro-elementor-addons' ),
				'description'        => __( 'Enable thumbnails for the gallery.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'popup!' => 'none',
				),
			)
		);

		$this->add_control(
			'thumbnail_by_default',
			array(
				'label'              => __( 'Thumb By Default', 'xpro-elementor-addons' ),
				'description'        => __( 'Show/Hide thumbnails by default.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'popup!'    => array( 'none', '2', '4' ),
					'thumbnail' => 'yes',
				),
			)
		);

		$this->add_control(
			'share',
			array(
				'label'              => __( 'Social Share', 'xpro-elementor-addons' ),
				'description'        => __( 'To show share buttons on popup.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'popup!' => 'none',
				),
			)
		);

		$this->add_control(
			'download',
			array(
				'label'              => __( 'Download', 'xpro-elementor-addons' ),
				'description'        => __( 'To show download button on popup.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'popup!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_overlay_style',
			array(
				'label' => __( 'Overlay', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'hover_effect',
			array(
				'label'              => __( 'Hover Effect', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'zoom',
				'options'            => array(
					'zoom'   => __( 'Zoom', 'xpro-elementor-addons' ),
					'fadeIn' => __( 'Fade In', 'xpro-elementor-addons' ),
					'rotate' => __( 'Rotate', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hover_color',
				'label'    => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery .cbp-caption-active .cbp-caption-activeWrap',
			)
		);

		$this->add_control(
			'outline_color',
			array(
				'label'     => __( 'Outline Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cbp-caption-zoom-box .cbp-caption-activeWrap:before,{{WRAPPER}} .cbp-caption-zoom-box .cbp-caption-activeWrap:after,{{WRAPPER}} .cbp-caption-zoom-box-out .cbp-caption-activeWrap:before,{{WRAPPER}} .cbp-caption-zoom-box-out .cbp-caption-activeWrap:after' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'hover_effect' => array( 'zoom-box', 'zoom-box-out' ),
				),
			)
		);

		$this->add_control(
			'overlay_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .cbp-caption-active .cbp-caption-activeWrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => __( 'Icon', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_name',
			array(
				'label'   => __( 'Icon', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-expand-arrows-alt',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon'       => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'icon_style_tabs'
		);

		$this->start_controls_tab(
			'icon_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon > i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg',
			array(
				'label'     => __( 'Icon Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'icon_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_hcolor',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon:hover > i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon:hover > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_hbg',
			array(
				'label'     => __( 'Icon Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_hborder',
			array(
				'label'     => __( 'Icon Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-overlay-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_caption_style',
			array(
				'label'     => __( 'Caption', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'caption' => 'yes',
				),
			)
		);

		$this->add_control(
			'caption_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery .xpro-title',
			)
		);

		$this->add_control(
			'caption_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description_style',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'description' => 'yes',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery .xpro-desc',
			)
		);

		$this->add_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_style',
			array(
				'label'     => __( 'Filter', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_filter' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item, {{WRAPPER}} .xpro-elementor-gallery-filter .xpro-select-option',
			)
		);

		$this->add_responsive_control(
			'filter_align',
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs(
			'filter_style_tabs'
		);

		$this->start_controls_tab(
			'filter_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'filter_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'filter_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'filter_hcolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_hbg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_active_tab_style',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'filter_acolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item.cbp-filter-item-active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_abg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item.cbp-filter-item-active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_aborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item.cbp-filter-item-active' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'filter_item_space',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_item_padding',
			array(
				'label'      => __( 'Item Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter > ul > li.cbp-filter-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-gallery-filter .xpro-select-option' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_dropdown',
			array(
				'label'     => __( 'Dropdown', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_dropdown!' => 'none',
					'show_filter'    => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_dropdown_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet){{WRAPPER}} .xpro-filter-dropdown-tablet .xpro-select-option, {{WRAPPER}} .xpro-filter-dropdown-tablet .cbp-l-filters-button .cbp-filter-item' => 'color: {{VALUE}} !important;',
					'(mobile){{WRAPPER}} .xpro-filter-dropdown-mobile .xpro-select-option, {{WRAPPER}} .xpro-filter-dropdown-mobile .cbp-l-filters-button .cbp-filter-item' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'filter_dropdown_bgcolor',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet){{WRAPPER}} .xpro-filter-dropdown-tablet .xpro-select-option, {{WRAPPER}} .xpro-filter-dropdown-tablet .cbp-l-filters-button .cbp-filter-item' => 'background-color: {{VALUE}} !important;',
					'(mobile){{WRAPPER}} .xpro-filter-dropdown-mobile .xpro-select-option, {{WRAPPER}} .xpro-filter-dropdown-mobile .cbp-l-filters-button .cbp-filter-item' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'filter_dropdown_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet){{WRAPPER}} .xpro-filter-dropdown-tablet .cbp-l-filters-button, {{WRAPPER}} .xpro-filter-dropdown-tablet .cbp-l-filters-button .cbp-filter-item' => 'border-color: {{VALUE}} !important;',
					'(mobile){{WRAPPER}} .xpro-filter-dropdown-mobile .cbp-l-filters-button, {{WRAPPER}} .xpro-filter-dropdown-mobile .cbp-l-filters-button .cbp-filter-item' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_misc',
			array(
				'label' => __( 'Misc', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'misc_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-gallery .xpro-elementor-gallery-item .cbp-caption',
			)
		);

		$this->add_control(
			'misc_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-gallery .xpro-elementor-gallery-item .cbp-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings       = $this->get_settings_for_display();
		$simple_gallery = $this->get_gallery_data();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'simple-gallery/layout/frontend.php';
	}

	protected function get_gallery_data() {

		$gallery = $this->get_settings_for_display( 'gallery' );

		if ( ! is_array( $gallery ) || empty( $gallery ) ) {
			return array();
		}

		$menu  = array();
		$items = array();

		foreach ( $gallery as $key => $item ) {
			$images = $item['images'];

			$filter = xpro_elementor_friendly_str_replace( $item['filter'] );

			if ( ! empty( $item['is_default_filter'] ) ) {
				$this->_default_filter = '.' . $filter;
			}

			if ( $filter && ! isset( $data[ $filter ] ) ) {
				$menu[ $filter ] = $item['filter'];
			}

			if ( isset( $images ) ) {
				foreach ( $images as $image ) {
					if ( ! empty( $image['url'] ) && ! empty( attachment_url_to_postid( $image['url'] ) ) && ! isset( $items[ attachment_url_to_postid( $image['url'] ) ] ) ) {
						$items[ attachment_url_to_postid( $image['url'] ) ] = $filter;
					} elseif ( ! empty( $image['id'] ) && ! empty( attachment_url_to_postid( wp_get_attachment_url( $image['id'] ) ) ) && ! isset( $items[ attachment_url_to_postid( wp_get_attachment_url( $image['id'] ) ) ] ) ) {
						$items[ attachment_url_to_postid( wp_get_attachment_url( $image['id'] ) ) ] = $filter;
					} elseif ( ! empty( $image['id'] ) && ! isset( $items[ $image['id'] ] ) ) {
						$items[ $image['id'] ] = $filter;
					} else {
						if ( ! empty( $image['url'] ) ) {
							$items[ attachment_url_to_postid( $image['url'] ) ] .= ' ' . $filter;
						} else {
							$items[ $image['id'] ] .= ' ' . $filter;
						}
					}
				}
			}
		}

		return compact( 'menu', 'items' );
	}
}
