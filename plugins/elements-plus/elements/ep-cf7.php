<?php
	namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_CF7 extends Widget_Base {

	public function get_name() {
		return 'ep-cf7-plus';
	}

	public function get_title() {
		return __( 'Contact Form 7 Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-comment';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_options',
			[
				'label' => __( 'Contact Form 7 Plus!', 'elements-plus' ),
			]
		);

		$this->add_control(
			'select_form',
			[
				'label'   => __( 'Select contact form', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => ep_get_cf7_forms(),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_label',
			[
				'label' => __( 'Form labels', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_label_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 label' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_label_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpcf7 label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_list_item_label',
			[
				'label' => __( 'List item labels', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_list_item_label_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7-list-item-label' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_list_item_label_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpcf7-list-item-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_input',
			[
				'label' => __( 'Form inputs', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_input_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_input_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select',
			]
		);

		$this->add_control(
			'form_input_validation',
			[
				'label'     => __( 'Input validation error color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'form_input_bg_color',
			[
				'label'     => __( 'Background color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select' => 'background-color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'form_input_border',
				'default'   => '',
				'selector'  => '{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_input_border_radius',
			[
				'label'      => __( 'Border Radius', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_input_padding',
			[
				'label'      => __( 'Input Padding', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '10',
					'right'    => '10',
					'bottom'   => '10',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'form_input_auto_height',
			[
				'label'        => __( 'Set input height to auto if needed.', 'elements-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'elements-plus' ),
				'label_off'    => __( 'Off', 'elements-plus' ),
				'return_value' => 'auto',
				'selectors'    => [
					'{{WRAPPER}} .wpcf7 input[type="text"],{{WRAPPER}} .wpcf7 input[type="email"],{{WRAPPER}} .wpcf7 input[type="number"],{{WRAPPER}} .wpcf7 input[type="tel"],{{WRAPPER}} .wpcf7 input[type="url"],{{WRAPPER}} .wpcf7 input[type="password"],{{WRAPPER}} .wpcf7 input[type="file"],{{WRAPPER}} .wpcf7 input[type="date"],{{WRAPPER}} .wpcf7 select' => 'height: {{form_input_auto_height}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_textarea',
			[
				'label' => __( 'Form texteareas', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_textarea_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 textarea' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_textarea_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} textarea',
			]
		);

		$this->add_control(
			'form_textarea_bg_color',
			[
				'label'     => __( 'Background color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 textarea' => 'background-color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'form_textarea_border',
				'default'   => '',
				'selector'  => '{{WRAPPER}} .wpcf7 textarea, {{WRAPPER}} .wpcf7 textarea:focus',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_textarea_border_radius',
			[
				'label'      => __( 'Border Radius', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcf7 textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_button',
			[
				'label' => __( 'Form Button', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-submit',
			]
		);

		$this->start_controls_tabs( 'button_tabs_button_style' );

		$this->start_controls_tab(
			'button_tab_button_normal',
			[
				'label' => __( 'Normal', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_button_hover',
			[
				'label' => __( 'Hover', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => __( 'Text Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wpcf7 .wpcf7-submit',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-submit',
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label'      => __( 'Padding', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcf7 .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_response',
			[
				'label' => __( 'Form responce notice', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_response_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-response-output' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_response_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpcf7-response-output',
			]
		);

		$this->add_control(
			'form_response_bg_color',
			[
				'label'     => __( 'Background color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 .wpcf7-response-output' => 'background-color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'form_response_border',
				'default'   => '',
				'selector'  => '{{WRAPPER}} .wpcf7 .wpcf7-response-output',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_response_border_radius',
			[
				'label'      => __( 'Border Radius', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcf7 .wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		$form_id  = $settings['select_form'];

		if ( empty( $form_id ) ) {
			return;
		}

		echo do_shortcode( '[contact-form-7 id="' . $form_id . '" title="quote"]' );
	}

	protected function content_template() {}

}

	add_action(
		'elementor/widgets/register',
		function ( $widgets_manager ) {
			$widgets_manager->register( new Widget_CF7() );
		}
	);

	function ep_get_cf7_forms() {
		$args = array(
			'post_type'      => 'wpcf7_contact_form',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$q = new \WP_Query( $args );

		$forms = wp_list_pluck( $q->posts, 'post_title', 'ID' );

		return $forms;
	}
