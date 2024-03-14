<?php
namespace CbParallax\Includes;

use CbParallax\Admin\Includes as AdminIncludes;
use CbParallax\Admin\Menu\Includes as MenuIncludes;
use WP_Query;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Require dependencies.
 */
if ( ! class_exists( 'MenuIncludes\cb_parallax_options' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/menu/includes/class-options.php';
}
if ( ! class_exists( 'AdminIncludes\cb_parallax_post_type_support' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-post-type-support.php';
}

//include CBPARALLAX_ROOT_DIR . '../../../wp-includes/l10n.php';

/**
 * Executes function such as migrating image_options and refactoring existing meta_keys.
 *
 * @link
 * @since             0.6.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_upgrade {
	
	/**
	 * The domain of the plugin.
	 *
	 * @since    0.6.0
	 * @access   private
	 * @var      string $domain
	 */
	private $domain;
	
	/**
	 * The version number of the plugin.
	 *
	 * @since    0.6.0
	 * @access   private
	 * @var      string $version
	 */
	private $version;
	
	/**
	 * The reference to the image_options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    object MenuIncludes\$image_options
	 */
	private $options;
	
	/**
	 * Maintains the allowed option values.
	 *
	 * @since  0.6.0
	 * @access public
	 * @var    array $image_options_whitelist
	 */
	public $image_options_whitelist;
	
	/**
	 * Maintains the default image image_options
	 *
	 * @since  0.6.0
	 * @access public
	 * @var    array $default_image_options
	 */
	public $default_image_options;
	
	/**
	 * Maintains the default plugin image_options
	 *
	 * @since  0.6.0
	 * @access public
	 * @var    array $default_plugin_options
	 */
	public $default_plugin_options;
	
	public $supported_post_types;
	
	/**
	 * cb_parallax_upgrade constructor.
	 *
	 * @param $domain
	 * @param $version
	 */
	public function __construct( $domain, $version ) {
		
		$this->domain = $domain;
		$this->version = $version;
        $this->retrieve_options();
	}
	
	/**
	 * Kicks off the options upgrader.
	 */
	public function run() {
		
		$this->migrate_stored_post_meta();
		$this->migrate_stored_options();
	}
	
	/**
	 * Instantiates the class responsible for handling the options
	 * and sets the required option values.
	 */
	private function retrieve_options() {
		
		$this->options = new MenuIncludes\cb_parallax_options( $this->domain );
		$this->image_options_whitelist = $this->options->get_image_options_whitelist();
		$this->default_image_options = $this->options->get_default_image_options();
		$this->default_plugin_options = $this->options->get_default_plugin_options();
	}
	
	/**
	 * Instantiates the class responsible for 'post type support'
	 * and returns an array containing the supported post types.
	 *
	 * @return array
	 */
	private function get_supported_post_types() {
		
		$post_type_support = new AdminIncludes\cb_parallax_post_type_support();
		
		return $post_type_support->get_supported_post_types();
	}
	
	/**
	 * Migrates the stored post meta data.
	 *
	 * @return bool
	 */
	public function migrate_stored_post_meta() {
		
		$args = array(
			'posts_per_page' => - 1,
			'post_type' => $this->get_supported_post_types(),
			'meta_key' => 'cb_parallax'
		);
		$posts = new WP_Query( $args );
		
		foreach ( $posts->posts as $i => $post ) {
			
			$input = get_post_meta( $post->ID, 'cb_parallax', true );
			$output = $this->migrate_options( $input );
			delete_post_meta( $post->ID, 'cb_parallax' );
			add_post_meta( $post->ID, 'cb_parallax', $output);
		}
		
		return true;
	}
	
	/**
	 * Migrates the stored options.
	 */
	private function migrate_stored_options() {
		
		$output = null;
		$input = get_option( 'cb_parallax_options' );
		if ( is_array( $input ) ) {
			$output = $this->migrate_options( $input );
			update_option( 'cb_parallax_options', $output, true );
		}
	}
	
	/**
	 * Replaces values that may were stored in a localized language with the English version
	 * and fixes a bug regarding the 'scroll down' action ( 'bottom' -> 'to bottom').
	 *
	 * @param array $post_meta
	 *
	 * @return array $post_meta
	 */
	private function migrate_options( $post_meta ) {
		
		$image_option_keys = $this->options->get_all_option_keys( 'image' );
		
		$options_meta = $this->options->get_options_arguments();
		foreach ( $image_option_keys as $i => $key ) {
			
			if ( 'select' === $options_meta[ $key ]['input_type'] ) {
				$stored_value = isset( $post_meta[ $key ] ) ? $post_meta[ $key ] : $options_meta[ $key ]['default_value'];
				$select_values = $options_meta[ $key ]['select_values'];
				
				foreach ( $select_values as $option_key => $select_value ) {
					
					// Replaces the value from a maybe localized value to its english version
					if ( $stored_value === $select_value ) {
						$post_meta[ $key ] = $option_key;
					}
					// Renames values or conforms values to English expressions
					if('cb_parallax_vertical_scroll_direction' === $key ){
						if( 'bottom' === $stored_value || 'to bottom' === $stored_value || 'runter' === $stored_value ){
							$post_meta[ $key ] = 'to bottom';
						}
						if ( 'top' === $stored_value || 'to top' === $stored_value || 'hoch' === $stored_value ) {
							$post_meta[ $key ] = 'to top';
						}
					}
					if ( 'cb_parallax_horizontal_scroll_direction' === $key ) {
						if ( 'left' === $stored_value || 'to the left' === $stored_value || 'nach links' === $stored_value ) {
							$post_meta[ $key ] = 'to the left';
						}
						if ( 'right' === $stored_value || 'to the right' === $stored_value || 'nach rechts' === $stored_value ) {
							$post_meta[ $key ] = 'to the right';
						}
					}
				}
			}
		}
		
		return $post_meta;
	}
	
}
