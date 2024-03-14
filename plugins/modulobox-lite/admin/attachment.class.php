<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox Attachment class
 *
 * @class ModuloBox_Attachement
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Attachement {

	/**
	 * Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'init_hooks' ) );

	}

	/**
	 * Hook into actions and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init_hooks() {

		$options = get_option( MOBX_NAME );

		if ( isset( $options['galleryShortcode'] ) && $options['galleryShortcode'] && ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) ) {

			// Add the action to add custom fields for gallery shortcode
			add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
			// Enqueue the media UI script
			add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );

		}

	}

	/**
	 * Outputs a view template which can be used with wp.media.template
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_media_templates() {

		$styles = 'style="width:65px;float:left"';

		$field  = '<script type="text/html" id="tmpl-mobx-gallery-settings">';

			$field .= '<label class="setting">';
				$field .= '<span>' . esc_html__( 'Row Height', 'modulobox' ) . '</span>';
				$field .= '<input type="number" data-setting="mobx_row_height" value="220" min="10" max="1000" step="1" autocomplete="off" ' . $styles . '>';
			$field .= '</label>';

			$field .= '<label class="setting">';
				$field .= '<span>' . esc_html__( 'Item Spacing', 'modulobox' ) . '</span>';
				$field .= '<input type="number" data-setting="mobx_spacing" value="4" min="0" max="100" step="1" autocomplete="off" ' . $styles . '>';
			$field .= '</label>';

		$field .= '</script>';

		echo $field;

	}

	/**
	 * Enqueue script to handle custom view template with wp.media.template
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_enqueue_media() {

		wp_register_script( MOBX_NAME . 'gallery-settings', MOBX_ADMIN_URL . 'assets/js/gallery.js', array( 'media-views' ), MOBX_VERSION );
		wp_enqueue_script( MOBX_NAME . 'gallery-settings' );

	}
}

new ModuloBox_Attachement;
