<?php

/**
* @package WP Product Feed Manager/User Interface/Functions
* @version 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gets called when opening an existing feed or on opening the feed form for creating a new Google (Supplemental) feed. It also gets called on the second stage of creating a new feed for a channel other than Google (Supplemental).
 * Returns a string containing the standard header for an admin page.
 *
 * @return string|null
 */
function wppfm_feed_form_sub_header_text() {
	$channel_class = new WPPFM_Channel();
	$data_class    = new WPPFM_Data();

	$feed_id                     = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $_GET['id'] : null;
	$channel_short_name          = $feed_id ? $channel_class->get_channel_short_name_from_feed_id( $feed_id ) : '';
	$feed_type                   = array_key_exists( 'feed-type', $_GET ) ? $_GET['feed-type'] : '';
	$feed_type_name              = wppfm_convert_string_with_dashes_to_upper_case_string_with_spaces( $feed_type );
	$channel_feed_specifications = 'google' === $channel_short_name || str_contains( $feed_type, 'google' ) ? $data_class->get_support_feed_specifications_url( $feed_type ) : $channel_class->get_channel_specifications_link( $channel_short_name );

	$new_feed_text            = 1 > $feed_id ? __( ' Select the products you want in the feed by selecting the correct Shop Category and make sure the required attributes are set correctly', 'wp-product-feed-manager' ) : '';
	$feed_specifications_link = $channel_feed_specifications ? '<br><a href="' . $channel_feed_specifications . '" target="_blank">' . __( 'Click here to view the specifications for this feed.', 'wp-product-feed-manager' ) . '</a>' : '';

	/* translators: %1$s: feed type name, %2$s: Initial description of how to start the feed setup, %3$s: Link to the feed specifications */
	return '' !== $feed_type ? sprintf( __( 'Here you can edit the parameters of your new %1$s.%2$s%3$s', 'wp-product-feed-manager' ), $feed_type_name, $new_feed_text, $feed_specifications_link ) :
		__( 'Here you can set up your new feed. Start by entering a name for your feed and selecting a channel.', 'wp-product-feed-manager' );
}
