<?php
/**
 * Recurring Shipping Methods Display
 *
 * Based on the WooCommerce core template: /woocommerce/templates/cart/cart-shipping.php
 *
 * @author  Prospress
 * @package WooCommerce Subscriptions/Templates
 * @version 2.0.12
 */
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
if ( 1 < count( $available_methods ) ) { ?>
	<?php foreach ( $available_methods as $method ) {
		$checked = checked( $method->id, $chosen_method, false );
		if ( '' !== $checked ) {
			?>
            <tr class="shipping recurring-total <?php echo esc_attr( $recurring_cart_key ); ?>">
                <th><?php
					echo "<span>" . wp_kses_post( $package_name ) . "</span>";
					echo '<small>&nbsp;' . sprintf( __( 'via %s', 'funnel-builder' ), WFACP_Common::shipping_method_label( $method ) ) . '</small>';
					?>
                </th>
                <td data-title="<?php echo esc_attr( $package_name ); ?>">
					<?php if ( $method->get_cost() == 0 ) {
						echo '<span>' . _e( 'Free', 'funnel-builder' ) . '</span>';
					} else {
						echo wc_price( $method->get_cost() );
					}
					if ( $show_package_details ) {
						echo '<p class="woocommerce-shipping-contents"><small>' . $package_details . '</small></p>';
					}
					?>
                </td>
            </tr>
			<?php
		}
	}
}
