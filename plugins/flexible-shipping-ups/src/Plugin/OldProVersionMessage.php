<?php
/**
 * Old PRO version message.
 *
 * @package WPDesk\FlexibleShippingUps
 */

namespace WPDesk\FlexibleShippingUps;

use UpsFreeVendor\WPDesk\Notice\Notice;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can display message when there is PRO plugin version older than 1.3;
 */
class OldProVersionMessage implements Hookable {

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'admin_notices', [ $this, 'show_old_pro_plugin_version_message_if_present' ] );
	}

	/**
	 * Show old PRO plugin version message if PRO plugin present.
	 */
	public function show_old_pro_plugin_version_message_if_present(): void {
		if ( defined( 'FLEXIBLE_SHIPPING_UPS_PRO_VERSION' ) && version_compare( FLEXIBLE_SHIPPING_UPS_PRO_VERSION, '1.3', '<' ) ) {
			new Notice( sprintf(
				// Translators: PRO plugin version.
				__( '"Flexible Shipping UPS PRO" plugin version %1$s cannot run with this version of "Flexible Shipping for UPS" plugin. %2$sPlease update "Flexible Shipping UPS PRO" plugin to the newest version!%3$s', 'flexible-shipping-ups' ),
				FLEXIBLE_SHIPPING_UPS_PRO_VERSION,
				'<br/><strong>',
				'</strong>'
			), Notice::NOTICE_TYPE_ERROR );
		}
	}

}
