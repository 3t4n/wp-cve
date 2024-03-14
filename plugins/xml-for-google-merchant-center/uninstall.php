<?php defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
if ( is_multisite() ) {
	$xfgmc_registered_feeds_arr = get_blog_option( get_current_blog_id(), 'xfgmc_registered_feeds_arr' );
	if ( is_array( $xfgmc_registered_feeds_arr ) ) {
		// с единицы, т.к инфа по конкретным фидам там
		for ( $i = 1; $i < count( $xfgmc_registered_feeds_arr ); $i++ ) {
			$feed_id = $xfgmc_registered_feeds_arr[ $i ]['id'];
			delete_blog_option( get_current_blog_id(), 'xfgmc_status_sborki' . $feed_id );
			delete_blog_option( get_current_blog_id(), 'xfgmc_last_element' . $feed_id );
		}
	}
	delete_blog_option( get_current_blog_id(), 'xfgmc_version' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_keeplogs' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_disable_notices' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_enable_five_min' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_feed_content' );

	delete_blog_option( get_current_blog_id(), 'xfgmc_settings_arr' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_registered_feeds_arr' );
} else {
	$xfgmc_registered_feeds_arr = get_option( 'xfgmc_registered_feeds_arr' );
	if ( is_array( $xfgmc_registered_feeds_arr ) ) {
		// с единицы, т.к инфа по конкретным фидам там
		for ( $i = 1; $i < count( $xfgmc_registered_feeds_arr ); $i++ ) {
			$feed_id = $xfgmc_registered_feeds_arr[ $i ]['id'];
			delete_option( 'xfgmc_status_sborki' . $feed_id );
			delete_option( 'xfgmc_last_element' . $feed_id );
		}
	}
	delete_option( 'xfgmc_version' );
	delete_option( 'xfgmc_keeplogs' );
	delete_option( 'xfgmc_disable_notices' );
	delete_option( 'xfgmc_enable_five_min' );
	delete_option( 'xfgmc_feed_content' );

	delete_option( 'xfgmc_settings_arr' );
	delete_option( 'xfgmc_registered_feeds_arr' );
}