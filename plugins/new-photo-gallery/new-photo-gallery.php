<?php
/**
@package New Photo Gallery
Plugin Name: New Photo Gallery
Plugin URI: https://awplife.com/wordpress-plugins/photo-gallery-premium/
Description: new photo gallery plugin with lightbox preview for WordPress
Version: 1.4.1
Author: A WP Life
Author URI: https://awplife.com/
License: GPLv2 or later
Text Domain: new-photo-gallery
Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'New_Photo_Gallery' ) ) {

	class New_Photo_Gallery {

		protected $protected_plugin_api;
		protected $ajax_plugin_nonce;

		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants() {
			// Plugin Version
			define( 'NPG_VER', '1.4.1' );

			// Plugin Text Domain
			define( 'NPG_TXTDM', 'new-photo-gallery' );

			// Plugin Name
			define( 'NPG_PLUGIN_NAME', __( 'New Photo Gallery', NPG_TXTDM ) );

			// Plugin Slug
			define( 'NPG_PLUGIN_SLUG', '_light_image_gallery' );

			// Plugin Directory Path
			define( 'NPG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Directory URL
			define( 'NPG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		} // end of constructor function

		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {

			// Load text domain
			add_action( 'plugins_loaded', array( $this, '_load_textdomain' ) );

			// add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_npg_menus' ), 101 );

			// create Image Gallery Custom Post
			add_action( 'init', array( $this, 'light_image_gallery' ) );

			// Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, '_admin_add_meta_box' ) );

			// loaded during admin init
			add_action( 'admin_init', array( $this, '_admin_add_meta_box' ) );

			add_action( 'wp_ajax_photo_gallery_js', array( &$this, '_ajax_light_image_gallery' ) );

			add_action( 'save_post', array( &$this, '_lg_save_settings' ) );

			// shortcode compatibility in Text Widgets
			add_filter( 'widget_text', 'do_shortcode' );

			// add npg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage__light_image_gallery_posts_columns', array( &$this, 'set_light_image_gallery_shortcode_column_name' ) );

			// add npg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage__light_image_gallery_posts_custom_column', array( &$this, 'custom_light_image_gallery_shodrcode_data' ), 10, 2 );

			add_action( 'wp_enqueue_scripts', array( &$this, 'ngp_enqueue_scripts_in_header' ) );

		} // end of hook function

		public function ngp_enqueue_scripts_in_header() {
			wp_enqueue_script( 'jquery' );
		}

		// npg cpt shortcode column before date columns
		public function set_light_image_gallery_shortcode_column_name( $defaults ) {
			$new       = array();
			$shortcode = $columns['_light_image_gallery_shortcode'];  // save the tags column
			unset( $defaults['tags'] );   // remove it from the columns list

			foreach ( $defaults as $key => $value ) {
				if ( $key == 'date' ) {  // when we find the date column
					$new['_light_image_gallery_shortcode'] = __( 'Shortcode', 'new-photo-gallery' );  // put the tags column before it
				}
				$new[ $key ] = $value;
			}
			return $new;
		}

		// npg cpt shortcode column data
		public function custom_light_image_gallery_shodrcode_data( $column, $post_id ) {
			switch ( $column ) {
				case '_light_image_gallery_shortcode':
					echo "<input type='text' class='button button-primary' id='light-image-gallery-shortcode-" . esc_attr( $post_id ) . "' value='[NPG id=" . esc_attr( $post_id ) . "]' style='font-weight:bold; background-color:#32373C; color:#FFFFFF; text-align:center;' />";
					echo "<input type='button' class='button button-primary' onclick='return PHOTOCopyShortcode" . esc_attr( $post_id ) . "();' readonly value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-" . esc_attr( $post_id ) . "' class='button button-primary' style='display:none; background-color:#32CD32; color:#FFFFFF; margin-left:4px; border-radius: 4px;'>copied</span>";
					echo '<script>
						function PHOTOCopyShortcode' . esc_attr( $post_id ) . "() {
							var copyText = document.getElementById('light-image-gallery-shortcode-" . esc_attr( $post_id ) . "');
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

		// Loads the language file
		public function _load_textdomain() {
			load_plugin_textdomain( 'new-photo-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		// Adds the photo gallery menus
		public function _npg_menus() {
			$docs_menu            = add_submenu_page( 'edit.php?post_type=' . NPG_PLUGIN_SLUG, __( 'Docs', 'new-photo-gallery' ), __( 'Docs', 'new-photo-gallery' ), 'administrator', 'npg-doc-page', array( $this, '_npg_doc_page' ) );
			$upgrade_premium_menu = add_submenu_page( 'edit.php?post_type=' . NPG_PLUGIN_SLUG, __( 'Upgrade Premium', 'new-photo-gallery' ), __( 'Upgrade Premium', 'new-photo-gallery' ), 'administrator', 'npg-upgrade-premium', array( $this, '_npg_upgrade_page' ) );
			$themes_menu          = add_submenu_page( 'edit.php?post_type=' . NPG_PLUGIN_SLUG, __( 'Our Themes', 'new-photo-gallery' ), __( 'Our Themes', 'new-photo-gallery' ), 'administrator', 'npg-themes', array( $this, '_npg_theme_page' ) );
			$plugins_menu         = add_submenu_page( 'edit.php?post_type=' . NPG_PLUGIN_SLUG, __( 'Our Plugins', 'new-photo-gallery' ), __( 'Our Plugins', 'new-photo-gallery' ), 'administrator', 'npg-plugins', array( $this, '_npg_featured_plugins' ) );
		}

		// Photo Gallery Custom Post
		public function light_image_gallery() {
			$labels = array(
				'name'               => __( 'New Photo Gallery', 'Post Type General Name', 'new-photo-gallery' ),
				'singular_name'      => __( 'New Photo Gallery', 'Post Type Singular Name', 'new-photo-gallery' ),
				'menu_name'          => __( 'New Photo Gallery', 'new-photo-gallery' ),
				'name_admin_bar'     => __( 'New Photo Gallery', 'new-photo-gallery' ),
				'parent_item_colon'  => __( 'Parent Item:', 'new-photo-gallery' ),
				'all_items'          => __( 'All Photo Gallery', 'new-photo-gallery' ),
				'add_new_item'       => __( 'Add New Photo Gallery', 'new-photo-gallery' ),
				'add_new'            => __( 'Add New Gallery', 'new-photo-gallery' ),
				'new_item'           => __( 'New Photo Gallery', 'new-photo-gallery' ),
				'edit_item'          => __( 'Edit New Photo Gallery', 'new-photo-gallery' ),
				'update_item'        => __( 'Update New Photo Gallery', 'new-photo-gallery' ),
				'search_items'       => __( 'Search New Photo Gallery', 'new-photo-gallery' ),
				'not_found'          => __( 'Photo Gallery Not found', 'new-photo-gallery' ),
				'not_found_in_trash' => __( 'Photo Gallery Not found in Trash', 'new-photo-gallery' ),
			);
			$args   = array(
				'label'               => __( 'New Photo Gallery', 'new-photo-gallery' ),
				'label'               => __( 'New Photo Gallery', 'new-photo-gallery' ),
				'description'         => __( 'Custom Post Type For New Photo Gallery', 'new-photo-gallery' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-images-alt2',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( '_light_image_gallery', $args );

		} // end of post type function

		// gallery setting meta box
		public function _admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( '1', __( 'Copy Photo Gallery Shortcode', 'new-photo-gallery' ), array( &$this, '_lg_shortcode_left_metabox' ), '_light_image_gallery', 'side', 'default' );
			add_meta_box( '', __( 'Add Photos To Photo Gallery', 'new-photo-gallery' ), array( &$this, 'lg_upload_multiple_images' ), '_light_image_gallery', 'normal', 'default' );
		}
		
		// image gallery copy shortcode meta box under publish button
		public function _lg_shortcode_left_metabox( $post ) { ?>
			<p class="input-text-wrap">
				<input type="text" name="photoCopyShortcode" id="photoCopyShortcode" value="<?php echo '[NPG id=' . esc_attr( $post->ID ) . ']'; ?>" readonly style="height: 50px; text-align: center; width:100%;  font-size: 24px; border: 2px dashed;">
				<p id="npg-copy-code"><?php esc_html_e( 'Shortcode copied to clipboard!', 'new-photo-gallery' ); ?></p>
				<p style="margin-top: 10px"><?php esc_html_e( 'Copy & Embed shotcode into any Page/ Post / Text Widget to display gallery.', 'new-photo-gallery' ); ?></p>
			</p>
			<span onclick="copyToClipboard('#photoCopyShortcode')" class="npg-copy dashicons dashicons-clipboard"></span>
			<style>
				.npg-copy {
					position: absolute;
					top: 9px;
					right: 24px;
					font-size: 26px;
					cursor: pointer;
				}
				.ui-sortable-handle > span {
					font-size: 16px !important;
				}
			</style>
			<script>
			jQuery( "#npg-copy-code" ).hide();
			function copyToClipboard(element) {
				var $temp = jQuery("<input>");
				jQuery("body").append($temp);
				$temp.val(jQuery(element).val()).select();
				document.execCommand("copy");
				$temp.remove();
				jQuery( "#photoCopyShortcode" ).select();
				jQuery( "#npg-copy-code" ).fadeIn();
			}
			</script>
			<?php
		}
		
		

		public function lg_upload_multiple_images( $post ) {
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'awplife-npg-uploader-js', NPG_PLUGIN_URL . 'js/awplife-npg-uploader.js', array( 'jquery' ) );
			wp_enqueue_style( 'awplife-npg-uploader-css', NPG_PLUGIN_URL . 'css/awplife-npg-uploader.css' );
			wp_enqueue_media();
		?>
			<div id="photo-gallery">
				<input type="button" id="remove-all-photos" name="remove-all-photos" class="button button-large remove-all-photos" rel="" value="<?php esc_html_e( 'Delete All Photos', 'new-photo-gallery' ); ?>">
				<ul id="remove-photos" class="photo-box">
					<?php
					$allimagesetting = unserialize( base64_decode( get_post_meta( $post->ID, 'awl_lg_settings_' . $post->ID, true ) ) );
					if ( isset( $allimagesetting['slide-ids'] ) ) {
						$count = 0;
						foreach ( $allimagesetting['slide-ids'] as $id ) {
							$thumbnail  = wp_get_attachment_image_src( $id, 'medium', true );
							$attachment = get_post( $id );
							$image_link = $allimagesetting['slide-link'][ $count ];
							$image_type = $allimagesetting['slide-type'][ $count ];
							?>
						<li class="photo-single">
							<img class="photo" src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php echo esc_html( get_the_title( $id ) ); ?>">
							<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo esc_attr( $id ); ?>" />
							<!-- Image Title, Caption, Alt Text, Description-->
							<select id="slide-type[]" name="slide-type[]" class="form-control photo-type" value="<?php echo esc_attr( $image_type ); ?>" >
								<option value="image" 
								<?php
								if ( $image_type == 'image' ) {
									echo 'selected=selected';}
								?>
								><?php esc_html_e( 'Image', 'new-photo-gallery' ); ?></option>
								<option value="video" 
								<?php
								if ( $image_type == 'video' ) {
									echo 'selected=selected';}
								?>
								><?php esc_html_e( 'Video', 'new-photo-gallery' ); ?></option>
							</select>
							<input type="text" name="slide-title[]" id="slide-title[]" class="photo-title" placeholder="<?php esc_html_e( 'Photo Title', 'new-photo-gallery' ); ?>" value="<?php esc_html_e( get_the_title( $id ), 'new-photo-gallery' ); ?>">
							<input type="text" name="slide-link[]" id="slide-link[]" class="photo-link" placeholder="<?php esc_html_e( 'YouTube / Vimeo Video URL', 'new-photo-gallery' ); ?>" value="<?php echo esc_attr( $image_link ); ?>">
							<input type="button" name="remove-photo" id="remove-photo" class="button button-danger photo-remove" value="<?php esc_html_e( 'Delete', 'new-photo-gallery' ); ?>">
						</li>
							<?php
							$count++; } // end of for each
					} //end of if
					?>
				</ul>
			</div>
			
			<!--Add New Photo Button-->
			<div name="add-new-photos" id="add-new-photos" class="add-new-photos">
				<div class="menu-icon dashicons dashicons-format-image"></div>
				<div class="add-text"><?php esc_html_e( 'Add Photo', 'new-photo-gallery' ); ?></div>
			</div>
			<div style="clear:left;"></div>
			<br>
			<h1><?php esc_html_e( 'Configure Settings For Photo Gallery', 'new-photo-gallery' ); ?></h1>
			<hr>
			<?php
			require_once 'settings.php';
		} // end of upload multiple image

		public function _ajax_light_image_gallery() {
			echo esc_attr( $this->_lg_ajax_callback_function( $_POST['slideId'] ) );
			die;
		}

		public function _lg_ajax_callback_function( $id ) {
			$thumbnail  = wp_get_attachment_image_src( $id, 'medium', true );
			$attachment = get_post( $id ); // $id = attachment id
			?>
			<li class="photo-single">
				<img class="photo" src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="<?php echo esc_html( get_the_title( $id ) ); ?>">
				<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo esc_attr( $id ); ?>" />
				<select id="slide-type[]" name="slide-type[]" class="form-control photo-type" value="<?php esc_html_e( $image_type, 'new-photo-gallery' ); ?>" >
					<option value="image" 
					<?php
					if ( $image_type == 'image' ) {
						echo 'selected=selected';}
					?>
					><?php esc_html_e( 'Image', 'new-photo-gallery' ); ?></option>
					<option value="video" 
					<?php
					if ( $image_type == 'video' ) {
						echo 'selected=selected';}
					?>
					><?php esc_html_e( 'Video', 'new-photo-gallery' ); ?></option>
				</select>
				<input type="text" name="slide-title[]" id="slide-title[]" class="photo-title" placeholder="<?php esc_html_e( 'Photo Title', 'new-photo-gallery' ); ?>" value="<?php esc_html_e( get_the_title( $id ), 'new-photo-gallery' ); ?>">
				<input type="text" name="slide-link[]" id="slide-link[]" class="photo-link" placeholder="<?php esc_html_e( 'YouTube / Vimeo Video URL', 'new-photo-gallery' ); ?>">
				<input type="button" name="remove-photo" id="remove-photo" class="button button-danger photo-remove" value="<?php esc_html_e( 'Delete', 'new-photo-gallery' ); ?>">
			</li>
			<?php
		}

		public function _lg_save_settings( $post_id ) {
			if ( isset( $_POST['lg_save_nonce'] ) ) {
				if ( isset( $_POST['lg_save_nonce'] ) || wp_verify_nonce( $_POST['lg_save_nonce'], 'lg_save_settings' ) ) {

					$gal_thumb_size          = sanitize_text_field( $_POST['gal_thumb_size'] );
					$col_large_desktops      = sanitize_text_field( $_POST['col_large_desktops'] );
					$col_desktops            = sanitize_text_field( $_POST['col_desktops'] );
					$col_tablets             = sanitize_text_field( $_POST['col_tablets'] );
					$col_phones              = sanitize_text_field( $_POST['col_phones'] );
					$tool_color              = sanitize_text_field( $_POST['tool_color'] );
					$title_color             = sanitize_text_field( $_POST['title_color'] );
					$image_hover_effect_type = sanitize_text_field( $_POST['image_hover_effect_type'] );
					$image_hover_effect_four = sanitize_text_field( $_POST['image_hover_effect_four'] );
					$transition_effects      = sanitize_text_field( $_POST['transition_effects'] );
					$thumbnails_spacing      = sanitize_text_field( $_POST['thumbnails_spacing'] );
					$custom_css              = sanitize_textarea_field( $_POST['custom-css'] );

					$i             = 0;
					$image_ids     = array();
					$image_titles  = array();
					$image_type    = array();
					$slide_link    = array();
					$image_ids_val = isset( $_POST['slide-ids'] ) ? (array) $_POST['slide-ids'] : array();
					$image_ids_val = array_map( 'sanitize_text_field', $image_ids_val );

					foreach ( $image_ids_val as $image_id ) {
						$image_ids[]         = sanitize_text_field( $_POST['slide-ids'][ $i ] );
						$image_titles[]      = sanitize_text_field( $_POST['slide-title'][ $i ] );
						$image_type[]        = sanitize_text_field( $_POST['slide-type'][ $i ] );
						$slide_link[]        = sanitize_text_field( $_POST['slide-link'][ $i ] );
						$single_image_update = array(
							'ID'         => $image_id,
							'post_title' => $image_titles[ $i ],
						);
						wp_update_post( $single_image_update );
						$i++;
					}

					$gallery_settings = array(
						'slide-ids'               => $image_ids,
						'slide-title'             => $image_titles,
						'slide-type'              => $image_type,
						'slide-link'              => $slide_link,
						'gal_thumb_size'          => $gal_thumb_size,
						'col_large_desktops'      => $col_large_desktops,
						'col_desktops'            => $col_desktops,
						'col_tablets'             => $col_tablets,
						'col_phones'              => $col_phones,
						'tool_color'              => $tool_color,
						'title_color'             => $title_color,
						'image_hover_effect_type' => $image_hover_effect_type,
						'image_hover_effect_four' => $image_hover_effect_four,
						'transition_effects'      => $transition_effects,
						'thumbnails_spacing'      => $thumbnails_spacing,
						'custom-css'              => $custom_css,

					);
					$awl_light_image_gallery_shortcode_setting = 'awl_lg_settings_' . $post_id;
					update_post_meta( $post_id, $awl_light_image_gallery_shortcode_setting, base64_encode( serialize( $gallery_settings ) ) );
				}
			}
		}//end _lg_save_settings()

		// doc page
		public function _npg_doc_page() {
			require_once 'docs.php';
		}

		// upgrade premium
		public function _npg_upgrade_page() {
			require_once 'upgrade-premium.php';
		}

		// a wp life plugins page
		public function _npg_featured_plugins() {
			require_once 'our-plugins/awplife-plugins.php';
		}

		// a wp life themes page
		public function _npg_theme_page() {
			require_once 'our-themes/awplife-themes.php';
		}
	}//end class

	// register sf scripts
	function awplife_npg_register_scripts() {

		// css & JS
		wp_register_script( 'npg-ig-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), '', true );
		wp_register_script( 'awplife-npg-isotope-js', plugin_dir_url( __FILE__ ) . 'js/isotope.pkgd.js' );
		wp_register_style( 'npg-bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css' );
		// css & JS
	}
		add_action( 'wp_enqueue_scripts', 'awplife_npg_register_scripts' );


	// Plugin Recommend
		add_action( 'tgmpa_register', 'NPG_TXTDM_plugin_recommend' );
	function NPG_TXTDM_plugin_recommend() {
		$plugins = array(
			array(
				'name'     => 'Modal Popup Box',
				'slug'     => 'modal-popup-box',
				'required' => false,
			),
			array(
				'name'     => 'Animated Live Wall',
				'slug'     => 'animated-live-wall',
				'required' => false,
			),
			array(
				'name'     => 'Album Gallery Photostream Profile For Flickr',
				'slug'     => 'wp-flickr-gallery',
				'required' => false,
			),
		);
		tgmpa( $plugins );
	}

	/**
	 * Instantiates the Class
	 */
	$lg_gallery_object = new New_Photo_Gallery();
	require_once 'shortcode.php';
	require_once 'class-tgm-plugin-activation.php';
} // end of class exists
?>
