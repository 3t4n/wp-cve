<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_variation_info_from_bottom' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_variation_info_from_bottom( $variations ) {
		$variations['info-from-bottom'] = esc_html__( 'Info from Bottom', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_team_member_layouts', 'qi_addons_for_elementor_add_team_member_variation_info_from_bottom' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_options_info_from_bottom' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_options_info_from_bottom( $options ) {
		$info_from_bottom_options = array();
		$background_color         = array(
			'field_type' => 'color',
			'name'       => 'info_from_bottom_background_color',
			'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-qi-team-member.qodef-item-layout--info-from-bottom .qodef-m-content' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-from-bottom',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$horizontal_alignment = array(
			'field_type'    => 'select',
			'name'          => 'info_from_bottom_content_horizontal_align',
			'title'         => esc_html__( 'Content Horizontal Alignment', 'qi-addons-for-elementor' ),
			'options'       => array(
				'flex-start' => esc_html__( 'Left', 'qi-addons-for-elementor' ),
				'center'     => esc_html__( 'Center', 'qi-addons-for-elementor' ),
				'flex-end'   => esc_html__( 'Right', 'qi-addons-for-elementor' ),
			),
			'default_value' => 'flex-start',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-item-layout--info-from-bottom .qodef-m-content' => 'align-items: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-from-bottom',
						'default_value' => 'default',
					),
				),
			),
			'group'         => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_from_bottom_content_padding',
			'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-from-bottom .qodef-m-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-from-bottom',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$info_from_bottom_options[] = $background_color;
		$info_from_bottom_options[] = $horizontal_alignment;
		$info_from_bottom_options[] = $content_padding;

		return array_merge( $options, $info_from_bottom_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_team_member_extra_options', 'qi_addons_for_elementor_add_team_member_options_info_from_bottom' );
}
