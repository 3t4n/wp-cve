<?php
/**
 * This class handles the setting page for Ultimate WP Captcha.
 *
 * @package uwc
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'UWC_Setting_Page', false ) ) {
	/**
	 * UWC_Setting_Page Class.
	 */
	class UWC_Setting_Page {
		/**
		 * Hook.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'save_uwc_setting' ) );
			add_action( 'admin_menu', array( $this, 'uwc_admin_menu' ) );
			add_action( 'admin_footer', array( $this, 'uwc_admin_footer' ) );
		}
		/**
		 * Adds custom js and css in footer.
		 *
		 * @since 1.0.0
		 */
		public function uwc_admin_footer() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';
			if ( 'toplevel_page_ultimate-wp-captcha' !== $screen_id ) {
				return;
			}
			?>
			<style>
			.hide_this_element {
				display: none;
			}
			form#uwc_captcha input[type=text],
			form#uwc_captcha select {
				min-width: 300px;
			}
			#uwc_captcha .form-table tr td label {
				display: inline-block;
				padding: 9px 0 0 0;
			}
			</style>
			<script>
			jQuery(document).ready(function($){
				$(".captcha_method").change(function(){
					var captcha_method = $(this).val();
					if ( 'google' === captcha_method ) {
						$('.google_recaptcha').show();
						$('.hcaptcha').hide();
					} else if ( 'hcaptcha' === captcha_method ) {
						$('.google_recaptcha').hide();
						$('.hcaptcha').show();
					}
				});
			});
			</script>
			<?php
		}
		/**
		 * Handle saving of settings.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function save_uwc_setting() {
			$nonce_value = uwc_get_var( $_REQUEST['uwc_form_field'], uwc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

			if ( ! wp_verify_nonce( $nonce_value, 'uwc_form_action' ) ) {
				return;
			}

			$user_id = get_current_user_id();

			if ( $user_id <= 0 ) {
				return;
			}
			$captcha_method       = ! empty( $_POST['captcha_method'] ) ? uwc_clean( wp_unslash( $_POST['captcha_method'] ) ) : '';// @codingStandardsIgnoreLine.
			$google_site_key      = ! empty( $_POST['google_site_key'] ) ? uwc_clean( wp_unslash( $_POST['google_site_key'] ) ) : '';// @codingStandardsIgnoreLine.
			$google_site_secret   = ! empty( $_POST['google_site_secret'] ) ? uwc_clean( wp_unslash( $_POST['google_site_secret'] ) ) : '';// @codingStandardsIgnoreLine.
			$hcaptcha_site_key    = ! empty( $_POST['hcaptcha_site_key'] ) ? uwc_clean( wp_unslash( $_POST['hcaptcha_site_key'] ) ) : '';// @codingStandardsIgnoreLine.
			$hcaptcha_site_secret = ! empty( $_POST['hcaptcha_site_secret'] ) ? uwc_clean( wp_unslash( $_POST['hcaptcha_site_secret'] ) ) : '';// @codingStandardsIgnoreLine.
			$enable_captcha_for   = ! empty( $_POST['enable_captcha_for'] ) ? uwc_clean( wp_unslash( $_POST['enable_captcha_for'] ) ) : '';// @codingStandardsIgnoreLine.
			$captcha_theme        = ! empty( $_POST['captcha_theme'] ) ? uwc_clean( wp_unslash( $_POST['captcha_theme'] ) ) : '';// @codingStandardsIgnoreLine.
			$setting_data         = array(
				'captcha_method'       => $captcha_method,
				'google_site_key'      => $google_site_key,
				'google_site_secret'   => $google_site_secret,
				'hcaptcha_site_key'    => $hcaptcha_site_key,
				'hcaptcha_site_secret' => $hcaptcha_site_secret,
				'enable_captcha_for'   => $enable_captcha_for,
				'captcha_theme'        => $captcha_theme,
			);
			// phpcs:enable
			$uwc_setting_data = update_option( 'uwc_setting_data', $setting_data );
			if ( $uwc_setting_data ) {
				add_action( 'admin_notices', array( $this, 'uwc_data_notice' ) );
			}
		}
		/**
		 * Notice displays here.
		 *
		 * @since 1.0.0
		 */
		public function uwc_data_notice() {
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved successfully', 'ultimate-wp-captcha' ) . '</strong></p></div>';
		}
		/**
		 * Add menu items.
		 *
		 * @since 1.0.0
		 */
		public function uwc_admin_menu() {
			add_menu_page( esc_html__( 'UWC Captcha', 'ultimate-wp-captcha' ), esc_html__( 'UWC Captcha', 'ultimate-wp-captcha' ), 'manage_options', 'ultimate-wp-captcha', array( $this, 'uwc_setting_content' ) );
		}
		/**
		 * Displays setting content.
		 *
		 * @since 1.0.0
		 */
		public function uwc_setting_content() {
			$uwc_setting_data = get_option( 'uwc_setting_data' );
			$captcha_method   = '';
			$captcha_theme    = '';
			if ( isset( $uwc_setting_data['captcha_method'] ) ) {
				$captcha_method = $uwc_setting_data['captcha_method'];
			}
			if ( isset( $uwc_setting_data['captcha_theme'] ) ) {
				$captcha_theme = $uwc_setting_data['captcha_theme'];
			}
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Ultimate WP Captcha Setting Options', 'ultimate-wp-captcha' ); ?></h2>
				<form name="uwc_captcha" id="uwc_captcha" action="" method="post">
				<table class="form-table">
					<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Captcha Method', 'ultimate-wp-captcha' ); ?></th>
					<td>
					<select name="captcha_method" class="captcha_method">
						<option value="google" <?php selected( $captcha_method, 'google' ); ?>><?php esc_html_e( 'Google reCAPTCHA', 'ultimate-wp-captcha' ); ?></option>
						<option value="hcaptcha" <?php selected( $captcha_method, 'hcaptcha' ); ?>><?php esc_html_e( 'hCaptcha', 'ultimate-wp-captcha' ); ?></option>
					</select>
					<p class="google_recaptcha <?php echo ( 'google' !== $captcha_method && ! empty( $captcha_method ) ) ? 'hide_this_element' : ''; ?>">
					<?php
					/* translators: %s: Google captcha link. */
					echo sprintf( __( '<a href="%s" target="_blank">Click here</a> to create or view keys for Google reCaptcha.' ), esc_url( 'https://www.google.com/recaptcha/admin#list' ) );// @codingStandardsIgnoreLine.
					?>
					</p>
					<p class="hcaptcha <?php echo ( 'hcaptcha' !== $captcha_method ) ? 'hide_this_element' : ''; ?>">
					<?php
					/* translators: %s: hcaptcha link. */
					echo sprintf( __( '<a href="%s" target="_blank">Click here</a> to create or view keys for hCaptcha.' ), esc_url( 'https://www.hcaptcha.com/signup-interstitial' ) );// @codingStandardsIgnoreLine.
					?>
					</p>
					</td>
					</tr>
					<tr valign="top" class="google_recaptcha <?php echo ( 'google' !== $captcha_method && ! empty( $captcha_method ) ) ? 'hide_this_element' : ''; ?>">
					<th scope="row"><?php esc_html_e( 'Site Key', 'ultimate-wp-captcha' ); ?></th>
					<td><input type="text" name="google_site_key" value="<?php echo esc_attr( isset( $uwc_setting_data['google_site_key'] ) ? $uwc_setting_data['google_site_key'] : null ); ?>" /></td>
					</tr>
					<tr valign="top" class="hcaptcha <?php echo ( 'hcaptcha' !== $captcha_method ) ? 'hide_this_element' : ''; ?>">
					<th scope="row"><?php esc_html_e( 'Site Key', 'ultimate-wp-captcha' ); ?></th>
					<td><input type="text" name="hcaptcha_site_key" value="<?php echo esc_attr( isset( $uwc_setting_data['hcaptcha_site_key'] ) ? $uwc_setting_data['hcaptcha_site_key'] : null ); ?>" /></td>
					</tr>
					<tr valign="top" class="google_recaptcha <?php echo ( 'google' !== $captcha_method && ! empty( $captcha_method ) ) ? 'hide_this_element' : ''; ?>">
					<th scope="row"><?php esc_html_e( 'Secret Key', 'ultimate-wp-captcha' ); ?></th>
					<td><input type="text" name="google_site_secret" value="<?php echo esc_attr( isset( $uwc_setting_data['google_site_secret'] ) ? $uwc_setting_data['google_site_secret'] : null ); ?>" /></td>
					</tr>
					<tr valign="top" class="hcaptcha <?php echo ( 'hcaptcha' !== $captcha_method ) ? 'hide_this_element' : ''; ?>">
					<th scope="row"><?php esc_html_e( 'Secret Key', 'ultimate-wp-captcha' ); ?></th>
					<td><input type="text" name="hcaptcha_site_secret" value="<?php echo esc_attr( isset( $uwc_setting_data['hcaptcha_site_secret'] ) ? $uwc_setting_data['hcaptcha_site_secret'] : null ); ?>" /></td>
					</tr>
					<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Theme', 'ultimate-wp-captcha' ); ?></th>
					<td>
					<select name="captcha_theme" class="captcha_theme">
						<option value="light" <?php selected( $captcha_theme, 'light' ); ?>><?php esc_html_e( 'Light', 'ultimate-wp-captcha' ); ?></option>
						<option value="dark" <?php selected( $captcha_theme, 'dark' ); ?>><?php esc_html_e( 'Dark', 'ultimate-wp-captcha' ); ?></option>
					</select>
					</td>
					</tr>
					<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Enable Captcha for', 'ultimate-wp-captcha' ); ?></th>
					<td>
					<?php foreach ( uwc_get_captcha_display_location() as $key => $display_location ) { ?>
					<label><input type="checkbox" <?php checked( uwc_is_this_location_checked( $key ), 1 ); ?> name="enable_captcha_for[]" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $display_location ); ?></label><br>
					<?php } ?>
					</td>
					</tr>
				</table>
			<?php wp_nonce_field( 'uwc_form_action', 'uwc_form_field' ); ?>
			<?php submit_button( __( 'Save Settings', 'ultimate-wp-captcha' ), 'primary' ); ?>
		</form>
		</div>
			<?php
		}
	}
	new UWC_Setting_Page();
}
