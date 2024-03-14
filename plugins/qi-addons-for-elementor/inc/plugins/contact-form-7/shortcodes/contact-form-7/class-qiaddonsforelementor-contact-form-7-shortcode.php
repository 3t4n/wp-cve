<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_contact_form_7_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_contact_form_7_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Contact_Form_7_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_contact_form_7_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Contact_Form_7_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_URL_PATH . '/contact-form-7/shortcodes/contact-form-7' );
			$this->set_base( 'qi_addons_for_elementor_contact_form_7' );
			$this->set_name( esc_html__( 'Contact Form 7', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays contact form 7 with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Form Style', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/contact-form-7/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#7_contact_form_7' );
			$this->set_video( 'https://www.youtube.com/watch?v=mjWBuQeXfr8' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'contact_form_id',
					'title'      => esc_html__( 'Choose Contact Form 7', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_contact_form_7_forms(),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'label_typography',
					'title'      => esc_html__( 'Label Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 label',
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'label_color',
					'title'      => esc_html__( 'Label Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 label' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'cf7_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'cf7_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'input_typography',
					'title'      => esc_html__( 'Input Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]), {{WRAPPER}} .qodef-qi-contact-form-7 textarea, {{WRAPPER}} .qodef-qi-contact-form-7 select',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'input_color',
					'title'      => esc_html__( 'Input Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit])'              => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea'                              => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 select'                                => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit])::placeholder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea::placeholder'                 => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'input_background_color',
					'title'      => esc_html__( 'Input Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit])' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea'                 => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 select'                   => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'input_border',
					'title'      => esc_html__( 'Input Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]),{{WRAPPER}} .qodef-qi-contact-form-7 textarea, {{WRAPPER}} .qodef-qi-contact-form-7 select',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'cf7_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'cf7_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'input_focus_typography',
					'title'      => esc_html__( 'Input Focus Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]):focus, {{WRAPPER}} .qodef-qi-contact-form-7 textarea:focus, {{WRAPPER}} .qodef-qi-contact-form-7 select:focus',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'input_focus_color',
					'title'      => esc_html__( 'Input Focus Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]):focus'              => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea:focus'                              => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 select:focus'                                => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]):focus::placeholder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea:focus::placeholder'                 => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'input_focus_background_color',
					'title'      => esc_html__( 'Input Focus Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]):focus' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea:focus'                 => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 select:focus'                   => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'input_focus_border',
					'title'      => esc_html__( 'Input Focus Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit]):focus,{{WRAPPER}} .qodef-qi-contact-form-7 textarea:focus, {{WRAPPER}} .qodef-qi-contact-form-7 select:focus',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'cf7_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'cf7_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'divider_border_radius',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'input_border_radius',
					'title'      => esc_html__( 'Input Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea'                 => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 select'                   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'checkbox_size',
					'title'      => esc_html__( 'Checkbox Input Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=checkbox]' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Checkbox Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'checkbox_margin',
					'title'      => esc_html__( 'Checkbox Input Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Checkbox Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=checkbox]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'checkbox_space',
					'title'      => esc_html__( 'Checkbox Space Between', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-checkbox .wpcf7-list-item' => 'margin-left: 0;',
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-checkbox .wpcf7-list-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Checkbox Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'checkbox_holder_margin',
					'title'      => esc_html__( 'Checkbox Holder Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Checkbox Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-checkbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'radio_size',
					'title'      => esc_html__( 'Radio Input Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=radio]' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Radio Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'radio_margin',
					'title'      => esc_html__( 'Radio Input Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Radio Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=radio]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'radio_space',
					'title'      => esc_html__( 'Radio Space Between', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-radio .wpcf7-list-item:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Radio Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'radio_holder_margin',
					'title'      => esc_html__( 'Radio Holder Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Radio Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-radio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'button_typography',
					'title'      => esc_html__( 'Button Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]',
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'cf7_button_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'cf7_button_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_color',
					'title'      => esc_html__( 'Button Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_background_color',
					'title'      => esc_html__( 'Button Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_border_color',
					'title'      => esc_html__( 'Button Border Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'cf7_button_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'cf7_button_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_hover_color',
					'title'      => esc_html__( 'Button Hover/Focus Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]:focus' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_hover_background_color',
					'title'      => esc_html__( 'Button Hover/Focus Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]:hover' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]:focus' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_hover_border_color',
					'title'      => esc_html__( 'Button Hover Border/Focus Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]:hover' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]:focus' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'cf7_button_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'cf7_button_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'divider_hover',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'button_border_style',
					'title'      => esc_html__( 'Button Border Style', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'border_style' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'border-style: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'button_border_width',
					'title'      => esc_html__( 'Button Border Width', 'qi-addons-for-elementor' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'button_border_radius',
					'title'      => esc_html__( 'Button Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'full_button',
					'title'      => esc_html__( 'Button Full Width', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'form_item_space',
					'title'              => esc_html__( 'Form Item Space', 'qi-addons-for-elementor' ),
					'group'              => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'allowed_dimensions' => array( 'top', 'bottom' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-form-control-wrap' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'input_padding',
					'title'      => esc_html__( 'Input Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input:not([type=submit])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 textarea'                 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 select'                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'button_margin',
					'title'      => esc_html__( 'Button Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'button_padding',
					'title'      => esc_html__( 'Button Padding', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'choose',
					'name'       => 'alignment',
					'title'      => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Global Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'choose',
					'name'       => 'error_alignment',
					'title'      => esc_html__( 'Error Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-not-valid-tip' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'error_typography',
					'title'      => esc_html__( 'Error Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-not-valid-tip',
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'error_color',
					'title'      => esc_html__( 'Error Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_error_typography',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'error_margin',
					'title'      => esc_html__( 'Error Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-not-valid-tip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'response_typography',
					'title'      => esc_html__( 'Response Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-response-output',
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'response_color',
					'title'      => esc_html__( 'Response Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-response-output' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_response_typography',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'response_padding',
					'title'      => esc_html__( 'Response Padding', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'response_margin',
					'title'      => esc_html__( 'Response Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-response-output' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_response_spacing',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'response_border',
					'title'      => esc_html__( 'Response Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-response-output',
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'response_background',
					'title'      => esc_html__( 'Response Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-contact-form-7 .wpcf7-response-output',
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_response_types',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'sent_color',
					'title'      => esc_html__( 'Sent Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 form.sent .wpcf7-response-output' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'failed_color',
					'title'      => esc_html__( 'Failed Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 form.failed .wpcf7-response-output'  => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 form.aborted .wpcf7-response-output' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'spam_color',
					'title'      => esc_html__( 'Spam Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 form.spam .wpcf7-response-output' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'invalid_color',
					'title'      => esc_html__( 'Invalid Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-contact-form-7 form.invalid .wpcf7-response-output'    => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-contact-form-7 form.unaccepted .wpcf7-response-output' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$form = WPCF7_ContactForm::get_instance( $atts['contact_form_id'] );

			if ( ! isset( $form ) || false === $form ) {
				return esc_html__( 'Please enter valid form ID', 'qi-addons-for-elementor' );
			}

			$id = $this->generate_unit_tag( $form->id() );

			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			add_filter(
				'qi_addons_for_elementor_cf7_unit_tag',
				function ( $unit_tag ) use ( $id ) {
					return $id;
				}
			);

			return qi_addons_for_elementor_get_template_part( 'plugins/contact-form-7/shortcodes/contact-form-7', 'templates/contact-form-7', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-contact-form-7';
			$holder_classes[] = ( 'yes' === $atts['full_button'] ) ? 'qodef-button--full-width' : '';

			return implode( ' ', $holder_classes );
		}

		private static function generate_unit_tag( $id = 0 ) {
			static $global_count = 0;

			$global_count += 1;

			if ( in_the_loop() ) {
				$unit_tag = sprintf(
					'wpcf7-f%1$d-p%2$d-o%3$d',
					absint( $id ),
					get_the_ID(),
					$global_count
				);
			} else {
				$unit_tag = sprintf(
					'wpcf7-f%1$d-o%2$d',
					absint( $id ),
					$global_count
				);
			}

			return $unit_tag;
		}
	}
}
