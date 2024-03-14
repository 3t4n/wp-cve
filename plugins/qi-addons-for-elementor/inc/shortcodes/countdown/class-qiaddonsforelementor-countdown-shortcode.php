<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_countdown_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_countdown_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Countdown_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_countdown_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Countdown_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_countdown_layouts', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/countdown' );
			$this->set_base( 'qi_addons_for_elementor_countdown' );
			$this->set_name( esc_html__( 'Countdown', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays countdown with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/countdown/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#countdown' );
			$this->set_video( 'https://www.youtube.com/watch?v=bkwmG8JZerA' );
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
					'field_type'    => 'date',
					'name'          => 'date',
					'title'         => esc_html__( 'Date', 'qi-addons-for-elementor' ),
					'default_value' => '06/06/2023',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'format',
					'title'      => esc_html__( 'Format', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'Show All', 'qi-addons-for-elementor' ),
						'sec'    => esc_html__( 'Hide Seconds', 'qi-addons-for-elementor' ),
						'minsec' => esc_html__( 'Hide Minutes And Seconds', 'qi-addons-for-elementor' ),
						'mon'    => esc_html__( 'Hide Months', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'labels_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'labels_singular_tab',
					'title'      => esc_html__( 'Singular', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'month_label',
					'title'      => esc_html__( 'Month Label', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'day_label',
					'title'      => esc_html__( 'Day Label', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'hour_label',
					'title'      => esc_html__( 'Hour Label', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'minute_label',
					'title'      => esc_html__( 'Minute Label', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'second_label',
					'title'      => esc_html__( 'Second Label', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'labels_singular_tab_end',
					'title'      => esc_html__( 'Singular End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'labels_plural_tab',
					'title'      => esc_html__( 'Plural', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'month_label_plural',
					'title'      => esc_html__( 'Month Label Plural', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'day_label_plural',
					'title'      => esc_html__( 'Day Label Plural', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'hour_label_plural',
					'title'      => esc_html__( 'Hour Label Plural', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'minute_label_plural',
					'title'      => esc_html__( 'Minute Label Plural', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'second_label_plural',
					'title'      => esc_html__( 'Second Label Plural', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'labels_plural_tab_end',
					'title'      => esc_html__( 'Plural End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'labels_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Labels', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'justify_content',
					'title'      => esc_html__( 'Justify Content', 'qi-addons-for-elementor' ),
					'options'    => array(
						'space-between' => esc_html__( 'Space Betwen', 'qi-addons-for-elementor' ),
						'space-around'  => esc_html__( 'Space Around', 'qi-addons-for-elementor' ),
						'space-evenly'  => esc_html__( 'Space Evenly', 'qi-addons-for-elementor' ),
						'center'        => esc_html__( 'Center', 'qi-addons-for-elementor' ),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-m-date' => 'justify-content: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'choose',
					'name'          => 'alignment',
					'title'         => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive'    => true,
					'default_value' => 'center',
					'selectors'     => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-digit-wrapper' => 'text-align: {{VALUE}};',
					),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_start',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'digit_color',
					'title'      => esc_html__( 'Digit Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-digit' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'digit_typography',
					'title'      => esc_html__( 'Digit Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-countdown .qodef-digit',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_style_digit_label',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'label_color',
					'title'      => esc_html__( 'Label Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-label' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'label_typography',
					'title'      => esc_html__( 'Label Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-countdown .qodef-label',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'label_margin_top',
					'title'      => esc_html__( 'Label Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-label' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'item_background',
					'title'      => esc_html__( 'Item Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-countdown .qodef-digit-wrapper',
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'item_width',
					'title'      => esc_html__( 'Item Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-digit-wrapper' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'item_height',
					'title'      => esc_html__( 'Item Height', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-digit-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'item_margin',
					'title'      => esc_html__( 'Item Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-countdown .qodef-digit-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['data_attrs']     = $this->get_data_attrs( $atts );
			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/countdown', 'variations/' . $atts['layout'] . '/templates/countdown', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-countdown';

			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['format'] ) ? 'qodef-hide--' . $atts['format'] : '';
			$holder_classes[] = ! empty( $atts['justify_content'] ) ? 'qodef-justify--' . $atts['justify_content'] : '';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			if ( ! empty( $atts['date'] ) ) {
				$date              = $atts['date'];
				$date_formatted    = gmdate( 'Y/m/d H:i:s', strtotime( $date ) );
				$data['data-date'] = $date_formatted;
			}

			$data['data-hide'] = $atts['format'];

			$date_formats = array(
				'month'  => array(
					'default' => esc_html__( 'Month', 'qi-addons-for-elementor' ),
					'plural'  => esc_html__( 'Months', 'qi-addons-for-elementor' ),
				),
				'day'    => array(
					'default' => esc_html__( 'Day', 'qi-addons-for-elementor' ),
					'plural'  => esc_html__( 'Days', 'qi-addons-for-elementor' ),
				),
				'hour'   => array(
					'default' => esc_html__( 'Hour', 'qi-addons-for-elementor' ),
					'plural'  => esc_html__( 'Hours', 'qi-addons-for-elementor' ),
				),
				'minute' => array(
					'default' => esc_html__( 'Minute', 'qi-addons-for-elementor' ),
					'plural'  => esc_html__( 'Minutes', 'qi-addons-for-elementor' ),
				),
				'second' => array(
					'default' => esc_html__( 'Second', 'qi-addons-for-elementor' ),
					'plural'  => esc_html__( 'Seconds', 'qi-addons-for-elementor' ),
				),
			);

			foreach ( $date_formats as $key => $value ) {
				if ( ! empty( $atts[ $key . '_label' ] ) ) {
					$data[ 'data-' . $key . '-label' ] = $atts[ $key . '_label' ];
				} else {
					$data[ 'data-' . $key . '-label' ] = $value['default'];
				}

				if ( ! empty( $atts[ $key . '_label_plural' ] ) ) {
					$data[ 'data-' . $key . '-label-plural' ] = $atts[ $key . '_label_plural' ];
				} else {
					$data[ 'data-' . $key . '-label-plural' ] = $value['plural'];
				}
			}

			return $data;
		}
	}
}
