<?php

/**
 * Plugin Name: bbPress Multi Image Uploader
 * Description: Allows to upload multiple images in topics and replies in bbPress
 * Version: 1.0.6
 * Author: Ankit Gade
 * Author URI: http://sharethingz.com
 * License: GPL2
 */

if( !defined('ABSPATH') ){
	exit;
}

/**
 * Make this plugin functional only if bbpress is active.
 */
if ( in_array( 'bbpress/bbpress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	if( !class_exists('bbp_uploader') ) {

		class bbp_uploader {

			/**
			 * Current version of plugin
			 * 
			 * @var String
			 */
			public $version;

			/**
			 * Arguments which needs to passed to plupload
			 * 
			 * @var array
			 */
			public $plupload_args;

			/**
			 * Holds configuration for this plugin.
			 * @var array
			 */
			public $config;

			/**
			 * bbpress uploader instance.
			 */
			public static function instance() {

				// Store the instance locally to avoid private static replication
				static $instance = null;

				// Only run these methods if they haven't been ran previously
				if( null === $instance ) {

					$instance = new bbp_uploader();
					$instance->setup_globals();
					$instance->includes();
					//$instance->setup_actions();
				}
				
				return $instance;
			}

			/**
			 * Setup properties
			 */
			private function setup_globals() {

				/* Version */
				$this->version = '1.0.6';

				// Setup some base path and URL information
				$this->file       = __FILE__;
				$this->basename   = apply_filters( 'bbp_uploader_plugin_basenname', plugin_basename( $this->file ) );
				$this->plugin_dir = apply_filters( 'bbp_uploader_plugin_dir_path',  plugin_dir_path( $this->file ) );
				$this->plugin_url = apply_filters( 'bbp_uploader_plugin_dir_url',   plugin_dir_url ( $this->file ) );
				
				/* Assets */
				$this->assets_url = apply_filters( 'bbp_uploader_plugin_assests_url', $this->plugin_url.'assets/' );
				
				// Includes
				$this->includes_dir = apply_filters( 'bbp_uploader_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
				$this->includes_url = apply_filters( 'bbp_uploader_includes_url', trailingslashit( $this->plugin_url . 'includes'  ) );
				
				// Languages
				$this->lang_dir     = apply_filters( 'bbp_lang_dir',     trailingslashit( $this->plugin_dir . 'languages' ) );

				/** Misc **/
				$this->domain         = 'bbpress-multi-image-uploader';

			}
			
			/**
			 * Include necessary files
			 */
			private function includes() {

				/* Hooks */
				require( $this->includes_dir . 'hooks/common-hooks.php' );
				require( $this->includes_dir . 'hooks/topic-hooks.php' );
				require( $this->includes_dir . 'hooks/reply-hooks.php' );

				/* Common */
				require( $this->includes_dir . 'common/functions.php' );

				/* Topic */
				require( $this->includes_dir . 'topic/functions.php' );

				/* Reply */
				require( $this->includes_dir . 'replies/functions.php' );

				/**
				 * Allow third party plugin to include necessary file.
				 * 
				 * This can be used to perform remove_action and add third party to add their functionality. ;)
				 */
				do_action( 'bbp_uploader_includes' );

			}

		}

		/**
		 * Instantiate the class
		 */
		function bbp_uploader() {
			return bbp_uploader::instance();
		}

		/**
		 * Hook bbPress early onto the 'plugins_loaded' action.
		 *
		 * This gives all other plugins the chance to load before bbPress, to get their
		 * actions, filters, and overrides setup without bbPress being in the way.
		 */
		if ( defined( 'BBP_UPLODER_LATE_LOAD' ) ) {
			add_action( 'plugins_loaded', 'bbp_uploader', (int) BBP_UPLODER_LATE_LOAD );

		// "And now here's something we hope you'll really like!"
		} else {
			bbp_uploader();
		}

	}
}
