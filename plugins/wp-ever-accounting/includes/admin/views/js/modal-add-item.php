<?php
/**
 * Add Item Modal.
 *
 * @since       1.1.0s
 * @subpackage  Admin/Js Templates
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
?>
<script type="text/template" id="ea-modal-add-item" data-title="<?php esc_html_e( 'Add Item', 'wp-ever-accounting' ); ?>">
	<form id="ea-modal-item-form" action="" method="post">
		<?php
		eaccounting_text_input(
			array(
				'wrapper_class' => 'ea-col-6',
				'label'         => __( 'Name', 'wp-ever-accounting' ),
				'name'          => 'name',
				'placeholder'   => __( 'Enter Name', 'wp-ever-accounting' ),
				'tip'           => __( 'Enter Name', 'wp-ever-accounting' ),
				'required'      => true,
			)
		);

		eaccounting_text_input(
			array(
				'wrapper_class' => 'ea-col-6',
				'label'         => __( 'Sale price', 'wp-ever-accounting' ),
				'name'          => 'sale_price',
				'placeholder'   => __( 'Enter Sale price', 'wp-ever-accounting' ),
				'required'      => true,
			)
		);

		eaccounting_text_input(
			array(
				'wrapper_class' => 'ea-col-6',
				'label'         => __( 'Purchase price', 'wp-ever-accounting' ),
				'name'          => 'purchase_price',
				'placeholder'   => __( 'Enter Purchase price', 'wp-ever-accounting' ),
				'required'      => true,
			)
		);

		eaccounting_category_dropdown(
			array(
				'wrapper_class' => 'ea-col-6',
				'label'         => __( 'Category', 'wp-ever-accounting' ),
				'name'          => 'category_id',
				'required'      => false,
				'type'          => 'item',
				'creatable'     => false,
				'ajax_action'   => 'eaccounting_get_item_categories',
			)
		);

		eaccounting_hidden_input(
			array(
				'name'  => 'action',
				'value' => 'eaccounting_edit_item',
			)
		);

		wp_nonce_field( 'ea_edit_item' );
		submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
		?>
	</form>
</script>
