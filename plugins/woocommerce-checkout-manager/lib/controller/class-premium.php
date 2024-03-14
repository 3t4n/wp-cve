<?php

namespace QuadLayers\WOOCCM\Controller;

/**
 * Premium Class
 */
class Premium {

	protected static $_instance;

	public function __construct() {
		add_action( 'wooccm_sections_header', array( __CLASS__, 'add_header' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
	}

	public static function add_header() {
		?>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=' . WOOCCM_PREFIX ) ); ?>"><?php echo esc_html__( 'Premium', 'woocommerce-checkout-manager' ); ?></a></li> |
		<?php
	}

	public static function add_menu() {
		add_submenu_page(
			'wc-settings',
			esc_html__( 'Premium', 'woocommerce-checkout-manager' ),
			esc_html__( 'Premium', 'woocommerce-checkout-manager' ),
			'manage_woocommerce',
			WOOCCM_PREFIX,
			function() {
				include_once WOOCCM_PLUGIN_DIR . 'lib/view/backend/pages/premium.php';
			}
		);
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
