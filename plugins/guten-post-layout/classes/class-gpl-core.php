<?php
/**
 * GPL Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package gpl
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if( !class_exists('GPL_Core') ){
	class GPL_Core{

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->load_plugin();
		}

		/**
		 * Load Plugin
		 *
		 */
		public function load_plugin(){
			add_action( 'plugins_loaded', array( $this, 'includes' ) );

			// Add REST API support to an already registered post type.
			add_action( 'init', array( $this, 'load_register_posts_args' ) );

			// Add REST API support to an already registered taxonomy.
			add_filter( 'register_taxonomy_args', array( $this, 'gpl_posts_taxonomy_args' ), 10, 2 );
		}

		public function load_register_posts_args(){
			add_filter( 'register_post_type_args', array( $this, 'gpl_register_posts_args' ), 10, 2 );
		}

		public function gpl_register_posts_args( $args, $post_type ) {

			if( !isset($args['show_in_rest']) && $post_type ){
				$args['show_in_rest'] = true;
				$args['rest_base']             = $post_type."s";
				$args['rest_controller_class'] = 'WP_REST_Posts_Controller';

			}

			return $args;
		}

		public function gpl_posts_taxonomy_args( $args, $taxonomy_name ) {

			if( !isset($args['show_in_rest']) && $taxonomy_name ){
				$args['show_in_rest'] = true;
				$args['rest_base']             = $taxonomy_name."s";
				$args['rest_controller_class'] = 'WP_REST_Terms_Controller';

			}

			return $args;
		}


		/**
		 * Includes.
		 *
		 */
		public function includes() {
			require( GUTEN_POST_LAYOUT_DIR_PATH . 'classes/class-gpl-init.php' );
			//require_once GUTEN_POST_LAYOUT_DIR_PATH.'src/blocks/post-grid/index.php';
			//require_once GUTEN_POST_LAYOUT_DIR_PATH.'src/blocks/post-grid/post-grid.php';
			require_once GUTEN_POST_LAYOUT_DIR_PATH.'classes/rest-api.php';

            //new POST_GRID();

			if( !defined('GUTEN_POST_LAYOUT_PRO_VERSION')){
				require_once GUTEN_POST_LAYOUT_DIR_PATH .'admin/gpl-options.php';
			}

		}
	}
}
GPL_Core::get_instance();
