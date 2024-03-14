<?php
/**
 * Template file to set Search fields
 *
 * @package YITH\Search\Views
 */

defined( 'ABSPATH' ) || exit;
$search_fields = ywcas()->settings->get_search_fields();

?>
<div class="ywcas-search-field-wrapper yith-plugin-ui--boxed-wp-list-style">
	<?php wp_nonce_field( 'ywcas_edit_search_field', '_ywcas_nonce' ); ?>

	<table id="search-fields" class="wp-list-table fixed table-view-list">
		<thead>
		<tr>
			<th id="field"
				class="colum-title"><?php echo esc_html_x( 'Field', 'Name of column field', 'yith-woocommerce-ajax-search' ); ?></th>

			<th id="priority-zone"
                class="colum-title"><?php echo esc_html_x( 'Priority', 'priority of the field', 'yith-woocommerce-ajax-search' ); ?></th>
			<th id="actions" class="colum-title"></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if ( $search_fields ) {
			foreach ( $search_fields as $key => $field ) {
				ywcas_get_view(
					'/custom-fields/types/content/search-input.php',
					array(
						'field' => $field,
						'key'   => $key,
					)
				);
			}
		}

		?>
		</tbody>
	</table>
	<div class="ywcas-add-field">
		<?php echo esc_html_x( '+ Add field', 'Add search field button label', 'yith-woocommerce-ajax-search' ); ?>
	</div>
</div>
<script type="text/html" id="tmpl-ywcas-search-fields">
	<?php
	$template_options = array(
		'field'          => array(
			'type'     => 'name',
			'priority' => 1,
		),
		'is_placeholder' => true,
	);
	ywcas_get_view( 'custom-fields/types/content/search-input.php', $template_options );
	?>
</script>
