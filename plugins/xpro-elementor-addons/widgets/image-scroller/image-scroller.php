<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Image_Selector;

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
class Image_Scroller extends Widget_Base {

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
		return 'xpro-image-scroller';
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
		return __( 'Image Scroller', 'xpro-elementor-addons' );
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
		return 'xi-scroll-image xpro-widget-label';
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
		return array( 'xpro', 'image', 'scrolling', 'scroll', 'img' );
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

		return array( 'animate' );
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
		return array( 'imagesloaded' );
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
			'section_scroller_image',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'direction_type',
			array(
				'label'              => __( 'Direction', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'vertical',
				'options'            => array(
					'vertical'   => __( 'Vertical', 'xpro-elementor-addons' ),
					'horizontal' => __( 'Horizontal', 'xpro-elementor-addons' ),
				),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'trigger_type',
			array(
				'label'              => __( 'Trigger', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'mouse-hover',
				'options'            => array(
					'mouse-hover'  => __( 'Mouse Hover', 'xpro-elementor-addons' ),
					'mouse-scroll' => __( 'Mouse Scroll', 'xpro-elementor-addons' ),
				),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'reverse',
			array(
				'label'              => __( 'Reverse', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'          => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'condition'          => array(
					'trigger_type' => 'mouse-hover',
				),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'duration_speed',
			array(
				'label'     => __( 'Duration', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'condition' => array(
					'trigger_type' => 'mouse-hover',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-scroll-img > img' => 'transition-duration: {{Value}}s',
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

		$this->add_control(
			'mask_image',
			array(
				'label'        => __( 'Mask Image', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_control(
			'mask_shape',
			array(
				'label'   => __( 'Mask Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'default',
				'options' => array(
					'default' => array(
						'title' => _x( 'Default Shapes', 'Mask Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image-bold',
					),
					'custom'  => array(
						'title' => _x( 'Custom Shape', 'Mask Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-upload',
					),
				),
				'toggle'  => false,
			)
		);

		$this->add_control(
			'mask_shape_default',
			array(
				'label'                => _x( 'Default', 'Mask Image', 'xpro-elementor-addons' ),
				'label_block'          => true,
				'show_label'           => false,
				'type'                 => Xpro_Elementor_Image_Selector::TYPE,
				'default'              => 'shape1',
				'options'              => xpro_elementor_masking_shape_list( 'list' ),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-image-scroll-img' => '-webkit-mask-image: url({{VALUE}}); mask-image: url({{VALUE}});',
				),
				'selectors_dictionary' => xpro_elementor_masking_shape_list( 'url' ),
				'condition'            => array(
					'mask_image' => 'yes',
					'mask_shape' => 'default',
				),
			)
		);

		$this->add_control(
			'mask_custom_shape',
			array(
				'label'       => _x( 'Custom Shape', 'Mask Image', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::MEDIA,
				'show_label'  => false,
				'description' => sprintf(
				/* translators: %s: Title */
					__( 'Note: Make sure svg support is enable to upload svg file. %1$sRead More%2$s', 'xpro-elementor-addons' ),
					'<a href="https://elementor.com/help/enable-svg-support-in-elementor/" target="_blank">',
					'</a>'
				),
				'selectors'   => array(
					'{{WRAPPER}} .xpro-image-scroll-img' => '-webkit-mask-image: url({{URL}}); mask-image: url({{URL}});',
				),
				'condition'   => array(
					'mask_image' => 'yes',
					'mask_shape' => 'custom',
				),
			)
		);

		$this->add_control(
			'mask_position',
			array(
				'label'                => _x( 'Position', 'Mask Image', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'center-center',
				'options'              => array(
					'center-center' => _x( 'Center Center', 'Mask Image', 'xpro-elementor-addons' ),
					'center-left'   => _x( 'Center Left', 'Mask Image', 'xpro-elementor-addons' ),
					'center-right'  => _x( 'Center Right', 'Mask Image', 'xpro-elementor-addons' ),
					'top-center'    => _x( 'Top Center', 'Mask Image', 'xpro-elementor-addons' ),
					'top-left'      => _x( 'Top Left', 'Mask Image', 'xpro-elementor-addons' ),
					'top-right'     => _x( 'Top Right', 'Mask Image', 'xpro-elementor-addons' ),
					'bottom-center' => _x( 'Bottom Center', 'Mask Image', 'xpro-elementor-addons' ),
					'bottom-left'   => _x( 'Bottom Left', 'Mask Image', 'xpro-elementor-addons' ),
					'bottom-right'  => _x( 'Bottom Right', 'Mask Image', 'xpro-elementor-addons' ),
				),
				'selectors_dictionary' => array(
					'center-center' => 'center center',
					'center-left'   => 'center left',
					'center-right'  => 'center right',
					'top-center'    => 'top center',
					'top-left'      => 'top left',
					'top-right'     => 'top right',
					'bottom-center' => 'bottom center',
					'bottom-left'   => 'bottom left',
					'bottom-right'  => 'bottom right',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-image-scroll-img' => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mask_size',
			array(
				'label'     => _x( 'Size', 'Mask Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'contain',
				'options'   => array(
					'auto'    => _x( 'Auto', 'Mask Image', 'xpro-elementor-addons' ),
					'cover'   => _x( 'Cover', 'Mask Image', 'xpro-elementor-addons' ),
					'contain' => _x( 'Contain', 'Mask Image', 'xpro-elementor-addons' ),
					'initial' => _x( 'Custom', 'Mask Image', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-scroll-img' => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'mask_custom_size',
			array(
				'label'      => _x( 'Custom Size', 'Mask Image', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%', 'vw' ),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-image-scroll-img' => '-webkit-mask-size: {{SIZE}}{{UNIT}}; mask-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'mask_size' => 'initial',
				),
			)
		);

		$this->add_control(
			'mask_repeat',
			array(
				'label'                => _x( 'Repeat', 'Mask Image', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'repeat',
				'options'              => array(
					'repeat'          => _x( 'Repeat', 'Mask Image', 'xpro-elementor-addons' ),
					'repeat-x'        => _x( 'Repeat-x', 'Mask Image', 'xpro-elementor-addons' ),
					'repeat-y'        => _x( 'Repeat-y', 'Mask Image', 'xpro-elementor-addons' ),
					'space'           => _x( 'Space', 'Mask Image', 'xpro-elementor-addons' ),
					'round'           => _x( 'Round', 'Mask Image', 'xpro-elementor-addons' ),
					'no-repeat'       => _x( 'No-repeat', 'Mask Image', 'xpro-elementor-addons' ),
					'repeat-space'    => _x( 'Repeat Space', 'Mask Image', 'xpro-elementor-addons' ),
					'round-space'     => _x( 'Round Space', 'Mask Image', 'xpro-elementor-addons' ),
					'no-repeat-round' => _x( 'No-repeat Round', 'Mask Image', 'xpro-elementor-addons' ),
				),
				'selectors_dictionary' => array(
					'repeat'          => 'repeat',
					'repeat-x'        => 'repeat-x',
					'repeat-y'        => 'repeat-y',
					'space'           => 'space',
					'round'           => 'round',
					'no-repeat'       => 'no-repeat',
					'repeat-space'    => 'repeat space',
					'round-space'     => 'round space',
					'no-repeat-round' => 'no-repeat round',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-image-scroll-img' => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				),
			)
		);

		$this->end_popover();

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

		$this->add_control(
			'show_indicator',
			array(
				'label'        => __( 'Overlay', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'icon_indicator',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'xi xi-mouse-2',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'show_indicator' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_badge',
			array(
				'label'        => __( 'Enable Badge', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'badge_text',
			array(
				'label'       => __( 'Badge Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'Badge Text',
				'placeholder' => __( 'Type Icon Badge Text', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Box Link', 'xpro-elementor-addons' ),
				'separator'   => 'before',
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		/* General */
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 300,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-scroll-image-inner' => 'height: {{SIZE}}{{UNIT}};',

				),
			)
		);

		$this->start_controls_tabs( '_image' );

		$this->start_controls_tab(
			'_scroll_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);
		$this->add_control(
			'opacity_normal',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-scroll-img > img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_normal',
				'selector' => '{{WRAPPER}} .xpro-image-scroll-img > img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_image_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-scroll-img:hover > img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .xpro-image-scroll-img:hover > img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'xpro-elementor-addons' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'luminosity'  => 'Luminosity',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-scroll-img img' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		/* Indicator */
		$this->start_controls_section(
			'section_style_indicator',
			array(
				'label'     => __( 'Overlay', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_indicator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'indicator-font',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 80,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-scroll-image-wrapper .xpro-scroll-image-indicator-icon' => 'font-size: {{SIZE}}{{UNIT}};',

				),
			)
		);

		$this->add_control(
			'indicator_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-scroll-image-wrapper .xpro-scroll-image-indicator-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'indicator_bg_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-scroll-image-wrapper .xpro-scroll-image-indicator-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* Badge */
		$this->start_controls_section(
			'section_style_badge',
			array(
				'label'     => __( 'Badge', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_badge!' => '',
				),
			)
		);

		$this->add_control(
			'badge_position',
			array(
				'label'   => __( 'Position', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'top-left'      => __( 'Top Left', 'xpro-elementor-addons' ),
					'top-center'    => __( 'Top Center', 'xpro-elementor-addons' ),
					'top-right'     => __( 'Top Right', 'xpro-elementor-addons' ),
					'middle-left'   => __( 'Middle Left', 'xpro-elementor-addons' ),
					'middle-center' => __( 'Middle Center', 'xpro-elementor-addons' ),
					'middle-right'  => __( 'Middle Right', 'xpro-elementor-addons' ),
					'bottom-left'   => __( 'Bottom Left', 'xpro-elementor-addons' ),
					'bottom-center' => __( 'Bottom Center', 'xpro-elementor-addons' ),
					'bottom-right'  => __( 'Bottom Right', 'xpro-elementor-addons' ),
				),
				'default' => 'top-left',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-badge',
			)
		);

		$this->add_control(
			'badge_offset_toggle',
			array(
				'label'        => __( 'Offset', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_offset_x',
			array(
				'label'      => __( 'Offset Left', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'badge_offset_toggle' => 'yes',
				),
				'range'      => array(
					'px' => array(
						'min' => - 1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-badge' => '--xpro-badge-translate-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_offset_y',
			array(
				'label'      => __( 'Offset Top', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'badge_offset_toggle' => 'yes',
				),
				'range'      => array(
					'px' => array(
						'min' => - 1000,
						'max' => 1000,
					),
					'%'  => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-badge' => '--xpro-badge-translate-y: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_popover();

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'badge_border',
				'selector' => '{{WRAPPER}} .xpro-badge',
			)
		);

		$this->add_responsive_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-badge',
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'image-scroller/layout/frontend.php';
	}
}
