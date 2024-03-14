<?php
/**
 * Admin Currency Edit Page.
 *
 * @package     EverAccounting
 * @subpackage  Admin/Settings/Currencies
 * @since       1.0.2
 */

defined( 'ABSPATH' ) || exit();
$currency_code = filter_input( INPUT_GET, 'currency_code', FILTER_SANITIZE_STRING );
try {
	$currency = new \EverAccounting\Models\Currency( $currency_code );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}
$currencies = eaccounting_get_global_currencies();
$options    = array();
foreach ( $currencies as $code => $props ) {
	$options[ $code ] = sprintf( '%s (%s)', $props['code'], $props['symbol'] );
}
$add_new = add_query_arg(
	array(
		'tab'    => 'currencies',
		'page'   => 'ea-settings',
		'action' => 'add',
	),
	admin_url( 'admin.php' )
);
?>
<div class="ea-title-section">
	<div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Currencies', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $currency->exists() ) : ?>
			<a href="<?php echo esc_url( $add_new ); ?>" class="page-title-action">
				<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?></a>
		<?php endif; ?>

	</div>
</div>
<hr class="wp-header-end">

<div class="notice notice-warning notice-large">
	<?php
	echo sprintf(
		'<p><strong>%s:</strong> %s',
		esc_html__( 'Note', 'wp-ever-accounting' ),
		esc_html__(
			'Default currency rate should be always 1 & additional currency rates should be equivalent of default currency.
		e.g. If USD is your default currency then USD rate is 1 & GBP rate will be 0.77',
			'wp-ever-accounting'
		)
	);
	?>
</div>
<form id="ea-currency-form" method="post">
<div class="ea-card">
	<div class="ea-card__header">
		<h3 class="ea-card__title"><?php echo $currency->exists() ? esc_html__( 'Update Currency', 'wp-ever-accounting' ) : esc_html__( 'Add Currency', 'wp-ever-accounting' ); ?></h3>
	</div>

	<div class="ea-card__inside">

			<div class="ea-row">
				<?php
				eaccounting_select2(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Currency Code', 'wp-ever-accounting' ),
						'name'          => 'code',
						'value'         => $currency->get_code( 'edit' ),
						'options'       => array( '' => __( 'Select', 'wp-ever-accounting' ) ) + $options,
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Name', 'wp-ever-accounting' ),
						'name'          => 'name',
						'placeholder'   => __( 'Enter Name', 'wp-ever-accounting' ),
						'value'         => $currency->get_name(),
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Currency Rate', 'wp-ever-accounting' ),
						'name'          => 'rate',
						'tooltip'       => __( 'For better precision use full conversion rate. Like 1 USD = 1.2635835 CAD', 'wp-ever-accounting' ),
						'value'         => $currency->get_rate( 'edit' ),
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Precision', 'wp-ever-accounting' ),
						'name'          => 'precision',
						'type'          => 'number',
						'value'         => $currency->get_precision( 'edit' ),
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Symbol', 'wp-ever-accounting' ),
						'name'          => 'symbol',
						'value'         => $currency->get_symbol( 'edit' ),
						'required'      => true,
					)
				);
				eaccounting_select2(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Symbol Position', 'wp-ever-accounting' ),
						'name'          => 'position',
						'value'         => $currency->get_position( 'edit' ),
						'options'       => array(
							'before' => __( 'Before', 'wp-ever-accounting' ),
							'after'  => __( 'After', 'wp-ever-accounting' ),
						),
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Decimal Separator', 'wp-ever-accounting' ),
						'name'          => 'decimal_separator',
						'value'         => $currency->get_decimal_separator( 'edit' ),
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Thousands Separator', 'wp-ever-accounting' ),
						'name'          => 'thousand_separator',
						'value'         => $currency->get_thousand_separator( 'edit' ),
						'required'      => true,
					)
				);
				eaccounting_hidden_input(
					array(
						'name'  => 'id',
						'value' => $currency->get_id(),
					)
				);

				eaccounting_hidden_input(
					array(
						'name'  => 'action',
						'value' => 'eaccounting_edit_currency',
					)
				);

				?>
			</div>
	</div>
	<div class="ea-card__footer">
		<?php
		wp_nonce_field( 'ea_edit_currency' );
		submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
		?>
	</div>
</div>
</form>
