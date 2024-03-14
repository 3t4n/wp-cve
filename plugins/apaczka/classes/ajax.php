<?php
/**
 * EasyPack AJAX
*
* @author      WPDesk
* @category    Admin
* @package     EasyPack
* @version     2.1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Apaczka_AJAX' ) ) :

class Apaczka_AJAX {

	/**
	 * Ajax handler
	 */
	public static function init() {
		add_action( 'wp_ajax_apaczka', array( __CLASS__, 'ajax_apaczka' ) );
		add_action( 'admin_head', array( __CLASS__, 'wp_footer_apaczka_nonce' ) );
	}

	public static function wp_footer_apaczka_nonce() {
		?>
		<script type="text/javascript">
			var apaczka_ajax_nonce = '<?php echo wp_create_nonce('apaczka_ajax_nonce'); ?>';
		</script>
		<?php
	}

	public static function ajax_apaczka() {
		check_ajax_referer( 'apaczka_ajax_nonce', 'security' );
		if ( isset( $_REQUEST['apaczka_action'] ) ) {
			$action = $_REQUEST['apaczka_action'];
			if ( $action == 'create_package' ) {
				self::create_package(WPDesk_Apaczka_Shipping::APACZKA_PICKUP_COURIER);
			}
            if ( $action == 'create_package_pickup_self' ) {
                self::create_package(WPDesk_Apaczka_Shipping::APACZKA_PICKUP_SELF);
            }
			if ( $action == 'get_waybill' ) {
				self::get_waybill();
			}
		}
	}

	public static function create_package($pickupmethod = WPDesk_Apaczka_Shipping::APACZKA_PICKUP_COURIER) {
		WPDesk_Apaczka_Shipping::ajax_create_package($pickupmethod);
	}

	public static function get_waybill() {
		WPDesk_Apaczka_Shipping::ajax_get_waybill();
	}

}

endif;

Apaczka_AJAX::init();