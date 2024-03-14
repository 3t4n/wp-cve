<?php

class LWP_LwpImageCarousel extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 0.9.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'lwp-image-carousel';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 0.9.0
	 *
	 * @var string
	 */
	public $name = 'lwp-image-carousel';

	/**
	 * The extension's version
	 *
	 * @since 0.9.0
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * LWP_LwpImageCarousel constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'lwp-image-carousel', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}
}

new LWP_LwpImageCarousel;
