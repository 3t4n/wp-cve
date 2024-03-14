<?php
update_option( 'sp_wp_team_version', '2.2.13' );
update_option( 'sp_wp_team_db_version', '2.2.13' );

/**
 * Update the filter all btn text.
 */
$shortcode_ids = get_posts(
	array(
		'post_type'      => 'sptp_generator',
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

		$border_radius_around_member = isset( $shortcode_data['border_bg_around_member']['border_radius_around_member'] ) ? $shortcode_data['border_bg_around_member']['border_radius_around_member'] : 0;

		if ( isset( $shortcode_data['border_bg_around_member']['border_radius_around_member'] ) ) {
			$shortcode_data['border_bg_around_member']['border_around_member']['radius'] = $border_radius_around_member;
		}

		$style_margin_between_member = isset( $shortcode_data['style_margin_between_member']['all'] ) ? $shortcode_data['style_margin_between_member']['all'] : 24;

		if ( isset( $shortcode_data['style_margin_between_member']['all'] ) ) {
			$shortcode_data['style_margin_between_member']['top-bottom'] = $style_margin_between_member;
			$shortcode_data['style_margin_between_member']['left-right'] = $style_margin_between_member;
		}

		update_post_meta( $shortcode_id, '_sptp_generator', $shortcode_data );
	}
}
