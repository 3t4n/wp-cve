<?php
/*
 * Plugin Name: WooCommerce Repeat Order Button
 * Description: Just add an "order again" button in Recent Orders list
 * Plugin URI: http://fernandoacosta.net
 * Author: Fernando Acosta
 * Author URI: http://fernandoacosta.net
 * Version: 1.2
 * License: GPL2
 * Requires at least: 4.1
 * Tested up to: 4.9.2
 * WC requires at least: 3.0
 * WC tested up to:      3.3.1
 * Text Domain: woocommerce-repeat-order-button
 * Domain Path: languages
*/

/*

    Copyright (C) 2016  Fernando Acosta  contato@fernandoacosta.net

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter( 'woocommerce_my_account_my_orders_actions', 'wrob_add_order_again_aciton', 10, 2 );
function wrob_add_order_again_aciton( $actions, $order ) {

  if ( ! $order || ! $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_order_again', array( 'completed' ) ) ) || ! is_user_logged_in() ) {
    return $actions;
  }


  $actions['order-again'] = array(
    'url'  => wp_nonce_url( add_query_arg( 'order_again', $order->get_id() ) , 'woocommerce-order_again' ),
    'name' => __( 'Order Again', 'woocommerce' )
  );

  return $actions;

}
