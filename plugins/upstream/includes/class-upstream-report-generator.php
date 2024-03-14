<?php
/**
 * Setup message asking for review.
 *
 * @author   UpStream
 * @category Admin
 * @package  UpStream/Admin
 * @version  1.0.0
 */

// Exit if accessed directly or already defined.
if ( ! defined( 'ABSPATH' ) || class_exists( 'UpStream_Report_Generator' ) ) {
	return;
}

/**
 * Class UpStream_Report
 */
class UpStream_Report_Generator {

	/**
	 * Instance
	 *
	 * @var mixed
	 */
	protected static $instance;

	/**
	 * Reports
	 *
	 * @var array
	 */
	public $reports = array();

	/**
	 * UpStream_Report_Generator constructor.
	 */
	public function __construct() {
		if ( class_exists( 'UpStream_Report' ) ) {
			$this->reports[] = new UpStream_Report_Projects();
		}
	}

	/**
	 * Get All Reports
	 */
	public function get_all_reports() {
		return $this->reports;
	}

	/**
	 * Get Report
	 *
	 * @param  mixed $id Id.
	 */
	public function get_report( $id ) {
		$reports = $this->get_all_reports();

		foreach ( $reports as $r ) {
			if ( $r->id === $id ) {
				return $r;
			}
		}

		return null;
	}

	/**
	 * Get Report Fields From Post
	 *
	 * @param  mixed $remove Remove.
	 */
	public function get_report_fields_from_post( $remove ) {
		$post_data    = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$nonce_passed = false;
		$nonce_keys   = array(
			'nonce'                      => 'upstream-nonce',
			'upstream_report_form_nonce' => 'upstream_report_form',
		);

		// check multiple nonce possibility.
		foreach ( $nonce_keys as $key => $value ) {
			if ( ! isset( $post_data[ $key ] ) ) {
				continue;
			}

			if ( wp_verify_nonce( $post_data[ $key ], $value ) ) {
				$nonce_passed = true;
			}
		}

		// bail out on invalid nonce.
		if ( ! $nonce_passed ) {
			return array();
		}

		$report_fields = array();

		foreach ( $post_data as $key => $value ) { // key and value are totally sanitized in the following lines.

			$key = sanitize_text_field( $key );

			// value is totally sanitized after this.
			if ( is_array( $value ) ) {
				$v = array();
				foreach ( $value as $itm ) {
					$v[] = sanitize_text_field( $itm );
				}
				$value = $v;
			} else {
				$value = sanitize_text_field( $value );
			}

			if ( stristr( $key, 'upstream_report__' ) ) {
				if ( $remove ) {
					$report_fields[ str_replace( 'upstream_report__', '', $key ) ] = $value;
				} else {
					$report_fields[ $key ] = $value;
				}
			}
		}

		return $report_fields;
	}

	/**
	 * Execute Report
	 *
	 * @param  mixed $report Report.
	 */
	public function execute_report( $report ) {
		$data = $report->executeReport( $this->get_report_fields_from_post( true ) );
		return $data;
	}

	/**
	 * Get Instance
	 */
	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			$instance         = new self();
			static::$instance = $instance;
		}

		return static::$instance;
	}

}

/**
 * Upstream Register Report
 *
 * @param  mixed $r R.
 * @param  mixed $display Display.
 * @return void
 */
function upstream_register_report( $r, $display = false ) {
	if ( $display ) {
		\UpStream_Report_Generator::get_instance()->reports[] = $r;
	}
}
