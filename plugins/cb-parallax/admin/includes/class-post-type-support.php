<?php
namespace CbParallax\Admin\Includes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Enables the custom background support for the given post types.
 *
 * @link
 * @since             0.1.0
 * @package           cb_paraallax
 * @subpackage        cb_parallax/admin/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_post_type_support {
	
	/**
	 * The available post types.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      array $post_types
	 */
	private $post_types = array( 'post', 'page', 'product', 'portfolio', 'books', 'movies', 'projects', 'work' );
	
	/**
	 * The feature we want the post types to work with.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $feature
	 */
	private $feature = 'custom-background';
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'init', array( $this, 'add_post_type_support' ) );
	}
	
	/**
	 * Calls the WordPress function that adds support for the given feature applied on the given post type.
	 */
	public function add_post_type_support() {
		
		foreach ( $this->post_types as $post_type ) {
			
			add_post_type_support( $post_type, $this->feature );
		}
	}
	
	/**
	 * Returns an array containing the supported post types.
	 *
	 * @return array
	 */
	public function get_supported_post_types() {
		
		return $this->post_types;
	}
	
}
