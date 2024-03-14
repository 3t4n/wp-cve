<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_variation_info_below' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_variation_info_below( $variations ) {
		$variations['info-below'] = esc_html__( 'Info Below', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_team_member_layouts', 'qi_addons_for_elementor_add_team_member_variation_info_below' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_options_info_below' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_options_info_below( $options ) {
		$info_below_options = array();
		$margin_option      = array(
			'field_type' => 'slider',
			'name'       => 'info_below_content_margin_top',
			'title'      => esc_html__( 'Content Top Margin', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-qi-team-member.qodef-item-layout--info-below .qodef-m-content' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$alignment = array(
			'field_type' => 'choose',
			'name'       => 'alignment',
			'title'      => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
			'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below' => 'text-align: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below',
						'default_value' => 'default',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$info_below_options[] = $margin_option;
		$info_below_options[] = $alignment;

		return array_merge( $options, $info_below_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_team_member_extra_options', 'qi_addons_for_elementor_add_team_member_options_info_below' );
}
