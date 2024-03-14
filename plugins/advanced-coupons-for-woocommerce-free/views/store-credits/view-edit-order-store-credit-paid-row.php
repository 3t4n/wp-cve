<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}?>

<tr class="acfw-store-credits-paid">
    <td class="label">
        <?php esc_html_e( 'Paid via Store Credits', 'advanced-coupons-for-woocommerce-free' ); ?>:
    </td>
    <td width="1%"></td>
    <td class="total">
        <?php echo wp_kses_post( wc_price( $sc_data['amount'] * -1, array( 'currency' => $order->get_currency() ) ) ); ?>
    </td>
</tr>
