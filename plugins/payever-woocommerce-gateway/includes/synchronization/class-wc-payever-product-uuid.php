<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Product_Uuid' ) ) {
	return;
}

class WC_Payever_Product_Uuid {

	use WC_Payever_Wpdb_Trait;

	/**
	 * @param array $data
	 */
	public function add_item( $data ) {
		$this->get_wpdb()->delete( $this->get_table_name(), array( 'product_id' => $data['product_id'] ) );
		$this->get_wpdb()->insert( $this->get_table_name(), $data );
	}

	/**
	 * @param string $uuid
	 * @return int|null
	 */
	public function findByUuid( $uuid ) {
		$table_name = $this->get_table_name();
		// phpcs:disable WordPress.DB.PreparedSQL
		$result = $this->get_wpdb()->get_row(
			$this->get_wpdb()->prepare(
				str_replace(
					'wp_woocommerce_payever_product_uuid',
					$table_name,
					'SELECT * FROM wp_woocommerce_payever_product_uuid WHERE uuid = %s'
				),
				$uuid
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.PreparedSQL

		return ! empty( $result['product_id'] ) ? $result['product_id'] : null;
	}

	/**
	 * @return string
	 */
	public function get_table_name() {
		$prefix = $this->get_wpdb()->prefix;

		return "{$prefix}woocommerce_payever_product_uuid";
	}
}
