<?php

namespace WpifyWoo;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;

/**
 * Class Admin
 * @package WpifyWoo
 * @property Plugin $plugin
 */
class Admin extends AbstractComponent {
	public function setup() {
		add_filter( 'plugin_action_links_wpify-woo/wpify-woo.php', [ $this, 'add_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_row_meta_links' ], 10, 4 );
		add_action( 'admin_init', [ $this, 'maybe_download_log' ] );
	}

	public function add_action_links( $links ) {
		$before = [
			'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=wpify-woo-settings' ), __( 'Settings', 'wpify-woo' ) ),
		];

		$after = [
			'wpify' => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://wpify.io', __( 'Get more plugins and support', 'wpify-woo' ) ),
		];

		return array_merge( $before, $links, $after );
	}

	public function add_row_meta_links( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		$new_links = [];

		if ( strpos( $plugin_file, 'wpify-woo.php' ) ) {
			$new_links = [
				'wpify-doc' => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://wpify.io/cs/knowledge-base/wpify-woo/', __( 'Documentation', 'wpify-woo' ) ),
			];
		}

		return array_merge( $plugin_meta, $new_links );
	}

	public function maybe_download_log() {
		global $wpdb;
		if ( ! isset( $_GET['wpify-action'] ) || $_GET['wpify-action'] !== 'download-log' ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['wpify-nonce'], 'download-log' ) ) {
			wp_die( __( 'Invalid nonce.', 'wpify-woo' ) );
		}

		if ( ! current_user_can( 'administrator' ) ) {
			wp_die( __( 'Only user with administrator role can export logs.', 'wpify-woo' ) );
		}


		$table = $wpdb->prefix . $this->plugin->get_logger()->table();
		$data  = $wpdb->get_results( "SELECT * from {$table} ORDER BY id DESC LIMIT 500", ARRAY_A );
		$this->array_to_csv_download( $data );
		exit();
	}

	private function array_to_csv_download( $array, $filename = "wpify-log.csv", $delimiter = ";" ) {
		// open raw memory as file so no temp files needed, you might run out of memory though
		$f = fopen( 'php://memory', 'w' );
		// loop over the input array
		foreach ( $array as $line ) {
			// generate csv lines from the inner arrays
			fputcsv( $f, $line, $delimiter );
		}
		// reset the file pointer to the start of the file
		fseek( $f, 0 );
		// tell the browser it's going to be a csv file
		header( 'Content-Type: application/csv' );
		// tell the browser we want to save it instead of displaying it
		header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
		// make php send the generated csv lines to the browser
		fpassthru( $f );
	}
}
