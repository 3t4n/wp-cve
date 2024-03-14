<?php

namespace WP_VGWORT;

/**
 * Settings Page View Class
 *
 * holds all things necessary to set up the settings page template
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page_Settings extends Page {
	/**
	 * holds an instance of the class for importing csv files
	 *
	 * @var object | null
	 */
	private object|null $csv;

	/**
	 * Constructor
	 */
	public function __construct( object $plugin ) {
		parent::__construct( $plugin );
		$this->add_hooks();

		// instantiate csv class and activate csv import functionality
		$this->csv = new CSV( $this->plugin );
		$this->csv->activate_csv();
	}

	/**
	 * Register all the settings we have and set their defaults, called by action hook
	 *
	 * @return void
	 */
	public function register_plugin_settings(): void {
		register_setting( 'wp_metis_settings', 'wp_metis_api_key',
			[ 'type' => 'string', 'default' => '', 'sanitize_callback' => [ $this, 'sanitize_api_key' ] ] );

		register_setting( 'wp_metis_settings', 'wp_metis_pixel_auto_add_pages',
			[ 'type' => 'string', 'default' => Common::AUTO_ADD_PAGES_DEFAULT ] );

		register_setting( 'wp_metis_settings', 'wp_metis_pixel_auto_add_posts',
			[ 'type' => 'string', 'default' => Common::AUTO_ADD_POSTS_DEFAULT ] );

	}

	/**
	 * Setup the form output, called by action hook
	 *
	 * @return void
	 */
	public function build_settings_form(): void {
		add_settings_section( 'general', esc_html__( 'Einstellungen', 'vgw-metis' ), [
			$this,
			'cb_section_general'
		], 'wp_metis_settings' );
		add_settings_field( 'wp_metis_api_key', esc_html__( 'API Key', 'vgw-metis' ), [
			$this,
			'render_api_key_field'
		], 'wp_metis_settings', 'general' );
		add_settings_field( 'wp_metis_pixel_auto_add_posts', esc_html__( 'Auto-Zählmarke Beiträge', 'vgw-metis' ), [
			$this,
			'render_auto_add_posts_field'
		], 'wp_metis_settings', 'general' );
		add_settings_field( 'wp_metis_pixel_auto_add_pages', esc_html__( 'Auto-Zählmarke Seiten', 'vgw-metis' ), [
			$this,
			'render_auto_add_pages_field'
		], 'wp_metis_settings', 'general' );

	}

	/**
	 * Validate the api key input
	 *
	 * @param string $input api key string to sanitize
	 *
	 * @return mixed
	 */
	public function sanitize_api_key( string $input ): string {
		if ( ! $input ) {
			return '';
		}

		return sanitize_key( $input );
	}

	/**
	 * Display stuff between Section Heading and Form Elements
	 *
	 * @return void
	 */
	public function cb_section_general(): void {
	}

	/**
	 * Render the input field for the API Key Setting
	 *
	 * @return void
	 */
	public function render_api_key_field(): void {
		$key = esc_attr( get_option( 'wp_metis_api_key', '' ) );
		?>
        <textarea id='wp_metis_api_key_field' name='wp_metis_api_key' cols="40"
                  rows="3"><?php echo esc_textarea( $key ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Der API-Key legt die Zugangsdaten für den Benutzer zum T.O.M.-Webservice fest. Dieser ist erforderlich, um Zählmarken zu bestellen und Meldungen zu erstellen. Der API-Key kann im Portal T.O.M. generiert werden.', 'vgw-metis' ); ?></p>
		<?php
	}

	/**
	 * Render radio options for the 'auto add pixels to pages' setting
	 *
	 * @return void
	 */
	public function render_auto_add_pages_field(): void {
		$value = get_option( 'wp_metis_pixel_auto_add_pages', 'yes' );
		?>
        <input type="radio" id="wp_metis_pixel_auto_add_pages_field_yes" name="wp_metis_pixel_auto_add_pages"
               value="yes" <?php checked( 'yes', $value ); ?>>
        <label for="wp_metis_pixel_auto_add_pages_field_yes"><?php esc_html_e( 'Ja', 'vgw-metis' ); ?></label>
        <input type="radio" id="wp_metis_pixel_auto_add_pages_field_no" name="wp_metis_pixel_auto_add_pages"
               value="no" <?php checked( 'no', $value ); ?>>
        <label for="wp_metis_pixel_auto_add_pages_field_no"> <?php esc_html_e( 'Nein', 'vgw-metis' ); ?></label>
        <p class="description"><?php esc_html_e( 'Standard-Einstellung für die automatische Zuweisung von Zählmarken bei der Erstellung von neuen Seiten.', 'vgw-metis' ); ?></p>
		<?php
	}

	/**
	 * Render radio options for the 'auto add pixels to posts' setting
	 *
	 * @return void
	 */
	public function render_auto_add_posts_field(): void {
		$value = get_option( 'wp_metis_pixel_auto_add_posts', 'yes' );
		?>
        <input type="radio" id="wp_metis_pixel_auto_add_posts_field_yes" name="wp_metis_pixel_auto_add_posts"
               value="yes" <?php checked( 'yes', $value ); ?>>
        <label for="wp_metis_pixel_auto_add_posts_field_yes"> <?php esc_html_e( 'Ja', 'vgw-metis' ); ?></label>
        <input type="radio" id="wp_metis_pixel_auto_add_posts_field_no" name="wp_metis_pixel_auto_add_posts"
               value="no" <?php checked( 'no', $value ); ?>>
        <label for="wp_metis_pixel_auto_add_posts_field_no"> <?php esc_html_e( 'Nein', 'vgw-metis' ); ?></label>
        <p class="description"><?php esc_html_e( 'Standard-Einstellung für die automatische Zuweisung von Zählmarken bei der Erstellung von neuen Beiträgen.', 'vgw-metis' ); ?></p>
		<?php
	}

	/**
	 * add the submenu for the settings page
	 *
	 * @return void
	 */
	public function add_settings_submenu() {
		add_submenu_page( 'metis-dashboard', esc_html__( 'VG WORT METIS Einstellungen', 'vgw-metis' ), esc_html__( 'Einstellungen', 'vgw-metis' ), 'manage_options', 'metis-settings', array(
			$this,
			'render'
		), 2 );

	}

	/**
	 * Adds all hooks for the settings class
	 *
	 * @return void
	 */
	private function add_hooks(): void {
		// register menu
        add_action( 'admin_menu', [$this, 'add_settings_submenu'] );
		// register the plugin settings action
		add_action( 'admin_init', [ $this, 'register_plugin_settings' ] );
		// register the build settings form action
		add_action( 'admin_init', [ $this, 'build_settings_form' ] );
		// register custom plugin action for validating api key
		add_action( 'admin_post_wp_metis_check_api_key', [ $this, 'check_api_key' ] );
		// register custom plugin action for ordering new pixels
		add_action( 'admin_post_wp_metis_order_pixels', [ $this, 'order_pixels' ] );
		// register custom plugin action for checking all existing pixels
		add_action( 'admin_post_wp_metis_check_pixels', [ $this, 'check_pixels' ] );
		// register custom plugin action for scanning the posts contents for pixel
		add_action( 'admin_post_wp_metis_scan_pixels', [ $this, 'scan_pixels' ] );
		// display a notice when there is no api key saved in settings
		if ( ! $this->has_valid_api_key() ) {
			add_action( 'admin_notices', [ $this, 'display_api_key_missing_notice' ] );
		}
	}

	/**
	 * Loads the settings page view partial template > render the settings!
	 *
	 * @return void
	 */
	public function render(): void {
		$this->plugin->notifications->display_notices();
		require_once 'partials/settings.php';
	}

	/**
	 * Calls API to validate the key and redirect with success / error message
	 *
	 * @return void
	 */
	public function check_api_key(): void {
		if ( Services::health_check() ) {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=validate_api_key_success' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=invalid_api_key_error' ) );
		}
		exit;
	}

	/**
	 * Calls API to order pixels and redirecit with success / error message
	 *
	 * @return void
	 */
	public function order_pixels(): void {
		$api_pixels = Services::order_pixels();

		$insert_pixels = Pixel::batch_transform_api_to_db_pixel( $api_pixels, Common::SOURCE_RESTAPI );

		if ( $insert_pixels && DB_Pixels::insert_pixels( $insert_pixels ) ) {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=order_pixels_success' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=order_pixels_error' ) );
		}
		exit;
	}

	/**
	 * Call API to update status of pixels and redirect with success / error message
	 *
	 * @return void
	 */
	public function check_pixels(): void {
		if ( Services::check_all_pixels() ) {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=check_pixels_success' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=check_pixels_error' ) );
		}
		exit;
	}

	/**
	 * scan the site for existing pixels and redirect with success / error message
	 *
	 * @return void
	 */
	public function scan_pixels(): void {
		$response = Services::scan_posts_for_pixels();
		if ( $response ) {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=scan_pixels_success&&custom_text=' . $response ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=metis-settings&notice=scan_pixels_error' ) );
		}
		exit;
	}

	/**
	 * Checks if there is a value stored in the API key option with the right format (doesn't necessarily mean the key
	 * works)
	 *
	 * @return bool yes or no
	 */
	public function has_valid_api_key(): bool {
		$api_key = get_option( 'wp_metis_api_key' );

		return ( $api_key !== false && strlen( $api_key ) >= 36 );
	}

	/**
	 * display admin warning notice: api key missing
	 *
	 * @return void
	 */
	public function display_api_key_missing_notice(): void {
		?>
        <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e( 'VG WORT METIS: Fehlender oder unvollständiger API-Key. Bitte geben Sie einen gültigen API-Key in den Einstellungen an!', 'vgw-metis' ); ?></p>
        </div>
		<?php
	}

	/**
	 * register the plugin settings (called in plugin bootstrap)
	 *
	 * @param Notifications $notifications
	 *
	 * @return void
	 */
	public static function register_notifications(Notifications &$notifications): void {
		$notifications->add_notice_by_key( 'no_api_key_error', esc_html__( 'Fehlender oder unvollständiger API Key. Geben Sie einen korrekten API Key ein!', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'invalid_api_key_error', esc_html__( 'Verbindungstest zu API fehlgeschlagen. Bitte überprüfen Sie Ihren API-Key!', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'order_pixels_error', esc_html__( 'Fehler bei der Bestellung neuer Zählmarken.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'check_pixels_error', esc_html__( 'Fehler bei der Überprüfung bestehender Zählmarken.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'validate_api_key_success', esc_html__( 'API-Key funktioniert ordnungsgemäß!', 'vgw-metis' ), 'success' );
		$notifications->add_notice_by_key( 'order_pixels_success', esc_html__( 'Neue Zählmarken wurden erfolgreich bestellt.', 'vgw-metis' ), 'success' );
		$notifications->add_notice_by_key( 'check_pixels_success', esc_html__( 'Zählmarkenüberprüfung wurde erfolgreich durchgeführt!', 'vgw-metis' ), 'success' );
		$notifications->add_notice_by_key( 'scan_pixels_success', esc_html__( 'Scan von Zählmarken wurde erfolgreich durchgeführt!', 'vgw-metis' ), 'success' );
		$notifications->add_notice_by_key( 'scan_pixels_error', esc_html__( 'Fehler beim Scan von Zählmarken.', 'vgw-metis' ) );
    }
}
