<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_faq_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_faq_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_FAQ_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_faq_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_FAQ_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_faq_layouts', array() ) );


			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/faq' );
			$this->set_base( 'qi_addons_for_elementor_faq' );
			$this->set_name( esc_html__( 'FAQs', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds FAQs holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'SEO', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/faqs/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#faqs' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );

			$this->set_scripts(
				array(
					'jquery-ui-accordion' => array(
						'registered' => true,
					),
				)
			);

			$options_map = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => $options_map['default_value'],
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_schema',
					'title'         => esc_html__( 'Enable JSON Schema', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'default_value' => 'yes',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'behavior',
					'title'         => esc_html__( 'Behavior', 'qi-addons-for-elementor' ),
					'options'       => array(
						'none'      => esc_html__( 'None', 'qi-addons-for-elementor' ),
						'accordion' => esc_html__( 'Accordion', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'none',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'closed',
					'title'         => esc_html__( 'Initially Closed', 'qi-addons-for-elementor' ),
					'options'       => array(
						'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
						'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
					),
					'dependency'    => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'style',
					'title'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'options'       => array(
						'standard'       => esc_html__( 'Standard', 'qi-addons-for-elementor' ),
						'boxed'          => esc_html__( 'Boxed', 'qi-addons-for-elementor' ),
						'border-between' => esc_html__( 'Border Between', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'standard',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_numbered',
					'title'         => esc_html__( 'Numbered Items', 'qi-addons-for-elementor' ),
					'options'       => array(
						'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
						'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'no',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'icon_open',
					'title'         => esc_html__( 'Open Icon', 'qi-addons-for-elementor' ),
					'default_value' => array(
						'value'   => 'fas fa-plus',
						'library' => 'fa-solid',
					),
					'dependency'    => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'icon_close',
					'title'         => esc_html__( 'Close Icon', 'qi-addons-for-elementor' ),
					'default_value' => array(
						'value'   => 'fas fa-minus',
						'library' => 'fa-solid',
					),
					'dependency'    => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'number_typography',
					'title'      => esc_html__( 'Number Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-number',
					'dependency' => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'faq_number_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'faq_number_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_color',
					'title'      => esc_html__( 'Number Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_background_color',
					'title'      => esc_html__( 'Number Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'faq_number_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'faq_number_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_hover_color',
					'title'      => esc_html__( 'Number Active Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder.ui-state-active .qodef-e-number' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'relation' => 'and',
						'show'     => array(
							'behavior'        => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_background_hover_color',
					'title'      => esc_html__( 'Number Background Active Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder.ui-state-active .qodef-e-number' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'relation' => 'and',
						'show'     => array(
							'behavior'        => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'faq_number_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'faq_number_style_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'faq_number_divider',
					'title'      => esc_html__( 'Number Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'number_width',
					'title'      => esc_html__( 'Number Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'width: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'number_height',
					'title'      => esc_html__( 'Number Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'height: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'number_margin',
					'title'              => esc_html__( 'Number Margin', 'qi-addons-for-elementor' ),
					'allowed_dimensions' => array( 'right', 'left' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-e-number' => 'margin-right: {{RIGHT}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}};',
					),
					'dependency'         => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'              => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'number_border_radius',
					'title'      => esc_html__( 'Number Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_numbered' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Number Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h3',
					'group'         => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-title-holder',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'faq_title_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'faq_title_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-faq .qodef-e-title-holder' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_background_color',
					'title'      => esc_html__( 'Title Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-style--standard .qodef-e-title-holder' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'style' => array(
								'values'        => array( 'standard' ),
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_padding',
					'title'      => esc_html__( 'Title Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'faq_title_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'faq_title_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Active Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder.ui-state-active' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_background_hover_color',
					'title'      => esc_html__( 'Title Background Active Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-style--standard .qodef-e-title-holder.ui-state-active' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'relation' => 'and',
						'show'     => array(
							'style'    => array(
								'values'        => 'standard',
								'default_value' => 'standard',
							),
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_active_padding',
					'title'      => esc_html__( 'Title Active Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder.ui-state-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'faq_title_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'faq_title_style_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_color',
					'title'      => esc_html__( 'Content Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-content' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_background_color',
					'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_active_background_color',
					'title'      => esc_html__( 'Content Active Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder.ui-state-active + .qodef-e-content' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'content_padding',
					'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'border_color',
					'title'      => esc_html__( 'Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-style--standard .qodef-e-content' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-style--standard .qodef-e-title-holder' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-style--boxed' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-style--boxed .qodef-e-title-holder:not(:first-child)' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-style--border-between .qodef-e-title-holder' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'items_space',
					'title'      => esc_html__( 'Items Space', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder:not(:first-child)' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'style' => array(
								'values'        => array( 'standard' ),
								'default_value' => 'standard',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_content_icon',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder .qodef-e-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'accordion',
								'default_value' => 'none',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'accordion_icon_style_tabs',
					'title'      => esc_html__( 'Icon Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'accordion_icon_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'accordion_icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-mark' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'accordion_icon_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'accordion_icon_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'accordion_icon_hover_color',
					'title'      => esc_html__( 'Icon Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder:hover .qodef-e-mark' => 'color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'accordion_icon_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'accordion_icon_style_tabs_end',
					'title'      => esc_html__( 'Icon End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title'   => esc_html__( 'Example Title 1', 'qi-addons-for-elementor' ),
							'item_content' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
						),
						array(
							'item_title'   => esc_html__( 'Example Title 2', 'qi-addons-for-elementor' ),
							'item_content' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'html',
							'name'          => 'item_content',
							'title'         => esc_html__( 'Content', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
						),
					),
				)
			);
		}

		public function load_assets() {
			wp_enqueue_script( 'jquery-ui-accordion' );
			qi_addons_for_elementor_icon_load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts                   = $this->get_atts();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			$atts['schema'] = $this->get_faq_schema( $atts['items'], $atts['enable_schema'] );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/faq', 'variations/' . $atts['layout'] . '/templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-faq';
			$holder_classes[] = 'qodef-qi-clear';
			$holder_classes[] = ( 'yes' === $atts['closed'] ) ? 'qodef-closed' : '';
			$holder_classes[] = ! empty( $atts['behavior'] ) ? 'qodef-behavior--' . $atts['behavior'] : '';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['style'] ) ? 'qodef-style--' . $atts['style'] : '';

			return implode( ' ', $holder_classes );
		}

		private function get_faq_schema( $items, $enable_schema ) {
			if ( 'yes' === $enable_schema ) {
				$schema = array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => array(),
				);

				if ( ! empty( $items ) ) {
					foreach ( $items as $item ) {
						if ( ! empty( $item['item_title'] ) ) {
							$answer = array(
								'@type' => 'Answer',
								'text'  => qi_addons_for_elementor_framework_wp_kses_html( 'content', $item['item_content'] ),
							);

							$schema['mainEntity'][] = array(
								'@type'          => 'Question',
								'name'           => esc_html( $item['item_title'] ),
								'acceptedAnswer' => $answer,
							);
						}
					}
				}
				return $schema;
			} else {
				return false;
			}
		}
	}
}
