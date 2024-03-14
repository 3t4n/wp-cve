<?php
	$lad_nonce = wp_create_nonce( 'load-slider-data' . $slider_id );
	$delete_nonce = wp_create_nonce( 'delete-slider' . $slider_id );
	$duplicate_nonce = wp_create_nonce( 'duplicate-slider' . $slider_id );
	$export_nonce = wp_create_nonce( 'export-slider' . $slider_id );

	$paging_params = $total_pages > 1 ? '&pages=' . $total_pages . '&sp_page=' . $current_page : '';

	$edit_url = admin_url( 'admin.php?page=sliderpro&id=' . $slider_id . '&action=edit' );
	$preview_url = admin_url( 'admin.php?page=sliderpro&id=' . $slider_id . '&action=preview' ) . '&lad_nonce=' . $lad_nonce;
	$delete_url = admin_url( 'admin.php?page=sliderpro&id=' . $slider_id . '&action=delete' ) . '&da_nonce=' . $delete_nonce;
	$duplicate_url = admin_url( 'admin.php?page=sliderpro&id=' . $slider_id . '&action=duplicate' ) . '&dua_nonce=' . $duplicate_nonce . $paging_params;
	$export_url = admin_url( 'admin.php?page=sliderpro&id=' . $slider_id . '&action=export' ) . '&ea_nonce=' . $export_nonce;
?>
<tr class="slider-row">
	<td><?php echo esc_html( $slider_id ); ?></td>
	<td><?php echo esc_html( $slider_name ); ?></td>
	<td><?php echo '[sliderpro id="' . esc_html( $slider_id ) . '"]'; ?></td>
	<td><?php echo $slider_created; ?></td>
	<td><?php echo $slider_modified; ?></td>
	<td>
		<a href="<?php echo esc_url( $edit_url ); ?>"><?php _e( 'Edit', 'sliderpro' ); ?></a> |
		<a class="preview-slider" href="<?php echo esc_url( $preview_url ); ?>"><?php _e( 'Preview', 'sliderpro' ); ?></a> |
		<a class="delete-slider" href="<?php echo esc_url( $delete_url ); ?>"><?php _e( 'Delete', 'sliderpro' ); ?></a> |
		<a class="duplicate-slider" href="<?php echo esc_url( $duplicate_url ); ?>"><?php _e( 'Duplicate', 'sliderpro' ); ?></a> |
		<a class="export-slider" href="<?php echo esc_url( $export_url ); ?>"><?php _e( 'Export', 'sliderpro' ); ?></a>
	</td>
</tr>