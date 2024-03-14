<?php

/**
 * Reset WordPress
 *
 * @link       https://addonspress.com/
 * @since      1.0.0
 *
 * @package    Potter_Kit
 * @subpackage Potter_Kit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Reset WordPress
 *
 * @package    Potter_Kit
 * @subpackage Potter_Kit/admin
 * @author     Addons Press <addonspress.com>
 */
class Potter_Kit_Reset_WordPress {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {}

	/**
	 * Main Potter_Kit_Reset_WordPress Instance
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @return object $instance Potter_Kit_Reset_WordPress Instance
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Potter_Kit_Reset_WordPress();

		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public function hide_reset_notice() {
		if ( isset( $_GET['potter-kit-hide-notice'] ) && isset( $_GET['_potter_kit_notice_nonce'] ) ) {
			/*Security*/
			if ( ! wp_verify_nonce( $_GET['_potter_kit_notice_nonce'], 'potter_kit_hide_notice_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'potter-kit' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'potter-kit' ) );
			}

			$hide_notice = sanitize_text_field( $_GET['potter-kit-hide-notice'] );

			if ( ! empty( $hide_notice ) && 'reset_notice' == $hide_notice ) {
                potter_kit_update_option( 'potter_kit_reset_notice', 1 );
			}
		}
	}

	/**
	 * Reset actions when a reset button is clicked.
	 */
	public function reset_wizard_actions() {
		global $wpdb, $current_user;

		if ( ! empty( $_GET['ai_reset_wordpress'] ) && ! empty( $_GET['ai_reset_wordpress_nonce'] ) ) {
			/*Security*/
			if ( ! wp_verify_nonce( wp_unslash( $_GET['ai_reset_wordpress_nonce'] ), 'ai_reset_wordpress' ) ) { // WPCS: input var ok, sanitization ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'potter-kit' ) );
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'No permission to reset WordPress', 'potter-kit' ) );
			}

			require_once ABSPATH . '/wp-admin/includes/upgrade.php';

			$template    = get_option( 'template' );
			$blogname    = get_option( 'blogname' );
			$admin_email = get_option( 'admin_email' );
			$blog_public = get_option( 'blog_public' );

			$current_url = potter_kit_current_url();

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
			$result = wp_install( $blogname, $user->user_login, $user->user_email, $blog_public );

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
			$required_plugins = (array) apply_filters( 'potter_kit_' . $template . '_required_plugins', array() );
			if ( is_array( $required_plugins ) ) {
				if ( ! in_array( plugin_basename( POTTER_KIT_PATH . '/potter-kit.php' ), $required_plugins ) ) {
					$required_plugins = array_merge( $required_plugins, array( POTTER_KIT_PATH . '/potter-kit.php' ) );
				}
				activate_plugins( $required_plugins, '', is_network_admin(), true );
			}

			// Update the cookies.
			wp_clear_auth_cookie();
			wp_set_auth_cookie( $result['user_id'] );

			// Redirect to demo importer page to display reset success notice.
			wp_safe_redirect( $current_url . '&reset=true&from=ai-reset-wp' );
			exit();
		}
	}

	/**
	 * Reset wizard notice.
	 */
	/* public function reset_wizard_notice() {

		$screen = get_current_screen();
		if ( ! in_array( $screen->base, potter_kit_admin()->hook_suffix ) ) {
			return;
		}
		$current_url = potter_kit_current_url();
		$reset_url   = wp_nonce_url(
			add_query_arg( 'ai_reset_wordpress', 'true', $current_url ),
			'ai_reset_wordpress',
			'ai_reset_wordpress_nonce'
		);

		$demo_notice_dismiss = get_option( 'potter_kit_reset_notice' );

		// Output reset wizard notice.
		if ( ! $demo_notice_dismiss ) {
			?>
			<div id="message" class="updated ai-import-message">
				<p><?php _e( '<strong>WordPress Reset</strong> &#8211; If no important data on your site. You can reset the WordPress back to default again!', 'potter-kit' ); ?></p>
				<p class="submit"><a href="<?php echo esc_url( $reset_url ); ?>" class="button button-primary ai-wp-reset"><?php esc_html_e( 'Run the Reset Wizard', 'potter-kit' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'potter-kit-hide-notice', 'reset_notice', $current_url ), 'potter_kit_hide_notice_nonce', '_potter_kit_notice_nonce' ) ); ?>"><?php esc_attr_e( 'Hide this notice', 'potter-kit' ); ?></a></p>
			</div>
			<?php
		} elseif ( isset( $_GET['reset'] ) && 'true' === $_GET['reset'] ) {
			$user = get_user_by( 'id', 1 );
			?>
			<div id="message" class="notice notice-info is-dismissible">
				<p><?php printf( __( 'WordPress has been reset back to defaults. The user <strong>"%1$s"</strong> was recreated with its previous password.', 'potter-kit' ), $user->user_login ); ?></p>
			</div>
			<?php
		}
	}*/


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
function potter_kit_reset_wordpress() {
	return Potter_Kit_Reset_WordPress::instance();
}
