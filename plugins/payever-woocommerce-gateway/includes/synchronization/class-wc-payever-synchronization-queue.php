<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Queue' ) ) {
	return;
}

use Payever\Sdk\Core\Base\MessageEntity;

/**
 * WC_Payever_Synchronization_Queue class.
 */
class WC_Payever_Synchronization_Queue {

	use WC_Payever_Wpdb_Trait;

	/**
	 * @param string $action
	 * @param string $direction
	 * @param MessageEntity|string $payload
	 */
	public function enqueue_action( $action, $direction, $payload ) {
		$this->add_item(
			array(
				'action'    => $action,
				'direction' => $direction,
				'payload'   => $payload instanceof MessageEntity ? \wp_json_encode( $payload->toArray() ) : $payload,
				'attempts'  => 0,
			)
		);
	}

	/**
	 * Adds new item to the cron queue
	 *
	 * @param $data
	 */
	public function add_item( $data ) {
		// phpcs:disable WordPress.DB.PreparedSQL
		$this->get_wpdb()->insert( $this->get_table_name(), $data );
	}

	/**
	 * Updates item
	 *
	 * @param $item_data
	 */
	public function update_item( $item_data ) {
		$this->get_wpdb()->update(
			$this->get_table_name(),
			$item_data,
			array(
				'id' => $item_data['id'],
			)
		);
	}

	/**
	 * Returns list of items from queue
	 *
	 * @param int $limit
	 *
	 * @return array|object|null
	 */
	public function get_items_list( $limit = 25 ) {
		return $this->get_wpdb()->get_results(
			$this->get_wpdb()->prepare(
				str_replace(
					'wp_woocommerce_payever_synchronization_queue',
					$this->get_table_name(),
					'SELECT * FROM `wp_woocommerce_payever_synchronization_queue` ORDER BY id ASC LIMIT %d'
				),
				$limit
			)
		);
	}

	/**
	 * Removes item by id
	 *
	 * @param string|int $item_id
	 */
	public function delete_item( $item_id ) {
		$this->get_wpdb()->delete(
			$this->get_table_name(),
			array(
				'id' => $item_id,
			),
			array( '%d' )
		);
	}

	/**
	 * @return void
	 */
	public function emptyQueue() {
		$tableName = $this->get_table_name();
		$this->get_wpdb()->query( "DELETE FROM $tableName WHERE 1" );
		// phpcs:enable WordPress.DB.PreparedSQL
	}

	/**
	 * @return string
	 */
	public function get_table_name() {
		$prefix = $this->get_wpdb()->prefix;

		return "{$prefix}woocommerce_payever_synchronization_queue";
	}
}
