<?php

namespace LIBRARY;

if ( ! class_exists( '\Plugin_Upgrader', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

class PluginInstallerSkinSilent extends \WP_Upgrader_Skin {

	public function header() {}
	public function footer() {}
	public function feedback( $string, ...$args ) {}
	public function decrement_update_count( $type ) {}
	public function error( $errors ) {}
}

