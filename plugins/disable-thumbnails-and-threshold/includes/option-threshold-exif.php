<?php
/*
Option Threshold & EXIF
Plugin: Disable Thumbnails, Threshold and Image Options
Since: 0.1
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class kgmdisablethreshold {
	private $kgmdisablethreshold_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'kgmdisablethreshold_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'kgmdisablethreshold_page_init' ) );
	}

	public function kgmdisablethreshold_add_plugin_page() {
		add_management_page(
			'Image Threshold&EXIF',
			'Image Threshold&EXIF',
			'manage_options',
			'kgmdisablethreshold',
			array( $this, 'kgmdisablethreshold_create_admin_page' )
		);
	}

	public function kgmdisablethreshold_create_admin_page() {
		$this->kgmdisablethreshold_options = get_option( 'kgmdisablethreshold_option_name' ); ?>

		<div class="wrap">
			<h2>Disable Threshold&EXIF</h2>
			<p><strong>Remember you need to regenerate thumbnails for delete old thumbnails image already generated.</strong></p>
			<p>Plugin raccomended for regenerate thumbnails -> <a href="http://uskgm.it/reg-thumb" target="_blank">Regenerate Thumbnails</a></p>
			<p>WP-CLI media regeneration -> <a href="http://uskgm.it/WP-CLI-thumb-rgnrt" target="_blank">Documentation</a></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'kgmdisablethreshold_option_group' );
					do_settings_sections( 'kgmdisablethreshold-admin' );
					wp_nonce_field( 'ts_save_settings', 'kgmdttio_nonce' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function kgmdisablethreshold_page_init() {
		register_setting(
			'kgmdisablethreshold_option_group',
			'kgmdisablethreshold_option_name',
			array( $this, 'kgmdisablethreshold_sanitize' )
		);

		add_settings_section(
			'kgmdisablethreshold_setting_section',
			'Settings', 
			array( $this, 'kgmdisablethreshold_section_info' ),
			'kgmdisablethreshold-admin' 
		);

		add_settings_field(
			'new_threshold',
			'New Size Threshold <br> <small>Default WordPress: 2560</small>',
			array( $this, 'new_threshold_callback' ),
			'kgmdisablethreshold-admin',
			'kgmdisablethreshold_setting_section'
		);
		add_settings_field(
			'disable_threshold',
			'Disable Threshold',
			array( $this, 'disable_threshold_callback' ),
			'kgmdisablethreshold-admin',
			'kgmdisablethreshold_setting_section'
		);
		add_settings_field(
			'disable_image_rotation_exif',
			'Disable Image Rotation by EXIF',
			array( $this, 'disable_image_rotation_exif_callback' ),
			'kgmdisablethreshold-admin',
			'kgmdisablethreshold_setting_section'
		);
	}

	public function kgmdisablethreshold_sanitize($input) {
		$sanitary_values = array();
		$valid           = true;

		if ( isset( $_POST['kgmdttio_nonce'] ) && wp_verify_nonce( $_POST['kgmdttio_nonce'], 'ts_save_settings' ) ) {
			if ( isset( $input['new_threshold'] ) ) {
				$sanitary_values['new_threshold'] = sanitize_text_field($input['new_threshold']);
			}
			if ( isset( $input['disable_threshold'] ) ) {
				$sanitary_values['disable_threshold'] = $input['disable_threshold'];
			}
			if ( isset( $input['disable_image_rotation_exif'] ) ) {
				$sanitary_values['disable_image_rotation_exif'] = $input['disable_image_rotation_exif'];
			}
		} else {
			$valid = false;
			add_settings_error( 'kgmdisablethreshold_option_notice', 'nonce_error', 'Nonce validation error.' );
		}

		if ( ! $valid ) {
			$sanitary_values = get_option( 'kgmdisablethreshold_option_name' );
		}
		
		return $sanitary_values;
	}

	public function kgmdisablethreshold_section_info() {
		
	}

	public function new_threshold_callback() {
		printf(
			'<input class="regular-text" type="number" step="1" min="0" name="kgmdisablethreshold_option_name[new_threshold]" id="new_threshold" value="%s">',
			isset( $this->kgmdisablethreshold_options['new_threshold'] ) ? esc_attr( $this->kgmdisablethreshold_options['new_threshold']) : ''
		);
	}

	public function disable_threshold_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethreshold_option_name[disable_threshold]" id="disable_threshold" value="disable_threshold" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethreshold_options['disable_threshold'] ) && $this->kgmdisablethreshold_options['disable_threshold'] === 'disable_threshold' ) ? 'checked' : ''
		);
	}

	public function disable_image_rotation_exif_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethreshold_option_name[disable_image_rotation_exif]" id="disable_image_rotation_exif" value="disable_image_rotation_exif" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethreshold_options['disable_image_rotation_exif'] ) && $this->kgmdisablethreshold_options['disable_image_rotation_exif'] === 'disable_image_rotation_exif' ) ? 'checked' : ''
		);
	}

}
if ( is_admin() )
	$kgmdisablethreshold = new kgmdisablethreshold();
