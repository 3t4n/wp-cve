<?php
/**
 * Template for the Faire order management metabox.
 *
 * @package  FAIRE
 *
 * @var int    $wc_order_id
 * @var string $faire_order_id
 * @var string $wc_order_status
 * @var WC_DateTime|NULL $wc_order_created
 * @var WC_DateTime|NULL $wc_order_updated
 */

?>
<?php if ( ! in_array( $wc_order_status, array( 'faire-new', 'processing' ), true ) ) : ?>
	<p><?php echo esc_html__( 'No actions can be taken on this order.', 'faire-for-woocommerce' ); ?></p>
<?php endif; ?>

<?php if ( 'faire-new' === $wc_order_status ) : ?>
	<button
		id="btn_faire_accept_order"
		class="button-primary"
		style="margin:0.5em 0;"
		type="button"
		data-faire_order_id="<?php echo esc_attr( (string) $faire_order_id ); ?>"
		data-wc_order_id="<?php echo esc_attr( (string) $wc_order_id ); ?>"
	>
		<?php echo esc_html__( 'Accept Order', 'faire-for-woocommerce' ); ?>
	</button>
<?php endif; ?>

<?php if ( 'processing' === $wc_order_status ) : ?>
	<p>
	<?php echo esc_html__( 'If the carrier you are using is not within the list below, please add the delivery details directly in your Faire account.', 'faire-for-woocommerce' ); ?>
	</p>
	<?php
		woocommerce_wp_select(
			array(
				'id'      => 'faire_order_shipping_method',
				'label'   => __( 'Shipping method (required)', 'faire-for-woocommerce' ),
				'style'   => 'width:95%;',
				'options' => array(
					''              => __( 'Select carrier', 'faire-for-woocommerce' ),
					'CANADA_POST'   => 'Canada Post',
					'DHL_ECOMMERCE' => 'DHL Ecommerce',
					'DHL_EXPRESS'   => 'DHL Express',
					'FEDEX'         => 'Fedex',
					'PUROLATOR'     => 'Purolator',
					'UPS'           => 'UPS',
					'USPS'          => 'USPS',
					'POSTNL'        => 'PostNL',
					'CANPAR'        => 'Canpar',
				),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'        => 'faire_shipping_cost',
				// translators: %s currency.
				'label'     => sprintf( __( 'Shipping cost (required) in %s', 'faire-for-woocommerce' ), get_woocommerce_currency() ),
				'data_type' => 'price',
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'    => 'faire_tracking_code',
				'label' => __( 'Tracking code (required)', 'faire-for-woocommerce' ),
			)
		);
	?>
	<button
		id="btn_faire_add_order_shipping_method"
		class="button-primary"
		type="button"
		data-faire_order_id="<?php echo esc_attr( (string) $faire_order_id ); ?>"
		data-wc_order_id="<?php echo esc_attr( (string) $wc_order_id ); ?>"
		data-wc_order_created="<?php echo esc_attr( (string) $wc_order_created ); ?>"
		data-wc_order_updated="<?php echo esc_attr( (string) $wc_order_updated ); ?>"
	>
		<?php echo esc_html__( 'Add shipment', 'faire-for-woocommerce' ); ?>
	</button>
<?php endif; ?>
<p id="faire_manage_order_msg" class="message"></p>
