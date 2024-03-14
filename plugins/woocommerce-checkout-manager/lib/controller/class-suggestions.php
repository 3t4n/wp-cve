<?php

namespace QuadLayers\WOOCCM\Controller;

/**
 * Suggestions Class
 */
class Suggestions {

	protected static $_instance;

	public function __construct() {
		add_action( 'wooccm_sections_header', array( $this, 'add_header' ) );
	}

	public function add_header() {
		?>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings_suggestions' ) ); ?>"><?php echo esc_html__( 'Suggestions', 'woocommerce-checkout-manager' ); ?></a></li> |
		<?php
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
