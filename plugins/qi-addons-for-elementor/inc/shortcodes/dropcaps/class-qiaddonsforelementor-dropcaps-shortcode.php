<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_dropcaps_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_dropcaps_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Dropcaps_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_dropcaps_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Dropcaps_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/dropcaps' );
			$this->set_base( 'qi_addons_for_elementor_dropcaps' );
			$this->set_name( esc_html__( 'Drop Caps', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays drop caps with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/drop-caps/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#drop_caps' );
			$this->set_video( 'https://www.youtube.com/watch?v=FGdvTbprRIE' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'textarea',
					'name'          => 'text',
					'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'letter_typography',
					'title'      => esc_html__( 'Letter Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'letter_color',
					'title'      => esc_html__( 'Letter Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'letter_size',
					'title'      => esc_html__( 'Letter Holder Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'letter_background',
					'title'      => esc_html__( 'Letter Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'letter_border',
					'title'      => esc_html__( 'Letter Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'letter_radius',
					'title'      => esc_html__( 'Letter Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_letter_stroke',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'letter_stroke_effect',
					'title'      => esc_html__( 'Letter Stroke Effect', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'letter_stroke_color',
					'title'      => esc_html__( 'Letter Stroke Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => '-webkit-text-stroke-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'letter_stroke_effect' => array(
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
					'name'       => 'letter_stroke_width',
					'title'      => esc_html__( 'Letter Stroke Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => '-webkit-text-stroke-width: {{SIZE}}px;',
					),
					'dependency' => array(
						'show' => array(
							'letter_stroke_effect' => array(
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
					'name'       => 'item_divider_style_letter_clip',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'letter_clip_effect',
					'title'      => esc_html__( 'Letter Clip Effect', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'letter_clip_background',
					'title'      => esc_html__( 'Letter Clip Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-dropcaps.qodef-letter-clip-effect .qodef-m-letter',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'letter_clip_effect' => array(
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
					'name'       => 'item_divider_style_letter',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'letter_margin',
					'title'      => esc_html__( 'Letter Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'letter_padding',
					'title'      => esc_html__( 'Letter Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-dropcaps .qodef-m-letter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/dropcaps', 'templates/dropcaps', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-dropcaps';
			$holder_classes[] = ( 'yes' === $atts['letter_stroke_effect'] ) ? 'qodef-letter-stroke-effect' : '';
			$holder_classes[] = ( 'yes' === $atts['letter_clip_effect'] ) ? 'qodef-letter-clip-effect' : '';

			return implode( ' ', $holder_classes );
		}
	}
}
