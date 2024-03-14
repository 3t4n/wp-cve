<?php

/**
 * Public side of the plugin.
 *
 * @link       http://www.webfactoryltd.com
 * @since      0.1
 */

/* Include important files. */
require_once 'include/functions.php';


function csmm_plugin_init() {
  // just to be on the safe side
  if (defined('DOING_CRON') && DOING_CRON) {
    return false;
  }
  if (defined('DOING_AJAX') && DOING_AJAX) {
    return false;
  }
  if (defined('WP_CLI') && WP_CLI) {
    return false;
  }

  add_action('wp_ajax_csmm_subscribe_hide', 'csmm_subscribe_hide');

	// Plugin options from the database
	$signals_csmm_options = csmm_get_options();

	// Getting custom login URL for the admin
	$signals_login_url = wp_login_url();


	// Checking for the server protocol status
	if ( isset($_SERVER['HTTPS']) === true ) {
		$signals_protocol = 'https';
	} else {
		$signals_protocol = 'http';
	}


	// This is the server address of the current page
	@$signals_server_url = $signals_protocol . '://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
	// Checking for the custom_login_url value
	if ( empty( $signals_csmm_options['custom_login_url'] ) ) {
		$signals_csmm_options['custom_login_url'] = NULL;
	}

    if(isset($_GET['preview_coming_soon']) && current_user_can('administrator')){
        csmm_render_template( $signals_csmm_options );
    }

	// Not for the backend
	// Only modifies the frontend of the system
	if ( ! is_admin() ) {
		if ( '1' == $signals_csmm_options['status'] ) {

			/**
			 * A lot of checks are going on over here.
			 * We are checking for admin role, crawler status, and important wordpress pages to bypass.
			 * If the admin decides to exclude search engine from viewing the plugin, the website will be shown.
			 */

			if ( false === strpos( $signals_server_url, '/wp-login.php' )
				&& false === strpos( $signals_server_url, '/wp-admin/' )
				&& false === strpos( $signals_server_url, '/async-upload.php' )
				&& false === strpos( $signals_server_url, '/upgrade.php' )
				&& false === strpos( $signals_server_url, '/plugins/' )
				&& false === strpos( $signals_server_url, '/xmlrpc.php' )
				&& false === strpos( $signals_server_url, $signals_login_url )
				&& (empty($signals_csmm_options['custom_login_url']) || false === strpos( $signals_server_url, $signals_csmm_options['custom_login_url']) ) ) {

                // Checking for the search engine option
				if ( '1' == $signals_csmm_options['exclude_se'] ) {
                    if ( ! csmm_check_referrer() ) {
						if ( '1' == $signals_csmm_options['show_logged_in'] ) {
							// Checking if the user is logged in or not
							if ( ! is_user_logged_in() ) {
								// Render the maintenance mode template since the user is not logged in
								csmm_render_template( $signals_csmm_options );
							}
						} else {
							// Render the maintenance mode template
							csmm_render_template( $signals_csmm_options );
						}
					}
				} else {
					if ( '1' == $signals_csmm_options['show_logged_in'] ) {
						// Checking if the user is logged in or not
						if ( ! is_user_logged_in() ) {
							// Render the maintenance mode template since the user is not logged in
							csmm_render_template( $signals_csmm_options );
						}
					} else {
						// Render the maintenance mode template.
						csmm_render_template( $signals_csmm_options );
					}
				}
			}
		}
	}
}
add_action( 'init', 'csmm_plugin_init' );

function csmm_subscribe_hide() {
  set_transient('csmm_subscribe_hide', true, DAY_IN_SECONDS * 90);

  wp_send_json_success();
} // csmm_subscribe_hide
