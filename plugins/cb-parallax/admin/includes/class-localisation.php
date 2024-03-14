<?php
namespace CbParallax\Admin\Includes;

use CbParallax\Admin\Includes as AdminIncludes;
use CbParallax\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for localizing the admin menu.
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
class cb_parallax_localisation {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * Holds a list of supported post types.
	 *
	 * @var array $screen_ids
	 */
	private $screen_ids;
	
	/**
	 * The reference to the options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    AdminIncludes\cb_parallax_options $options
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
	 * cb_parallax_admin constructor.
	 *
	 * @param string $domain
	 * @param array $screen_ids
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $screen_ids, $options ) {
		
		$this->domain = $domain;
		$this->screen_ids = $screen_ids;
		$this->options = $options;
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'retrieve_image_options' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'localize' ), 40 );
			add_action( 'admin_enqueue_scripts', array( $this, 'localize_media_frame' ), 40 );
		}
	}
	
	/**
	 * Retrieves the stored image options that are related to the requested page or post and assigns the values to it's member.
	 *
	 * @param bool false | \WP_Post $post
	 */
	public function retrieve_image_options() {
		
		$current_screen = get_current_screen();
		
		if ( in_array( $current_screen->base, $this->screen_ids ) && isset($_REQUEST['post']) ) {
			$section = 'image';
			$image_options = get_post_meta( (int) $_REQUEST['post'], 'cb_parallax', true );
		} else {
			$section = '';
			$image_options = get_option( 'cb_parallax_options' );
		}
		$default_options = $this->options->get_default_options( $section );
		
		// If we have no related post meta data, we don't do anything here.
		if ( false == $image_options || '' == $image_options ) {
			$image_options = $default_options;
		} else {
			$image_options = array_merge( $default_options, $image_options );
		}
		
		$post_data = null;
		$options = null;
		$pattern = '/cb_parallax_/';
		$excluded_options = array(
			'cb_parallax_background_color',
			'cb_parallax_overlay_color',
			'cb_parallax_attachment_id',
			'cb_parallax_background_image_url'
		);
		$whitelist = array_merge( $this->options->get_image_options_whitelist(), $this->options->get_plugin_options_whitelist() );
		$default_options = array_merge( $this->options->get_default_image_options(), $this->options->get_default_plugin_options() );
		// Match the option keys against the image option values, filtered trough the options-whitelist
		foreach ( $default_options as $option_key => $value ) {
			
			if ( ! in_array( $option_key, $excluded_options ) ) {
				
				if ( isset( $image_options[ $option_key ] ) ) {
					// Remove the prefix
					$key = preg_replace( $pattern, '', $option_key );
					// Prepare the option key for the script
					$key = lcfirst( implode( '', array_map( 'ucfirst', explode( '_', $key ) ) ) );
					
					if ( in_array( $image_options[ $option_key ], $whitelist[ $option_key ] ) ) {
						
						$options[ $key ] = $image_options[ $option_key ];
					} else {
						$options[ $key ] = $default_options[ $option_key ];
					}
				}
			}
		}
		
		// The following values have no defaults, so we check them "by hand":
		// We retrieve these values "by hand" since there is no default value that could be used as a pattern to match against.
		$colors['backgroundColor'] = isset( $image_options['cb_parallax_background_color'] ) ? $image_options['cb_parallax_background_color'] : '';
		$colors['overlayColor'] = isset( $image_options['cb_parallax_overlay_color'] ) ? $image_options['cb_parallax_overlay_color'] : '';
		
		// Check the color values
		foreach ( $colors as $color_key => $color_value ) {
			
			if ( isset( $color_value ) && ! preg_match( '/^#[a-f0-9]{3,6}$/i', $color_value ) ) {
				
				$options[ $color_key ] = '';
			} else {
				$options[ $color_key ] = $color_value;
			}
		}
		// Add the attachment id.
		$attachment_id = $options['attachmentId'] = isset( $image_options['cb_parallax_attachment_id'] ) ? $image_options['cb_parallax_attachment_id'] : '';
		// If an attachment ID was found, get the image source.
		$image = null;
		if ( false !== $attachment_id ) {
			$image = wp_get_attachment_image_src( absint( $attachment_id ), 'full' );
		}
		// Set the url.
		$options['backgroundImageUrl'] = isset( $image[0] ) ? $image[0] : '';
		// Set the image dimensions.
		$options['attachmentWidth'] = isset( $image[1] ) ? $image[1] : '';
		$options['attachmentHeight'] = isset( $image[2] ) ? $image[2] : '';
		
		$this->image_options = $options;
	}
	
