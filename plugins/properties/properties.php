<?php
/*
* Plugin Name: Properties
* Plugin URI:  https://wordpress.org/plugins/properties/
* Description: Adds new content type to your website - Properties. Allows you to publish and organize real estate property pages.
* Version:     0.1.4
* Author:      Serge Liatko
* Author URI:  http://sergeliatko.com/?utm_source=properties&utm_medium=textlink&utm_content=authorlink&utm_campaign=wpplugins
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Domain Path: /languages
* Text Domain: properties
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*
* Copyright 2016 Serge Liatko <contact@sergeliatko.com> http://sergeliatko.com
*/

/* prevent direct loading */
defined('ABSPATH') or die( sprintf( 'Please, do not load this file directly. File: %s', __FILE__ ) );

/* define paths */
define( 'PRPRTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRPRTS_URL', plugin_dir_url( __FILE__ ) );

/* start plugin class */
if( !class_exists('Properties_Plugin') ) {

	class Properties_Plugin
	{

		/* declare variables */
		public static $_instance;
		public static $version;
		public static $ns;

		public function __construct()
		{

			/* define basic variables */
			self::$version = '0.1.4';
			self::$ns = 'properties_plugin';

			/* load text domain */
			load_plugin_textdomain( 'properties', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

			/* register post type */
			add_action( 'init', array( __CLASS__, 'register_property_content_type' ), 0, 0 );

			/* load admin */
			if( is_admin() )
			{
				/* add plugin settings link */
				add_filter( 'plugin_action_links', array( __CLASS__, 'add_settings_link' ), 10, 2 );
				/* add plugin meta links */
				add_filter( 'plugin_row_meta', array( __CLASS__, 'add_meta_links' ), 10, 2 );

				/* load admin */
				require_once( PRPRTS_PATH . 'admin/class-properties-plugin-admin.php' );
				Properties_Plugin_Admin::load_admin();
			}

			/* properties_plugin__construct hook */
			do_action('properties_plugin__construct');
		}

		/*** POST TYPE ***/

		/* registers property content type and taxonomies */
		public static function register_property_content_type()
		{
			if( !class_exists('Properties_Plugin_Content_Type') ) {
				require_once( PRPRTS_PATH . 'includes/classes/class-properties-plugin-content-type.php' );
			}
			Properties_Plugin_Content_Type::register_property_content_type();
			Properties_Plugin_Content_Type::register_property_taxonomies();
			/* properties_plugin_register_property_content_type hook */
			do_action('properties_plugin_register_property_content_type');
		}

		/*** HELPERS ***/

		/* checks if variable is empty */
		public static function is_empty( $data = null )
		{
			return empty( $data );
		}

		/* returns plugin instance */
		public static function getInstance()
		{
			if ( !isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			/* properties_plugin_init hook */
			do_action( 'properties_plugin_init', self::$_instance );
			/* return instance */
			return self::$_instance;
		}

		/* loads plugin instance */
		public static function load_plugin()
		{
			return self::getInstance();
		}

		/* fires on plugin activation */
		public static function on_activate()
		{
			/* remove uninstall option */
			delete_option('pp_uninstall');
			/* flush rewrite rules */
			load_plugin_textdomain( 'properties', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			self::register_property_content_type();
			flush_rewrite_rules();
			/* properties_plugin_on_activate hook */
			do_action('properties_plugin_on_activate');
		}

		/* fires on plugin uninstall */
		public static function on_uninstall()
		{
			/* check user uninstall / abort if not */
			if( self::is_empty( get_option('pp_uninstall') ) )
			{
				return;
			}
			/* remove options */
			foreach( array(
				'pp_uninstall',
				'pp_reset_settings',
				'pp_property_slug',
				'pp_property_type_slug',
				'pp_property_area_slug',
				'pp_property_complex_slug',
				'pp_property_collection_slug'
			) as $option )
			{
				delete_option( $option );
			}

			/* TODO: delete all properties / their revisions and postmeta */

			/* properties_plugin_on_uninstall hook */
			do_action('properties_plugin_on_uninstall');

			/* flush rewrite rules */
			flush_rewrite_rules();
		}

		/* adds plugin settings link */
		public static function add_settings_link( $links, $file )
		{
			if( $file === plugin_basename( __FILE__ ) )
			{
				array_unshift(
					$links,
					sprintf( '<a href="%s">%s</a>',
						add_query_arg( array(
								'post_type' => 'property',
								'page' => 'properties_plugin'
							),
							admin_url('edit.php')
						),
						__( 'Settings', 'properties' )
					)
				);
			}
			return $links;
		}

		/* adds plugin meta links */
		public static function add_meta_links( $links, $file )
		{
			if( $file === plugin_basename( __FILE__ ) )
			{
				$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
					'https://wordpress.org/support/plugin/properties',
					__( 'Support', 'properties' )
				);
				$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
					'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QPF2QLR5BGKGS',
					__( 'Donate', 'properties' )
				);
				$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
					'https://wordpress.org/support/plugin/properties/reviews/',
					__( 'Rate this plugin', 'properties' )
				);
			}
			return $links;
		}
	}

	/* load plugin */
	add_action( 'plugins_loaded', array( 'Properties_Plugin', 'load_plugin' ), 0, 0 );

	/* activate hook */
	register_activation_hook( __FILE__, array( 'Properties_Plugin', 'on_activate' ) );

	/* uninstall hook */
	register_uninstall_hook( __FILE__, array( 'Properties_Plugin', 'on_uninstall' ) );
}
