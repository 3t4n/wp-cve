<?php
/**
 * WC Signle Order Page.
 *
 */

$bp_action_variables = bp_action_variables();

if ( ! empty( $bp_action_variables ) ) {
	if ( isset( $bp_action_variables[0] ) && ! empty( $bp_action_variables[1] ) && 'view-order' === $bp_action_variables[0] && is_numeric( $bp_action_variables[1] ) ) {
		$order_id = absint( $bp_action_variables[1] ); ?>

		<div class="youzify-wc-main-content youzify-wc-view-order-content">
			<?php do_action( 'youzify_wc_before_view_order_content' ); ?>
			<?php woocommerce_account_view_order( $order_id ); ?>
			<?php do_action( 'youzify_wc_afet_view_order_content' ); ?>
		</div>

		<?php
	}
} else {
	echo esc_attr( sprintf( '<div class="woocommerce-error">%s</div>', __( 'Please enter a valid order ID', 'youzify' ) ) );
}
