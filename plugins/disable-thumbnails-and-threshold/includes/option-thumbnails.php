<?php
/*
Option Thumbnail
Plugin: Disable Thumbnails, Threshold and Image Options
Since: 0.1
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class kgmdisablethumbnails {
	private $kgmdisablethumbnails_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'kgmdisablethumbnails_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'kgmdisablethumbnails_page_init' ) );
	}

	public function kgmdisablethumbnails_add_plugin_page() {
		add_management_page(
			'Image Sizes', 
			'Image Sizes', 
			'manage_options', 
			'kgmdisablethumbnails', 
			array( $this, 'kgmdisablethumbnails_create_admin_page' )
		);
	}

	public function kgmdisablethumbnails_create_admin_page() {
		$this->kgmdisablethumbnails_options = get_option( 'kgmdisablethumbnails_option_name' ); ?>

		<div class="wrap">
			<h2>Image Thumbnnails</h2>
			<p><strong>Remember you need to regenerate thumbnails for delete old thumbnails image already generated.</strong></p>
			<p>Plugin raccomended for regenerate thumbnails -> <a href="http://uskgm.it/reg-thumb" target="_blank">Regenerate Thumbnails</a></p>
			<p>WP-CLI media regeneration -> <a href="http://uskgm.it/WP-CLI-thumb-rgnrt" target="_blank">Documentation</a></p>
			<?php settings_errors(); ?>
			
			<form method="post" action="options.php">
				<?php
					settings_fields( 'kgmdisablethumbnails_option_group' );
					do_settings_sections( 'kgmdisablethumbnails-admin' );
					wp_nonce_field( 'dt_save_settings', 'kgmdttio_nonce' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function kgmdisablethumbnails_page_init() {
		register_setting(
			'kgmdisablethumbnails_option_group',
			'kgmdisablethumbnails_option_name', 
			array( $this, 'kgmdisablethumbnails_sanitize' ) 
		);

		add_settings_section(
			'kgmdisablethumbnails_setting_section', 
			'Settings', 
			array( $this, 'kgmdisablethumbnails_section_info' ),
			'kgmdisablethumbnails-admin' 
		);

		add_settings_field(
			'thumbnail',
			'Thumbnail', 
			array( $this, 'thumbnail_callback' ),
			'kgmdisablethumbnails-admin', 
			'kgmdisablethumbnails_setting_section' 
		);
		add_settings_field(
			'medium',
			'Medium', 
			array( $this, 'medium_callback' ),
			'kgmdisablethumbnails-admin', 
			'kgmdisablethumbnails_setting_section' 
		);
		add_settings_field(
			'medium_large',
			'Medium Large',
			array( $this, 'medium_large_callback' ),
			'kgmdisablethumbnails-admin',
			'kgmdisablethumbnails_setting_section' 
		);
		add_settings_field(
			'large', 
			'Large', 
			array( $this, 'large_callback' ),
			'kgmdisablethumbnails-admin', 
			'kgmdisablethumbnails_setting_section' 
		);
		add_settings_field(
			'full', 
			'Full', 
			array( $this, 'full_callback' ),
			'kgmdisablethumbnails-admin', 
			'kgmdisablethumbnails_setting_section' 
		);

		$image_sizes = wp_get_additional_image_sizes();
		foreach ( $image_sizes as $key => $image_size ) {
			if ( $image_size['crop'] == 1 ) {
				$crop = "cropped";
			} else {
				$crop = "";
			}
	        add_settings_field(
				$key, 
				$key . '<br><small>(' . esc_attr( $image_size['width'] ) . 'x' . esc_attr( $image_size['height'] ) . ')</small><br><small>' . esc_attr( $crop ) . '</small>', 
				array( $this, 'ext_callback' ),
				'kgmdisablethumbnails-admin', 
				'kgmdisablethumbnails_setting_section', 
				$name = $key
			);
		}
	}

	public function kgmdisablethumbnails_sanitize($input) {
		$sanitary_values = array();
		$valid           = true;
		
		if ( isset( $_POST['kgmdttio_nonce'] ) && wp_verify_nonce( $_POST['kgmdttio_nonce'], 'dt_save_settings' ) ) {
			if ( isset( $input['thumbnail'] ) ) {
				$sanitary_values['thumbnail'] = $input['thumbnail'];
			}
			if ( isset( $input['medium'] ) ) {
				$sanitary_values['medium'] = $input['medium'];
			}
			if ( isset( $input['medium_large'] ) ) {
				$sanitary_values['medium_large'] = $input['medium_large'];
			}
			if ( isset( $input['large'] ) ) {
				$sanitary_values['large'] = $input['large'];
			}
			if ( isset( $input['full'] ) ) {
				$sanitary_values['full'] = $input['full'];
			}

			$image_sizes = wp_get_additional_image_sizes();
			foreach ( $image_sizes as $key => $image_size ) {
				if ( isset( $input[$key] ) ) {
					$sanitary_values[$key] = $input[$key];
				}
			}
		} else {
			$valid = false;
			add_settings_error( 'kgmdisablethumbnails_option_notice', 'nonce_error', 'Nonce validation error.' );
		}

		if ( ! $valid ) {
			$sanitary_values = get_option( 'kgmdisablethumbnails_option_name' );
		}
		
		return $sanitary_values;

		return $sanitary_values;
	}

	public function kgmdisablethumbnails_section_info() {
		
	}

	public function thumbnail_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethumbnails_option_name[thumbnail]" id="thumbnail" value="thumbnail" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethumbnails_options['thumbnail'] ) && $this->kgmdisablethumbnails_options['thumbnail'] === 'thumbnail' ) ? 'checked' : ''
		);
	}

	public function medium_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethumbnails_option_name[medium]" id="medium" value="medium" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethumbnails_options['medium'] ) && $this->kgmdisablethumbnails_options['medium'] === 'medium' ) ? 'checked' : ''
		);
	}

	public function medium_large_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethumbnails_option_name[medium_large]" id="medium_large" value="medium_large" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethumbnails_options['medium_large'] ) && $this->kgmdisablethumbnails_options['medium_large'] === 'medium_large' ) ? 'checked' : ''
		);
	}

	public function large_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethumbnails_option_name[large]" id="large" value="large" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethumbnails_options['large'] ) && $this->kgmdisablethumbnails_options['large'] === 'large' ) ? 'checked' : ''
		);
	}

	public function full_callback() {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethumbnails_option_name[full]" id="full" value="full" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethumbnails_options['full'] ) && $this->kgmdisablethumbnails_options['full'] === 'full' ) ? 'checked' : ''
		);
	}

	public function ext_callback($name) {
		printf(
			'<label class="switch"><input type="checkbox" name="kgmdisablethumbnails_option_name['.$name.']" id="'.$name.'" value="'.$name.'" %s><span class="slider"></span></label>',
			( isset( $this->kgmdisablethumbnails_options[$name] ) && $this->kgmdisablethumbnails_options[$name] === $name ) ? 'checked' : ''
		);
	}

}
if ( is_admin() )
	$kgmdisablethumbnails = new kgmdisablethumbnails();
