<?php
defined( 'ABSPATH' ) || exit;

/**
 * Related Subscriptions section beneath order details table
 *
 * @author        Prospress
 * @category    WooCommerce Subscriptions/Templates
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$order_subscription_heading = ( isset( WFFN_Core()->thank_you_pages->data->component_order_details->data['order_subscription_heading'] ) && ! empty( WFFN_Core()->thank_you_pages->data->component_order_details->data['order_subscription_heading'] ) ) ? WFFN_Core()->thank_you_pages->data->component_order_details->data['order_subscription_heading'] : esc_html__( 'Subscription', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
?>
<div class="wfty_box wfty_subscription">

    <div class="wfty_title"><?php echo esc_html( $order_subscription_heading ); ?></div>
    <table class="shop_table shop_table_responsive my_account_orders">
        <thead>
        <tr>
            <th class="order-number wfty_left"><span class="nobr"><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?></span></th>
            <th class="order-status wfty_center "><span class="nobr"><?php echo esc_html_x( 'Next Payment', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
            <th class="order-total wfty_center "><span class="nobr"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
            <th class="order-total wfty_center "><span class="nobr"></span></th>
        </tr>


        </thead>
        <tbody>
		<?php foreach ( $subscriptions as $subscription_id => $subscription ) : //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
            <tr class="order">
                <td data-title="Subscription" class="subscription-id order-number wfty_left">
                    <a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>"><strong><?php echo sprintf( esc_html_x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), esc_html( $subscription->get_order_number() ) ); ?></strong></a>
                    <small>( <?php echo esc_attr( wcs_get_subscription_status_name( $subscription->get_status() ) ); ?>)</small>
                </td>
                <td data-title="Next Payment" class="subscription-next-payment order-date wfty_center "> <?php echo esc_attr( $subscription->get_date_to_display( 'next_payment' ) ); ?></td>
                <td data-title="Total" class="subscription-total order-total wfty_center "> <?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?></td>
                <td data-title="Action" class="subscription-actions order-actions wfty_center">
                    <a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>" class="button view"><?php echo esc_html_x( 'View', 'view a subscription', 'woocommerce-subscriptions' ); ?></a>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
	<?php do_action( 'woocommerce_subscription_after_related_subscriptions_table', $subscriptions, $order_id ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
</div>
