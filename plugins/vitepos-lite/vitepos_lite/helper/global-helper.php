<?php
/**
 * It helper of global helper for both version.
 *
 * @package  vitepos
 */

if ( ! function_exists( 'vitepos_is_api_request' ) ) {
	/**
	 * Checks if the current request is a WP REST API and vitepos api call or not.
	 *
	 * @param string $base_route Its the base route to check.
	 *
	 * @return bool
	 */
	function vitepos_is_api_request( $base_route = 'vitepos/v1/' ) {
		if ( vitepos_is_rest() ) {
			$current_url = wp_parse_url( add_query_arg( array() ) );
			$is_exists   = strpos( $current_url['path'], $base_route );
			return false !== $is_exists && ! is_null( $is_exists );
		}

		return false;
	}
}
if ( ! function_exists( 'vitepos_is_hpos_enabled' ) ) {
	/**
	 * Checks if the current request is a WP REST API and vitepos api call or not.
	 *
	 * @return bool
	 */
	function vitepos_is_hpos_enabled() {

		return get_option( 'woocommerce_custom_orders_table_enabled' ) === 'yes';
	}
}
if ( ! function_exists( 'vitepos_get_dashboard_query' ) ) {
	/**
	 * Checks if the current request is a WP REST API and vitepos api call or not.
	 *
	 * @param string $prefix Its the base route to check.
	 *
	 * @return string
	 */
	function vitepos_get_dashboard_query( $prefix ) {

		if ( vitepos_is_hpos_enabled() ) {
			$query = "SELECT count(*)AS total_order,mt1.meta_value AS outlet_id,sum(total_amount)	AS total_amount	
				FROM {$prefix}wc_orders 
				INNER JOIN {$prefix}wc_orders_meta ON ({$prefix}wc_orders.ID = {$prefix}wc_orders_meta.order_id AND ( {$prefix}wc_orders_meta.meta_key = '_is_vitepos' AND 		{$prefix}wc_orders_meta.meta_value = 'Y' ))	
				INNER JOIN {$prefix}wc_orders_meta AS mt1 ON ( {$prefix}wc_orders.ID = mt1.order_id AND mt1.meta_key = '_vtp_outlet_id' )	
				WHERE {$prefix}wc_orders.`status`='wc-completed'
				GROUP BY outlet_id
				ORDER BY total_amount desc";
		} else {
			$query = "SELECT count({$prefix}posts.ID) as total_order,	mt1.meta_value as outlet_id,	sum(mt3.meta_value) as total_amount
			FROM	{$prefix}posts INNER JOIN {$prefix}postmeta ON ( {$prefix}posts.ID = {$prefix}postmeta.post_id and ({$prefix}postmeta.meta_key = '_is_vitepos' AND {$prefix}postmeta.meta_value = 'Y')) 
			INNER JOIN {$prefix}postmeta AS mt1 ON ( {$prefix}posts.ID = mt1.post_id and mt1.meta_key = '_vtp_outlet_id' )
			INNER JOIN {$prefix}postmeta AS mt2 ON ( {$prefix}posts.ID = mt2.post_id and mt2.meta_key = '_vtp_payment_list') INNER JOIN {$prefix}postmeta AS mt3 ON ( {$prefix}posts.ID = mt3.post_id and mt3.meta_key = '_order_total')
			WHERE {$prefix}posts.post_type IN ( 'shop_order', 'shop_order_refund' ) 
			AND (({$prefix}posts.post_status = 'wc-completed')) 
			GROUP BY outlet_id
			ORDER BY total_amount desc";
		}

		return $query;
	}
}


if ( ! function_exists( 'vitepos_wc_update_meta' ) ) {
	/**
	 * Checks if the current request is a WP REST API and vitepos api call or not.
	 *
	 * @param string $order_id Its the base route to check.
	 *
	 * @param string $key It is meta key.
	 * @param string $val It is meta value.
	 *
	 * @return string
	 */
	function vitepos_wc_update_meta( $order_id, $key, $val ) {
		$order = wc_get_order( $order_id );
		return vitepos_wc_order_update_meta( $order, $key, $val );
	}
}

if ( ! function_exists( 'vitepos_wc_get_meta' ) ) {
	/**
	 * Checks if the current request is a WP REST API and vitepos api call or not.
	 *
	 * @param string $order_id Its the base route to check.
	 * @param string $key Its meta key.
	 *
	 * @return string
	 */
	function vitepos_wc_get_meta( $order_id, $key ) {
		$order = wc_get_order( $order_id );
		return $order->get_meta( $key, true );
	}
}
if ( ! function_exists( 'vitepos_wc_order_update_meta' ) ) {
	/**
	 * Checks if the current request is a WP REST API and vitepos api call or not.
	 *
	 * @param WC_Order $order Its the base route to check.
	 *
	 * @param string   $key It meta key.
	 * @param string   $val It meta value.
	 *
	 * @return bool
	 */
	function vitepos_wc_order_update_meta( &$order, $key, $val ) {
		$order->update_meta_data( $key, $val );
		return $order->save();
	}
}
