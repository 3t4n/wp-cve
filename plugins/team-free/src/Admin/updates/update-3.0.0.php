<?php
/**
 * Update version.
 */
update_option( 'sp_wp_team_version', '3.0.0' );
update_option( 'sp_wp_team_db_version', '3.0.0' );

/**
 * Update the filter all btn text.
 */
$shortcode_ids = get_posts(
	array(
		'post_type'      => array( 'sptp_member', 'sptp_generator' ),
		'post_status'    => 'any',
		'posts_per_page' => '9999',
		'fields'         => 'ids',
	)
);
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {

		$shortcode_data = get_post_meta( $shortcode_id, '_sptp_generator', true );

		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}
		$layout = get_post_meta( $shortcode_id, '_sptp_generator_layout', true );

		$old_list_style                               = isset( $shortcode_data['style_member_content_position_list'] ) ? $shortcode_data['style_member_content_position_list'] : '';
		$layout['style_member_content_position_list'] = $old_list_style;

		if ( isset( $shortcode_data['border_bg_around_member']['border_around_member']['border_radius'] ) ) {
			$border_around_member = $shortcode_data['border_bg_around_member']['border_around_member'];
			$shortcode_data['border_bg_around_member']['border_around_member_border_radius'] = array(
				'all'  => $border_around_member['border_radius'],
				'unit' => 'px',
			);
		}
		$old_carousel_navigation_switch                                    = isset( $shortcode_data['carousel_navigation'] ) ? $shortcode_data['carousel_navigation'] : '';
		$shortcode_data['carousel_navigation_data']['carousel_navigation'] = $old_carousel_navigation_switch;

		$old_carousel_pagination_switch                                     = isset( $shortcode_data['carousel_pagination'] ) ? $shortcode_data['carousel_pagination'] : '';
		$shortcode_data['carousel_pagination_group']['carousel_pagination'] = $old_carousel_pagination_switch;

		if ( isset( $shortcode_data['border_bg_around_member']['border_around_member']['border_radius'] ) ) {
			$border_around_member = $shortcode_data['border_bg_around_member']['border_around_member'];
			$shortcode_data['border_bg_around_member']['border_around_member_border_radius'] = array(
				'all'  => $border_around_member['border_radius'],
				'unit' => 'px',
			);
		}

		update_post_meta( $shortcode_id, '_sptp_generator_layout', $layout );
		update_post_meta( $shortcode_id, '_sptp_generator', $shortcode_data );
	}

	// Get the '_sptp_settings' option value.
	$sptp_settings = get_option( '_sptp_settings' );

	// Check if the option exists and is not empty.
	if ( $sptp_settings && isset( $sptp_settings['detail_page_fields'] ) ) {
		// Extract detail page fields.
		$detail_fields = $sptp_settings['detail_page_fields'];

		// Update 'name_switch' field.
		$detail_fields['name_switch'] = in_array( 'name', $detail_fields, true ) ? true : false;
		// Update 'image_switch' field.
		$detail_fields['image_switch'] = in_array( 'img', $detail_fields, true ) ? true : false;
		// Update 'bio_switch' field.
		$detail_fields['bio_switch'] = in_array( 'desc', $detail_fields, true ) ? true : false;
		// Update 'job_position_switch' field.
		$detail_fields['job_position_switch'] = in_array( 'position', $detail_fields, true ) ? true : false;
		// Update 'social_switch' field.
		$detail_fields['social_switch'] = in_array( 'social_profiles', $detail_fields, true ) ? true : false;

		// Check if the option exists and is not empty.
		if ( isset( $sptp_settings['rename_team'] ) && 'Team' === $sptp_settings['rename_team'] ) {
			$sptp_settings['rename_team'] = 'Teams';
		}

		// Update the '_sptp_settings' option with the modified values.
		$sptp_settings['detail_page_fields'] = $detail_fields;
		update_option( '_sptp_settings', $sptp_settings );
	}
}

