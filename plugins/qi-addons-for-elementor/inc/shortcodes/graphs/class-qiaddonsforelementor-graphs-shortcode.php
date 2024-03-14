<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_graphs_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_graphs_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Graphs_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_graphs_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Graphs_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/graphs' );
			$this->set_base( 'qi_addons_for_elementor_graphs' );
			$this->set_name( esc_html__( 'Graphs', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays graphs with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Infographics', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/graphs/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#graphs' );
			$this->set_scripts(
				array(
					'chart' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/shortcodes/graphs/assets/js/plugins/Chart.min.js',
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
					'title'         => esc_html__( 'Datasets', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_dataset_values' => '20,60,60,20,80',
							'item_dataset_labels' => esc_html__( 'Example Label 1', 'qi-addons-for-elementor' ),
							'item_border_color'   => '#1e1e1e',
						),
						array(
							'item_dataset_values' => '40,50,40,30,50',
							'item_dataset_labels' => esc_html__( 'Example Label 2', 'qi-addons-for-elementor' ),
							'item_border_color'   => '#666666',
						),
						array(
							'item_dataset_values' => '50,20,30,50,70',
							'item_dataset_labels' => esc_html__( 'Example Label 3', 'qi-addons-for-elementor' ),
							'item_border_color'   => '#939393',
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_dataset_values',
							'title'         => esc_html__( 'Dataset Values', 'qi-addons-for-elementor' ),
							'dynamic'       => false,
							'default_value' => '30,20,30,80,20',
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_dataset_labels',
							'title'         => esc_html__( 'Dataset Labels', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Label', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_a',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'color',
							'name'          => 'item_border_color',
							'title'         => esc_html__( 'Line Color', 'qi-addons-for-elementor' ),
							'default_value' => '#999999',
						),
						array(
							'field_type'  => 'color',
							'name'        => 'item_hover_border_color',
							'title'       => esc_html__( 'Hover Line Color', 'qi-addons-for-elementor' ),
							'description' => esc_html__( 'Only for bar graphs', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_b',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'  => 'text',
							'name'        => 'item_fill_area',
							'title'       => esc_html__( 'Filling Modes', 'qi-addons-for-elementor' ),
							'dynamic'     => false,
							'description' => esc_html__( 'Only for line graphs. Ex. values: origin, -1, -2, +1, +2 ...', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_background_color',
							'title'      => esc_html__( 'Area Color', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'  => 'color',
							'name'        => 'item_hover_background_color',
							'title'       => esc_html__( 'Hover Area Color', 'qi-addons-for-elementor' ),
							'description' => esc_html__( 'Only for bar graphs', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'select',
							'name'          => 'item_linear',
							'title'         => esc_html__( 'Enable Linear Mode', 'qi-addons-for-elementor' ),
							'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
							'default_value' => 'no',
							'description'   => esc_html__( 'Only for line graphs', 'qi-addons-for-elementor' ),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'data_labels',
					'title'         => esc_html__( 'Data Labels', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( '1 month, 3 months, 6 months, 12 months, 24 months', 'qi-addons-for-elementor' ),
					'description'   => esc_html__( 'Separate labels with commas', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'chart_type',
					'title'      => esc_html__( 'Graph Type', 'qi-addons-for-elementor' ),
					'options'    => array(
						'line' => esc_html__( 'Line', 'qi-addons-for-elementor' ),
						'bar'  => esc_html__( 'Bar', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'chart_alignment',
					'title'      => esc_html__( 'Graph Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_flex', false ),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
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

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'chart_size',
					'title'      => esc_html__( 'Graph Size', 'qi-addons-for-elementor' ),
					'responsive' => true,
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 700,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-graphs .qodef-m-canvas' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'border_width',
					'title'      => esc_html__( 'Border Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'hover_border_width',
					'title'      => esc_html__( 'Hover Border Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'chart_type' => array(
								'values'        => 'bar',
								'default_value' => 'line',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'bar_size',
					'title'      => esc_html__( 'Bar Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1,
							'step' => 0.05,
						),
					),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'chart_type' => array(
								'values'        => 'bar',
								'default_value' => 'line',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'cat_size',
					'title'      => esc_html__( 'Category Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1,
							'step' => 0.05,
						),
					),
					'group'      => esc_html__( 'Graph Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'chart_type' => array(
								'values'        => 'bar',
								'default_value' => 'line',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'ticks_min',
					'title'      => esc_html__( 'Minimum Data Value', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Tick Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'ticks_max',
					'title'      => esc_html__( 'Maximum Data Value', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Tick Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'ticks_step',
					'title'      => esc_html__( 'Step Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Tick Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function load_assets() {
			wp_enqueue_script( 'chart' );
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_graphs', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['data_attrs']     = $this->get_data_attrs( $atts['items'], $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/graphs', 'templates/graphs', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-graphs';
			$holder_classes[] = ! empty( $atts['chart_alignment'] ) ? 'qodef-chart-alignment--' . $atts['chart_alignment'] : '';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $items, $atts ) {
			$data = array();
			$temp = array();

			foreach ( $items as $key => $value ) {
				$temp['data-values'][]                  = ! empty( $value['item_dataset_values'] ) ? $value['item_dataset_values'] : 0;
				$temp['data-item-labels'][]             = ! empty( $value['item_dataset_labels'] ) ? $value['item_dataset_labels'] : '';
				$temp['data-background-colors'][]       = ! empty( $value['item_background_color'] ) ? $value['item_background_color'] : '#1e1e1e';
				$temp['data-hover-background-colors'][] = ! empty( $value['item_hover_background_color'] ) ? $value['item_hover_background_color'] : '#ececec';
				$temp['data-border-colors'][]           = ! empty( $value['item_border_color'] ) ? $value['item_border_color'] : '#fff';
				$temp['data-hover-border-colors'][]     = ! empty( $value['item_hover_border_color'] ) ? $value['item_hover_border_color'] : '#fff';
				$temp['data-fill'][]                    = ! empty( $value['item_fill_area'] ) ? $value['item_fill_area'] : false;
				$temp['data-linear'][]                  = ( 'yes' === $value['item_linear'] ) ? 0 : 0.4;
			}
			$temp['data-labels'] = ! empty( $atts['data_labels'] ) ? explode( ',', $atts['data_labels'] ) : '';

			$temp['data-border-width']       = ( '' !== $atts['border_width'] ) ? intval( $atts['border_width'] ) : 3;
			$temp['data-hover-border-width'] = ( '' !== $atts['hover_border_width'] ) ? intval( $atts['hover_border_width'] ) : 3;

			$temp['data-bar-size'] = ! empty( $atts['bar_size']['size'] ) ? $atts['bar_size']['size'] : 0.4;
			$temp['data-cat-size'] = ! empty( $atts['cat_size']['size'] ) ? $atts['cat_size']['size'] : 0.65;

			$temp['data-type'] = 'line' === $atts['chart_type'];

			$temp['data-ticks']['min']  = ! empty( $atts['ticks_min'] ) ? intval( $atts['ticks_min'] ) : '';
			$temp['data-ticks']['max']  = ! empty( $atts['ticks_max'] ) ? intval( $atts['ticks_max'] ) : '';
			$temp['data-ticks']['step'] = ! empty( $atts['ticks_step'] ) ? intval( $atts['ticks_step'] ) : '';

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
