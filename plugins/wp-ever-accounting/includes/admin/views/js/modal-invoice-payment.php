<?php
/**
 * Invoice payment.
 *
 * @package     EverAccounting
 * @subpackage  Admin/Js Templates
 * @since       1.0.2
 * @var \EverAccounting\Models\Invoice $invoice;
 */

defined( 'ABSPATH' ) || exit();
?>
<script type="text/template" id="ea-modal-add-invoice-payment" data-title="<?php esc_html_e( 'Add Payment', 'wp-ever-accounting' ); ?>">
	<form action="" method="post">
		<?php
		eaccounting_text_input(
			array(
				'label'       => __( 'Date', 'wp-ever-accounting' ),
				'name'        => 'date',
				'placeholder' => __( 'Enter Date', 'wp-ever-accounting' ),
				'data_type'   => 'date',
				'value'       => date_i18n( 'Y-m-d' ),
				'required'    => true,
			)
		);
		eaccounting_text_input(
			array(
				'label'       => __( 'Amount', 'wp-ever-accounting' ),
				'name'        => 'amount',
				'value'       => $invoice->get_total_due(),
				'data_type'   => 'price',
				'required'    => true,
				'placeholder' => __( 'Enter Amount', 'wp-ever-accounting' ),
				/* translators: %s amount */
				'desc'        => sprintf( __( 'Total amount due:%s', 'wp-ever-accounting' ), eaccounting_price( $invoice->get_total_due(), $invoice->get_currency_code() ) ),
			)
		);
		eaccounting_account_dropdown(
			array(
				'label'       => __( 'Account', 'wp-ever-accounting' ),
				'name'        => 'account_id',
				'creatable'   => false,
				'placeholder' => __( 'Select Account', 'wp-ever-accounting' ),
				'required'    => true,
			)
		);
		eaccounting_payment_method_dropdown(
			array(
				'label'    => __( 'Payment Method', 'wp-ever-accounting' ),
				'name'     => 'payment_method',
				'required' => true,
				'value'    => '',
			)
		);
		eaccounting_textarea(
			array(
				'label'       => __( 'Description', 'wp-ever-accounting' ),
				'name'        => 'description',
				'value'       => '',
				'required'    => false,
				'placeholder' => __( 'Enter description', 'wp-ever-accounting' ),
			)
		);
		eaccounting_hidden_input(
			array(
				'name'  => 'invoice_id',
				'value' => $invoice->get_id(),
			)
		);

		eaccounting_hidden_input(
			array(
				'name'  => 'action',
				'value' => 'eaccounting_add_invoice_payment',
			)
		);
		wp_nonce_field( 'ea_add_invoice_payment' );
		?>
	</form>
</script>

