<?php
defined( 'ABSPATH' ) || exit;

$billing_email = $this->order->get_billing_email(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$billing_phone = $this->order->get_billing_phone(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

$customer_layout = '';
if ( isset( $this->data['layout_settings'] ) ) {
	$l_setting = $this->data['layout_settings']; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	if ( !is_null($l_setting) && strpos( $l_setting, 'wfty_cont_style' ) ) {
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
		if ( ! empty( $billing_email ) ) {
			echo '<div class="wfty_text_bold"><strong>' . $texts['email'] . '</strong></div>';
			echo '<div class="wfty_view">' . esc_html( $billing_email ) . '</div>';
		}
		echo '</div>';
		echo '<div class="wfty_2_col_right">';
		if ( ! empty( $billing_phone ) ) {
			echo '<div class="wfty_text_bold"><strong>' . $texts['phone'] . '</strong></div>';
			echo '<div class="wfty_view">' . esc_html( $billing_phone ) . '</div>';
		}
		echo '</div>';
		echo '<div class="wfty_clear_15"></div>';
		$billing_address     = $this->order->get_formatted_billing_address(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		$billing_address_raw = $this->order->get_address(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

		/** check if only billing */
		if ( ! empty( $billing_address ) ) {
			?>
            <div class="wfty_2_col_left">
                <div class="wfty_text">
                    <div class="wfty_text_bold"><strong><?php echo $texts['billing_address']; ?></strong></div>
                    <div class="wfty_view">
						<?php
						echo wp_kses_post( $billing_address );
						?>
                    </div>
                </div>
            </div>
			<?php
		}

		/** show shipping address */
		$shipping_address     = $this->order->get_formatted_shipping_address(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		$shipping_address_raw = $this->order->get_address( 'shipping' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

		$shipping_option = get_option( 'woocommerce_ship_to_countries' );

		if ( 'disabled' !== $shipping_option && ! empty( $shipping_address ) ) {
			$extra_class = ( empty( $billing_address ) ) ? 'wfty_2_col_left' : 'wfty_2_col_right';
			?>
            <div class="<?php echo esc_attr( $extra_class ); ?>">
                <div class="wfty_text">
                    <div class="wfty_text_bold"><strong><?php echo $texts['shipping_address']; ?></strong></div>
                    <div class="wfty_view">
						<?php
						echo wp_kses_post( $shipping_address );
						?>
                    </div>
                </div>
            </div>
			<?php
		}
		?>
        <div class="wfty_clear"></div>
		<?php
		if ( class_exists( 'WFACP_Common_Helper' ) ) {
			if ( method_exists( 'WFACP_Common_Helper', 'print_custom_field_at_thankyou' ) ) {
				WFACP_Common_Helper::print_custom_field_at_thankyou( $this->order );//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			}
		}
		?>
    </div>
</div>
