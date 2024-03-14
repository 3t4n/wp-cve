<?php
namespace CbParallax\Pub\Includes;

use CbParallax\Admin\Menu\Includes as MenuIncludes;
use WP_Post;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This class is responsible for localizing the public part of the plugin.
 *
 * @link              https://github.com/demispatti/cb-parallax
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/public/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 *  License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_public_localisation {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * The reference to the options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    MenuIncludes\cb_parallax_options $options
	 */
	private $options;

	/**
	 * Holds the current plugin options for the requested page or post
	 *
	 * @var array $plugin_options
	 */
	private $plugin_options;
	
	/**
	 * Holds the current image options for the requested page or post
	 *
	 * @var array $image_options
	 */
	private $image_options;
	
	/**
	 * Sets the stored plugin options.
	 *
	 * @param bool false | WP_Post $post
	 */
	private function set_plugin_options( $post = false ) {
		
		$this->plugin_options = $this->options->get_plugin_options( $post );
	}
	
	/**
	 * Sets the stored image options.
	 *
	 * @param bool false | \WP_Post $post
	 */
	private function set_image_options( $post = false ) {
		
		$this->image_options = $this->options->get_image_options( $post );
	}
	
	/**
	 * cb_parallax_public_localisation constructor.
	 *
	 * @param string $domain
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $options ) {
		
		$this->domain = $domain;
		$this->options = $options;
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'localize_frontend' ), 40 );
	}
	
	/**
	 * Retrieves the image data for the requested image,
	 * and stored it's width and height in an array.
	 *
	 * @param string $attachment_id
	 *
	 * @return array $image_data
	 */
	private function get_image_data( $attachment_id ) {
		
		$image_data = array();
		if ( '' !== $attachment_id ) {
			$image_attributes = wp_get_attachment_image_src( (int) $attachment_id, 'full' );
			
			$image_data['image_width'] = $image_attributes[1];
			$image_data['image_height'] = $image_attributes[2];
		} else {
			
			$image_data['image_width'] = 0;
			$image_data['image_height'] = 0;
		}
		
		return $image_data;
	}
	
	/**
	 * Assembles the data we're sending to the javascript file,
	 * and calls the WordPress function that localizes our javascript file.
	 *
	 * @return void
	 */
	public function localize_frontend() {
		
		/**
		 * @var WP_Post $post
		 */
		global $post;
		
		/**
		 * If the image was not found, we bail early.
		 */
		if ( false === $this->options->is_image_in_media_library( $post ) ) {
			return;
		}
		
		global $post;
		$this->set_plugin_options( $post );
		$this->set_image_options( $post );
		
		$none_string = array( 'none_string' => __( 'none', $this->domain ) );
		$stored_image_options = $this->options->get_image_options( $post );
		$stored_plugin_options = $this->options->get_plugin_options( $post );
		$attachment_id = $stored_image_options['cb_parallax_attachment_id'];
		$image_data = $this->get_image_data( $attachment_id );
		$overlay_options = $this->get_overlay_options( $stored_image_options );
		$stored_image_options['can_parallax'] = $this->can_parallax( $image_data );
		$has_nsr = '1' === get_transient( 'cb_parallax_has_nsr' ) ? '1' : '0';
		$image_source = $this->options->determine_options_source( $post );
		
		$data = array_merge(
			array( 'strings' => $none_string ),
			array( 'plugin_options' => $stored_plugin_options ),
			array( 'image_data' => $image_data ),
			array( 'image_options' => $stored_image_options ),
			array( 'overlay_options' => $overlay_options ),
			array( 'nicescrollr' => array( 'cb_parallax_has_nsr' => $has_nsr ) ),
			array( 'image_source' => array('source' => $image_source) )
		);
		
		$prepared_data = array();
		foreach ( $data as $section => $array ) {
			foreach ( $array as $key => $value ) {
				$key = str_replace( 'cb_parallax_', '', $key );
				$prepared_data[ $section ][ $key ] = $value;
			}
		}
		wp_localize_script( 'cb-parallax-public-js', 'Cb_Parallax_Public', $prepared_data );
		
		delete_transient( 'cb_parallax_has_nsr' );
	}
	
	/**
	 * Extracts the image overlay options from the stored options and puts them into an array.
	 *
	 * @param array $stored_image_options
	 *
	 * @return array $overlay_options
	 */
	private function get_overlay_options( $stored_image_options ) {
		
		$overlay_image_select_values = $this->options->get_image_options_whitelist();
		$overlay_image_select_values = $overlay_image_select_values['cb_parallax_overlay_image'];
		$overlay_image = isset( $stored_image_options['cb_parallax_overlay_image'] ) ? $stored_image_options['cb_parallax_overlay_image'] : 'none';
		
		// Replace the human readable overlay image name with the effective file name
		if ( 'none' !== $overlay_image ) {
			foreach ( $overlay_image_select_values as $key => $value ) {
				if ( $overlay_image === $value ) {
					$overlay_image = $key;
				}
			}
		}
		
		$overlay_options['cb_parallax_overlay_path'] = CBPARALLAX_ROOT_URL . 'public/images/overlays/';
		$overlay_options['cb_parallax_overlay_image'] = $overlay_image;
		$overlay_options['cb_parallax_overlay_color'] = $stored_image_options['cb_parallax_overlay_color'];
		$overlay_options['cb_parallax_overlay_opacity'] = $stored_image_options['cb_parallax_overlay_opacity'];
		
		return $overlay_options;
	}
	
	/**
	 * Checks the image for the minimum width and height required to make use of the parallax effect.
	 *
	 * @param array $image_data
	 *
	 * @return string $image_data javascript-conform value for true or false
	 */
	private function can_parallax( $image_data ) {
		
		$min_width = '1920';
		$min_height = '1200';
		
		return $image_data['image_width'] >= $min_width && $image_data['image_height'] >= $min_height ? '1' : '0';
	}

}
