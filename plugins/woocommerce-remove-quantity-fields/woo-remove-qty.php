<?
/*
Plugin Name: Woocommerce Remove Quantity
Plugin URI: http://stillarts.com
Description: Remove the Qty from the product page in Woocommerce
Version: 0.1
Author: Marirs
Author URI: http://stillarts.com
License: GPL2


    Copyright 2012  WooCommerce Remove Quantity  (email : marirs@aol.in)

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

	define('WOO_REMOVE_QTY_VERSION', '0.1');

	function callback($buffer) {

		/* Remove the Div Class from the product page */
		$contents =  $buffer;		
		$pattern = '#\<div class="quantity"\>\s*(.+?)\s*\<\/div\>#s';		
		$contents = preg_replace_callback(
		  $pattern,
		  create_function(
		    '$matches',
		    'return "<!--<div class=quantity>$matches[1]</div>-->";'
		  ),
		  $contents
		);
		
		/* Remove the TH from the Cart page */
		$pattern = '#\<th class="product-quantity"\>\s*(.+?)\s*\<\/th\>#s';
		$contents = preg_replace_callback(
		  $pattern,
		  create_function(
		    '$matches',
		    'return "<!--<th class=product-quantity>$matches[1]</th>-->";'
		  ),
		  $contents
		);		
		
		$pattern = '#\<td class="product-quantity"\>\s*<!--(.+?)-->\s*<\/td\>#s';
		$contents = preg_replace_callback(
		  $pattern,
		  create_function(
		    '$matches',
		    'return "<!--<td class=product-quantity>$matches[1]</td>-->";'
		  ),
		  $contents
		);	
				
		$buffer = $contents;
		
	  	return $buffer;
	}
	 
	function buffer_start() { ob_start("callback"); } 
	function buffer_end() { ob_end_flush(); }
	 
	add_action('wp_head', 'buffer_start');
	add_action('wp_footer', 'buffer_end');

?>