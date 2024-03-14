<?php
/**
 * Customer functions
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Providing a new customer object
 *
 * @param $contact
 *
 * @return WooFunnels_Customer
 */
function bwf_get_customer( $contact ) {
	return new WooFunnels_Customer( $contact );
}

/**
 * @param $bwf_contact
 * @param $order WC_Order
 * @param $order_id
 * @param $products - only in case of upstroke upsell orders
 * @param $total
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
if ( ! function_exists( 'bwf_create_update_customer' ) ) {
	function bwf_create_update_customer( $bwf_contact, $order, $order_id, $products, $total ) {
		if ( ! $order instanceof WC_Order ) {
			return;
		}
		/** Registering customer as child entities to contact for using its object */
		$bwf_contact->set_customer_child();

		$indexed = BWF_WC_Compatibility::get_order_meta( $order, '_woofunnel_custid' );
		if ( ! $indexed && ( ! is_array( $products ) || ( is_array( $products ) && count( $products ) < 1 ) ) ) { //Non-batching un-indexed order
			$bwf_contact->set_customer_total_order_count( $bwf_contact->get_customer_total_order_count() + 1 );
		}

		$total_change = false;
		$new_total    = 0;
		if ( ( $total > 0 && $indexed ) ) {
			/** Offer accepted and parent order already indexed and batching is on */
			$new_total    = $total - $order->get_total_refunded();
			$total_change = true;
		} elseif ( ! $indexed ) {
			/** new order checkout, payment status paid */
			$new_total    = $order->get_total() - $order->get_total_refunded();
			$total_change = true;
		}

		if ( $total_change && $new_total >= 0 ) {
			/** Convert to base currency */
			$fixed_order_spent    = BWF_Plugin_Compatibilities::get_fixed_currency_price_reverse( $new_total, BWF_WC_Compatibility::get_order_currency( $order ) );
			$db_total_order_spent = $bwf_contact->get_customer_total_order_value();
			$fixed_order_spent    = $fixed_order_spent + $db_total_order_spent;
			$bwf_contact->set_customer_total_order_value( $fixed_order_spent );
		}

		/**BWF_Logger::get_instance()->log( "Order id: $order_id, Indexed: $indexed, New Total: $new_total, TotalChange: $total_change, Total: $total, OrderTotal: {$order->get_total()}, DatePaid: {$order->get_date_paid()}", "customer_batch" );*/

		$product_ids = $cat_ids = $tag_ids = array();

		$subscription_renewal = BWF_WC_Compatibility::get_order_meta( $order, '_subscription_renewal' );
		if ( empty( $subscription_renewal ) ) { //Don't scan a subscription renewal order for products, cats and tags
			$is_batching = false;

			$db_products = $bwf_contact->get_customer_purchased_products();
			$db_cats     = $bwf_contact->get_customer_purchased_products_cats();
			$db_tags     = $bwf_contact->get_customer_purchased_products_tags();

			$cat_ids = $db_cats;
			$tag_ids = $db_tags;

			if ( is_array( $products ) && count( $products ) > 0 ) { //batching on current offer package products and parent order is already been indexed
				$is_batching = true;
				foreach ( $products as $product_id ) {
					if ( ! in_array( $product_id, $db_products, true ) && ! in_array( $product_id, $product_ids, true ) && $product_id > 0 ) {
						array_push( $product_ids, $product_id );
					}
					$product = wc_get_product( $product_id );
					if ( $product->is_type( 'variation' ) ) {
						$product_id = $product->get_parent_id();

						if ( ! in_array( $product_id, $product_ids, true ) && ! in_array( $product_id, $db_products, true ) && $product_id > 0 ) {
							array_push( $product_ids, $product_id );
						}
					}

					$updated_tags_cats = bwf_update_cats_and_tags( $product_id, $cat_ids, $tag_ids );
					$cat_ids           = $updated_tags_cats['cats'];
					$tag_ids           = $updated_tags_cats['tags'];
				}
			}

			if ( false === $is_batching ) {
				foreach ( $order->get_items() as $item ) {
					$product = $item->get_product();
					if ( $product instanceof WC_Product ) {
						$product_id = $product->get_id();
						if ( ! in_array( $product_id, $product_ids, true ) && ! in_array( $product_id, $db_products, true ) && $product_id > 0 ) {
							array_push( $product_ids, $product_id );
						}

						if ( $product->is_type( 'variation' ) ) {
							$product_id = $product->get_parent_id();
							if ( ! in_array( $product_id, $product_ids, true ) && ! in_array( $product_id, $db_products, true ) && $product_id > 0 ) {
								array_push( $product_ids, $product_id );
							}
						}

						$updated_tags_cats = bwf_update_cats_and_tags( $product_id, $cat_ids, $tag_ids );
						$cat_ids           = $updated_tags_cats['cats'];
						$tag_ids           = $updated_tags_cats['tags'];
					}
				}

				$db_used_coupons = $bwf_contact->get_customer_used_coupons();
				$order_coupons   = BWF_WC_Compatibility::get_used_coupons( $order );
				if ( count( $order_coupons ) > 0 && ( count( array_diff( $order_coupons, $db_used_coupons ) ) > 0 || count( $db_used_coupons ) < 1 ) ) {
					$final_coupons = array_unique( array_merge( $db_used_coupons, $order_coupons ) );
					sort( $final_coupons );
					$bwf_contact->set_customer_used_coupons( $final_coupons );
				}
			}
			if ( is_array( $product_ids ) && count( $product_ids ) > 0 && ( count( array_diff( $product_ids, $db_products ) ) > 0 || count( $db_products ) < 1 ) ) {
				$final_products = bwf_get_array_unique_integers( $db_products, $product_ids );
				$bwf_contact->set_customer_purchased_products( $final_products );
			}
			if ( count( $cat_ids ) > 0 && ( count( array_diff( $cat_ids, $db_cats ) ) > 0 || count( $db_cats ) < 1 ) ) {
				$final_cats = bwf_get_array_unique_integers( $db_cats, $cat_ids );
				$bwf_contact->set_customer_purchased_products_cats( $final_cats );
			}
			if ( count( $tag_ids ) > 0 && ( count( array_diff( $tag_ids, $db_tags ) ) > 0 || count( $db_tags ) < 1 ) ) {
				$final_tags = bwf_get_array_unique_integers( $db_tags, $tag_ids );
				$bwf_contact->set_customer_purchased_products_tags( $final_tags );
			}
		}
		$bwf_l_order_date   = $bwf_contact->get_customer_l_order_date();
		$bwf_f_order_date   = $bwf_contact->get_customer_f_order_date();
		$order_created_date = $order->get_date_created()->date( 'Y-m-d H:i:s' );

		if ( empty( $bwf_l_order_date ) || $bwf_l_order_date < $order_created_date ) {
			$bwf_contact->set_customer_l_order_date( $order_created_date );
		}
		if ( empty( $bwf_f_order_date ) || $bwf_f_order_date === '0000-00-00' || $bwf_f_order_date === '0000-00-00 00:00:00' || $bwf_f_order_date > $order_created_date ) {
			$bwf_contact->set_customer_f_order_date( $order_created_date );
		}

		$cid = $bwf_contact->get_id();
		$bwf_contact->set_customer_cid( $cid );
		$order->update_meta_data( '_woofunnel_custid', $cid );
		$order->save_meta_data();

	}
}

