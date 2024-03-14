<?php
/**
 * Change widget order.
 *
 * @package Swift_Control
 */

namespace CustomLoginDashboard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Migration {

	/**
	 * The old plugin slug.
	 *
	 * @var string
	 */
	private $old_plugin_basename = '';

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_cldashboard_migration', [ $this, 'handler' ] );

	}

	/**
	 * The request handler.
	 */
	public function handler() {

		$this->validate();
		$this->migrate();

	}

	/**
	 * Validate the data.
	 */
	private function validate() {

		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'cldashboard_nonce_migration', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'erident-custom-login-and-dashboard' ), 401 );
		}

		// Check against old plugin basename existence.
		if ( ! isset( $_POST['old_plugin_basename'] ) || empty( $_POST['old_plugin_basename'] ) ) {
			wp_send_json_error( __( 'Old plugin basename is not specified', 'erident-custom-login-and-dashboard' ), 401 );
		}

		if (
			defined( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION' )
			|| file_exists( WP_CONTENT_DIR . '/plugins/ultimate-dashboard/ultimate-dashboard.php' )
		) {
			wp_send_json_error(
				__(
					'You already have Ultimate Dashboard installed. You may want to uninstall Ultimate Dashboard manually and start the migration again. Make sure the "Remove Data on Uninstall" option in Ultimate Dashboard is NOT checked so that your existing Ultimate Dashboard settings will stay in place.',
					'erident-custom-login-and-dashboard'
				),
				401
			);
		}

		if ( ! function_exists( 'fsockopen' ) ) {
			wp_send_json_error(
				__(
					'Your server does not have the fsockopen function enabled. This is required to check the internet connection. Please contact your host and ask them to enable this function.',
					'erident-custom-login-and-dashboard'
				),
				503
			);
		}

		$internet_connected = $this->is_internet_connected();
		$internet_connected = ! $internet_connected ? $this->is_internet_connected( 443 ) : $internet_connected;

		// Check against internet connection.
		if ( ! $internet_connected ) {
			wp_send_json_error( 'Seems like you are not connected to the internet. A stable connection is required to download the Ultimate Dashboard plugin.<br>Please check your internet connection. If you are using a proxy, try to disable it.', 503 );
		}

		$this->old_plugin_basename = sanitize_text_field( $_POST['old_plugin_basename'] );
	}

	/**
	 * Check if the internet is connected.
	 *
	 * Thanks to Alfred <https://stackoverflow.com/users/484082/alfred>
	 *
	 * @link https://stackoverflow.com/questions/4860365/determine-in-php-script-if-connected-to-internet#answer-4860432
	 *
	 * @param int $port The port to check.
	 * @return bool
	 */
	private function is_internet_connected( $port = 80 ) {

		// Website, port (try 80 or 443).
		$connected = @fsockopen( 'google.com', 80 );

		if ( $connected ) {
			// Action when connected.
			$is_connected = true;
			fclose( $connected );
		} else {
			// Action in connection failure.
			$is_connected = false;
		}

		return $is_connected;

	}

	/**
	 * Migrate to Ultimate Dashboard.
	 */
	private function migrate() {

		if ( ! file_exists( WP_PLUGIN_DIR . '/' . $this->old_plugin_basename ) ) {
			wp_send_json_error( __( 'Erident Custom Login & Dashboard plugin is not found', 'erident-custom-login-and-dashboard' ), 403 );
		}

		$this->migrate_settings();

		update_option( 'udb_migration_from_erident', 1 );

		deactivate_plugins( $this->old_plugin_basename );

		$deletion = delete_plugins( [ $this->old_plugin_basename ] );

		if ( $deletion && ! is_wp_error( $deletion ) ) {
			// If the plugin is deleted successfully, let's delete the option.
			delete_option( 'plugin_erident_settings' );
			wp_send_json_success( __( 'Erident Custom Login & Dashboard plugin has been removed', 'erident-custom-login-and-dashboard' ) );
		} elseif ( is_wp_error( $deletion ) ) {
			wp_send_json_error( $deletion->get_error_message(), 403 );
		} elseif ( null === $deletion ) {
			wp_send_json_error( __( 'Filesystem credentials are required', 'erident-custom-login-and-dashboard' ), 403 );
		} else {
			wp_send_json_error( __( 'Erident Custom Login & Dashboard plugin is not specified', 'erident-custom-login-and-dashboard' ), 401 );
		}

	}

	/**
	 * Migrate Erident Custom Login & Dashboard settings to Ultimate Dashboard settings.
	 */
	private function migrate_settings() {

		$this->migrate_general_settings();
		$this->migrate_login_settings();

	}

	/**
	 * Migrate general settings.
	 */
	private function migrate_general_settings() {

		$udb_general_options  = get_option( 'udb_settings', [] );
		$udb_branding_options = get_option( 'udb_branding', [] );
		$erident_options      = get_option( 'plugin_erident_settings', [] );

		if ( empty( $erident_options ) ) {
			return;
		}

		$is_general_changed  = false;
		$is_branding_changed = false;

		$clean_deactivation = isset( $erident_options['dashboard_delete_db'] ) ? $erident_options['dashboard_delete_db'] : 0;
		$clean_deactivation = 'yes' === strtolower( $clean_deactivation ) ? 1 : $clean_deactivation;
		$clean_deactivation = 'no' === strtolower( $clean_deactivation ) ? 0 : $clean_deactivation;

		if ( $clean_deactivation ) {
			$udb_general_options['remove-on-uninstall'] = 1;

			$is_general_changed = true;
		}

		$footer_text = isset( $erident_options['dashboard_data_left'] ) ? stripslashes( $erident_options['dashboard_data_left'] ) : '';

		if ( $footer_text ) {
			$udb_branding_options['footer_text'] = $footer_text;

			$is_branding_changed = true;
		}

		$version_text = isset( $erident_options['dashboard_data_right'] ) ? stripslashes( $erident_options['dashboard_data_right'] ) : '';

		if ( $version_text ) {
			$udb_branding_options['version_text'] = $version_text;

			$is_branding_changed = true;
		}

		if ( $is_general_changed ) {
			update_option( 'udb_settings', $udb_general_options );
		}

		if ( $is_branding_changed ) {
			update_option( 'udb_branding', $udb_branding_options );
		}

	}

	/**
	 * Migrate login customization settings.
	 *
	 * We don't need to use "is_changed" flag here because
	 * almost all Erident settings are about login customization.
	 */
	private function migrate_login_settings() {

		$udb_login_options = get_option( 'udb_login', [] );
		$erident_options   = get_option( 'plugin_erident_settings', [] );

		$udb_login_options['logo_url'] = get_bloginfo( 'url' );

		if ( empty( $erident_options ) ) {
			update_option( 'udb_login', $udb_login_options );
			return;
		}

		$logo_image_url = isset( $erident_options['dashboard_image_logo'] ) && ! empty( $erident_options['dashboard_image_logo'] ) ? $erident_options['dashboard_image_logo'] : '';

		if ( $logo_image_url ) {
			$udb_login_options['logo_image'] = $logo_image_url;
		}

		$logo_width = isset( $erident_options['dashboard_image_logo_width'] ) && ! empty( $erident_options['dashboard_image_logo_width'] ) ? $erident_options['dashboard_image_logo_width'] : '';

		if ( $logo_width ) {
			$logo_width = trim( $logo_width ) . 'px';

			// Logo width doesn't exist in UDB login customizer, but let's keep this.
			$udb_login_options['logo_width'] = $logo_width;
		}

		$logo_height = isset( $erident_options['dashboard_image_logo_height'] ) && ! empty( $erident_options['dashboard_image_logo_height'] ) ? $erident_options['dashboard_image_logo_height'] : '';

		if ( $logo_height ) {
			$logo_height = trim( $logo_height ) . 'px';

			$udb_login_options['logo_height'] = $logo_height;
		}

		$logo_text = isset( $erident_options['dashboard_power_text'] ) && ! empty( $erident_options['dashboard_power_text'] ) ? $erident_options['dashboard_power_text'] : '';

		if ( $logo_text ) {
			$udb_login_options['logo_title'] = $logo_text;
		}

		$bg_color = isset( $erident_options['top_bg_color'] ) && ! empty( $erident_options['top_bg_color'] ) ? $erident_options['top_bg_color'] : '';

		if ( $bg_color ) {
			$udb_login_options['bg_color'] = $bg_color;
		}

		$bg_image_url = isset( $erident_options['top_bg_image'] ) && ! empty( $erident_options['top_bg_image'] ) ? $erident_options['top_bg_image'] : '';

		if ( $bg_image_url ) {
			$udb_login_options['bg_image'] = $bg_image_url;
		}

		$bg_repeat = isset( $erident_options['top_bg_repeat'] ) && ! empty( $erident_options['top_bg_repeat'] ) ? $erident_options['top_bg_repeat'] : '';

		if ( $bg_repeat ) {
			$udb_login_options['bg_repeat'] = $bg_repeat;
		}

		$horizontal_bg_pos = isset( $erident_options['top_bg_xpos'] ) && ! empty( $erident_options['top_bg_xpos'] ) ? $erident_options['top_bg_xpos'] : '';
		$horizontal_bg_pos = trim( $horizontal_bg_pos );

		$vertical_bg_pos = isset( $erident_options['top_bg_ypos'] ) && ! empty( $erident_options['top_bg_ypos'] ) ? $erident_options['top_bg_ypos'] : '';
		$vertical_bg_pos = trim( $vertical_bg_pos );

		$bg_position        = $horizontal_bg_pos . ' ' . $vertical_bg_pos;
		$bg_custom_position = '';

		$udb_bg_positions = array(
			'left top',
			'left center',
			'left bottom',
			'center top',
			'center center',
			'center bottom',
			'right top',
			'right center',
			'right bottom',
			'custom',
		);

		if ( ! in_array( $bg_position, $udb_bg_positions, true ) ) {
			$bg_custom_position = $bg_position;
			$bg_position        = 'custom';
		}

		if ( $bg_position ) {
			$udb_login_options['bg_position'] = $bg_position;
		}

		if ( $bg_custom_position ) {
			$udb_login_options['bg_custom_position'] = $bg_custom_position;
		}

		$bg_size        = isset( $erident_options['top_bg_size'] ) && ! empty( $erident_options['top_bg_size'] ) ? $erident_options['top_bg_size'] : '';
		$bg_custom_size = '';

		$udb_bg_sizes = array(
			'auto',
			'cover',
			'contain',
			'custom',
		);

		if ( ! in_array( $bg_size, $udb_bg_sizes, true ) ) {
			$bg_custom_size = $bg_position;
			$bg_size        = 'custom';
		}

		if ( $bg_size ) {
			$udb_login_options['bg_size'] = $bg_size;
		}

		if ( $bg_custom_size ) {
			$udb_login_options['bg_custom_size'] = $bg_custom_size;
		}

		$form_width = isset( $erident_options['dashboard_login_width'] ) && ! empty( $erident_options['dashboard_login_width'] ) ? $erident_options['dashboard_login_width'] : '';

		if ( $form_width ) {
			$form_width = trim( $form_width ) . 'px';

			$udb_login_options['form_width'] = $form_width;
		}

		$form_border_radius = isset( $erident_options['dashboard_login_radius'] ) && ! empty( $erident_options['dashboard_login_radius'] ) ? $erident_options['dashboard_login_radius'] : '';

		if ( $form_border_radius ) {
			$form_border_radius = trim( $form_border_radius ) . 'px';

			$udb_login_options['form_border_radius'] = $form_border_radius;
		}

		$form_border_width = isset( $erident_options['dashboard_border_thick'] ) && ! empty( $erident_options['dashboard_border_thick'] ) ? $erident_options['dashboard_border_thick'] : '';

		if ( $form_border_width ) {
			$form_border_width = trim( $form_border_width ) . 'px';

			$udb_login_options['form_border_width'] = $form_border_width;
		}

		$form_border_style = isset( $erident_options['dashboard_login_border'] ) && ! empty( $erident_options['dashboard_login_border'] ) ? $erident_options['dashboard_login_border'] : '';

		if ( $form_border_style ) {
			$udb_login_options['form_border_style'] = $form_border_style;
		}

		$form_border_color = isset( $erident_options['dashboard_border_color'] ) && ! empty( $erident_options['dashboard_border_color'] ) ? $erident_options['dashboard_border_color'] : '';

		if ( $form_border_color ) {
			$udb_login_options['form_border_color'] = $form_border_color;
		}

		$enable_form_shadow = isset( $erident_options['dashboard_check_form_shadow'] ) ? $erident_options['dashboard_check_form_shadow'] : 0;
		$enable_form_shadow = 'yes' === strtolower( $enable_form_shadow ) ? 1 : $enable_form_shadow;
		$enable_form_shadow = 'no' === strtolower( $enable_form_shadow ) ? 0 : $enable_form_shadow;
		$enable_form_shadow = absint( $enable_form_shadow );

		if ( $enable_form_shadow ) {
			$udb_login_options['enable_form_shadow'] = 1;
		}

		$form_shadow_color = isset( $erident_options['dashboard_form_shadow'] ) && ! empty( $erident_options['dashboard_form_shadow'] ) ? $erident_options['dashboard_form_shadow'] : '';

		if ( $form_shadow_color ) {
			$udb_login_options['form_shadow_color'] = $form_shadow_color;
		}

		$form_bg_color = isset( $erident_options['dashboard_login_bg'] ) && ! empty( $erident_options['dashboard_login_bg'] ) ? $erident_options['dashboard_login_bg'] : '';

		if ( isset( $erident_options['dashboard_login_bg_opacity'] ) ) {
			// This `dashboard_login_bg_opacity` won't be used anymore since we use colorpicker alpha now.
			$form_bg_opacity = '' !== $erident_options['dashboard_login_bg_opacity'] ? $erident_options['dashboard_login_bg_opacity'] : 1; // 0 is allowed here.

			if ( false === stripos( $form_bg_color, 'rgba' ) && 1 > $form_bg_opacity ) {
				$form_bg_color = \ariColor::newColor( $form_bg_color );
				$form_bg_color = $form_bg_color->getNew( 'alpha', $form_bg_opacity )->toCSS( 'rgba' );
			}
		}

		if ( $form_bg_color ) {
			$udb_login_options['form_bg_color'] = $form_bg_color;
		}

		$form_bg_image_url = isset( $erident_options['login_bg_image'] ) && ! empty( $erident_options['login_bg_image'] ) ? $erident_options['login_bg_image'] : '';

		if ( $form_bg_image_url ) {
			$udb_login_options['form_bg_image'] = $form_bg_image_url;
		}

		$form_bg_repeat = isset( $erident_options['login_bg_repeat'] ) && ! empty( $erident_options['login_bg_repeat'] ) ? $erident_options['login_bg_repeat'] : '';

		if ( $form_bg_repeat ) {
			$udb_login_options['form_bg_repeat'] = $form_bg_repeat;
		}

		$form_horizontal_bg_pos = isset( $erident_options['login_bg_xpos'] ) && ! empty( $erident_options['login_bg_xpos'] ) ? $erident_options['login_bg_xpos'] : '';
		$form_horizontal_bg_pos = trim( $form_horizontal_bg_pos );

		$form_vertical_bg_pos = isset( $erident_options['login_bg_ypos'] ) && ! empty( $erident_options['login_bg_ypos'] ) ? $erident_options['login_bg_ypos'] : '';
		$form_vertical_bg_pos = trim( $form_vertical_bg_pos );

		$form_bg_position        = $form_horizontal_bg_pos . ' ' . $form_horizontal_bg_pos;
		$form_bg_custom_position = '';

		if ( ! in_array( $form_bg_position, $udb_bg_positions, true ) ) {
			$form_bg_custom_position = $form_bg_position;
			$form_bg_position        = 'custom';
		}

		if ( $form_bg_position ) {
			$udb_login_options['form_bg_position'] = $form_bg_position;
		}

		if ( $form_bg_custom_position ) {
			$udb_login_options['form_bg_custom_position'] = $form_bg_custom_position;
		}

		$labels_color = isset( $erident_options['dashboard_text_color'] ) && ! empty( $erident_options['dashboard_text_color'] ) ? $erident_options['dashboard_text_color'] : '';

		if ( $labels_color ) {
			$udb_login_options['labels_color'] = $labels_color;
		}

		$labels_font_size = isset( $erident_options['dashboard_label_text_size'] ) && ! empty( $erident_options['dashboard_label_text_size'] ) ? $erident_options['dashboard_label_text_size'] : '';

		if ( $labels_font_size ) {
			$labels_font_size = trim( $labels_font_size ) . 'px';

			$udb_login_options['labels_font_size'] = $labels_font_size;
		}

		$fields_text_color = isset( $erident_options['dashboard_input_text_color'] ) && ! empty( $erident_options['dashboard_input_text_color'] ) ? $erident_options['dashboard_input_text_color'] : '';

		if ( $fields_text_color ) {
			$udb_login_options['fields_text_color']       = $fields_text_color;
			$udb_login_options['fields_text_color_focus'] = $fields_text_color;
		}

		$fields_font_size = isset( $erident_options['dashboard_input_text_size'] ) && ! empty( $erident_options['dashboard_input_text_size'] ) ? $erident_options['dashboard_input_text_size'] : '';

		if ( $fields_font_size ) {
			$fields_font_size = trim( $fields_font_size ) . 'px';

			$udb_login_options['fields_font_size'] = $fields_font_size;
		}

		$button_bg_color       = isset( $erident_options['dashboard_button_color'] ) && ! empty( $erident_options['dashboard_button_color'] ) ? $erident_options['dashboard_button_color'] : '';
		$button_bg_color_hover = '';

		if ( $button_bg_color ) {
			$button_bg_color_hover = \ariColor::newColor( $button_bg_color );
			$button_bg_color_hover = $button_bg_color_hover->getNew( 'alpha', 0.9 )->toCSS( 'rgba' );

			$udb_login_options['button_bg_color']       = $button_bg_color;
			$udb_login_options['button_bg_color_hover'] = $button_bg_color_hover;
		}

		$button_text_color = isset( $erident_options['dashboard_button_text_color'] ) && ! empty( $erident_options['dashboard_button_text_color'] ) ? $erident_options['dashboard_button_text_color'] : '';

		if ( $button_text_color ) {
			$udb_login_options['button_text_color']       = $button_text_color;
			$udb_login_options['button_text_color_hover'] = $button_text_color;
		}

		$footer_link_color = isset( $erident_options['dashboard_link_color'] ) && ! empty( $erident_options['dashboard_link_color'] ) ? $erident_options['dashboard_link_color'] : '';

		if ( $footer_link_color ) {
			$udb_login_options['footer_link_color']       = $footer_link_color;
			$udb_login_options['footer_link_color_hover'] = $footer_link_color;
		}

		$enable_link_shadow = isset( $erident_options['dashboard_check_shadow'] ) ? $erident_options['dashboard_check_shadow'] : 0;
		$enable_link_shadow = 'yes' === strtolower( $enable_link_shadow ) ? 1 : $enable_link_shadow;
		$enable_link_shadow = 'no' === strtolower( $enable_link_shadow ) ? 0 : $enable_link_shadow;
		$enable_link_shadow = absint( $enable_link_shadow );

		if ( $enable_link_shadow ) {
			// Enable link shadow doesn't exist in UDB login customizer, but let's keep this.
			$udb_login_options['enable_link_shadow'] = 1;
		}

		$link_shadow_color = isset( $erident_options['dashboard_link_shadow'] ) && ! empty( $erident_options['dashboard_link_shadow'] ) ? $erident_options['dashboard_link_shadow'] : '';

		if ( $link_shadow_color ) {
			// Link shadow color doesn't exist in UDB login customizer, but let's keep this.
			$udb_login_options['link_shadow_color'] = $link_shadow_color;
		}

		$remove_register_link = isset( $erident_options['dashboard_check_lost_pass'] ) ? $erident_options['dashboard_check_lost_pass'] : 0;
		$remove_register_link = 'yes' === strtolower( $remove_register_link ) ? 1 : $remove_register_link;
		$remove_register_link = 'no' === strtolower( $remove_register_link ) ? 0 : $remove_register_link;
		$remove_register_link = absint( $remove_register_link );

		if ( $remove_register_link ) {
			$udb_login_options['remove_register_lost_pw_link'] = 1;
		}

		$remove_back_to_blog_link = isset( $erident_options['dashboard_check_backtoblog'] ) ? $erident_options['dashboard_check_backtoblog'] : 0;
		$remove_back_to_blog_link = 'yes' === strtolower( $remove_back_to_blog_link ) ? 1 : $remove_back_to_blog_link;
		$remove_back_to_blog_link = 'no' === strtolower( $remove_back_to_blog_link ) ? 0 : $remove_back_to_blog_link;
		$remove_back_to_blog_link = absint( $remove_back_to_blog_link );

		if ( $remove_back_to_blog_link ) {
			$udb_login_options['remove_back_to_site_link'] = 1;
		}

		update_option( 'udb_login', $udb_login_options );

	}

}
