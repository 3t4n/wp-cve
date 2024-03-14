<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_link_showcase_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Interactive_Link_Showcase_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_interactive_link_showcase_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Interactive_Link_Showcase_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_interactive_link_showcase_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_interactive_link_showcase_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/interactive-link-showcase' );
			$this->set_base( 'qi_addons_for_elementor_interactive_link_showcase' );
			$this->set_name( esc_html__( 'Interactive Links', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds interactive links', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/interactive-links/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#interactive_links' );
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
					'default_value' => 'standard',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_full_height',
					'title'         => esc_html__( 'Enable Full Height', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Child elements', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title'          => esc_html__( 'Item Title 1', 'qi-addons-for-elementor' ),
							'item_text'           => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_image'          => $placeholder,
							'item_image_position' => 'left',
						),
						array(
							'item_title'          => esc_html__( 'Item Title 2', 'qi-addons-for-elementor' ),
							'item_text'           => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_image'          => $placeholder,
							'item_image_position' => 'right',
						),
					),
					'items'         => array(
						array(
							'field_type' => 'link',
							'name'       => 'item_link',
							'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Item Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'field_type'    => 'image',
							'name'          => 'item_image',
							'title'         => esc_html__( 'Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type'    => 'select',
							'name'          => 'item_image_position',
							'title'         => esc_html__( 'Image Position', 'qi-addons-for-elementor' ),
							'options'       => array(
								'left'  => esc_html__( 'Left', 'qi-addons-for-elementor' ),
								'right' => esc_html__( 'Right', 'qi-addons-for-elementor' ),
							),
							'default_value' => 'left',
							'description'   => esc_html__( 'This option affects only standard and inline layout', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'         => 'dimensions',
							'name'               => 'item_image_offset_vertical',
							'title'              => esc_html__( 'Image Offset From Center', 'qi-addons-for-elementor' ),
							'size_units'         => array( 'px', '%' ),
							'allowed_dimensions' => array( 'top', 'left' ),
							'responsive'         => true,
							'selectors'          => array(
								'{{WRAPPER}} .qodef-layout--standard {{CURRENT_ITEM}} img' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .qodef-layout--inline {{CURRENT_ITEM}} img' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
							),
							'description'        => esc_html__( 'This option affects only standard and inline layout', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'  => 'slider',
							'name'        => 'item_image_width',
							'title'       => esc_html__( 'Image Width', 'qi-addons-for-elementor' ),
							'size_units'  => array( 'px', 'vw', '%' ),
							'range'       => array(
								'px' => array(
									'min' => 0,
									'max' => 700,
								),
							),
							'responsive'  => true,
							'selectors'   => array(
								'{{WRAPPER}} .qodef-layout--inline .qodef-e-image{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .qodef-layout--standard .qodef-e-image{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}} !important;',
							),
							'description' => esc_html__( 'This option affects only inline and standard layout', 'qi-addons-for-elementor' ),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'list_inner_padding',
					'title'      => esc_html__( 'List Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'list_width',
					'title'      => esc_html__( 'List Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 100,
							'max' => 2000,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-items' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-layout--standard .qodef-e-image.qodef-position--right' => 'width: calc((100% - {{SIZE}}{{UNIT}})/2);',
						'{{WRAPPER}} .qodef-layout--standard .qodef-e-image.qodef-position--left' => 'width: calc((100% - {{SIZE}}{{UNIT}})/2);',
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'space_between_items',
					'title'      => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'vw', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-item' => 'margin: calc({{SIZE}}{{UNIT}}/2) 0;',
						'{{WRAPPER}} .qodef-layout--inline .qodef-m-items' => 'margin: 0 calc(-{{SIZE}}{{UNIT}}/2);',
						'{{WRAPPER}} .qodef-layout--inline .qodef-e-title' => 'padding: 0 calc({{SIZE}}{{UNIT}}/2);',
						'{{WRAPPER}} .qodef-layout--inline .qodef-e-text' => 'padding: 0 calc({{SIZE}}{{UNIT}}/2);',
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef--active.qodef-m-item .qodef-e-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_hover_color',
					'title'      => esc_html__( 'Text Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef--active.qodef-m-item .qodef-e-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_top',
					'title'      => esc_html__( 'Text Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -100,
							'max' => 100,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'margin-top: {{SIZE}}{{UNIT}}',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_hover_style',
					'title'         => esc_html__( 'Title Hover Style', 'qi-addons-for-elementor' ),
					'options'       => array(
						'underline'    => esc_html__( 'Underline', 'qi-addons-for-elementor' ),
						'line-through' => esc_html__( 'Line Through', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'underline',
					'group'         => esc_html__( 'Hover Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'title_line_thickness',
					'title'      => esc_html__( 'Hover Line Thickness', 'qi-addons-for-elementor' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-inner-title:after' => 'height: {{VALUE}}px;',
					),
					'group'      => esc_html__( 'Hover Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'number',
					'name'       => 'title_line_offset',
					'title'      => esc_html__( 'Hover Line Offset', 'qi-addons-for-elementor' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-hover--line-through .qodef-e-inner-title:after' => 'top: calc(50% - {{VALUE}}px);',
						'{{WRAPPER}} .qodef-hover--underline .qodef-e-inner-title:after'    => 'bottom: {{VALUE}}px;',
					),
					'group'      => esc_html__( 'Hover Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/interactive-link-showcase', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-interactive-link-showcase';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ( 'yes' === $atts['enable_full_height'] ) ? 'qodef--full-height' : '';
			$holder_classes[] = ! empty( $atts['title_hover_style'] ) ? 'qodef-hover--' . $atts['title_hover_style'] : '';

			return implode( ' ', $holder_classes );
		}

		public function get_image_styles( $atts ) {
			$styles = array();

			if ( ! empty( $atts['item_image'] ) ) {
				$styles[] = 'background-image: url(' . esc_url( qi_addons_for_elementor_get_attachment_url( $atts['item_image'] ) ) . ')';
			}

			return $styles;
		}
	}
}