/**
 * Setting category ids and tag ids
 *
 * @param $product_ids
 * @param $product_id
 */
if ( ! function_exists( 'bwf_update_cats_and_tags' ) ) {
	function bwf_update_cats_and_tags( $product_id, $cat_ids, $tag_ids ) {

		$product_obj = wc_get_product( $product_id );
		if ( ! $product_obj instanceof WC_Product ) {
			return array(
				'cats' => [],
				'tags' => [],
			);
		}
		/** Terms */
		$product_cats = $product_obj->get_category_ids();
		$product_tags = $product_obj->get_tag_ids();

		$cat_ids = ( is_array( $product_cats ) ) ? array_merge( $cat_ids, $product_cats ) : [];
		$tag_ids = ( is_array( $product_tags ) ) ? array_merge( $tag_ids, $product_tags ) : [];

		$cat_ids = ( is_array( $cat_ids ) ) ? array_unique( $cat_ids ) : [];
		$tag_ids = ( is_array( $tag_ids ) ) ? array_unique( $tag_ids ) : [];

		return array(
			'cats' => $cat_ids,
			'tags' => $tag_ids,
		);

	}
}

/**
 * Updating refunded amount in customer meta
 *
 * @param $order_id
 * @param $amount
 */
if ( ! function_exists( 'bwf_update_customer_refunded' ) ) {
	function bwf_update_customer_refunded( $order_id, $refund_amount ) {
		$order = wc_get_order( $order_id );

		$custid = BWF_WC_Compatibility::get_order_meta( $order, '_woofunnel_custid' );
		if ( empty( $custid ) || 0 === $custid ) {
			return;
		}
		$cid          = BWF_WC_Compatibility::get_order_meta( $order, '_woofunnel_cid' );
		$meta_key     = '_bwf_customer_refunded';
		$bwf_refunded = BWF_WC_Compatibility::get_order_meta( $order, $meta_key );
		$bwf_refunded = empty( $bwf_refunded ) ? 0 : $bwf_refunded;

		$bwf_contacts = BWF_Contacts::get_instance();
		$bwf_contact  = $bwf_contacts->get_contact_by( 'id', $cid );

		$bwf_contact->set_customer_child();
		$customer_total = $bwf_contact->get_customer_total_order_value();
		$order_total    = $order->get_total();

		BWF_Logger::get_instance()->log( "Processing a refund for amount $refund_amount and order id: $order_id Order Total: $order_total, cid: $cid BWF Refunded: $bwf_refunded", 'woofunnels_indexing' );

		if ( $refund_amount <= ( $order_total - $bwf_refunded ) ) {
			/** Convert to base currency */
			$refund_amount  = BWF_Plugin_Compatibilities::get_fixed_currency_price_reverse( $refund_amount, BWF_WC_Compatibility::get_order_currency( $order ) );
			$customer_total -= $refund_amount;
			if ( $customer_total < 0 ) {
				$customer_total = 0;
			}
			$bwf_contact->set_customer_total_order_value( $customer_total );
			bwf_contact_maybe_update_creation_date( $bwf_contact, $order );
			$bwf_contact->save();
			$bwf_refunded += $refund_amount;
			BWF_Logger::get_instance()->log( "Refund amount $refund_amount is reduced from customer meta 'total_value' for contact id: $cid Reduced total is: $customer_total ", 'woofunnels_indexing' );
		}

		$order->update_meta_data( $meta_key, $bwf_refunded );
		$order->save_meta_data();
	}
}
/**
 * Reducing customer total spent on order cancelled
 *
 * @param $order_id
 */
