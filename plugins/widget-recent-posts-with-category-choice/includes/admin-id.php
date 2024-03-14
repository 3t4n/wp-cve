<?php
/*
ID of post and tax in admin table
Plugin: Recent Posts Widget Advanced
Since: 1.0
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$kgmarp_options = get_option( 'kgmarp_option_name' );
if ( is_array( $kgmarp_options ) ) {
	if ( is_admin() && $kgmarp_options['arp_id'] != 'arp_id' ) {
		//post
		function arp_posts_columns_id( $columns ) {
			$columns['arp_post_id'] = __('ID');
			return $columns;
		}
		function arp_posts_custom_id_columns( $column_name, $id ) {
			if ( $column_name === 'arp_post_id' ) {
					echo esc_html( $id );
			}
		}
		add_filter( 'manage_posts_columns', 'arp_posts_columns_id', 5 );
		add_action( 'manage_posts_custom_column', 'arp_posts_custom_id_columns', 5, 2 );
		add_filter( 'manage_pages_columns', 'arp_posts_columns_id', 5 );
		add_action( 'manage_pages_custom_column', 'arp_posts_custom_id_columns', 5, 2 );

		//tax
		function list_my_taxonomies() {
			foreach ( get_taxonomies() as $taxonomy ) {
				add_filter( "manage_edit-${taxonomy}_columns", 'arp_category_column_id', 10 );
				add_action( "manage_${taxonomy}_custom_column", 'arp_taxonomy_custom_id_columns', 10, 3 );
			}
		}
		add_action( 'wp_loaded', 'list_my_taxonomies' );
		function arp_category_column_id( $columns ) {
			$columns['arp_tax_id'] = __('ID');
			return $columns;
		}
		function arp_taxonomy_custom_id_columns( $content, $column_name, $term_id ) {
			if ( $column_name === 'arp_tax_id' ) {
				return $term_id;
			}
		}

		//author
		function arp_add_user_id_column( $columns ) {
			$columns['arp_user_id'] = __('ID');
			return $columns;
		}
		function arp_show_user_id_column_content( $value, $column_name, $user_id ) {
			$user = get_userdata( $user_id );
			if ( 'arp_user_id' == $column_name )
				return $user_id;
			return $value;
		}
		add_filter( 'manage_users_columns', 'arp_add_user_id_column' );
		add_action( 'manage_users_custom_column',  'arp_show_user_id_column_content', 10, 3 );
	}
}
