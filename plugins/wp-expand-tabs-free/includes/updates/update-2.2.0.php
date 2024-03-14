<?php
/**
 * Update version.
 *
 * @package    WP_Tabs
 */

update_option( 'wp_tabs_version', WP_TABS_VERSION );
update_option( 'wp_tabs_db_version', WP_TABS_VERSION );

$args          = new WP_Query(
	array(
		'post_type'      => 'sp_wp_tabs',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );

if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_id ) {
		$sptpro_shortcode_options = get_post_meta( $shortcode_id, 'sp_tab_shortcode_options', true );

		if ( ! is_array( $sptpro_shortcode_options ) ) {
			continue;
		}

		// Margin bottom from the section title.
		$sptpro_section_title_margin_bottom_old = isset( $sptpro_shortcode_options['sptpro_section_title_margin_bottom']['all'] ) ? $sptpro_shortcode_options['sptpro_section_title_margin_bottom']['all'] : '';
		if ( isset( $sptpro_shortcode_options['sptpro_section_title_typo'] ) ) {
			$sptpro_shortcode_options['sptpro_section_title_typo']['margin-bottom'] = $sptpro_section_title_margin_bottom_old;
		}

		// Border Radius around tabs.
		$sptpro_tab_border_radius_old = isset( $sptpro_shortcode_options['sptpro_tab_border_radius']['all'] ) ? $sptpro_shortcode_options['sptpro_tab_border_radius']['all'] : '';
		if ( isset( $sptpro_shortcode_options['sptpro_tabs_border'] ) ) {
			$sptpro_shortcode_options['sptpro_tabs_border']['border_radius'] = $sptpro_tab_border_radius_old;
		}
		update_post_meta( $shortcode_id, 'sp_tab_shortcode_options', $sptpro_shortcode_options );
	}
}
