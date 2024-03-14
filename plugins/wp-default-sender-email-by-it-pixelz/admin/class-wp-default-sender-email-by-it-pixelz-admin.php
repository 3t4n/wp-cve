<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks
 *
 * @package    Wp_Default_Sender_Email_By_It_Pixelz
 * @subpackage Wp_Default_Sender_Email_By_It_Pixelz/admin
 * @author     Umar Draz <umar.draz001@gmail.com>
 */
class Wp_Default_Sender_Email_By_It_Pixelz_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Options settings key of this plugin.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @var      string $options_settings_key options key
	 */
	public $options_settings_key = WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_OPTIONS_KEY;

	/**
	 * Options settings.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @var      string $options_settings options
	 */
	public $options_settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    2.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		$this->options_settings = get_option( $this->options_settings_key );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', [], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {


	}

	/**
	 * Add Settings link on plugins page
	 *
	 * @since    2.0.0
	 */
	function add_settings_links( $links ) {
		$mylinks = [
			'<a href="' . admin_url( 'options-general.php?page=wp-default-sender-email-itpixelz' ) . '">Settings</a>',
		];

		return array_merge( $links, $mylinks );
	}

	/**
	 * Redirect on activation
	 *
	 * @since    2.0.0
	 */
	function admin_redirect() {
		if ( get_option( 'wdsei_activation_redirect', false ) ) {
			delete_option( 'wdsei_activation_redirect' );
			wp_redirect( admin_url( 'options-general.php?page=wp-default-sender-email-itpixelz' ) );
		}
	}

	/**
	 * add admin menu
	 *
	 * @since    2.0.0
	 */
	function admin_menu() {
		add_options_page(
			__( 'Wp Default Sender Email by IT Pixelz', 'wp-default-sender-email-by-it-pixelz' ),
			__( 'WP Default Mail', 'wp-default-sender-email-by-it-pixelz' ),
			'manage_options',
			'wp-default-sender-email-itpixelz',
			[ $this, 'settings_page' ]
		);
	}


	/**
	 * settings page callback
	 *
	 * @since    2.0.0
	 */
	function settings_page() {
		?>
        <div class="wrap" id="wdsei_settings_wrapper">
            <h1><?php _e( 'WP Default Sender Email Address Settings', 'wp-default-sender-email-by-it-pixelz' ) ?></h1>
            <form method="post" action="options.php">
				<?php
				settings_fields( 'wdsei_itpixez_settings_options' );
				do_settings_sections( $this->options_settings_key );
				submit_button();
				?>
            </form>
            <p>
                <strong><?php _e( 'Note:', 'wp-default-sender-email-by-it-pixelz' ); ?></strong>
				<?php _e( 'If you are going to use email account other than the domain on your hosting, then you may have to confirm if your current web hosting is offering so.', 'wp-default-sender-email-by-it-pixelz' ); ?>
            </p>
        </div>
		<?php
	}

	/**
	 * settings page callback
	 *
	 * @since    2.0.0
	 */
	function wp_setting_init() {
		register_setting(
			'wdsei_itpixez_settings_options',
			$this->options_settings_key,
			[ $this, 'sanitize_text_fields' ]
		);

		add_settings_section(
			'wdsei_settings_section',
			__( 'Update Your Default Mail Sender Details', 'wp-default-sender-email-by-it-pixelz' ),
			[ $this, 'default_sender_settings_callback' ],
			$this->options_settings_key
		);

		add_settings_field(
			'sender_name',
			__( 'Sender Name', 'wp-default-sender-email-by-it-pixelz' ),
			[ $this, 'sender_name_callback' ],
			$this->options_settings_key,
			'wdsei_settings_section'
		);

		add_settings_field(
			'sender_mail',
			__( 'Sender Mail ID', 'wp-default-sender-email-by-it-pixelz' ),
			[ $this, 'sender_mail_callback' ],
			$this->options_settings_key,
			'wdsei_settings_section'
		);
	}


	/**
	 * Sender name callback
	 *
	 * @since    2.0.0
	 */
	public function sender_name_callback( $args ) {
		$val = ( isset( $this->options_settings['sender_name'] ) ) ? $this->options_settings['sender_name'] : '';

		echo '<input name="' . $this->options_settings_key . '[sender_name]" id="sender_name" type="text" value="' . esc_attr( $val ) . '" class="regular-text" />
              <p>' . __( 'Sender email name, From name of the email address', 'wp-default-sender-email-by-it-pixelz' ) . '</p>';
	}

	/**
	 * Sender email callback
	 *
	 * @since    2.0.0
	 */
	public function sender_mail_callback( $args ) {

		$val = ( isset( $this->options_settings['sender_mail'] ) ) ? $this->options_settings['sender_mail'] : '';

		echo '<input name="' . $this->options_settings_key . '[sender_mail]" id="sender_mail" type="email" value="' . esc_attr( $val ) . '" class="regular-text" />
              <p>' . __( 'Sender email address, From email ID', 'wp-default-sender-email-by-it-pixelz' ) . '</p>';
	}


	/**
	 * Settings section callback
	 *
	 * @since    2.0.0
	 */
	public function default_sender_settings_callback() {

	}


	/**
	 * sanitize all text fields
	 *
	 * @since    2.0.0
	 */
	public function sanitize_text_fields( $fields ) {
		$valid_fields = [];

		// Validate title field
		$sender_name                 = trim( $fields['sender_name'] );
		$sender_mail                 = trim( $fields['sender_mail'] );
		$valid_fields['sender_name'] = sanitize_text_field( $sender_name );
		$valid_fields['sender_mail'] = sanitize_email( $sender_mail );

		return apply_filters( 'validate_options', $valid_fields, $fields );
	}
}
