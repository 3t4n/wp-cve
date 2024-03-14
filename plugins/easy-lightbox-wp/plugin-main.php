<?php
/**
 * Plugin Name: Easy Lightbox
 * Plugin URI: https://wordpress.org/plugins/easy-lightbox-wp
 * Description: This plugin will enable an awesome Lightbox in your WordPress site.
 * Author: ShapedPlugin
 * Author URI: https://shapedplugin.com
 * Version: 1.1.2
 *
 * @package Easy_Lightbox
 *
 * @category Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue jQuery.
 *
 * @return void
 */
function lazy_p_wp_lightobx_free_jquery() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'init', 'lazy_p_wp_lightobx_free_jquery' );

/*Some Set-up*/
define( 'LAZY_P_WP_LIGBTBOX_FREE', plugin_dir_url( __FILE__ ) );
define( 'EASY_LIGHTBOX_VERSION', '1.1.2' );

/**
 * Include JavaScript and CSS files.
 *
 * @return void
 */
function lazy_p_wp_lightbox_free_files() {
	wp_enqueue_script( 'lazy-p-lightbox-image-l-js', LAZY_P_WP_LIGBTBOX_FREE . 'js/images-loaded.min.js', array( 'jquery' ), EASY_LIGHTBOX_VERSION, true );
	wp_enqueue_script( 'lazy-p-lightbox-main-js', LAZY_P_WP_LIGBTBOX_FREE . 'js/litebox.min.js', array( 'jquery' ), EASY_LIGHTBOX_VERSION, true );
	wp_enqueue_style( 'lazy-p-lightbox-main-css', LAZY_P_WP_LIGBTBOX_FREE . 'css/litebox.min.css', array(), EASY_LIGHTBOX_VERSION, false );
}
add_action( 'wp_enqueue_scripts', 'lazy_p_wp_lightbox_free_files' );

/**
 * Initialize Lightbox
 *
 * @return void
 */
function lughtbox_hin_head() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".litebox").liteBox();
			jQuery("div[id^=gallery] a").liteBox();

			jQuery('div.gallery a').attr('data-litebox-group', 'galone');
		});
	</script>
	<?php
}
add_action( 'wp_head', 'lughtbox_hin_head' );
