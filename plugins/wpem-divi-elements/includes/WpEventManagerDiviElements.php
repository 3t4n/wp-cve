<?php

class WPEM_WpEventManagerDiviElements extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'wpem-wp-event-manager-divi-elements';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'wp-event-manager-divi-elements';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.1';

	/**
	 * WPEM_WpEventManagerDiviElements constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'wp-event-manager-divi-elements', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}
}

new WPEM_WpEventManagerDiviElements;
