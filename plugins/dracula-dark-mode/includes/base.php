<?php


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//final class
final class Dracula {

	/**
	 * The single instance of the class.
	 *
	 * @var Dracula
	 */
	protected static $_instance = null;

	/**
	 * Main Dracula Instance.
	 *
	 * Ensures only one instance of Dracula is loaded or can be loaded.
	 *
	 * @return Dracula - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheating huh?', 'dracula-dark-mode' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheating huh?', 'dracula-dark-mode' ), '1.0.0' );
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();

		register_activation_hook( DRACULA_FILE, array( $this, 'activate' ) );

		do_action( 'dracula_loaded' );
	}

	public function activate() {
		include_once DRACULA_INCLUDES . '/class-install.php';

		Dracula_Install::activate();
	}

	public function includes() {
		include_once DRACULA_INCLUDES . '/functions.php';
		include_once DRACULA_INCLUDES . '/class-enqueue.php';
		include_once DRACULA_INCLUDES . '/class-shortcode.php';
		include_once DRACULA_INCLUDES . '/class-hooks.php';
		include_once DRACULA_INCLUDES . '/class-ajax.php';
		include_once DRACULA_INCLUDES . '/class-toggle-builder.php';
		include_once DRACULA_INCLUDES . '/class-metabox.php';

		// Analytics
		include_once DRACULA_INCLUDES . '/class-analytics.php';

		// blocks
		include_once DRACULA_PATH . '/blocks/class-blocks.php';

		// Elementor
		include_once DRACULA_PATH . '/elementor/class-elementor.php';

		if ( is_admin() ) {
			include_once DRACULA_INCLUDES . '/class-admin.php';
		}

	}


	public function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		//plugin action links
		add_filter( 'plugin_action_links_' . plugin_basename( DRACULA_FILE ), array( $this, 'plugin_action_links' ) );
	}

	public function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=dracula' ) . '">' . __( 'Settings', 'dracula-dark-mode' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}


	/**
	 * Load the textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'dracula', false, dirname( plugin_basename( DRACULA_FILE ) ) . '/languages' );
	}

}

if ( ! function_exists( 'dracula' ) ) {
	function dracula() {
		return Dracula::instance();
	}
}

dracula();