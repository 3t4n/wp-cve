<?php
//Updating contact and customer tables functions in background
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BWF_THRESHOLD_ORDERS', 0 ); //defining it more than 0 means you want the background to run only on "n" orders
define( 'BWF_ORDERS_PER_BATCH', 20 ); //defining it means how many orders to process per batch operation

/*** Updating customer tables ***/
if ( ! function_exists( 'bwf_create_update_contact_customer' ) ) {
	/**
	 *
	 * @return bool|string
	 */
	function bwf_create_update_contact_customer() {
		global $wpdb;
		add_action( 'shutdown', [ WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater'], 'capture_fatal_error' ] );
		/**
		 * get the offset and the threshold of max orders to process
		 */
		$offset = get_option( '_bwf_offset', 0 );

		$get_threshold_order = get_option( '_bwf_order_threshold', BWF_THRESHOLD_ORDERS );

		$paid_statuses = implode( ',', array_map( function ( $status ) {
			return "'wc-$status'";
		}, wc_get_is_paid_statuses() ) );
		if ( 0 === $get_threshold_order ) {


			if ( ! BWF_WC_Compatibility::is_hpos_enabled() ) {

				$query = $wpdb->prepare( "SELECT COUNT({$wpdb->posts}.ID) FROM FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON ( p.ID = pm.post_id AND pm.meta_key = '_woofunnel_cid') LEFT JOIN {$wpdb->postmeta} AS pm2 ON (p.ID = pm2.post_id) WHERE 1=1 AND pm.post_id IS NULL AND ( pm2.meta_key = '_billing_email' AND pm2.meta_value != '' ) AND p.post_type = %s AND p.post_status IN ({$paid_statuses})
								ORDER BY {$wpdb->posts}.post_date DESC", 'shop_order' );

			} else {
				$order_table      = $wpdb->prefix . 'wc_orders';
				$order_meta_table = $wpdb->prefix . 'wc_orders_meta';
				$query            = $wpdb->prepare( "SELECT COUNT(p.id) FROM {$order_table} AS p LEFT JOIN {$order_meta_table} AS pm ON ( p.id = pm.order_id AND pm.meta_key = '_woofunnel_cid') WHERE 1=1 AND pm.order_id IS NULL AND p.billing_email != '' AND 
												p.type = %s  AND p.status IN ({$paid_statuses})
                                ORDER BY p.date_created_gmt DESC", 'shop_order' );


			}


			$query_results = $wpdb->get_var( $query );


			$get_threshold_order = $query_results;
			update_option( '_bwf_order_threshold', $get_threshold_order );
		}

		/**************** PROCESS BATCH STARTS ************/
		$numberposts = ( ( $offset > 0 ) && ( ( $get_threshold_order / $offset ) < 2 ) && ( ( $get_threshold_order % $offset ) < BWF_ORDERS_PER_BATCH ) ) ? ( $get_threshold_order % $offset ) : BWF_ORDERS_PER_BATCH;


		if ( ! BWF_WC_Compatibility::is_hpos_enabled() ) {
			$query = $wpdb->prepare( "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON ( p.ID = pm.post_id AND pm.meta_key = '_woofunnel_cid') LEFT JOIN {$wpdb->postmeta} AS pm2 ON (p.ID = pm2.post_id) WHERE 1=1 AND pm.post_id IS NULL AND ( pm2.meta_key = '_billing_email' AND pm2.meta_value != '' ) AND p.post_type = %s AND p.post_status IN ({$paid_statuses})
								ORDER BY {$wpdb->posts}.post_date DESC LIMIT 0, %d", 'shop_order', $numberposts );

		} else {

			$order_table      = $wpdb->prefix . 'wc_orders';
			$order_meta_table = $wpdb->prefix . 'wc_orders_meta';
			$query            = $wpdb->prepare( "SELECT p.id as ID FROM {$order_table} AS p LEFT JOIN {$order_meta_table} AS pm ON ( p.id = pm.order_id AND pm.meta_key = '_woofunnel_cid') WHERE 1=1 AND pm.order_id IS NULL AND p.billing_email != '' AND 
												p.type = %s  AND p.status IN ({$paid_statuses})
                                ORDER BY p.date_created_gmt DESC LIMIT 0, %d", 'shop_order', $numberposts );

		}
		$query_results = $wpdb->get_results( $query );


		if ( empty( $query_results ) || ! is_array( $query_results ) ) {
			return false;
		}

		$order_ids = array_map( function ( $query_instance ) {
			return $query_instance->ID;
		}, $query_results );
		/**
		 * IF offset reached the threshold or no unindexed orders found, its time to terminate the batch process.
		 */
		if ( $offset >= $get_threshold_order || count( $order_ids ) < 1 ) {
			BWF_Logger::get_instance()->log( 'Terminated on ' . $get_threshold_order, 'woofunnels_indexing' );
			remove_action( 'shutdown', [ WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater'], 'capture_fatal_error' ] );

			return false;
		}

		/**
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 */
		$retrieved_count = count( $order_ids );
		BWF_Logger::get_instance()->log( "These $retrieved_count orders are retrieved: " . implode( ',', $order_ids ), 'woofunnels_indexing' ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r


		WooFunnels_DB_Updater::$indexing = true;
		foreach ( $order_ids as $order_id ) {
			WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater']->set_order_id_in_process( $order_id );
			bwf_create_update_contact( $order_id, array(), 0, true );

			$offset ++;
			update_option( '_bwf_offset', $offset );
		}
		WooFunnels_DB_Updater::$indexing = null;

		/**************** PROCESS BATCH ENDS ************/

		BWF_Logger::get_instance()->log( "bwf_create_update_contact_customer function returned. Offset: $offset, Order Count: $get_threshold_order ", 'woofunnels_indexing' );
		remove_action( 'shutdown', [ WooFunnels_Dashboard::$classes['WooFunnels_DB_Updater'], 'capture_fatal_error' ] );

		return 'bwf_create_update_contact_customer';

	}
}


/*
 * CONTACTS DATABASE STARTS
 */
if ( ! function_exists( 'bwf_contacts_v1_0_init_db_setup' ) ) {
	function bwf_contacts_v1_0_init_db_setup() {
		return 'bwf_contacts_v1_0_init_db_setup';
	}
}