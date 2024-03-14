<?php
/**
* Plugin Name: Askly
* Plugin URI: http://www.askly.me/
* Version: 1.1.0
* Author: Askly
* Author URI: http://www.askly.me/
* Description: Allows you to insert Askly chat code to enable chat for your website
* License: GPL2
*/

class TranslatewiseChat {
	public function __construct() {
		$file_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
 
		$this->plugin                           = new stdClass;
		$this->plugin->name                     = 'translatewise-chat';
		$this->plugin->displayName              = 'Askly chat';
		$this->plugin->version                  = $file_data['Version'];
		$this->plugin->folder                   = plugin_dir_path( __FILE__ );

		// Hooks
		add_action( 'admin_init', array( &$this, 'adminInit' ) );
		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );

		// Add chat script 
		add_action('wp_enqueue_scripts', array($this, 'frontEnqueueScripts'));

		// Add admin page css
		add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
	}
 
    public function adminEnqueueScripts() { 
        wp_enqueue_style('tw-chat-settings-style', plugins_url('media/css/settings.css', __FILE__), array(), $this->plugin->version);
    }

	function adminInit() {
		register_setting( $this->plugin->name, 'tw-client-key', 'trim' ); 
		register_setting( $this->plugin->name, 'tw-chat-enabled', 'trim' ); 
	}

	function adminMenu() {
		add_submenu_page( 'options-general.php', $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array( &$this, 'chatSettingsPanel' ) );
	}

	function chatSettingsPanel() { 
		if ( ! current_user_can( 'manage_options' ) ) { 
			wp_die('Access denied');
		}

		if ( ! current_user_can( 'unfiltered_html' ) ) { 
			$this->errorMessage =  'Access denied';
		}

		// If POST save script
		if ( $_SERVER['REQUEST_METHOD'] === 'POST') {
			// Check permissions and nonce.
			if ( ! current_user_can( 'unfiltered_html' ) ) {
				wp_die('Access denied');
			} elseif ( ! isset( $_REQUEST[ $this->plugin->name . '_nonce' ] ) ) {
				$this->errorMessage = 'Something went wrong';
			} elseif ( ! wp_verify_nonce( $_REQUEST[ $this->plugin->name . '_nonce' ], $this->plugin->name ) ) {
				$this->errorMessage = 'Something went wrong';
			} else {
				update_option( 'tw-client-key', sanitize_text_field($_REQUEST['tw-client-key']) );
				update_option( 'tw-chat-enabled', (isset($_REQUEST['tw-chat-enabled']) ? "1" : "0") );
				$this->message = 'Settings saved'; 
			}
		} 

		$this->settings = array(
			'tw-client-key' => esc_html( wp_unslash( get_option( 'tw-client-key' ) ) ), 
			'tw-chat-enabled' => esc_html( wp_unslash( get_option( 'tw-chat-enabled', '1' ) ) ), 
		);

		include_once( $this->plugin->folder . '/views/settings.php' );
	}  
  
	function frontEnqueueScripts() {
		$isEnabled = get_option( 'tw-chat-enabled' );
		if ( empty( $isEnabled ) ||  trim( $isEnabled ) === '0' ) {
			return;
		}

		// Get script
		$clientKey = get_option( 'tw-client-key' );
		if ( empty( $clientKey ) || trim( $clientKey ) === '' ) {
			return;
		}

		wp_print_script_tag(
			array(
				'src' => 'https://chat.askly.me/cw/chat/latest.js',
				'async' => true,
				'tw-client-key' => $clientKey,
			)
		);
	}
}

$twChat = new TranslatewiseChat();
