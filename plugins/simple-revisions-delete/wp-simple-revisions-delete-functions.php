<?php
/**
 * SECURITY : Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed!' );
}

/**
 * Print Style in admin header
 */
add_action( 'admin_print_styles-post-new.php', 'wpsrd_add_admin_style' );
add_action( 'admin_print_styles-post.php', 'wpsrd_add_admin_style' );
function wpsrd_add_admin_style() {
	echo '
	<style>
		#wpsrd-clear-revisions,
		.wpsrd-no-js {
			display:none;
		}
		.wpsrd-loading { 
			display:none; 
			background-image: url(' . admin_url( 'images/spinner-2x.gif' ) . '); 
			display: none; 
			width: 18px; 
			height: 18px; 
			background-size: cover; 
			margin: 0 0 -5px 4px;
		}
		.edit-post-last-revision__panel{
			position: relative;
		}
		.edit-post-last-revision__panel #wpsrd-clear-revisions{
			padding: 13px 16px;
			position: absolute;
			top: 0;
			right: 0;
		}
		#wpsrd-clear-revisions .wpsrd-link.sucess { 
			color: #444;
			font-weight: 600;
		}
		#wpsrd-clear-revisions .wpsrd-link.error { 
			display: block
			color: #a00;
			font-weight: normal;
		}
		.wpsrd-no-js:before {
			color: #888;
			content: "\f182";
			font: 400 20px/1 dashicons;
			speak: none;
			display: inline-block;
			padding: 0 2px 0 0;
			top: 0;
			left: -1px;
			position: relative;
			vertical-align: top;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			text-decoration: none!important;
		}
		.wp-core-ui .action.wpsrd-btn {
			display: inline-block;
			margin-left: 10px;
		}
	</style>
	<noscript>
		<style>
			.wpsrd-no-js {
				display:block;
			}
		</style>
	</noscript>
	';
}

/**
 * Check if revisions are activated on plugin load
 */
register_activation_hook( __FILE__, 'wpsrd_norev_check' );
function wpsrd_norev_check() {
	if ( ! WP_POST_REVISIONS ) {
		//Keep in memory if revisions are deactivated
		set_transient( 'wpsrd_norev', true, 0 );
	}
}

/**
 * Display the notice if revisions are deactivated
 */
add_action( 'admin_notices', 'wpsrd_norev_notice' );
function wpsrd_norev_notice() {
	if ( current_user_can( 'activate_plugins' ) && ! WP_POST_REVISIONS ) {
		// Exit if no notice
		if ( ! ( get_transient( 'wpsrd_norev' ) ) ) {
			return;
		}

		//Build the dismiss notice link
		$dismiss = '
			<a class="wpsrd-dismiss" href="' . admin_url( 'admin-post.php?action=wpsrd_norev_dismiss' ) . '" style="float: right; text-decoration: none;">
				' . __( 'Dismiss' ) . '<span class="dashicons dashicons-no-alt"></span>
			</a>
		';

		//Prepare the notice
		add_settings_error(
			'wpsrd-admin-norev',
			'wpsrd_norev',
			__( 'Revisions are deactivated on this site, the plugin "Simple Revisions Delete" has no reason to be installed.', 'simple-revisions-delete' ) . ' ' . $dismiss,
			'error'
		);

		//Display the notice
		settings_errors( 'wpsrd-admin-norev' );
	}
}

/**
 * Dismiss the notice if revisions are deactivated
 */
add_action( 'admin_post_wpsrd_norev_dismiss', 'wpsrd_norev_dismiss' );
function wpsrd_norev_dismiss() {
	// Only redirect if accesed direclty & transients has already been deleted
	if ( ( get_transient( 'wpsrd_norev' ) ) ) {
		delete_transient( 'wpsrd_norev' );
	}

	//Redirect to previous page
	wp_safe_redirect( wp_get_referer() );
}

/**
 * Admin enqueue script
 */
add_action( 'admin_enqueue_scripts', 'wpsrd_add_admin_scripts', 10, 1 );
add_action( 'init', 'wpsrd_gutenberg_register' );
add_action( 'enqueue_block_editor_assets', 'wpsrd_gutenberg_enqueue' );

function wpsrd_add_admin_scripts( $page ) {
	if ( $page == 'post-new.php' || $page == 'post.php' ) {
		wp_enqueue_script( 'wpsrd_admin_js', plugin_dir_url( __FILE__ ) . 'js/wpsrd-admin-script.js', array( 'jquery' ), '1.5' );
	}
}

function wpsrd_gutenberg_register() {
	wp_register_script( 'wpsrd_gutenberg_admin_js', plugin_dir_url( __FILE__ ) . 'js/wpsrd-gutenberg-script.js', array( 'jquery' ), '1.5' );
}

function wpsrd_gutenberg_enqueue() {
	wp_enqueue_script( 'wpsrd_gutenberg_admin_js' );
}

/**
 * Check if current admin screen is using the block editor
 */
function wpsrd_is_gutenberg_page() {
	// The Gutenberg plugin is on.
	if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
		return true;
	}

	// Gutenberg page on 5+.
	$current_screen = get_current_screen();
	if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
		return true;
	}

	return false;
}

/**
 * Post types supported list
 */
function wpsrd_post_types_default() {
	$postTypes        = array( 'post', 'page' );
	return $postTypes = apply_filters( 'wpsrd_post_types_list', $postTypes );
}

/**
 * Display admin notice after purging revisions
 */
add_action( 'admin_notices', 'wpsrd_notice_display', 0 );
function wpsrd_notice_display() {

	// Exit if no notice
	if ( ! ( $notices = get_transient( 'wpsrd_settings_errors' ) ) ) {
		return;
	}

	$notice_code = array( 'wpsrd_notice', 'wpsrd_notice_WP_error' );

	//Rebuild the notice
	foreach ( (array) $notices as $notice ) {
		if ( isset( $notice['code'] ) && in_array( $notice['code'], $notice_code ) ) {
			add_settings_error(
				$notice['setting'],
				$notice['code'],
				$notice['message'],
				$notice['type']
			);
		}
	}

	//Display the notice
	settings_errors( $notice['setting'] );

	// Remove the transient after displaying the notice
	delete_transient( 'wpsrd_settings_errors' );

}
