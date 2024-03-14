<?php
/**
 * Bootstrap file of FDP.

 * @package Freesoul Deactivate Plugins
 */

 defined( 'EOS_DP_PLUGIN_DIR' ) || exit;

if ( isset( $_REQUEST['eos_dp_preview'] ) || is_admin() || wp_doing_ajax() ) {
    define( 'EOS_DP_PRO_TESTING_UNIQUE_ID', eos_dp_pro_testing_uniqueid() );
}

if ( isset( $_GET['fdp_console'] ) && 'true' === sanitize_text_field( $_GET['fdp_console'] ) ) {
    require_once EOS_DP_PLUGIN_DIR . '/inc/class-fdp-php-to-console.php';

}

if( is_admin() || defined( 'WP_CLI' ) ){
    require EOS_DP_PLUGIN_DIR . '/inc/fdp-main.php';
}

if ( is_admin() ) {
	// Filter translation files.
	require_once EOS_DP_PLUGIN_DIR . '/admin/eos-dp-helper.php';
	require_once EOS_DP_PLUGIN_DIR . '/admin/fdp-admin-base.php';
	if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && false !== strpos( sanitize_text_field( $_REQUEST['action'] ), 'eos_dp' ) ) {
		require EOS_DP_PLUGIN_DIR . '/admin/eos-dp-ajax.php'; // file including the code for ajax requests.
	} else {
		require EOS_DP_PLUGIN_DIR . '/admin/fdp-admin.php'; // file including the code for admin.
	}
}

/**
 * It returns a password to use for page loading testing.
 *
 * @since 1.9.0
 *
 */
function eos_dp_pro_testing_uniqueid() {
	$pass = get_site_transient( 'eos_dp_pro_unique_id' );
	if ( $pass ) {
		return $pass;
	}
	if ( is_admin() ) {
		$pass = md5( uniqid( microtime() . mt_rand(), true ) );
		set_site_transient( 'eos_dp_pro_unique_id', $pass, 60 * 60 * 24 );
	}
	return $pass;
}

if ( isset( $_REQUEST['eos_dp_preview'] ) || isset( $_REQUEST['eos_dp_debug'] ) ) {
	add_action( 'after_setup_theme', 'eos_dp_after_theme_setup_on_preview' );
}

/**
 * Actions and filters if page preview.
 *
 * @since 1.9.0
 *
 */
function eos_dp_after_theme_setup_on_preview() {
	// Remove redirections on checkout page.
	add_filter( 'woocommerce_checkout_redirect_empty_cart', '__return_false' );
	add_filter( 'woocommerce_checkout_update_order_review_expired', '__return_false' );
}

add_action( 'plugins_loaded', 'eos_dp_prevent_missing_functions_errors' );

/**
 * Prevent errors due to missing functions when other plugins don't check properly the existence of other plugins.
 *
 * @since 1.9.0
 *
 */
function eos_dp_prevent_missing_functions_errors() {
	if ( ! is_admin() && function_exists( 'fdp_is_plugin_globally_active' ) && fdp_is_plugin_globally_active( 'woocommerce/woocommerce.php' ) ) {
		require_once EOS_DP_PLUGIN_DIR . '/inc/fdp-woocommerce.php';
	}
}

