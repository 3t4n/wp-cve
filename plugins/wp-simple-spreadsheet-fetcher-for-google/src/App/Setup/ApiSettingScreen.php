<?php

namespace Fetcher\App\Setup;
require(PLUGIN_ROOT_DIR .'vendor/autoload.php');
use Fetcher\App\Utils\ApiManipulation;

class ApiSettingScreen extends ApiManipulation {

	public function init() {
		add_action( 'admin_menu', array( $this, 'add_sub_menu' ) );
		add_action( 'admin_enqueue_scripts', array($this,'add_admin_scripts') );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_settings' ) );
	}

	public function render_set_api_key() {
		$message = '<span class="success" style="color:#28a745; font-size:1.2rem">' . __( "API Key set!", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</span >';

		return $this->render_settings_page( $message );
	}

	public function render_api_key_not_set() {
		$message = '<strong class="warn" style="color:#dc3545; font-size:1.2rem">' . __( "You have not entered your API key", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</strong >';

		return $this->render_settings_page( $message );
	}

	public function render_settings_page( $message ) {

		if ( $this->get_api_key() ) {
			$api_key         = $this->get_api_key();
		} else {
			$api_key         = '';
		}

		$html = '<div class="api-key" >';
		$html .= '<h2>' . __( "Setting API key", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</h2>';
		$html .= $message;
		$html .= '<br>';
		$html .= '<br>';
		$html .= '<form id="wp2s2fg_api_spreadsheetId_form" action="' . htmlspecialchars( $_SERVER["PHP_SELF"] . '?' . $_SERVER["QUERY_STRING"] ) . '" method="POST" >';
		$html .= '<div class="wp2s2fg_api_spreadsheetId_form_label">' . __( "API Key : ", 'wp-simple-spreadsheet-fetcher-for-google' ) .'</div><input type="text" name="api_key" placeholder="API-Key" value="' . esc_html( $api_key ) . '" required />';
		$html .= '<br>';
		$html .= '<input type="submit" value="' . __( "Set Configuration Info", 'wp-simple-spreadsheet-fetcher-for-google' ) .'" />';
		$html .= wp_nonce_field( wp_create_nonce( __FILE__ ), 'wp-simple-spreadsheet-fetcher-for-google-nonce' );
		$html .= '</form >';
		$html .= '<br>';
		$html .= '<h2>' . __( "How to use", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</h2>';
		$html .= '<ul>';
		$html .= '<li>' . __( "1. Create the API key . For more detail . Please refer to ", 'wp-simple-spreadsheet-fetcher-for-google' ) . '<a href="https://developers.google.com/sheets/api/quickstart/js#step_1_turn_on_the" target="_blank">' . __( "https://developers.google.com/sheets/api/quickstart/js#step_1_turn_on_the", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</a></li>';
		$html .= '<li>' . __( "2. Save your API key from the form above.", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</li>';
		$html .= '<li>' . __( "3. Turn on Get shareable link . For more detail . Please refer to ", 'wp-simple-spreadsheet-fetcher-for-google' ) . '<a href="https://support.google.com/drive/answer/2494822#link_sharing" target="_blank">' . __( "https://support.google.com/drive/answer/2494822#link_sharing", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</a></li>';
		$html .= '<li>' . __( "4. Choose blocks at \"WP Simple Spreadsheet Fetcher for Google\" category , use side panel to indicate the cell to fetch data.", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</li>';
		$html .= '</ul>';
		$html .= '<h2>' . __( "Tutorial Video", 'wp-simple-spreadsheet-fetcher-for-google' ) . '</h2>';
		$html .= '<iframe width="560" height="315" src="https://www.youtube.com/embed/VYMFFMyRK3I" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		$html .= '</div>';
		return $html;
	}

	public function add_sub_menu() {
		$custom_page = add_submenu_page(
			'/plugins.php',
			__( 'WP Simple Spreadsheet Fetcher for Google', 'wp-simple-spreadsheet-fetcher-for-google' ),
			__( 'WP Simple Spreadsheet Fetcher for Google', 'wp-simple-spreadsheet-fetcher-for-google' ),
			'edit_others_posts',
			'wsgsf_settings',
			array( $this, 'render_settings' )
		);
	}

	public function render_settings() {

		if ( ! empty( $_POST['api_key'] ) && check_admin_referer( wp_create_nonce( __FILE__ ), 'wp-simple-spreadsheet-fetcher-for-google-nonce' ) ) {
			$this->set_api_key( sanitize_text_field( $_POST['api_key'] ) );
		}

		if ( ! $this->get_api_key()) {
			echo $this->render_api_key_not_set();
		}else{
			echo $this->render_set_api_key();
		}
	}

	public function add_admin_scripts($hook_suffix) {

		if ( 'plugins_page_wsgsf_settings' === $hook_suffix ) {
			wp_enqueue_style( 'admin_style',  plugins_url( '/css/admin.css',__FILE__ )  );
		}
	}

	public function add_settings( $links ) {
		$url = admin_url( 'admin.php?page=wsgsf_settings' );
		$url = '<a href="' . esc_url( $url ) . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $url );
		return $links;
	}

	public function deactivation() {
		$this->delete_api_key();
		$this->delete_spread_sheet_id();
	}
}
