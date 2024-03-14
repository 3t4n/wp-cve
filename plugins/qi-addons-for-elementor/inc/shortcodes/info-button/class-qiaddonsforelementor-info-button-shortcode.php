<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_info_button_shortcode' ) ) {
	/**
	 * Function that isadding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_info_button_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Info_Button_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_info_button_shortcode', 9 );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Info_Button_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/info-button' );
			$this->set_base( 'qi_addons_for_elementor_info_button' );
			$this->set_name( esc_html__( 'Info Button', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays info button with provided parameters', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/info-button/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#info_button' );
			$this->set_video( 'https://www.youtube.com/watch?v=JkjHNRcc2zI' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );

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
					'name'          => 'info_button_layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => array(
						'filled'   => esc_html__( 'Filled', 'qi-addons-for-elementor' ),
						'outlined' => esc_html__( 'Outlined', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'filled',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'info_button_type',
					'title'         => esc_html__( 'Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'standard'   => esc_html__( 'Standard', 'qi-addons-for-elementor' ),
						'icon-boxed' => esc_html__( 'Icon Boxed', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'standard',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'info_button_size',
					'title'      => esc_html__( 'Size', 'qi-addons-for-elementor' ),
					'options'    => array(
						''      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
						'small' => esc_html__( 'Small', 'qi-addons-for-elementor' ),
						'large' => esc_html__( 'Large', 'qi-addons-for-elementor' ),
						'full'  => esc_html__( 'Normal Full Width', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'info_button_text',
					'title'         => esc_html__( 'Button Text', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( 'Click Here', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'info_button_subtext',
					'title'         => esc_html__( 'Button Subtext', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( 'See More Here', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'link',
					'name'       => 'info_button_link',
					'title'      => esc_html__( 'Button Link', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'info_button_typography',
					'title'      => esc_html__( 'Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-info-button',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'info_button_subtext_typography',
					'title'      => esc_html__( 'Subtext Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-subtext',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'info_button_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'info_button_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_subtext_color',
					'title'      => esc_html__( 'Subtext Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-subtext' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_background_color',
					'title'      => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button.qodef-layout--filled' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_border_color',
					'title'      => esc_html__( 'Border Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'info_button_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'info_button_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_hover_color',
					'title'      => esc_html__( 'Text Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button:hover' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_subtext_hover_color',
					'title'      => esc_html__( 'Subtext Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button:hover .qodef-m-subtext' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'info_button_subtext_color' => array(
								'values'        => '',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_hover_background_color',
					'title'      => esc_html__( 'Background Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button.qodef-layout--filled:hover'   => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-qi-info-button.qodef-layout--outlined:hover' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_hover_border_color',
					'title'      => esc_html__( 'Border Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button:hover' => 'border-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'info_button_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'info_button_style_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_info_button_tab_style',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'info_button_border_width',
					'title'      => esc_html__( 'Border Width', 'qi-addons-for-elementor' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'info_button_border_radius',
					'title'      => esc_html__( 'Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'info_button_padding',
					'title'      => esc_html__( 'Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-info-button.qodef-type--icon-boxed .qodef-m-text-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-info-button.qodef-type--icon-boxed .qodef-m-icon' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			//Icon options
			$this->set_option(
				array(
					'field_type' => 'icons',
					'name'       => 'info_button_icon',
					'title'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'info_button_icon_position',
					'title'         => esc_html__( 'Icon Position', 'qi-addons-for-elementor' ),
					'options'       => array(
						'left'  => esc_html__( 'Left', 'qi-addons-for-elementor' ),
						'right' => esc_html__( 'Right', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'right',
					'group'         => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'info_button_icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', 'rem', 'vw' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'info_button_icon_style_tabs',
					'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'info_button_icon_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			//Icon Background Options (only for icon boxed, when filled and outline layout is chosen)
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_icon_background_color',
					'title'      => esc_html__( 'Icon Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button.qodef-type--icon-boxed .qodef-m-icon' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'info_button_type' => array(
								'values'        => 'icon-boxed',
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'info_button_tab_icon_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'info_button_icon_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_icon_hover_color',
					'title'      => esc_html__( 'Icon Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button:hover .qodef-m-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'info_button_icon_color' => array(
								'values'        => '',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_icon_background_hover_color',
					'title'      => esc_html__( 'Icon Background Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button.qodef-type--icon-boxed:hover .qodef-m-icon' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'info_button_type' => array(
								'values'        => 'icon-boxed',
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'info_button_icon_hover_move',
					'title'      => esc_html__( 'Move Icon', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						'move-horizontal-short' => esc_html__( 'Horizontal Short', 'qi-addons-for-elementor' ),
						'move-horizontal'       => esc_html__( 'Horizontal', 'qi-addons-for-elementor' ),
						'move-vertical'         => esc_html__( 'Vertical', 'qi-addons-for-elementor' ),
						'move-diagonal'         => esc_html__( 'Diagonal', 'qi-addons-for-elementor' ),
						''                      => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'info_button_icon_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'info_button_icon_style_tabs_end',
					'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'info_button_icon_margin_divider',
					'title'      => esc_html__( 'Icon Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'info_button_icon_margin',
					'title'              => esc_html__( 'Icon Margin', 'qi-addons-for-elementor' ),
					'group'              => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'allowed_dimensions' => array( 'left', 'right' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-m-icon'   => 'margin: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'info_button_icon_border_divider',
					'title'      => esc_html__( 'Icon Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			//Icon Side Border Options (only for icon boxed, when filled and outline layout is chosen)
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'info_button_icon_enable_side_border',
					'title'         => esc_html__( 'Enable Icon Side Border', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'dependency'    => array(
						'show' => array(
							'info_button_type' => array(
								'values'        => 'icon-boxed',
								'default_value' => 'standard',
							),
						),
					),
					'group'         => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_icon_side_color',
					'title'      => esc_html__( 'Icon Side Border Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-border' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'info_button_icon_enable_side_border' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_button_icon_side_hover_color',
					'title'      => esc_html__( 'Icon Side Border Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-info-button.qodef-type--icon-boxed:hover .qodef-m-border' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'info_button_icon_enable_side_border' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'info_button_icon_side_height',
					'title'      => esc_html__( 'Icon Side Border Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-border' => 'height: {{SIZE}}{{UNIT}}; align-self: center;',
					),
					'dependency' => array(
						'show' => array(
							'info_button_icon_enable_side_border' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'info_button_icon_side_width',
					'title'      => esc_html__( 'Icon Side Border Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'responsive' => true,
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 10,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-border' => 'width: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'info_button_icon_enable_side_border' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'array',
					'name'       => 'custom_attrs',
					'title'      => esc_html__( 'Custom Data Attributes', 'qi-addons-for-elementor' ),
					'visibility' => array(
						'map_for_page_builder' => false,
					),
				)
			);
		}

		public function load_assets() {
			parent::load_assets();

			qi_addons_for_elementor_icon_load_assets();
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_info_button', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {

			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes']  = $this->get_holder_classes( $atts );
			$atts['data_attrs']      = $this->get_data_attrs( $atts );
			$atts['icon_classes']    = $this->get_icon_classes( $atts );
			$atts['subtext_classes'] = $this->get_subtext_classes( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/info-button', 'variations/' . $atts['info_button_type'] . '/templates/button', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-info-button';
			$holder_classes[] = 'qodef-html--link';
			$holder_classes[] = ! empty( $atts['info_button_layout'] ) ? 'qodef-layout--' . $atts['info_button_layout'] : '';
			$holder_classes[] = ! empty( $atts['info_button_type'] ) ? 'qodef-type--' . $atts['info_button_type'] : '';
			$holder_classes[] = ! empty( $atts['info_button_size'] ) ? 'qodef-size--' . $atts['info_button_size'] : '';
			$holder_classes[] = ! empty( $atts['info_button_icon_position'] ) ? 'qodef-icon--' . $atts['info_button_icon_position'] : '';
			$holder_classes[] = ! empty( $atts['info_button_icon_hover_move'] ) ? 'qodef-hover--icon-' . $atts['info_button_icon_hover_move'] : '';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $atts ) {
			$data = qi_addons_for_elementor_get_link_attributes( $atts['info_button_link'] );

			if ( ! empty( $atts['custom_attrs'] ) && is_array( $atts['custom_attrs'] ) ) {
				$data = array_merge( $data, $atts['custom_attrs'] );
			}

			return $data;
		}

		private function get_icon_classes( $atts ) {
			$icon_classes[] = 'qodef-m-icon';
			$icon_classes[] = ! empty( $atts['info_button_icon_color'] ) ? 'qodef--icon-color-set' : '';

			return implode( ' ', $icon_classes );
		}

		private function get_subtext_classes( $atts ) {
			$icon_classes[] = 'qodef-m-subtext';
			$icon_classes[] = ! empty( $atts['info_button_subtext_color'] ) ? 'qodef--subtext-color-set' : '';

			return implode( ' ', $icon_classes );
		}
	}
}