if ( isset( $_REQUEST['fdp-autosuggestion'] ) && 'on' === $_REQUEST['fdp-autosuggestion'] ) {
	if( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	add_filter( 'show_admin_bar', '__return_false' );
	add_filter(
		'body_class',
		/**
		 * Add body class when the page requsted for the auto-suggestion.
		 *
		 * @since 1.9.0
		 *
		 */		
		function() {
			$classes[] = 'fdp-autosuggestion-class-start';
			$classes[] = esc_attr( str_replace( '/', '-', strtok( sanitize_text_field( $_SERVER['REQUEST_URI'] ), '?' ) ) );
			$classes[] = 'fdp-autosuggestion-class-end';
			return $classes;
		}
	);
}

/**
 * Actions triggered after plugin activation or after a new site of a multisite installation is created.
 *
 * @since 1.9.0
 *
 */
function eos_dp_initialize_plugin( $networkwide ) {
	if ( is_multisite() && $networkwide ) {
		wp_die( sprintf( esc_html__( "Freesoul Deactivate Plugins can't be activated networkwide, but only on each single site. %1\$s%2\$s%3\$s", 'freesoul-deactivate-plugins' ), '<div><a class="button" href="' . esc_url( admin_url( 'network/plugins.php' ) ) . '">', esc_html__( 'Back to plugins', 'freesoul-deactivate-plugins' ), '</a></div>' ) );
	}
	require EOS_DP_PLUGIN_DIR . '/plugin-activation.php';
}
register_activation_hook( FDP_PLUGIN_FILE, 'eos_dp_initialize_plugin' );

/**
 * Actions triggered after plugin deaactivation.
 *
 * @since 1.9.0
 *
 */
function eos_dp_deactivate_plugin() {
	if ( ! is_multisite() && file_exists( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' ) ) {
		unlink( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' );
	}
	eos_dp_update_fdp_cache( 'nav', '', true );
}
register_deactivation_hook( FDP_PLUGIN_FILE, 'eos_dp_deactivate_plugin' );

add_action( 'upgrader_process_complete', 'eos_dp_after_upgrade', 10, 2 );

/**
 * Update mu-plugin after upgrade and update plugins slugs/names.
 *
 * @since 1.0.0
 *
 */
function eos_dp_after_upgrade( $upgrader_object, $options ) {
	$update_mu = false;
	if ( isset( $options['plugins'] ) && is_array( $options['plugins'] ) && ! empty( $options['plugins'] ) && isset( $options['action'] ) && 'update' === $options['action'] && isset( $options['type'] ) && 'plugin' === $options['type'] ) {
		foreach ( $options['plugins'] as $plugin ) {
			if ( EOS_DP_PLUGIN_BASE_NAME === $plugin ) {
				 $update_mu = true;
				 break;
			}
		}
	} elseif ( isset( $upgrader_object->new_plugin_data ) ) {
		$new_plugin_data = $upgrader_object->new_plugin_data;
		if ( isset( $new_plugin_data['TextDomain'] ) && 'freesoul-deactivate-plugins' === $new_plugin_data['TextDomain'] ) {
			$update_mu = true;
		}
	}
	if ( $update_mu ) {
		/**
		 * Update mu-plugin after upgrade.
		 *
		 * @since 1.0.0
		 *
		 */
		if ( file_exists( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' ) ) {
			unlink( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' );
		}
		eos_dp_write_file( EOS_DP_PLUGIN_DIR . '/mu-plugins/eos-deactivate-plugins.php', WPMU_PLUGIN_DIR, WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php', true );
		delete_transient( 'eos_dp_changelog_version' );
	}
	if ( function_exists( 'eos_dp_update_fdp_admin_menu' ) && function_exists( 'eos_dp_user_headers' ) ) {
		eos_dp_update_fdp_admin_menu(
			eos_dp_user_headers(
				array(
					'timeout'   => 5,
					'sslverify' => false,
				),
				true
			)
		);
	}
	/**
	 * Update plugins slugs/names.
	 *
	 * @since 2.0.0
	 *
	 */
	eos_dp_update_plugins_slugs_names();
}

/**
 * Update associative array plugin slugs/names.
 *
 * @since 2.0.0
 *
 */
function eos_dp_update_plugins_slugs_names(){
	$active_plugins = isset( $GLOBALS['fdp_all_plugins'] ) && $GLOBALS['fdp_all_plugins'] && is_array( $GLOBALS['fdp_all_plugins'] ) ? array_map( 'sanitize_text_field',$GLOBALS['fdp_all_plugins'] ) : eos_dp_get_option( 'active_plugins' ); // @codingStandardsIgnoreLine. We sanitize with array_map.
	if( $active_plugins && !empty( $active_plugins ) && is_array( $active_plugins ) ){
		$plugin_slug_names = eos_dp_get_option( 'fdp_plugin_slug_names' );
		if( !$plugin_slug_names ){
			$plugin_slug_names = array();
		}
		foreach( $active_plugins as $plugin ){
			$plugin_slug_names[sanitize_text_field( $plugin )] = eos_dp_get_plugin_info( sanitize_text_field( $plugin ) );
		}
		if( !empty( $plugin_slug_names ) ){
			eos_dp_update_option( 'fdp_plugin_slug_names',$plugin_slug_names );
		}
	}
}

/**
 * Helper funciton to write files.
 *
 * @since 1.9.0
 *
 */
function eos_dp_write_file( $source, $destination_dir, $destination, $update_info = false ) {
	$writeAccess = false;
	$access_type = get_filesystem_method();
	if ( $access_type === 'direct' ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( admin_url(), '', false, false, array() );
		/* initialize the API */
		if ( ! WP_Filesystem( $creds ) ) {
			/* any problems and we exit */
			return false;
		}
		global $wp_filesystem;
		$writeAccess = true;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		if ( ! $wp_filesystem->is_dir( $destination_dir ) ) {
			/* directory didn't exist, so let's create it */
			$wp_filesystem->mkdir( $destination_dir );
		}

		$copied = @$wp_filesystem->copy( $source, $destination );
		if ( ! $copied ) {
			echo wp_kses_post( sprintf( esc_html__( 'Failed to create %s', 'freesoul-deactivate-plugins' ), $destination ) );
		} else {
			if ( $update_info ) {
				set_transient( 'freesoul-dp-notice-succ', true, 5 );
				update_option(
					'eos_dp_activation_info',
					array(
						'time'    => time(),
						'version' => EOS_DP_VERSION,
						'no',
					)
				);
			}
		}
	} else {
		if ( $update_info ) {
			set_transient( 'freesoul-dp-notice-fail', true, 5 ); /* don't have direct write access. Prompt user with our notice */
		}
	}
}

/**
 * Get plugin name by plugin slug.
 *
 * @since 2.0.0
 *
 */
function eos_dp_get_plugin_info( $plugin,$regex = 'Plugin Name' ) {
	if( ! file_exists( WP_PLUGIN_DIR . '/' . sanitize_text_field( $plugin ) ) ) {
		return false;
	}
	$kilobytes = 'Plugin Name' === $regex ? 2 : 5;
	// Pull only the first 4 KB of the file in.
	$file_data = file_get_contents( WP_PLUGIN_DIR . '/' . sanitize_text_field( $plugin ), false, null, 0, $kilobytes * KB_IN_BYTES );
	if ( false === $file_data || empty( $file_data ) ) {
		return false;
	}
	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );
	do_action( 'fdp_get_plugin_info', $file_data, $plugin );
	if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ) {
		return sanitize_text_field( _cleanup_header_comment( $match[1] ) );
	}
	return 'Plugin Name' === $regex ? str_replace( '-',' ',dirname( sanitize_text_field( $plugin ) ) ) : false;
}

if( !function_exists( 'eos_dp_update_option' ) ) {
	/**
	 * Update options in case of single or multisite installation.
	 *
	 * @since 1.9.0
	 *
	 */
	function eos_dp_update_option( $option, $newvalue ) {
		if ( ! is_multisite() ) {
			$autoload = in_array(
				$option,
				apply_filters(
					'fdp_autoloaded_options',
					array(
						'eos_dp_archives',
						'eos_dp_desktop',
						'eos_dp_mobile',
						'eos_dp_one_place',
						'eos_dp_browser',
						'eos_dp_frontend_everywhere',
						'eos_dp_by_url',
						'eos_post_types_plugins',
						'eos_dp_opts',
						'eos_dp_pro_main',
						'fdp_addons',
						'active_plugins',
						'eos_dp_by_plugin',
						'eos_dp_plugin_conflicts',
					)
				)
			);
			return update_option( $option, $newvalue, $autoload );
		} else {
			return update_blog_option( get_current_blog_id(), $option, $newvalue );
		}
	}
}

add_action( 'activated_plugin', 'eos_dp_external_plugin_activation', 10 );

/**
 * Run when a new plugin is activated.
 *
 * @since 1.9.0
 *
 */
function eos_dp_external_plugin_activation( $plugin ) {
	eos_dp_update_option( 'eos_dp_new_plugin_activated', 'activated' );
	if ( function_exists( 'eos_dp_update_fdp_admin_menu' ) && function_exists( 'eos_dp_user_headers' ) ) {
		eos_dp_update_fdp_admin_menu(
			eos_dp_user_headers(
				array(
					'timeout'   => 5,
					'sslverify' => false,
				),
				true
			)
		);
	}
	eos_dp_check_if_fdp_addon( $plugin );
	eos_dp_update_plugins_slugs_names();
	eos_dp_update_fdp_cache( 'nav', '', true );
}

/**
 * Check if the activated plugin is an FDP add-on.
 *
 * @since 1.9.0
 *
 */
function eos_dp_check_if_fdp_addon( $plugin ) {
	$fdp_json =  WP_PLUGIN_DIR . '/' . dirname( sanitize_text_field( $plugin ) ) . '/fdp.json';
	if( file_exists( $fdp_json ) ) {
		$fdp_addons = eos_dp_get_option( 'fdp_addons', array() );
		$fdp_addons[] = sanitize_text_field( $plugin );
		eos_dp_update_option( 'fdp_addons', array_unique( $fdp_addons ) );
	}
}