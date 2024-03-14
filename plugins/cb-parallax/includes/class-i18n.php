<?php
namespace CbParallax\Includes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Defines the internationalization functionality.
 * Loads and defines the internationalization files for this plugin.
 *
 * @link
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_i18n {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * cb_parallax_i18n constructor.
	 *
	 * @param string $domain
	 */
	public function __construct( $domain ) {
		
		$this->domain = $domain;
		
		$this->add_hooks();
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}
	
	/**
	 * Calls the WordPress function that loads this plugin's translated strings.
	 */
	public function load_plugin_textdomain() {
		
		load_plugin_textdomain( $this->domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}
	
}
