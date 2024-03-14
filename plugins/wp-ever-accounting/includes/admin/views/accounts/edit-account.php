<?php
/**
 * Admin Account Edit Page.
 * Page: Banking
 * Tab: Accounts
 *
 * @package     EverAccounting
 * @subpackage  Admin/View/Accounts
 * @since       1.0.2
 *
 * @var int $account_id
 */

defined( 'ABSPATH' ) || exit();

try {
	$account = new \EverAccounting\Models\Account( $account_id );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}

$title   = $account->exists() ? __( 'Update Account', 'wp-ever-accounting' ) : __( 'Add Account', 'wp-ever-accounting' );
$add_new = add_query_arg(
	array(
		'tab'    => 'accounts',
		'page'   => 'ea-banking',
		'action' => 'add',
	),
	admin_url( 'admin.php' )
);
?>
	<div class="ea-title-section">
		<div>
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Accounts', 'wp-ever-accounting' ); ?></h1>
			<?php if ( $account->exists() ) : ?>
				<a href="<?php echo esc_url( $add_new ); ?>" class="page-title-action">
					<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<hr class="wp-header-end">

	<form id="ea-account-form" method="post">
		<div class="ea-card">
			<div class="ea-card__header">
				<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
				<?php if ( $account->exists() ) : ?>
					<div>
						<a href="<?php echo esc_url( add_query_arg( 'action', 'view' ) ); ?>" class="button-secondary">
							<?php esc_html_e( 'View Account', 'wp-ever-accounting' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<div class="ea-card__body">
				<div class="ea-card__inside">

					<div class="ea-row">
						<?php
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Account Name', 'wp-ever-accounting' ),
								'name'          => 'name',
								'value'         => $account->get_name( 'edit' ),
								'required'      => true,
								'placeholder'   => __( 'Enter account name', 'wp-ever-accounting' ),
							)
						);
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Account Number', 'wp-ever-accounting' ),
								'name'          => 'number',
								'value'         => $account->get_number( 'edit' ),
								'required'      => true,
								'placeholder'   => __( 'Enter account number', 'wp-ever-accounting' ),
							)
						);
						eaccounting_currency_dropdown(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Account Currency', 'wp-ever-accounting' ),
								'name'          => 'currency_code',
								'value'         => $account->get_currency_code(),
								'required'      => true,
								'creatable'     => true,
							)
						);
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Opening Balance', 'wp-ever-accounting' ),
								'name'          => 'opening_balance',
								'value'         => $account->get_opening_balance(),
								'default'       => '0.00',
							)
						);
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Bank Name', 'wp-ever-accounting' ),
								'name'          => 'bank_name',
								'value'         => $account->get_bank_name( 'edit' ),
								'placeholder'   => __( 'Enter bank name', 'wp-ever-accounting' ),
							)
						);
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Bank Phone', 'wp-ever-accounting' ),
								'name'          => 'bank_phone',
								'value'         => $account->get_bank_phone( 'edit' ),
								'placeholder'   => __( 'Enter bank phone', 'wp-ever-accounting' ),
							)
						);
						eaccounting_textarea(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Bank Address', 'wp-ever-accounting' ),
								'name'          => 'bank_address',
								'value'         => $account->get_bank_address( 'edit' ),
								'placeholder'   => __( 'Enter bank address', 'wp-ever-accounting' ),
							)
						);
						eaccounting_file_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Photo', 'wp-ever-accounting' ),
								'name'          => 'thumbnail_id',
								'allowed-types' => 'jpg,jpeg,png',
								'value'         => $account->get_thumbnail_id(),
							)
						);
						eaccounting_hidden_input(
							array(
								'name'  => 'id',
								'value' => $account->get_id(),
							)
						);
						eaccounting_hidden_input(
							array(
								'name'  => 'action',
								'value' => 'eaccounting_edit_account',
							)
						);
						?>
					</div>
				</div>
			</div>
			<div class="ea-card__footer">
				<?php

				wp_nonce_field( 'ea_edit_account' );
				submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );

				?>
			</div>
			<?php if ( $account->exists() ) : ?>
				<div class="ea-card__footer">
					<p class="description"><span class="dashicons dashicons-info"></span>
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %s date and %s name */
								esc_html__( 'The account was created at %1$s by %2$s', 'wp-ever-accounting' ),
								eaccounting_date( $account->get_date_created(), 'F m, Y H:i a' ),
								eaccounting_get_full_name( $account->get_creator_id() )
							)
						);
						?>
					</p>
				</div>
			<?php endif; ?>


		</div>
	</form>
<?php
eaccounting_enqueue_js(
	"
	jQuery('#ea-account-form #opening_balance').inputmask('decimal', {
			alias: 'numeric',
			groupSeparator: '" . $account->get_currency_thousand_separator() . "',
			autoGroup: true,
			digits: '" . $account->get_currency_precision() . "',
			radixPoint: '" . $account->get_currency_decimal_separator() . "',
			digitsOptional: false,
			allowMinus: false,
			prefix: '" . $account->get_currency_symbol() . "',
			placeholder: '0.000',
			rightAlign: 0,
			autoUnmask: true
		});
"
);
