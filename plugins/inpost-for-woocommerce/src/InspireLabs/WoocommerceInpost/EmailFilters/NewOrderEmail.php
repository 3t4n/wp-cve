<?php


namespace InspireLabs\WoocommerceInpost\EmailFilters;

use WC_Order;
use InspireLabs\WoocommerceInpost\EasyPack;

class NewOrderEmail {

	public function init() {
		add_action( 'woocommerce_email_order_meta',
			[ $this, "print_parcel_machine_info" ],
			10,
			3 );
	}

	/**
	 * @param WC_Order $wc_order
	 * @param          $sent_to_admin
	 * @param          $plain_text
	 */
	public function print_parcel_machine_info(
		WC_Order $wc_order,
		$sent_to_admin,
		$plain_text
	): void {

			$parcelMachine = get_post_meta( $wc_order->get_id(),
				'_parcel_machine_id', true );

			if ( empty( $parcelMachine ) ) {
				return;
			}

        $notice =  __( sprintf( __( 'Selected parcel machine', 'woocommerce-inpost' ) . ': %s',
            $parcelMachine ) );

        echo "<div style='margin-bottom: 40px'>";
        echo wp_kses_post( $notice );
        echo "</div>";

	}
}
