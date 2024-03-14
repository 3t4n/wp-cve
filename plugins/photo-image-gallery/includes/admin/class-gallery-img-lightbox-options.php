<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class UXGallery_Lightbox_Options {

	public function __construct() {
		add_action( 'uxgallery_save_lightbox_options', array( $this, 'save_options' ) );
	}

	/**
	 * Loads Lightbox options page
	 */
	public function load_page() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'Options_gallery_lightbox_styles' ) {
			if ( isset( $_GET['task'] ) ) {
				if ( $_GET['task'] == 'save' ) {
					do_action( 'uxgallery_save_lightbox_options' );
				}
			} else {
				$this->show_page();
			}
		}
	}

	/**
	 * Shows Lightbox options page
	 */
	public function show_page() {
		require( UXGALLERY_TEMPLATES_PATH.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'lightbox-options-html.php' );
	}

	/**
	 * Save Lightbox Options
	 */

	public function save_options() {
		if ( !isset( $_REQUEST['gallery_lightbox_options_nonce'] ) || ! wp_verify_nonce( $_REQUEST['gallery_lightbox_options_nonce'], 'uxgallery_nonce_save_lightbox_options' ) ) {
			wp_die( 'Security check fail' );
		}
		if (isset($_POST['params'])) {
			$share_params_keys = array(
				'uxgallery_lightbox_facebookButton'=>'false',
				'uxgallery_lightbox_twitterButton'=>'false',
				'uxgallery_lightbox_googleplusButton'=>'false',
				'uxgallery_lightbox_pinterestButton'=>'false',
				'uxgallery_lightbox_linkedinButton'=>'false',
				'uxgallery_lightbox_tumblrButton'=>'false',
				'uxgallery_lightbox_redditButton'=>'false',
				'uxgallery_lightbox_bufferButton'=>'false',
				'uxgallery_lightbox_diggButton'=>'false',
				'uxgallery_lightbox_vkButton'=>'false',
				'uxgallery_lightbox_yummlyButton'=>'false'
			);
			$params = $_POST['params'];

            if ( isset( $_POST['share_params'] ) ) {
                $new_share_params = wp_parse_args($_POST['share_params'], $share_params_keys);
                foreach ( $new_share_params as $name => $value ) {
                    update_option( $name, sanitize_text_field( $value ) );
                }
            }


			foreach ($params as $name => $value) {
				update_option($name, wp_unslash(sanitize_text_field($value)));
			}
			?>
			<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
			<?php
		}
		$this->show_page();
	}

}