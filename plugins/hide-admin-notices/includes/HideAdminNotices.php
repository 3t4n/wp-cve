<?php

/**
 * @link       https://pontetlabs.com
 * @since      1.0.0
 *
 * @package    Hide_Admin_Notices
 * @subpackage Hide_Admin_Notices/includes
 */

namespace Pontet_Labs\Hide_Admin_Notices;

/**
 * @since      1.0.0
 * @package    Hide_Admin_Notices
 * @subpackage Hide_Admin_Notices/includes
 * @author     PontetLabs <hi@pontetlabs.com>
 */
class Hide_Admin_Notices {

	static $instance = null;

	const OPTIONS_NAME = 'hide-admin-notices-options';

	protected Context $context;
	protected Options $options;
	protected array $config = array(
		'compatibility_requests' => array(),
	);

	/**
	 * Initialize the class.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->context = new Context( $this->config );
		$this->context->init();
	}

	public function init() {
		$this->init_classes();
		$this->register_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function init_classes(): void {
		$this->options = new Options( $this->context );
		$this->options->init();
		$admin = new Admin( $this->options );
		$admin->init();
	}

	/**
	 * Register all the hooks related to the admin area.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_hooks(): void {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain(): void {
		load_plugin_textdomain(
			'hide-admin-notices',
			false,
			HIDE_ADMIN_NOTICES_DIR . 'languages/'
		);
	}
}
