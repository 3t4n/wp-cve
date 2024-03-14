<?php

namespace WpifyWoo\Modules\PacketaShipping;

use WC_Shipping_Flat_Rate;

if ( ! class_exists( PacketaShippingMethod::class ) ) {
	class PacketaShippingMethod extends WC_Shipping_Flat_Rate {
		/**
		 * Constructor.
		 *
		 * @param int $instance_id Shipping method instance ID.
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id           = 'packeta';
			$this->instance_id  = absint( $instance_id );
			$this->method_title = __( 'Packeta', 'wpify-woo' );
			$this->supports     = array(
				'shipping-zones',
				'instance-settings',
				'instance-settings-modal',
			);

			$this->init();

			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			add_filter( 'woocommerce_shipping_instance_form_fields_' . $this->id, array( $this, 'shipping_instance_form_add_extra_fields' ) );
		}

		public function shipping_instance_form_add_extra_fields( $settings ) {
			$settings['logo_type'] = [
				'type'        => 'select',
				'title'       => __( 'Select logo type', 'wpify-woo-balikovna' ),
				'description' => __( 'Select logo to display', 'wpify-woo' ),
				'options'       => [
					'zasilkovna_logo'    => __( 'Packeta CZ', 'wpify-woo' ),
					'zasilkovna_sk_logo' => __( 'Packeta SK', 'wpify-woo' ),
					'packeta_logo'       => __( 'Packeta Logo', 'wpify-woo' ),
					'packeta_ico'        => __( 'Icon before button', 'wpify-woo' ),
				],
			];

			return $settings;
		}
	}

}
