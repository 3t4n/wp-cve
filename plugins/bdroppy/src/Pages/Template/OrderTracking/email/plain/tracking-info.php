<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $tracking_items ) :

	echo apply_filters( 'bdroppy_shipment_tracking_my_orders_title', __( 'TRACKING INFORMATION', 'bdroppy-shipment-tracking' ) );

		echo  "\n";

	echo esc_html( $tracking_items ) . "\n";

	echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= \n\n";

endif;

?>
