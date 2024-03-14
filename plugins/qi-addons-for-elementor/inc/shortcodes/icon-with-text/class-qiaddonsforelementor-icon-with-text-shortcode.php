<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Icon_With_Text_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_icon_with_text_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Icon_With_Text_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_icon_with_text_layouts', array() ) );

			$options_map   = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );
			$default_value = $options_map['default_value'];

			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_icon_with_text_extra_options', array(), $default_value ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/icon-with-text' );
			$this->set_base( 'qi_addons_for_elementor_icon_with_text' );
			$this->set_name( esc_html__( 'Icon with Text', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds icon with text element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Typography', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/icon-with-text/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#icon_with_text' );
			$this->set_video( 'https://www.youtube.com/watch?v=CJGx8d_MPgI' );
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
					'field_type' => 'link',
					'name'       => 'link',
					'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'icons',
					'name'       => 'icon_type',
					'title'      => esc_html__( 'Icon Type', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'enable_separator',
					'title'      => esc_html__( 'Enable Separator', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'title',
					'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'title' ),
					'group'         => esc_html__( 'Content', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h4',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-icon-with-text:hover .qodef-m-title' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-title',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_top',
					'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'html',
					'name'          => 'text',
					'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text(),
					'group'         => esc_html__( 'Content', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-content > .qodef-m-text' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-content > .qodef-m-text',
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
						'{{WRAPPER}} .qodef-m-content > .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'icon_boxed',
					'title'         => esc_html__( 'Icon Boxed', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', true ),
					'default_value' => 'no',
					'group'         => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-m-icon-holder' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_content',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'icon_style_tabs',
					'title'      => esc_html__( 'Icon Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'icon_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-m-icon-holder a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_background_color',
					'title'      => esc_html__( 'Icon Background Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
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
					'name'       => 'icon_border_color',
					'title'      => esc_html__( 'Icon Border Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder' => 'border-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
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
					'name'       => 'icon_stroke_color',
					'title'      => esc_html__( 'Icon Stroke Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder svg' => 'stroke: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'icon_type[library]' => array(
								'values'        => 'svg',
								'default_value' => '',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'icon_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'icon_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_hover_color',
					'title'      => esc_html__( 'Icon Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-icon-with-text:hover .qodef-m-icon-holder'   => 'color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_background_hover_color',
					'title'      => esc_html__( 'Icon Background Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-icon-with-text:hover .qodef-m-icon-holder' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
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
					'name'       => 'icon_border_hover_color',
					'title'      => esc_html__( 'Icon Border Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-icon-with-text:hover .qodef-m-icon-holder'   => 'border-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
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
					'name'       => 'icon_stroke_hover_color',
					'title'      => esc_html__( 'Icon Stroke Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-icon-with-text:hover  .qodef-m-icon-holder svg' => 'stroke: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'icon_type[library]' => array(
								'values'        => 'svg',
								'default_value' => '',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'icon_hover_move',
					'title'      => esc_html__( 'Icon Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						''                => esc_html__( 'None', 'qi-addons-for-elementor' ),
						'move-horizontal' => esc_html__( 'Move Horizontal', 'qi-addons-for-elementor' ),
						'move-vertical'   => esc_html__( 'Move Vertical', 'qi-addons-for-elementor' ),
						'scale'           => esc_html__( 'Scale', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'icon_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'icon_style_tabs_end',
					'title'      => esc_html__( 'Icon End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_icon',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_box_size',
					'title'      => esc_html__( 'Icon Box Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_border_width',
					'title'      => esc_html__( 'Icon Border Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 30,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder' => 'border-width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'icon_border_radius',
					'title'      => esc_html__( 'Icon Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-icon-holder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'icon_boxed' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'icon_stroke_width',
					'title'         => esc_html__( 'Icon Stroke Width', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-m-icon-holder svg' => 'stroke-width: {{VALUE}};',
					),
					'default_value' => 1,
					'dependency'    => array(
						'show' => array(
							'icon_type[library]' => array(
								'values'        => 'svg',
								'default_value' => '',
							),
						),
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
						'{{WRAPPER}} .qodef-m-icon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'button_margin_top',
					'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-button' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_button',
					'exclude'           => array( 'custom_class' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Button', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_separator',
					'exclude'           => array( 'custom_class' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Separator', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'appear_animation',
					'title'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'appear_animation', false ),
					'default_value' => 'none',
					'group'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'appear_delay',
					'title'      => esc_html__( 'Appear Delay', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'appear_delay' ),
					'dependency' => array(
						'hide' => array(
							'appear_animation' => array(
								'values'        => 'none',
								'default_value' => 'none',
							),
						),
					),
					'group'      => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'appear_delay_in_ms',
					'title'         => esc_html__( 'Set Appear Delay', 'qi-addons-for-elementor' ) . ' (ms)',
					'dependency'    => array(
						'show' => array(
							'appear_delay' => array(
								'values'        => 'ms',
								'default_value' => 'default',
							),
						),
					),
					'default_value' => 100,
					'group'         => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
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
			$atts['button_params']    = $this->generate_button_params( $atts );
			$atts['separator_params'] = $this->generate_separator_params( $atts );
			$atts['data_attrs']       = $this->get_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/icon-with-text', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-icon-with-text';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ( 'yes' === $atts['icon_boxed'] ) ? 'qodef-icon-boxed' : '';
			$holder_classes[] = ( 'none' !== $atts['appear_animation'] ) ? 'qodef-qi--has-appear qodef--appear-' . $atts['appear_animation'] : '';
			$holder_classes[] = ! empty( $atts['icon_hover_move'] ) ? 'qodef-icon--hover-' . $atts['icon_hover_move'] : '';

			$icon_class = '';

			if ( ! empty( $atts['icon_type']['value'] ) ) {
				if ( is_string( $atts['icon_type']['value'] ) ) {
					$icon_class = 'icon-pack';
				} else {
					$icon_class = 'custom-icon';
				}
			}

			$holder_classes[] = 'qodef--' . $icon_class;

			$holder_classes = apply_filters( 'qi_addons_for_elementor_filter_icon_with_text_variation_classes', $holder_classes, $atts );

			return implode( ' ', $holder_classes );
		}

		private function generate_button_params( $atts ) {
			$params = array();

			if ( ! empty( $atts['button_text'] ) || ! empty( $atts['button_icon']['value'] ) ) {
				$params = $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_button',
						'exclude'        => array( 'custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return $params;
		}

		private function generate_separator_params( $atts ) {
			$params = array();

			if ( 'yes' === $atts['enable_separator'] ) {
				$params = $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_separator',
						'exclude'        => array( 'custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return $params;
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			if ( 'ms' === $atts['appear_delay'] && $atts['appear_delay_in_ms'] >= 0 ) {
				$data['data-appear-delay'] = $atts['appear_delay_in_ms'];
			} elseif ( 'random' === $atts['appear_delay'] ) {
				$data['data-appear-delay'] = $atts['appear_delay'];
			}

			return $data;
		}
	}
}
