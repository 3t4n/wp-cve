<?php
/* wppa-admin.php
* Package: wp-photo-album-plus
*
* Contains the admin menu and startups the admin pages
* Version: 8.6.01.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly (2)" );

/* CHECK INSTALLATION */
// Check setup
add_action ( 'init', 'wppa_setup', '8' );	// admin_init

/* ADMIN MENU */
add_action( 'admin_menu', 'wppa_add_admin' );

function wppa_add_admin() {
	global $wp_roles;
	global $wpdb;

	// Make sure admin has access rights
	if ( wppa_user_is_admin() ) {
		$wp_roles->add_cap( 'administrator', 'wppa_admin' );
		$wp_roles->add_cap( 'administrator', 'wppa_upload' );
		$wp_roles->add_cap( 'administrator', 'wppa_import' );
		$wp_roles->add_cap( 'administrator', 'wppa_moderate' );
		$wp_roles->add_cap( 'administrator', 'wppa_export' );
		$wp_roles->add_cap( 'administrator', 'wppa_settings' );
		$wp_roles->add_cap( 'administrator', 'wppa_edit_email' );
		$wp_roles->add_cap( 'administrator', 'wppa_comments' );
		$wp_roles->add_cap( 'administrator', 'wppa_edit_tags' );
		$wp_roles->add_cap( 'administrator', 'wppa_edit_sequence' );
		$wp_roles->add_cap( 'administrator', 'wppa_help' );
	}

	// See if there are comments pending moderation
	$com_pending = '';
	$com_pending_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE status = 'pending' OR status = 'spam' OR status = ''" );
	if ( $com_pending_count ) $com_pending = '<span class="update-plugins"><span class="plugin-count">' . $com_pending_count . '</span></span>';

	// See if there are uploads pending moderation
	$upl_pending = '';
	$upl_pending_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE status = 'pending' AND album > 0" );
	if ( $upl_pending_count ) $upl_pending = '<span class="update-plugins"><span class="plugin-count">' . $upl_pending_count . '</span></span>';

	// Compute total pending moderation
	$tot_pending = '';
	$tot_pending_count = '0';
	if ( current_user_can('wppa_comments') || current_user_can('wppa_moderate') ) $tot_pending_count += $com_pending_count;
	if ( current_user_can('wppa_admin') || current_user_can('wppa_moderate') ) $tot_pending_count += $upl_pending_count;
	if ( $tot_pending_count ) $tot_pending = '<span class="update-plugins"><span class="plugin-count"><b>' . $tot_pending_count . '</b></span></span>';

	// Add wppa menu
	$icon_url = WPPA_URL . '/img/camera16.png';

	// Main menu Photo Albums
	add_menu_page( 'WP Photo Album', 													// page_title
					_x( 'Photo&thinsp;Albums', 'menu-item', 'wp-photo-album-plus' ) . $tot_pending, 	// menu_title
					'wppa_admin', 														// capability
					'wppa_admin_menu', 													// menu_slug
					'wppa_admin', 														// function
					$icon_url );														// icon_url

	// Albums
	add_submenu_page( 'wppa_admin_menu',  												// parent_slug
						__( 'Albums', 'wp-photo-album-plus' ),			 				// page_title
						_x( 'Albums', 'menu-item', 'wp-photo-album-plus' ),				// menu_title
						'wppa_admin',        											// capability
						'wppa_admin_menu',      										// menu_slug
						'wppa_admin' );													// function

	// Upload *
    add_submenu_page( 'wppa_admin_menu',
						__( 'Upload', 'wp-photo-album-plus' ),          				// page_title
						_x( 'Upload', 'menu-item', 'wp-photo-album-plus' ),         	// menu_title
						'wppa_upload',
						'wppa_upload_photos',
						'wppa_page_upload' ); 											// function

	// Uploader without album admin rights, but when the upload_edit switch set, may edit his own photos *
	if ( ! current_user_can( 'wppa_admin' ) && wppa_opt( 'upload_edit' ) != '-none-' ) {
		add_submenu_page( 'wppa_admin_menu',
						__( 'Edit photos', 'wp-photo-album-plus' ), 	// page_title
						_x( 'Edit', 'menu-item', 'wp-photo-album-plus' ), 	// menu_title
						'wppa_upload',
						'wppa_edit_photo',
						'wppa_edit_photo' );
	}

	// Import *
	add_submenu_page( 'wppa_admin_menu',
						__( 'Import', 'wp-photo-album-plus' ),          // page_title
						_x( 'Import', 'menu-item', 'wp-photo-album-plus' ),          // menu_title
						'wppa_import',
						'wppa_import_photos',
						'wppa_page_import' );

	// Export *
	add_submenu_page( 'wppa_admin_menu',
						__( 'Export', 'wp-photo-album-plus' ),         // page_title
						_x( 'Export', 'menu-item', 'wp-photo-album-plus' ),         // menu_title
						'wppa_export',
						'wppa_export_photos',
						'wppa_page_export' );

	// Comments, only if enabled *
	if ( wppa_switch( 'show_comments' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Comments', 'wp-photo-album-plus' ),      				// page_title
							_x( 'Comments', 'menu-item', 'wp-photo-album-plus' ),  	// menu_title
							'wppa_comments',
							'wppa_manage_comments',
							'wppa_comment_admin' );
	}

	// Moderate uploads *
	if ( $upl_pending )
	add_submenu_page( 'wppa_admin_menu',
						__( 'Moderate uploads', 'wp-photo-album-plus' ),		 // page_title
						_x( 'Moderate uploads', 'menu-item', 'wp-photo-album-plus' ) . $upl_pending,
						'wppa_moderate',
						'wppa_moderate_photos',
						'wppa_page_moderate_uploads' );

	// Moderate comments *
	if ( $com_pending )
	add_submenu_page( 'wppa_admin_menu',
						__( 'Moderate comments', 'wp-photo-album-plus' ),		 // page_title
						_x( 'Moderate comments', 'menu-item', 'wp-photo-album-plus' ) . $com_pending,
						'wppa_moderate',
						'wppa_moderate_comments',
						'wppa_page_moderate_comments' );

	// Settings *
	add_submenu_page( 'wppa_admin_menu',
						__( 'Settings', 'wp-photo-album-plus' ),         // page_title
						_x( 'Settings', 'menu-item', 'wp-photo-album-plus' ),
						'wppa_settings',
						'wppa_options',
						'wppa_page_options' );

	// Search *
	if ( wppa_switch( 'opt_menu_search' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Search', 'wp-photo-album-plus' ),
							_x( 'Search', 'menu-item', 'wp-photo-album-plus' ),
							'wppa_admin',
							'wppa_search',
							'wppa_search' );
	}

	// Tags *
	if ( wppa_switch( 'opt_menu_edit_tags' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Tags', 'wp-photo-album-plus' ),
							_x( 'Tags', 'menu-item', 'wp-photo-album-plus' ),
							'wppa_edit_tags',
							'wppa_edit_tags',
							'wppa_edit_tags' );
	}

	// Sequence *
	if ( wppa_switch( 'opt_menu_edit_sequence' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Sequence', 'wp-photo-album-plus' ),
							_x( 'Sequence', 'menu-item', 'wp-photo-album-plus' ),
							'wppa_edit_sequence',
							'wppa_edit_sequence',
							'wppa_edit_sequence' );

	}

	// Email *
	if ( wppa_switch( 'opt_menu_edit_email' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Manage email subscriptions', 'wp-photo-album-plus' ),
							_x( 'Email', 'menu-item', 'wp-photo-album-plus' ),
							'wppa_edit_email',
							'wppa_edit_email',
							'wppa_edit_email' );
	}

	// Documentation *
	if ( wppa_switch( 'opt_menu_doc' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Documentation', 'wp-photo-album-plus' ),
							_x( 'Documentation', 'menu-item', 'wp-photo-album-plus' ),
							'wppa_help',
							'wppa_help',
							'wppa_page_help' );
	}

	// Logfile *
	if ( wppa_switch( 'logfile_on_menu' ) ) {
		add_submenu_page( 'wppa_admin_menu',
							__( 'Logfile', 'wp-photo-album-plus' ),
							_x( 'Logfile', 'menu-item', 'wp-photo-album-plus' ),
							'administrator',
							'wppa_log',
							'wppa_log_page' );
	}

	// Cache *
	$hits = wppa_get_option( 'wppa_cache_hits', '0' );
	$miss = wppa_get_option( 'wppa_cache_misses', '1' );
	$perc = sprintf( '%5.2f', 100 * $hits / ( $hits + $miss ) );
	add_submenu_page( 'wppa_admin_menu',
							__( 'Cache', 'wp-photo-album-plus' ),
							_x( 'Cache', 'menu-item', 'wp-photo-album-plus' ) . ' ' . $perc . '%',
							'wppa_admin',
							'wppa_cache',
							'wppa_page_cache' );
}

