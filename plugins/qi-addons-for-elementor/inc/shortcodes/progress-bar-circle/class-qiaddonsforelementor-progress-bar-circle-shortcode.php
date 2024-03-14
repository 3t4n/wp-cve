<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_progress_bar_circle_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_progress_bar_circle_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Progress_Bar_Circle_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_progress_bar_circle_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Progress_Bar_Circle_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/progress-bar-circle' );
			$this->set_base( 'qi_addons_for_elementor_progress_bar_circle' );
			$this->set_name( esc_html__( 'Radial Progress Bar', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays radial progress bar with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Infographics', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/radial-progress-bar/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#radial_progress_bar' );
			$this->set_video( 'https://www.youtube.com/watch?v=wmuYN2VIVRw' );
			$this->set_scripts(
				array(
					'progress-bar' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/shortcodes/progress-bar-circle/assets/js/plugins/progressbar.min.js',
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
					'field_type'    => 'number',
					'name'          => 'number',
					'title'         => esc_html__( 'Percentage Number', 'qi-addons-for-elementor' ),
					'default_value' => 75,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'duration',
					'title'      => esc_html__( 'Animation Duration (ms)', 'qi-addons-for-elementor' ),
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
					'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'active_line_color',
					'title'      => esc_html__( 'Active Line Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Circle Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'inactive_line_color',
					'title'      => esc_html__( 'Inactive Line Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Circle Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'active_line_width',
					'title'      => esc_html__( 'Active Line Thickness', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Circle Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'inactive_line_width',
					'title'      => esc_html__( 'Inactive Line Thickness', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Circle Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'circle_fill_color',
					'title'      => esc_html__( 'Circle Fill Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-canvas svg path:first-child' => 'fill-opacity: 1; fill: {{VALUE}};',
					),
					'group'      => esc_html__( 'Circle Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'circle_size',
					'title'      => esc_html__( 'Circle Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Circle Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 500,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-canvas' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-title',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_top',
					'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-text',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_top',
					'title'      => esc_html__( 'Text Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_color',
					'title'      => esc_html__( 'Number Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-value' => 'color: {{VALUE}} !important;',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'number_typography',
					'title'      => esc_html__( 'Number Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-progress-bar-circle .qodef-m-value',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function load_assets() {
			wp_enqueue_script( 'progress-bar' );
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_progress_bar_circle', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['data_attrs']     = $this->get_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/progress-bar-circle', 'templates/progress-bar-circle', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-progress-bar-circle';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			$data['data-active-line-color'] = ! empty( $atts['active_line_color'] ) ? $atts['active_line_color'] : '#1e1e1e';
			$data['data-active-line-width'] = ! empty( $atts['active_line_width'] ) ? floatval( $atts['active_line_width'] ) : 4;

			$data['data-inactive-line-color'] = ! empty( $atts['inactive_line_color'] ) ? $atts['inactive_line_color'] : '#ececec';
			$data['data-inactive-line-width'] = ! empty( $atts['inactive_line_width'] ) ? floatval( $atts['inactive_line_width'] ) : 4;

			$data['data-duration'] = ! empty( $atts['duration'] ) ? intval( $atts['duration'] ) : '';
			$data['data-number']   = ! empty( $atts['number'] ) ? $atts['number'] : '0.00';

			$data['data-text-color'] = ! empty( $atts['number_color'] ) ? $atts['number_color'] : '#1e1e1e';

			return $data;
		}
	}
}
