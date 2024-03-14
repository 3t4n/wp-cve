<?php
/**
 * Admin view Bill.
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

$bill_actions = apply_filters(
	'eaccounting_bill_actions',
	array(
		'status_cancelled' => __( 'Mark as Cancelled', 'wp-ever-accounting' ),
	)
);

if ( ! in_array( $bill->get_status( 'edit' ), array( 'paid', 'partial' ), true ) ) {
	$bill_actions['status_received'] = __( 'Mark as Received', 'wp-ever-accounting' );
}

if ( $bill->needs_payment() ) {
	$bill_actions['status_paid'] = __( 'Mark as Paid', 'wp-ever-accounting' );
}
$bill_actions['delete'] = __( 'Delete', 'wp-ever-accounting' );
if ( $bill->exists() ) {
	add_meta_box( 'bill_payments', __( 'Bill Payments', 'wp-ever-accounting' ), array( '\EverAccounting\Admin\Bill_Actions', 'bill_payments' ), 'ea_bill', 'side' );
	add_meta_box( 'bill_notes', __( 'Bill Notes', 'wp-ever-accounting' ), array( '\EverAccounting\Admin\Bill_Actions', 'bill_notes' ), 'ea_bill', 'side' );
}
/**
 * Fires after all built-in meta boxes have been added, contextually for the given object.
 *
 * @param Bill $bill object.
 *
 * @since 1.1.0
 */
do_action( 'add_meta_boxes_ea_bill', $bill );
?>
	<div class="ea-title-section">
		<div>
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Bills', 'wp-ever-accounting' ); ?></h1>
			<a href="<?php echo esc_url( 'admin.php?page=ea-expenses&tab=bills&action=add' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?></a>
		</div>
	</div>
	<hr class="wp-header-end">

<?php if ( $bill->exists() && $bill->is_draft() ) : ?>
	<div class="notice error">
		<p><?php echo wp_kses_post( __( 'This is a <strong>DRAFT</strong> bill and will not be reflected until its marked as <strong>received</strong>.', 'wp-ever-accounting' ) ); ?></p>
	</div>
<?php endif; ?>

	<div id="ea-bill" class="ea-clearfix">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">

					<div class="ea-card">

						<div class="ea-card__header">
							<div class="ea-card__header-left">

								<div class="ea-document__status <?php echo sanitize_html_class( $bill->get_status() ); ?>">
									<span><?php echo esc_html( $bill->get_status_nicename() ); ?></span>
								</div>

							</div>
							<div class="ea-card__header-right">
								<?php if ( ! empty( $bill_actions ) ) : ?>
									<div class="ea-dropdown">
										<button class="button-secondary ea-dropdown-trigger"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'More', 'wp-ever-accounting' ); ?><span class="dashicons dashicons-arrow-down-alt2"></span></button>
										<a class="button-secondary" href="<?php echo esc_url( $bill->get_url() ); ?>" target="_blank"><span class="dashicons dashicons-printer"></span> <?php esc_html_e( 'Print', 'wp-ever-accounting' ); ?></a>
										<ul class="ea-dropdown-menu">
											<?php
											foreach ( $bill_actions as $action => $title ) {
												echo sprintf(
													'<li><a href="%s">%s</a></li>',
													esc_attr(
														wp_nonce_url(
															add_query_arg(
																array(
																	'action'      => 'eaccounting_bill_action',
																	'bill_action' => $action,
																	'bill_id'     => $bill->get_id(),
																),
																admin_url( 'admin-post.php' )
															),
															'ea_bill_action'
														)
													),
													esc_html( $title )
												);
											}
											?>
										</ul>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $bill->is_editable() ) ) : ?>
									<a href="<?php echo esc_url( add_query_arg( 'action', 'edit' ) ); ?>" class="button-secondary">
										<span class="dashicons dashicons-edit"></span>
										<?php esc_html_e( 'Edit', 'wp-ever-accounting' ); ?>
									</a>
								<?php endif; ?>
								<?php if ( ! empty( $bill->needs_payment() ) ) : ?>
									<button class="button-primary add-payment">
										<span class="dashicons dashicons-money-alt"></span>
										<?php esc_html_e( 'Add Payment', 'wp-ever-accounting' ); ?>
									</button>
								<?php endif; ?>
							</div>
						</div>

						<div class="ea-card__inside">
							<?php eaccounting_get_template( 'bill/bill.php', array( 'bill' => $bill ) ); ?>
						</div>

					</div>

					<?php eaccounting_do_meta_boxes( 'ea_bill', 'advanced', $bill ); ?>
				</div><!--/post-body-content-->
				<div id="postbox-container-1" class="postbox-container">
					<?php eaccounting_do_meta_boxes( 'ea_bill', 'side', $bill ); ?>
				</div><!--/postbox-container-->

				<div id="postbox-container-2" class="postbox-container">
					<?php eaccounting_do_meta_boxes( 'ea_bill', 'normal', $bill ); ?>
				</div><!--/postbox-container-->

			</div><!--/post-body-->
		</div><!--/poststuff-->
	</div><!--/ea-bill-->
<?php
if ( $bill->needs_payment() ) {
	eaccounting_get_admin_template( 'js/modal-bill-payment', array( 'bill' => $bill ) );
}
