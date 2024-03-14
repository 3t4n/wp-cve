<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_before_after_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_before_after_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Before_After_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_before_after_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Before_After_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_before_after_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/before-after' );
			$this->set_base( 'qi_addons_for_elementor_before_after' );
			$this->set_name( esc_html__( 'Before/After Comparison Slider', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds before/after comparison slider', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/before-after-comparison-slider/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#before_after_comparison_slider' );

			$this->set_scripts(
				array(
					'twentytwenty' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/before-after/assets/js/plugins/jquery.twentytwenty.js',
						'dependency' => array( 'jquery', 'jquery-effects-core' ),
						'version'    => false,
						'footer'     => true,
					),
					'event-move'   => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/before-after/assets/js/plugins/jquery.event.move.js',
						'dependency' => array( 'jquery', 'jquery-effects-core' ),
						'version'    => false,
						'footer'     => true,
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
					'field_type' => 'image',
					'name'       => 'image_before',
					'title'      => esc_html__( 'Image Before', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'image_after',
					'title'      => esc_html__( 'Image After', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'handle_text',
					'title'         => esc_html__( 'Handle Text', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( 'Drag', 'qi-addons-for-elementor' ),
					'dynamic'       => false,
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'orientation',
					'title'      => esc_html__( 'Orientation', 'qi-addons-for-elementor' ),
					'options'    => array(
						'horizontal' => esc_html__( 'Horizontal', 'qi-addons-for-elementor' ),
						'vertical'   => esc_html__( 'Vertical', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'offset',
					'title'       => esc_html__( 'Default Offset', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Default value is 50 (%)', 'qi-addons-for-elementor' ),
					'dynamic'     => false,
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'handle_offset',
					'title'      => esc_html__( 'Handle Top Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 500,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-before-after .twentytwenty-handle' => 'top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'circle_size',
					'title'      => esc_html__( 'Circle Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-before-after .twentytwenty-handle' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'border_width',
					'title'      => esc_html__( 'Border Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:after'  => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:before' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:after'    => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:before'   => 'height: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'handle_text_color',
					'title'      => esc_html__( 'Handle Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .twentytwenty-handle .qodef-handle-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'handle_text_typography',
					'title'      => esc_html__( 'Handle Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .twentytwenty-handle .qodef-handle-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'handle_color',
					'title'      => esc_html__( 'Handle Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .twentytwenty-handle' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->map_extra_options();
		}

		public function load_assets() {
			wp_enqueue_script( 'jquery-effects-core' );

			wp_enqueue_script( 'twentytwenty' );
			wp_enqueue_script( 'event-move' );
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['offset'] = doubleval( $atts['offset'] );
			if ( $atts['offset'] < 0 || $atts['offset'] > 100 ) {
				$atts['offset'] = 50;
			}

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['holder_data']    = $this->getHolderData( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/before-after', 'templates/before-after', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-before-after';
			$holder_classes[] = ( ! empty( $atts['orientation'] ) ) ? 'qodef--' . $atts['orientation'] : '';

			return implode( ' ', $holder_classes );
		}

		private function getHolderData( $atts ) {
			$holder_data = array();

			$holder_data['data-offset'] = ! empty( $atts['offset'] ) ? esc_attr( $atts['offset'] ) : 50;

			return $holder_data;
		}
	}
}
