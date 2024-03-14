<?php
	namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_EP_Caldera extends Widget_Base {

	public function get_name() {
		return 'ep-caldera-forms-plus';
	}

	public function get_title() {
		return __( 'Caldera Forms Plus!', 'elements-plus' );
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
				'label' => __( 'Caldera Forms Plus!', 'elements-plus' ),
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label'       => __( 'Widget title', 'elements-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Widget title', 'elements-plus' ),
			]
		);

		$this->add_control(
			'select_form',
			[
				'label'   => __( 'Select Form', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => wp_list_pluck( \Caldera_Forms_Forms::get_forms( true ), 'name', 'ID' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_widget_title',
			[
				'label' => __( 'Widget title', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'widget_title_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
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
				'name'     => 'widget_title_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .widget-title',
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
					'{{WRAPPER}} .caldera-grid label' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .caldera-grid label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_sublabel',
			[
				'label' => __( 'Field descriptions', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_sublabel_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .caldera-grid .help-block' => 'color: {{VALUE}};',
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
				'name'     => 'form_sublabel_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .caldera-grid .help-block',
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
					'{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="phone"],{{WRAPPER}} input[type="credit_card_cvc"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="phone"],{{WRAPPER}} input[type="credit_card_cvc"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select',
			]
		);

		$this->add_control(
			'form_input_bg_color',
			[
				'label'     => __( 'Background color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="phone"],{{WRAPPER}} input[type="credit_card_cvc"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select' => 'background-color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="phone"],{{WRAPPER}} input[type="credit_card_cvc"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select',
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
					'{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="date"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="phone"],{{WRAPPER}} input[type="credit_card_cvc"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} textarea' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} textarea' => 'background-color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} textarea, {{WRAPPER}} textarea:focus',
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
					'{{WRAPPER}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .btn',
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
					'{{WRAPPER}} .btn' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .btn' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .btn:hover' => 'border-color: {{VALUE}};',
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
				'selector'    => '{{WRAPPER}} .btn',
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
					'{{WRAPPER}} .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .btn',
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label'      => __( 'Padding', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_errors',
			[
				'label' => __( 'Form error labels', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_error_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#C40000',
				'selectors' => [
					'{{WRAPPER}} .caldera-grid  .has-error .help-block' => 'color: {{VALUE}};',
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
				'name'     => 'form_error_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .caldera-grid .has-error .help-block',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings     = $this->get_settings();
		$widget_title = $settings['widget_title'];
		$form_id      = $settings['select_form'];

		if ( empty( $form_id ) ) {
			return;
		}

		if ( $widget_title ) {
			echo '<h5 class="widget-title">' . esc_html( $widget_title ) . '</h5>';
		}

		echo \Caldera_Forms::render_form( $form_id );

	}

	protected function content_template() {}

}

	add_action(
		'elementor/widgets/register',
		function ( $widgets_manager ) {
			$widgets_manager->register( new Widget_EP_Caldera() );
		}
	);
