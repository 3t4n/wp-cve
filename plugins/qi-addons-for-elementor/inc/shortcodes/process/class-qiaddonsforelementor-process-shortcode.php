<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_process_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_process_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Process_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_process_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_List_Shortcode' ) ) {
	class QiAddonsForElementor_Process_Shortcode extends QiAddonsForElementor_List_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_process_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_process_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/process' );
			$this->set_base( 'qi_addons_for_elementor_process' );
			$this->set_name( esc_html__( 'Process', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays list of process', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Infographics', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/process/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#process' );
			$this->set_video( 'https://www.youtube.com/watch?v=colSjAmnAQU' );
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
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title' => esc_html__( 'Item Title 1', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'item_title' => esc_html__( 'Item Title 2', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'item_title' => esc_html__( 'Item Title 3', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
					),
					'items'         => array(
						array(
							'field_type' => 'icons',
							'name'       => 'icon_type',
							'title'      => esc_html__( 'Icon Type', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_a',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Item Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'textarea',
							'name'          => 'item_text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_b',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'item_margin-top',
							'title'      => esc_html__( 'Item Offset', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--horizontal {{CURRENT_ITEM}}.qodef-process-item' => 'margin-top: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--vertical {{CURRENT_ITEM}}.qodef-process-item' => 'margin-left: {{SIZE}}{{UNIT}};',
							),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'item_icon_holder_size',
							'title'      => esc_html__( 'Item Holder Size', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
							),
						),
						array(
							'field_type' => 'typography',
							'name'       => 'item_icon_typography',
							'title'      => esc_html__( 'Item Typography', 'qi-addons-for-elementor' ),
							'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-icon > .qodef-e-item-icon-text',
							'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_icon_color',
							'title'      => esc_html__( 'Item Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-icon' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type' => 'background',
							'name'       => 'item_icon_holder_background',
							'title'      => esc_html__( 'Item Holder Background', 'qi-addons-for-elementor' ),
							'types'      => array( 'classic', 'gradient', 'video' ),
							'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-icon',
						),
						array(
							'field_type' => 'dimensions',
							'name'       => 'item_icon_holder_radius',
							'title'      => esc_html__( 'Item Holder Radius', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						),
						array(
							'field_type' => 'border',
							'name'       => 'item_icon_border',
							'title'      => esc_html__( 'Item Border', 'qi-addons-for-elementor' ),
							'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-icon',
						),
						array(
							'field_type' => 'divider',
							'name'       => 'item_divider_d',
							'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'item_line_top_offset',
							'title'      => esc_html__( 'Line Top Offset', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-line' => 'top: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--horizontal {{CURRENT_ITEM}} .qodef-e-line' => 'top: {{SIZE}}{{UNIT}};',
							),
							'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'number',
							'name'       => 'line_transform_rotate',
							'title'      => esc_html__( 'Line Rotation', 'qi-addons-for-elementor' ),
							'min'        => 0,
							'max'        => 360,
							'step'       => 1,
							'default'    => 0,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-line' => 'transform:rotate({{VALUE}}deg);',
							),
						),
					),
				)
			);
			$this->map_list_options(
				array(
					'exclude_behavior' => array( 'masonry' ),
					'exclude_option'   => array( 'images_proportion', 'enable_pagination', 'space' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'choose',
					'name'          => 'alignment',
					'title'         => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-e-content' => 'text-align: {{VALUE}};',
					),
					'default_value' => 'center',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'item_title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Title/Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process .qodef-e-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title/Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_author_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-process .qodef-e-title',
					'group'      => esc_html__( 'Title/Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'title_text_divider',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title/Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process .qodef-e-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title/Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-process .qodef-e-text',
					'group'      => esc_html__( 'Title/Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'text_line_divider',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'global_item_margin_top',
					'title'      => esc_html__( 'Item Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--horizontal .qodef-process-item' => 'margin-top: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--vertical .qodef-process-item' => 'margin-left: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'global_item_icon_holder_size',
					'title'      => esc_html__( 'Item Holder Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'global_item_icon_typography',
					'title'      => esc_html__( 'Item Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-icon > .qodef-e-item-icon-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'global_item_icon_color',
					'title'      => esc_html__( 'Item Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'global_item_icon_holder_background',
					'title'      => esc_html__( 'Item Holder Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-e-icon',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'global_item_icon_holder_radius',
					'title'      => esc_html__( 'Item Holder Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'global_item_icon_border',
					'title'      => esc_html__( 'Item Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-icon',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'line_border_style',
					'title'      => esc_html__( 'Line Border Style', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'border_style', false ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--horizontal .qodef-e-line-inner' => 'border-bottom-style: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--vertical .qodef-e-line-inner' => 'border-left-style: {{VALUE}};',
					),
					'group'      => esc_html__( 'Line Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'line_border_color',
					'title'      => esc_html__( 'Line Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process .qodef-e-line-inner' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Line Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'line_thickness',
					'title'      => esc_html__( 'Line Thickness', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-line-inner' => 'border-width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--horizontal .qodef-e-line' => 'top: calc(50% - {{SIZE}}{{UNIT}}/2);',
					),
					'group'      => esc_html__( 'Line Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_top',
					'title'      => esc_html__( 'Item Title Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process .qodef-e-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_top',
					'title'      => esc_html__( 'Item Text Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process .qodef-e-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'text_padding',
					'title'      => esc_html__( 'Item Text Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-process .qodef-e-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'horizontal_additional_holder_color',
					'title'      => esc_html__( 'Additional Holder Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Additional Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'horizontal_additional_holder_typography',
					'title'      => esc_html__( 'Additional Holder Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-number',
					'group'      => esc_html__( 'Additional Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'horizontal_additional_holder_size',
					'title'      => esc_html__( 'Additional Holder Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Additional Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'horizontal_additional_holder_position',
					'title'              => esc_html__( 'Additional Holder Position', 'qi-addons-for-elementor' ),
					'allowed_dimensions' => array( 'top', 'right' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-e-number' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
					),
					'group'              => esc_html__( 'Additional Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'horizontal_additional_holder_background',
					'title'      => esc_html__( 'Additional Holder Background', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-number',
					'group'      => esc_html__( 'Additional Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'appear_animation',
					'title'         => esc_html__( 'Enable Appear Animation', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'default_value' => 'yes',
					'group'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);

			$this->map_layout_options(
				array(
					'layouts'        => $this->get_layouts(),
					'exclude_option' => array( 'title_tag', 'title_color', 'title_hover_color', 'title_typography' ),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['item_classes']   = $this->get_item_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/process', 'templates/content', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-process';
			$holder_classes[] = isset( $atts['skin'] ) && ! empty( $atts['skin'] ) ? 'qodef-skin--' . $atts['skin'] : '';
			$holder_classes[] = 'yes' === $atts['appear_animation'] ? 'qodef-qi--has-appear' : '';

			$list_classes   = $this->get_list_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$item_classes[] = 'qodef-process-item';

			$list_item_classes = $this->get_list_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $list_item_classes );

			return implode( ' ', $item_classes );
		}
	}
}
