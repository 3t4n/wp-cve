<?php

/* --------------------------------------------------------- */
/* !Load the admin scripts - 1.1.9 */
/* --------------------------------------------------------- */

function mtphr_members_admin_scripts( $hook ) {

	global $typenow;
	
	if ( $typenow == 'mtphr_member' && in_array($hook, array('post-new.php', 'post.php', 'mtphr_member_page_mtphr_members_settings_menu')) ) {

		// Load scipts for the media uploader
		if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
		} else {
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		}
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-tabs' );

		// Load the scripts		
		wp_register_script( 'mtphr-members', MTPHR_MEMBERS_URL.'/assets/js/script-admin.js', false, MTPHR_MEMBERS_VERSION, true );
		wp_enqueue_script( 'mtphr-members' );
	}

	// Load the style sheet
	wp_register_style( 'mtphr-members-admin', MTPHR_MEMBERS_URL.'/assets/css/style-admin.css', false, MTPHR_MEMBERS_VERSION );
	wp_enqueue_style( 'mtphr-members-admin' );
	
	// Shortcode generator
	wp_register_script( 'mtphr-members-sc-gen', MTPHR_MEMBERS_URL.'/assets/js/admin/generator.js', array('jquery'), MTPHR_MEMBERS_VERSION, true );
	wp_enqueue_script( 'mtphr-members-sc-gen' );
}
add_action( 'admin_enqueue_scripts', 'mtphr_members_admin_scripts', 11 );




add_action( 'wp_enqueue_scripts', 'mtphr_members_scripts' );
/**
 * Load the front end scripts
 *
 * @since 1.0.5
 */
function mtphr_members_scripts() {

	// Load the style sheet
	wp_register_style( 'mtphr-members', MTPHR_MEMBERS_URL.'/assets/css/style.css', false, MTPHR_MEMBERS_VERSION );
	wp_enqueue_style( 'mtphr-members' );

	wp_register_script( 'respond', MTPHR_MEMBERS_URL.'/assets/js/respond.min.js', array('jquery'), MTPHR_MEMBERS_VERSION, true );
	wp_enqueue_script( 'respond' );
}



