<?php
/**
 * Admin Payment Edit Page.
 * Page: Expenses
 * Tab: Payment
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Payments
 * @package     EverAccounting
 *
 * @var int $payment_id
 */

defined( 'ABSPATH' ) || exit();

try {
	$payment = new \EverAccounting\Models\Payment( $payment_id );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}

$back_url = remove_query_arg( array( 'action', 'payment_id' ) );
$title    = $payment->exists() ? __( 'Update Payment', 'wp-ever-accounting' ) : __( 'Add Payment', 'wp-ever-accounting' );

?>
	<div class="ea-title-section">
		<div>
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Payments', 'wp-ever-accounting' ); ?></h1>
			<?php if ( $payment->exists() ) : ?>
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'tab'    => 'payments',
							'page'   => 'ea-expenses',
							'action' => 'add',
						),
						admin_url( 'admin.php' )
					)
				);
				?>
							" class="page-title-action">
					<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action">
					<?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<hr class="wp-header-end">

	<form id="ea-payment-form" method="post">
		<div class="ea-card">
			<div class="ea-card__header">
				<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
			</div>

			<div class="ea-card__inside">

				<div class="ea-row">
					<?php
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Date', 'wp-ever-accounting' ),
							'name'          => 'payment_date',
							'placeholder'   => esc_html__( 'Enter date', 'wp-ever-accounting' ),
							'data_type'     => 'date',
							'value'         => $payment->get_payment_date() ? $payment->get_payment_date() : null,
							'required'      => true,
						)
					);

					eaccounting_account_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Account', 'wp-ever-accounting' ),
							'name'          => 'account_id',
							'value'         => $payment->get_account_id(),
							'creatable'     => true,
							'placeholder'   => esc_html__( 'Select Account', 'wp-ever-accounting' ),
							'required'      => true,
						)
					);

					eaccounting_text_input(
						array(
							'label'         => esc_html__( 'Amount', 'wp-ever-accounting' ),
							'name'          => 'amount',
							'value'         => $payment->get_amount(),
							'data_type'     => 'price',
							'required'      => true,
							'wrapper_class' => 'ea-col-6',
							'placeholder'   => esc_html__( 'Enter amount', 'wp-ever-accounting' ),
						)
					);

					eaccounting_vendor_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Vendor', 'wp-ever-accounting' ),
							'name'          => 'contact_id',
							'id'            => 'vendor_id',
							'value'         => $payment->get_contact_id(),
							'placeholder'   => esc_html__( 'Select Vendor', 'wp-ever-accounting' ),
							'type'          => 'vendor',
							'creatable'     => true,
						)
					);

					eaccounting_category_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => esc_html__( 'Category', 'wp-ever-accounting' ),
							'name'          => 'category_id',
							'value'         => $payment->get_category_id(),
							'required'      => true,
							'type'          => 'expense',
							'creatable'     => true,
							'ajax_action'   => 'eaccounting_get_expense_categories',
							'modal_id'      => 'ea-modal-add-expense-category',
						)
					);

					eaccounting_payment_method_dropdown(
						array(
							'label'         => esc_html__( 'Payment Method', 'wp-ever-accounting' ),
							'name'          => 'payment_method',
							'wrapper_class' => 'ea-col-6',
							'required'      => true,
							'value'         => $payment->get_payment_method(),
						)
					);
					eaccounting_textarea(
						array(
							'label'         => esc_html__( 'Description', 'wp-ever-accounting' ),
							'name'          => 'description',
							'value'         => $payment->get_description(),
							'required'      => false,
							'wrapper_class' => 'ea-col-12',
							'placeholder'   => esc_html__( 'Enter description', 'wp-ever-accounting' ),
						)
					);

					eaccounting_text_input(
						array(
							'label'         => esc_html__( 'Reference', 'wp-ever-accounting' ),
							'name'          => 'reference',
							'value'         => $payment->get_reference(),
							'required'      => false,
							'wrapper_class' => 'ea-col-6',
							'placeholder'   => esc_html__( 'Enter reference', 'wp-ever-accounting' ),
						)
					);

					eaccounting_file_input(
						array(
							'label'         => esc_html__( 'Attachments', 'wp-ever-accounting' ),
							'name'          => 'attachment_id',
							'allowed-types' => 'jpg,jpeg,png,pdf',
							'value'         => $payment->get_attachment_id(),
							'required'      => false,
							'wrapper_class' => 'ea-col-6',
							'placeholder'   => esc_html__( 'Upload File', 'wp-ever-accounting' ),
						)
					);
					eaccounting_hidden_input(
						array(
							'name'  => 'id',
							'value' => $payment->get_id(),
						)
					);

					eaccounting_hidden_input(
						array(
							'name'  => 'action',
							'value' => 'eaccounting_edit_payment',
						)
					);

					?>
				</div>


			</div>
			<div class="ea-card__footer">
				<?php
				wp_nonce_field( 'ea_edit_payment' );
				submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
				?>
			</div>
		</div>
	</form>
<?php
eaccounting_enqueue_js(
	"
	jQuery('#ea-payment-form #amount').inputmask('decimal', {
			alias: 'numeric',
			groupSeparator: '" . $payment->get_currency_thousand_separator() . "',
			autoGroup: true,
			digits: '" . $payment->get_currency_precision() . "',
			radixPoint: '" . $payment->get_currency_decimal_separator() . "',
			digitsOptional: false,
			allowMinus: false,
			prefix: '" . $payment->get_currency_symbol() . "',
			placeholder: '0.000',
			rightAlign: 0,
			autoUnmask: true
		});
"
);
