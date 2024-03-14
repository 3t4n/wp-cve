<?php
/**
 * Contains all the jobs ran by linkPizza-Manager
 *
 * @link       http://linkpizza.com
 * @since      4.9
 *
 * @package    linkPizza-manager
 * @subpackage linkPizza-manager/includes
 */

/**
 * Manager for all CronJobs
 */
class LinkPizza_Manager_Jobs {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Initializes cron job hooks.
	 *
	 * @since 5.5.2
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'linkpizza_refresh_token', array( $this, 'refresh_token' ) );
	}

	/**
	 * Hook to refresh the OAuth token.
	 *
	 * @since 5.5.2
	 * @return void
	 */
	public function refresh_token() {
		$response = pzz_do_oauth_call_with_refresh_check( PZZ_OIDC_API_BASE_PATH . '/user/me', array(), 0 );

		// pzz_write_log( $response );
	}

	/**
	 * Initializes the scheduling of the cron jobs.
	 *
	 * @since 5.5.2
	 * @return void
	 */
	public function schedule() {
		if ( ! wp_next_scheduled( 'linkpizza_refresh_token' ) ) {
			wp_schedule_event( time(), 'daily', 'linkpizza_refresh_token' );
		}
	}

	/**
	 * Unhook the cron jobs.
	 *
	 * @since 5.5.2
	 * @return void
	 */
	public static function unschedule() {
		$timestamp = wp_next_scheduled( 'linkpizza_refresh_token' );
		wp_unschedule_event( $timestamp, 'linkpizza_refresh_token' );
	}
}
