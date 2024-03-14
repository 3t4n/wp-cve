<?php


// -- Include css files
if ( ! function_exists('gs_twitter_feed_styles') ) {
	function gs_twitter_feed_styles() {
		if (!is_admin()) {
			$media = 'all';

			wp_register_style('gstwitter-vendors-css', GSTWF_FILES_URI . '/css/gstw_vendors.min.css','', GSTWF_VERSION, $media);
			wp_enqueue_style('gstwitter-vendors-css');

			//Plugin main stylesheet
			wp_register_style('gs_twitter_csutom_css', GSTWF_FILES_URI . '/css/gs-twitter-custom.css',' ', GSTWF_VERSION, $media);
			wp_enqueue_style('gs_twitter_csutom_css');			
		}
	}
	add_action( 'init', 'gs_twitter_feed_styles' );
}

// -- Admin css
function gstwitter_enque_admin_style() {
    $media = 'all';

	wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-effects-core');

    wp_register_script( 'gs_tw_SwitchButtonJs', GSTWF_FILES_URI . '/admin/js/gstw.jquery.switchButton.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-effects-core' ), GSTWF_VERSION, false );
    wp_enqueue_script('gs_tw_SwitchButtonJs');

    wp_register_style( 'gs-tw-free-plugin-style', GSTWF_FILES_URI . '/admin/css/gs_free_plugins.css', '', GSTWF_VERSION, $media );
    wp_enqueue_style( 'gs-tw-free-plugin-style' );

    wp_register_style( 'gstwitter-admin-style', GSTWF_FILES_URI . '/admin/css/gstw_admin_style.css', '', GSTWF_VERSION, $media );
    wp_enqueue_style( 'gstwitter-admin-style' );
}
add_action( 'admin_enqueue_scripts', 'gstwitter_enque_admin_style' );