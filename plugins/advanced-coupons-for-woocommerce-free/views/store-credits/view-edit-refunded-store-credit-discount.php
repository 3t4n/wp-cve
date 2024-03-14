<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}?>

<tr class="acfw-refunded-store-credit-discount-row">
    <td colspan="3" style="border-top: 1px solid #999; margin-top:22px; padding-top:12px; height: 1px;"></td>
</tr>

<tr class="acfw-refunded-store-credit-discount-row">
    <td class="label">
        <?php esc_html_e( 'Paid/discounted in store credits', 'advanced-coupons-for-woocommerce-free' ); ?>:
    </td>
    <td width="1%"></td>
    <td class="total">
        <?php echo wp_kses_post( wc_price( $sc_discount, array( 'currency' => $order->get_currency() ) ) ); ?>
    </td>
</tr>

<tr class="acfw-refunded-store-credit-discount-row">
    <td class="label refunded-total">
        <?php esc_html_e( 'Refunded store credit discount/payment', 'advanced-coupons-for-woocommerce-free' ); ?>:
    </td>
    <td width="1%"></td>
    <td class="total refunded-total">
        <?php echo wp_kses_post( wc_price( $refunded * -1, array( 'currency' => $order->get_currency() ) ) ); ?>
    </td>
</tr>

<tr class="acfw-refunded-store-credit-discount-row">
    <td class="label label-highlight">
        <?php esc_html_e( 'Total paid/discounted in store credits', 'advanced-coupons-for-woocommerce-free' ); ?>:
    </td>
    <td width="1%"></td>
    <td class="total label-highlight">
        <?php echo wp_kses_post( wc_price( $total, array( 'currency' => $order->get_currency() ) ) ); ?>
    </td>
</tr>
