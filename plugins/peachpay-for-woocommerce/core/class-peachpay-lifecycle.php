<?php
/**
 * PeachPay's activator, deactivator, and updator all in one.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-singleton.php';

/**
 * Class for the lifecycle of the PeachPay plugin
 *   - Activate, Deactivate, Upgrade, or Downgrade
 */
final class PeachPay_Lifecycle {


	use PeachPay_Singleton;

	/**
	 * Setup the hooks
	 */
	public function __construct() {
		$lifecycle = $this;

		/**
		 * Listen for Plugin activation.
		 * 彡～彡 (whooshing noise) 彡～彡
		 */
		register_activation_hook( PEACHPAY_PLUGIN_FILE, array( $this, 'plugin_activated' ) );

		/**
		 * Listen for Plugin deactivation.
		 * 彡～彡 (whooshing noise) 彡～彡
		 */
		register_deactivation_hook( PEACHPAY_PLUGIN_FILE, array( $this, 'plugin_deactivated' ) );

		/**
		 * Detect plugin version changes from the WordPress Upgrader Process
		 */
		add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 10, 2 );

		/**
		 * Detect plugin version changes from manual file changes or FTP uploads.
		 */
		if ( get_option( 'peachpay_old_version' ) !== PEACHPAY_VERSION ) {
			add_action(
				'plugins_loaded',
				function () {
					if ( ! current_user_can( 'manage_options' ) ) {
						return;
					}

					$previous_plugin_version = get_option( 'peachpay_old_version', null );
					update_option( 'peachpay_old_version', PEACHPAY_VERSION );

					if ( null === $previous_plugin_version ) {
						return;
					}

					if ( ! wp_next_scheduled( 'peachpay_version_change_event', array( $previous_plugin_version ) ) ) {
						wp_schedule_single_event( time() + 60, 'peachpay_version_change_event', array( $previous_plugin_version ) );
					}
				}
			);
		}

		/**
		 * Handle Plugin version change events.
		 * 彡～彡 (whooshing noise) 彡～彡
		 */
		add_action(
			'peachpay_version_change_event',
			function ( $previous_plugin_version ) use ( $lifecycle ) {
				if ( version_compare( PEACHPAY_VERSION, $previous_plugin_version, '>' ) ) {
					$lifecycle->plugin_upgraded( $previous_plugin_version );
				} else {
					$lifecycle->plugin_downgraded( $previous_plugin_version );
				}
			}
		);
	}

	/**
	 * Handles plugin activation routines.
	 */
	public function plugin_activated() {
		do_action( 'peachpay_plugin_activated' );

		PeachPay_Capabilities::refresh();
	}

	/**
	 * Handles plugin deactivation routines.
	 */
	public function plugin_deactivated() {
		do_action( 'peachpay_plugin_deactivated' );

		PeachPay_Capabilities::refresh();
	}

	/**
	 * Handles plugin upgraded routines.
	 *
	 * @param string $old_version The old plugin version upgraded from.
	 */
	public function plugin_upgraded( $old_version ) {
		do_action( 'peachpay_plugin_upgraded', $old_version );

		PeachPay_Capabilities::refresh();
	}

	/**
	 * Handles plugin downgraded routines.
	 *
	 * @param string $old_version The old plugin version downgraded from.
	 */
	public function plugin_downgraded( $old_version ) {
		do_action( 'peachpay_plugin_downgraded', $old_version );

		PeachPay_Capabilities::refresh();
	}

	/**
	 * Handles when the upgrader process is complete. Schedules a plugin upgrade or downgrade event
	 *
	 * @param \WP_Upgrader $upgrader_object The upgrader object.
	 * @param array        $hook_extra      Extra arguments passed to hooked filters.
	 */
	public function upgrader_process_complete( $upgrader_object, $hook_extra ) {
		if ( is_array( $hook_extra ) && array_key_exists( 'action', $hook_extra ) && 'update' === $hook_extra['action'] ) {
			if ( array_key_exists( 'type', $hook_extra ) && 'plugin' === $hook_extra['type'] ) {
				$this_plugin_updated = false;

				if ( array_key_exists( 'plugins', $hook_extra ) ) {
					// if bulk plugin update (in update page)
					foreach ( $hook_extra['plugins'] as $each_plugin ) {
						if ( PEACHPAY_BASENAME === $each_plugin ) {
							$this_plugin_updated = true;
							break;
						}
					}

					unset( $each_plugin );
				} elseif ( array_key_exists( 'plugin', $hook_extra ) ) {
					// if normal plugin update or via auto update.
					if ( PEACHPAY_BASENAME === $hook_extra['plugin'] ) {
						$this_plugin_updated = true;
					}
				}

				if ( true === $this_plugin_updated ) {
					wp_schedule_single_event( time() + 60, 'peachpay_version_change_event', array( PEACHPAY_VERSION ) );
				}
			}
		}
	}
}

return PeachPay_Lifecycle::instance();
