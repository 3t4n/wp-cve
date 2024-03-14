<?php
namespace CbParallax\Admin\Includes;

use CbParallax\Admin\Menu\Includes as MenuIncludes;
use CbParallax\Admin\Partials as Partials;
use WP_Post;

if ( ! class_exists( 'Partials\cb_parallax_settings_display' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/partials/class-settings-display.php';
}

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Defines and displays the meta box.
 *
 * @link
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/admin/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_meta_box {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * Whether the theme has a custom backround callback for 'wp_head' output.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    bool
	 */
	public $theme_has_callback = false;
	
	/**
	 * The reference to the options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    MenuIncludes\cb_parallax_options $options
	 */
	private $options;
	
	/**
	 * Maintains the allowed option values for the image.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $allowed_image_options
	 */
	public $allowed_image_options;
	
	/**
	 * Maintains the default image image_options.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $default_image_options
	 */
	public $default_image_options;
	
	/**
	 * The array holding the names of the supported post types.
	 *
	 * @since    0.7.4
	 * @access   private
	 * @var      array $screen_ids
	 */
    private $screen_ids;
	
	/**
	 * cb_parallax_meta_box constructor.
	 *
	 * @param string $domain
	 * @param array $screen_ids
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $screen_ids, $options ) {
		
		$this->domain = $domain;
		$this->screen_ids = $screen_ids;
		$this->options = $options;
		
		/* If the current user can't edit custom backgrounds, bail early. */
		if ( ! current_user_can( 'cb_parallax_edit' ) && ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		
		$this->add_hooks();
		$this->retrieve_options();
	}
	
	/**
	 * Retrieves and sets the white-listed image options and the default options.
	 *
	 * @return void
	 */
	private function retrieve_options() {
		
		$this->allowed_image_options = $this->options->get_image_options_whitelist();
		$this->default_image_options = $this->options->get_default_image_options();
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {

		/* Only load on the edit post screen. */
		add_action( 'load-post.php', array( $this, 'load_post' ) );
		add_action( 'load-post-new.php', array( $this, 'load_post' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		// Save meta data.
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}
	
	/**
	 * Adds the actions that add the meta box and add the callback for when the user saves a page or post.
	 */
	public function load_post() {
		
		$screen = get_current_screen();
		
		/* If the current theme doesn't support custom backgrounds, bail. */
		if ( ! current_theme_supports( 'custom-background' ) || ! post_type_supports( $screen->post_type, 'custom-background' ) ) {
			return;
		}
		
		/* Get the 'wp_head' callback. */
		$wp_head_callback = get_theme_support( 'custom-background', 'wp-head-callback' );
		
		/* Checks if the theme has set up a custom callback. */
		$this->theme_has_callback = empty( $wp_head_callback ) || '_custom_background_cb' === $wp_head_callback ? false : true;
		
		// Add the meta box
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 5 );
		
		// Save meta data.
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}
	
	/**
	 * Calls the WordPress function to add the meta box,
	 * if we're on a white-listed screen.
	 *
	 * @param string $post_type
	 *
	 * @return void
	 */
	public function add_meta_box( $post_type ) {
		
		$screen = get_current_screen();
		
		if ( in_array( $screen->base, $this->screen_ids ) ) {
			
			add_meta_box( 'cb-parallax-meta-box', __( 'cb Parallax', $this->domain ), array(
				$this,
				'edit_screen_display'
			), $post_type, 'side', 'core' );
		}
	}
	
	/**
	 * Retrieves the user-defined optuons and orchestrates the functions that display the form and the settings fields.
	 *
	 * @param WP_Post $post
	 */
	public function edit_screen_display( $post ) {
		
		global $post;
		
		$display = new Partials\cb_parallax_settings_display( $this->domain, $this->options, $this->allowed_image_options );
		
		if ( is_array( get_post_meta( $post->ID, 'cb_parallax', true ) ) ) {
			$options = array_merge( $this->options->get_default_image_options(), get_post_meta( $post->ID, 'cb_parallax', true ) );
		} else {
			$options = $this->options->get_default_image_options();
		}
		$attachment_id = isset( $options['cb_parallax_attachment_id'] ) ? $options['cb_parallax_attachment_id'] : false;
		
		// Get image meta
		$image = null;
		if ( false !== $attachment_id ) {
			$image = wp_get_attachment_image_src( absint( $attachment_id ), 'full' );
		}
		// Get the image URL.
		$url = isset( $image[0] ) ? $image[0] : '';
		
		$nonce = wp_create_nonce( 'cb_parallax_manage_options_nonce' );
		echo '<div id="cb_parallax_settings_form" data-nonce="' . $nonce . '" data-form="image"  data-postid="' . $post->ID . '">';
		echo $display->get_hidden_fields_display( $attachment_id, $url );
		$settings = $this->options->get_options_arguments( 'image' );
		foreach ( $settings as $option_key => $args ) {
			
			$value = $options[ $option_key ];
			
			switch ( $args['input_type'] ) {
				
				case( $args['input_type'] == 'checkbox' );
					
					echo $display->get_checkbox_display( $value, $args );
					if ( 'cb_parallax_parallax_enabled' === $option_key ) {
						echo $display->get_settings_title( 'metabox' );
					}
					break;
				
				case( $args['input_type'] == 'color' );
					
					echo $display->get_color_picker_field( $value, $args );
					break;
				
				case( $args['input_type'] == 'select' );
					
					echo $display->get_select_field( $value, $args );
					break;
				
				case( $args['input_type'] == 'media' );
					
					echo $display->get_media_button_display();
					echo $display->get_background_image_display( $url, 'image' );
					
					break;
				
				default:
			}
		}
		echo '<input id="cb_parallax_form_submit" type="submit" value="' . __( 'Save' ) . '" class="button button-primary button-large" />';
		echo '<input id="cb_parallax_form_reset" type="submit" value="' . __( 'Reset' ) . '" class="button button-secondary button-large" />';
		echo '</div>';
	}
	
	/**
	 * Whenever a supported post type get's saved:
	 * Retrieves the plugin-related input, preprocesses it and uses the reference of the options class to process and finally store the plugin-related data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function save_post( $post_id, $post ) {
		
		// Verify the nonce.
		if ( ! isset( $_POST['cb_parallax_nonce'] ) || ! wp_verify_nonce( $_POST['cb_parallax_nonce'], 'cb_parallax_nonce_field' ) ) {
			return;
		}

		/**
		 * Get the post type object.
		 *
		 * @var WP_Post $post_type
		 */
		$post_type = get_post_type_object( $post->post_type );
		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}
		// Don't save if the post is only a revision.
		if ( 'revision' == $post->post_type ) {
			return;
		}
		
		$image_options_whitelist = $this->options->get_image_options_whitelist();
		$posted_data = $_POST['cb_parallax_options'];
		$posted_data['cb_parallax_background_image_url'] = $_POST['cb_parallax_options']['cb_parallax_background_image_url_hidden'];
		unset( $posted_data['cb_parallax_background_image_url_hidden'] );
		$data = array();
		foreach ( $image_options_whitelist as $key => $args ) {
			$data[ $key ] = isset( $posted_data[ $key ] ) ? $posted_data[ $key ] : null;
		}
		
		$this->options->save_options( $data, (int) $post_id );
	}
	
}
