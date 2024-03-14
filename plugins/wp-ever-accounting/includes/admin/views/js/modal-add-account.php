<?php
/**
 * Add Account Modal.
 *
 * @since       1.0.2
 * @subpackage  Admin/Js Templates
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
?>
<script type="text/template" id="ea-modal-add-account" data-title="<?php esc_html_e( 'Add Account', 'wp-ever-accounting' ); ?>">
	<form id="ea-modal-account-form" action="" method="post">
		<?php
		eaccounting_text_input(
			array(
				'label'    => __( 'Account Name', 'wp-ever-accounting' ),
				'name'     => 'name',
				'value'    => '',
				'required' => true,
			)
		);
		eaccounting_text_input(
			array(
				'label'    => __( 'Account Number', 'wp-ever-accounting' ),
				'name'     => 'number',
				'value'    => '',
				'required' => true,
			)
		);

		eaccounting_currency_dropdown(
			array(
				'label'       => __( 'Account Currency', 'wp-ever-accounting' ),
				'name'        => 'currency_code',
				'value'       => '',
				'placeholder' => __( 'Select Currency', 'wp-ever-accounting' ),
				'ajax'        => true,
				'type'        => 'currency',
				'creatable'   => 'no',
				'required'    => true,
			)
		);

		eaccounting_text_input(
			array(
				'label'    => __( 'Opening Balance', 'wp-ever-accounting' ),
				'name'     => 'opening_balance',
				'value'    => '',
				'default'  => '0.00',
				'required' => true,
			)
		);
		eaccounting_hidden_input(
			array(
				'name'  => 'action',
				'value' => 'eaccounting_edit_account',
			)
		);

		wp_nonce_field( 'ea_edit_account' );
		submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
		?>
	</form>
</script>
