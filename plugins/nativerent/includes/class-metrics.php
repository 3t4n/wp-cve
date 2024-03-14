<?php
/**
 * Nativerent Metrics
 *
 * @package nativerent
 */

namespace NativeRent;

use function floor;
use function json_encode;
use function microtime;
use function setcookie;

defined( 'ABSPATH' ) || exit;

/**
 * Class Metrics
 */
class Metrics {

	/**
	 * Cookie key.
	 *
	 * @var string
	 */
	const COOKIE_KEY = 'nrentdata';

	/**
	 * The single instance of the class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Time of process beginning
	 *
	 * @var int
	 */
	private $processing_begin;

	/**
	 * Site ID value
	 *
	 * @var string
	 */
	private $site_id;

	/**
	 * Metrics data
	 *
	 * @var array{r: int, s: int, p: int, i: string, u: string, t: int}
	 */
	protected $metrics = array();

	/**
	 * Cookie path value
	 *
	 * @var string
	 */
	protected $cookie_path = '/';

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
		$this->prepare_metrics();
	}

	/**
	 * Preparing actions for collect metrics.
	 *
	 * @return void
	 */
	private function prepare_metrics() {
		$this->processing_begin = microtime( true );
	}

	/**
	 * Set Site ID
	 *
	 * @param string $site_id Site Id.
	 *
	 * @return void
	 */
	public function set_site_id( $site_id ) {
		$this->site_id = $site_id;
	}

	/**
	 * Setup current path
	 *
	 * @param string $uri Request URI value.
	 *
	 * @return void
	 */
	public function set_cookie_path( $uri ) {
		$this->cookie_path = $uri;
	}

	/**
	 * Send metric data to JS script.
	 *
	 * @param array $metrics Actual metrics data.
	 */
	public function update_metrics( $metrics = array() ) {
		foreach ( $metrics as $key => $value ) {
			$this->metrics[ $key ] = $value;
		}
	}

	/**
	 * Set request time
	 *
	 * @return void
	 */
	public function set_request_time() {
		$res = floor( ( microtime( true ) - $this->processing_begin ) * 1000 );
		if ( false !== $res ) {
			$this->update_metrics(
				array(
					'r' => (int) $res,
				)
			);
		}
	}

	/**
	 * Set request error by timeout
	 *
	 * @return void
	 */
	public function set_request_timeout_error() {
		$this->update_metrics(
			array(
				'rr' => 1,
			)
		);
	}

	/**
	 * Set processing time
	 *
	 * @param float $time Time for processing.
	 *
	 * @return void
	 */
	public function set_processing_time( $time ) {
		$res = floor( $time * 1000 );
		if ( false !== $res ) {
			$this->update_metrics(
				array(
					'p' => (int) $res,
				)
			);
		}
	}

	/**
	 * Set processing error by timeout
	 *
	 * @return void
	 */
	public function set_processing_timeout_error() {
		$this->update_metrics(
			array(
				'pp' => 1,
			)
		);
	}

	/**
	 * Set adv status
	 *
	 * @param int $status Adv status.
	 *
	 * @return void
	 */
	public function set_ads_status( $status = - 1 ) {
		$this->update_metrics(
			array(
				's' => $status,
			)
		);
	}

	/**
	 * Insert metrics to cookies
	 *
	 * @return void
	 */
	public function push_metrics() {
		$data = '';
		if ( ! empty( $this->metrics ) ) {
			if ( ! isset( $this->metrics['p'] ) ) {
				$this->metrics['p'] = 0;
			}
			if ( ! isset( $this->metrics['r'] ) ) {
				$this->metrics['r'] = 0;
			}

			$this->metrics['i'] = $this->site_id;
			$this->metrics['u'] = uniqid();
			$this->metrics['t'] = time();

			// Cannot use wp_json_encode().
			$data = json_encode( $this->metrics );
		}
		setcookie( self::COOKIE_KEY, $data, 0, $this->cookie_path );
	}
}
