<?php

namespace LIBRARY;

if ( ! class_exists( '\Plugin_Upgrader', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

class PluginInstallerSkin extends \WP_Upgrader_Skin {

	public function header() {}
	public function footer() {}
	public function feedback( $string, ...$args ) {}
	public function decrement_update_count( $type ) {}

	public function error( $errors ) {
		if ( empty( $errors ) ) {
			return;
		}

		if ( is_string( $errors ) ) {
			wp_send_json_error( $errors );
		} elseif ( is_wp_error( $errors ) && $errors->has_errors() ) {
			if ( $errors->get_error_data() && is_string( $errors->get_error_data() ) ) {
				wp_send_json_error( $message . ' ' . esc_html( strip_tags( $errors->get_error_data() ) ) );
			} else {
				wp_send_json_error( $message );
			}
		}
	}
}

