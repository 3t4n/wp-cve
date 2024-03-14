<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blockquote_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blockquote_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Blockquote_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_blockquote_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Blockquote_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_blockquote_layouts', array() ) );

			$options_map   = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );
			$default_value = $options_map['default_value'];

			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_blockquote_extra_options', array(), $default_value ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/blockquote' );
			$this->set_base( 'qi_addons_for_elementor_blockquote' );
			$this->set_name( esc_html__( 'Blockquote', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds blockquote element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/blockquote/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#blockquote' );
			$this->set_video( 'https://www.youtube.com/watch?v=LxHZ2d9HejY' );
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
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => 'top',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
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
					'field_type'    => 'icons',
					'name'          => 'icon_type',
					'title'         => esc_html__( 'Icon Type', 'qi-addons-for-elementor' ),
					'default_value' => array(
						'value'   => 'fas fa-quote-right',
						'library' => 'fa-solid',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'choose',
					'name'          => 'alignment',
					'title'         => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-qi-blockquote' => 'text-align: {{VALUE}};',
					),
					'default_value' => 'left',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency'    => array(
						'show' => array(
							'layout' => array(
								'values'        => 'top',
								'default_value' => 'top',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'holder_background',
					'title'      => esc_html__( 'Holder Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-blockquote',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'holder_border',
					'title'      => esc_html__( 'Holder Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-blockquote',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'text_tag',
					'title'         => esc_html__( 'Text Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h4',
					'group'         => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-text' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-text',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'choose',
					'name'          => 'icon_alignment',
					'title'         => esc_html__( 'Icon Alignment', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-qi-blockquote .qodef-m-icon' => 'text-align: {{VALUE}};',
					),
					'default_value' => 'center',
					'group'         => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'dependency'    => array(
						'show' => array(
							'layout' => array(
								'values'        => 'top',
								'default_value' => 'top',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-blockquote .qodef-m-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-blockquote .qodef-m-icon svg' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-blockquote .qodef-m-icon' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'holder_padding',
					'title'      => esc_html__( 'Holder Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}}  .qodef-qi-blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-blockquote .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'icon_margin',
					'title'      => esc_html__( 'Icon Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-blockquote .qodef-m-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->map_extra_options();
		}

		public function load_assets() {
			parent::load_assets();

			qi_addons_for_elementor_icon_load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			return apply_filters( 'qi_addons_for_elementor_filter_blockquote_render_template', qi_addons_for_elementor_get_template_part( 'shortcodes/blockquote', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts ), $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-blockquote';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';

			$icon_class = '';

			if ( ! empty( $atts['icon_type']['value'] ) ) {
				if ( is_string( $atts['icon_type']['value'] ) ) {
					$icon_class = 'icon-pack';
				} else {
					$icon_class = 'custom-icon';
				}
			}

			$holder_classes[] = 'qodef--' . $icon_class;

			$holder_classes = apply_filters( 'qi_addons_for_elementor_filter_blockquote_variation_classes', $holder_classes, $atts );

			return implode( ' ', $holder_classes );
		}
	}
}
