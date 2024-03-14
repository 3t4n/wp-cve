<?php
/**
 * Declare class Lasso_Process_Update_Amazon
 *
 * @package Lasso_Process_Update_Amazon
 */

namespace LassoLite\Classes\Processes;

use LassoLite\Classes\Amazon_Api;
use LassoLite\Classes\Processes\Process;

use LassoLite\Models\Amazon_Products;

/**
 * Lasso_Process_Update_Amazon
 */
class Amazon extends Process {
	/**
	 * Action name
	 *
	 * @var string $action
	 */
	protected $action = 'lassolite_amazon_process';

	/**
	 * Log name
	 *
	 * @var string $log_name
	 */
	protected $log_name = 'update_amazon';

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $product_id Queue item to iterate over.
	 *
	 * @return mixed
	 */
	public function task( $product_id ) {
		if ( empty( $product_id ) ) {
			return false;
		}

		$this->update_amazon_pricing( $product_id );
		$this->set_processing_runtime();

		return false;
	}

	/**
	 * Prepare data for process
	 */
	public function run() {
		// ? check whether process is age out and make it can work on Lasso UI via ajax requests
		$this->is_process_age_out();

		if ( $this->is_process_running() ) {
			return false;
		}

		$total_amz          = Amazon_Products::count_amazon_product_in_db();
		$limit              = intval( ceil( $total_amz / ( 20 * 3 ) ) );
		$amazon_product_ids = Amazon_Products::get_amazon_product_in_db( $limit );
		$count              = count( $amazon_product_ids ) ?? 0;

		foreach ( $amazon_product_ids as $product ) {
			$id                   = Amazon_Api::get_product_id_by_url( $product['base_url'] );
			$product['amazon_id'] = '' === $product['amazon_id'] ? $id : $product['amazon_id'];

			$this->push_to_queue( $product['amazon_id'] );
		}

		$this->set_total( $count );
		$this->set_log_file_name( $this->log_name );
		$this->task_start_log();

		$this->save()->dispatch();

		return true;
	}

	/**
	 * Refresh Amazon products pricing
	 *
	 * @param string $product_id Amazon product id.
	 */
	private function update_amazon_pricing( $product_id ) {
		$lasso_amazon_api = new Amazon_Api();

		try {
			// ? if a product is checked very quick, we need to delay this in a short time
			// ? because amazon api will response an error when we request continuously
			sleep( 1 );

			$last_updated = gmdate( 'Y-m-d H:i:s', time() );
			$amazon_db    = $lasso_amazon_api->get_amazon_product_from_db( $product_id );
			$amz_link     = $amazon_db['monetized_url'] ?? '';
			$lasso_amazon_api->fetch_product_info( $product_id, true, $last_updated, $amz_link );
		} catch ( \Exception $e ) {
			// ? error
		}
	}
}
new Amazon();
