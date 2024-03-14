<?php
update_option( 'sp_wp_team_version', '2.2.15' );
update_option( 'sp_wp_team_db_version', '2.2.15' );

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

		$old_section_title_margin_bottom = isset( $shortcode_data['style_title_margin_bottom']['bottom'] ) ? $shortcode_data['style_title_margin_bottom']['bottom'] : 25;

		$shortcode_data['typo_team_title']['margin-bottom'] = $old_section_title_margin_bottom;

		update_post_meta( $shortcode_id, '_sptp_generator', $shortcode_data );
	}
}
