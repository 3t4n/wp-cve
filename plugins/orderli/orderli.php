<?php
/*
Plugin Name: Orderli
Plugin URI: http://wordpress.org/extend/plugins/orderli/
Description: Assign a specific display order to your links.
Version: 1.2
Author: Sam Simmons
Author URI: http://samiconductor.com
License: GPL2
*/
?>
<?php
/*  Copyright 2011 Sam Simmons (email : sam@samiconductor.com)

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
?>
<?php

add_action( 'add_meta_boxes', 'orderli_add_links_order' );

function orderli_add_links_order() {
    add_meta_box(
        'links-order',
        __( 'Orderli', 'orderli' ),
        'orderli_links_order_box',
        'link'
    );
}

function orderli_links_order_box( $link ) {
?>
    <label for="link_order"><?php _e( 'Order', 'orderli' ) ?></label>
    <input type="text" id="link_order" name="link_order" value="<?php echo property_exists( $link, 'link_id' ) ? get_option( "link_order_$link->link_id", 0 ) : 0 ?>" size="5" />
<?php
}

add_action( 'edit_link', 'orderli_save_link_order' );
add_action( 'add_link', 'orderli_save_link_order' );

function orderli_save_link_order( $link_id ) {
    // see if set to zero
    if ( $_POST['link_order'] == '0' ) {
        $order = 0;

    // parse int - return 0 on failure
    } elseif ( !( $order = intval( $_POST['link_order'] ) ) ) {
        // set to previous or 0
        $order = get_option( "link_order_$link_id", 0 );
    }

    if ( !update_option( "link_order_$link_id", $order ) ) {
        add_option( "link_order_$link_id", $order );
    }
}

add_action( 'delete_link', 'orderli_delete_link_order' );

function orderli_delete_link_order( $link_id ) {
    delete_option( "link_order_$link_id" );
}

function orderli_asc($a, $b) {
    $a_order = get_option( "link_order_$a->link_id", 0 );
    $b_order = get_option( "link_order_$b->link_id", 0 );

    if ( $a_order == $b_order ) {
        return 0;
    }

    return ( $a_order > $b_order ) ? 1 : -1;
}

function orderli_desc($a, $b) {
    $a_order = get_option( "link_order_$a->link_id", 0 );
    $b_order = get_option( "link_order_$b->link_id", 0 );

    if ( $a_order == $b_order ) {
        return 0;
    }

    return ( $a_order < $b_order ) ? 1 : -1;
}

// mergesort code taken from comment on php manual usort comment
// sreid at sea-to-sky dot net 08-Jan-2004 04:22
function orderli_mergesort(&$array, $cmp_function = 'orderli_asc' ) {
    // Arrays of size < 2 require no action.
    if (count($array) < 2) return;

    // Split the array in half
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway);
    $array2 = array_slice($array, $halfway);

    // Recurse to sort the two halves
    orderli_mergesort($array1, $cmp_function);
    orderli_mergesort($array2, $cmp_function);

    // If all of $array1 is <= all of $array2, just append them.
    if (call_user_func($cmp_function, end($array1), $array2[0]) < 1) {
        $array = array_merge($array1, $array2);
        return;
    }

    // Merge the two sorted arrays into a single sorted array
    $array = array();
    $ptr1 = $ptr2 = 0;
    while ($ptr1 < count($array1) && $ptr2 < count($array2)) {
        if (call_user_func($cmp_function, $array1[$ptr1], $array2[$ptr2]) < 1) {
            $array[] = $array1[$ptr1++];
        }
        else {
            $array[] = $array2[$ptr2++];
        }
    }

    // Merge the remainder
    while ($ptr1 < count($array1)) $array[] = $array1[$ptr1++];
    while ($ptr2 < count($array2)) $array[] = $array2[$ptr2++];
    return;
} 

function orderli_sort_links_by_order( $results, $args ) {

    // allow for descending link order
    if ( $args['order'] == 'DESC' ) {
        orderli_mergesort( $results, 'orderli_desc' );
    } else {
        orderli_mergesort( $results );
    }

    return $results;
}
add_filter( 'get_bookmarks', 'orderli_sort_links_by_order', 10, 2 );
