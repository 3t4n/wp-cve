<?php

namespace AweBooking\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Elementor Rooms.
 */
class Search_Availability_Form extends Abstract_Widget {

	/**
	 * Get widget patch.
	 *
	 * Retrieve widget patch.
	 *
	 * @access public
	 *
	 * @return string Widget patch.
	 */
	public function get_path() {
		return 'search-availability-form';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Search Availability Form', 'awebooking-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return apply_filters( 'abrs_elementor_' . $this->get_name(), 'eicon-search' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'attributes_section',
			[
				'label' => esc_html__( 'Attributes', 'awebooking-elementor' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'awebooking-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'awebooking-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'awebooking-elementor' ),
				],
			]
		);

		$this->add_control(
			'occupancy',
			[
				'label'     => esc_html__( 'Occupancy', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'awebooking-elementor' ),
				'label_off' => esc_html__( 'Hide', 'awebooking-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'hotel_location',
			[
				'label'     => esc_html__( 'Hotel location', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'awebooking-elementor' ),
				'label_off' => esc_html__( 'Hide', 'awebooking-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Alignment', 'awebooking-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .searchbox' => 'text-align: {{VALUE}};',
				],
				'default'   => 'left',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'  => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .searchbox__box-icon' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__box-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_typography',
				'selector'  => '{{WRAPPER}} .searchbox__box-icon',
				'condition' => [
					'icon_display' => 'block',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Input', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'input_margin',
			[
				'label'      => esc_html__( 'Margin', 'awebooking-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .searchbox__box-line' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'awebooking-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .searchbox__box-line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label'     => esc_html__( 'Label', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'  => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .searchbox__box-label' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__box-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'label_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'label_typography',
				'selector'  => '{{WRAPPER}} .searchbox__box-label',
				'condition' => [
					'label_display' => 'block',
				],
			]
		);

		$this->add_responsive_control(
			'label_margin_bottom',
			[
				'label'     => esc_html__( 'Margin bottom (px)', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .searchbox__box-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'label_display' => 'block',
				],
			]
		);

		$this->add_control(
			'heading_input_text',
			[
				'label'     => esc_html__( 'Input', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_control(
			'input_text_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__box-input, {{WRAPPER}} .searchbox__input, {{WRAPPER}} .searchbox__spinner-box, {{WRAPPER}} input[type="number"].searchbox__spinner-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_text_typography',
				'selector' => '{{WRAPPER}} .searchbox__box-input, {{WRAPPER}} .searchbox__input, {{WRAPPER}} .searchbox__spinner-box, {{WRAPPER}} input[type="number"].searchbox__spinner-input',
			]
		);

		$this->add_responsive_control(
			'input_text_margin_bottom',
			[
				'label'     => esc_html__( 'Margin bottom (px)', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .searchbox__box-input' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'label'     => esc_html__( 'Button', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'button_colors' );

		$this->start_controls_tab(
			'button_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'awebooking-elementor' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Background color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => esc_html__( 'Border color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__submit' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'awebooking-elementor' ),
			]
		);

		$this->add_control(
			'hover_button_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_button_bg_color',
			[
				'label'     => esc_html__( 'Background color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_button_border_color',
			[
				'label'     => esc_html__( 'Border color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .searchbox__submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .searchbox__submit',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'awebooking-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .searchbox__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Margin', 'awebooking-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .searchbox__submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}
}
