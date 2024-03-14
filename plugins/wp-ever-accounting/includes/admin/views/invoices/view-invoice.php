<?php
/**
 * Render Single invoice
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

$invoice_actions = apply_filters(
	'eaccounting_invoice_actions',
	array(
		'status_cancelled' => __( 'Mark Cancelled', 'wp-ever-accounting' ),
	)
);
if ( $invoice->needs_payment() ) {
	$invoice_actions['status_paid'] = __( 'Mark as Paid', 'wp-ever-accounting' );
}

if ( ! in_array( $invoice->get_status( 'edit' ), array( 'paid', 'partial' ), true ) ) {
	$invoice_actions['status_pending'] = __( 'Mark Pending', 'wp-ever-accounting' );
}

$invoice_actions['delete'] = __( 'Delete', 'wp-ever-accounting' );
if ( $invoice->exists() ) {
	add_meta_box( 'invoice_payments', __( 'Invoice Payments', 'wp-ever-accounting' ), array( '\EverAccounting\Admin\Invoice_Actions', 'invoice_payments' ), 'ea_invoice', 'side' );
	add_meta_box( 'invoice_notes', __( 'Invoice Notes', 'wp-ever-accounting' ), array( '\EverAccounting\Admin\Invoice_Actions', 'invoice_notes' ), 'ea_invoice', 'side' );
}
/**
 * Fires after all built-in meta boxes have been added, contextually for the given object.
 *
 * @param Invoice $invoice object.
 *
 * @since 1.1.0
 */
do_action( 'add_meta_boxes_ea_invoice', $invoice );
?>
	<div class="ea-title-section">
		<div>
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Invoices', 'wp-ever-accounting' ); ?></h1>
			<a href="<?php echo esc_url( 'admin.php?page=ea-sales&tab=invoices&action=add' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?></a>

		</div>
	</div>
	<hr class="wp-header-end">

<?php if ( $invoice->exists() && $invoice->is_draft() ) : ?>
	<div class="notice error">
		<p><?php echo wp_kses_post( __( 'This is a <strong>DRAFT</strong> Invoice and will not be reflected until its marked as <strong>pending</strong>.', 'wp-ever-accounting' ) ); ?></p>
	</div>
<?php endif; ?>

	<div id="ea-invoice" class="ea-clearfix">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">

					<div class="ea-card">

						<div class="ea-card__header">
							<div class="ea-card__header-left">

								<div class="ea-document__status <?php echo sanitize_html_class( $invoice->get_status() ); ?>">
									<span><?php echo esc_html( $invoice->get_status_nicename() ); ?></span>
								</div>

							</div>
							<div class="ea-card__header-right">
								<?php if ( ! empty( $invoice_actions ) ) : ?>
									<div class="ea-dropdown">
										<button class="button-secondary ea-dropdown-trigger"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'More', 'wp-ever-accounting' ); ?><span class="dashicons dashicons-arrow-down-alt2"></span></button>
										<a class="button-secondary" href="<?php echo esc_url( $invoice->get_url() ); ?>" target="_blank"><span class="dashicons dashicons-printer"></span> <?php esc_html_e( 'Print', 'wp-ever-accounting' ); ?></a>
										<ul class="ea-dropdown-menu">
											<?php
											do_action( 'eaccounting_before_invoice_actions', $invoice );
											foreach ( $invoice_actions as $action => $title ) {
												echo sprintf(
													'<li><a href="%s">%s</a></li>',
													esc_url(
														wp_nonce_url(
															add_query_arg(
																array(
																	'action'         => 'eaccounting_invoice_action',
																	'invoice_action' => $action,
																	'invoice_id'     => $invoice->get_id(),
																),
																admin_url( 'admin-post.php' )
															),
															'ea_invoice_action'
														)
													),
													esc_html( $title )
												);
											}
											do_action( 'eaccounting_after_invoice_actions', $invoice );
											?>
										</ul>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $invoice->is_editable() ) ) : ?>
									<a href="<?php echo esc_url( add_query_arg( 'action', 'edit' ) ); ?>" class="button-secondary">
										<span class="dashicons dashicons-edit"></span>
										<?php esc_html_e( 'Edit', 'wp-ever-accounting' ); ?>
									</a>
								<?php endif; ?>
								<?php if ( ! empty( $invoice->needs_payment() ) ) : ?>
									<button class="button-primary receive-payment">
										<span class="dashicons dashicons-money-alt"></span>
										<?php esc_html_e( 'Add Payment', 'wp-ever-accounting' ); ?>
									</button>
								<?php endif; ?>
							</div>
						</div>

						<div class="ea-card__inside">
							<?php eaccounting_get_template( 'invoice/invoice.php', array( 'invoice' => $invoice ) ); ?>
						</div>

					</div>

					<?php eaccounting_do_meta_boxes( 'ea_invoice', 'advanced', $invoice ); ?>
				</div><!--/post-body-content-->
				<div id="postbox-container-1" class="postbox-container">
					<?php eaccounting_do_meta_boxes( 'ea_invoice', 'side', $invoice ); ?>
				</div><!--/postbox-container-->

				<div id="postbox-container-2" class="postbox-container">
					<?php eaccounting_do_meta_boxes( 'ea_invoice', 'normal', $invoice ); ?>
				</div><!--/postbox-container-->

			</div><!--/post-body-->
		</div><!--/poststuff-->
	</div><!--/ea-invoice-->

<?php
if ( ! $invoice->is_status( 'paid' ) ) {
	eaccounting_get_admin_template( 'js/modal-invoice-payment', array( 'invoice' => $invoice ) );
}
