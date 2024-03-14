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
?>
<div class="xlwcty_Box xlwcty_subscription xlwcty_order_details_2_col xlwcty_Box xlwcty_minicart">

    <div class="xlwcty_title"><?php esc_html_e( 'Related Subscriptions', 'woocommerce-subscriptions' ); ?></div>
    <table class="shop_table shop_table_responsive my_account_orders">
        <thead>
        <tr>
            <th class="order-number xlwcty_left"><span class="nobr"><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?></span></th>
            <th class="order-status xlwcty_center "><span class="nobr"><?php echo esc_html_x( 'Next Payment', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
            <th class="order-total xlwcty_center "><span class="nobr"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
            <th class="order-total xlwcty_center "><span class="nobr"><?php echo esc_html_x( 'Action', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $subscriptions as $subscription_id => $subscription ) : ?>
            <tr class="order">
                <td data-title="Subscription" class="subscription-id order-number xlwcty_left">
                    <a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>"><strong><?php echo sprintf( esc_html_x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), esc_html( $subscription->get_order_number() ) ); ?></strong></a>
                    <small>(
						<?php
						if ( function_exists( 'wcs_get_subscription_status_name' ) ) {
							echo ' ' . esc_attr( wcs_get_subscription_status_name( $subscription->get_status() ) );
						} else {
							echo ' ' . esc_attr( $subscription->get_status() );
						}
						?>
                        )</small>
                </td>
                <td data-title="Next Payment" class="subscription-next-payment order-date xlwcty_center "> <?php echo esc_attr( $subscription->get_date_to_display( 'next_payment' ) ); ?></td>
                <td data-title="Total" class="subscription-total order-total xlwcty_center "> <?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?></td>
                <td data-title="Action" class="subscription-actions order-actions xlwcty_center">
                    <a href="<?php echo esc_url( $subscription->get_view_order_url() ); ?>" class="button view"><?php echo esc_html_x( 'View', 'view a subscription', 'woocommerce-subscriptions' ); ?></a>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
	<?php do_action( 'woocommerce_subscription_after_related_subscriptions_table', $subscriptions, $order_id ); ?>
</div>