if ( ! function_exists( 'bwf_reduce_customer_total_on_cancel' ) ) {
	function bwf_reduce_customer_total_on_cancel( $order_id ) {
		$order = wc_get_order( $order_id );

		$custid = BWF_WC_Compatibility::get_order_meta( $order, '_woofunnel_custid' );
		if ( empty( $custid ) ) {
			return;
		}
		$cid          = BWF_WC_Compatibility::get_order_meta( $order, '_woofunnel_cid' );
		$meta_key     = '_bwf_customer_refunded';
		$bwf_refunded = BWF_WC_Compatibility::get_order_meta( $order, $meta_key );
		$bwf_refunded = empty( $bwf_refunded ) ? 0 : $bwf_refunded;

		$bwf_contacts = BWF_Contacts::get_instance();
		$bwf_contact  = $bwf_contacts->get_contact_by( 'id', $cid );

		$order_total = $order->get_total();
		$bwf_contact->set_customer_child();
		$customer_total  = $bwf_contact->get_customer_total_order_value();
		$remaining_total = $order_total - $bwf_refunded;

		BWF_Logger::get_instance()->log( "Processing a cancellation for order_id: $order_id, BWF Refunded: $bwf_refunded, Order Total: $order_total, Remaining order total: $remaining_total, Customer total: $customer_total", 'woofunnels_indexing' );

		/** Convert to base currency */
		$remaining_total = BWF_Plugin_Compatibilities::get_fixed_currency_price_reverse( $remaining_total, BWF_WC_Compatibility::get_order_currency( $order ) );
		$customer_total  -= $remaining_total;
		if ( $customer_total < 0 ) {
			$customer_total = 0;
		}
		$bwf_contact->set_customer_total_order_value( $customer_total );
		bwf_contact_maybe_update_creation_date( $bwf_contact, $order );

		$bwf_contact->save();

		$order->update_meta_data( $meta_key, $order_total );
		$order->save_meta_data();

		BWF_Logger::get_instance()->log( "Order $order_id is cancelled for contact id: $cid. Reduced total is: $customer_total ", 'woofunnels_indexing' );
	}
}

