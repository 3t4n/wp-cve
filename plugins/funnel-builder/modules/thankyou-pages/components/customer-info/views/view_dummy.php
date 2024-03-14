<?php
defined( 'ABSPATH' ) || exit;
$order_id        = ( $this->order instanceof WC_Order ) ? $this->order->get_id() : 0; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$customer_layout = '';
if ( isset( $this->data['layout_settings'] ) ) {
	$l_setting = $this->data['layout_settings']; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	if ( strpos( $l_setting, 'wfty_cont_style' ) ) {
		$customer_layout = $l_setting;
	} elseif ( '2c' !== $l_setting ) {
		$customer_layout = ' wfty_full_width';
	}
}

$customer_details_heading = isset( $this->data['customer_details_heading'] ) ? $this->data['customer_details_heading'] : __( 'Customer Details', 'funnel-builder' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

$texts = apply_filters( 'wffn_thankyou_customer_info_text', array(
	'email'            => esc_attr__( 'Email', 'woocommerce' ),
	'phone'            => esc_attr__( 'Phone', 'woocommerce' ),
	'billing_address'  => esc_attr__( 'Billing address', 'woocommerce' ),
	'shipping_address' => esc_attr__( 'Shipping address', 'woocommerce' ),
) );
?>
<div class="wfty_box wfty_customer_info">
    <div class="wfty-customer-info-heading wfty_title"><?php echo esc_html( $customer_details_heading ); ?></div>
    <div class="wfty_content wfty_clearfix wfty_text <?php echo esc_attr( $customer_layout ) ?>">
		<?php
		echo '<div class="wfty_2_col_left">';
		if ( ! empty( $dummy_data['email'] ) ) {
			echo '<div class="wfty_text_bold"><strong>' . $texts['email'] . '</strong></div>';
			echo '<div class="wfty_view">' . esc_html( $dummy_data['email'] ) . '</div>'; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		}
		echo '</div>';
		echo '<div class="wfty_2_col_right">';
		if ( ! empty( $dummy_data['phone'] ) ) {
			echo '<div class="wfty_text_bold"><strong>' . $texts['phone'] . '</strong></div>';
			echo '<div class="wfty_view">' . esc_html( $dummy_data['phone'] ) . '</div>'; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		}
		echo '</div>';
		echo '<div class="wfty_clear_15"></div>';

		/** check if only billing */
		if ( ! empty( $billing_address ) ) {
			?>
            <div class="wfty_2_col_left">
                <div class="wfty_text">
                    <div class="wfty_text_bold"><strong><?php echo $texts['billing_address']; ?></strong></div>
                    <div class="wfty_view">
						<?php echo wp_kses_post( $billing_address ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
                    </div>
                </div>
            </div>
			<?php
		}

		/** show shipping address */
		$shipping_option = get_option( 'woocommerce_ship_to_countries' );
		if ( 'disabled' !== $shipping_option && ! empty( $shipping_address ) ) {
			$extra_class = ( empty( $billing_address ) ) ? 'wfty_2_col_left' : 'wfty_2_col_right';
			?>
            <div class="<?php echo esc_attr( $extra_class ); ?>">
                <div class="wfty_text">
                    <div class="wfty_text_bold"><strong><?php echo $texts['shipping_address']; ?></strong></div>
                    <div class="wfty_view">
						<?php echo wp_kses_post( $shipping_address ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
                    </div>
                </div>
            </div>
			<?php
		}
		?>
        <div class="wfty_clear"></div>
    </div>
</div>
