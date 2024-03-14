<?php
/**
 * Class RTWWDPDL_Cart_Query to perform cart based query.
 *
 * @since    1.0.0
 */
class RTWWDPDL_Cart_Query {
	/**
	 * function to sort items in cart accroding to the price.
	 *
	 * @since    1.0.0
	 */
	public static function rtw_sort_by_price( $rtwwdpdl_cart_item_a, $rtwwdpdlcart_item_b ) {
		return $rtwwdpdl_cart_item_a['data']->get_price('edit') <=> $rtwwdpdlcart_item_b['data']->get_price('edit');
	}
	/**
	 * function to sort items in cart according to the price in deceseding.  
	 *
	 * @since    1.0.0
	 */
	public static function sort_by_price_desc( $rtwwdpdl_cart_item_a, $rtwwdpdlcart_item_b ) {
		return $rtwwdpdl_cart_item_a['data']->get_price('edit') <=> $rtwwdpdlcart_item_b['data']->get_price('edit');
	}
}
