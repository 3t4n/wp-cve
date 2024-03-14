<?php
/*
Plugin Name: WP Remove Category prefix
Description: Removes the category base (prefix) slug from the category archive permalinks.
Version: 1.0
Author: Axelnsk
Author URI: https://vk.com/axelnsk
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly
}

if ( ! class_exists( 'WP_Remove_Category_Prefix' ) ) {
	class WP_Remove_Category_Prefix {
		
		function __construct() {
			add_action( 'init', array( $this, 'flush_rules' ), 999 );

			foreach ( array( 'created_category', 'edited_category', 'delete_category' ) as $action ) {
				add_action( $action, array( $this, 'schedule_flush' ) );
			};
			
			add_filter( 'query_vars', array( $this, 'update_query_vars' ) );
			add_filter( 'category_link', array( $this, 'remove_Category_Prefix' ) );
			add_filter( 'request', array( $this, 'redirect_old_category_url' ) );
			add_filter( 'category_rewrite_rules', array( $this, 'add_category_rewrite_rules' ) );

			register_activation_hook( __FILE__, array( $this, 'on_activation_and_deactivation' ) );
            register_deactivation_hook( __FILE__, array( $this, 'on_activation_and_deactivation' ) );
		}
		
		public function flush_rules() {
			if ( get_option( 'rcb_flush_rewrite_rules' ) ) {
				add_action( 'shutdown', 'flush_rewrite_rules' );
				delete_option( 'rcb_flush_rewrite_rules' );
			}
		}
		
		public function schedule_flush() {
			update_option( 'rcb_flush_rewrite_rules', 1 );
		}
		
		public function remove_Category_Prefix( $permalink ) {
			$Category_Prefix = get_option( 'Category_Prefix' ) ? get_option( 'Category_Prefix' ) : 'category';
			
			// Remove initial slash, if there is one (the trailing slash is removed in the regex replacement and we don't want to end up short a slash)
			if ( '/' === substr( $Category_Prefix, 0, 1 ) ) {
				$Category_Prefix = substr( $Category_Prefix, 1 );
			}
			
			$Category_Prefix .= '/';
			
			return preg_replace( '`' . preg_quote( $Category_Prefix, '`' ) . '`u', '', $permalink, 1 );
		}
		
		public function update_query_vars( $query_vars ) {
			$query_vars[] = 'rcb_category_redirect';
			return $query_vars;
		}
		
		public function redirect_old_category_url( $query_vars ) {
			if ( isset( $query_vars['rcb_category_redirect'] ) ) {
				$category_link = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['rcb_category_redirect'], 'category' );
				wp_redirect( $category_link, 301 );
				exit;
			}
			return $query_vars;
		}
		
		public function add_category_rewrite_rules() {
			global $wp_rewrite;
			
			$category_rewrite = array();
			
			if ( function_exists( 'is_multisite' ) && is_multisite() && ! is_subdomain_install() && is_main_site() ) {
				$blog_prefix = 'blog/';
			} else {
				$blog_prefix = '';
			}
					
			foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
				$category_nicename = $category->slug;
				
				if ( $category->cat_ID == $category->parent ) { // recursive recursion
					$category->parent = 0;
				} elseif ( 0 != $category->parent ) {
					$category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
				}
				
				$category_rewrite[$blog_prefix . '(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
				$category_rewrite[$blog_prefix . '(' . $category_nicename . ')/' . $wp_rewrite->pagination_base . '/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
				$category_rewrite[$blog_prefix . '(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
			}
			
			// Redirect support for `old` category base
			$old_base = $wp_rewrite->get_category_permastruct();
			$old_base = str_replace( '%category%', '(.+)', $old_base );
			$old_base = trim( $old_base, '/' );
			
			$category_rewrite[$old_base . '$'] = 'index.php?rcb_category_redirect=$matches[1]';
			
			return $category_rewrite;
		}

		public function on_activation_and_deactivation() {
			flush_rewrite_rules();
		}
	}

	new WP_Remove_Category_Prefix();
}