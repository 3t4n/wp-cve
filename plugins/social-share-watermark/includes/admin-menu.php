<?php
/**
 * Create A Simple Theme Options Panel
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'Synthiasoft_FB_Watermark_Option' ) ) {

	class Synthiasoft_FB_Watermark_Option {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'Synthiasoft_FB_Watermark_Option', 'Synthiasoft_add_admin_menu' ) );
				add_action( 'admin_init', array( 'Synthiasoft_FB_Watermark_Option', 'Synthiasoft_settings' ) );
			}

		}

		/**
		 * Returns all theme options
		 *
		 * @since 1.0.0
		 */
		public static function Synthiasoft_get_fb_watermark_options() {
			return get_option( 'fb_watermark_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function Synthiasoft_get_fb_watermark_option( $id ) {
			$options = self::Synthiasoft_get_fb_watermark_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function Synthiasoft_add_admin_menu() {
			add_menu_page(
				esc_html__( 'Facebook Share Watermark', 'synthiasoft' ),
				esc_html__( 'Facebook Share Watermark', 'synthiasoft' ),
				'manage_options',
				'fbwatermark-settings',
				array( 'Synthiasoft_FB_Watermark_Option', 'Synthiasoft_create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function Synthiasoft_settings() {
			register_setting( 'fb_watermark_options', 'fb_watermark_options', array( 'Synthiasoft_FB_Watermark_Option', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Checkbox
				if ( ! empty( $options['enable_overlay'] ) ) {
					$options['enable_overlay'] = 'on';
				} else {
					unset( $options['enable_overlay'] ); // Remove from options if not checked
}
				if ( ! empty( $options['fb_overlay'] ) ) {
					$options['fb_overlay'] = sanitize_text_field( $options['fb_overlay'] );
				}
				if ( ! empty( $options['fb_default'] ) ) {
					$options['fb_default'] = sanitize_text_field( $options['fb_default'] );
				}

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function Synthiasoft_create_admin_page() { ?>

			<div class="wrap" style="background: white; padding: 10px 30px;">

				<h1><?php esc_html_e( 'Share Watermark Setting', 'synthiasoft' ); ?></h1>
				<div><?php settings_errors() ?></div>

				<form method="post" action="options.php">

					<?php settings_fields( 'fb_watermark_options' ); ?>

					<table class="form-table wpex-custom-admin-login-table">

						<?php // Checkbox example ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Enable Watermark', 'synthiasoft' ); ?></th>
							<td>
								<?php $value = self::Synthiasoft_get_fb_watermark_option( 'enable_overlay' ); ?>
								<input type="checkbox" name="fb_watermark_options[enable_overlay]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Tick To enable Watermark', 'synthiasoft' ); ?>
							</td>
						</tr>

											
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Upload Watermark (1200x80 Pixel)', 'synthiasoft' ); ?></th>
							<td>
								<?php $value = self::Synthiasoft_get_fb_watermark_option( 'fb_overlay' ); ?>
					<input id="fb_overlay_img" type="hidden" name="fb_watermark_options[fb_overlay]" value="<?php echo esc_attr( $value ); ?>" />
					<img id="pv_overlay_img" src="<?php echo esc_attr( $value ); ?>" style="width: 50%;">
					<br>
<input id="upload_image_button_one" type="button" class="button-primary" value="Insert Image" />
</td>
						</tr>
<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Facebook Default Image', 'synthiasoft' ); ?></th>
							<td>
								<?php $value = self::Synthiasoft_get_fb_watermark_option( 'fb_default' ); ?>
					<input id="fb_default" type="hidden" name="fb_watermark_options[fb_default]" value="<?php echo esc_attr( $value ); ?>" />
					<img id="pv_d" src="<?php echo esc_attr( $value ); ?>" style="width: 50%;">
					<br>
<input id="upload_image_button_d" type="button" class="button-primary" value="Insert Image" />
</td>
						</tr>

					</table>
					
					<?php submit_button(); ?>

				</form>

			</div><!-- .wrap -->
		<?php }

	}
}
new Synthiasoft_FB_Watermark_Option();

// Helper function to use in your theme to return a theme option value
function myprefix_Synthiasoft_get_fb_watermark_option( $id = '' ) {
	return Synthiasoft_FB_Watermark_Option::Synthiasoft_get_fb_watermark_option( $id );
}