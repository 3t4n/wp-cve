<?php
/**
 * Searchanise bootsrap
 *
 * @package Searchanise/Bootstrap
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

use Searchanise\Extensions\WcWeglot;
use Searchanise\Extensions\WcSeJetpack;

/**
 * Bootstrap class
 */
class Bootstrap {

	/**
	 * Initialization
	 */
	public static function init() {
		// Init logger.
		add_action(
			'init',
			function () {
				$GLOBALS['SearchaniseLogger'] = Logger::get_instance(
					array(
						'log_dir'      => SE_LOG_DIR,
						'log_debug'    => SE_DEBUG_LOG,
						'log_errors'   => SE_ERROR_LOG,
						'output_debug' => SE_DEBUG,
					)
				);
			}
		);

		if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			return;
		}

		if ( SE_DEBUG || isset( $_REQUEST[ Async::FL_DISPLAY_ERRORS ] ) && Async::FL_DISPLAY_ERRORS_KEY == $_REQUEST[ Async::FL_DISPLAY_ERRORS ] ) {
			fn_se_define( 'WP_DEBUG', true );
			fn_se_define( 'WP_DEBUG_DISPLAY', true );
		}

		add_action( 'plugins_loaded', array( __CLASS__, 'plugin_loaded' ) );
		add_action( 'wp_dashboard_setup', array( Admin_Dashboard::class, 'init' ) );

		// Init Searchanise Async.
		add_action( 'plugins_loaded', array( Async::class, 'init' ), Api::LOAD_PRIORITY );

		// Add to cart action.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			add_action( 'wp_ajax_se_ajax_add_to_cart', array( Search_Results::class, 'ajax_add_to_cart' ) );
			add_action( 'wp_ajax_nopriv_se_ajax_add_to_cart', array( Search_Results::class, 'ajax_add_to_cart' ) );
		}

		// Init Se info.
		add_action( 'wp_ajax_nopriv_se_info', array( Info::class, 'display' ) );
		add_action( 'wp_ajax_se_info', array( Info::class, 'display' ) );
		add_action( 'init', array( Info::class, 'init' ) );

		// Init Se cron.
		add_filter( 'cron_schedules', array( Cron::class, 'add_intervals' ) );
		add_action( 'wp', array( Cron::class, 'activate' ) );
		add_action( Cron::CRON_INDEX_EVENT, array( Cron::class, 'indexer' ) );
		add_action( Cron::CRON_RESYNC_EVENT, array( Cron::class, 'reimporter' ) );

		// Init hooks.
		$GLOBALS['SeHooks'] = new Hooks();

		self::load_extensions();
	}

	/**
	 * Load plugins
	 */
	public static function plugin_loaded() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$GLOBALS['SearchaniseCli'] = new Cli_Commands();

		} elseif ( ! is_admin() && ! defined( 'DOING_AJAX' ) && ! defined( 'DOING_CRON' ) ) {
			if ( ! Api::get_instance()->get_wc_status() ) {
				return false;
			}

			// Init Searchanise SmartNavigaion.
			$GLOBALS['SearchaniseNavigation'] = new Navigation( Api::get_instance()->get_locale() );
			// Init Searchanise Recommendations.
			$GLOBALS['SearchaniseRecommendations'] = new Recommendations( Api::get_instance()->get_locale() );
			// Init fulltext search.
			$GLOBALS['SearchaniseSearch'] = new Fulltext_Search();
			// Init widgets.
			add_action(
				'plugins_loaded',
				function () {
					$currently_language = Api::get_instance()->get_currently_language();
					$GLOBALS['searchanise'] = new Search_Results( $currently_language );
				},
				Api::POSTPONED_LOAD_PRIORITY
			);

			Async::init();

		} elseif ( is_admin() && ! defined( 'DOING_AJAX' ) && ! defined( 'DOING_CRON' ) ) {
			$GLOBALS['Admin'] = new Admin();
		}
	}

	/**
	 * Load Extensions
	 *
	 * @return void
	 */
	public static function load_extensions() {
		add_action(
			'plugins_loaded',
			function () {
				$GLOBALS['WoocommerceSearchaniseWeglot'] = new WcWeglot();
				$GLOBALS['WoocommerceSearchaniseJetpack'] = new WcSeJetpack();
			},
			5
		);

		register_uninstall_hook( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'weglot/weglot.php', array( WcWeglot::class, 'uninstallAddon' ) );
	}
}
