<?php
/**
 * Order tracking rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>

<div class="<?php echo esc_html( Branding::$branding_short ); ?>-tracking">
	<?php if ( property_exists( $tracking, 'shipmentsTracking' ) && ! empty( $tracking->shipmentsTracking ) ) : ?>
		<?php foreach ( $tracking->shipmentsTracking as $shipment ) : ?>
			<?php $parcel_count = count( $shipment->parcelsTracking ); ?>
			<?php if ( 1 === $parcel_count || 0 === $parcel_count ) : ?>
				<?php /* translators: 1) int number of shipments */ ?>
				<p><?php echo esc_html( sprintf( __( 'Your shipment has %s package.', 'boxtal-connect' ), $parcel_count ) ); ?></p>
			<?php else : ?>
				<?php /* translators: 1) int number of shipments */ ?>
				<p><?php echo esc_html( sprintf( __( 'Your shipment has %s packages.', 'boxtal-connect' ), $parcel_count ) ); ?></p>
			<?php endif; ?>
			<?php foreach ( $shipment->parcelsTracking as $parcel ) : ?>
				<?php if ( null !== $parcel->trackingUrl ) : ?>
					<?php /* translators: 1) shipment reference */ ?>
					<p><?php echo sprintf( esc_html__( 'Package reference %s', 'boxtal-connect' ), '<a href="' . esc_url( $parcel->trackingUrl ) . '" target="_blank">' . esc_html( $parcel->reference ) . '</a>' ); ?></p>
				<?php else : ?>
					<?php /* translators: 1) shipment reference */ ?>
					<p><?php echo esc_html( sprintf( __( 'Package reference %s', 'boxtal-connect' ), $parcel->reference ) ); ?></p>
				<?php endif; ?>
				<?php if ( is_array( $parcel->trackingEvents ) && count( $parcel->trackingEvents ) > 0 ) : ?>
				<?php else : ?>
					<p><?php esc_html_e( 'No tracking event for this package yet.', 'boxtal-connect' ); ?></p>
				<?php endif; ?>
				<br/>
			<?php endforeach; ?>
		<?php endforeach; ?>

	<?php endif; ?>
</div>
