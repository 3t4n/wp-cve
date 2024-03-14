<?php

namespace WpifyWoo\Modules\PacketaShipping\ShippingMethods;

use WC_Shipping_Flat_Rate;

if ( ! class_exists( "WpifyWoo\Modules\PacketaShipping\ShippingMethods\PacketaShippingMethodGRACSHD" ) ) {
	class GRACSHD extends WC_Shipping_Flat_Rate {
		/**
		 * Constructor.
		 *
		 * @param int $instance_id Shipping method instance ID.
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id           = "packeta_7770";
			$this->instance_id  = absint( $instance_id );
			$this->method_title = __( "GR ACS HD", "wpify-woo" );
			$this->supports     = array(
				"shipping-zones",
				"instance-settings",
				"instance-settings-modal",
			);

			$this->init();

			add_action( "woocommerce_update_options_shipping_" . $this->id, array( $this, "process_admin_options" ) );
		}
	}
}