/* ADMIN STYLES */
add_action( 'admin_init', 'wppa_admin_styles' );

function wppa_admin_styles() {
global $wppa_version;

	$ver = filemtime( WPPA_PATH . '/wppa-admin-styles.css' );
	wp_register_style( 'wppa_admin_style', WPPA_URL.'/wppa-admin-styles.css', '', $ver );
	wp_enqueue_style( 'wppa_admin_style' );

	if ( wppa_can_magick() ) {
		wp_register_style( 'wppa_cropper_style', WPPA_URL.'/vendor/cropperjs/dist/cropper.min.css' );
		wp_enqueue_style( 'wppa_cropper_style' );
	}

	// Standard wppa frontend styles
	wp_register_style('wppa_style', WPPA_URL.'/wppa-style.css', array(), $wppa_version);
	wp_enqueue_style('wppa_style');

	$the_css = wppa_create_wppa_dynamic_css();
	wp_add_inline_style( 'wppa_style', $the_css );

	$the_css = '
	.wppa-toplevel-details > summary::before {
        content: "' . __( 'Show', 'wp-photo-album-plus' ) . '";
    }
    .wppa-toplevel-details[open] > summary::before {
        content: "' . __( 'Hide', 'wp-photo-album-plus' ) . '";
    }';
	wp_add_inline_style( 'wppa_style', $the_css );
}

