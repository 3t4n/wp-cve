<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Wc_Subscriptions {

	public function __construct() {
	}

	public function xl_find_if_order_is_upgrade_or_downgrade( $order_id ) {
		$final_result = null;
		global $wpdb;

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return $final_result;
		}

		$is_subscription_switch_order = $order->get_meta( '_subscription_switch', true );
		if ( '' != $is_subscription_switch_order ) {
			$all_switches = maybe_unserialize( $order->get_meta( '_subscription_switch_data', true ) );
			if ( is_array( $all_switches ) && count( $all_switches ) > 0 ) {
				$all_switches = $all_switches[ $is_subscription_switch_order ]['switches'];
				if ( is_array( $all_switches ) && count( $all_switches ) > 0 ) {
					$removed_items = array();
					$added_items   = array();
					foreach ( $all_switches as $key1 => $value1 ) {
						$removed_items[] = $value1['remove_line_item'];
						$added_items[]   = $value1['add_line_item'];
					}
				}
				$table_order_itemmeta        = $wpdb->prefix . 'woocommerce_order_itemmeta';
				$how_many                    = count( $removed_items );
				$placeholders                = array_fill( 0, $how_many, '%d' );
				$format                      = implode( ', ', $placeholders );
				$query                       = 'SELECT woi.order_item_id, woi.meta_value as product_id FROM `' . $table_order_itemmeta . "` as woi WHERE woi.meta_key = '_product_id' AND woi.order_item_id IN($format)";
				$results_removed_product_ids = $wpdb->get_results( $wpdb->prepare( $query, $removed_items ), ARRAY_A );
				$results_added_product_ids   = $wpdb->get_results( $wpdb->prepare( $query, $added_items ), ARRAY_A );
				if ( is_array( $results_removed_product_ids ) && count( $results_removed_product_ids ) > 0 ) {
					$all_itmes_total_removed_price = 0;
					$all_itmes_total_added_price   = 0;
					foreach ( $results_removed_product_ids as $key1 => $value1 ) {
						$product = wc_get_product( $value1['product_id'] );
						if ( $product->is_type( 'variable' ) ) {
							$query        = 'SELECT woi.order_item_id, woi.meta_value as variation_id FROM `' . $table_order_itemmeta . "` as woi WHERE woi.meta_key = '_variation_id' AND woi.order_item_id = %d";
							$variation_id = $wpdb->get_results( $wpdb->prepare( $query, $value1['order_item_id'] ), ARRAY_A );
							if ( is_array( $variation_id ) && count( $variation_id ) > 0 ) {
								$variation_id                  = $variation_id[0]['variation_id'];
								$variable_product              = wc_get_product( $variation_id );
								$price                         = $variable_product->price;
								$all_itmes_total_removed_price += $price;
							}
						} else {
							$price                         = $product->get_price();
							$all_itmes_total_removed_price += $price;
						}
					}
					foreach ( $results_added_product_ids as $key1 => $value1 ) {
						$product = wc_get_product( $value1['product_id'] );
						if ( $product->is_type( 'variable' ) ) {
							$query        = 'SELECT woi.order_item_id, woi.meta_value as variation_id FROM `' . $table_order_itemmeta . "` as woi WHERE woi.meta_key = '_variation_id' AND woi.order_item_id = %d";
							$variation_id = $wpdb->get_results( $wpdb->prepare( $query, $value1['order_item_id'] ), ARRAY_A );
							if ( is_array( $variation_id ) && count( $variation_id ) > 0 ) {
								$variation_id                = $variation_id[0]['variation_id'];
								$variable_product            = wc_get_product( $variation_id );
								$price                       = $variable_product->price;
								$all_itmes_total_added_price += $price;
							}
						} else {
							$price                       = $product->get_price();
							$all_itmes_total_added_price += $price;
						}
					}
					if ( $all_itmes_total_removed_price > $all_itmes_total_added_price ) {
						$final_result = 'downgrade';
					} elseif ( $all_itmes_total_removed_price < $all_itmes_total_added_price ) {
						$final_result = 'upgrade';
					}
				}
			}
		}

		return $final_result;
	}

}
