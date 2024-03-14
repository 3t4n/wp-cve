<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

function yahman_addons_delete_plugin() {

	$option = get_option('yahman_addons');
	if(!isset($option['other']['delete_all'])) return;

	delete_option( 'yahman_addons' );
	delete_option( 'yahman_addons_version' );
	delete_option( 'yahman_addons_count' );
	delete_option( 'yahman_addons_external_cache' );

	$allposts = get_posts( 'numberposts=-1&post_type=any&post_status=any' );
	$period_name = array('all','yearly','monthly','weekly','daily');
	foreach( $allposts as $postinfo ) {
		foreach ($period_name as $key) {
			delete_post_meta( $postinfo->ID, '_yahman_addons_pv_'.$key);
			delete_post_meta( $postinfo->ID, '_yahman_addons_coverage_period_'.$key);
		}
		
		delete_transient( 'ya_amp_cache_' . $postinfo->ID );
		delete_transient( 'ya_faster_cache_' . $postinfo->ID );
	}

	$dir = WP_CONTENT_DIR.'/uploads/yahman_addons_cache/';
	if ( file_exists($dir) ) {
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		global $wp_filesystem;
		if ( WP_Filesystem() ) {
			if ( $wp_filesystem->is_dir($dir) ) {
				$wp_filesystem->delete($dir,true);
			}
		}
	}
}

yahman_addons_delete_plugin();
