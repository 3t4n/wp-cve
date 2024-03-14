<?php
/**
 * Faire Orders sync status data management.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use Faire\Wc\Admin\Settings as FaireAdminSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Faire Orders sync status data management.
 */
class Sync_Order_Status {

	/**
	 * Option to save the results of an orders sync.
	 *
	 * @var string
	 */
	const ORDERS_SYNC_RESULTS = 'order_sync_results';

	/**
	 * Option to flag if an orders sync is currently running.
	 *
	 * @var string
	 */
	const ORDERS_ALREADY_SYNCED = 'faire_orders_already_synced';

	/**
	 * Option to flag if an orders sync is currently running.
	 *
	 * @var string
	 */
	const ORDERS_SYNC_RUNNING_FLAG = 'faire_orders_sync_running';

	/**
	 * Option to flag wether it is the first orders sync.
	 *
	 * @var string
	 */
	const ORDERS_SYNC_OCCURRENCE = 'faire_orders_sync_occurrence';

	/**
	 * Date of when last orders sync was started.
	 *
	 * @var string
	 */
	const ORDERS_LAST_SYNC_DATE = 'faire_orders_last_sync_date';

	/**
	 * Option to save the time last orders sync finished.
	 *
	 * @var string
	 */
	const ORDERS_LAST_SYNC_FINISH_TIME = 'faire_orders_last_sync_finish_timestamp';

	/**
	 * Minimum time (seconds) to wait after last orders sync finished.
	 *
	 * @var int
	 */
	const ORDERS_LAST_SYNC_TIME_MIN = 300;

	/**
	 * Signals an orders sync has finished.
	 *
	 * @var int
	 */
	const ORDERS_SYNC_FINISHED_STATUS = 0;

	/**
	 * Signals an orders sync is already running.
	 *
	 * @var int
	 */
	const ORDERS_SYNC_RUNNING_STATUS = -1;

	/**
	 * Signals is too soon to start a new orders sync.
	 *
	 * @var int
	 */
	const ORDERS_SYNC_FINISHED_RECENTLY_STATUS = -2;

	/**
	 * Instance of Faire\Wc\Admin\Settings class.
	 *
	 * @var FaireAdminSettings
	 */
	private FaireAdminSettings $settings;

