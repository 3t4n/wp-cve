<?php
	namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_WPForms extends Widget_Base {

	public function get_name() {
		return 'ep-wpforms-plus';
	}

	public function get_title() {
		return __( 'WPForms Plus!', 'elements-plus' );
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
				'label' => __( 'WPForms Plus!', 'elements-plus' ),
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
				'label'   => __( 'Select WPForm', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => wp_list_pluck( wpforms()->form->get(), 'post_title', 'ID' ),
			]
		);

		$this->add_control(
			'display_name',
			[
				'label'     => __( 'Display form name', 'elements-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_on'  => __( 'Yes', 'elements-plus' ),
				'label_off' => __( 'No', 'elements-plus' ),
			]
		);

		$this->add_control(
			'display_description',
			[
				'label'     => __( 'Display form description', 'elements-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_on'  => __( 'Yes', 'elements-plus' ),
				'label_off' => __( 'No', 'elements-plus' ),
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
			'section_form_title',
			[
				'label' => __( 'Form title', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_title_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpforms-title' => 'color: {{VALUE}};',
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
				'name'     => 'form_title_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpforms-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_description',
			[
				'label' => __( 'Form description', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_description_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpforms-description' => 'color: {{VALUE}};',
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
				'name'     => 'form_description_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpforms-description',
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
					'{{WRAPPER}} .wpforms-field-label' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .wpforms-field-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_sublabel',
			[
				'label' => __( 'Form sublabels', 'elements-plus' ),
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
					'{{WRAPPER}} .wpforms-field-sublabel' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .wpforms-field-sublabel',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_inlinelabel',
			[
				'label' => __( 'Form inline labels', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_inlinelabel_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpforms-field-label-inline' => 'color: {{VALUE}};',
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
				'name'     => 'form_inlinelabel_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpforms-field-label-inline',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_field_desc',
			[
				'label' => __( 'Form field descriptions', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_field_desc_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpforms-field-description' => 'color: {{VALUE}};',
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
				'name'     => 'form_field_desc_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpforms-field-description',
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
					'{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select',
			]
		);

		$this->add_control(
			'form_input_validation',
			[
				'label'     => __( 'Input validation error color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wpforms-form label.wpforms-error' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpforms-form input.wpforms-error' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpforms-form textarea.wpforms-error' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select' => 'background-color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select',
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
					'{{WRAPPER}} input[type="text"],{{WRAPPER}} input[type="email"],{{WRAPPER}} input[type="number"],{{WRAPPER}} input[type="tel"],{{WRAPPER}} input[type="url"],{{WRAPPER}} input[type="password"],{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .wpforms-submit',
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
					'{{WRAPPER}} .wpforms-submit' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpforms-submit' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .wpforms-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpforms-submit:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .wpforms-submit:hover' => 'border-color: {{VALUE}};',
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
				'selector'    => '{{WRAPPER}} .wpforms-submit',
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
					'{{WRAPPER}} .wpforms-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpforms-submit',
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label'      => __( 'Padding', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpforms-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();

		if ( wpforms()->pro ) {
			$this->start_controls_section(
				'section_star_rating',
				[
					'label' => __( 'Star rating', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'star_rating_color',
				[
					'label'     => __( 'Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .wpforms-field-rating svg' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();
		}

		if ( function_exists( 'wpforms_surveys_polls' ) ) {
			$this->start_controls_section(
				'section_table',
				[
					'label' => __( 'Survey tables', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'table_head_color',
				[
					'label'     => __( 'Table head color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .wpforms-field-likert_scale table thead th' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'table_head_typography',
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .wpforms-field-likert_scale table thead th',
				]
			);

			$this->add_control(
				'table_row_heading',
				[
					'label'     => __( 'Row heading color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .wpforms-field-likert_scale table tbody th' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'table_row_heading_typography',
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .wpforms-field-likert_scale table tbody th',
				]
			);

			$this->add_control(
				'table_selection',
				[
					'label'     => __( 'Selection background color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} form.wpforms-form .wpforms-field-likert_scale table.modern tbody tr td input[type="radio"]:checked + label::after, {{WRAPPER}} form.wpforms-form .wpforms-field-likert_scale table.modern tbody tr td input[type="checkbox"]:checked + label::after, {{WRAPPER}} form.wpforms-form .wpforms-field-net_promoter_score table.modern tbody tr td input[type="radio"]:checked + label' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();
		}

	}

	protected function render() {
		$settings     = $this->get_settings();
		$widget_title = $settings['widget_title'];
		$form_id      = $settings['select_form'];
		$show_title   = $settings['display_name'];
		$show_desc    = $settings['display_description'];

		if ( empty( $form_id ) ) {
			return;
		}

		if ( $widget_title ) {
			echo '<h5 class="widget-title">' . esc_html( $widget_title ) . '</h5>';
		}

		wpforms()->frontend->output( absint( $form_id ), $show_title, $show_desc );

	}

	protected function content_template() {}

}

	add_action(
		'elementor/widgets/register',
		function ( $widgets_manager ) {
			$widgets_manager->register( new Widget_WPForms() );
		}
	);