// Standard theme styles, optional
add_action( 'admin_enqueue_scripts', 'theme_styles_for_wppa' );

function theme_styles_for_wppa() {

    if ( wppa_switch( 'admin_theme_css' ) ) {

		// Load theme css
		wp_enqueue_style( 'parent-style-for-wppa', get_template_directory_uri() . '/style.css', array( 'wppa_style' ) );

		// Load child theme css
		wp_enqueue_style( 'child-style-for-wppa', get_stylesheet_uri(), array( 'parent-style-for-wppa', 'wppa_style' ) );
	}

	$the_url = wppa_opt( 'admin_extra_css' );
	if ( $the_url ) {

		// Load extra stylesheet
		wp_enqueue_style( 'extra-style-for-wppa', $the_url, array( 'wppa_style' ) );
	}

	$the_css = wppa_opt( 'admin_inline_css' );
	if ( $the_css ) {

		// Load inline css
		wp_add_inline_style( wppa_switch( 'admin_theme_css' ) ? 'child-style-for-wppa' : 'wppa_style', $the_css );
	}
}

/* ADMIN SCRIPTS */
add_action( 'admin_init', 'wppa_admin_scripts' );

function wppa_admin_scripts() {

	$depts = array(
		'jquery',
		'jquery-ui-sortable',
		'jquery-ui-dialog',
		'jquery-form',
		'jquery-masonry',
		'wp-i18n',
	);

	$js_ver = date( "ymd-Gis", filemtime( WPPA_PATH . '/js/wppa-admin-scripts.js' ) );
	wp_enqueue_script( 'wppa-admin', WPPA_URL . '/js/wppa-admin-scripts.js', $depts, $js_ver, true );
	$js_ver = date( "ymd-Gis", filemtime( WPPA_PATH . '/js/wppa-multifile-compressed.js' ) );
 	wppa_enqueue_script( 'wppa-upload', WPPA_URL . '/js/wppa-multifile-compressed.js', $depts, $js_ver, true );

	if ( wppa_can_magick() ) {
		$js_ver = date( "ymd-Gis", filemtime( WPPA_PATH . '/vendor/cropperjs/dist/cropper.min.js' ) );
		wppa_enqueue_script( 'cropperjs', WPPA_URL . '/vendor/cropperjs/dist/cropper.min.js', $depts, $js_ver );
	}

	wp_enqueue_style( 'wp-jquery-ui-dialog' );
	wppa_enqueue_script( 'wppa-touch-punch', '/wp-includes/js/jquery/jquery.ui.touch-punch.js' );
}

require_once 'wppa-admin-local-js.php';

/* ADMIN PAGE PHP's */

