<?php

/*
Plugin Name: Sort Query Posts
Plugin URI: http://wordpress.org/extend/plugins/sort-query-posts
Description: Sort posts on-the-fly without making a new SQL query
Version: 1.1
Author: Túbal Martín
Author URI: http://margenn.com
License: GPL2

Copyright 2011  Túbal Martín  (email : tubalmartin@gmail.com)

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

if (! function_exists('sort_query_posts_by'))
{
    function sort_query_posts_by($order_by, $order = 'asc')
    {
        global $wp_query;
        $order_by = strtolower($order_by);
        $order    = strtolower($order);

        if ($order_by == 'rand') {
            shuffle($wp_query->posts);
            return;
        }

        if ($order_by == 'none') {
            $order_by = 'id';
            $order = 'asc';
        }

        $props = array(
            'author'        => 'return sqp_compare_by_number($o1->post_author, $o2->post_author, '.$order.');',
            'comment_count' => 'return sqp_compare_by_number($o1->comment_count, $o2->comment_count, '.$order.');',
            'date'          => 'return sqp_compare_by_number(strtotime($o1->post_date), strtotime($o2->post_date), '.$order.');',
            'id'            => 'return sqp_compare_by_number($o1->ID, $o2->ID, '.$order.');',
            'menu_order'    => 'return sqp_compare_by_number($o1->menu_order, $o2->menu_order, '.$order.');',
            'modified'      => 'return sqp_compare_by_number(strtotime($o1->post_modified), strtotime($o2->post_modified), '.$order.');',
            'parent'        => 'return sqp_compare_by_number($o1->post_parent, $o2->post_parent, '.$order.');',
            'title'         => 'return sqp_compare_by_string($o1->post_title, $o2->post_title, '.$order.');'
        );

        usort($wp_query->posts, create_function('$o1, $o2', $props[$order_by]));
    }

    function sqp_compare_by_number($n1, $n2, $order)
    {
        $n1 = (int) $n1;
        $n2 = (int) $n2;
        $v  = $n1 > $n2 ? 1 : ($n1 < $n2 ? -1 : 0);
        return ($order == 'desc') ? $v * -1 : $v;
    }

    function sqp_compare_by_string($s1, $s2, $order)
    {
        $v = strnatcasecmp($s1, $s2);
        return ($order == 'desc') ? $v * -1 : $v;
    }
}