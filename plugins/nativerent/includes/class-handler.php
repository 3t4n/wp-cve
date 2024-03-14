<?php
/**
 * Handler
 *
 * @package nativerent
 */

namespace NativeRent;

use Exception;
use HyperCache;

use function add_action;
use function add_filter;
use function apply_filters;
use function class_exists;
use function defined;
use function get_class;
use function in_array;
use function is_null;
use function is_page;
use function is_single;
use function nativerent_report_error;
use function ob_end_flush;
use function ob_list_handlers;
use function ob_start;

use const NATIVERENT_PLUGIN_MAX_PRIORITY;
use const NATIVERENT_PLUGIN_MIN_PRIORITY;

defined( 'ABSPATH' ) || exit;

/**
 * Handler class
 */
class Handler {
	/**
	 * The single instance of the class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Priority of `template_redirect` handler.
	 *
	 * @var int|float
	 */
	private $integration_priority = NATIVERENT_PLUGIN_MIN_PRIORITY;


	/**
	 * Partner monetization info.
	 *
	 * @var Monetizations
	 */
	private $monetizations;

	/**
	 * Site moderation status instance.
	 *
	 * @var Site_Moderation_Status
	 */
	private $site_moderation_status;

	/**
	 * Main Instance.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * A dummy magic method to prevent class from being cloned
	 */
	public function __clone() {
	}

	/**
	 * A dummy magic method to prevent class from being un-serialized
	 */
	public function __wakeup() {
	}

	/**
	 * Constructor is private to prevent creating new instances
	 */
	private function __construct() {
		// Fix priority for Hyper Cache.
		if ( class_exists( HyperCache::class ) ) {
			$this->integration_priority = 1;
		}

		$this->monetizations          = Options::get_monetizations();
		$this->site_moderation_status = Options::get_site_moderation_status();
	}

	/**
	 * Add actions to filter content and handle output
	 */
	public function add_content_actions() {
		// Skip the integration if the site was rejected.
		if ( $this->monetizations->is_all_rejected() || $this->site_moderation_status->is_rejected() ) {
			return;
		}

		// Integration to page.
		add_action( 'template_redirect', array( $this, 'add_integration_action' ), $this->integration_priority );
		add_action( 'shutdown', array( $this, 'add_shutdown_action' ), NATIVERENT_PLUGIN_MAX_PRIORITY );

		Optimization_Handler::init();
	}

	/**
	 * Filter $output
	 *
	 * @param  string $output  Output.
	 *
	 * @return string
	 */
	public function filter_output( $output ) {
		try {
			return Adv::processing( $output, $this->monetizations );
		} catch ( Exception $e ) {
			nativerent_report_error( $e );

			return $output;
		}
	}

	/**
	 * Realtime integration scripts and styles to the <head>...</head>.
	 * We should use code injection method like this to make sure it has the highest priority.
	 *
	 * @param  string $buffer  Content chunk.
	 *
	 * @return string
	 */
	public static function add_head_integration( $buffer ) {
		return apply_filters( 'nativerent_integration_filter', $buffer );
	}

	/**
	 * Filter $content to add integration
	 *
	 * @param  string $content  Content.
	 *
	 * @return string
	 */
	public function try_content_integration( $content ) {
		try {
			return Adv::content_integration( $content );
		} catch ( Exception $e ) {
			nativerent_report_error( $e );

			return $content;
		}
	}

	/**
	 * Run integration process
	 *
	 * @return void
	 */
	public function add_integration_action() {
		if ( ! is_single() && ! is_page() ) {
			return;
		}

		add_filter( 'nativerent_integration_filter', array( $this, 'filter_output' ) );
		add_filter( 'the_content', array( $this, 'try_content_integration' ), NATIVERENT_PLUGIN_MIN_PRIORITY );
		ob_start( array( $this, 'add_head_integration' ) );
	}

	/**
	 * Action handler for `shutdown`.
	 *
	 * @return void
	 */
	public function add_shutdown_action() {
		if ( in_array( get_class( $this ) . '::add_head_integration', ob_list_handlers() ) ) {
			ob_end_flush();
		}
	}
}
