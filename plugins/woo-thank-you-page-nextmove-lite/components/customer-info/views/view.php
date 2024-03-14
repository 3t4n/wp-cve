<?php
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_order_formatted_billing_address', array( $this, 'xlwcty_format_billing_address' ), 11, 2 );
add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'xlwcty_format_shipping_address' ), 11, 2 );
if ( 'yes' !== $this->data->show_billing && 'yes' !== $this->data->show_shipping ) {
	XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'Data not set', 'woo-thank-you-page-nextmove-lite' ) ) );

	return false;
}
XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'On', 'woo-thank-you-page-nextmove-lite' ) ) );

$billing_email = XLWCTY_Compatibility::get_order_data( $order_data, 'billing_email' );
$billing_phone = XLWCTY_Compatibility::get_order_data( $order_data, 'billing_phone' );
$heading_desc  = '';

ob_start();
$desc_class = '';
if ( ! empty( $this->data->desc_alignment ) ) {
	$desc_class = ' class="xlwcty_' . $this->data->desc_alignment . ' xlwcty_margin_bottom"';
}

echo $this->data->desc ? '<div' . $desc_class . '>' . apply_filters( 'xlwcty_the_content', $this->data->desc ) . '</div>' : '';
$heading_desc = ob_get_clean();
$after_desc   = '';

ob_start();
$desc_class = '';
if ( ! empty( $this->data->after_desc_alignment ) ) {
	$desc_class = ' class="xlwcty_' . $this->data->after_desc_alignment . ' xlwcty_margin_top"';
}

echo $this->data->after_desc ? '<div' . $desc_class . '>' . apply_filters( 'xlwcty_the_content', $this->data->after_desc ) . '</div>' : '';
unset( $desc_class );
$after_desc = ob_get_clean();

