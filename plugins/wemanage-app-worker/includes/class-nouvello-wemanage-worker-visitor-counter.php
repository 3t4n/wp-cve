<?php
/**
 * Nouvello WeManage Worker Visitor Counter Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Nouvello WeManage Worker Visitor Counter Class
 */
class Nouvello_WeManage_Worker_Visitor_Counter {

	/**
	 * Constructor
	 */
	public function __construct() {
		// install.
		register_activation_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', array( $this, 'nouvello_hits_counter_install_table' ) );
		// add_action( 'init', array( $this, 'nouvello_hits_counter_install_table' ) ); // manual install for debug.

		// enqueue.
		add_action( 'wp_enqueue_scripts', array( $this, 'nouvello_hits_counter_enqeue' ) );

		// ajax.
		add_action( 'wp_ajax_nouvello_update_counter', array( $this, 'nouvello_update_counter_cb' ) );
		add_action( 'wp_ajax_nopriv_nouvello_update_counter', array( $this, 'nouvello_update_counter_cb' ) );

		// woocommerce - after order is completed.
		add_action( 'woocommerce_checkout_create_order', array( $this, 'nouvello_set_order_vistor_counters_meta_order_create' ), 10, 2 ); // for production.
		// add_action( 'woocommerce_thankyou', array( $this, 'nouvello_set_order_vistor_counters_meta_checkout' ) ); // for debug.
		// add_action( 'init', array( $this, 'update_all_orders_visitors_meta' ) ); // for debug.
	}

