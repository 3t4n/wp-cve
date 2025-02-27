<?php
namespace Yay_Swatches\Engine;

use Yay_Swatches\Utils\SingletonTrait;

/**
 * Activate and deactive method of the plugin and relates.
 */
class ActDeact {

	use SingletonTrait;

	protected function __construct() {}

	public static function install_yayswatches_admin_notice() {
		/* translators: %s: Woocommerce link */
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'YaySwatches is enabled but not effective. It requires %s in order to work', 'yay-swatches' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">WooCommerce</a>' ) . '</strong></p></div>';
		return false;
	}

	public static function activate() {
		do_action( 'yaySwatches_activate' );
	}

	public static function deactivate() {
		do_action( 'yaySwatches_deactivate' );
	}

}
