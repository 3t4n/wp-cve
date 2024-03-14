<?php
namespace CbParallax\Pub;

use CbParallax\Pub\Includes as PublicIncludes;
use CbParallax\Admin\Menu\Includes as MenuIncludes;
use WP_Post;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Require dependencies.
 */
if ( ! class_exists( 'PublicIncludes\cb_parallax_public_localisation' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'public/includes/class-public-localisation.php';
}

/**
 * The class responsible for the public facing part of the plugin.
 *
 * @link
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/public
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_public {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * Holds the reference to the options class.
	 *
	 * @var    MenuIncludes\cb_parallax_options $options
	 * @since  0.6.0
	 * @access private
	 */
	private $options;
	
	/**
	 * Holds the user-defined plugin settings.
	 *
	 * @var array $plugin_options
	 */
	private $plugin_options;
	
	/**
	 * Retrieves the user-defined plugin settings.
	 *
	 * @param bool false | WP_Post $post
	 */
	private function set_plugin_options( $post = false ) {
		
		$this->plugin_options = $this->options->get_plugin_options( $post );
	}
	
	/**
	 * cb_parallax_public constructor.
	 *
	 * @param string $domain
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $options ) {
		
		$this->domain = $domain;
		$this->options = $options;
		
		$this->set_plugin_options();
		$this->include_public_localisation();
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
	}
	
	/**
	 * Registers the stylesheets with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function enqueue_styles() {
		
		global $post;
		
		/**
		 * If the image was not found, we bail early.
		 */
		if ( false === $this->options->is_image_in_media_library( $post ) || false === $this->options->has_stored_options() ) {
			return;
		}
		
		wp_enqueue_style( 'cb-parallax-public-css', CBPARALLAX_ROOT_URL . 'public/css/public.css', array(), 'all', 'all' );
	}
	
	/**
	 * Registers the javascript files with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function enqueue_scripts() {
		
		/**
		 * @var WP_Post $post
		 */
		global $post;
		
		/**
		 * If the image was not found, we bail early.
		 */
        /**
         * If the image was not found, we bail early.
         */
        if (false === $this->options->is_image_in_media_library($post) || false === $this->options->has_stored_options()) {
            return;
        }
		
		$this->set_plugin_options( $post );
		
		wp_enqueue_script(
			'cb-parallax-inc-raf-js',
			CBPARALLAX_ROOT_URL . 'vendor/raf/raf.js',
			array( 'jquery' ),
			'all',
			true
		);
		
		wp_enqueue_script(
			'cb-parallax-inc-smoothscroll-min-js',
			CBPARALLAX_ROOT_URL . 'vendor/smoothscroll/smoothscroll.js',
			array( 'jquery' ),
			'all',
			true
		);
		
		// Parallax script.
		wp_enqueue_script(
			'cb-parallax-public-js',
			CBPARALLAX_ROOT_URL . 'public/js/public.js',
			array( 'jquery', 'cb-parallax-inc-raf-js', 'cb-parallax-inc-smoothscroll-min-js' ),
			'all',
			true
		);
	}
	
	/**
	 * Includes the class responsible for assembling and sending data to the respective javascript file.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	private function include_public_localisation() {
		
		$public_localisation = new PublicIncludes\cb_parallax_public_localisation( $this->domain, $this->options );
		$public_localisation->add_hooks();
	}
	
}
