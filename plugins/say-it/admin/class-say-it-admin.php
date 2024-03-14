<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Say_It
 * @subpackage Say_It/admin
 * @author     David Manson <david.manson@me.com>
 */
class Say_It_Admin {


	private $plugin_name;
	private $version;
	private $options;


	public function __construct( $plugin_name, $version, $folder, $options ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->folder = $folder;
		$this->options = $options;

		$this->google_tts = new Say_It_Google_TTS( $this->plugin_name, $this->options );
		$this->amazon_polly = new Say_It_Amazon_Polly( $this->plugin_name, $this->options );
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/say-it-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('wp-codemirror');
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'application/json'));
		wp_localize_script('jquery', 'cm_settings', $cm_settings);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/say-it-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu() {
		add_submenu_page( 'options-general.php', 'Say It! Options', 'Say It!', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	/**
	 * Add settings action link to the plugins page.
	 */
	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
		);
		return array_merge(  $settings_link, $links );
	}

	/**
	 * Ajax route for getting (gutenberg)
	 * @since    1.0.0
	 */
	function sayit_get_mp3_ajax() {
		$words = $_POST['words'];
		$response = [
			'mp3' => $this->amazon_polly->get_mp3($words)
		];
		wp_send_json_success($response);
		die(); 
	}
	
	/**
	 * Render the settings page for this plugin.
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );
	}

	/**
	 * Helper to render template parts of the settings page
	 */
	public function display_template_part($name) {
		include_once( 'partials/' . $this->plugin_name . '-admin-display-'.$name.'.php' );
	}
	

	/**
	 * Validate fields from admin area plugin settings form
	 * @param  mixed $input as field form settings form
	 * @return mixed as validated fields
	 */
	public function validate($input) {
		$valid = array();
		$valid['mode'] = ( isset($input['mode'] ) && ! empty( $input['mode'] ) ) ? esc_attr($input['mode']) : 'html5';
		$valid['default_language'] = ( isset($input['default_language'] ) && ! empty( $input['default_language'] ) ) ? esc_attr($input['default_language']) : 'en-US';
		$valid['default_speed'] = ( isset( $input['default_speed'] ) && ! empty( $input['default_speed'] ) ) ? esc_attr($input['default_speed']) : '1';
		$valid['google_tts_key'] = ( isset($input['google_tts_key'] ) && ! empty( $input['google_tts_key'] ) ) ? esc_attr($input['google_tts_key']) : null;
		$valid['google_language'] = ( isset($input['google_language'] ) && ! empty( $input['google_language'] ) ) ? esc_attr($input['google_language']) : 'en-US';
		$valid['google_gender'] = ( isset($input['google_gender'] ) && ! empty( $input['google_gender'] ) ) ? esc_attr($input['google_gender']) : 'male';
		$valid['google_speed'] = ( isset( $input['google_speed'] ) && ! empty( $input['google_speed'] ) ) ? esc_attr( $input['google_speed'] ) : '1';
		$valid['google_custom_voice'] = ( isset( $input['google_custom_voice'] ) && ! empty( $input['google_custom_voice'] ) ) ? esc_attr( $input['google_custom_voice'] ) : null;
		$valid['amazon_polly_region'] = ( isset( $input['amazon_polly_region'] ) && ! empty( $input['amazon_polly_region'] ) ) ? esc_attr( $input['amazon_polly_region'] ) : null;
		$valid['amazon_polly_key'] = ( isset( $input['amazon_polly_key'] ) && ! empty( $input['amazon_polly_key'] ) ) ? esc_attr( $input['amazon_polly_key'] ) : null;
		$valid['amazon_polly_secret'] = ( isset( $input['amazon_polly_secret'] ) && ! empty( $input['amazon_polly_secret'] ) ) ? esc_attr( $input['amazon_polly_secret'] ) : null;
		$valid['amazon_voice'] = ( isset( $input['amazon_voice'] ) && ! empty( $input['amazon_voice'] ) ) ? esc_attr( $input['amazon_voice'] ) : 'Kimberly';
		$valid['tooltip_text'] = ( isset( $input['tooltip_text'] ) && ! empty( $input['tooltip_text'] ) ) ? esc_attr( $input['tooltip_text'] ) : 'Listen';

		$valid['skin'] = ( isset( $input['skin'] ) && ! empty( $input['skin'] ) ) ? esc_attr( $input['skin'] ) : null;

		return $valid;
	}
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}

}