if ( '2c' === $this->data->layout ) {
	?>


    <div class="xlwcty_Box xlwcty_customer_info">
		<?php
		echo $this->data->heading ? '<div class="xlwcty_title">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ) . '</div>' : '';
		echo $heading_desc;
		if ( ( 'yes' === $this->data->show_billing ) || ( 'yes' === $this->data->show_shipping ) ) {
			echo '<div class="xlwcty_content xlwcty_clearfix">';

			echo '<div class="xlwcty_2_colLeft">';
			if ( '' !== $billing_email ) {
				echo '<p class="xlwcty_BSpace"><strong>' . __( 'Email', 'woocommerce' ) . '</strong></p>';
				echo '<p>' . $billing_email . '</p>';
			}
			echo '</div>';
			echo '<div class="xlwcty_2_colRight">';
			if ( '' !== $billing_phone ) {
				echo '<p class="xlwcty_BSpace"><strong>' . __( 'Phone', 'woocommerce' ) . '</strong></p>';
				echo '<p>' . $billing_phone . '</p>';
			}
			echo '</div>';
			echo '<div class="xlwcty_clear_15"></div>';
			if ( 'yes' === $this->data->show_billing ) {
				$billing_address     = $order_data->get_formatted_billing_address();
				$billing_address_raw = $order_data->get_address();
				$contact_name        = trim( $billing_address_raw['first_name'] . ' ' . $billing_address_raw['last_name'] );
				$contact_name        .= ( $billing_address_raw['company'] ) ? '<br/>' . $billing_address_raw['company'] : '';
				if ( ! empty( $billing_address ) ) {
					?>
                    <div class="xlwcty_2_colLeft">
                        <p class="xlwcty_BSpace"><strong><?php echo __( 'Billing address', 'woocommerce' ); ?></strong></p>
                        <div class="xlwcty_Dview">
                            <p>
								<?php
								echo $contact_name ? $contact_name . '<br/>' : '';
								echo $billing_address;
								?>
                            </p>
                        </div>
                        <div class="xlwcty_Mview">
                            <p>
								<?php
								echo $contact_name ? $contact_name . '<br/>' : '';
								echo $billing_address;
								?>
                            </p>
                        </div>
                    </div>
					<?php
				}
			}
			$billing_add_status = false;
			if ( 'yes' === $this->data->show_shipping ) {
				$shipping_address     = $order_data->get_formatted_shipping_address();
				$shipping_address_raw = $order_data->get_address( 'shipping' );
				$contact_name         = trim( $shipping_address_raw['first_name'] . ' ' . $shipping_address_raw['last_name'] );
				$contact_name         .= ( $shipping_address_raw['company'] ) ? '<br/>' . $shipping_address_raw['company'] : '';
				if ( ! empty( $shipping_address ) ) {
					$billing_add_status = true;
					$extra_class        = ( true === $billing_add_status ) ? 'xlwcty_2_colRight' : 'xlwcty_2_colLeft';
					?>
                    <div class="<?php echo $extra_class; ?>">
                        <p class="xlwcty_BSpace"><strong><?php echo __( 'Shipping address', 'woocommerce' ); ?></strong></p>
                        <div class="xlwcty_Dview">
                            <p>
								<?php
								echo $contact_name ? $contact_name . '<br/>' : '';
								echo $shipping_address;
								?>
                            </p>
                        </div>
                        <div class="xlwcty_Mview">
                            <p>
								<?php
								echo $contact_name ? $contact_name . '<br/>' : '';
								echo $shipping_address;
								?>
                            </p>
                        </div>
                    </div>
					<?php
				}
			}

			echo '</div>';
		}
		echo $after_desc;
		?>

    </div>
	<?php
} else {
	?>
    <div class="xlwcty_Box xlwcty_customer_info xlwcty_info_full_width">
		<?php
		echo $this->data->heading ? '<div class="xlwcty_title">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ) . '</div>' : '';
		echo $heading_desc;
		if ( '' !== $billing_email ) {

			echo '<div class="xlwcty_content xlwcty_clearfix">';
			echo '<p class="xlwcty_BSpace"><strong>' . __( 'Email', 'woocommerce' ) . '</strong></p>';
			echo '<p>' . $billing_email . '</p>';
			echo '</div>';
		}
		if ( '' !== $billing_phone ) {
			echo '<div class="xlwcty_content xlwcty_clearfix">';
			echo '<p class="xlwcty_BSpace"><strong>' . __( 'Phone', 'woocommerce' ) . '</strong></p>';
			echo '<p>' . $billing_phone . '</p>';
			echo '</div>';
		}
		if ( 'yes' === $this->data->show_billing ) {
			$billing_address     = $order_data->get_formatted_billing_address();
			$billing_address_raw = $order_data->get_address();
			$contact_name        = trim( $billing_address_raw['first_name'] . ' ' . $billing_address_raw['last_name'] );
			$contact_name        .= ( $billing_address_raw['company'] ) ? '<br/>' . $billing_address_raw['company'] : '';

			$contact_name = apply_filters( 'xlwcty_customer_info_contact_name', $contact_name, $billing_address_raw );

			if ( ! empty( $billing_address ) ) {
				?>
                <div class="xlwcty_content xlwcty_clearfix">
                    <p class="xlwcty_BSpace"><strong><?php echo __( 'Billing address', 'woocommerce' ); ?></strong></p>
                    <p>
						<?php
						echo $contact_name ? $contact_name . '<br/>' : '';
						echo $billing_address;
						?>
                    </p>
                </div>
				<?php
			}
		}
		if ( 'yes' === $this->data->show_shipping ) {
			$shipping_address     = $order_data->get_formatted_shipping_address();
			$shipping_address_raw = $order_data->get_address( 'shipping' );
			$contact_name         = trim( $shipping_address_raw['first_name'] . ' ' . $shipping_address_raw['last_name'] );
			$contact_name         .= ( $shipping_address_raw['company'] ) ? '<br/>' . $shipping_address_raw['company'] : '';
			$contact_name         = apply_filters( 'xlwcty_customer_info_contact_name', $contact_name, $shipping_address_raw );

			if ( ! empty( $shipping_address ) ) {
				?>
                <div class="xlwcty_content xlwcty_clearfix">
                    <p class="xlwcty_BSpace"><strong><?php echo __( 'Shipping address', 'woocommerce' ); ?></strong></p>
                    <p>
						<?php
						echo $contact_name ? $contact_name . '<br/>' : '';
						echo $shipping_address;
						?>
                    </p>
                </div>
				<?php
			}
		}
		echo $after_desc;
		?>
    </div>
	<?php
}
remove_filter( 'woocommerce_order_formatted_billing_address', array( $this, 'xlwcty_format_billing_address' ), 11 );
remove_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'xlwcty_format_shipping_address' ), 11 );
