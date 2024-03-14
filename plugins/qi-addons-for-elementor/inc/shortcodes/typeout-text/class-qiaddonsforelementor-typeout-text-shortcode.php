<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_typeout_text_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_typeout_text_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Typeout_Text_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_typeout_text_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Typeout_Text_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/typeout-text' );
			$this->set_base( 'qi_addons_for_elementor_typeout_text' );
			$this->set_name( esc_html__( 'Typeout Text', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds typeout text element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/typeout-text/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#typeout_text' );
			$this->set_scripts(
				array(
					'typed' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/shortcodes/typeout-text/assets/js/plugins/typed.js',
						'dependency' => array( 'jquery' ),
					),
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
					'field_type'    => 'text',
					'name'          => 'text',
					'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'cursor',
					'title'         => esc_html__( 'Cursor Character', 'qi-addons-for-elementor' ),
					'dynamic'       => false,
					'default_value' => '|',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Typeout Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'typeout' => esc_html__( 'Example', 'qi-addons-for-elementor' ),
						),
						array(
							'typeout' => esc_html__( 'Typing', 'qi-addons-for-elementor' ),
						),
						array(
							'typeout' => esc_html__( 'Text', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'typeout',
							'title'         => esc_html__( 'Typeout Text', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Again', 'qi-addons-for-elementor' ),
						),
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
						'{{WRAPPER}} .qodef-qi-typeout-text' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_alignment',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'text_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h2',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-typeout-text .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-typeout-text .qodef-m-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'typeout_color',
					'title'      => esc_html__( 'Typeout Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-typeout-text .qodef-typeout-holder' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['data_attrs']     = $this->get_data_attrs( $atts['items'], $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/typeout-text', 'templates/typeout-text', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-typeout-text';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $items, $atts ) {
			$data = array();
			$temp = array();

			foreach ( $items as $key => $value ) {
				$temp['data-strings'][] = ! empty( $value['typeout'] ) ? $value['typeout'] : '';
			}

			foreach ( $temp as $key => $value ) {
				$data[ $key ] = json_encode( $value );
			}

			if ( ! empty( $atts['cursor'] ) ) {
				$data['data-cursor'] = $atts['cursor'];
			}

			return $data;
		}
	}
}
