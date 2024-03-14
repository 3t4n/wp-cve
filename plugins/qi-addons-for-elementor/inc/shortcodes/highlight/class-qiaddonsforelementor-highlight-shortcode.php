<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_highlight_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_highlight_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Highlight_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_highlight_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Highlight_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/highlight' );
			$this->set_base( 'qi_addons_for_elementor_highlight' );
			$this->set_name( esc_html__( 'Highlighted Text', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays highlighted text with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/highlighted-text/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#highlighted_text' );
			$this->set_video( 'https://www.youtube.com/watch?v=oWff7vXBvTo' );
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
					'field_type'    => 'text',
					'name'          => 'highlight_position',
					'title'         => esc_html__( 'Highlight Text Position', 'qi-addons-for-elementor' ),
					'default_value' => '3-5,9-11',
					'dynamic'       => false,
					'description'   => esc_html__( 'If you would like to wrap from third to fifth and from seventh to ninth words, you would enter "3-5,7-9" or if you want to highlight whole text enter -1', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'select',
					'name'        => 'appear_animation',
					'title'       => esc_html__( 'Enable Appear Animation', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Will not affect gradient type', 'qi-addons-for-elementor' ),
					'options'     => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'       => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'appear_delay',
					'title'      => esc_html__( 'Appear Delay', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'appear_delay' ),
					'dependency' => array(
						'hide' => array(
							'appear_animation' => array(
								'values'        => 'none',
								'default_value' => 'none',
							),
						),
					),
					'group'      => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'appear_delay_in_ms',
					'title'         => esc_html__( 'Set Appear Delay', 'qi-addons-for-elementor' ) . ' (ms)',
					'dependency'    => array(
						'show' => array(
							'appear_delay' => array(
								'values'        => 'ms',
								'default_value' => 'default',
							),
						),
					),
					'default_value' => 100,
					'group'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-highlight' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'text_tag',
					'title'         => esc_html__( 'Text Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'p',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-highlight' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-highlight',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_highlight',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'highlight_color',
					'title'      => esc_html__( 'Highlight Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-highlight .qodef-highlight-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'highlight_background_style',
					'title'         => esc_html__( 'Highlight Text Background Style', 'qi-addons-for-elementor' ),
					'options'       => array(
						'color'    => esc_html__( 'Color', 'qi-addons-for-elementor' ),
						'gradient' => esc_html__( 'Gradient', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'color',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'color',
					'name'          => 'highlight_background_color',
					'title'         => esc_html__( 'Highlight Text Background Color', 'qi-addons-for-elementor' ),
					'default_value' => '#000',
					'dependency'    => array(
						'show' => array(
							'highlight_background_style' => array(
								'values'        => 'color',
								'default_value' => 'color',
							),
						),
					),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'gradient_colors',
					'title'         => esc_html__( 'Colors', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_color'       => '#2A8080',
							'color_percentage' => array(
								'size' => 0,
								'unit' => 'px',
							),
						),
						array(
							'item_color'       => '#ffffff',
							'color_percentage' => array(
								'size' => 85,
								'unit' => 'px',
							),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'color',
							'name'          => 'item_color',
							'title'         => esc_html__( 'Color', 'qi-addons-for-elementor' ),
							'default_value' => '#00ff00',
						),
						array(
							'field_type'    => 'slider',
							'name'          => 'color_percentage',
							'title'         => esc_html__( 'Percent', 'qi-addons-for-elementor' ),
							'range'         => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'default_value' => array(
								'size' => 100,
								'unit' => 'px',
							),
						),
					),
					'dependency'    => array(
						'show' => array(
							'highlight_background_style' => array(
								'values'        => 'gradient',
								'default_value' => 'color',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'slider',
					'name'          => 'gradient_degree',
					'title'         => esc_html__( 'Gradient Degree', 'qi-addons-for-elementor' ),
					'size_units'    => array( 'px' ),
					'range'         => array(
						'px' => array(
							'min' => - 180,
							'max' => 180,
						),
					),
					'default_value' => array(
						'size' => 90,
						'unit' => 'px',
					),
					'dependency'    => array(
						'show' => array(
							'highlight_background_style' => array(
								'values'        => 'gradient',
								'default_value' => 'color',
							),
						),
					),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'top_offset',
					'title'         => esc_html__( 'Highlight Top Offset', 'qi-addons-for-elementor' ),
					'default_value' => 0,
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'bottom_offset',
					'title'         => esc_html__( 'Highlight Bottom Offset', 'qi-addons-for-elementor' ),
					'default_value' => 0,
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'highlight_padding',
					'title'      => esc_html__( 'Highlight Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-highlight-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['text']           = $this->get_modified_text( $atts );
			$atts['data_attrs']     = $this->get_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/highlight', 'templates/highlight', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-highlight';
			$holder_classes[] = 'yes' === $atts['appear_animation'] ? 'qodef-qi--has-appear ' : '';
			$holder_classes[] = isset( $atts['highlight_background_style'] ) ? 'qodef-highlight-style--' . $atts['highlight_background_style'] : '';

			return implode( ' ', $holder_classes );
		}

		private function get_modified_text( $atts ) {
			$text = $atts['text'];

			if ( ! empty( $text ) && ! empty( $atts['highlight_position'] ) ) {

				$highlight_styles   = $this->get_highlight_styles( $atts );
				$highlight_position = $atts['highlight_position'];
				$text_prefix        = '<span class="qodef-highlight-text" ' . qi_addons_for_elementor_framework_get_inline_style( $highlight_styles ) . '>';
				$text_suffix        = '</span>';

				if ( '-1' === $highlight_position ) {
					$text = $text_prefix . $text . $text_suffix;
				} else {
					$split_text = explode( ' ', $text );
					$highlights = explode( ',', str_replace( ' ', '', $atts['highlight_position'] ) );

					$highlight_positions = array();
					foreach ( $highlights as $highlight ) {
						$highlight_positions[] = explode( '-', $highlight );
					}

					foreach ( $highlight_positions as $highlight_position ) {
						$positions = array_filter(
							array(
								'start' => isset( $highlight_position[0] ) ? $highlight_position[0] : '',
								'end'   => isset( $highlight_position[1] ) ? $highlight_position[1] : '',
							)
						);
						asort( $positions );

						if ( ! empty( $positions ) ) {

							foreach ( $positions as $key => $value ) {
								$text_prefix_html = 'start' === $key ? $text_prefix : '';
								$text_suffix_html = 'end' === $key ? $text_suffix : '';

								if ( isset( $split_text[ intval( $value ) - 1 ] ) && ! empty( $split_text[ intval( $value ) - 1 ] ) ) {
									$split_text[ $value - 1 ] = $text_prefix_html . $split_text[ $value - 1 ] . $text_suffix_html;
								}
							}

							$text = implode( ' ', $split_text );
						}
					}
				}
			}

			return $text;
		}

		private function get_highlight_styles( $atts ) {
			$styles = array();

			if ( ! isset( $atts['highlight_background_style'] ) || 'color' === $atts['highlight_background_style'] ) {
				if ( ! empty( $atts['highlight_background_color'] ) ) {
					$styles[] = 'background-image: linear-gradient(to bottom, transparent ' . ( 0 + (int) $atts['top_offset'] ) . '%, ' . $atts['highlight_background_color'] . ' ' . ( 0 + (int) $atts['top_offset'] ) . '%, ' . $atts['highlight_background_color'] . ' ' . ( 100 - (int) $atts['bottom_offset'] ) . '%, transparent ' . ( 100 - (int) $atts['bottom_offset'] ) . '%)';
				}
			} elseif ( 'gradient' === $atts['highlight_background_style'] ) {
				$gradient_colors = $this->parse_repeater_items( $atts['gradient_colors'] );

				if ( count( $gradient_colors ) ) {
					$style = 'background: linear-gradient(' . $atts['gradient_degree']['size'] . 'deg';

					foreach ( $gradient_colors as $color ) {
						$style .= ',' . $color['item_color'] . ' ' . $color['color_percentage']['size'] . '%';
					}

					$style .= ')';

					$styles[] = $style;
				}
			}

			return $styles;
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			if ( 'ms' === $atts['appear_delay'] && $atts['appear_delay_in_ms'] >= 0 ) {
				$data['data-appear-delay'] = $atts['appear_delay_in_ms'];
			} elseif ( 'random' === $atts['appear_delay'] ) {
				$data['data-appear-delay'] = $atts['appear_delay'];
			}

			return $data;
		}
	}
}
