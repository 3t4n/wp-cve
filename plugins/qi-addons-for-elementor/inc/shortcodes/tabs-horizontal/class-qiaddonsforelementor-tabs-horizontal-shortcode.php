<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_tabs_horizontal_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_tabs_horizontal_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Tabs_Horizontal_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_tabs_horizontal_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Tabs_Horizontal_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_tabs_horizontal_layouts', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/tabs-horizontal' );
			$this->set_base( 'qi_addons_for_elementor_tabs_horizontal' );
			$this->set_name( esc_html__( 'Horizontal Tabs', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds tabs holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/horizontal-tabs/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#horizontal_tabs' );

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
					'field_type' => 'choose',
					'name'       => 'title_alignment',
					'title'      => esc_html__( 'Individual Title Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation .qodef-tab-title a' => 'text-align: {{VALUE}};',
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
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'horizontal_tabs_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'horizontal_tabs_tab_normal',
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
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation .qodef-tab-title a' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'title_background_color',
					'title'      => esc_html__( 'Title Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'horizontal_tabs_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'horizontal_tabs_tab_hover',
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
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li.ui-state-hover a'  => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li.ui-state-active a' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'title_hover_background',
					'title'      => esc_html__( 'Title Active/Hover Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a:before',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_underline_height',
					'title'      => esc_html__( 'Title Active/Hover Underline Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a:after'  => 'height: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_underline_bottom',
					'title'      => esc_html__( 'Title Active/Hover Underline Offset', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a:after'  => 'bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_underline_color',
					'title'      => esc_html__( 'Title Active/Hover Underline Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a:after'  => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_underline_draw',
					'title'         => esc_html__( 'Title Active/Hover Underline Draw', 'qi-addons-for-elementor' ),
					'options'       => array(
						'left'   => esc_html__( 'From Left', 'qi-addons-for-elementor' ),
						'right'  => esc_html__( 'From Right', 'qi-addons-for-elementor' ),
						'center' => esc_html__( 'From Center', 'qi-addons-for-elementor' ),
						''       => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'left',
					'group'         => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'horizontal_tabs_tab_hover_end',
					'title'      => esc_html__( 'Active/Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'horizontal_tabs_tabs_end',
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
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-content' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-content',
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_background_color',
					'title'      => esc_html__( 'Text Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'text_border',
					'title'      => esc_html__( 'Text Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-content',
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
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_margin',
					'title'      => esc_html__( 'Title Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_width',
					'title'      => esc_html__( 'Title Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 300,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-tabs-horizontal .qodef-tabs-horizontal-navigation li a' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
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

			return qi_addons_for_elementor_get_template_part( 'shortcodes/tabs-horizontal', 'variations/' . $atts['layout'] . '/templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-tabs-horizontal';
			$holder_classes[] = 'qodef-qi-clear';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['title_underline_draw'] ) ? 'qodef-title-hover--underline-draw qodef-title-underline-from-' . $atts['title_underline_draw'] : '';

			return implode( ' ', $holder_classes );
		}
	}
}
