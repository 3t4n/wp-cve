<?php
/*
Plugin Name: RPS Blog Info
Plugin URI: http://redpixel.com/rps-blog-info-plugin/
Description: Adds menus to the WordPress Toolbar to display blog, page, post and attachment IDs along with other related information.
Version: 1.1.1
Requires at least: 3.3
Author: Red Pixel Studios
Author URI: https://redpixel.com/
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: rps-blog-info

Copyright 2013-2023 Red Pixel Studios, Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses.

*/

/**
 * Adds menus to the WordPress Toolbar to display blog, page, post and attachment IDs along with other related information.
 *
 * @package rps-blog-info
 * @author Red Pixel Studios
 * @version 1.0.6
 */
if ( ! class_exists( 'RPS_Blog_Info', false ) ) {

	class RPS_Blog_Info /* extends RPS_Plugin_Framework */{
			
		/**
		 * The current version of the plugin for internal use.
		 * Be sure to keep this updated as the plugin is updated.
		 *
		 * @since 1.0.0
		 */
		public function plugin_version() {
			return '1.1.1';
		}
		
		/**
		 * The plugin's name for use in printing to the user.
		 *
		 * @since 1.0.0
		 */
		public function plugin_name() {
			return 'RPS Blog Info';
		}
			
		/**
		 * A unique identifier for the plugin. Used for CSS classes
		 * and the like. Uses hyphens instead of spaces.
		 *
		 * @since 1.0.0
		 */
		public function plugin_slug() {
			return 'rps-blog-info';
		}
	
		/**
		 * A unique prefix that identifies the plugin. Used for storing
		 * database options, naming interface elements, and so on.
		 *
		 * @since 1.0.0
		 */
		public function plugin_prefix() {
			return 'rps_blog_info';
		}
				
		/**
		 * A private instance of the plugin for internal use.
		 *
		 * @since 1.0.0
		 */
		private static $plugin_instance;
		
		/**
		 * An entry point wrapper to ensure that the plugin is only invoked once.
		 *
		 * @since 1.0.0
		 */
		public static function invoke() {
			if ( ! isset( self::$plugin_instance ) ) {
				self::$plugin_instance = new self;
			}
		}
	
		public function __construct() {
			add_action( 'plugins_loaded', array( &$this, '_plugins_loaded' ) );			
			add_action( 'admin_bar_menu', array( &$this, 'rps_blog_info' ), 999 );
			add_filter( 'attachment_fields_to_edit', array( &$this, 'f_media_cache_post_object' ), 10, 2 );
			add_action( 'admin_print_styles', array( &$this, 'cb_enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'cb_enqueue_styles' ) );
		}
				
		/**
		 * Load the text domain for l10n and i18n.
		 *
		 * @since 1.0.0
		 */
		public function _plugins_loaded() {
			load_plugin_textdomain( 'rps-blog-info', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'lang/' );
		}
		
		public function cb_enqueue_styles() {
			wp_enqueue_style( 'rps-blog-info-styles', plugins_url( 'rps-blog-info.css', __FILE__ ) );
		}
		
		/**
		 * Gets the client public IP address.
		 *
		 * @since 				1.1.1
		 * @access 				private
		 * @return 				string 				$address 				The client IP address.
		 */
		private static function get_remote_address() {
		    $address = __( 'Undetected', 'rps-blog-info' );
		    $ip_found = false;
		    $ips = '';
		    if (
		    	( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) and $_SERVER['HTTP_X_FORWARDED_FOR'] ) or
		    	( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) and $_SERVER['HTTP_CF_CONNECTING_IP'] )
		    ) {
				if (
					isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) and
					$_SERVER['HTTP_X_FORWARDED_FOR']
				) {
					$ips = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
				}
				else if (
					isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) and
					$_SERVER['HTTP_CF_CONNECTING_IP']
				) {
					$ips = explode( ',', $_SERVER['HTTP_CF_CONNECTING_IP'] );
				}
				foreach( $ips as $ip ) {
					$ip = trim( $ip );
					if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
						$ip_found = true;
						$address = $ip;
						break;
					}
				}
			}
			
			if (
				! $ip_found and
				isset( $_SERVER['REMOTE_ADDR'] ) and
				$_SERVER['REMOTE_ADDR']
			) {
				$address = $_SERVER['REMOTE_ADDR'];
			}
			
			if ( $address == '::1' ) {
				$address = '127.0.0.1';
			}
			
			return $address;
		}

		public function rps_blog_info() {
			global $wp_admin_bar, $post, $pagenow;
			if ( ! isset( $post ) || empty( $post ) ) {
				$post = $this->media_post_object;
			}
			
			if ( ! is_admin_bar_showing() && ( !current_user_can( 'edit_pages' ) || !current_user_can( 'edit_posts' ) ) ) {
				return;
			}
			
			$multisite = ( is_multisite() ) ? true : false;
			$date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
			
			$blog_id = ( $multisite ) ? ' ' . get_current_blog_id() : '';
			
			$blog_details = ( $multisite ) ? get_blog_details( $blog_id ) : false;
							
			if ( $multisite ) {
				$blog_domain = $blog_details->domain;
			}
			else {
				$blog_url = parse_url( get_bloginfo( 'url' ) );
				$blog_domain = $blog_url['host'];
			}
					
			$blog_public = ( $multisite ) ? $blog_details->public : get_option( 'blog_public' );
			$blog_public_status = ( absint( $blog_public ) === 0 ) ? __( 'No Index', 'rps-blog-info' ) : __( 'Index', 'rps-blog-info' );
			
			$server_address = gethostbyname( $blog_domain );
			$remote_address = self::get_remote_address();
			
			$wp_admin_bar->add_node( array(
				'id' => 'rps-blog-info',
				'title' => '<span class="ab-icon"></span>' . __( 'Blog', 'rps-blog-info' ) . $blog_id . ' ',
				'meta' => array( 'tabindex' => 0, 'class' => ( '127.0.0.1' == $server_address or 0 == $blog_public ) ? 'flag' : '' )
			) );
			
			$wp_admin_bar->add_group( array(
				'id' => 'rps-blog-group-1',
				'parent' => 'rps-blog-info',
			) );
		
			$wp_admin_bar->add_node( array(
				'id' => 'rps-blog-group-1-remote-addr',
				'parent' => 'rps-blog-group-1',
				'title' => __( 'Client', 'rps-blog-info' ) . ': ' . $remote_address,
				'meta' => array( 'class' => ( '127.0.0.1' == $server_address ) ? 'flag' : '', 'title' => ( '127.0.0.1' == $server_address ) ? __( 'Client is running on localhost.', 'rps-blog-info' ) : '' )
			) );
			
			$wp_admin_bar->add_node( array(
				'id' => 'rps-blog-group-1-server-addr',
				'parent' => 'rps-blog-group-1',
				'title' => __( 'Server', 'rps-blog-info' ) . ': ' . $server_address,
				'meta' => array( 'class' => ( '127.0.0.1' == $server_address ) ? 'flag' : '', 'title' => ( '127.0.0.1' == $server_address ) ? __( 'Site is running on localhost.', 'rps-blog-info' ) : '' )
			) );
		
			$wp_admin_bar->add_node( array(
				'id' => 'rps-blog-info-domain',
				'parent' => 'rps-blog-info',
				'title' => __( 'Domain', 'rps-blog-info' ) . ': ' . $blog_domain
			) );
	
			$wp_admin_bar->add_node( array(
				'id' => 'rps-blog-info-search-engines',
				'parent' => 'rps-blog-info',
				'title' => __( 'Search Engines', 'rps-blog-info' ) . ': ' . $blog_public_status,
				'meta' => array( 'class' => ( 0 == $blog_public ) ? 'flag' : '', 'title' => ( 0 == $blog_public ) ? __( 'Search engines are instructed not to index your site.', 'rps-blog-info' ) : '' )
			) );
			
			if ( is_singular() || ( is_admin() && ( isset( $pagenow ) && $pagenow == 'post.php' || isset( $pagenow ) && $pagenow == 'media.php' ) ) ) {
				$post_type_obj = get_post_type_object( $post->post_type );
				$post_type = $post_type_obj->labels->singular_name;
				$post_modified = date( $date_format, strtotime( $post->post_modified ) );
				$post_created = date( $date_format, strtotime( $post->post_date ) );
				$post_name = $post->post_name;
				$post_author = get_the_author_meta( 'display_name', $post->post_author );
				$post_status = ucfirst( $post->post_status );
				if ( $post_status == 'Publish' ) {
					$post_status = __( 'Published', 'rps-blog-info' );
				}
				$post_password = ( $post->post_password != '' ) ? _x( 'Yes', 'post has password', 'rps-blog-info' ) : _x( 'No', 'post has no password', 'rps-blog-info' );
				$comment_status = ucfirst( $post->comment_status );
				$comment_count = $post->comment_count;
				$ping_status = ucfirst( $post->ping_status );
		
				$wp_admin_bar->add_node( array(
					'id' => 'rps-post-info',
					'title' => $post_type . ' ' . $post->ID,
					'meta' => array( 'tabindex' => 0 )
				) );
				
				$wp_admin_bar->add_group( array(
					'id' => 'rps-post-group-1',
					'parent' => 'rps-post-info',
				) );
				
				$wp_admin_bar->add_node( array(
					'id' => 'rps-post-group-1-updated',
					'parent' => 'rps-post-group-1',
					'title' => __( 'Updated', 'rps-blog-info' ) . ': ' . $post_modified
				) );
				
				$wp_admin_bar->add_node( array(
					'id' => 'rps-post-info-slug',
					'parent' => 'rps-post-info',
					'title' => __( 'Slug', 'rps-blog-info' ) . ': ' . $post_name
				) );
	
				$wp_admin_bar->add_node( array(
					'id' => 'rps-post-info-author',
					'parent' => 'rps-post-info',
					'title' => __( 'Author', 'rps-blog-info' ) . ': ' . $post_author
				) );
	
				if ( $post->post_type != 'attachment' ) {
					$wp_admin_bar->add_group( array(
						'id' => 'rps-post-group-2',
						'parent' => 'rps-post-info',
					) );
					
					$wp_admin_bar->add_node( array(
						'id' => 'rps-post-group-2-status',
						'parent' => 'rps-post-group-2',
						'title' => __( 'Status', 'rps-blog-info' ) . ': ' . $post_status
					) );
		
					$wp_admin_bar->add_node( array(
						'id' => 'rps-post-group-2-password',
						'parent' => 'rps-post-group-2',
						'title' => __( 'Password', 'rps-blog-info' ) . ': ' . $post_password
					) );
		
					$wp_admin_bar->add_node( array(
						'id' => 'rps-post-group-2-comments',
						'parent' => 'rps-post-group-2',
						'title' => __( 'Comments', 'rps-blog-info' ) . ': ' . $comment_status . ' (' . $comment_count . ')'
					) );
		
					$wp_admin_bar->add_node( array(
						'id' => 'rps-post-group-2-pings',
						'parent' => 'rps-post-group-2',
						'title' => __( 'Pings', 'rps-blog-info' ) . ': ' . $ping_status
					) );
				}
				
			}
			
		}
		
		// method to grab the post object on the media.php page and cache it.
		public function f_media_cache_post_object( $fields, $post ) {
			global $pagenow;
			if ( is_admin() && isset( $pagenow ) && $pagenow == 'media.php' ) {
				$this->media_post_object = (object) $post;
			}
			return $fields;
		}
		
		// object to cache post information on the edit media page.
		private $media_post_object = null;
	}
	
	if ( ! isset( $rps_blog_info ) ) $rps_blog_info = new RPS_Blog_Info;

}

?>