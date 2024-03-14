<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_charts_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_charts_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_DoughnutAndPieCharts_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_charts_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_DoughnutAndPieCharts_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/charts' );
			$this->set_base( 'qi_addons_for_elementor_charts' );
			$this->set_name( esc_html__( 'Pie and Donut Charts', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays charts with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Infographics', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/pie-and-donut-charts/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#pie_and_donut_charts' );
			$this->set_scripts(
				array(
					'chart' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/shortcodes/charts/assets/js/plugins/Chart.min.js',
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
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Values', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_data_value'       => 10,
							'item_background_color' => '#1e1e1e',
							'item_data_label'       => esc_html__( 'Label 1', 'qi-addons-for-elementor' ),
						),
						array(
							'item_data_value'       => 30,
							'item_background_color' => '#666666',
							'item_data_label'       => esc_html__( 'Label 2', 'qi-addons-for-elementor' ),
						),
						array(
							'item_data_value'       => 60,
							'item_background_color' => '#939393',
							'item_data_label'       => esc_html__( 'Label 3', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'number',
							'name'          => 'item_data_value',
							'title'         => esc_html__( 'Data Value', 'qi-addons-for-elementor' ),
							'default_value' => 20,
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_data_label',
							'title'         => esc_html__( 'Data Label', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Label', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_a',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'color',
							'name'          => 'item_background_color',
							'title'         => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
							'default_value' => '#9999',
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_hover_background_color',
							'title'      => esc_html__( 'Hover Background Color', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'image',
							'name'       => 'item_pattern_image',
							'title'      => esc_html__( 'Pattern Image', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_b',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_border_color',
							'title'      => esc_html__( 'Border Color', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_hover_border_color',
							'title'      => esc_html__( 'Hover Border Color', 'qi-addons-for-elementor' ),
						),
					),
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
					'field_type' => 'select',
					'name'       => 'chart_alignment',
					'title'      => esc_html__( 'Chart Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_flex', false ),
					'group'      => esc_html__( 'Chart Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'chart_type',
					'title'      => esc_html__( 'Chart Type', 'qi-addons-for-elementor' ),
					'options'    => array(
						'pie'      => esc_html__( 'Pie', 'qi-addons-for-elementor' ),
						'doughnut' => esc_html__( 'Doughnut', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Chart Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'chart_size',
					'title'      => esc_html__( 'Chart Width', 'qi-addons-for-elementor' ),
					'responsive' => true,
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 500,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-canvas' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Chart Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'chart_aspect_ratio',
					'title'      => esc_html__( 'Chart Aspect Ratio', 'qi-addons-for-elementor' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 5,
							'step' => 0.1
						),
					),
					'group'      => esc_html__( 'Chart Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'border_width',
					'title'      => esc_html__( 'Border Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Chart Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'hover_border_width',
					'title'      => esc_html__( 'Hover Border Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Chart Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'choose',
					'name'       => 'alignment',
					'title'      => esc_html__( 'Text Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-inner' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-charts .qodef-m-title',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-charts .qodef-m-text',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-title' => 'margin-top: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-charts .qodef-m-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'enable_chart_legend',
					'title'      => esc_html__( 'Enable Chart Legend', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'legend_position',
					'title'      => esc_html__( 'Legend Position', 'qi-addons-for-elementor' ),
					'options'    => array(
						'top'    => esc_html__( 'Top', 'qi-addons-for-elementor' ),
						'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
						'bottom' => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
						'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'legend_alignment',
					'title'      => esc_html__( 'Legend Alignment', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'Default', 'qi-addons-for-elementor' ),
						'start'  => esc_html__( 'Start', 'qi-addons-for-elementor' ),
						'center' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
						'end'    => esc_html__( 'End', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'legend_divider',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'legend_bar_width',
					'title'      => esc_html__( 'Legend Bar Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'legend_bar_height',
					'title'      => esc_html__( 'Legend Bar Height', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'legend_bar_margin',
					'title'      => esc_html__( 'Legend Bar Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'legend_label_divider',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'legend_label_color',
					'title'      => esc_html__( 'Legend Label Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'fonts',
					'name'       => 'legend_label_font',
					'title'      => esc_html__( 'Legend Label Font', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'legend_label_font_size',
					'title'      => esc_html__( 'Legend Label Font Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'legend_label_font_weight',
					'title'      => esc_html__( 'Legend Label Font Weight', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'font_weight' ),
					'group'      => esc_html__( 'Legend Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function load_assets() {
			wp_enqueue_script( 'chart' );
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_charts', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['data_attrs']     = $this->get_data_attrs( $atts['items'], $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/charts', 'templates/charts', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-charts';
			$holder_classes[] = ! empty( $atts['chart_alignment'] ) ? 'qodef-chart-alignment--' . $atts['chart_alignment'] : '';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $items, $atts ) {
			$data = array();
			$temp = array();

			foreach ( $items as $key => $value ) {
				$temp['data-values'][]                  = ! empty( $value['item_data_value'] ) ? $value['item_data_value'] : 0;
				$temp['data-labels'][]                  = ! empty( $value['item_data_label'] ) ? $value['item_data_label'] : 'label-' . ( $key + 1 );
				$temp['data-pattern-images'][]          = ! empty( $value['item_pattern_image'] ) ? qi_addons_for_elementor_get_attachment_image_src( $value['item_pattern_image'], 'full' )[0] : '';
				$temp['data-background-colors'][]       = ! empty( $value['item_background_color'] ) ? $value['item_background_color'] : '#1e1e1e';
				$temp['data-hover-background-colors'][] = ! empty( $value['item_hover_background_color'] ) ? $value['item_hover_background_color'] : '#ececec';
				$temp['data-border-colors'][]           = ! empty( $value['item_border_color'] ) ? $value['item_border_color'] : '#fff';
				$temp['data-hover-border-colors'][]     = ! empty( $value['item_hover_border_color'] ) ? $value['item_hover_border_color'] : '#fff';
			}

			$temp['data-border-width']       = ! empty( intval( $atts['border_width'] ) ) ? intval( $atts['border_width'] ) : 0;
			$temp['data-hover-border-width'] = ! empty( intval( $atts['hover_border_width'] ) ) ? intval( $atts['hover_border_width'] ) : 0;

			$temp['data-type']         = 'pie' === $atts['chart_type'];
			$temp['data-aspect-ratio'] = ! empty( $atts['chart_aspect_ratio']['size'] ) ? $atts['chart_aspect_ratio']['size'] : 1;

			$temp['data-enable-legend']            = ( 'yes' === $atts['enable_chart_legend'] ) ? true : false;
			$temp['data-legend-position']          = ! empty( $atts['legend_position'] ) ? $atts['legend_position'] : 'top';
			$temp['data-legend-alignment']         = ! empty( $atts['legend_alignment'] ) ? $atts['legend_alignment'] : '';
			$temp['data-legend-bar-width']         = ! empty( $atts['legend_bar_width']['size'] ) ? intval( $atts['legend_bar_width']['size'] ) : '';
			$temp['data-legend-bar-height']        = ! empty( $atts['legend_bar_height']['size'] ) ? intval( $atts['legend_bar_height']['size'] ) : '';
			$temp['data-legend-bar-margin']        = ! empty( $atts['legend_bar_margin']['size'] ) ? intval( $atts['legend_bar_margin']['size'] ) : '';
			$temp['data-legend-label-color']       = ! empty( $atts['legend_label_color'] ) ? $atts['legend_label_color'] : '';
			$temp['data-legend-label-font']        = ! empty( $atts['legend_label_font'] ) ? $atts['legend_label_font'] : '';
			$temp['data-legend-label-font-size']   = ! empty( $atts['legend_label_font_size']['size'] ) ? intval( $atts['legend_label_font_size']['size'] ) : '';
			$temp['data-legend-label-font-weight'] = ! empty( $atts['legend_label_font_weight'] ) ? $atts['legend_label_font_weight'] : '';

			foreach ( $temp as $key => $value ) {
				if ( is_array( $value ) || is_bool( $value ) ) {
					$data[ $key ] = json_encode( $value );
				} else {
					$data[ $key ] = $value;
				}
			}

			return $data;
		}
	}
}