	/**
	 * Setup db table.
	 */
	public function nouvello_hits_counter_install_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'nouvello_visitor_counter';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
			id BIGINT UNSIGNED NOT NULL auto_increment,
			date date,
			time time,
			post_id mediumint(9),
			visitors mediumint(9),
			views mediumint(9),
			PRIMARY KEY  (id)
		) ' . $charset_collate . ';';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		return true;
	}

	/**
	 * Enqueue.
	 */
	public function nouvello_hits_counter_enqeue() {
		wp_register_script( 'nouvello-visitor-counter', NSWMW_ROOT_DIR . '/includes/assets/js/visitor-counter.min.js', array( 'jquery' ), 1 );
		wp_localize_script(
			'nouvello-visitor-counter',
			'nouvello_visitor_counter',
			array(
				'ajax_url'          => site_url() . '/wp-admin/admin-ajax.php',
				'ajax_nonce'        => wp_create_nonce( 'nouvello_visitor_nonce' ),
				'post_id'           => get_the_ID(),
			)
		);
		wp_enqueue_script( 'nouvello-visitor-counter' );
	}

	/**
	 * Update hits counter
	 */
	public function nouvello_update_counter_cb() {
		check_ajax_referer( 'nouvello_visitor_nonce', 'nonce' );
		$post_array = $_POST;
		$post_id = sanitize_text_field( $post_array['post_id'] );
		$visitors = 0;
		$views = 0;
		if ( ! isset( $_COOKIE['nouvello_unique_visitor'] ) ) {
			setcookie( 'nouvello_unique_visitor', '1', 0, '/', parse_url( site_url(), PHP_URL_HOST ) );
			$visitors = 1;
		}

		$views = 1;
		$this->nouvello_update_counter( $post_id, $visitors, $views );
	}

	/**
	 * [nouvello_update_views_visitors description]
	 *
	 * @param  [type] $post_id  [description].
	 * @param  [type] $visitors [description].
	 * @param  [type] $views    [description].
	 */
	public function nouvello_update_counter( $post_id, $visitors, $views ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'nouvello_visitor_counter';
		$date = gmdate( 'Y-m-d' );
		$time = gmdate( 'h:i:s' );

		// @codingStandardsIgnoreStart
		$post_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $table_name . '` WHERE `post_id` = %d AND `date` = %s', $post_id, $date ) );
		// @codingStandardsIgnoreEnd

		if ( $post_data ) { // update.
			$visitors = $post_data[0]->visitors + $visitors;
			$views = $post_data[0]->views + $views;

			$wpdb->update(
				$table_name,
				array(
					'visitors' => $visitors,
					'views' => $views,
				),
				array(
					'post_id' => $post_id,
					'date' => $date,
				)
			);
			die( 'updated' );
		} else { // insert.
			$wpdb->insert(
				$table_name,
				array(
					'date' => $date,
					'time' => $time,
					'post_id' => $post_id,
					'visitors' => $visitors,
					'views' => $views,
				)
			);

			die( 'inserted' );
		}
	}

	/**
	 * Get post counters
	 *
	 * @param  [type] $post_id  [description].
	 * @param  string $date_min [description].
	 * @param  string $date_max [description].
	 * @return [type]           [description]
	 */
	public function nouvello_get_counter( $post_id, $date_min = '', $date_max = '' ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'nouvello_visitor_counter';
		// @codingStandardsIgnoreStart
		$post_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $table_name . '` WHERE `post_id` = %d AND `date` BETWEEN %s AND %s', $post_id, $date_min, $date_max ) );
		// @codingStandardsIgnoreEnd
		if ( isset( $post_data ) && isset( $post_data[0] ) ) {
			return $post_data[0];
		}

		return array();
	}




	/**
	 * Store product visitor counts with order meta data.
	 *
	 * @param  [type] $order passed by ref.
	 * @param  [type] $data  passed by ref.
	 */
	public function nouvello_set_order_vistor_counters_meta_order_create( $order, $data ) {
		$order_id = $order->get_id();
		$this->nouvello_save_order_vistor_counters_meta( $order, $order_id );
	}

	/**
	 * Store product visitor counts with order meta data.
	 *
	 * @param  [type] $order_id passed by ref.
	 */
	public function nouvello_set_order_vistor_counters_meta_checkout( $order_id ) {
		$order = wc_get_order( $order_id );
		$this->nouvello_save_order_vistor_counters_meta( $order, $order_id );
	}


	/**
	 * Store product visitor counts with order meta data.
	 *
	 * @param  [type] $order    [description].
	 * @param  [type] $order_id [description].
	 * @param  string $date_min [description].
	 * @param  string $date_max [description].
	 */
	public function nouvello_save_order_vistor_counters_meta( $order, $order_id, $date_min = '', $date_max = '' ) {
		$order_counters_meta = array();
		$order_counters_meta['order_id'] = $order_id;
		$order_counters_meta['products'] = array();

		$index = 0;
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			$variation_id = $item->get_variation_id();
			$product_name = $item->get_name();

			// $product = $item->get_product();
			// $quantity = $item->get_quantity();
			// $subtotal = $item->get_subtotal();
			// $total = $item->get_total();
			// $tax = $item->get_subtotal_tax();
			// $taxclass = $item->get_tax_class();
			// $taxstat = $item->get_tax_status();
			// $allmeta = $item->get_meta_data();
			// $somemeta = $item->get_meta( '_whatever', true );
			// $product_type = $item->get_type();

			$datetime = new DateTime();
			$date = $datetime->format( 'Y-m-d' ); // now.
			$date_min = $date;
			$date_max = $date;

			// we want to get the products views for the day it was perchased.
			$product_counters = $this->nouvello_get_counter( $product_id, $date_min, $date_max );
			$order_counters_meta['products'][ $index ] = array(
				'product_id' => $product_id,
				'name' => $product_name,
			);
			if ( ! empty( $product_counters ) ) {
				 // visitors mean - a unique visitor to the website (its counter only one time per website on the initial visit, regrdless of what page the visit was on the site).
				$order_counters_meta['products'][ $index ]['visitors'] = $product_counters->visitors;
				$order_counters_meta['products'][ $index ]['views'] = $product_counters->views;
			} else {
				$order_counters_meta['products'][ $index ]['visitors'] = 0;
				$order_counters_meta['products'][ $index ]['views'] = 0;
			}
			$index++;
		}

		$is = $order->update_meta_data( 'order_vistors_counter', $order_counters_meta );
		$order->save();
	}

	/**
	 * Returns meta data saved with the order that contains the products and their visitors, view counts.
	 *
	 * @param  [type] $order_id [description].
	 * @return [type]           [description].
	 */
	public function get_order_vistor_counters_meta( $order_id = '' ) {
		$order = wc_get_order( $order_id );
		$order_vistor_counters = $order->get_meta( 'order_vistors_counter' );
		echo '<pre>';
		print_r( $order_vistor_counters );
		exit; // debug.
		return $order_vistor_counters;
	}

	/**
	 * Utility function we can use to update all orders. Used for db correction.
	 */
	public function update_all_orders_visitors_meta() {
		return;
		// get all orders IDS.
		$query = new WC_Order_Query(
			array(
				'limit' => -1,
				'orderby' => 'date',
				'order' => 'DESC',
				'return' => 'ids',
			)
		);
		$orders = $query->get_orders();
		foreach ( $orders as $order_id ) {
			$order = wc_get_order( $order_id );
			$order_data = $order->get_data(); // order data.
			$order_date_created = $order_data['date_created']->date( 'Y-m-d' );
			$this->nouvello_save_order_vistor_counters_meta( $order, $order_id, $order_date_created, $order_date_created );
			$order_vistor_counters = $order->get_meta( 'order_vistors_counter' );
			echo '<pre>';
			print_r( $order_vistor_counters );
			echo '</pre>';
		}
		die( 'OK' );
	}
}
