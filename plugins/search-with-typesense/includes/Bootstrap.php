<?php

namespace Codemanas\Typesense;

use Codemanas\Typesense\Backend\Admin;
use Codemanas\Typesense\Backend\Customizer;
use Codemanas\Typesense\Main\EventListener;
use Codemanas\Typesense\Main\Shortcodes;
use Codemanas\Typesense\Helpers\Templates;
use Codemanas\Typesense\Frontend\Frontend;
use Codemanas\Typesense\Main\TemplateHooks;
use Codemanas\Typesense\Elementor\Elementor;
use Codemanas\Typesense\WPCLI\WPCLI;

final class Bootstrap {
	const VERSION = '1.0.0';
	const MINIMUM_PHP_VERSION = '7.4';
	public static ?Bootstrap $_instance = null;
	public ?Templates $templating = null;

	/**
	 * @return Bootstrap|null
	 */
	public static function getInstance(): ?Bootstrap {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self:: $_instance;
	}

	/**
	 * pluginName constructor.
	 */
	public function __construct() {

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

			return;
		}
		$this->autoload();
		$this->templating = Templates::getInstance();

		add_action( 'plugins_loaded', array( $this, 'initPlugin' ) );
		register_activation_hook( CODEMANAS_TYPESENSE_FILE_PATH, [ $this, 'plugin_activated' ] );
		register_deactivation_hook( CODEMANAS_TYPESENSE_FILE_PATH, [ $this, 'plugin_deactivated' ] );
		add_action( 'in_plugin_update_message-' . CODEMANAS_TYPESENSE_BASE_FILE, [ $this, 'plugin_update_message' ] );
	}

	public function plugin_activated() {
		//other plugins can get this option and check if plugin is activated
		update_option( 'cm_typesense_plugin_activate', 'activated' );
	}

	public function plugin_deactivated() {
		delete_option( 'cm_typesense_plugin_activate' );
	}

	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'search-with-typesense' ),
			'<strong>' . esc_html__( 'Search with Typesense', 'search-with-typesense' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'search-with-typesense' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Autoload - PSR 4 Compliance
	 */
	public function autoload() {
		require_once CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/vendor/autoload.php';
	}

	public function initPlugin() {
		require_once CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/includes/Helpers/template-functions.php';
		Admin::getInstance();
		Shortcodes::getInstance();
		EventListener::getInstance();
		Frontend::getInstance();
		TemplateHooks::get_instance();
		Customizer::get_instance();
		WPCLI::getInstance();

		// Run only if Elementor plugin is active
		if ( $this->is_plugin_active( 'elementor/elementor.php' ) ) {
			Elementor::getInstance();
		}

		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'search-with-typesense', false, dirname( plugin_basename( CODEMANAS_TYPESENSE_FILE_PATH ) ) . '/languages' );
	}

	public function plugin_update_message( $plugin_data ) {
		$this->version_update_warning( CODEMANAS_TYPESENSE_VERSION, $plugin_data['new_version'] );
	}

	/**
	 * @param $plugin
	 *
	 * @return bool
	 */
	public function is_plugin_active( $plugin ): bool {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true );
	}

	public function version_update_warning( $current_version, $new_version ) {
		$current_version_minor_part = explode( '.', $current_version )[1];
		$new_version_minor_part     = explode( '.', $new_version )[1];
		if ( $current_version_minor_part === $new_version_minor_part ) {
			return;
		}
		?>
        <style>
            .cmswt-MajorUpdateNotice {
                display: flex;
                max-width: 1000px;
                margin: 0.5em 0;
            }

            .cmswt-MajorUpdateMessage {
                margin-left: 1rem;
            }

            .cmswt-MajorUpdateMessage-desc {
                margin-top: .5rem;
            }

            .cmswt-MajorUpdateNotice + p {
                display: none;
            }
        </style>
        <hr>
        <div class="cmswt-MajorUpdateNotice">
            <span class="dashicons dashicons-info" style="color: #f56e28;"></span>
            <div class="cmswt-MajorUpdateMessage">
                <strong class="cmswt-MajorUpdateMessage-title">
					<?php esc_html_e( 'Heads up, Please backup before upgrade!', 'search-with-typesense' ); ?>
                </strong>
                <div class="cmswt-MajorUpdateMessage-desc">
					<?php
					esc_html_e( 'The latest update includes some substantial changes across different areas of the plugin. We highly recommend you backup your site before upgrading, and make sure you first update in a staging environment', 'search-with-typesense' );
					?>
                </div>
            </div>
        </div>
		<?php
	}
}

Bootstrap::getInstance();