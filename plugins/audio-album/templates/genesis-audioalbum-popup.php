<?php
/**
 * Custom Template for popup page
 * File: genesis-audioalbum-popup.php
 *
 */


/**
* Add custom body class to the head
*
*/
add_filter( 'body_class', 'cc_add_body_popup_class' );

function cc_add_body_popup_class( $classes ) {
	$classes[] = 'pop-up';
return $classes;
}


/**
* Remove header, navigation, breadcrumbs, footer widgets, footer
*
*/
remove_all_actions( 'genesis_before' );
remove_all_actions( 'genesis_after' );
remove_all_actions( 'genesis_before_header' );
remove_all_actions( 'genesis_header' );
remove_all_actions( 'genesis_after_header' );
remove_all_actions( 'genesis_before_content' );
remove_all_actions( 'genesis_after_content' );
remove_all_actions( 'genesis_before_footer' );
remove_all_actions( 'genesis_footer' );
remove_all_actions( 'genesis_after_footer' );
remove_all_actions( 'genesis_before_loop' );
remove_all_actions( 'genesis_after_loop' );

//* HTML5 Hooks
remove_all_actions( 'genesis_before_entry' );
remove_all_actions( 'genesis_after_entry' );
remove_all_actions( 'genesis_before_entry_content' );
remove_all_actions( 'genesis_after_entry_content' );
remove_all_actions( 'genesis_entry_footer' );


/**
* add close button
*
*/
add_action ( 'genesis_after_content', 'cc_close_window' );

function cc_close_window() {
	echo '<a class="close-popup" href="JavaScript:window.close()"><span class="dashicons dashicons-dismiss"></span></a>';
}


/**
* Force full width
*
*/
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );


/**
* Add stylesheet (with dashicons as a dependency)
*
*/
function cc_audioalbum_popup_css() {
	wp_enqueue_style( 'audioalbum-popup', plugin_dir_url( dirname(__FILE__) ) . 'css/audioalbum-popup.css', array('dashicons'), CC_AUDIOALBUM_VERSION );
}

add_action('wp_enqueue_scripts', 'cc_audioalbum_popup_css' );

genesis();