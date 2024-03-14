<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_animated_text_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_animated_text_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Animated_Text_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_animated_text_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Animated_Text_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/animated-text' );
			$this->set_base( 'qi_addons_for_elementor_animated_text' );
			$this->set_name( esc_html__( 'Animated Text', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds animated text element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/animated-text/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#animated_text' );
			$this->set_video( 'https://www.youtube.com/watch?v=qC_J-2ppdf0' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
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
					'field_type' => 'choose',
					'name'       => 'alignment',
					'title'      => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-animated-text' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h2',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-animated-text .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-animated-text .qodef-m-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-m-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'split_title',
					'title'      => esc_html__( 'Split Title', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'None', 'qi-addons-for-elementor' ),
						'word'   => esc_html__( 'By Word', 'qi-addons-for-elementor' ),
						'letter' => esc_html__( 'By Letter', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'appear_animation',
					'title'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'appear_animation', false ),
					'default_value' => 'from-bottom',
					'group'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
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
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['title']          = $this->get_modified_title( $atts );
			$atts['data_attrs']     = $this->get_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/animated-text', 'templates/animated-text', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-animated-text';
			$holder_classes[] = ! empty( $atts['split_title'] ) ? 'qodef--animated-by-' . $atts['split_title'] : '';
			$holder_classes[] = ! empty( $atts['alignment'] ) ? 'qodef--alignment-' . $atts['alignment'] : '';

			$holder_classes[] = ( 'none' !== $atts['appear_animation'] ) ? 'qodef-qi--has-appear qodef--appear-' . $atts['appear_animation'] : '';

			return implode( ' ', $holder_classes );
		}

		function str_split_unicode( $str ) {
			mb_internal_encoding( 'UTF-8' );
			$str = html_entity_decode( $str, ENT_QUOTES, 'UTF-8' );
			$len = mb_strlen( $str );

			for ( $i = 0; $i < $len; $i ++ ) {
				$result[] = mb_substr( $str, $i, 1, 'UTF-8' );
			}

			return $result;
		}

		private function get_modified_title( $atts ) {
			$title = $atts['title'];

			if ( ! empty( $title ) && $atts['split_title'] ) {
				if ( 'word' === $atts['split_title'] ) {
					$split_text = explode( ' ', $title );

					foreach ( $split_text as $key => $value ) {
						$split_text[ $key ] = '<span class="qodef-e-word">' . $value . '</span>';
					}
				} elseif ( 'letter' === $atts['split_title'] ) {
					$split_text = explode( ' ', $title );
					foreach ( $split_text as $key => $value ) {
						$split_text[ $key ] = '<span class="qodef-e-word-holder">' . $value . '</span>';
					}
				}

				return implode( ' ', $split_text );
			}

			return $title;
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
