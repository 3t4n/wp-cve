<?php

/**
 * The admin-specific functionality of the plugin.
 * Reset WordPress
 */
class Xpro_Elementor_Starter_Sites_Reset_WordPress {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {}

	/**
	 * Main Xpro_Elementor_Starter_Sites_Reset_WordPress Instance
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @return object $instance Xpro_Elementor_Starter_Sites_Reset_WordPress Instance
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null == $instance ) {
			$instance = new self();

		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Check if user can reset
	 */
	private function can_reset() {
		if ( ! empty( $_GET['xs_reset_wordpress'] ) && ! empty( $_GET['xs_reset_wordpress_nonce'] ) ) {
			/*Security*/
			if ( ! wp_verify_nonce( wp_unslash( $_GET['xs_reset_wordpress_nonce'] ), 'xs_reset_wordpress' ) ) { // WPCS: input var ok, sanitization ok.
				return false;
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Attempt to deactivate the plugins which gives errors while reseting.
	 * We may add other plugins after testing/reported
	 */
	private function deactivate_plugins() {

		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( ! function_exists( 'deactivate_plugins' ) ) {
			return;
		}

		if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			deactivate_plugins( 'elementor/elementor.php' );
		}

	}

	/**
	 * Reset actions when a reset button is clicked.
	 */
	public function reset_wizard_actions() {

		global $wpdb, $current_user;

		if ( ! empty( $_GET['xs_reset_wordpress'] ) && ! empty( $_GET['xs_reset_wordpress_nonce'] ) && $this->can_reset() ) {
			/*Security*/
			if ( ! wp_verify_nonce( wp_unslash( $_GET['xs_reset_wordpress_nonce'] ), 'xs_reset_wordpress' ) ) { // WPCS: input var ok, sanitization ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'xpro-elementor-addons' ) );
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'No permission to reset WordPress', 'xpro-elementor-addons' ) );
			}

			require_once ABSPATH . '/wp-admin/includes/upgrade.php';

			$template    = get_option( 'template' );
			$blogname    = get_option( 'blogname' );
			$admin_email = get_option( 'admin_email' );
			$blog_public = get_option( 'blog_public' );

			$current_url = xpro_elementor_starter_sites_current_url();

			if ( 'admin' != $current_user->user_login ) {
				$user = get_user_by( 'login', 'admin' );
			}

			if ( empty( $user->user_level ) || $user->user_level < 10 ) {
				$user = $current_user;
			}

			// Drop tables.
			$drop_tables = $wpdb->get_col( sprintf( "SHOW TABLES LIKE '%s%%'", str_replace( '_', '\_', $wpdb->prefix ) ) );
			foreach ( $drop_tables as $table ) {
				$wpdb->query( "DROP TABLE IF EXISTS $table" );
			}

			// Installs the site.
			$result = wp_install( $blogname, $user->user_login, $user->user_email, $blog_public, '', md5( wp_rand() ) );

			// Updates the user password with a old one.
			$wpdb->update(
				$wpdb->users,
				array(
					'user_pass'           => $user->user_pass,
					'user_activation_key' => '',
				),
				array( 'ID' => $result['user_id'] )
			);

			// Set up the Password change nag.
			$default_password_nag = get_user_option( 'default_password_nag', $result['user_id'] );
			if ( $default_password_nag ) {
				update_user_option( $result['user_id'], 'default_password_nag', false, true );
			}

			// Switch current theme.
			$current_theme = wp_get_theme( $template );
			if ( $current_theme->exists() ) {
				switch_theme( $template );
			}

			// Activate required plugins.
			$required_plugins = (array) apply_filters( 'xpro_elementor_starter_sites_' . $template . '_required_plugins', array() );
			if ( is_array( $required_plugins ) ) {
				if ( ! in_array( plugin_basename( WP_PLUGIN_DIR . '/xpro-elementor-addons' ), $required_plugins, true ) ) {
					$required_plugins = array_merge( $required_plugins, array( WP_PLUGIN_DIR . '/xpro-elementor-addons/xpro-elementor-addons.php' ) );
				}
				if ( ! in_array( plugin_basename( WP_PLUGIN_DIR . '/elementor' ), $required_plugins, true ) ) {
					$required_plugins = array_merge( $required_plugins, array( WP_PLUGIN_DIR . '/elementor/elementor.php' ) );
				}
				activate_plugins( $required_plugins, '', is_network_admin(), true );
			}

			// Update the cookies.
			wp_clear_auth_cookie();
			wp_set_auth_cookie( $result['user_id'] );

			// Redirect to demo importer page to display reset success notice.
			wp_safe_redirect( $current_url . '&reset=true&from=xs-reset-wp' );
			exit();
		}
	}

	/**
	 * Before Reset Ajax callback
	 */
	public function before_reset() {
		/*check for security*/
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
				)
			);
		}
		check_admin_referer( 'xpro-elementor-starter-sites-reset' );

		/*Deactivate troubleshoot plugins before reset*/
		$this->deactivate_plugins();

		do_action( 'xpro_elementor_starter_sites_before_reset' );

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Success', 'xpro-elementor-addons' ),
			)
		);
	}


}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function xpro_elementor_starter_sites_reset_wordpress() {
	return Xpro_Elementor_Starter_Sites_Reset_WordPress::instance();
}
