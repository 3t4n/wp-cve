<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_variation_info_on_hover_inset' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_variation_info_on_hover_inset( $variations ) {
		$variations['info-on-hover-inset'] = esc_html__( 'Info on Hover Inset', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_team_member_layouts', 'qi_addons_for_elementor_add_team_member_variation_info_on_hover_inset' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_options_info_on_hover_inset' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_options_info_on_hover_inset( $options ) {
		$info_on_hover_inset_options = array();

		$text = array(
			'field_type' => 'textarea',
			'name'       => 'info_on_hover_inset_text',
			'title'      => esc_html__( 'Text', 'qi-addons-for-elementor' ),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
		);

		$text_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_hover_inset_text_color',
			'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-text' => 'color: {{VALUE}};',
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$text_typography = array(
			'field_type' => 'typography',
			'name'       => 'info_on_hover_inset_typography',
			'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
			'selector'   => '{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-text',
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$text_margin_bottom = array(
			'field_type' => 'slider',
			'name'       => 'info_on_hover_inset_margin_bottom',
			'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$background_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_hover_inset_background_color',
			'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-content' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$vertical_aligment = array(
			'field_type'    => 'select',
			'name'          => 'info_on_hover_inset_content_vertical_align',
			'title'         => esc_html__( 'Content Vertical Alignment', 'qi-addons-for-elementor' ),
			'options'       => array(
				'flex-start' => esc_html__( 'Top', 'qi-addons-for-elementor' ),
				'center'     => esc_html__( 'Middle', 'qi-addons-for-elementor' ),
				'flex-end'   => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
			),
			'default_value' => 'center',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-content' => 'justify-content: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
			'group'         => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$horizontal_alignment = array(
			'field_type'    => 'choose',
			'name'          => 'info_on_hover_inset_content_horizontal_align',
			'title'         => esc_html__( 'Content Horizontal Alignment', 'qi-addons-for-elementor' ),
			'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
			'default_value' => 'center',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-content' => 'text-align: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
			'group'         => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_on_hover_inset_content_padding',
			'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_positioning = array(
			'field_type' => 'dimensions',
			'name'       => 'info_on_hover_inset_content_positioning',
			'title'      => esc_html__( 'Content Positioning', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-content' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);
		$content_offset = array(
			'field_type' => 'slider',
			'name'       => 'info_on_hover_inset_content_inset',
			'title'      => esc_html__( 'Content Background Inner Offset', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-hover-inset .qodef-m-inner:hover .qodef-m-content' => 'clip-path: inset({{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}});',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover-inset',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$info_on_hover_inset_options[] = $text;
		$info_on_hover_inset_options[] = $text_color;
		$info_on_hover_inset_options[] = $text_typography;
		$info_on_hover_inset_options[] = $text_margin_bottom;
		$info_on_hover_inset_options[] = $background_color;
		$info_on_hover_inset_options[] = $vertical_aligment;
		$info_on_hover_inset_options[] = $horizontal_alignment;
		$info_on_hover_inset_options[] = $content_padding;
		$info_on_hover_inset_options[] = $content_positioning;
		$info_on_hover_inset_options[] = $content_offset;

		return array_merge( $options, $info_on_hover_inset_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_team_member_extra_options', 'qi_addons_for_elementor_add_team_member_options_info_on_hover_inset' );
}
