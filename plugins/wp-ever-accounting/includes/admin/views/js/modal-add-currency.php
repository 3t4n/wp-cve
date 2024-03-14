<?php
/**
 * Add Currency Modal.
 *
 * @since       1.0.2
 * @subpackage  Admin/Js Templates
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
$currencies = eaccounting_get_global_currencies();
$options    = array();
foreach ( $currencies as $code => $props ) {
	$options[ $code ] = sprintf( '%s (%s)', $props['code'], $props['symbol'] );
}
ksort( $options, SORT_STRING );
$options = array_merge( array( '' => __( 'Select Currency', 'wp-ever-accounting' ) ), $options );
?>
	<script type="text/template" id="ea-modal-add-currency" data-title="<?php esc_html_e( 'Add Currency', 'wp-ever-accounting' ); ?>">
		<form action="" method="post">
			<div class="ea-row">
				<?php
				eaccounting_select(
					array(
						'wrapper_class' => 'ea-col-12',
						'label'         => __( 'Currency Code', 'wp-ever-accounting' ),
						'name'          => 'code',
						'class'         => 'ea-select2',
						'value'         => '',
						'options'       => $options,
						'required'      => true,
					)
				);
				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-12',
						'label'         => __( 'Currency Rate', 'wp-ever-accounting' ),
						'name'          => 'rate',
						'value'         => '',
						'required'      => true,
					)
				);
				eaccounting_hidden_input(
					array(
						'name'  => 'action',
						'value' => 'eaccounting_edit_currency',
					)
				);
				wp_nonce_field( 'ea_edit_currency' );
				?>
			</div>
		</form>
	</script>
<?php
