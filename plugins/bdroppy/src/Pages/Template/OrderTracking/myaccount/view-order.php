<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $tracking_items ) : ?>

	<h2><?php echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( 'Tracking Information', 'bdroppy-shipment-tracking' ) ); ?></h2>

	<table class="shop_table shop_table_responsive my_account_tracking">
        <tr>
            <th class="tracking-provider"><span class="nobr"><?php _e( 'Track', 'bdroppy-shipment-tracking' ); ?></span></th>
            <td class="tracking-provider" data-title="<?php _e( 'Provider', 'woocommerce-shipment-tracking' ); ?>">
                <a href="<?php echo $tracking_items ; ?>" >tracking</a>
            </td>
        </tr>
	</table>

<?php
endif;
