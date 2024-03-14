<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
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
class Before_After extends Widget_Base {


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
		return 'xpro-before-after';
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
		return __( 'Before After', 'xpro-elementor-addons' );
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
		return 'xi-before-after xpro-widget-label';
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
		return array( 'before', 'after', 'compare', 'comparison' );
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
		return array( 'xpro-compare' );
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
		return array( 'xpro-compare' );
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
			'section_before',
			array(
				'label' => __( 'Before', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'before_label',
			array(
				'label'       => __( 'Label', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Before', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Before Text Here', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'before_image',
			array(
				'label'   => __( 'Choose Image', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'before_thumbnail',
				'default'   => 'large',
				'separator' => 'none',
				'exclude'   => array(
					'custom',
				),
			)
		);

		$this->end_controls_section();

		//After
		$this->start_controls_section(
			'section_after',
			array(
				'label' => __( 'After', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'after_label',
			array(
				'label'       => __( 'Label', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'After', 'xpro-elementor-addons' ),
				'placeholder' => __( 'After Text Here', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'after_image',
			array(
				'label'   => __( 'Choose Image', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'after_thumbnail',
				'default'   => 'large',
				'separator' => 'none',
				'exclude'   => array(
					'custom',
				),
			)
		);

		$this->end_controls_section();

		//Settings
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => __( 'Settings', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'              => __( 'Orientation', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'horizontal'  => __( 'Horizontal', 'xpro-elementor-addons' ),
					'vertical'    => __( 'Vertical', 'xpro-elementor-addons' ),
					'sides'       => __( 'Diagonal Left', 'xpro-elementor-addons' ),
					'sides-right' => __( 'Diagonal Right', 'xpro-elementor-addons' ),
				),
				'default'            => 'horizontal',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'display_label',
			array(
				'label'   => __( 'Display Label', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'on-hover' => __( 'On Hover', 'xpro-elementor-addons' ),
					'always'   => __( 'Always', 'xpro-elementor-addons' ),
				),
				'default' => 'always',
			)
		);

		$this->add_control(
			'display_handle',
			array(
				'label'   => __( 'Display Handle', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'on-hover' => __( 'On Hover', 'xpro-elementor-addons' ),
					'always'   => __( 'Always', 'xpro-elementor-addons' ),
				),
				'default' => 'always',
			)
		);

		$this->add_control(
			'mouse_move',
			array(
				'label'              => __( 'Move On Hover', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'          => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'wiggle',
			array(
				'label'              => __( 'Wiggle', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'          => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'wiggle_timeout',
			array(
				'label'              => __( 'Wiggle Delay', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 1.5,
				),
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'condition'          => array(
					'wiggle' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'visible_ratio',
			array(
				'label'              => __( 'Visible Ratio', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'separator'          => 'before',
				'default'            => array(
					'size' => 50,
				),
				'size_units'         => array( '%' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		//General
		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'item_height',
			array(
				'label'       => __( 'Height', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'vh', '%' ),
				'default'     => array(
					'unit' => 'vh',
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} .xpro-compare-wrapper, {{WRAPPER}} .xpro-compare-item img' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-container .xpro-compare-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		//Label
		$this->start_controls_section(
			'section_label_style',
			array(
				'label' => __( 'Label', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label, {{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label',
			)
		);

		$this->start_controls_tabs( 'label_before_after_tabs' );

		$this->start_controls_tab(
			'label_before_tab',
			array(
				'label' => __( 'Before', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'label_before_hpos',
			array(
				'label'                => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
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
				'default'              => 'center',
				'selectors_dictionary' => array(
					'left'   => 'left: 0;',
					'center' => 'left:50%; transform:translateX(-50%);',
					'right'  => 'right:0;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label' => '{{VALUE}};',
				),
				'toggle'               => false,
				'condition'            => array(
					'orientation' => 'vertical',
				),
			)
		);

		$this->add_control(
			'label_before_vpos',
			array(
				'label'                => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'toggle'               => false,
				'selectors_dictionary' => array(
					'top'    => 'top: 0;',
					'middle' => 'top:50%; transform:translateY(-50%);',
					'bottom' => 'bottom:0;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label' => '{{VALUE}};',
				),
				'condition'            => array(
					'orientation!' => 'vertical',
				),
			)
		);

		$this->add_control(
			'before_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'before_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'before_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'label_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'label_after_tab',
			array(
				'label' => __( 'After', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'label_after_hpos',
			array(
				'label'                => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
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
				'default'              => 'center',
				'selectors_dictionary' => array(
					'left'   => 'left: 0;',
					'center' => 'left:50%; transform:translateX(-50%);',
					'right'  => 'right:0;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => '{{VALUE}};',
				),
				'toggle'               => false,
				'condition'            => array(
					'orientation' => 'vertical',
				),
			)
		);

		$this->add_control(
			'label_after_vpos',
			array(
				'label'                => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'toggle'               => false,
				'selectors_dictionary' => array(
					'top'    => 'top: 0;',
					'middle' => 'top:50%; transform:translateY(-50%);',
					'bottom' => 'bottom:0;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => '{{VALUE}};',
				),
				'condition'            => array(
					'orientation!' => 'vertical',
				),
			)
		);

		$this->add_control(
			'after_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'after_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'after_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'label_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'label_border',
				'selector'  => '{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label, {{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'label_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label, {{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label, {{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-compare-overlay .xpro-compare-before-label, {{WRAPPER}} .xpro-compare-overlay .xpro-compare-after-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Handle
		$this->start_controls_section(
			'section_separator_style',
			array(
				'label' => __( 'Separator', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'separator_line_size',
			array(
				'label'      => __( 'Line Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-compare-vertical .xpro-compare-handle:after, {{WRAPPER}} .xpro-compare-vertical .xpro-compare-handle:before'     => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-compare-horizontal .xpro-compare-handle:after, {{WRAPPER}} .xpro-compare-horizontal .xpro-compare-handle:before' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'orientation' => array( 'vertical', 'horizontal' ),
				),
			)
		);

		$this->start_controls_tabs( 'separator_tabs' );

		$this->start_controls_tab(
			'separator_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'separator_icon_color',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-container .xpro-compare-handle .xpro-compare-up-arrow'    => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-compare-container .xpro-compare-handle .xpro-compare-down-arrow'  => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-compare-container .xpro-compare-handle .xpro-compare-left-arrow'  => 'border-right-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-compare-container .xpro-compare-handle .xpro-compare-right-arrow' => 'border-left-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_icon_bg',
			array(
				'label'     => __( 'Icon Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-handle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_line_color',
			array(
				'label'     => __( 'Line Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-handle:after,{{WRAPPER}} .xpro-compare-handle:before' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'orientation' => array( 'vertical', 'horizontal' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'separator_hover_tab',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'separator_icon_hcolor',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-handle:hover .xpro-compare-up-arrow'    => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-compare-handle:hover .xpro-compare-down-arrow'  => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-compare-handle:hover .xpro-compare-left-arrow'  => 'border-right-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-compare-handle:hover .xpro-compare-right-arrow' => 'border-left-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_icon_hbg',
			array(
				'label'     => __( 'Icon Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-handle:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_border_hcolor',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-handle:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'separator_border_border!' => '',
				),
			)
		);

		$this->add_control(
			'separator_line_hcolor',
			array(
				'label'     => __( 'Line Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-compare-handle:hover::after, .xpro-compare-handle:hover::before' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'orientation' => array( 'vertical', 'horizontal' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'separator_border',
				'selector'  => '{{WRAPPER}} .xpro-compare-handle',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'separator_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-compare-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'before-after/layout/frontend.php';
	}
}
