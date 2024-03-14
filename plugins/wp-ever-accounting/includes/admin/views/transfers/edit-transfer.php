<?php
/**
 * Admin Transfers Edit Page.
 *
 * @since       1.0.2
 * @subpackage  Admin/Banking/Transfers
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

$transfer_id = filter_input( INPUT_GET, 'transfer_id', FILTER_VALIDATE_INT );
try {
	$transfer = new \EverAccounting\Models\Transfer( $transfer_id );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}
?>
<div class="ea-title-section">
	<div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Transfers', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $transfer->exists() ) : ?>
			<a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'tab'    => 'transfers',
						'page'   => 'ea-sales',
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
			<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?></a>
		<?php endif; ?>
	</div>
</div>
<hr class="wp-header-end">

<form id="ea-transfer-form" method="post" enctype="multipart/form-data">
	<div class="ea-card">
		<div class="ea-card__header">
			<h3 class="ea-card__title"><?php echo $transfer->exists() ? esc_html__( 'Update Transfer', 'wp-ever-accounting' ) : esc_html__( 'Add Transfer', 'wp-ever-accounting' ); ?></h3>
		</div>

		<div class="ea-card__inside">

			<div class="ea-row">
				<?php
				eaccounting_account_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => esc_html__( 'From Account', 'wp-ever-accounting' ),
						'name'          => 'from_account_id',
						'value'         => $transfer->get_from_account_id(),
						'required'      => true,
						'placeholder'   => esc_html__( 'Select Account', 'wp-ever-accounting' ),
						'creatable'     => true,
					)
				);

				eaccounting_account_dropdown(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => esc_html__( 'To Account', 'wp-ever-accounting' ),
						'name'          => 'to_account_id',
						'value'         => $transfer->get_to_account_id(),
						'default'       => '',
						'required'      => true,
						'placeholder'   => esc_html__( 'Select Account', 'wp-ever-accounting' ),
						'creatable'     => true,
					)
				);

				eaccounting_text_input(
					array(
						'label'         => esc_html__( 'Amount', 'wp-ever-accounting' ),
						'name'          => 'amount',
						'value'         => $transfer->get_amount(),
						'data_type'     => 'price',
						'required'      => true,
						'wrapper_class' => 'ea-col-6',
						'placeholder'   => esc_html__( 'Enter amount', 'wp-ever-accounting' ),
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => esc_html__( 'Date', 'wp-ever-accounting' ),
						'name'          => 'date',
						'placeholder'   => esc_html__( 'Enter date', 'wp-ever-accounting' ),
						'data_type'     => 'date',
						'value'         => $transfer->get_date() ? eaccounting_date( $transfer->get_date(), 'Y-m-d' ) : null,
						'required'      => true,
					)
				);
				eaccounting_payment_method_dropdown(
					array(
						'label'         => esc_html__( 'Payment Method', 'wp-ever-accounting' ),
						'name'          => 'payment_method',
						'placeholder'   => esc_html__( 'Enter payment method', 'wp-ever-accounting' ),
						'wrapper_class' => 'ea-col-6',
						'required'      => true,
						'value'         => $transfer->get_payment_method(),
					)
				);
				eaccounting_text_input(
					array(
						'label'         => esc_html__( 'Reference', 'wp-ever-accounting' ),
						'name'          => 'reference',
						'value'         => $transfer->get_reference(),
						'required'      => false,
						'wrapper_class' => 'ea-col-6',
						'placeholder'   => esc_html__( 'Enter reference', 'wp-ever-accounting' ),
					)
				);
				eaccounting_textarea(
					array(
						'label'         => esc_html__( 'Description', 'wp-ever-accounting' ),
						'name'          => 'description',
						'value'         => $transfer->get_description(),
						'required'      => false,
						'wrapper_class' => 'ea-col-12',
						'placeholder'   => esc_html__( 'Enter description', 'wp-ever-accounting' ),
					)
				);

				eaccounting_hidden_input(
					array(
						'name'  => 'id',
						'value' => $transfer->get_id(),
					)
				);
				eaccounting_hidden_input(
					array(
						'name'  => 'action',
						'value' => 'eaccounting_edit_transfer',
					)
				);
				?>
			</div>


		</div>
		<div class="ea-card__footer">
			<?php

			wp_nonce_field( 'ea_edit_transfer' );

			submit_button( esc_html__( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
			?>
		</div>
	</div>
</form>
