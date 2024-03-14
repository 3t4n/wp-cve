<?php
/**
 * This file defines the admin class for managing (external) featured images.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements a few hooks for working with external featured images.
 *
 * The only exception is anything related to the meta box, which has its own
 * class.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */
class Nelio_Content_External_Featured_Image_Admin {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		if ( ! nc_get_site_id() ) {
			return;
		}//end if

		add_action( 'nelio_content_site_created', array( $this, 'set_efi_mode' ) );
		add_action( 'admin_init', array( $this, 'disable_original_nelioefi_hooks' ), 1 );

		add_action( 'after_switch_theme', array( $this, 'set_efi_mode' ) );
		add_action( 'after_switch_theme', array( $this, 'maybe_regenerate_efi_placeholder_thumbnails' ) );

		add_action( 'init', array( $this, 'add_settings_based_hooks' ) );

	}//end init()

	public function add_settings_based_hooks() {
		$settings = Nelio_Content_Settings::instance();
		if ( 'disabled' === $settings->get( 'auto_feat_image' ) ) {
			return;
		}//end if

		$aux = Nelio_Content_External_Featured_Image_Helper::instance();
		add_action( 'save_post', array( $aux, 'extract_featured_images_for_autoset' ) );
	}//end add_settings_based_hooks()

	public function disable_original_nelioefi_hooks() {
		remove_action( 'add_meta_boxes', 'nelioefi_add_url_metabox' );
		remove_action( 'save_post', 'nelioefi_save_url' );
	}//end disable_original_nelioefi_hooks()

	public function set_efi_mode() {
		$theme    = wp_get_theme();
		$efi_mode = false;

		switch ( strtolower( $theme['Template'] ) ) {

			case 'twenty-fourteen':
			case 'twenty-fifteen':
			case 'twenty-sixteen':
			case 'twenty-seventeen':
			case 'twenty-nineteen':
			case 'twenty-twenty':
			case 'twenty-twentyone':
				$efi_mode = 'default';
				break;

			case 'newspaper':
			case 'newsmag':
			case 'enfold':
				$efi_mode = 'double-quotes';
				break;

		}//end switch

		if ( empty( $efi_mode ) ) {
			return;
		}//end if

		$settings        = Nelio_Content_Settings::instance();
		$aux             = get_option( $settings->get_name(), array() );
		$aux['efi_mode'] = $efi_mode;
		update_option( $settings->get_name(), $aux );

	}//end set_efi_mode()

	public function maybe_regenerate_efi_placeholder_thumbnails() {

		// Let's see if the placeholder attachment exists and is available...
		$attach_id = get_option( 'nc_efi_placeholder_id', false );
		if ( $attach_id ) {
			$aux = get_post( $attach_id );
			if ( empty( $aux ) ) {
				return;
			}//end if
		}//end if

		// If it is, let's regenerate the thumbnails (or, at least, try to).
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$filename    = get_attached_file( $attach_id );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

	}//end maybe_regenerate_efi_placeholder_thumbnails()

}//end class
