<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_separator_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_separator_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Separator_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_separator_shortcode', 9 );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Separator_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_separator_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_separator_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/separator' );
			$this->set_base( 'qi_addons_for_elementor_separator' );
			$this->set_name( esc_html__( 'Divider', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays divider with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/divider/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#divider' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);

			$options_map = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'separator_layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => 'standard',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'position',
					'title'      => esc_html__( 'Position', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'Default', 'qi-addons-for-elementor' ),
						'center' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
						'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
						'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'separator_color',
					'title'      => esc_html__( 'Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-line' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'separator_layout' => array(
								'values'        => array( 'border-image' ),
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'separator_border_style',
					'title'      => esc_html__( 'Border Style', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'border_style' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-line' => 'border-style: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'separator_layout' => array(
								'values'        => array( 'border-image' ),
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'separator_width',
					'title'      => esc_html__( 'Width (px or %)', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 500,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-line' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'separator_thickness',
					'title'      => esc_html__( 'Thickness (px)', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-line' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'separator_margin_top',
					'title'      => esc_html__( 'Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-line' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'separator_margin_bottom',
					'title'      => esc_html__( 'Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-line' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->map_extra_options();
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_separator', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function load_assets() {
			parent::load_assets();

			qi_addons_for_elementor_icon_load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/separator', 'variations/' . $atts['separator_layout'] . '/templates/' . $atts['separator_layout'], '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-separator';
			$holder_classes[] = 'qodef-qi-clear';
			$holder_classes[] = ! empty( $atts['separator_layout'] ) ? 'qodef-separator--' . $atts['separator_layout'] : '';
			$holder_classes[] = ! empty( $atts['position'] ) ? 'qodef-position--' . $atts['position'] : '';

			return implode( ' ', $holder_classes );
		}
	}
}