/**
 * Return the total order amount
 *
 * @param $order WC_Order
 *
 * @return int
 */
if ( ! function_exists( 'bwf_get_order_total' ) ) {
	function bwf_get_order_total( $order ) {
		$total = 0;

		if ( ! $order instanceof WC_Order ) {
			return $total;
		}

		$total = $order->get_total() - $order->get_total_refunded();
		$total = BWF_Plugin_Compatibilities::get_fixed_currency_price_reverse( $total, BWF_WC_Compatibility::get_order_currency( $order ) );

		return $total;
	}
}
/**
 * Return the Order product items ids
 *
 * @param $order WC_Order
 *
 * @return array
 */
if ( ! function_exists( 'bwf_get_order_product_ids' ) ) {
	function bwf_get_order_product_ids( $order ) {
		$product_ids = [];

		if ( ! $order instanceof WC_Order ) {
			return $product_ids;
		}

		$products = $order->get_items();
		if ( ! is_array( $products ) || count( $products ) === 0 ) {
			return $product_ids;
		}

		$product_arr = [];
		foreach ( $products as $val ) {
			if ( ! $val instanceof WC_Order_Item_Product ) {
				continue;
			}
			$product_id   = $val->get_product_id();
			$variation_id = $val->get_variation_id();

			if ( $variation_id > 0 ) {
				$product_id = $variation_id;
			}
			$product_arr[] = $product_id;
		}

		return $product_arr;
	}
}
/**
 * Return the Order product items categories and terms ids
 *
 * @param $order WC_Order
 *
 * @return array
 */
if ( ! function_exists( 'bwf_get_order_product_terms' ) ) {
	function bwf_get_order_product_terms( $order ) {
		$product_ids = [];

		if ( ! $order instanceof WC_Order ) {
			return $product_ids;
		}

		$products = $order->get_items();
		if ( ! is_array( $products ) || ( is_array( $products ) && count( $products ) === 0 ) ) {
			return $product_ids;
		}

		$order_cats   = $order_tags = [];
		$product_cats = false;
		$product_tags = false;
		foreach ( $products as $val ) {
			if ( ! $val instanceof WC_Order_Item_Product ) {
				continue;
			}

			/** @todo we can save this product obj as object cache so that can be used */
			$product_obj = $val->get_product();
			if ( ! $product_obj instanceof WC_Product ) {
				continue;
			}
			/** Terms */
			$product_cats = $product_obj->get_category_ids();
			$product_tags = $product_obj->get_tag_ids();

			$order_cats = ( is_array( $product_cats ) ) ? array_merge( $order_cats, $product_cats ) : [];
			$order_tags = ( is_array( $product_tags ) ) ? array_merge( $order_tags, $product_tags ) : [];
		}

		$order_cats = ( is_array( $product_cats ) ) ? array_unique( $order_cats ) : [];
		$order_tags = ( is_array( $product_tags ) ) ? array_unique( $order_tags ) : [];

		return array(
			'cats' => $order_cats,
			'tags' => $order_tags,
		);
	}
}
/**
 * Combine 2 arrays and return unique integer array values
 *
 * @param $arr
 *
 * @return mixed
 */
if ( ! function_exists( 'bwf_get_array_unique_integers' ) ) {
	function bwf_get_array_unique_integers( $array1 = [], $array2 = [] ) {
		$arr = array_unique( array_merge( $array1, $array2 ) );
		sort( $arr );
		$arr = array_map( 'intval', $arr );

		return $arr;
	}
}
