<?php
/**
 * Background Emailer
 *
 * @version 3.0.1
 * @package WooCommerce/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Background_Process', false ) ) {
	include_once dirname( WC_PLUGIN_FILE ) . '/abstracts/class-wc-background-process.php';
}

class WC_Szamlazz_Background_Migrator extends WC_Background_Process {

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		$this->prefix = 'wp_' . get_current_blog_id();
		$this->action = 'wc_szamlazz_migrate_orders';
		parent::__construct();
	}

	protected function task( $item ) {
		if ( ! $item || empty( $item['task'] ) ) {
			return false;
		}

		$process_count = 0;
		$process_limit = 20;

		switch ( $item['task'] ) {
			case 'migrate_orders':
				$process_count = $this->migrate_orders( $process_limit );
				break;
		}

		if ( $process_limit === $process_count ) {
			// Needs to run again.
			return $item;
		} else {
			update_option('_wc_szamlazz_migrating', false);
			update_option('_wc_szamlazz_migrated', true);
		}

		return false;
	}

	public function migrate_orders($limit = 20) {
		$query = array(
			'limit' => $limit,
			'meta_key' => '_wc_szamlazz_migrated',
			'meta_compare' => 'NOT EXISTS'
		);

		$orders = wc_get_orders( $query );
		$count = 0;

		if($orders) {
			foreach ( $orders as $order ) {
				$order_id = $order->get_id();

				WC_Szamlazz()->log_debug_messages('migrate-order', $order_id, true);

				$old_meta_keys = array(
					'_wc_szamlazz_szallitolevel' => '_wc_szamlazz_delivery',
					'_wc_szamlazz_szallitolevel_pdf' => '_wc_szamlazz_delivery_pdf',
					'_wc_szamlazz_dijbekero' => '_wc_szamlazz_proform',
					'_wc_szamlazz_dijbekero_pdf' => '_wc_szamlazz_proform_pdf',
					'_wc_szamlazz_jovairas' => '_wc_szamlazz_completed',
					'_wc_szamlazz' => '_wc_szamlazz_invoice',
					'_wc_szamlazz_pdf' => '_wc_szamlazz_invoice_pdf',
					'_wc_szamlazz_sztorno' => '_wc_szamlazz_void',
					'_wc_szamlazz_sztorno_pdf' => '_wc_szamlazz_void_pdf',
					'adoszam' => 'wc_szamlazz_adoszam'
				);

				//If order is a receipt
				if(get_post_meta( $order_id, '_wc_nyugta_type', true ) == 'receipt') {
					$old_meta_keys['_wc_szamlazz'] = '_wc_szamlazz_receipt';
					$old_meta_keys['_wc_szamlazz_pdf'] = '_wc_szamlazz_receipt_pdf';
					$old_meta_keys['_wc_szamlazz_sztorno'] = '_wc_szamlazz_void_receipt';
					$old_meta_keys['_wc_szamlazz_sztorno_pdf'] = '_wc_szamlazz_void_receipt_pdf';
					update_post_meta($order_id, '_wc_szamlazz_type_receipt', true);
				}

				foreach ($old_meta_keys as $old_meta_key => $new_meta_key) {
					$old_meta_value = get_post_meta($order_id, $old_meta_key, true);
					if($old_meta_value) {
						update_post_meta($order_id, $new_meta_key, $old_meta_value);
					}
				}

				update_post_meta($order_id, '_wc_szamlazz_migrated', true);

				$count++;
			}
		}

		return $count;
	}

}
