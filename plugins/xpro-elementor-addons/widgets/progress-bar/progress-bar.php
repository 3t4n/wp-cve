<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
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
class Progress_Bar extends Widget_Base {

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
		return 'xpro-progress-bar';
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
		return __( 'Progress Bar', 'xpro-elementor-addons' );
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
		return 'xi-progress-bar xpro-widget-label';
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
		return array( 'skill', 'progress', 'bar' );
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
		return array( 'elementor-waypoints' );
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
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'1'  => esc_html__( 'Style 1', 'xpro-elementor-addons' ),
					'2'  => esc_html__( 'Style 2', 'xpro-elementor-addons' ),
					'3'  => esc_html__( 'Style 3', 'xpro-elementor-addons' ),
					'4'  => esc_html__( 'Style 4', 'xpro-elementor-addons' ),
					'5'  => esc_html__( 'Style 5', 'xpro-elementor-addons' ),
					'6'  => esc_html__( 'Style 6', 'xpro-elementor-addons' ),
					'7'  => esc_html__( 'Style 7', 'xpro-elementor-addons' ),
					'8'  => esc_html__( 'Style 8', 'xpro-elementor-addons' ),
					'9'  => esc_html__( 'Style 9', 'xpro-elementor-addons' ),
					'10' => esc_html__( 'Style 10', 'xpro-elementor-addons' ),
					'11' => esc_html__( 'Style 11', 'xpro-elementor-addons' ),
					'12' => esc_html__( 'Style 12', 'xpro-elementor-addons' ),
					'13' => esc_html__( 'Style 13', 'xpro-elementor-addons' ),
					'14' => esc_html__( 'Style 14', 'xpro-elementor-addons' ),
					'15' => esc_html__( 'Style 15', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'xpro-elementor-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'WordPress', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'value',
			array(
				'label'              => esc_html__( 'Percentage', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 100,
				'step'               => 1,
				'default'            => 90,
				'frontend_available' => true,
				'dynamic'            => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'              => __( 'Show Count', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'          => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'              => esc_html__( 'Animation Duration', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'frontend_available' => true,
				'range'              => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),

				),
				'default'            => array(
					'size' => 3,
				),

			)
		);

		$this->add_control(
			'align',
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
					'{{WRAPPER}} .xpro-progress-content' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '15' ),
				),
			)
		);

		$this->end_controls_section();

		//Title Style
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-progress-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Counter Style
		$this->start_controls_section(
			'section_style_counter',
			array(
				'label' => __( 'Counter', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'counter_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-progress-counter',
			)
		);

		$this->add_control(
			'counter_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-counter' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'counter_secondary_color',
			array(
				'label'     => __( 'Decrement Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-bar-layout-6 .xpro-progress-count-less-wrapper' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '6' ),
				),
			)
		);

		$this->add_control(
			'counter_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-bar-layout-3 .xpro-progress-counter,{{WRAPPER}} .xpro-progress-bar-layout-7 .xpro-progress-counter,
					 {{WRAPPER}}  .xpro-progress-bar-layout-11 .xpro-progress-track::after,{{WRAPPER}} .xpro-progress-bar-layout-12 .xpro-progress-counter,
					 {{WRAPPER}} .xpro-progress-bar-layout-13 .xpro-progress-counter,{{WRAPPER}} .xpro-progress-bar-layout-14 .xpro-progress-counter'               => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .xpro-progress-bar-layout-7 .xpro-progress-counter::after,{{WRAPPER}} .xpro-progress-bar-layout-13 .xpro-progress-counter::before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} .xpro-progress-bar-layout-14 .xpro-progress-counter::before'                                                                       => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '3', '7', '11', '12', '13', '14' ),
				),
			)
		);

		$this->add_control(
			'counter_shape_color',
			array(
				'label'     => __( 'Shape Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-control' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .xpro-progress-control:before,{{WRAPPER}} .xpro-progress-bar-layout-10 .xpro-progress-counter::after' => 'border-top-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '5', '10' ),
				),
			)
		);

		$this->add_control(
			'counter_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-bar-layout-7 .xpro-progress-counter,{{WRAPPER}} .xpro-progress-bar-layout-11 .xpro-progress-track::after,{{WRAPPER}} .xpro-progress-bar-layout-12 .xpro-progress-counter' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( '7', '11', '12' ),
				),
			)
		);

		$this->add_control(
			'counter_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-bar-layout-7 .xpro-progress-counter::before,{{WRAPPER}} .xpro-progress-bar-layout-11 .xpro-progress-track::after,{{WRAPPER}} .xpro-progress-bar-layout-12 .xpro-progress-counter' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '7', '11', '12' ),
				),
			)
		);

		$this->add_responsive_control(
			'counter_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_bar',
			array(
				'label' => __( 'Track', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bar_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-bar' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'bar_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-progress-bar,{{WRAPPER}}  .xpro-progress-bar-layout-11 .xpro-progress-track::after',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'bar_shadow',
				'label'    => __( 'Bar Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-progress-bar',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'bar_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-progress-bar',
			)
		);

		$this->add_responsive_control(
			'bar_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_track',
			array(
				'label' => __( 'Bar', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'track_striped',
			array(
				'label'        => __( 'Striped', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .xpro-progress-track' => 'background-size: 1rem 1rem; background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);',
				),
				'condition'    => array(
					'layout!' => array( '9', '10' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'track_bg',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-progress-track',
				'condition' => array(
					'layout!' => array( '9', '10' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'track_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-progress-track',
				'condition' => array(
					'layout!' => array( '9', '10' ),
				),
			)
		);

		$this->add_control(
			'track_striped_color',
			array(
				'label'     => __( 'Striped Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-progress-track' => 'background: repeating-linear-gradient(to right,{{VALUE}},{{VALUE}} 10px,transparent 10px,transparent 12px)',
				),
				'condition' => array(
					'layout' => array( '9', '10' ),
				),
			)
		);

		$this->add_responsive_control(
			'track_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-progress-track' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'progress-bar/layout/frontend.php';
	}
}
