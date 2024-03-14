<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_text_marquee_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_text_marquee_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Text_Marquee_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_text_marquee_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Text_Marquee_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_text_marquee_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_text_marquee_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/text-marquee' );
			$this->set_base( 'qi_addons_for_elementor_text_marquee' );
			$this->set_name( esc_html__( 'Text Marquee', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds Text Marquee element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/text-marquee/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#text_marquee' );
			$this->set_video( 'https://www.youtube.com/watch?v=Q4-2PHcCAj0' );
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
					'default_value' => $options_map['default_value'],
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_text' => esc_html__( 'Example Text 1', 'qi-addons-for-elementor' ),
						),
						array(
							'item_text' => esc_html__( 'Example Text 2', 'qi-addons-for-elementor' ),
						),
						array(
							'item_text' => esc_html__( 'Example Text 3', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'textarea',
							'name'          => 'item_text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Text', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'typography',
							'name'       => 'item_typography',
							'title'      => esc_html__( 'Typography', 'qi-addons-for-elementor' ),
							'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}}.qodef-m-text-item',
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'separator_icon',
					'title'         => esc_html__( 'Separator Icon', 'qi-addons-for-elementor' ),
					'default_value' => array(),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'duration',
					'title'         => esc_html__( 'Animation Duration (Seconds)', 'qi-addons-for-elementor' ),
					'default_value' => '20',
				)
			);

			$this->set_option(
				array(
					'field_type'           => 'checkbox',
					'name'                 => 'reverse_direction',
					'title'                => esc_html__( 'Reverse Direction', 'qi-addons-for-elementor' ),
					'options'              => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'selectors_dictionary' => array(
						'yes' => 'animation: qode-move-horizontal-reverse-text-marquee',
						''    => 'animation: qode-move-horizontal-normal-text-marquee',
					),
					'selectors'            => array(
						'{{WRAPPER}} .qodef-qi-text-marquee .qodef-m-text.qodef-text--original' => '{{VALUE}} {{duration.VALUE}}s linear infinite',
						'{{WRAPPER}} .qodef-qi-text-marquee .qodef-m-text.qodef-text--copy'     => '{{VALUE}}-copy {{duration.VALUE}}s linear infinite',
					),
					'default_value'        => '',
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'color',
					'title'      => esc_html__( 'Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-text-item' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'typography',
					'title'      => esc_html__( 'Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-text-item',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'text_stroke_effect',
					'title'      => esc_html__( 'Text Stroke Effect', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_stroke_color',
					'title'      => esc_html__( 'Text Stroke Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-text-item ' => '-webkit-text-stroke-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'text_stroke_effect' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'text_stroke_width',
					'title'      => esc_html__( 'Text Stroke Width', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-text-item ' => '-webkit-text-stroke-width: {{SIZE}}px;',
					),
					'dependency' => array(
						'show' => array(
							'text_stroke_effect' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon-holder' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon-holder' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'space_between_items',
					'title'      => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-text-item' => 'padding-right: calc({{SIZE}}{{UNIT}}/2); padding-left: calc({{SIZE}}{{UNIT}}/2);',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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

			$atts['holder_classes']   = $this->get_holder_classes( $atts );
			$atts['items']            = $this->parse_repeater_items( $atts['children'] );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/text-marquee', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-text-marquee';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ( 'yes' === $atts['text_stroke_effect'] ) ? 'qodef-text-stroke-effect' : '';

			return implode( ' ', $holder_classes );
		}
	}
}
