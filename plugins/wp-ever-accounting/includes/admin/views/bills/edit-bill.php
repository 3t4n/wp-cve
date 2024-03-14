<?php
/**
 * Admin Bill Form.
 *
 * Page: Expenses
 * Tab: Bills
 *
 * @since       1.1.0
 * @subpackage  Admin/Views/Bills
 * @package     EverAccounting
 *
 * @var Bill $bill
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit();

$bill->maybe_set_bill_number();
$title    = $bill->exists() ? esc_html__( 'Update Bill', 'wp-ever-accounting' ) : __( 'Add Bill', 'wp-ever-accounting' );
$note     = eaccounting()->settings->get( 'bill_note' );
$terms    = eaccounting()->settings->get( 'bill_terms' );
$due      = eaccounting()->settings->get( 'bill_due', 15 );
$due_date = date_i18n( 'Y-m-d', strtotime( "+ $due days", wp_date( 'U' ) ) );
?>
<div class="ea-row">
	<div class="ea-col-7">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Bills', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $bill->exists() ) : ?>
			<a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'tab'    => 'bills',
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

	<div class="ea-col-5">

	</div>
</div>
<hr class="wp-header-end">

<form id="ea-bill-form" name="bill" method="post" class="ea-form">
	<div class="ea-card">
		<div class="ea-card__header">
			<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
			<div>
				<?php if ( $bill->exists() ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'action', 'view' ) ); ?>" class="button-secondary">
						<?php esc_html_e( 'View Bill', 'wp-ever-accounting' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="ea-card__inside">

			<div class="ea-row">
				<?php
				eaccounting_vendor_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Vendor', 'wp-ever-accounting' ),
						'name'          => 'vendor_id',
						'placeholder'   => __( 'Select Vendor', 'wp-ever-accounting' ),
						'value'         => $bill->get_vendor_id(),
						'required'      => true,
						'creatable'     => true,
					)
				);
				eaccounting_currency_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Currency', 'wp-ever-accounting' ),
						'name'          => 'currency_code',
						'value'         => $bill->get_currency_code(),
						'required'      => true,
						'creatable'     => true,
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Bill Date', 'wp-ever-accounting' ),
						'name'          => 'issue_date',
						'value'         => $bill->get_issue_date() ? eaccounting_date( $bill->get_issue_date(), 'Y-m-d' ) : date_i18n( 'Y-m-d' ),
						'required'      => true,
						'data_type'     => 'date',
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Due Date', 'wp-ever-accounting' ),
						'name'          => 'due_date',
						'value'         => $bill->get_due_date() ? eaccounting_date( $bill->get_due_date(), 'Y-m-d' ) : $due_date,
						'required'      => true,
						'data_type'     => 'date',
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Bill Number', 'wp-ever-accounting' ),
						'name'          => 'bill_number',
						'value'         => empty( $bill->get_bill_number() ) ? $bill->get_bill_number() : $bill->get_bill_number(),
						'required'      => true,
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Order Number', 'wp-ever-accounting' ),
						'name'          => 'order_number',
						'value'         => $bill->get_order_number(),
						'required'      => false,
					)
				);

				eaccounting_category_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Category', 'wp-ever-accounting' ),
						'name'          => 'category_id',
						'value'         => $bill->get_category_id(),
						'required'      => true,
						'type'          => 'expense',
						'creatable'     => true,
						'ajax_action'   => 'eaccounting_get_expense_categories',
						'modal_id'      => 'ea-modal-add-expense-category',
					)
				);
				?>

			</div>

			<?php
			eaccounting_get_admin_template(
				'bills/bill-items',
				array(
					'bill' => $bill,
				)
			);
			?>

			<div class="ea-row ea-mt-20">
				<?php
				eaccounting_textarea(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Note', 'wp-ever-accounting' ),
						'name'          => 'note',
						'value'         => $bill->exists() ? $bill->get_note() : $note,
						'required'      => false,
					)
				);
				eaccounting_textarea(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Terms & Conditions', 'wp-ever-accounting' ),
						'name'          => 'terms',
						'value'         => $bill->exists() ? $bill->get_terms() : $terms,
						'required'      => false,
					)
				);
				?>
			</div>


		</div>

		<div class="ea-card__footer">
			<?php submit_button( __( 'Submit', 'wp-ever-accounting' ) ); ?>
		</div>
	</div>

	<?php eaccounting_hidden_input( 'id', $bill->get_id() ); ?>
	<?php eaccounting_hidden_input( 'discount', $bill->get_discount() ); ?>
	<?php eaccounting_hidden_input( 'discount_type', $bill->get_discount_type() ); ?>
	<?php eaccounting_hidden_input( 'action', 'eaccounting_edit_bill' ); ?>
	<?php wp_nonce_field( 'ea_edit_bill' ); ?>
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
