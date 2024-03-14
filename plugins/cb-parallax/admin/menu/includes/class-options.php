<?php
namespace CbParallax\Admin\Menu\Includes;

use CbParallax\Admin\Menu\Includes as MenuIncludes;
use WP_Error;
use WP_Post;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class that maintains the default image_options and the related meta data.
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
class cb_parallax_options {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
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
	 * Holds the image options and their arguments.
	 *
	 * @return array
	 */
	private function image_options() {
		
		return array(
			'cb_parallax_attachment_id' => array(
				'option_key' => 'cb_parallax_attachment_id',
				'name' => __( 'Attachment ID', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => '',
				'default_value' => '',
				'input_type' => 'none',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cb_parallax_background_image_url' => array(
				'option_key' => 'cb_parallax_background_image_url',
				'name' => __( 'Background Image', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => '',
				'default_value' => '',
				'input_type' => 'media',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cb_parallax_background_color' => array(
				'option_key' => 'cb_parallax_background_color',
				'name' => __( 'Background Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Set a background color. Best to be used with partially transparent images.', $this->domain ),
				'default_value' => '',
				'input_type' => 'color',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cb_parallax_parallax_enabled' => array(
				'option_key' => 'cb_parallax_parallax_enabled',
				'name' => __( 'Enable Parallax Features', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Enable parallax', $this->domain ),
				'default_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cb_parallax_background_repeat' => array(
				'option_key' => 'cb_parallax_background_repeat',
				'name' => __( 'Image Repeat', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Set repeat property.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_background_repeat']['repeat-y'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_background_repeat'],
			),
			'cb_parallax_position_x' => array(
				'option_key' => 'cb_parallax_position_x',
				'name' => __( 'Horizontal Position', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Set the horizontal position of the background image.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_position_x']['center'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_position_x'],
			),
			'cb_parallax_position_y' => array(
				'option_key' => 'cb_parallax_position_y',
				'name' => __( 'Vertical Position', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Set the vertical position of the background image.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_position_y']['center'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_position_y'],
			),
			'cb_parallax_background_attachment' => array(
				'option_key' => 'cb_parallax_background_attachment',
				'name' => __( 'Background Attachment', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Set the attachment.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_background_attachment']['fixed'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_background_attachment'],
			),
			'cb_parallax_direction' => array(
				'option_key' => 'cb_parallax_direction',
				'name' => __( 'Image Moving Direction', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Choose horizontal or vertical parallax direction.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_direction']['vertical'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_direction'],
			),
			'cb_parallax_vertical_scroll_direction' => array(
				'option_key' => 'cb_parallax_vertical_scroll_direction',
				'name' => __( 'While Scrolling Down The Background Image Moves', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Choose whether the image should move up or downwards on page scroll.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_vertical_scroll_direction']['top'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_vertical_scroll_direction'],
			),
			'cb_parallax_horizontal_scroll_direction' => array(
				'option_key' => 'cb_parallax_horizontal_scroll_direction',
				'name' => __( 'While Scrolling Down The Background Image Moves', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Choose whether the image should move to the left or to the right on page scroll.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_horizontal_scroll_direction']['left'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_horizontal_scroll_direction']
			),
			'cb_parallax_horizontal_alignment' => array(
				'option_key' => 'cb_parallax_horizontal_alignment',
				'name' => __( 'Horizontal Alignment', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Align the image horizontally.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_horizontal_alignment']['center'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_horizontal_alignment'],
			),
			'cb_parallax_vertical_alignment' => array(
				'option_key' => 'cb_parallax_vertical_alignment',
				'name' => __( 'Vertical Alignment', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Align the image vertically.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_vertical_alignment']['center'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_vertical_alignment']
			),
			'cb_parallax_overlay_image' => array(
				'option_key' => 'cb_parallax_overlay_image',
				'name' => __( 'Overlay Image', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Select an overlay image if you like.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_overlay_image']['none'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_overlay_image']
			),
			'cb_parallax_overlay_opacity' => array(
				'option_key' => 'cb_parallax_overlay_opacity',
				'name' => __( 'Overlay Opacity', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Set overlay opacity.', $this->domain ),
				'default_value' => $this->image_options_whitelist['cb_parallax_overlay_opacity']['default'],
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => $this->image_options_whitelist['cb_parallax_overlay_opacity']
			),
			'cb_parallax_overlay_color' => array(
				'option_key' => 'cb_parallax_overlay_color',
				'name' => __( 'Overlay Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Select overlay color.', $this->domain ),
				'default_value' => '',
				'input_type' => 'color',
				'notice_level' => 'none',
				'select_values' => 'none'
			),
		);
	}
	
	/**
	 * Holds the plugin options and their arguments.
	 *
	 * @return array
	 */
	private function plugin_options() {
		
		return array(
			
			'cb_parallax_global' => array(
				'option_key' => 'cb_parallax_global',
				'name' => __( 'Use These Settings On Every Page', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Enable this feature to apply this image and it\'s settings to all supported post types.', $this->domain ),
				'default_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cb_parallax_allow_override' => array(
				'option_key' => 'cb_parallax_allow_override',
				'name' => __( 'Allow Custom Settings On Posts And Pages', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Individual settings on a per post -/ per page basis will be used instead of this image and it\'s settings. On pages where no background image is defined, the above image will be displayed, if one is defined.', $this->domain ),
				'default_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cb_parallax_disable_on_mobile' => array(
				'option_key' => 'cb_parallax_disable_on_mobile',
				'name' => __( 'Disable Parallax Effect On Mobile Devices', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'background-image',
				'description' => __( 'Disable parallax-effect on mobile devices. This may be useful <i>if</i> you encounter performance issues.', $this->domain ),
				'default_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			)
		);
	}
	
	/**
	 * Holds all whitelisted image options.
	 *
	 *
	 * @return mixed
	 */
	private function image_options_whitelist() {
		
		$image_options_whitelist['cb_parallax_attachment_id'] = array();
		
		$image_options_whitelist['cb_parallax_background_image_url'] = array();
		
		$image_options_whitelist['cb_parallax_background_color'] = array();
		
		$image_options_whitelist['cb_parallax_parallax_enabled'] = array(
			'off' => false,
			'on' => true,
		);
		
		// Image image_options for a static background image.
		$image_options_whitelist['cb_parallax_position_x'] = array(
			'left' => __( 'left', $this->domain ),
			'center' => __( 'center', $this->domain ),
			'right' => __( 'right', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_position_y'] = array(
			'top' => __( 'top', $this->domain ),
			'center' => __( 'center', $this->domain ),
			'bottom' => __( 'bottom', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_background_attachment'] = array(
			'fixed' => __( 'fixed', $this->domain ),
			'scroll' => __( 'scroll', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_background_repeat'] = array(
			'no-repeat' => __( 'no-repeat', $this->domain ),
			'repeat' => __( 'repeat', $this->domain ),
			'repeat-x' => __( 'horizontal', $this->domain ),
			'repeat-y' => __( 'vertical', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_direction'] = array(
			'vertical' => __( 'vertical', $this->domain ),
			'horizontal' => __( 'horizontal', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_vertical_scroll_direction'] = array(
			'top' => __( 'to top', $this->domain ),
			'bottom' => __( 'to bottom', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_horizontal_scroll_direction'] = array(
			'left' => __( 'to the left', $this->domain ),
			'right' => __( 'to the right', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_horizontal_alignment'] = array(
			'left' => __( 'left', $this->domain ),
			'center' => __( 'center', $this->domain ),
			'right' => __( 'right', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_vertical_alignment'] = array(
			'top' => __( 'top', $this->domain ),
			'center' => __( 'center', $this->domain ),
			'bottom' => __( 'bottom', $this->domain ),
		);
		
		$image_options_whitelist['cb_parallax_overlay_image'] = array_merge(
			$this->convert_overlay_image_names_for_display( $this->retrieve_overlay_image_names() ),
			array( 'none' => __( 'none', $this->domain ) )
		);
		
		$image_options_whitelist['cb_parallax_overlay_opacity'] = array(
			'default' => __( 'default', $this->domain ),
			'0.1' => '0.1',
			'0.2' => '0.2',
			'0.3' => '0.3',
			'0.4' => '0.4',
			'0.5' => '0.5',
			'0.6' => '0.6',
			'0.7' => '0.7',
			'0.8' => '0.8',
			'0.9' => '0.9',
			'0.0' => __( 'none', $this->domain )
		);
		
		$image_options_whitelist['cb_parallax_overlay_color'] = '';
		
		return $image_options_whitelist;
	}
	
	/**
	 * Holds all whitelisted plugin options.
	 *
	 * @return mixed
	 */
	private function plugin_options_whitelist() {
		
		$plugin_options['cb_parallax_global'] = array(
			'off' => false,
			'on' => true,
		);
		
		$plugin_options['cb_parallax_allow_override'] = array(
			'off' => false,
			'on' => true,
		);
		
		$plugin_options['cb_parallax_preserve_scrolling'] = array(
			'off' => false,
			'on' => true,
		);
		
		$plugin_options['cb_parallax_disable_on_mobile'] = array(
			'off' => false,
			'on' => true,
		);
		
		return $plugin_options;
	}
	
	/**
	 * cb_parallax_admin constructor.
	 *
	 * @param string $domain
	 */
	public function __construct( $domain ) {
		
		$this->domain = $domain;
		
		$this->image_options_whitelist = $this->image_options_whitelist();
		$this->plugin_options_whitelist = $this->plugin_options_whitelist();
	}
	
	/**
	 * Returns an array containing the whitelisted image options.
	 *
	 * @return array|mixed
	 */
	public function get_image_options_whitelist() {
		
		return $this->image_options_whitelist;
	}
	
	/**
	 * Returns an array containing the whitelisted plugin options.
	 *
	 * @return array|mixed
	 */
	public function get_plugin_options_whitelist() {
		
		return $this->plugin_options_whitelist;
	}
	
	/**
	 * Returns an array containing the default image options as key/value pairs.
	 *
	 * @return array $default_image_options
	 */
	public function get_default_image_options() {
		
		$default_image_options = array();
		
		$image_options = $this->image_options();
		
		foreach ( $image_options as $option_key => $args ) {
			
			$default_image_options[ $option_key ] = $args['default_value'];
		}
		
		return $default_image_options;
	}
	
	/**
	 * Returns an array containing the default plugin options as key/value pairs.
	 *
	 * @return array $default_plugin_options
	 */
	public function get_default_plugin_options() {
		
		$default_plugin_options = array();
		
		$options = $this->plugin_options();
		
		foreach ( $options as $option_key => $args ) {
			
			$default_plugin_options[ $option_key ] = $args['default_value'];
		}
		
		return $default_plugin_options;
	}
	
	/**
	 * Returns an array containing the default options per section or all of them.
	 *
	 * @param string $section
	 *
	 * @return array
	 */
	public function get_default_options( $section = '' ) {
		
		if ( 'image' == $section ) {
			
			return $this->get_default_image_options();
		}
		
		return array_merge( $this->get_default_image_options(), $this->get_default_plugin_options() );
	}
	
	/**
	 * Returns an array containing the option keys per section or all of them.
	 *
	 * @param string $section
	 *
	 * @return array $keys
	 */
	public function get_all_option_keys( $section = '' ) {
		
		if ( 'plugin' === $section ) {
			$option_keys = $this->get_default_plugin_options();
		} else if ( 'image' === $section ) {
			$option_keys = $this->get_default_image_options();
		} else {
			$option_keys = array_merge( $this->get_default_image_options(), $this->get_default_plugin_options() );
		}
		
		$keys = array();
		
		foreach ( $option_keys as $name => $option_key ) {
			$keys[] = $name;
		}
		
		return $keys;
	}
	
	/**
	 * Returns an array containing the option arguments per section or all of them.
	 *
	 * @param string $section
	 *
	 * @return array $args
	 */
	public function get_options_arguments( $section = '' ) {
		
		if ( 'plugin' === $section ) {
			$options = $this->plugin_options();
		} else if ( 'image' === $section ) {
			$options = $this->image_options();
		} else {
			$options = array_merge( $this->image_options(), $this->plugin_options() );
		}
		
		$args = array();
		
		foreach ( $options as $option_key => $arguments ) {
			
			$args[ $option_key ] = array(
				'option_key' => $arguments['option_key'],
				'name' => $arguments['name'],
				'settings_group' => $arguments['settings_group'],
				'description' => $arguments['description'],
				'input_type' => $arguments['input_type'],
				'default_value' => $arguments['default_value'],
				'select_values' => $arguments['select_values'],
			);
		}
		
		return $args;
	}
	
	/**
	 * Returns the section heading that will be displayed in the settings form.
	 *
	 * @param string $section
	 *
	 * @return array|bool
	 */
	public function get_section_heading( $section ) {
		
		$background_image_heading = array(
			'option_key' => 'cb_parallax_image_options',
			'name' => __( 'Background Image', $this->domain ),
			'settings_group' => 'background-image',
			'description' => __( 'Customize the background image image_options.', $this->domain ),
			'callback' => 'background_image_settings_section_callback',
			'class' => 'icon icon-equalizer',
		);
		
		$plugin_heading = array(
			'option_key' => 'cb_parallax_parallax_options',
			'name' => __( 'General Settings', $this->domain ),
			'settings_group' => 'plugin',
			'description' => __( 'General settings.', $this->domain ),
			'callback' => 'plugin_settings_section_callback',
			'class' => 'icon icon-equalizer',
		);
		
		switch ( $section ) {
			
			case( 'background-image' == $section );
				$heading = $background_image_heading;
				break;
			
			case( 'plugin' == $section );
				$heading = $plugin_heading;
				break;
			default:
				return false;
		}
		
		return $heading;
	}
	
	/**
	 * Retrieves the user-defined options.
	 *
	 * @return array|bool|mixed
	 */
	public function fetch_options() {
		
		global $post;
		$options = false;
		
		// Determines if we get the image data from the post meta or from the image_options array.
		$source_type = $this->determine_image_source();
		
		if ( $source_type === 'per_post' ) {
			
			$page_for_posts = get_option( 'page_for_posts' );
			$post_types = array( 'product', 'portfolio', 'post', 'page' );
			$current_post_type = isset( $post ) ? get_post_type( $post ) : '';
			
			if ( false !== $current_post_type && in_array( $current_post_type, $post_types, true ) && false == ( ! is_front_page() && is_home() ) ) {
				
				$options = get_post_meta( $post->ID, 'cb_parallax', true );
			} else if ( false != $page_for_posts ) {
				
				$options = get_post_meta( $page_for_posts, 'cb_parallax', true );
			} else {
				
				if ( isset( $post ) ) {
					$options = get_post_meta( $post->ID, 'cb_parallax', true );
				}
			}
			
			if ( is_array( $options ) ) {
				
				$options = array_merge( $this->get_default_image_options(), $options );
			}
			
			return $options;
		}
		
		return get_option( 'cb_parallax_options' );
	}
	
	/**
	 * Determines where to fetch the image from.
	 *
	 * - First we need to check if the settings will be applied 'global'.
	 * - Then, since there is a plugin-related option to override the settings on a per post-basis,
	 *   we need to check that setting too.
	 * - At last, we check if the requested image exists and can be served
	 *
	 * @return string $result
	 */
	private function determine_image_source() {
		
		global $post;
		
		$post_meta = isset( $post ) ? get_post_meta( $post->ID, 'cb_parallax', true ) : false;
		$post_has_image = isset( $post_meta['cb_parallax_attachment_id'] ) ? $post_meta['cb_parallax_attachment_id'] : false;
		$options = get_option( 'cb_parallax_options' );
		$is_global = isset( $options['cb_parallax_global'] ) ? $options['cb_parallax_global'] : false;
		$allow_override = isset( $options['cb_parallax_allow_override'] ) ? $options['cb_parallax_allow_override'] : false;
		$attachment_id = isset( $options['cb_parallax_attachment_id'] ) ? $options['cb_parallax_attachment_id'] : '';
		
		$source_type = null;
		
		if ( ! $is_global || ( $is_global && $attachment_id === '' ) ) {
			$source_type = 'per_post';
		} else if ( $is_global && $allow_override && $post_has_image ) {
			$source_type = 'per_post';
		} else {
			$source_type = 'global';
		}
		
		return $source_type;
	}
	
	/**
	 * Retrieves and puts the file names of the overlay images into an array and returns it.
	 *
	 * @return mixed | bool false | array $names_list
	 */
	private function retrieve_overlay_image_names() {
		
		$path = CBPARALLAX_ROOT_DIR . 'public/images/overlays/';
		$excluded = array( '.', '..', '.DS_Store' );
		$names_list = array();
		
		if ( $handle = opendir( $path ) ) {
			while( false !== ( $entry = readdir( $handle ) ) ) {
				if ( ! in_array( $entry, $excluded ) ) {
					$names_list[] = strip_tags( $entry );
				}
			}
			closedir( $handle );
			
			return $names_list;
		} else {
			
			return false;
		}
	}
	
	/**
	 * Converts the overlay image file names into 'human readable' ones.
	 *
	 * @param array $input
	 *
	 * @return array $output
	 */
	private function convert_overlay_image_names_for_display( $input ) {
		
		$output = array();
		
		foreach ( $input as $option_key => $value ) {
			// Remove the file extension
			$name = preg_replace( array( '/.png/', '/-/' ), array( '', ' ' ), $value );
			if ( preg_match( '/\s/', $name ) ) {
				// Remove whitespace and capitalize.
				$name = implode( ' ', array_map( 'ucfirst', explode( ' ', $name ) ) );
				$output[ $value ] = $name;
			} else {
				$output[ $value ] = ucfirst( $name );
			}
		}
		
		return $output;
	}
	
	/**
	 * Returns the plugin options based on the plugin settings or the post meta data respectively.
	 *
	 * @param mixed | WP_Post $post | bool false
	 *
	 * @return array
	 */
	public function get_plugin_options( $post = false ) {
		
		$plugin_options = array();
		$stored_options = get_option( 'cb_parallax_options' );
		$is_enabled = null;
		$plugin_options_keys = $this->get_all_option_keys( 'plugin' );
		$plugin_options_keys[] = 'cb_parallax_parallax_enabled';
		
		// If a page or post is requested AND the options are fetched on a per post basis
		if ( is_a( $post, '\WP_Post' ) && 'per_page' === $this->determine_options_source( $post ) ) {
			/**
			 * @var WP_Post $post
			 */
			$post_meta = get_post_meta( $post->ID, 'cb_parallax', true );
			// Check the stored value and set it to '0' if it isn't set
			$stored_options['cb_parallax_parallax_enabled'] = isset( $stored_options['cb_parallax_parallax_enabled'] ) ? $stored_options['cb_parallax_parallax_enabled'] : '0';
			// Maybe overwrite the value with the one from the post meta data
			$stored_options['cb_parallax_parallax_enabled'] = isset( $post_meta['cb_parallax_parallax_enabled'] ) ? $post_meta['cb_parallax_parallax_enabled'] : $stored_options['cb_parallax_parallax_enabled'];
		}
		
		foreach ( $plugin_options_keys as $i => $key ) {
			$plugin_options[ $key ] = isset( $stored_options[ $key ] ) ? $stored_options[ $key ] : '0';
		}
		
		return $plugin_options;
	}
	
	/**
	 * Returns the image options based on the plugin settings or the post meta data respectively.
	 *
	 * @param mixed | WP_Post $post | bool false
	 *
	 * @return array $image_options
	 */
	public function get_image_options( $post = false ) {
		
		$stored_options = get_option( 'cb_parallax_options' );
		$options = false !== $stored_options ? $stored_options : $this->get_default_options();
		$image_options = array();
		$default_plugin_options = $this->get_default_plugin_options();
		
		// If a page or post is requested AND the options are fetched on a per post basis
		$serve_image_how = $this->determine_options_source( $post );
		if ( is_a( $post, '\WP_Post' ) ) {
			/**
			 * @var WP_Post $post
			 */
			//$serve_image_how = $this->determine_options_source( $post );
			if ( (! is_admin() && 'per_page' === $serve_image_how) || is_admin() ) {
				$post_meta = get_post_meta( $post->ID, 'cb_parallax', true );
				// Merge the optons to get the plugin options combined with the overridden image options from 'post meta'.
				if ( is_array( $post_meta ) ) {
					$options = array_merge( $options, $post_meta );
				} else {
					$options = array_merge( $default_plugin_options, $options );
				}
			}
		}
		
		$image_options_keys = $this->get_all_option_keys( 'image' );
		foreach ( $image_options_keys as $i => $key ) {
			$image_options[ $key ] = isset( $options[ $key ] ) ? $options[ $key ] : '0';
		}
		
		return $image_options;
	}
	
	/**
	 * Determines where to fetch the options from.
	 *
	 * - First we need to check if the settings will be applied 'global'.
	 * - Then, since there is a plugin-related option to override the settings on a per post-basis,
	 *   we need to check that setting too.
	 * - At last, we check if the requested image exists and can be served
	 *
	 * @param WP_Post $post
	 *
	 * @return string $result
	 */
	public function determine_options_source( $post ) {
		
		$stored_options = get_option( 'cb_parallax_options' );
		$is_sidewide = isset($stored_options['cb_parallax_global']) && '1' === $stored_options['cb_parallax_global'];
		$is_per_page_allowed = isset($stored_options['cb_parallax_allow_override']) && '1' === $stored_options['cb_parallax_allow_override'];
		$is_post = isset($post);
		$has_image_url_in_post_meta = false;
		if($is_post){
			$has_image_url_in_post_meta = isset( get_post_meta( $post->ID, 'cb_parallax', true )['cb_parallax_background_image_url'] ) && '' !== get_post_meta( $post->ID, 'cb_parallax', true )['cb_parallax_background_image_url'];
		}
		$has_image_url_in_options = true === $is_post && isset($stored_options['cb_parallax_background_image_url']) ? $stored_options['cb_parallax_background_image_url'] : false;

		// Determine image source
		$result = 'none';
		if ( $is_sidewide && false === $is_per_page_allowed  && $has_image_url_in_options ) {
			$result = 'global';
		} else if ( $is_sidewide && $is_per_page_allowed && false === $has_image_url_in_post_meta && $has_image_url_in_options ) {
			$result = 'global';
		} else if ( $is_per_page_allowed && $has_image_url_in_post_meta ) {
			$result = 'per_page';
		}
		
		return $result;
	}
	
	/**
	 * Saves the user-defined options to the database.
	 *
	 * @param string $post_id
	 * @param array $input
	 *
	 * @return bool|WP_Error
	 */
	public function save_options( $input, $post_id = '' ) {
		
		$validation = new MenuIncludes\cb_parallax_validation( $this->domain, $this );
		
		$data = $validation->run( $post_id, $input );
		
		// Checks if the options are on a per post basis or plugin-related.
		if ( '' === $post_id ) {
			$result = update_option( 'cb_parallax_options', $data, true );
		} else {
			delete_post_meta( (int) $post_id, 'cb_parallax' );
			$result = add_post_meta( (int) $post_id, 'cb_parallax', $data, false );
		}
		
		/**
		 * If the result is false,
		 * the cause could be that we tried to store identical, already stored options.
		 * So we check if that is the case and maybe turn the 'false' into a more detailed answer.
		 */
		if ( false === $result ) {
			
			if ( '' === $post_id ) {
				$stored_options = get_option( 'cb_parallax_options' );
			} else {
				$stored_options = get_post_meta( (int) $post_id, 'cb_parallax', true );
			}
			$diff = array_diff( $data, $stored_options );
			if ( empty( $diff ) ) {
				
				return new WP_Error( - 1, __( 'There\'s nothing new to save.', $this->domain ) );
			}
			
			return new WP_Error( - 2, __( 'Failed to save settings.', $this->domain ) );
		}
		
		return true;
	}
	
	/**
	 * Restores the plugin settings to it's default values.
	 *
	 * @param $post_id
	 *
	 * @return  WP_Error | bool true
	 */
	public function reset_options( $post_id ) {
		
		delete_option( 'cb_parallax_options' );
		
		$result = null;
		// Checks if the options are post-related or plugin-related.
		if ( '' === $post_id ) {
			$result = add_option( 'cb_parallax_options', $this->get_default_options() );
		} else {
			delete_post_meta( (int) $post_id, 'cb_parallax' );
			$result = add_post_meta( (int) $post_id, 'cb_parallax', $this->get_default_image_options(), false );
		}
		
		if ( false === $result ) {
			
			return new WP_Error( - 3, __( 'Failed to reset settings. Please refresh the page and try again.', $this->domain ) );
		}
		
		return true;
	}
	
	/**
	 * @toBeImplemented
	 *
	 * Resets all plugin-related meta data on posts and pages.
	 *
	 * @return bool
	 */
	public function reset_all_post_meta() {
		
		$args = array(
			'posts_per_page' => - 1,
			'post_type' => array( 'page', 'post', 'product' ),
			'meta_key' => 'cb_parallax'
		);
		$posts = new WP_Query( $args );
		
		foreach($posts as $i => $post){
			delete_post_meta($post->ID, 'cb_parallax');
		}
		
		return true;
	}
	
	/**
	 * Looks for the referred media file and compares it by file name aditionally,
	 * to make sure we are dealing with the correct file.
	 * Since the ids for attachments will most likely be assigned in a different order after website migrations,
	 * we check the file by it's file name, too. We rather serve no file instead of a wrong one.
	 *
	 * @param WP_Post $post
	 *
	 * @return bool
	 */
	public function is_image_in_media_library( $post ) {
		
		$stored_image_options = $this->get_image_options( $post );
		$attachment_id = $stored_image_options['cb_parallax_attachment_id'];
		$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
		$attachment_meta = wp_get_attachment_metadata( $attachment_id, false );
		
		// If no image was found...
		// The user will be informed about that in the admin area. // @todo
		if ( false === $attachment_meta ) {
			set_transient( 'cb_parallax_background_image_missing', array(
				'attachment_id' => $attachment_id,
				'url' => $stored_image_options['cb_parallax_background_image_url']
			) );
			
			return false;
		} else {
			$stored_file = isset($attachment_meta['file']) ? $attachment_meta['file'] : 'false';
			$found_file = str_replace( site_url() . '/wp-content/uploads/', '', $image_attributes[0] );
		}
		
		/**
		 * If the stored file does not match the given file that is related to the stored 'attachment id'...
		 * The user will be informed about that in the admin area.
		 */
		if ( $stored_file !== $found_file ) {
			set_transient( 'cb_parallax_background_image_missmatch', array( 'stored_file' => $stored_file, 'found_file' => $found_file ) );
			
			return false;
		}
		
		return true;
	}

    public function has_stored_options() {

        return !(false === get_option('cb_parallax_options'));
    }
	
}