	/**
	 * Class constructor.
	 *
	 * @param FaireAdminSettings $settings Instance of Faire\Wc\Admin\Settings class.
	 */
	public function __construct( FaireAdminSettings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Checks if is the first running orders sync.
	 *
	 * @return bool True if is the first running orders sync.
	 */
	public function is_first_orders_sync(): bool {
		return $this->get_orders_sync_occurrence() === 'first';
	}

	/**
	 * Retrieves the order sync occurrence.
	 *
	 * Returned value is 'first' for the first time running orders sync,
	 * 'not-first' for following orders syncs.
	 *
	 * @return string Order sync occurrence.
	 */
	public function get_orders_sync_occurrence(): string {
		return get_option( self::ORDERS_SYNC_OCCURRENCE, 'first' );
	}

	/**
	 * Updates the order sync occurrence.
	 */
	public function update_orders_sync_occurrence(): bool {
		return update_option( self::ORDERS_SYNC_OCCURRENCE, 'not-first' );
	}

	/**
	 * Returns a list of already synced orders from Faire.
	 *
	 * @return array List of synced orders.
	 */
	public function get_orders_already_synced(): array {
		return get_option( self::ORDERS_ALREADY_SYNCED, array() );
	}

	/**
	 * Records a list of Faire order IDs as already synced.
	 *
	 * @param array $new_synced_orders The list of Faire order IDs.
	 */
	public function save_orders_already_synced( array $new_synced_orders ) {
		$synced_orders = array_unique(
			array_merge( $this->get_orders_already_synced(), $new_synced_orders )
		);
		update_option( self::ORDERS_ALREADY_SYNCED, $synced_orders );
	}

	/**
	 * Removes a Faire order ID from the list of already synced orders.
	 *
	 * @param string $order_id The Faire order ID.
	 */
	public function delete_order_already_synced( string $order_id ) {
		update_option(
			self::ORDERS_ALREADY_SYNCED,
			array_diff( $this->get_orders_already_synced(), array( $order_id ) )
		);
	}

	/**
	 * Sets the initial value for the last orders sync date.
	 */
	public function init_orders_last_sync_date() {
		$last_sync_date = $this->get_orders_last_sync_date( 'none' );
		if ( in_array( $last_sync_date, array( 'none', '' ), true ) ) {
			$this->save_orders_last_sync_date();
		}
	}

	/**
	 * Retrieves the ISO 8601 timestamp of the last orders syncing.
	 *
	 * If no timestamp exists, we assume it should be "now".
	 *
	 * @param string $default Default value.
	 *
	 * @return string
	 *   The ISO 8601 timestamp of the last orders syncing.
	 */
	public function get_orders_last_sync_date( $default = '' ): string {
		return get_option(
			self::ORDERS_LAST_SYNC_DATE,
			$default ? $default : gmdate( 'Y-m-d\TH:i:s.v\Z' )
		);
	}

	/**
	 * Saves the ISO 8601 timestamp of the last orders syncing.
	 *
	 * If no timestamp is given, we assume it should be "now".
	 *
	 * @param string $date ISO 8601 timestamp.
	 */
	public function save_orders_last_sync_date( string $date = '' ) {
		update_option(
			self::ORDERS_LAST_SYNC_DATE,
			$date ? $date : gmdate( 'Y-m-d\TH:i:s.v\Z' ),
			false
		);
	}

	/**
	 * Checks if an orders sync is currently running.
	 *
	 * @return bool True if an orders sync is running.
	 */
	public function check_sync_running(): bool {
		return get_option( self::ORDERS_SYNC_RUNNING_FLAG, 'no' ) === 'yes';
	}

	/**
	 * Sets a flag to indicate if an orders sync is running.
	 *
	 * @param bool $running True to indicate an orders sync is running.
	 */
	public function sync_running( bool $running = false ) {
		update_option( self::ORDERS_SYNC_RUNNING_FLAG, $running ? 'yes' : 'no' );
	}

	/**
	 * Saves the last time an orders sync finished.
	 */
	public function save_last_sync_finish_timestamp() {
		update_option( self::ORDERS_LAST_SYNC_FINISH_TIME, time() );
	}

	/**
	 * Retrieves the last time an orders sync finished.
	 *
	 * @return int The time in seconds
	 */
	public function get_last_sync_finish_timestamp(): int {
		return (int) get_option( self::ORDERS_LAST_SYNC_FINISH_TIME, 0 );
	}

	/**
	 * Checks if it's too soon to start an orders sync.
	 *
	 * @return bool True if minimum time has passed.
	 */
	public function check_sync_finished_recently(): bool {
		$last_time = $this->get_last_sync_finish_timestamp();
		return $last_time && self::ORDERS_LAST_SYNC_TIME_MIN > time() - $last_time;
	}

	/**
	 * Saves the results of an orders sync as a string.
	 *
	 * @param array $results List of results of the orders sync.
	 */
	public function save_orders_sync_results( array $results ) {
		$results_details = $this->get_order_sync_results_details( $results );
		$summary_entries = array(
			// translators: %d orders count.
			'total_orders'            => __( 'Sync queued for %d orders', 'faire-for-woocommerce' ),
			''                        => '',
			// translators: %d orders count.
			'success_orders'          => __( 'Successful Orders Count: %d', 'faire-for-woocommerce' ),
			// translators: %d orders count.
			'failed_orders'           => __( 'Failed Order Count: %d', 'faire-for-woocommerce' ),
			// translators: %d orders count.
			'missing_products_orders' => '- ' . __( 'Orders not synced: %d - couldn\'t match products in order', 'faire-for-woocommerce' ),
			// translators: %d orders count.
			'already_synced_orders'   => '- ' . __( 'Orders already synced: %d', 'faire-for-woocommerce' ),
		);
		$summary         = sprintf(
			// translators: %s sync date.
			__( 'Last sync at %s', 'faire-for-woocommerce' ),
			gmdate( 'Y-m-d\TH:i:s.v\Z', $this->get_last_sync_finish_timestamp() )
		);

		if ( $results_details['total_orders'] ) {
			foreach ( $summary_entries as $entry_name => $entry_text ) {
				$summary .= PHP_EOL . (
					$entry_name ? $this->get_summary_entry( $results_details, $entry_name, $entry_text ) : ''
				);
			}
		} else {
			$summary .= PHP_EOL . __( 'Could not find orders to import.', 'faire-for-wordpress' );
		}

		$this->settings->update_option( self::ORDERS_SYNC_RESULTS, $summary );
	}

	/**
	 * Builds an entry for the sync results summary.
	 *
	 * @param array  $results_details Detailed sync results.
	 * @param string $entry_name     Name of the details entry.
	 * @param string $entry_text     Text to add to the summary.
	 *
	 * @return string The summary entry.
	 */
	private function get_summary_entry(
		array $results_details,
		string $entry_name,
		string $entry_text
	): string {
		$summary_entry = '';
		if ( $results_details[ $entry_name ] ) {
			$summary_entry = sprintf( $entry_text, $results_details[ $entry_name ] );
		}
		return $summary_entry;
	}

	/**
	 * Extracts details from the orders sync results.
	 *
	 * @param array $results Results of the orders sync.
	 *
	 * @return array Details of the orders sync results.
	 */
	private function get_order_sync_results_details( array $results ): array {
		$missing_products_orders = 0;
		$already_synced_orders   = 0;
		$failed_orders           = 0;
		$success_orders          = 0;
		$total_orders            = 0;
		foreach ( $results as $result ) {
			if ( 'error' === $result['status'] ) {
				if ( false !== strpos( $result['info'], 'Could not find orders' ) ) {
					continue;
				}
				$missing_products_orders +=
					( false !== strpos( $result['info'], 'No products found' ) ) ? 1 : 0;
				$already_synced_orders   +=
					( false !== strpos( $result['info'], 'already synced' ) ) ? 1 : 0;
				$total_orders++;
				$failed_orders++;
				continue;
			}
			// Skip summary of successfully imported orders.
			if ( false !== strpos( $result['info'], 'Successfully synced:' ) ) {
				continue;
			}
			$total_orders++;
			$success_orders++;
		}

		return array(
			'missing_products_orders' => $missing_products_orders,
			'already_synced_orders'   => $already_synced_orders,
			'failed_orders'           => $failed_orders,
			'success_orders'          => $success_orders,
			'total_orders'            => $total_orders,
		);
	}

	/**
	 * Retrieves the results of last orders sync.
	 *
	 * @return string Results of the orders sync.
	 */
	public function get_orders_sync_results(): string {
		return $this->settings->get_option( self::ORDERS_SYNC_RESULTS, '' );
	}

}
