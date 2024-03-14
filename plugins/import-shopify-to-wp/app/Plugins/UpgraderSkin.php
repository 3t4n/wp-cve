<?php

namespace S2WPImporter\Plugins;

/**
 * Class UpgraderSkin
 *
 * @package S2WPImporter\Plugins
 */
class UpgraderSkin extends \WP_Upgrader_Skin {

	/**
	 * Constructor
	 *
	 * @param array $args
	 */
	function __construct( $args = [] ) {
		$defaults = [ 'type' => 'web', 'url' => '', 'plugin' => '', 'nonce' => '', 'title' => '' ];
		$args     = wp_parse_args( $args, $defaults );

		$this->type = $args['type'];
		$this->api  = isset( $args['api'] ) ? $args['api'] : [];

		parent::__construct( $args );
	}

	public function request_filesystem_credentials( $error = false, $context = false, $allow_relaxed_file_ownership = false ) {
		return true;
	}

	public function error( $errors ) {
		die( '-1' );
	}

	public function header() {
	}

	public function footer() {
	}

	public function feedback( $string, ...$args ) {
	}
}
