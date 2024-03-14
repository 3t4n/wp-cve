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
class cb_parallax_theme_support {
	
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
		
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
	}
	
	/**
	 * Calls the WordPress function that adds theme support for the given feature.
	 */
	public function add_theme_support() {
		
		add_theme_support( $this->feature );
	}
	
}
