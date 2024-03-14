<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_accordion_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_accordion_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Accordion_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_accordion_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Accordion_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_accordion_layouts', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/accordion' );
			$this->set_base( 'qi_addons_for_elementor_accordion' );
			$this->set_name( esc_html__( 'Accordions and Toggles', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds accordion/toggle holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/accordions-and-toggles/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#accordions_and_toggles' );
			$this->set_video( 'https://www.youtube.com/watch?v=WDgy5sFM0vc' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );

			$this->set_scripts(
				array(
					'jquery-ui-accordion' => array(
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
					'name'          => 'behavior',
					'title'         => esc_html__( 'Behavior', 'qi-addons-for-elementor' ),
					'options'       => array(
						'accordion' => esc_html__( 'Accordion', 'qi-addons-for-elementor' ),
						'toggle'    => esc_html__( 'Toggle', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'accordion',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'style',
					'title'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'options'       => array(
						'standard'       => esc_html__( 'Standard', 'qi-addons-for-elementor' ),
						'boxed'          => esc_html__( 'Boxed', 'qi-addons-for-elementor' ),
						'border-top'     => esc_html__( 'Border Top', 'qi-addons-for-elementor' ),
						'border-between' => esc_html__( 'Border Between', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'standard',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'accordion_content_height',
					'title'         => esc_html__( 'Content Height', 'qi-addons-for-elementor' ),
					'options'       => array(
						'auto'    => esc_html__( 'Tallest Item Height', 'qi-addons-for-elementor' ),
						'content' => esc_html__( 'Individual Item Height', 'qi-addons-for-elementor' ),
					),
					'dependency'    => array(
						'show' => array(
							'behavior' => array(
								'values'        => array( 'accordion' ),
								'default_value' => 'accordion',
							),
						),
					),
					'default_value' => 'auto',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'icon_open',
					'title'         => esc_html__( 'Open Icon', 'qi-addons-for-elementor' ),
					'default_value' => array(
						'value'   => 'fas fa-plus',
						'library' => 'fa-solid',
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'icon_close',
					'title'         => esc_html__( 'Close Icon', 'qi-addons-for-elementor' ),
					'default_value' => array(
						'value'   => 'fas fa-minus',
						'library' => 'fa-solid',
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h3',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'accordion_title_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'accordion_title_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_background_color',
					'title'      => esc_html__( 'Title Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--standard .qodef-e-title-holder'   => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-top .qodef-e-title-holder' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--boxed .qodef-e-title-holder'      => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'style' => array(
								'values'        => array( 'standard', 'border-top', 'boxed' ),
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'accordion_title_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'accordion_title_tab_active',
					'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Active Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder.ui-state-active' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_background_hover_color',
					'title'      => esc_html__( 'Title Background Active Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--standard .qodef-e-title-holder.ui-state-active'   => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-top .qodef-e-title-holder.ui-state-active' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--boxed .qodef-e-title-holder.ui-state-active'      => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'style' => array(
								'values'        => array( 'standard', 'border-top', 'boxed' ),
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_active_padding',
					'title'      => esc_html__( 'Title Active Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder.ui-state-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'accordion_title_tab_active_end',
					'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'accordion_title_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_content',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_color',
					'title'      => esc_html__( 'Content Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-content' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_background_color',
					'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_active_background_color',
					'title'      => esc_html__( 'Content Active Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder.ui-state-active + .qodef-e-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'border_color',
					'title'      => esc_html__( 'Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--boxed' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--boxed .qodef-e-title-holder:not(:first-child)' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-top' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-top .qodef-e-title-holder' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-between .qodef-e-content' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-between .qodef-e-title-holder' => 'border-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'style' => array(
								'values'        => array( 'standard' ),
								'default_value' => 'standard',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'border_width',
					'title'      => esc_html__( 'Border Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--boxed' => 'border-width: {{SIZE}}px;',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--boxed .qodef-e-title-holder:not(:first-child)' => 'border-width: {{SIZE}}px;',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-top' => 'border-width: {{SIZE}}px;',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-top .qodef-e-title-holder' => 'border-width: {{SIZE}}px;',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-between .qodef-e-content' => 'border-width: {{SIZE}}px;',
						'{{WRAPPER}} .qodef-qi-accordion.qodef-style--border-between .qodef-e-title-holder' => 'border-width: {{SIZE}}px;',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'style' => array(
								'values'        => array( 'standard' ),
								'default_value' => 'standard',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'items_space',
					'title'      => esc_html__( 'Items Space', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-accordion .qodef-e-title-holder:not(:first-child)' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'style' => array(
								'values'        => array( 'standard' ),
								'default_value' => 'standard',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_content_icon',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder .qodef-e-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'accordion_icon_style_tabs',
					'title'      => esc_html__( 'Icon Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'accordion_icon_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'accordion_icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-mark' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'accordion_icon_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'accordion_icon_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'accordion_icon_hover_color',
					'title'      => esc_html__( 'Icon Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder:hover .qodef-e-mark' => 'color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'accordion_icon_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'accordion_icon_style_tabs_end',
					'title'      => esc_html__( 'Icon End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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
			wp_enqueue_script( 'jquery-ui-accordion' );
			qi_addons_for_elementor_icon_load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts                   = $this->get_atts();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/accordion', 'variations/' . $atts['layout'] . '/templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-accordion';
			$holder_classes[] = 'qodef-qi-clear';
			$holder_classes[] = ! empty( $atts['behavior'] ) ? 'qodef-behavior--' . $atts['behavior'] : '';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['style'] ) ? 'qodef-style--' . $atts['style'] : '';
			$holder_classes[] = ! empty( $atts['accordion_content_height'] ) ? 'qodef-height--' . $atts['accordion_content_height'] : '';

			return implode( ' ', $holder_classes );
		}
	}
}
