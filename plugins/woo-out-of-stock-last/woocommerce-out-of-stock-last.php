<?php
/**
 * Plugin Name: WooCommerce Out Of Stock Last
 * Plugin URI: http://www.alcmidia.com.br/
 * Description: Organizes woocommerce products listing products out of stock at the end.
 * Version: 2.1
 * Author: pileggi
 * Author URI: http://www.alcmidia.com.br
 */

add_filter( 'woocommerce_get_catalog_ordering_args', 'bbloomer_first_sort_by_stock_amount', 9999 );
 
function bbloomer_first_sort_by_stock_amount( $args ) {
   $args['orderby'] = 'meta_value title';
   $args['order'] = 'ASC';
   $args['meta_key'] = '_stock_status';
   return $args;
}
?>