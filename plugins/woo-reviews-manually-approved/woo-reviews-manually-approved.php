<?php
/*
Plugin Name: Manually Approved Reviews for WooCommerce
Plugin URI: https://wordpress.org/plugins/woo-reviews-manually-approved/
Description: Force WooCommerce product reviews to be manually approved.
Version: 1.3.0
Author: Tim Eckel
Author URI: https://www.dogblocker.com
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: woo-reviews-manually-approved
*/

/*  Copyright 2023  Tim Eckel  (email : eckel.tim@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function teckel_comment_inserted($comment_id, $comment_object) {
		if ( get_post_type($comment_object->comment_post_ID) == 'product' ) {
			wp_set_comment_status($comment_object->comment_ID, 'hold');
		}
	}
	add_action( 'wp_insert_comment','teckel_comment_inserted', 99, 2 );

}

?>