// Album admin page
function wppa_admin() {
	wppa_grant_albums();
	require_once 'wppa-album-admin-autosave.php';
	require_once 'wppa-photo-admin-autosave.php';
	require_once 'wppa-album-covers.php';
	wppa_publish_scheduled();
	_wppa_admin();
}

// Upload admin page
function wppa_page_upload() {
	if ( wppa_is_user_blacklisted() ) wp_die(__( 'Uploading is temporary disabled for you' , 'wp-photo-album-plus' ) );
	wppa_grant_albums();
	require_once 'wppa-upload.php';
	_wppa_page_upload();
}

// Edit photo(s)
function wppa_edit_photo() {
	if ( wppa_is_user_blacklisted() ) wp_die(__( 'Editing is temporary disabled for you' , 'wp-photo-album-plus' ) );
	require_once 'wppa-photo-admin-autosave.php';
	wppa_publish_scheduled();
	_wppa_edit_photo();
}

// Import admin page
function wppa_page_import() {

	// check the user depot directory
	$dir = WPPA_DEPOT_PATH;
	if ( ! wppa_is_dir( $dir ) ) {
		wppa_mktree( $dir );
	}
	wppa_chmod( $dir, true );

	if ( wppa_is_user_blacklisted() ) wp_die(__( 'Importing is temporary disabled for you' , 'wp-photo-album-plus' ) );
	wppa_grant_albums();
	wppa_rename_files_sanitized( WPPA_DEPOT_PATH );
	require_once 'wppa-import.php';
	wppa_load_import_errors();
	_wppa_page_import();
}

// Moderate uploads admin page
function wppa_page_moderate_uploads() {
	require_once 'wppa-photo-admin-autosave.php';
	wppa_publish_scheduled();
	_wppa_moderate_photos( 'photos' );
}

// Moderate comments admin page
function wppa_page_moderate_comments() {
	require_once 'wppa-photo-admin-autosave.php';
	wppa_publish_scheduled();
	_wppa_moderate_photos( 'comments' );
}

// Export admin page
function wppa_page_export() {
	require_once 'wppa-export.php';
	_wppa_page_export();
}

// Settings admin page
function wppa_page_options() {
	require_once 'wppa-settings-autosave.php';
	require_once 'wppa-setting-functions.php';

	// jQuery Easing for Nicescroller
	$easing_url = WPPA_URL . '/vendor/jquery-easing/jquery.easing.min.js';
	wppa_enqueue_script( 'nicescrollr-easing-min-js', $easing_url, array( 'jquery' ), 'all', true );

	// Nicescroll Library
	$nice_url = WPPA_URL . '/vendor/nicescroll/jquery.nicescroll.min.js';
	wppa_enqueue_script( 'nicescrollr-inc-nicescroll-min-js', $nice_url, array( 'jquery', 'nicescrollr-easing-min-js' ), 'all', true );

	_wppa_page_options();
}

// Comments admin page
function wppa_comment_admin() {
	require_once 'wppa-listtable.php';
	require_once 'wppa-comment-admin.php';
	_wppa_comment_admin();
}

// Search
function wppa_search() {
//	wppa_grant_albums();
	require_once 'wppa-album-admin-autosave.php';
	require_once 'wppa-photo-admin-autosave.php';
//	require_once 'wppa-album-covers.php';
	wppa_publish_scheduled();
	_wppa_search();
}

// Help admin page
function wppa_page_help() {
	require_once 'wppa-help.php';
	_wppa_page_help();
}

// Edit tags
function wppa_edit_tags() {
	require_once 'wppa-edit-tags.php';
	_wppa_edit_tags();
}

// Edit sequence
function wppa_edit_sequence() {
	require_once 'wppa-edit-sequence.php';
	require_once 'wppa-photo-admin-autosave.php';
	_wppa_edit_sequence();
}

// Manage emails
function wppa_edit_email() {
	require_once 'wppa-setting-functions.php';
	require_once 'wppa-edit-email.php';
	_wppa_edit_email();
}

/* GENERAL ADMIN */

// General purpose admin functions
require_once 'wppa-admin-functions.php';

// Shortcode generators fdor editors
add_action( 'init', 'wppa_add_scgens', 1 );

function wppa_add_scgens() {

	// Depends on roles
	if ( wppa_show_scgens() ) {
		require_once 'wppa-tinymce-shortcodes.php';
		require_once 'wppa-tinymce-photo.php';

		require_once 'wppa-gutenberg-wppa.php';
	}
}

