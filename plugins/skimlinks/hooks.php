<?php

//hook to insert js code in footer
add_action( 'wp_footer', 'sl_footer_js' );

//hook into switch theme to check if the new theme works with the skimlinks js footer
add_action( 'switch_theme', 'sl_verify_footer_js' );

//hook into the RSS feed to replace external links with skimlinks ones (only if option is selected)
add_action( 'template_redirect', 'sl_rss_filtering_hooks' );
function sl_rss_filtering_hooks() {
	if( sl_is_rss_filtering_enabled() ) {
		global $wp_version;
		
		//versions older than 2.9 do not call the 'the_content_rss' filter, so it must be check manually
		if( version_compare( $wp_version, '2.9', '<' ) ) {
			if( is_feed() )
				add_filter( 'the_content', 'sl_modify_enternal_links' );
		} else {
			add_filter( 'the_content_rss', 'sl_modify_enternal_links' );
			add_filter( 'the_content_feed', 'sl_modify_enternal_links' );
		}
	}
}

// hook to append disclosure badge to content (if checked)
if( sl_is_disclosure_badge_appended() && sl_is_plugin_configured() ) {
	add_filter( 'the_content', 'sl_add_disclosure_badge_to_content' );
}