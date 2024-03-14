<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $tracking_items ) : ?>
	<h2><?php echo apply_filters( 'bdroppy_shipment_tracking_my_orders_title', __( 'Tracking Information', 'bdroppy-shipment-tracking' ) ); ?></h2>

    <a href="<?php echo esc_url( $tracking_items ); ?>" target="_blank"><?php _e( 'Track', 'bdroppy-shipment-tracking' ); ?></a>

    <br /><br />

<?php
endif;
