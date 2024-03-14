<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Plugin Name: Album Gallery - Flickr Album Gallery - 2.2.13
 * Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
 * Description: Flickr Album Gallery is on JS API plugin to display all public Flickr albums on your WordPress website.
 * Version:     2.2.13
 * Author:      FARAZFRANK
 * Author URI:  https://wpfrank.com/
 * Text Domain: flickr-album-gallery
 * Domain Path: /languages
 * License:     GPL2

Flickr Album Gallery is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Flickr Album Gallery is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Flickr Album Gallery. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * Constant Variable
 */
define( 'FAG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FAG_PLUGIN_VER', '2.2.13' );

// load JS script
function wpfrank_fag_load_scripts() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'wpfrank_fag_load_scripts' );

/**
 * Flickr album gallery Plugin Class
 */
class FlickrAlbumGallery {

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'plugins_loaded', array( &$this, 'FAG_Translate' ), 1 );
			add_action( 'init', array( &$this, 'FlickrAlbumGallery_CPT' ), 1 );
			add_action( 'add_meta_boxes', array( &$this, 'Add_all_fag_meta_boxes' ) );
			add_action( 'admin_init', array( &$this, 'Add_all_fag_meta_boxes' ), 1 );
			add_action( 'save_post', array( &$this, 'Save_fag_meta_box_save' ), 9, 1 );
		}
	}

	/**
	 * Translate Plugin
	 */
	public function FAG_Translate() {
		load_plugin_textdomain( 'flickr-album-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	// 2 - Register Flickr Album Custom Post Type
	public function FlickrAlbumGallery_CPT() {
		$labels = array(
			'name'               => __( 'Flickr Album Gallery', 'flickr-album-gallery' ),
			'singular_name'      => __( 'Flickr Album Gallery', 'flickr-album-gallery' ),
			'add_new'            => __( 'Add New Album', 'flickr-album-gallery' ),
			'add_new_item'       => __( 'Add New Album', 'flickr-album-gallery' ),
			'edit_item'          => __( 'Edit Flickr Album', 'flickr-album-gallery' ),
			'new_item'           => __( 'New Flickr Album', 'flickr-album-gallery' ),
			'view_item'          => __( 'View Album Gallery', 'flickr-album-gallery' ),
			'search_items'       => __( 'Search Album Galleries', 'flickr-album-gallery' ),
			'not_found'          => __( 'No Album Galleries Found', 'flickr-album-gallery' ),
			'not_found_in_trash' => __( 'No Album Galleries Found in Trash', 'flickr-album-gallery' ),
			'parent_item_colon'  => __( 'Parent Album Gallery:', 'flickr-album-gallery' ),
			'all_items'          => __( 'All Album Galleries', 'flickr-album-gallery' ),
			'menu_name'          => __( 'Flickr Album Gallery', 'flickr-album-gallery' ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'menu_icon'           => 'dashicons-format-gallery',
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);

		register_post_type( 'fa_gallery', $args );
		add_filter( 'manage_edit-fa_gallery_columns', array( &$this, 'fa_gallery_columns' ) );
		add_action( 'manage_fa_gallery_posts_custom_column', array( &$this, 'fa_gallery_manage_columns' ), 10, 2 );
	}

	function fa_gallery_columns( $columns ) {
		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'title'         => __( 'Title' ),
			'fag-shortcode' => __( 'Copy Shortcode' ),
			'date'          => __( 'Date' ),
		);
		return $columns;
	}

	function fa_gallery_manage_columns( $columns, $post_id ) {
		global $post;
		switch ( $columns ) {
			case 'fag-shortcode':
				$fag_allowed_shortcode = array(
					'input' => array(
						'type'     => array(),
						'value'    => array(),
						'readonly' => array(),
					),
				);
				echo wp_kses( '<input type="text" value="[FAG id=' . $post_id . ']" readonly="readonly" />', $fag_allowed_shortcode );
				break;
			default:
				break;
		}
	}


	// 3 - Meta Box Creator
	public function Add_all_fag_meta_boxes() {
		add_meta_box( __( 'Configure Settings', 'flickr-album-gallery' ), __( 'Configure Settings', 'flickr-album-gallery' ), array( &$this, 'fag_meta_box_form_function' ), 'fa_gallery', 'normal', 'low' );
		add_meta_box( 'Our Pro Plugins', 'Our Pro Plugins', array( $this, 'Upgrade_to_meta_box_function' ), 'fa_gallery', 'normal', 'low' );
		add_meta_box( __( 'Flickr Album Gallery Shortcode', 'flickr-album-gallery' ), __( 'Flickr Album Gallery Shortcode', 'flickr-album-gallery' ), array( &$this, 'fag_shortcode_meta_box_form_function' ), 'fa_gallery', 'side', 'low' );
		add_meta_box( 'Rate Us', 'Rate Us', array( $this, 'Rate_us_meta_box_function' ), 'fa_gallery', 'side', 'low' );
	}

	/**
	 * Rate Us Meta Box
	 */
	public function Rate_us_meta_box_function() { ?>
		<style>
		.fag-rate-us span.dashicons{
			width: 30px;
			height: 30px;
		}
		.fag-rate-us span.dashicons-star-filled:before {
			content: "\f155";
			font-size: 30px;
		}
		.custnote{
			background-color: rgba(23, 31, 22, 0.64);
			color: #fff;
			width: 348px;
			border-radius: 5px;
			padding-right: 5px;
			padding-left: 5px;
			padding-top: 2px;
			padding-bottom: 2px;
		}
		</style>
		<div align="center">
			<p>Please Review & Rate Us On WordPress</p>
			<a class="upgrade-to-pro-demo .fag-rate-us" style="text-decoration: none; height: 40px; width: 40px;" href="https://wordpress.org/support/plugin/flickr-album-gallery/reviews/#new-post" target="_blank">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
		</div>
		<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
			<a href="https://wordpress.org/support/plugin/flickr-album-gallery/reviews/#new-post" target="_blank" class="button button-primary button-hero">RATE US</a>
		</div>
		<?php
	}

	/**
	 * Shortcode Meta Box
	 */
	public function fag_shortcode_meta_box_form_function() {
		?>
		<p><?php esc_html_e( 'Use below shortcode in any Page/Post to publish your Flickr Album Gallery', 'flickr-album-gallery' ); ?></p>
		<input readonly="readonly" type="text" value="<?php echo esc_attr( '[FAG id=' . get_the_ID() . ']' ); ?>">
		<?php
	}

	/**
	 * Upgrade To Meta Box
	 */
	public function Upgrade_to_meta_box_function() { ?>
		<style>
		#wpfrank-action-metabox h3 {
			font-size: 1rem;
			line-height: 1.4;
			margin-bottom: 5px;
		}
		#wpfrank-action-metabox a {
			display: inline-block !important;
			margin-bottom: 5px !important;
		}
		</style>
		<div class="welcome-panel-column" id="wpfrank-action-metabox">
			<h3>Unlock More Features in Flickr Album Gallery Pro</h3>
			<p>Like - 8 Light Box, Multiple Column Layouts, 8 Mouse Hover Effects, Various Thumbnail Settings</p>
			<a class="button button-primary button-hero load-customize hide-if-no-customize" target="_blank" href="http://wpfrank.com/demo/flickr-album-gallery-pro/">Check Pro Plugin Demo</a>
			<a class="button button-primary button-hero load-customize hide-if-no-customize" target="_blank" href="http://wpfrank.com/account/signup/flickr-album-gallery-pro">Buy Pro Plugin $29</a>
		</div>
		<?php
	}

	/**
	 * Gallery API Key & Album ID Form
	 */
	public function fag_meta_box_form_function( $post ) {
		// get plugin version
		$fag_plugin_version = '2.2.1';
		if ( is_admin() ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$fag_plugin_data    = get_plugin_data( __FILE__ );
			$fag_plugin_version = $fag_plugin_data['Version'];
		}
		//this is for older plugin version compatibility.
		if ( $fag_plugin_version == '2.2.1' ) {
			$FAG_Settings = unserialize( get_post_meta( $post->ID, 'fag_settings', true ) );
		} else {
			$FAG_Settings = get_post_meta( $post->ID, 'fag_settings', true );
		}

		if ( isset( $FAG_Settings[0]['fag_api_key'] ) && $FAG_Settings[0]['fag_album_id'] ) {
			$FAG_API_KEY    = $FAG_Settings[0]['fag_api_key'];
			$FAG_Album_ID   = $FAG_Settings[0]['fag_album_id'];
			$FAG_Show_Title = isset( $FAG_Settings[0]['fag_show_title'] ) ? $FAG_Settings[0]['fag_show_title'] : '';
			$FAG_Col_Layout = isset( $FAG_Settings[0]['fag_col_layout'] ) ? $FAG_Settings[0]['fag_col_layout'] : '';
			$FAG_Custom_CSS = isset( $FAG_Settings[0]['fag_custom_css'] ) ? $FAG_Settings[0]['fag_custom_css'] : '';
		}

		/**
		 * Default Settings
		 */
		if ( ! isset( $FAG_API_KEY ) ) {
			$FAG_API_KEY = '037c012784565c3b5691cc5a0aa912b7';
		}

		if ( ! isset( $FAG_Album_ID ) ) {
			$FAG_Album_ID = '72157698333322752';
		}

		if ( ! isset( $FAG_Show_Title ) ) {
			$FAG_Show_Title = 'yes';
		}

		if ( ! isset( $FAG_Col_Layout ) ) {
			$FAG_Col_Layout = 'col-md-3';
		}
		?>
		<p><strong><?php esc_html_e( 'Enter Flickr API Key', 'flickr-album-gallery' ); ?></strong></p>
		<input required type="text" style="width:50%;" name="flickr-api-key" id="flickr-api-key" value="<?php echo esc_attr( $FAG_API_KEY ); ?>"> <a title="Get your Flickr account API Key"href="https://wpfrank.com/how-to-get-flickr-api-key/" target="_blank"><?php _e( 'Get Your API Key', 'flickr-album-gallery' ); ?></a>

		<p><strong><?php esc_html_e( 'Enter Flickr Album ID', 'flickr-album-gallery' ); ?></strong></p>
		<input required type="text" style="width:50%;" name="flickr-album-id" id="flickr-album-id" value="<?php echo esc_attr( $FAG_Album_ID ); ?>"> <a title="Get your Flickr photo Album ID" href="https://wpfrank.com/how-to-get-flickr-album-id/" target="_blank"><?php _e( 'Get Your Album ID', 'flickr-album-gallery' ); ?></a>
		<br><br>

		<p><strong><?php esc_html_e( 'Show Gallery Title', 'flickr-album-gallery' ); ?></strong></p>
		<p>
		<input type="radio" name="fag-show-title" id="fag-show-title" value="yes" <?php if ( $FAG_Show_Title == 'yes' ) { echo esc_attr( 'checked' ); } ?>>  <i class="fa fa-check fa-2x"></i> <?php esc_html_e( 'Yes', 'flickr-album-gallery' ); ?>
		<input type="radio" name="fag-show-title" id="fag-show-title" value="no" <?php if ( $FAG_Show_Title == 'no' ) { echo esc_attr( 'checked' ); } ?>>  <i class="fa fa-times fa-2x"></i> <?php esc_html_e( 'No', 'flickr-album-gallery' ); ?>
		</p>
		<br>

		<p><strong><?php esc_html_e( 'Gallery Column Layout', 'flickr-album-gallery' ); ?></strong></p>
		<p>
			<select name="fag-col-layout" id="fag-col-layout" class="fag_layout">
				<optgroup label="<?php esc_html_e( 'Select Column Layout', 'flickr-album-gallery' ); ?>">
					<option value="col-md-4" <?php if ( $FAG_Col_Layout == 'col-md-4' ) { echo esc_attr( 'selected=selected' ); } ?>><?php esc_html_e( 'Three Column', 'flickr-album-gallery' ); ?></option>
					<option value="col-md-3" <?php if ( $FAG_Col_Layout == 'col-md-3' ) { echo esc_attr( 'selected=selected' ); } ?>><?php esc_html_e( 'Four Column', 'flickr-album-gallery' ); ?></option>
				</optgroup>
			</select>
		</p>
		<br>

		<p><strong><?php esc_html_e( 'Custom CSS', 'flickr-album-gallery' ); ?></strong></p>
		<?php
			if ( ! isset( $FAG_Custom_CSS ) ) {
				$FAG_Custom_CSS = '';
			} 
		?>
		<textarea name="fag-custom-css" id="fag-custom-css" rows="5" cols="97"><?php echo esc_textarea( $FAG_Custom_CSS ); ?></textarea>
		<p class="description">
			<?php esc_html_e( 'Enter any custom CSS you want to apply.', 'flickr-album-gallery' ); ?>.<br>
		</p>
		<p class="custnote"><strong><?php esc_html_e( 'Note:', 'flickr-album-gallery' ); ?></strong> <?php esc_html_e( "Please don't use STYLE tag in custom CSS code", 'flickr-album-gallery' ); ?></p>
		<hr>
		<p>
			<strong><label><?php esc_html_e( 'Review Appeal', 'flickr-album-gallery' ); ?></label></strong>
			<p>If you find my plugin is easy and useful to making your website wonderful. Please post a good feedback for my work to encourage me.</p>
			<p><a class="button button-primary" href="https://wordpress.org/support/plugin/flickr-album-gallery/reviews/#new-post" target="_new">Post A Feedback</a></p>
		</p>
		<?php
	}

	/**
	 * FAG Save
	 */
	public function Save_fag_meta_box_save( $PostID ) {
		if ( isset( $_POST['flickr-api-key'] ) && isset( $_POST['flickr-album-id'] ) ) {

			// get plugin version
			$fag_plugin_version = '2.2.1';
			if ( is_admin() ) {
				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$fag_plugin_data    = get_plugin_data( __FILE__ );
				$fag_plugin_version = $fag_plugin_data['Version'];
			}

			$FAG_API_KEY    = sanitize_text_field( wp_unslash( $_POST['flickr-api-key'] ) );
			$FAG_Album_ID   = sanitize_text_field( wp_unslash( $_POST['flickr-album-id'] ) );
			$FAG_Show_Title = sanitize_text_field( wp_unslash( $_POST['fag-show-title'] ) );
			$FAG_Col_Layout = sanitize_text_field( wp_unslash( $_POST['fag-col-layout'] ) );
			$FAG_Custom_CSS = sanitize_text_field( wp_unslash( $_POST['fag-custom-css'] ) );
			$FAGArray[]     = array(
				'fag_api_key'        => $FAG_API_KEY,
				'fag_album_id'       => $FAG_Album_ID,
				'fag_show_title'     => $FAG_Show_Title,
				'fag_col_layout'     => $FAG_Col_Layout,
				'fag_custom_css'     => $FAG_Custom_CSS,
				'fag_plugin_version' => $fag_plugin_version,
			);
			update_post_meta( $PostID, 'fag_settings', $FAGArray );
		}
	}
}//end class

global $FlickrAlbumGallery;
$FlickrAlbumGallery = new FlickrAlbumGallery();

// Flickr Album gallery Shortcode [FAG]
require_once 'shortcode.php';

global $FlickrAlbumGallery;
$FlickrAlbumGallery = new FlickrAlbumGallery();
require_once 'widget.php';

// pro plugin banner at all galleries page
add_action( 'admin_notices', 'fag_admin_pro_banner' );
function fag_admin_pro_banner() {
	global $pagenow;
	$fag_screen = get_current_screen();
	if ( $pagenow == 'edit.php' && $fag_screen->post_type == 'fa_gallery' && ! isset( $_GET['page'] ) ) {
		require_once 'banner.php';
		// get plugin version
	}
}

// more product page
require_once 'products.php';

// Recommended plugins page
if ( is_admin() ) {
	require_once 'plugin-notice/admin/getting-started.php';
}
?>