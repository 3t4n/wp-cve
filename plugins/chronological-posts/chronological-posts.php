<?php
/**
 * Plugin Name: Chronological Posts
 * Plugin URI: http://celloexpressions.com/plugins/chronological-posts/
 * Description: Globably reverses the post order to be chronological, so that your site can display a journal or a book in chronological order.
 * Version: 1.0
 * Author: Nick Halsey
 * Author URI: http://celloexpressions.com/
 * Tags: post order, chronological, reverse post order
 * License: GPL

=====================================================================================
Copyright (C) 2016 Nick Halsey

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

add_filter( 'pre_get_posts', 'reverse_post_order_pre_get_posts' );
function reverse_post_order_pre_get_posts( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		$query->set( 'order', 'ASC' );
	}
}