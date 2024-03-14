<?php
/**
 * Plugin Name: Animated Live Wall
 * Description: The Animated Live Wall Gallery is a responsive animated gallery that helps to makes beutiful your WordPress site.
 * Version: 1.1.7
 * Author: A WP Life
 * Plugin URI:
 * Author URI: https://www.awplife.com
 * License: GPLv2 or later
 * Text Domain: animated-live-wall
 * Domain Path: /languages
 **/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Awl_Photo_Wall' ) ) {

	class Awl_Photo_Wall {

		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants() {
			// Plugin Version
			define( 'ALW_PLUGIN_VER', '1.1.7' );

			// Plugin Text Domain
			define( 'ALW_TXTDM', 'animated-live-wall' );

			// Plugin Name
			define( 'ALW_PLUGIN_NAME', __( 'Animated Live Wall Premium', 'animated-live-wall' ) );

			// Plugin Slug
			define( 'ALW_PLUGIN_SLUG', 'animated-live-wall' );

			// Plugin Directory Path
			define( 'ALW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Directory URL
			define( 'ALW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			define( 'ALW_SECURE_KEY', md5( NONCE_KEY ) );

		} // end of constructor function

		protected function _hooks() {

			// Load text domain
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

			// add gallery menu item, change menu filter for multi-site
			add_action( 'admin_menu', array( $this, 'alw_menu' ), 101 );

			// Create Animated Live Wall Custom Post
			add_action( 'init', array( $this, 'Photo_Wall' ) );

			// Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, 'admin_add_meta_box' ) );

			// loaded during admin init
			add_action( 'admin_init', array( $this, 'admin_add_meta_box' ) );

			// Script in header
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts_in_header' ) );

			add_action( 'wp_ajax_alw_gallery_js', array( &$this, '_ajax_alw_gallery' ) );

			add_action( 'save_post', array( &$this, '_alw_save_settings' ) );

			// Shortcode Compatibility in Text Widgets
			add_filter( 'widget_text', 'do_shortcode' );

			add_image_size( 'custum_500x500', 500, 500, true );
			add_image_size( 'custum_800x800', 800, 800, true );

		} // end of hook function


		public function enqueue_scripts_in_header() {
			wp_enqueue_script( 'jquery' );
		}

		public function load_textdomain() {
			load_plugin_textdomain( 'animated-live-wall', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function alw_menu() {
			$filter_menu = add_submenu_page( 'edit.php?post_type=' . ALW_PLUGIN_SLUG, __( 'Filters', 'animated-live-wall' ), __( 'Filters', 'animated-live-wall' ), 'administrator', 'alw-filter-page', array( $this, 'awl_filter_page' ) );
			$doc_menu    = add_submenu_page( 'edit.php?post_type=' . ALW_PLUGIN_SLUG, __( 'Docs', 'animated-live-wall' ), __( 'Docs', 'animated-live-wall' ), 'administrator', 'sr-doc-page', array( $this, 'alw_doc_page' ) );
		}

		public function Photo_Wall() {
			$labels = array(
				'name'               => _x( 'Animated Live Wall', 'animated-live-wall' ),
				'singular_name'      => _x( 'Animated Live Wall', 'animated-live-wall' ),
				'menu_name'          => __( 'Animated Live Wall', 'animated-live-wall' ),
				'name_admin_bar'     => __( 'Portfolio Filter', 'animated-live-wall' ),
				'parent_item_colon'  => __( 'Parent Item:', 'animated-live-wall' ),
				'all_items'          => __( 'All Animated Live Wall', 'animated-live-wall' ),
				'add_new_item'       => __( 'Add New', 'animated-live-wall' ),
				'add_new'            => __( 'Add New', 'animated-live-wall' ),
				'new_item'           => __( 'New Animated Live Wall', 'animated-live-wall' ),
				'edit_item'          => __( 'Edit Animated Live Wall', 'animated-live-wall' ),
				'update_item'        => __( 'Update Animated Live Wall', 'animated-live-wall' ),
				'search_items'       => __( 'Search Animated Live Wall', 'animated-live-wall' ),
				'not_found'          => __( 'Animated Live Wall Not found', 'animated-live-wall' ),
				'not_found_in_trash' => __( 'Animated Live Wall Not found in Trash', 'animated-live-wall' ),
			);

			$args = array(
				'label'               => __( 'Animated Live Wall', 'animated-live-wall' ),
				'description'         => __( 'Custom Post Type For Animated Live Wall', 'animated-live-wall' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-layout',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);

			register_post_type( 'animated_live_wall', $args );
		} // end of post type function

		public function admin_add_meta_box() {
			add_meta_box( 'add-photo-wall', __( 'Add Animated Live Wall', 'animated-live-wall' ), array( &$this, 'ALW_Genrate_Gallery' ), 'animated_live_wall', 'normal', 'default' );
			add_meta_box( 'alw-shortcode', __( 'Copy Shortcode', 'animated-live-wall' ), array( &$this, 'ALW_Shortcode' ), 'animated_live_wall', 'side', 'default' );
		}

		public function ALW_Genrate_Gallery( $post ) {
			// js
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'alw-bootstrap-js', ALW_PLUGIN_URL . 'assets/bootstrap/js/bootstrap.js', array( 'jquery' ) );
			wp_enqueue_script( 'alw-option-tab-js', ALW_PLUGIN_URL . 'assets/js/alw-option-tab.js', array( 'jquery' ) );
			wp_enqueue_script( 'alw-uploader-js', ALW_PLUGIN_URL . 'assets/js/alw-uploader.js', array( 'jquery' ) );

			// CSS
			wp_enqueue_style( 'alw-bootstrap-css', ALW_PLUGIN_URL . 'assets/css/bootstrap-min.css' );
			wp_enqueue_style( 'alw-option-tab-css', ALW_PLUGIN_URL . 'assets/css/alw-option-tab.css' );

			wp_enqueue_style( 'alw-uploader-css', ALW_PLUGIN_URL . 'assets/css/alw-uploader.css' );
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			require_once 'include/admin/animated-live-wall-setting.php';
		}

		public function ALW_Shortcode( $post ) { ?>
			<div class="pw-shortcode">
				<input type="text" name="shortcode" id="shortcode" value="<?php echo '[ALW ID=' . esc_attr( $post->ID ) . ']'; ?>" readonly style="height: 60px; text-align: center; font-size: 20px; width: 100%; border: 2px dotted;">
				<p id="pw-copt-code"><?php esc_html_e( 'Shortcode copied to clipboard!', 'animated-live-wall' ); ?></p>
				<p><?php esc_html_e( 'Copy & Embed shortcode into any Page/ Post / Text Widget to display your image gallery on site.', 'animated-live-wall' ); ?><br></p>
			</div>
			<span onclick="copyToClipboard('#shortcode')" class="pw-copy dashicons dashicons-clipboard"></span>
			<style>
			.pw-copy {
				position: absolute;
				top: 9px;
				right: 24px;
				font-size: 26px;
				cursor: pointer;
			}
			</style>
			<script>
			jQuery( "#pw-copt-code" ).hide();
			function copyToClipboard(element) {
			  var temp = jQuery("<input>");
			  jQuery("body").append(temp);
			  temp.val(jQuery(element).val()).select();
			  document.execCommand("copy");
			  temp.remove();
			  jQuery( "#shortcode" ).select();
			  jQuery( "#pw-copt-code" ).fadeIn();
			}
			</script>
			<?php
		}//end ALW_Shortcode()

		public function _ig_ajax_callback_function( $id ) {
			// wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false );
			// thumb, thumbnail, medium, large, post-thumbnail
			$thumbnail  = wp_get_attachment_image_src( $id, 'medium', true );
			$attachment = get_post( $id ); // $id = attachment id
			?>
			
			<li class="item image col-lg-2 col-md-3 col-sm-6 col-xs-12">
				<img class="new-image" src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php echo esc_html( get_the_title( $id ) ); ?>" style="height: 150px;">
				<div class="item-overlay bottom label label-info" style="opacity:0; position:absolute; color: #fff; background-color:#5bc0de; padding:2px;">ID-<?php echo esc_attr( $id ); ?></div>
				<input type="hidden" id="image-ids[]" name="image-ids[]" value="<?php echo esc_attr( $id ); ?>" />
				<input type="text" name="image-title[]" id="image-title[]" placeholder="Image Title" value="<?php echo esc_html( get_the_title( $id ) ); ?>">
				<input type="text" name="image-link[]" id="image-link[]" placeholder="Video URL / Link URL"  value="<?php echo esc_url( $image_link ); ?>">
				<a class="pw-trash-icon" name="remove-image" id="remove-image" href="#"><span class="dashicons dashicons-trash"></span></a>
			</li>
			<?php
		}

		public function _ajax_alw_gallery() {
			echo esc_attr( $this->_ig_ajax_callback_function( $_POST['imageId'] ) );
			die;
		}

		public function _alw_save_settings( $post_id ) {
			if ( isset( $_POST['alw_save_nonce'] ) ) {
				if ( ! isset( $_POST['alw_save_nonce'] ) || ! wp_verify_nonce( $_POST['alw_save_nonce'], 'alw_save_settings' ) ) {
					print 'Sorry, your nonce did not verify.';
					exit;
				} else {
					$alw_gallery_wall         = sanitize_text_field( $_POST['alw_gallery_wall'] );
					$alw_instagram_token      = sanitize_text_field( $_POST['alw_instagram_token'] );
					$alw_flickr_api_key       = sanitize_text_field( $_POST['alw_flickr_api_key'] );
					$alw_flickr_user_id       = sanitize_text_field( $_POST['alw_flickr_user_id'] );
					$enable_gallery_layout    = sanitize_text_field( $_POST['enable_gallery_layout'] );
					$alw_grid_rows            = sanitize_text_field( $_POST['alw_grid_rows'] );
					$alw_grid_columns         = sanitize_text_field( $_POST['alw_grid_columns'] );
					$alw_grid_thumb_size      = sanitize_text_field( $_POST['alw_grid_thumb_size'] );
					$alw_grid_stop_anim       = sanitize_text_field( $_POST['alw_grid_stop_anim'] );
					$alw_grid_animation       = sanitize_text_field( $_POST['alw_grid_animation'] );
					$alw_grid_gap             = sanitize_text_field( $_POST['alw_grid_gap'] );
					$alw_img_redirection      = sanitize_text_field( $_POST['alw_img_redirection'] );
					$column_setting           = sanitize_text_field( $_POST['column_setting'] );
					$alw_thumb_size           = sanitize_text_field( $_POST['alw_thumb_size'] );
					$alw_images_gap           = sanitize_text_field( $_POST['alw_images_gap'] );
					$alw_maso_img_redirection = sanitize_text_field( $_POST['alw_maso_img_redirection'] );
					$alw_lightbox             = sanitize_text_field( $_POST['alw_lightbox'] );

					$alw_custum_css = sanitize_text_field( $_POST['alw_custum_css'] );
					if ( isset( $_POST['alw_custum_css'] ) ) {
						// Parse / sanitize the CSS
						$custom_css = wp_kses( $_POST['alw_custum_css'], array(), array() );
					} else {
						$custom_css = '';
					}

					$image_ids    = array();
					$image_titles = array();

					$image_ids_val = isset( $_POST['image-ids'] ) ? (array) $_POST['image-ids'] : array();
					$image_ids_val = array_map( 'sanitize_text_field', $image_ids_val );

					$filters = isset( $_POST['filters'] ) ? (array) $_POST['filters'] : array();

					$i = 0;
					foreach ( $image_ids_val as $image_id ) {

						$image_ids[]    = sanitize_text_field( $_POST['image-ids'][ $i ] );
						$image_titles[] = sanitize_text_field( $_POST['image-title'][ $i ] );
						$image_link[]   = sanitize_text_field( $_POST['image-link'][ $i ] );

						$single_image_update = array(
							'ID'         => $image_id,
							'post_title' => $image_titles[ $i ],
						);

						wp_update_post( $single_image_update );
						$i++;
					}

					$alw_post_setting = array(
						'image-ids'                => $image_ids,
						'image_title'              => $image_titles,
						'image-link'               => $image_link,
						'alw_gallery_wall'         => $alw_gallery_wall,
						'alw_instagram_token'      => $alw_instagram_token,
						'alw_flickr_api_key'       => $alw_flickr_api_key,
						'alw_flickr_user_id'       => $alw_flickr_user_id,
						'enable_gallery_layout'    => $enable_gallery_layout,
						'alw_grid_rows'            => $alw_grid_rows,
						'alw_grid_columns'         => $alw_grid_columns,
						'alw_grid_thumb_size'      => $alw_grid_thumb_size,
						'alw_grid_stop_anim'       => $alw_grid_stop_anim,
						'alw_grid_animation'       => $alw_grid_animation,
						'alw_grid_gap'             => $alw_grid_gap,
						'alw_img_redirection'      => $alw_img_redirection,
						'column_setting'           => $column_setting,
						'alw_thumb_size'           => $alw_thumb_size,
						'alw_images_gap'           => $alw_images_gap,
						'alw_maso_img_redirection' => $alw_maso_img_redirection,
						'alw_lightbox'             => $alw_lightbox,
						'alw_custum_css'           => $alw_custum_css,

					);

					$awl_animated_live_wall_shortcode_setting = 'awl_animated_live_wall' . $post_id;
					update_post_meta( $post_id, $awl_animated_live_wall_shortcode_setting, $alw_post_setting );
				}
			}
		}//end _alw_save_settings()
	}

	// register sf scripts
	function awplife_alw_register_scripts() {

		// css & JS
		wp_register_script( 'modernizr-custom-js', plugin_dir_url( __FILE__ ) . 'assets/js/modernizr.custom.26633.js', array( 'jquery' ), '', false );
		wp_register_script( 'jquery-gridrotator-js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.gridrotator.js', array( 'jquery' ), '', false );

		wp_register_style( 'alw-style-css', plugin_dir_url( __FILE__ ) . 'assets/css/alw-style.css' );

		// Freewall
		wp_register_script( 'freewall-js', plugin_dir_url( __FILE__ ) . 'assets/freewall/freewall.js', array( 'jquery' ), '', false );
		wp_register_style( 'freewall-style-css', plugin_dir_url( __FILE__ ) . 'assets/freewall/freewall-style.css' );
		// css & JS

		// Lightbox
		wp_register_script( 'colorbox-lightbox-js', plugin_dir_url( __FILE__ ) . 'assets/lightbox/jquery.colorbox.js', array( 'jquery' ), '', false );
		wp_register_style( 'colorbox-lightbox-css', plugin_dir_url( __FILE__ ) . 'assets/lightbox/colorbox.css' );
		// css & JS

		// fontawesome
		wp_register_style( 'all-fontawesome-min-css', plugin_dir_url( __FILE__ ) . 'assets/css/fontawesome-all.min.css' );

		// hover effects
		wp_register_style( 'hover-effect-css', plugin_dir_url( __FILE__ ) . 'assets/hover-effects/hover-effect.css' );

	}
		add_action( 'wp_enqueue_scripts', 'awplife_alw_register_scripts' );


	// Plugin Recommend
		add_action( 'tgmpa_register', 'ALW_TXTDM_plugin_recommend' );
	function ALW_TXTDM_plugin_recommend() {
		$plugins = array(
			array(
				'name'     => 'Blog Filter & Post Portfolio',
				'slug'     => 'blog-filter',
				'required' => false,
			),
			array(
				'name'     => 'Album Gallery Photostream Profile For Flickr',
				'slug'     => 'wp-flickr-gallery',
				'required' => false,
			),
			array(
				'name'     => 'Event Management Tickets Booking',
				'slug'     => 'event-monster',
				'required' => false,
			),
		);
		tgmpa( $plugins );
	}


	$animated_live_wall_object = new Awl_Photo_Wall();
	// Generate random number
	function random() {
		return (float) rand() / (float) getrandmax(); }
	require_once 'shortcode.php';
	require_once 'class-tgm-plugin-activation.php';
}
?>
