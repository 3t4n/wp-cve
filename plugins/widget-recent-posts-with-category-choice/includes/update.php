<?php
/*
Widget Update Options
Plugin: Recent Posts Widget Advanced
Since: 0.4
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$instance = $old_instance;
$instance['title']                 = sanitize_text_field( $new_instance['title'] );
$instance['post_type']             = sanitize_text_field( $new_instance['post_type'] );
$instance['post_format']           = sanitize_text_field( $new_instance['post_format'] );
$instance['exclude_post_format']   = isset( $new_instance['exclude_post_format'] ) ? (bool) $new_instance['exclude_post_format'] : false;
$instance['category']              = sanitize_text_field( $new_instance['category'] );
$instance['exclude']               = isset( $new_instance['exclude'] ) ? (bool) $new_instance['exclude'] : false;
$instance['tag']                   = sanitize_text_field( $new_instance['tag'] );
$instance['exclude_tag']           = isset( $new_instance['exclude_tag'] ) ? (bool) $new_instance['exclude_tag'] : false;
$instance['author']                = sanitize_text_field( $new_instance['author'] );
$instance['exclude_author']        = isset( $new_instance['exclude_author'] ) ? (bool) $new_instance['exclude_author'] : false;
$instance['show_sticky_posts']     = isset( $new_instance['show_sticky_posts'] ) ? (bool) $new_instance['show_sticky_posts'] : false;
$instance['show_thumb']            = isset( $new_instance['show_thumb'] ) ? (bool) $new_instance['show_thumb'] : false;
$instance['show_date']             = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
$instance['show_author']           = isset( $new_instance['show_author'] ) ? (bool) $new_instance['show_author'] : false;
$instance['hide_on_same_cpt_page'] = isset( $new_instance['hide_on_same_cpt_page'] ) ? (bool) $new_instance['hide_on_same_cpt_page'] : false;
$instance['number']                = (int) $new_instance['number'];
$instance['offset']                = (int) $new_instance['offset'];
