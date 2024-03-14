<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_counter_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_counter_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Counter_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_counter_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Counter_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_counter_layouts', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/counter' );
			$this->set_base( 'qi_addons_for_elementor_counter' );
			$this->set_name( esc_html__( 'Counters', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays counters with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Infographics', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/counters/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#counters' );
			$this->set_video( 'https://www.youtube.com/watch?v=JzyY-mtl7Tk' );

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
					'field_type' => 'divider',
					'name'       => 'item_divider_class_digit',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'start_digit',
					'title'         => esc_html__( 'Start Digit', 'qi-addons-for-elementor' ),
					'default_value' => 0,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'end_digit',
					'title'         => esc_html__( 'End Digit', 'qi-addons-for-elementor' ),
					'default_value' => 23,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'step_digit',
					'title'      => esc_html__( 'Step Between Digits', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'step_delay',
					'title'      => esc_html__( 'Step Delay', 'qi-addons-for-elementor' ),
					'dynamic'    => false,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'digit_label',
					'title'      => esc_html__( 'Digit Label', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_digit_enable',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'enable_icon',
					'title'      => esc_html__( 'Enable Icon', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'enable_separator',
					'title'      => esc_html__( 'Enable Separator', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_enable_content',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'title',
					'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'title' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'textarea',
					'name'          => 'text',
					'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text(),
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
						'{{WRAPPER}} .qodef-qi-counter' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_alignment_digit',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'digit_typography',
					'title'      => esc_html__( 'Digit Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-counter .qodef-m-digit',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'digit_color',
					'title'      => esc_html__( 'Digit Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-digit' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_digit',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'digit_background_color',
					'title'      => esc_html__( 'Digit Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-digit-wrapper' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'digit_background_size',
					'title'      => esc_html__( 'Digit Background Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-digit-wrapper' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'digit_background_radius',
					'title'      => esc_html__( 'Digit Background Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-digit-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_digit_effect',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'digit_stroke_effect',
					'title'      => esc_html__( 'Digit Stroke Effect', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'digit_stroke_color',
					'title'      => esc_html__( 'Digit Stroke Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-digit' => '-webkit-text-stroke-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'digit_stroke_effect' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'digit_stroke_width',
					'title'      => esc_html__( 'Digit Stroke Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-digit' => '-webkit-text-stroke-width: {{SIZE}}px;',
					),
					'dependency' => array(
						'show' => array(
							'digit_stroke_effect' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_digit_title',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-counter .qodef-m-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_title_text',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-counter .qodef-m-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_top',
					'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_top',
					'title'      => esc_html__( 'Text Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'text_padding',
					'title'      => esc_html__( 'Text Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'icons',
					'name'       => 'digit_icon',
					'title'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_separator',
					'exclude'           => array( 'custom_class' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Separator', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-counter .qodef-m-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_counter', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts                     = $this->get_atts();
			$atts['data_attrs']       = $this->get_data_attrs( $atts );
			$atts['holder_classes']   = $this->get_holder_classes( $atts );
			$atts['separator_params'] = $this->generate_separator_params( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/counter', 'variations/' . $atts['layout'] . '/templates/counter', '', $atts );
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			if ( ! empty( $atts['start_digit'] ) ) {
				$data['data-start-digit'] = $atts['start_digit'];
			}

			if ( ! empty( $atts['end_digit'] ) ) {
				$data['data-end-digit'] = $atts['end_digit'];
			}

			if ( ! empty( $atts['step_digit'] ) ) {
				$data['data-step-digit'] = $atts['step_digit'];
			}

			if ( ! empty( $atts['step_delay'] ) ) {
				$data['data-step-delay'] = $atts['step_delay'];
			}

			if ( ! empty( $atts['digit_label'] ) ) {
				$data['data-digit-label'] = $atts['digit_label'];
			}

			return $data;
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-counter';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['digit_background_size']['size'] ) ? 'qodef-digit-background' : '';
			$holder_classes[] = ( 'yes' === $atts['digit_stroke_effect'] ) ? 'qodef-digit-stroke-effect' : '';

			return implode( ' ', $holder_classes );
		}

		private function generate_separator_params( $atts ) {
			$params = array();

			if ( 'yes' === $atts['enable_separator'] ) {
				$params = $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_separator',
						'exclude'        => array( 'custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return $params;
		}
	}
}
