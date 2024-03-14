<?php

class LWP_DiviFlipbox extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 0.9.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'lwp-divi-flipbox';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 0.9.0
	 *
	 * @var string
	 */
	public $name = 'divi-flipbox';

	/**
	 * The extension's version
	 *
	 * @since 0.9.0
	 *
	 * @var string
	 */
	public $version = '0.9.4';

	/**
	 * LWP_DiviFlipbox constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'divi-flipbox', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}
}

new LWP_DiviFlipbox;
