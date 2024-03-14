<?php

/**
 * @author            Dhanendran (https://dhanendranrajagopal.me/)
 * @link              https://dhanendranrajagopal.me/
 * @since             0.1.0
 * @package           qr-code-creator
 *
 * @wordpress-plugin
 * Plugin Name:       QR Code Creator
 * Plugin URI:        https://github.com/dhanendran/qr-code-creator
 * Description:       A WordPress plugin which will help you to create QR Codes.
 * Tags:              QR Code, Generator, QR Code Creator, QR Code Generator
 * Version:           0.1.4
 * Author:            Dhanendran
 * Author URI:        http://dhanendranrajagopal.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       qr-code-creator
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class QRCodeCreator {
	/**
	 * Start up
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );

		wp_enqueue_script( 'qr_code_creator_script', plugin_dir_url( __FILE__ ) . 'script.js', array(), '1.0.0', true );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_options_page(
			'QR Code Creator',
			'QR Code Creator',
			'manage_options',
			'qr-code-creator',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		?>
		<div class="qr-code-creator">
			<h1>QR Code Creator</h1>
			<textarea name="qr_code_content" id="qr_code_content" rows="10" cols="70" placeholder="Enter your content here"></textarea>
			<div class="actions">
				<button id="create_qr_code" class="button button-primary">Create</button>
				<button id="reset_qr_code" class="button button-secondary">Reset</button>
			</div>
			<h2>QR Code</h2>
			<img id="qr_code">
			<h4>Download: </h4>
			<div id="qr_code_download_link"></div>
		</div>
		<?php
	}
}

if ( is_admin() ) {
	$qrCodeCreator = new QRCodeCreator();
	$qrCodeCreator->init();
}


