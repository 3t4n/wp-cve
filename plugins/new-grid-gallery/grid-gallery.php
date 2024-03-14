<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
@package New Grid Gallery
Plugin Name: New Grid Gallery
Plugin URI: https://awplife.com/wodpress-plugins/grid-gallery-premium/
Description: Grid gallery plugin with preview for WordPress
Version: 1.4.1
Author: A WP Life
Author URI: https://awplife.com/
Text Domain: GGP_TXTDM
License: GPLv2 or later
Domain Path: /languages
*/

if ( ! class_exists( 'Awl_Grid_Gallery' ) ) {

	class Awl_Grid_Gallery {

		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants() {
			// Plugin Version
			define( 'GG_PLUGIN_VER', '1.4.1' );

			// Plugin Text Domain
			define( 'GGP_TXTDM', 'new-grid-gallery' );

			// Plugin Name
			define( 'GG_PLUGIN_NAME', __( 'New Grid Gallery', GGP_TXTDM ) );

			// Plugin Slug
			define( 'GG_PLUGIN_SLUG', 'grid_gallery' );

			// Plugin Directory Path
			define( 'GG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Directory URL
			define( 'GG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			/**
			 * Create a key for the .htaccess secure download link.
			 *
			 * @uses    NONCE_KEY     Defined in the WP root config.php
			 */
			define( 'GG_SECURE_KEY', md5( NONCE_KEY ) );

		} // end of constructor function


		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {

			// Load text domain
			add_action( 'plugins_loaded', array( $this, '_load_textdomain' ) );

			// add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_Grid_Menu' ), 65 );

			// add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_Featured_Plugins_Grid_Menu' ), 68 );

			// Create grid Gallery Custom Post
			add_action( 'init', array( $this, '_Grid_Gallery' ) );

			// Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, '_admin_add_meta_box' ) );

			add_action( 'wp_ajax_grid_gallery_js', array( &$this, '_ajax_grid_gallery' ) );

			add_action( 'save_post', array( &$this, '_gg_save_settings' ) );

			// Shortcode Compatibility in Text Widgets
			add_filter( 'widget_text', 'do_shortcode' );

			// add pfg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage_grid_gallery_posts_columns', array( &$this, 'set_grid_gallery_shortcode_column_name' ) );

			// add pfg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage_grid_gallery_posts_custom_column', array( &$this, 'custom_grid_gallery_shodrcode_data' ), 10, 2 );

			add_action( 'wp_enqueue_scripts', array( &$this, 'grid_enqueue_scripts_in_header' ) );

		} // end of hook function

		public function grid_enqueue_scripts_in_header() {
			wp_enqueue_script( 'jquery' );
		}

		// Grid Gallery cpt shortcode column before date columns
		public function set_grid_gallery_shortcode_column_name( $defaults ) {
			$new       = array();
			$shortcode = $columns['grid_gallery_shortcode'];  // save the tags column
			unset( $defaults['tags'] );   // remove it from the columns list

			foreach ( $defaults as $key => $value ) {
				if ( $key == 'date' ) {  // when we find the date column
					$new['grid_gallery_shortcode'] = __( 'Shortcode', 'new-grid-gallery' );  // put the tags column before it
				}
				$new[ $key ] = $value;
			}
			return $new;
		}

		// Grid Gallery cpt shortcode column data
		public function custom_grid_gallery_shodrcode_data( $column, $post_id ) {
			switch ( $column ) {
				case 'grid_gallery_shortcode':
					echo "<input type='text' class='button button-primary' id='grid-gallery-shortcode-" . esc_attr( $post_id ) . "' value='[GGAL id=" . esc_attr( $post_id ) . "]' style='font-weight:bold; background-color:#32373C; color:#FFFFFF; text-align:center;' />";
					echo "<input type='button' class='button button-primary' onclick='return GRIDCopyShortcode" . esc_attr( $post_id ) . "();' readonly value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-" . esc_attr( $post_id ) . "' class='button button-primary' style='display:none; background-color:#32CD32; color:#FFFFFF; margin-left:4px; border-radius: 4px;'>copied</span>";
					echo '<script>
						function GRIDCopyShortcode' . esc_attr( $post_id ) . "() {
							var copyText = document.getElementById('grid-gallery-shortcode-" . esc_attr( $post_id ) . "');
							copyText.select();
							document.execCommand('copy');
							
							//fade in and out copied message
							jQuery('#copy-msg-" . esc_attr( $post_id ) . "').fadeIn('1000', 'linear');
							jQuery('#copy-msg-" . esc_attr( $post_id ) . "').fadeOut(2500,'swing');
						}
						</script>
					";
					break;
			}
		}

		public function _load_textdomain() {
			load_plugin_textdomain( 'new-grid-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function _Grid_Menu() {
			$help_menu = add_submenu_page( 'edit.php?post_type=' . GG_PLUGIN_SLUG, __( 'Docs', 'new-grid-gallery' ), __( 'Docs', 'new-grid-gallery' ), 'administrator', 'sr-doc-page', array( $this, '_gg_doc_page' ) );
		}

		public function _Featured_Plugins_Grid_Menu() {
			$help_menu       = add_submenu_page( 'edit.php?post_type=' . GG_PLUGIN_SLUG, __( 'Featured Plugins', 'new-grid-gallery' ), __( 'Featured Plugins', 'new-grid-gallery' ), 'administrator', 'sr-featured-plugins-page', array( $this, '_gg_featured_plugins' ) );
			$buy_plugin_menu = add_submenu_page( 'edit.php?post_type=' . GG_PLUGIN_SLUG, __( 'Upgrade Plugin', 'new-grid-gallery' ), __( 'Upgrade Plugin', 'new-grid-gallery' ), 'administrator', 'sr-upgrade-plugins-page', array( $this, '_gg_upgrade_plugins' ) );
			$theme_menu      = add_submenu_page( 'edit.php?post_type=' . GG_PLUGIN_SLUG, __( 'Our Theme', 'new-grid-gallery' ), __( 'Our Theme', 'new-grid-gallery' ), 'administrator', 'sr-theme-page', array( $this, '_gg_theme_page' ) );
		}

		/**
		 * Grid Gallery Custom Post
		 * Create gallery post type in admin dashboard.
		 */
		public function _Grid_Gallery() {
			$labels = array(
				'name'               => _x( 'Grid Gallery', 'Post Type General Name', 'new-grid-gallery' ),
				'singular_name'      => _x( 'Grid Gallery', 'Post Type Singular Name', 'new-grid-gallery' ),
				'menu_name'          => __( 'Grid Gallery', 'new-grid-gallery' ),
				'name_admin_bar'     => __( 'Grid Gallery', 'new-grid-gallery' ),
				'parent_item_colon'  => __( 'Parent Item:', 'new-grid-gallery' ),
				'all_items'          => __( 'All Grid Gallery', 'new-grid-gallery' ),
				'add_new_item'       => __( 'Add Grid Gallery', 'new-grid-gallery' ),
				'add_new'            => __( 'Add Grid Gallery', 'new-grid-gallery' ),
				'new_item'           => __( 'Grid Gallery', 'new-grid-gallery' ),
				'edit_item'          => __( 'Edit Grid Gallery', 'new-grid-gallery' ),
				'update_item'        => __( 'Update Grid Gallery', 'new-grid-gallery' ),
				'search_items'       => __( 'Search Grid Gallery', 'new-grid-gallery' ),
				'not_found'          => __( 'Grid Gallery Not found', 'new-grid-gallery' ),
				'not_found_in_trash' => __( 'Grid Gallery Not found in Trash', 'new-grid-gallery' ),
			);
			$args   = array(
				'label'               => __( 'Grid Gallery', 'new-grid-gallery' ),
				'description'         => __( 'Custom Post Type For Grid Gallery', 'new-grid-gallery' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-grid-view',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'grid_gallery', $args );

		} // end of post type function

		/**
		 * Adds Meta Boxes
		 */
		public function _admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( __( 'Add Image', 'new-grid-gallery' ), __( 'Add Image', 'new-grid-gallery' ), array( &$this, 'gg_upload_multiple_images' ), 'grid_gallery', 'normal', 'default' );
			add_meta_box( __( 'Upgrade Grid Gallery Pro', 'new-grid-gallery' ), __( 'Upgrade Grid Gallery Pro', 'new-grid-gallery' ), array( &$this, 'gg_upgrade_pro' ), 'grid_gallery', 'side', 'default' );
			add_meta_box( __( 'Rate Our Plugin', 'new-grid-gallery' ), __( 'Rate Our Plugin', 'new-grid-gallery' ), array( &$this, 'gg_rate_plugin' ), 'grid_gallery', 'side', 'default' );
		}

		// meta upgrade pro
		public function gg_upgrade_pro() { ?>
			<img src="<?php echo esc_url( GG_PLUGIN_URL . 'img/2017-12-09_17-58-48.png' ); ?>" width="250" height="280">
			<a href="https://awplife.com/demo/grid-gallery-premium/" target="_new" class="button button-primary button-large" style="background: #496481; text-shadow: none; margin-top:10px"><span class="dashicons dashicons-search" style="line-height:1.4;" ></span> <?php _e( 'Live Demo', 'new-grid-gallery' ); ?></a>
			<a href="https://awplife.com/wordpress-plugins/grid-gallery-wordpress-plugin/" target="_new" class="button button-primary button-large" style="background: #496481; text-shadow: none; margin-top:10px"><span class="dashicons dashicons-unlock" style="line-height:1.4;" ></span> <?php _e( 'Upgrade Pro', 'new-grid-gallery' ); ?></a>
			<?php
		}

		// meta rate us
		public function gg_rate_plugin() {
			?>
			<div style="text-align:center">
				<p><?php esc_html_e( 'If you like our plugin then please', 'new-grid-gallery' ); ?> <b><?php esc_html_e( 'Rate us', 'new-grid-gallery' ); ?></b> <?php esc_html_e( 'on WordPress', 'new-grid-gallery' ); ?></p>
			</div>
			<div style="text-align:center">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</div>
			<br>
			<div style="text-align:center">
				<a href="https://wordpress.org/support/plugin/new-grid-gallery/reviews/?filter=5" target="_new" class="button button-primary button-large" style="background: #496481; text-shadow: none;"><span class="dashicons dashicons-heart" style="line-height:1.4;" ></span> <?php esc_html_e( 'Please Rate Us', 'new-grid-gallery' ); ?></a>
			</div>
			<?php
		}

		public function gg_upload_multiple_images( $post ) {
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'awl-gg-uploader.js', GG_PLUGIN_URL . 'js/awl-gg-uploader.js', array( 'jquery' ) );
			wp_enqueue_style( 'awl-gg-uploader-css', GG_PLUGIN_URL . 'css/awl-gg-uploader.css' );
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'awl-gg-color-picker-js', plugins_url( 'js/gg-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			?>
			<div id="slider-gallery">
				<input type="button" id="remove-all-slides" name="remove-all-slides" class="button button-large remove-all-slides" rel="" value="<?php esc_html_e( 'Delete All Images', 'new-grid-gallery' ); ?>">
				<ul id="remove-slides" class="sbox">
				<?php
				$allimagesetting = unserialize( base64_decode( get_post_meta( $post->ID, 'awl_gg_settings_' . $post->ID, true ) ) );
				if ( isset( $allimagesetting['slide-ids'] ) ) {
					$count = 0;
					foreach ( $allimagesetting['slide-ids'] as $id ) {
						$thumbnail      = wp_get_attachment_image_src( $id, 'medium', true );
						$attachment     = get_post( $id );
						$gg_slide_title = get_the_title( $id );
						$gg_slide_alt   = $slide_alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
						?>
						<li class="slide">
							<img class="new-slide" src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php echo esc_attr( $gg_slide_alt ); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
							<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo esc_attr( $id ); ?>" />
							<!-- Image Title, Caption, Alt Text, Description-->
							<input type="text" name="slide-title[]" id="slide-title[]" style="width: 100%;" placeholder="Image Title" value="<?php echo esc_attr( $gg_slide_title ); ?>">
							<input type="button" name="remove-slide" id="remove-slide" class="button remove-single-slide button-danger" style="width: 100%;" value="Delete">
						</li>
						<?php
						$count++;
					} // end of foreach
				} //end of if
				?>
				</ul>
			</div>
			
			<!--Add New Image Button-->
			<div name="add-new-slider" id="add-new-slider" class="new-slider" style="height: 200px; width: 205px; border-radius: 20px;">
				<div class="menu-icon dashicons dashicons-format-image"></div>
				<div class="add-text"><?php esc_html_e( 'Add Image', 'new-grid-gallery' ); ?></div>
			</div>
			<div style="clear:left;"></div>
			<br>
			<br>
			<h1><?php esc_html_e( 'Copy Grid Gallery Shortcode', 'new-grid-gallery' ); ?></h1>
			<hr>
			<p class="input-text-wrap">
				<p><?php esc_html_e( 'Copy & Embed shotcode into any Page/ Post / Text Widget to display your grid gallery on site.', 'new-grid-gallery' ); ?><br></p>
				<input type="text" name="shortcode" id="shortcode" value="<?php echo '[GGAL id=' . esc_attr( $post->ID ) . ']'; ?>" readonly style="height: 60px; text-align: center; font-size: 24px; width: 25%; border: 2px dashed;" onmouseover="return pulseOff();" onmouseout="return pulseStart();">
			</p>
			<br>
			<br>
			<h1><?php esc_html_e( 'Grid Gallery Setting', 'new-grid-gallery' ); ?></h1>
			<hr>
			<?php
			require_once 'grid-gallery-settings.php';
		} // end of upload multiple image

		public function _gg_ajax_callback_function( $id ) {
			// thumb, thumbnail, medium, large, post-thumbnail
			$thumbnail      = wp_get_attachment_image_src( $id, 'medium', true );
			$attachment     = get_post( $id ); // $id = attachment id
			$gg_slide_title = get_the_title( $id );
			$gg_slide_alt   = $slide_alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
			?>
			<li class="slide">
				<img class="new-slide" src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php echo esc_html( $gg_slide_alt ); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
				<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo esc_attr( $id ); ?>" />
				<input type="text" name="slide-title[]" id="slide-title[]" style="width: 100%;" placeholder="Image Title" value="<?php echo esc_html( $gg_slide_title ); ?>">
				<input type="button" name="remove-slide" id="remove-slide" style="width: 100%;" class="button" value="Delete">
			</li>
			<?php
		}

		public function _ajax_grid_gallery() {
			echo esc_attr( $this->_gg_ajax_callback_function( $_POST['slideId'] ) );
			die;
		}

		public function _gg_save_settings( $post_id ) {
			if ( isset( $_POST['gg_save_nonce'] ) ) {
				if ( isset( $_POST['gg_save_nonce'] ) || wp_verify_nonce( $_POST['gg_save_nonce'], 'gg_save_settings' ) ) {
					$gal_thumb_size          = sanitize_text_field( $_POST['gal_thumb_size'] );
					$animation_speed         = sanitize_text_field( $_POST['animation_speed'] );
					$image_hover_effect_type = sanitize_text_field( $_POST['image_hover_effect_type'] );
					$image_hover_effect_four = sanitize_text_field( $_POST['image_hover_effect_four'] );
					$scroll_loading          = sanitize_text_field( $_POST['scroll_loading'] );
					$nbp_setting2            = sanitize_text_field( $_POST['nbp_setting2'] );
					$thumb_title             = sanitize_text_field( $_POST['thumb_title'] );
					$title_setting           = sanitize_text_field( $_POST['title_setting'] );
					$title_color             = sanitize_text_field( $_POST['title_color'] );
					$thumbnail_border        = sanitize_text_field( $_POST['thumbnail_border'] );
					$no_spacing              = sanitize_text_field( $_POST['no_spacing'] );
					$custom_css              = sanitize_textarea_field( $_POST['custom-css'] );
					$i                       = 0;
					$image_ids               = array();
					$image_titles            = array();
					$image_ids_val           = isset( $_POST['slide-ids'] ) ? (array) $_POST['slide-ids'] : array();
					$image_ids_val           = array_map( 'sanitize_text_field', $image_ids_val );

					foreach ( $image_ids_val as $image_id ) {
						$image_ids[]         = sanitize_text_field( $_POST['slide-ids'][ $i ] );
						$image_titles[]      = sanitize_text_field( $_POST['slide-title'][ $i ] );
						$single_image_update = array(
							'ID'         => $image_id,
							'post_title' => $image_titles[ $i ],
						);
						wp_update_post( $single_image_update );
						$i++;
					}

					$gg_settings = array(
						'slide-ids'               => $image_ids,
						'slide-title'             => $image_titles,
						'gal_thumb_size'          => $gal_thumb_size,
						'animation_speed'         => $animation_speed,
						'image_hover_effect_type' => $image_hover_effect_type,
						'image_hover_effect_four' => $image_hover_effect_four,
						'scroll_loading'          => $scroll_loading,
						'nbp_setting2'            => $nbp_setting2,
						'thumb_title'             => $thumb_title,
						'title_setting'           => $title_setting,
						'title_color'             => $title_color,
						'thumbnail_border'        => $thumbnail_border,
						'no_spacing'              => $no_spacing,
						'custom-css'              => $custom_css,
					);

					$awl_grid_gallery_shortcode_setting = 'awl_gg_settings_' . $post_id;
					update_post_meta( $post_id, $awl_grid_gallery_shortcode_setting, base64_encode( serialize( $gg_settings ) ) );
				}
			}
		}//end _gg_save_settings()

		/**
		 * Grid Gallery Docs Page
		 * Create doc page to help user to setup plugin
		 */
		public function _gg_doc_page() {
			require_once 'docs.php';
		}

		public function _gg_featured_plugins() {
			require_once 'featured-plugins/featured-plugins.php';
		}

		public function _gg_upgrade_plugins() {
			require_once 'buy-grid-gallery-premium.php';
		}

		// theme page
		public function _gg_theme_page() {
			require_once 'our-theme/awp-theme.php';
		}

	} // end of class

		// register sf scripts
	function awplife_ggp_register_scripts() {

		// css & JS
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'awl-gg-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js' );
		wp_register_script( 'awl-gridder-js', plugin_dir_url( __FILE__ ) . 'js/jquery.gridder.min.js' );
		wp_register_style( 'gg-bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/gg-bootstrap.css' );
		wp_register_style( 'gg-gridder-css', plugin_dir_url( __FILE__ ) . 'css/jquery.gridder.min.css' );
		wp_register_style( 'gg-demo-css', plugin_dir_url( __FILE__ ) . 'css/demo.css' );
		wp_register_style( 'gg-font-awesome-css', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css' );
		// css & JS
	}
		add_action( 'wp_enqueue_scripts', 'awplife_ggp_register_scripts' );

	// Plugin Recommend
		add_action( 'tgmpa_register', 'GGP_TXTDM_plugin_recommend' );
	function GGP_TXTDM_plugin_recommend() {
		$plugins = array(
			array(
				'name'     => 'Photo Gallery',
				'slug'     => 'new-photo-gallery',
				'required' => false,
			),
			array(
				'name'     => 'Responsive Slider Gallery',
				'slug'     => 'responsive-slider-gallery',
				'required' => false,
			),
			array(
				'name'     => 'Testimonial',
				'slug'     => 'testimonial-maker',
				'required' => false,
			),
		);
		tgmpa( $plugins );
	}


	/**
	 * Instantiates the Class
	 */
	$gg_gallery_object = new Awl_Grid_Gallery();
	require_once 'grid-gallery-shortcode.php';
	require_once 'class-tgm-plugin-activation.php';
} // end of class exists
?>
