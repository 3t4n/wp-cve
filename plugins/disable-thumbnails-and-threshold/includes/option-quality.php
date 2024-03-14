<?php
/*
Option Quality
Plugin: Disable Thumbnails, Threshold and Image Options
Since: 0.2
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class kgmimgquality {
	private $kgmimgquality_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'kgmimgquality_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'kgmimgquality_page_init' ) );
	}

	public function kgmimgquality_add_plugin_page() {
		add_management_page(
			'Image Quality',
			'Image Quality',
			'manage_options',
			'kgmimgquality',
			array( $this, 'kgmimgquality_create_admin_page' )
		);
	}

	public function kgmimgquality_create_admin_page() {
		$this->kgmimgquality_options = get_option( 'kgmimgquality_option_name' ); ?>

		<div class="wrap">
			<h2>Image Quality</h2>
			<p><strong>Remember you need to regenerate thumbnails for delete old thumbnails image already generated.</strong></p>
			<p>Plugin raccomended for regenerate thumbnails -> <a href="https://uskgm.it/reg-thumb" target="_blank">Regenerate Thumbnails</a></p>
			<p>WP-CLI media regeneration -> <a href="https://uskgm.it/WP-CLI-thumb-rgnrt" target="_blank">Documentation</a></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'kgmimgquality_option_group' );
					do_settings_sections( 'kgmimgquality-admin' );
					wp_nonce_field( 'qi_save_settings', 'kgmdttio_nonce' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function kgmimgquality_page_init() {
		register_setting(
			'kgmimgquality_option_group',
			'kgmimgquality_option_name',
			array( $this, 'kgmimgquality_sanitize' )
		);

		add_settings_section(
			'kgmimgquality_setting_section',
			'Settings', 
			array( $this, 'kgmimgquality_section_info' ),
			'kgmimgquality-admin' 
		);

		add_settings_field(
			'jpeg_quality',
			'JPEG Quality <br> <small>Default WordPress: 82%</small>',
			array( $this, 'jpeg_quality_callback' ),
			'kgmimgquality-admin',
			'kgmimgquality_setting_section'
		);
	}

	public function kgmimgquality_sanitize($input) {
		$sanitary_values = array();
		$valid           = true;
		
		if ( isset( $_POST['kgmdttio_nonce'] ) && wp_verify_nonce( $_POST['kgmdttio_nonce'], 'qi_save_settings' ) ) {
			if ( isset( $input['jpeg_quality'] ) ) {
				$sanitary_values['jpeg_quality'] = sanitize_text_field($input['jpeg_quality']);
			}
		} else {
			$valid = false;
			add_settings_error( 'kgmimgquality_option_notice', 'nonce_error', 'Nonce validation error.' );
		}

		if ( ! $valid ) {
			$sanitary_values = get_option( 'kgmimgquality_option_name' );
		}

		return $sanitary_values;
	}

	public function kgmimgquality_section_info() {
		
	}

	public function jpeg_quality_callback() {
		printf(
			'<input class="regular-text" type="number" step="1" min="0" max="100" name="kgmimgquality_option_name[jpeg_quality]" id="jpeg_quality" value="%s">',
			isset( $this->kgmimgquality_options['jpeg_quality'] ) ? esc_attr( $this->kgmimgquality_options['jpeg_quality']) : ''
		);
	}

}
if ( is_admin() )
	$kgmimgquality = new kgmimgquality();