	/**
	 * Assembles the data we're sending to the javascript file,
	 * and calls the WordPress function that localizes our javascript file.
	 *
	 * @return void
	 */
	public function localize() {
		
		global $post;
		$this->set_plugin_options( $post );
		$this->set_image_options( $post );
		
		$plugin_option_keys = $this->options->get_all_option_keys( 'plugin' );
		$image_option_keys = $this->options->get_all_option_keys( 'image' );
		$all_option_keys = $this->options->get_all_option_keys();
		$default_options = $this->options->get_default_options();
		$stored_image_options = $this->image_options;
		$attachment_id = $stored_image_options['cb_parallax_attachment_id'];
		$image_data = $this->get_image_data( $attachment_id );
		$strings = $this->get_strings();
		
		/**
		 * If the image was not found, we bail early.
		 */
		if ( false === $this->options->is_image_in_media_library( $post ) ) {
			$stored_image_options['cb_parallax_attachment_id'] = '';
			$stored_image_options['cb_parallax_background_image_url'] = '';
		}
		$stored_image_options['can_parallax'] = $this->can_parallax( $image_data );
		
		$data = array_merge(
			array( 'image_options' => $stored_image_options ),
			array( 'option_keys' => array( 'all' => $all_option_keys, 'plugin' => $plugin_option_keys, 'image' => $image_option_keys ) ),
			array( 'default_options' => $default_options ),
			array( 'image_data' => $image_data ),
			array( 'strings' => $strings )
		);
		
		wp_localize_script( 'cb-parallax-settings-display-js', 'Cb_Parallax_Admin', $data );
	}
	
	/**
	 * Assembles the data we're sending to the javascript file,
	 * and calls the WordPress function that localizes our javascript file.
	 *
	 * @return void
	 */
	public function localize_media_frame() {
		
		wp_localize_script( 'cb-parallax-settings-display-js', 'cbParallaxMediaFrame', array(
			'title' => __( 'Set Background Image', $this->domain ),
			'button' => __( 'Set background image', $this->domain ),
		) );
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
		
		$image_data = null;
		if ( '' !== $attachment_id ) {
			$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
			
			$image_data['image_width'] = $image_attributes[1];
			$image_data['image_height'] = $image_attributes[2];
		} else {
			
			$image_data['image_width'] = 0;
			$image_data['image_height'] = 0;
		}
		
		return $image_data;
	}
	
	/**
	 * Returns an array containing arrays of strings that may be translated into the user-defined locale.
	 * They'll be displayed via the javascript file.
	 *
	 * @return array
	 */
	public function get_strings() {
		
		return array_merge(
			array(
				'vertical_string' => __( 'vertical', $this->domain )
			),
			array(
				'save_options_ok' => __( 'Background image settings saved.', $this->domain ),
				'save_options_error' => __( 'Error saving the background image settings.', $this->domain ) . ' ' . __( 'Please reload the page and try again.', $this->domain ),
				'reset_options_ok' => __( 'Background image settings resetted.', $this->domain ),
				'reset_options_error' => __( 'Error resetting the background image settings.', $this->domain ) . ' ' . __( 'Please reload the page and try again.', $this->domain )
			),
			array(
				'reset_options_confirmation' => __( 'Reset the settings to default?', $this->domain )
			),
			// Switches Texts
			array(
				'locale' => get_locale(),
				'switches_text' => array( 'On' => __( 'On', $this->domain ), 'Off' => __( 'Off', $this->domain ) ),
			),
			// Background Color Texts
			array(
				'background_color_text' => __( 'Background Color', $this->domain ),
				'overlay_color_text' => __( 'Overlay Color', $this->domain ),
				'none_string' => __( 'none', $this->domain ),
			),
			// Heading Texts
			array(
				'image_title_text' => __( 'Background Image', $this->domain ),
				'image_section_title_text' => __( 'Background Image Settings', $this->domain ),
				'plugin_section_title_text' => __( 'General Settings', $this->domain )
			),
			// Reset Settings Confirmation
			array(
				'reset_settings_confirmation' => __( 'Do you really want to reset the settings?', $this->domain )
			)
		);
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
