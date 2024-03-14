<?php
/**
 * Admin Invoice Edit Page.
 *
 * Page: Sales
 * Tab: Invoices
 *
 * @since       1.1.0
 * @subpackage  Admin/Views/Invoices
 * @package     EverAccounting
 *
 * @var Invoice $invoice
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit();

$due      = eaccounting()->settings->get( 'invoice_due', 15 );
$due_date = date_i18n( 'Y-m-d', strtotime( "+ $due days", wp_date( 'U' ) ) );
$invoice->maybe_set_invoice_number();
$title    = $invoice->exists() ? __( 'Update Invoice', 'wp-ever-accounting' ) : __( 'Add Invoice', 'wp-ever-accounting' );
$note     = eaccounting()->settings->get( 'invoice_note' );
$terms    = eaccounting()->settings->get( 'invoice_terms' );
$view_url = admin_url( 'admin.php' ) . '?page=ea-sales&tab=invoices&action=view&invoice_id=' . $invoice->get_id();
$add_new  = add_query_arg(
	array(
		'tab'    => 'invoices',
		'page'   => 'ea-sales',
		'action' => 'add',
	),
	admin_url( 'admin.php' )
);
?>
<div class="ea-row">
	<div class="ea-col-7">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Invoices', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $invoice->exists() ) : ?>
			<a href="<?php echo esc_url( $add_new ); ?>" class="page-title-action">
				<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action">
				<?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<div class="ea-col-5"></div>
</div>

<hr class="wp-header-end">
<form id="ea-invoice-form" method="post" class="ea-form">
	<div class="ea-card">
		<div class="ea-card__header">
			<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
			<div>
				<?php if ( $invoice->exists() ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'action', 'view' ) ); ?>" class="button-secondary">
						<?php esc_html_e( 'View Invoice', 'wp-ever-accounting' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="ea-card__inside">
			<div class="ea-row">
				<?php
				eaccounting_customer_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Customer', 'wp-ever-accounting' ),
						'name'          => 'customer_id',
						'placeholder'   => __( 'Select Customer', 'wp-ever-accounting' ),
						'value'         => $invoice->get_customer_id(),
						'required'      => true,
						'type'          => 'customer',
						'creatable'     => true,
					)
				);
				eaccounting_currency_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Currency', 'wp-ever-accounting' ),
						'name'          => 'currency_code',
						'value'         => $invoice->get_currency_code(),
						'required'      => true,
						'creatable'     => true,
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Invoice Date', 'wp-ever-accounting' ),
						'name'          => 'issue_date',
						'value'         => $invoice->get_issue_date() ? eaccounting_date( $invoice->get_issue_date(), 'Y-m-d' ) : date_i18n( 'Y-m-d' ),
						'required'      => true,
						'data_type'     => 'date',
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Due Date', 'wp-ever-accounting' ),
						'name'          => 'due_date',
						'value'         => $invoice->get_due_date() ? eaccounting_date( $invoice->get_due_date(), 'Y-m-d' ) : $due_date,
						'required'      => true,
						'data_type'     => 'date',
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Invoice Number', 'wp-ever-accounting' ),
						'name'          => 'invoice_number',
						'value'         => empty( $invoice->get_invoice_number() ) ? $invoice->get_invoice_number() : $invoice->get_invoice_number(),
						'required'      => true,
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Order Number', 'wp-ever-accounting' ),
						'name'          => 'order_number',
						'value'         => $invoice->get_order_number(),
						'required'      => false,
					)
				);

				eaccounting_category_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Category', 'wp-ever-accounting' ),
						'name'          => 'category_id',
						'value'         => $invoice->get_category_id(),
						'required'      => true,
						'type'          => 'income',
						'creatable'     => true,
						'ajax_action'   => 'eaccounting_get_income_categories',
						'modal_id'      => 'ea-modal-add-income-category',
					)
				);

				eaccounting_get_admin_template(
					'invoices/invoice-items',
					array(
						'invoice' => $invoice,
					)
				);
				?>
			</div>
			<div class="ea-row ea-mt-20">
				<?php
				eaccounting_textarea(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Note', 'wp-ever-accounting' ),
						'name'          => 'note',
						'value'         => $invoice->exists() ? $invoice->get_note() : $note,
						'required'      => false,
					)
				);
				eaccounting_textarea(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Terms & Conditions', 'wp-ever-accounting' ),
						'name'          => 'terms',
						'value'         => $invoice->exists() ? $invoice->get_terms() : $terms,
						'required'      => false,
					)
				);
				?>
			</div>

		</div>
		<div class="ea-card__footer">
			<?php submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' ); ?>
		</div>
	</div>
	<?php eaccounting_hidden_input( 'id', $invoice->get_id() ); ?>
	<?php eaccounting_hidden_input( 'discount', $invoice->get_discount() ); ?>
	<?php eaccounting_hidden_input( 'discount_type', $invoice->get_discount_type() ); ?>
	<?php eaccounting_hidden_input( 'action', 'eaccounting_edit_invoice' ); ?>
	<?php wp_nonce_field( 'ea_edit_invoice' ); ?>
</form>

<script type="text/template" id="ea-modal-add-discount" data-title="<?php esc_html_e( 'Add Discount', 'wp-ever-accounting' ); ?>">
	<form action="" method="post">
		<?php
		eaccounting_text_input(
			array(
				'label'    => __( 'Discount Amount', 'wp-ever-accounting' ),
				'name'     => 'discount',
				'type'     => 'number',
				'value'    => 0.0000,
				'required' => true,
				'attr'     => array(
					'step' => 0.0001,
					'min'  => 0,
				),
			)
		);
		eaccounting_select(
			array(
				'label'    => __( 'Discount Type', 'wp-ever-accounting' ),
				'name'     => 'discount_type',
				'required' => true,
				'options'  => array(
					'percentage' => __( 'Percentage', 'wp-ever-accounting' ),
					'fixed'      => __( 'Fixed', 'wp-ever-accounting' ),
				),
			)
		);
		?>
	</form>
</script>
