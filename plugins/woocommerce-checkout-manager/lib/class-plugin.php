<?php

namespace QuadLayers\WOOCCM;

use QuadLayers\WOOCCM\Model\Field_Billing as Field_Billing_Model;
use QuadLayers\WOOCCM\Model\Field_Shipping as Field_Shipping_Model;
use QuadLayers\WOOCCM\Model\Field_Additional as Field_Additional_Model;

/**
 * Plugin Class
 */
final class Plugin {

	protected static $_instance;

	public $billing;
	public $shipping;
	public $additional;

	private function __construct() {

		/**
		 * Load plugin textdomain.
		 */
		load_plugin_textdomain( 'woocommerce-checkout-manager', false, WOOCCM_PLUGIN_DIR . '/languages/' );

		/**
		 * Load plugin on woocommerce_init
		 */
		add_action(
			'woocommerce_init',
			function() {

			$this->init_session();

			Backend::instance();
			Upload::instance();
			Controller\Checkout::instance();
			Controller\Field::instance();
			Controller\Order::instance();
			Controller\Email::instance();
			Controller\Advanced::instance();
			Controller\Premium::instance();
			Controller\Suggestions::instance();

			/**
			 * Load checkout fields models
			 */
			$this->billing    = Field_Billing_Model::instance();
			$this->shipping   = Field_Shipping_Model::instance();
			$this->additional = Field_Additional_Model::instance();

			/**
			 * Add premium CSS
			 */
			add_action( 'admin_footer', array( __CLASS__, 'add_premium_css' ) );
			do_action( 'wooccm_init' );

			}
		);

		/**
		 * Clear session on checkout order processed
		 */
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'clear_session' ), 150 );
	}

	public function register_scripts() {
		global $wp_version;

		// Frontend
		// -----------------------------------------------------------------------.

		$frontend = include WOOCCM_PLUGIN_DIR . 'build/frontend/js/index.asset.php';

		wp_register_style( 'wooccm-checkout-css', plugins_url( 'build/frontend/css/style.css', WOOCCM_PLUGIN_FILE ), false, WOOCCM_PLUGIN_VERSION, 'all' );

		wp_register_script( 'wooccm-frontend-js', plugins_url( 'build/frontend/js/index.js', WOOCCM_PLUGIN_FILE ), $frontend['dependencies'], $frontend['version'], true );

		wp_localize_script(
			'wooccm-frontend-js',
			'wooccm_upload',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'wooccm_upload' ),
				'icons'    => array(
					'interactive' => site_url( 'wp-includes/images/media/interactive.png' ),
					'spreadsheet' => site_url( 'wp-includes/images/media/spreadsheet.png' ),
					'archive'     => site_url( 'wp-includes/images/media/archive.png' ),
					'audio'       => site_url( 'wp-includes/images/media/audio.png' ),
					'text'        => site_url( 'wp-includes/images/media/text.png' ),
					'video'       => site_url( 'wp-includes/images/media/video.png' ),
				),
				'message'  => array(
					'uploading' => esc_html__( 'Uploading, please wait...', 'woocommerce-checkout-manager' ),
					'saving'    => esc_html__( 'Saving, please wait...', 'woocommerce-checkout-manager' ),
					'success'   => esc_html__( 'Files uploaded successfully.', 'woocommerce-checkout-manager' ),
					'deleted'   => esc_html__( 'Deleted successfully.', 'woocommerce-checkout-manager' ),
				),
			)
		);

		// Colorpicker
		// ---------------------------------------------------------------------.
		wp_register_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), $wp_version );

		wp_register_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris', 'wp-i18n' ), $wp_version );

		wp_localize_script(
			'wp-color-picker',
			'wpColorPickerL10n',
			array(
				'clear'         => esc_html__( 'Clear' ),
				'defaultString' => esc_html__( 'Default' ),
				'pick'          => esc_html__( 'Select Color' ),
				'current'       => esc_html__( 'Current Color' ),
			)
		);

		wp_register_script( 'farbtastic', admin_url( 'js/farbtastic.js' ), array( 'jquery' ), $wp_version );

	}

	public function clear_session() {
		unset( WC()->session->wooccm );
	}

	public function init_session() {
		if ( isset( WC()->session ) && ! WC()->session->wooccm ) {

			WC()->session->wooccm = array(
				'fields' => array(),
				'fees'   => array(),
				'files'  => array(),
			);
		}
	}

	public static function is_min() {
		if ( ! WOOCCM_DEVELOPER && ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) ) {
			return '.min';
		}
	}

	/**
	 * Add CSS
	 *
	 * @since 7.0.0
	 */
	public static function add_premium_css() {
		?>
		<style>
			.wooccm-premium-field {
				opacity: 0.5;
				pointer-events: none;
			}

			.wooccm-premium-field .description {
				display: block !important;
			}
			#order_data .order_data_column .wooccm-premium-field {
				width: 100% !important;
				float: none !important;
				clear: both;
			}
			#order_data .order_data_column .wooccm-premium-field:after,
			#order_data .order_data_column .wooccm-premium-field:before {
				display: block;
				content: "";
				clear: both;
			}
		</style>
		<script>
			const fields = document.querySelectorAll('.wooccm-premium-field')
			Array.from(fields).forEach((field) => {
				field.closest('tr')?.classList.add('wooccm-premium-field');
			})
		</script>
		<?php
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
function WOOCCM() {
	return Plugin::instance();
}

WOOCCM();
