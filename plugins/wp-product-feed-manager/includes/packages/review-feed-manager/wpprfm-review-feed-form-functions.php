<?php
/**
 * Form functions and hooks.
 *
 * @package WP Google Product Review Feed Manager/Functions
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Google Review Feed specific sources to the source selectors.
 *
 * @param array $standard_sources   Array with all the standard sources.
 *
 * @return array    Array with the sources.
 */
function wpprfm_add_review_specific_sources( $standard_sources ) {

	$additional_sources = WPPRFM_Feed_Sources::review_feed_specific_sources();

	foreach ( $additional_sources as $additional_source ) {
		$standard_sources[] = $additional_source;
	}

	return $standard_sources;
}

add_filter( 'wppfm_all_source_fields', 'wpprfm_add_review_specific_sources' );

/**
 * Sets the correct Google Review Feed Header.
 *
 * @param string $header        Original header text.
 * @param string $feed_id       The feed id.
 * @param string $feed_type_id  The feed type id.
 *
 * @return string   String with the new Google Review Feed header.
 */
function wpprfm_header_string( $header, $feed_id, $feed_type_id ) {
	return '2' === $feed_type_id ? WPPRFM_Feed_File_Element::google_review_feed_header_element( $feed_id ) : $header;
}

add_filter( 'wppfm_header_string', 'wpprfm_header_string', 10, 3 );

/**
 * Sets the correct Google Review Feed Footer.
 *
 * @param string $footer        The original footer text.
 * @param string $feed_id       The feed id.
 * @param string $feed_type_id  The feed type id.
 *
 * @return string   The html code for the review feed footer.
 */
function wpprfm_footer_string( $footer, $feed_id, $feed_type_id ) {
	return '2' === $feed_type_id ? WPPRFM_Feed_File_Element::google_review_feed_footer_element() : $footer;
}

add_filter( 'wppfm_footer_string', 'wpprfm_footer_string', 10, 3 );

/**
 * Starts the Google Product Review Feed page when clicked on the tab
 */
function wpprfm_open_review_feed_page() {

	$review_page = new WPPRFM_Add_Review_Feed_Editor_Page();
	$review_page->show();
}
