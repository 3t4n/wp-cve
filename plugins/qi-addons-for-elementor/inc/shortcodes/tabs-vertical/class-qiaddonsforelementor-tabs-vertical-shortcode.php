<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_tabs_vertical_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_tabs_vertical_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Tabs_Vertical_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_tabs_vertical_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Tabs_Vertical_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_tabs_vertical_layouts', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/tabs-vertical' );
			$this->set_base( 'qi_addons_for_elementor_tabs_vertical' );
			$this->set_name( esc_html__( 'Vertical Tabs', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds tabs holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/vertical-tabs/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#vertical_tabs' );
			$this->set_video( 'https://www.youtube.com/watch?v=4Aw9qk05M3w' );

			$this->set_scripts(
				array(
					'jquery-ui-tabs' => array(
						'registered' => true,
					),
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
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'responsive_layout',
					'title'         => esc_html__( 'Place content below tab on screens under:', 'qi-addons-for-elementor' ),
					'options'       => array(
						'1024' => esc_html__( '1024px', 'qi-addons-for-elementor' ),
						'768'  => esc_html__( '768px', 'qi-addons-for-elementor' ),
						'680'  => esc_html__( '680px', 'qi-addons-for-elementor' ),
						'480'  => esc_html__( '480px', 'qi-addons-for-elementor' ),
					),
					'default_value' => '680',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'choose',
					'name'       => 'title_alignment',
					'title'      => esc_html__( 'Title Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation .qodef-tab-title a' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'vertical_tabs_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'vertical_tabs_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation .qodef-tab-title a' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'title_background_color',
					'title'      => esc_html__( 'Title Background Color', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'vertical_tabs_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'vertical_tabs_tab_hover',
					'title'      => esc_html__( 'Active/Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Active/Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li.ui-state-hover a'  => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li.ui-state-active a' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'title_hover_background',
					'title'      => esc_html__( 'Title Active/Hover Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a:before',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_sideline_width',
					'title'      => esc_html__( 'Title Active/Hover Sideline Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a:after'  => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_sideline_right',
					'title'      => esc_html__( 'Title Active/Hover Sideline Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => - 10,
							'max' => 10,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a:after'  => 'right: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_sideline_color',
					'title'      => esc_html__( 'Title Active/Hover Sideline Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a:after'  => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_sideline_draw',
					'title'         => esc_html__( 'Title Active/Hover Sideline Draw', 'qi-addons-for-elementor' ),
					'options'       => array(
						'top'    => esc_html__( 'From Top', 'qi-addons-for-elementor' ),
						'center' => esc_html__( 'From Center', 'qi-addons-for-elementor' ),
						''       => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'top',
					'group'         => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'vertical_tabs_tab_hover_end',
					'title'      => esc_html__( 'Active/Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'vertical_tabs_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'divider_style_title_text',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'title_border',
					'title'      => esc_html__( 'Title Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-content' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-content',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_background_color',
					'title'      => esc_html__( 'Text Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'text_border',
					'title'      => esc_html__( 'Text Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-content',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_padding',
					'title'      => esc_html__( 'Title Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'content_padding',
					'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'items_space',
					'title'      => esc_html__( 'Items Space', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min'  => - 100,
							'max'  => 100,
							'step' => 1,
						),
						'%'  => array(
							'min'  => - 100,
							'max'  => 100,
							'step' => 1,
						),
						'em' => array(
							'min'  => - 10,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-vertical .qodef-tabs-vertical-navigation li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title'   => esc_html__( 'Example Title 1', 'qi-addons-for-elementor' ),
							'item_content' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
						),
						array(
							'item_title'   => esc_html__( 'Example Title 2', 'qi-addons-for-elementor' ),
							'item_content' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'html',
							'name'          => 'item_content',
							'title'         => esc_html__( 'Content', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_long' ),
						),
					),
				)
			);
		}

		public function load_assets() {
			wp_enqueue_script( 'jquery-ui-tabs' );
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts                   = $this->get_atts();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/tabs-vertical', 'variations/' . $atts['layout'] . '/templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-tabs-vertical';
			$holder_classes[] = 'qodef-qi-clear';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['responsive_layout'] ) ? 'qodef-responsive--' . $atts['responsive_layout'] : '';
			$holder_classes[] = ! empty( $atts['title_sideline_draw'] ) ? 'qodef-title-hover--sideline-draw qodef-title-sideline-from-' . $atts['title_sideline_draw'] : '';

			return implode( ' ', $holder_classes );
		}
	}
}