require_once 'wppa-privacy-policy.php';

/* Add "donate" link to main plugins page */
add_filter('plugin_row_meta', 'wppa_donate_link', 10, 2);

/* Check multisite config */
add_action('admin_notices', 'wppa_verify_multisite_config');

/* Check for pending maintenance procs */
add_action('admin_notices', 'wppa_maintenance_messages');

// Check for configuration conflicts
add_action( 'admin_notices', 'wppa_check_config_conflicts' );

// Check if tags system needs conversion
add_action( 'admin_init', 'wppa_check_tag_system' );

// Check if cats system needs conversion
add_action( 'admin_init', 'wppa_check_cat_system' );

require_once 'wppa-dashboard-widgets.php';

// Load panorama js if needed at the backend
if ( wppa_get_option( 'wppa_enable_panorama' ) == 'yes' ) {
	add_action( 'admin_footer', 'wppa_load_panorama_js' );
}
function wppa_load_panorama_js() {
global $wppa_version;

	if ( wppa( 'has_panorama' ) ) {
		$three_url = WPPA_URL . '/vendor/three/three.min.js';
		$ver = '122';
		wppa_enqueue_script( 'wppa-three-min-js', $three_url, array(), $ver, true );
	}
}

// Register category for Gutenberg
function wppa_block_categories( $categories, $post ) {

    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'wppa-shortcodes',
                'title' => __( 'MEDIA', 'wp-photo-album-plus' ),
            ),
        )
    );
}
add_filter( 'block_categories_all', 'wppa_block_categories', 10, 2 );

// Fix Gutenberg bug and clear cache selectively
function wppa_fix_gutenberg_shortcodes( $id, $post, $update ) {
wppa_log('obs', 'Doing fix gutenberg');
	// Get content
	$post_content = $post->post_content;

	// Fix block type
	$new_content = str_replace( array( 'wp:wppa/gutenberg-photo', 'wp:wppa/gutenberg-wppa' ), 'wp:shortcode', $post_content );
	$new_content = str_replace( '="%23', '="#', $new_content );

	// Update if something changed
	if ( $post_content != $new_content ) {

		$post->post_content = $new_content;
		$iret = wp_update_post( $post );
	}

	// Clear cache for this post
	wppa_clear_cache( ['page' => $id] );

	// Remove used by's for this post
	wppa_remove_usedby( $post->ID );

}
add_action( 'save_post', 'wppa_fix_gutenberg_shortcodes', 1, 3 );

// Delete user stuff when user deleted
function wppa_delete_user( $user_id ) {
global $wpdb;

	if ( wppa_switch( 'clear_vanished_user' ) ) {
		$user = get_user_by( 'ID', $user_id );
		$user_login = $user->user_login;

		$photos = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE owner = %s", $user_login ) );
		if ( $photos ) foreach( $photos as $photo ) {
			wppa_delete_photo( $photo );
		}

		$albums = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE owner = %s", $user_login ) );
		if ( $albums ) foreach( $albums as $album ) {

			// If album is empty: remove it
			$is_empty = ( 0 == $wpdb->get_var( "SELECT COUNT (*) FROM WPPA_PHOTOS WHERE album = $album" ) );
			if ( $is_empty ) {
				wppa_del_row( WPPA_ALBUMS, 'id', $album );
			}

			// Not empty: make it separate
			else {
				wppa_update_album( $album, ['a_parent' => '-1'] );
			}
		}

		// This is not so clever
//		wppa_schedule_maintenance_proc( 'wppa_clear_vanished_user_photos' );
//		wppa_schedule_maintenance_proc( 'wppa_clear_vanished_user_albums' );
	}
}
add_action( 'delete_user', 'wppa_delete_user' );

// If we used fe download before, say they need to reconfigure
if ( get_option( 'wppa_art_monkey_link', 'none' ) != 'none' && get_option( 'wppa_art_monkey_on', 'nil' ) != 'yes' ) {
	add_action('admin_notices', 'wppa_say_fe_reconfig' );
}
elseif ( get_option( 'wppa_art_monkey_link', 'nil' ) != 'nil' ) {
	delete_option( 'wppa_art_monkey_link' );
}

function wppa_say_fe_reconfig() {
	wppa_error_message( __( 'Frontend download must be enabled and reconfigured', 'wp-photo-album-plus' ) . wppa_see_also( 'links', '4' ) );
}