<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_wp_forms_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_wp_forms_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Wp_Forms_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_wp_forms_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Wp_Forms_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_URL_PATH . '/wp-forms/shortcodes/wp-forms' );
			$this->set_base( 'qi_addons_for_elementor_wp_forms' );
			$this->set_name( esc_html__( 'WPForms', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays wp forms with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Form Style', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/wpforms/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#1_wpforms' );
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
					'title'      => esc_html__( 'Choose WPForms', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_wp_forms_forms(),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'label_typography',
					'title'      => esc_html__( 'Label Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms label, {{WRAPPER}} .qodef-qi-wp-forms .wpforms-required-label, {{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider-hint',
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'label_color',
					'title'      => esc_html__( 'Label Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms label'                             => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-required-label'           => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider-hint' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'wpf_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'wpf_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'input_typography',
					'title'      => esc_html__( 'Input Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]), {{WRAPPER}} .qodef-qi-wp-forms textarea, {{WRAPPER}} .qodef-qi-wp-forms select, {{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'input_color',
					'title'      => esc_html__( 'Input Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit])'                       => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea'                                       => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select'                                         => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit])::placeholder'          => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea::placeholder'                          => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit])'                       => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea'                                       => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select'                                         => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'input_border',
					'title'      => esc_html__( 'Input Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]),{{WRAPPER}} .qodef-qi-wp-forms textarea, {{WRAPPER}} .qodef-qi-wp-forms select, {{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit])'                                                      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms {{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea'                                                                      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select'                                                                        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'wpf_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'wpf_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'input_focus_typography',
					'title'      => esc_html__( 'Input Focus Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]):focus,{{WRAPPER}} .qodef-qi-wp-forms select:focus, {{WRAPPER}} .qodef-qi-wp-forms textarea:focus, {{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]:focus',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'input_focus_color',
					'title'      => esc_html__( 'Input Focus Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]):focus'                       => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select:focus'                                         => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]:focus' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea:focus'                                       => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]):focus::placeholder'          => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea:focus::placeholder'                          => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]):focus'                       => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select:focus'                                         => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]:focus' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea:focus'                                       => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'input_focus_border',
					'title'      => esc_html__( 'Input Focus Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]):focus,{{WRAPPER}} .qodef-qi-wp-forms select:focus,{{WRAPPER}} .qodef-qi-wp-forms textarea:focus,{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]:focus',
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'input_focus_border_radius',
					'title'      => esc_html__( 'Input Focus Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit]):focus'                       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select:focus'                                         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider input[type=range]:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea:focus'                                       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'wpf_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Input Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'wpf_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-wp-forms input[type=checkbox]' => 'font-size: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input[type=checkbox]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'checkbox_space',
					'title'      => esc_html__( 'Checkbox Item Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-checkbox ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-checkbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input[type=radio]' => 'font-size: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input[type=radio]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'radio_space',
					'title'      => esc_html__( 'Radio Item Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-radio ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-radio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'button_typography',
					'title'      => esc_html__( 'Button Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]',
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'wpf_button_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'wpf_button_tab_normal',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'wpf_button_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'wpf_button_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_hover_color',
					'title'      => esc_html__( 'Button Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]:hover' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_hover_background_color',
					'title'      => esc_html__( 'Button Hover Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]:hover' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'button_hover_border_color',
					'title'      => esc_html__( 'Button Hover Border Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]:hover' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'wpf_button_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Button Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'wpf_button_tabs_end',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]:hover' => 'border-style: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]:hover' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'label_space',
					'title'      => esc_html__( 'Label Space', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-label'              => 'margin-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-required-label'           => 'margin-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-field-number-slider-hint' => 'margin-top: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms input:not([type=submit])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms textarea'                 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-wp-forms select'                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'textarea_height',
					'title'      => esc_html__( 'Textarea Height', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'vh', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms textarea' => 'height: {{SIZE}}{{UNIT}};',
					),
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms button[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms' => 'text-align: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms label.wpforms-error' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'error_typography',
					'title'      => esc_html__( 'Error Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms label.wpforms-error',
					'group'      => esc_html__( 'Error Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'error_color',
					'title'      => esc_html__( 'Error Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms label.wpforms-error' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms label.wpforms-error' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'response_typography',
					'title'      => esc_html__( 'Response Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms .wpforms-confirmation-container-full',
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'response_color',
					'title'      => esc_html__( 'Response Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-confirmation-container-full' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-confirmation-container-full' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-wp-forms .wpforms-confirmation-container-full' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms .wpforms-confirmation-container-full',
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'response_background',
					'title'      => esc_html__( 'Response Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-wp-forms .wpforms-confirmation-container-full',
					'group'      => esc_html__( 'Response Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			return qi_addons_for_elementor_get_template_part( 'plugins/wp-forms/shortcodes/wp-forms', 'templates/wp-forms', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-wp-forms';
			$holder_classes[] = ( 'yes' === $atts['full_button'] ) ? 'qodef-button--full-width' : '';

			return implode( ' ', $holder_classes );
		}
	}
}
