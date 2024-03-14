<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_progress_bar_vertical_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_progress_bar_vertical_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Progress_Bar_Vertical_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_progress_bar_vertical_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Progress_Bar_Vertical_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/progress-bar-vertical' );
			$this->set_base( 'qi_addons_for_elementor_progress_bar_vertical' );
			$this->set_name( esc_html__( 'Vertical Progress Bar', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays vertical progress bar with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Infographics', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/vertical-progress-bar/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#vertical_progress_bar' );
			$this->set_video( 'https://www.youtube.com/watch?v=RmeMJXkq2f0' );
			$this->set_scripts(
				array(
					'progress-bar' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/shortcodes/progress-bar-vertical/assets/js/plugins/progressbar.min.js',
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
					'field_type' => 'select',
					'name'       => 'percentage_type',
					'title'      => esc_html__( 'Percentage Type', 'qi-addons-for-elementor' ),
					'options'    => array(
						'fixed-bottom' => esc_html__( 'Fixed Bottom', 'qi-addons-for-elementor' ),
						'fixed-right'  => esc_html__( 'Fixed Right', 'qi-addons-for-elementor' ),
						'fixed-on'     => esc_html__( 'Fixed On', 'qi-addons-for-elementor' ),
						'floating-top' => esc_html__( 'Floating Top', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_gradient_fill',
					'title'         => esc_html__( 'Enable Gradient Fill', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'gradient_color_start',
					'title'      => esc_html__( 'Gradient Color Start', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'enable_gradient_fill' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'gradient_color_end',
					'title'      => esc_html__( 'Gradient Color End', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'enable_gradient_fill' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_pattern_fill',
					'title'         => esc_html__( 'Enable Pattern Fill', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'pattern_image',
					'title'      => esc_html__( 'Pattern Image', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'enable_pattern_fill' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
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
					'field_type' => 'number',
					'name'       => 'progress_bar_height',
					'title'      => esc_html__( 'Progress Bar Height', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'bar_border',
					'title'      => esc_html__( 'Bar Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-canvas svg',
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_bar_color',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'active_line_color',
					'title'      => esc_html__( 'Active Line Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
					'alpha'      => false,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'inactive_line_color',
					'title'      => esc_html__( 'Inactive Line Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
					'alpha'      => false,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_color_opacity',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'active_line_opacity',
					'title'      => esc_html__( 'Active Line Opacity', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1,
							'step' => 0.05,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-canvas svg path:nth-child(2)' => 'stroke-opacity: {{SIZE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'inactive_line_opacity',
					'title'      => esc_html__( 'Inactive Line Opacity', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1,
							'step' => 0.05,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-canvas svg path:nth-child(1)' => 'stroke-opacity: {{SIZE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_opacity_width',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'active_line_width',
					'title'      => esc_html__( 'Active Line Thickness', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'inactive_line_width',
					'title'      => esc_html__( 'Inactive Line Thickness', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Bar Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-title',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_margin',
					'title'      => esc_html__( 'Title Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_title_number',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_color',
					'title'      => esc_html__( 'Number Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-value' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'number_typography',
					'title'      => esc_html__( 'Number Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-value',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'number_margin',
					'title'      => esc_html__( 'Number Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-progress-bar-vertical .qodef-m-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function load_assets() {
			wp_enqueue_script( 'progress-bar' );
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_progress_bar_vertical', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['data_attrs']     = $this->get_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/progress-bar-vertical', 'templates/progress-bar-vertical', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-progress-bar-vertical';
			$holder_classes[] = 'qodef-percentage--' . $atts['percentage_type'];
			$holder_classes[] = ( 'yes' === $atts['enable_pattern_fill'] ) ? 'qodef--pattern' : '';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			$data['data-bar-height'] = ! empty( $atts['progress_bar_height'] ) ? $atts['progress_bar_height'] : '300';

			$data['data-active-line-color'] = ! empty( $atts['active_line_color'] ) ? $atts['active_line_color'] : '#1e1e1e';
			$data['data-active-line-width'] = ! empty( $atts['active_line_width'] ) ? floatval( $atts['active_line_width'] ) : 25;

			$data['data-inactive-line-color'] = ! empty( $atts['inactive_line_color'] ) ? $atts['inactive_line_color'] : '#ececec';
			$data['data-inactive-line-width'] = ! empty( $atts['inactive_line_width'] ) ? floatval( $atts['inactive_line_width'] ) : 25;

			$data['data-duration']        = ! empty( $atts['duration'] ) ? intval( $atts['duration'] ) : '';
			$data['data-number']          = ! empty( $atts['number'] ) ? $atts['number'] : '0.00';
			$data['data-percentage-type'] = $atts['percentage_type'];
			$data['data-gradient-fill']   = $atts['enable_gradient_fill'];
			$data['data-rand-id']         = rand();

			$data['data-gradient-start'] = ! empty( $atts['gradient_color_start'] ) ? $atts['gradient_color_start'] : '#D9E7FA';
			$data['data-gradient-end']   = ! empty( $atts['gradient_color_end'] ) ? $atts['gradient_color_end'] : '#FCC4AF';

			$data['data-text-color'] = ! empty( $atts['number_color'] ) ? $atts['number_color'] : '#1e1e1e';
			$data['data-pattern']    = ! empty( $atts['pattern_image'] ) ? qi_addons_for_elementor_get_attachment_url( $atts['pattern_image'] ) : '';

			return $data;
		}
	}
}
