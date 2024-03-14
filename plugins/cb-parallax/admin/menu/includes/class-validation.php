<?php
namespace CbParallax\Admin\Menu\Includes;

use CbParallax\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for sanitizing and validating the user inputs.
 *
 * @link
 * @since             0.6.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_validation {
	
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
	 * Maintains the whitelist for the image options.
	 *
	 * @since  0.6.0
	 * @access public
	 * @var    array $image_options_whitelist
	 */
	public $image_options_whitelist;
	
	/**
	 * Maintains the whitelist for the plugin options.
	 *
	 * @since  0.6.0
	 * @access public
	 * @var    array $plugin_options_whitelist
	 */
	public $plugin_options_whitelist;
	
	/**
	 * Maintains the default image image options
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $default_image_options
	 */
	public $default_image_options;
	
	/**
	 * cb_parallax_admin constructor.
	 *
	 * @param string $domain
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $options ) {
		
		$this->domain = $domain;
		$this->options = $options;
		
		$this->retrieve_options();
	}
	
	/**
	 * Retrieves and sets the whitelists for the plugin and image options
	 * and the default options, too.
	 */
	private function retrieve_options() {
		
		$this->image_options_whitelist = $this->options->get_image_options_whitelist();
		$this->plugin_options_whitelist = $this->options->get_plugin_options_whitelist();
		$this->default_image_options = $this->options->get_default_image_options();
	}
	
	/**
	 * Calls the functions to sanitize and validate the user input.
	 *
	 * @param string $post_id
	 * @param array $input
	 *
	 * @return array
	 */
	public function run( $post_id, $input ) {
		
		$input = $this->sanitize( $input );
		
		return $this->validate_input( $post_id, $input );
	}
	
	/**
	 * Sanitizes the input values.
	 *
	 * @param array $input
	 *
	 * @return array $output
	 */
	private function sanitize( $input ) {
		
		$output = array();
		
		foreach ( $input as $key => $value ) {
			
			if ( isset ( $input[ $key ] ) ) {
				$output[ $key ] = strip_tags( stripslashes( $value ) );
			}
		}
		
		return apply_filters( 'sanitize', $output, $input );
	}
	
	/**
	 * Validates the user input.
	 *
	 * @param string $post_id
	 * @param array $input
	 *
	 * @return array $output
	 */
	public function validate_input( $post_id, $input ) {
		
		if ( '' === $post_id ) {
			$defaults = $this->options->get_default_options();
		} else {
			$defaults = $this->options->get_default_image_options();
		}
		
		$options_meta = $this->options->get_options_arguments( 'image' );
		$data = array();
		$validation_value = null;
		$rgba_pattern = '/(^[a-zA-Z]+$)|(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i';
		$url_pattern = '/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i';
		
		foreach ( $input as $option_key => $value ) {
			
			switch ( $option_key ) {
				case ( $option_key === 'cb_parallax_background_color' || $option_key === 'cb_parallax_overlay_color' );
					if ( ! preg_match( $rgba_pattern, $value ) ) {
						$value = $defaults[ $option_key ];
					}
					break;
				
				case ( $option_key === 'cb_parallax_background_image_url' );
					if ( ! preg_match( $url_pattern, $value ) ) {
						$value = $defaults[ $option_key ];
					}
					break;
			}
			// The array holding the processed values.
			$data[ $option_key ] = $value;
		}
		
		$output = array();
		foreach ( $data as $option_key => $value ) {
			
			if ( isset( $options_meta[ $option_key ]['input_type'] ) && 'checkbox' === $options_meta[ $option_key ]['input_type'] ) {
				$output[ $option_key ] = '1' === $value ? '1' : '0';
			} else if ( isset( $options_meta[ $option_key ]['input_type'] ) && 'select' === $options_meta[ $option_key ]['input_type'] ) {
				if ( ! in_array( $value, $options_meta[ $option_key ]['select_values'] ) ) {
					$output[ $option_key ] = $options_meta[ $option_key ]['default_value'];
				} else {
					$output[ $option_key ] = $value;
				}
			} else {
				$output[ $option_key ] = $value;
			}
		}
		
		return apply_filters( 'validate', $output, $input );
	}
	
}
