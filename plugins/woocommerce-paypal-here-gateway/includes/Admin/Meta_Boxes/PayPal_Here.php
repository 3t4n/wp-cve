<?php
/**
 * WooCommerce PayPal Here Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes;

use PHPQRCode\QRcode;

defined( 'ABSPATH' ) or exit;

/**
 * PayPal Here Meta Box.
 *
 * @since 1.0.0
 */
class PayPal_Here extends Meta_Box {


	/**
	 * Constructs the PayPal Here Meta Box class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->title   = __( 'PayPal Here', 'woocommerce-gateway-paypal-here' );
		$this->context = 'side';

		// show at the top
		$this->priority = 'high';
	}


	/**
	 * Outputs the meta box markup.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post the post object
	 */
	public function output( $post ) {

		$order = wc_get_order( $post );

		if ( ! $order || ! $order instanceof \WC_Order ) {
			return;
		}

		// the QR code should link to a different URL since we don't know the logged-in status
		// of the scanning device. For that reason, and also in order to keep the complexity of the
		// code low, we should link to a page which checks logged-in status, and then redirects to the
		// sideload URL if logged-in, and redirects to a login page if not logged in
		$qr_code_url  = wc_paypal_here()->get_gateway()->get_sideload_redirect_url( $order->get_id() );
		$sideload_url = wc_paypal_here()->get_gateway()->get_sideload_url( $order );
		$protocol     = array( wc_paypal_here()->get_gateway()->get_sideload_protocol() );

		if ( $sideload_url && '' !== $sideload_url ) {

			?>

			<div class="paypal-here-qr-code-container">
				<?php QRcode::svg( $qr_code_url, 'php://output', QR_ECLEVEL_L, 5 ); ?>
			</div>

			<a class="button paypal-here-show-qr-button"><?php esc_html_e( 'QR Code', 'woocommerce-gateway-paypal-here' ); ?></a>

			<a class="button button-primary paypal-here-open-button"
			   href="<?php echo esc_url( $sideload_url, $protocol ); ?>"
			>
				<?php esc_html_e( 'Open in PayPal Here', 'woocommerce-gateway-paypal-here' ); ?>
			</a>

			<?php
		}
	}


	/**
	 * Saves the data inside this meta box.
	 *
	 * @since 1.0.0
	 */
	public function save() {}


}
