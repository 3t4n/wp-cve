<?php if (! defined('ABSPATH')) exit; ?>
<div id='<?php echo WCSRI_SLUG; ?>-container' class='wrap'>
    <h2><?php _e( 'WC Shipping Rates Importer' ); ?></h2>
    <?php 
		$notice = '';
		if ( !empty( $admin_notice ) ) { 
			echo '<div class="notice notice-' . $admin_notice[0] . '"><p>' . $admin_notice[1] . '</p></div>';
        } 
	?>
<?php if ($wc_installed) { ?>
	<?php if ( !empty( $db_stats ) ) { ?>
	<h3><?php _e( 'Current Shipping Table Statistics' ); ?></h3>
	<table class='form-table permalink-structure'>
		<tbody>
			<tr>
				<th><label><?php _e( 'Table' ); ?></label></td>
				<th><label><?php _e( '# of Rows in DB' ); ?></label></td>
				<th><label><?php _e( '# of Rows in Upload' ); ?></label></td>
			</tr>
			<?php 
				$statistics = '';
				foreach ( $db_stats AS $table => $count ) {
					if ( null === $count ) $count = __( 'Not Installed/Found' );
			?>		
			<tr>
				<td><label><?php echo $table; ?></label></td>
				<td><label><?php echo $count; ?></label></td>
				<td><label><?php echo @$upload_stats[$table]; ?></label></td>
			</tr>
			<?php			
				}
			?>
	</table>
	<?php } ?>
	
	<div>
		<hr>
		<?php if ( !empty ( $import_file ) ) { ?>
		<p>
			<form id='<?php echo WCSRI_SLUG; ?>-form-import' method='post'>
				<input type='hidden' name='<?php echo WCSRI_SLUG; ?>-action' value='import'>
				<input type='hidden' name='import_file' value='<?php echo $import_file; ?>'>
				<input type='submit' name='import' value='<?php _e( 'Import' ); ?>' class='button button-primary'>
			</form>
			<p><?php _e( 'Execute Import Process.' ); ?></p>
			<p><?php _e( 'WARNING: This will wipe all your current zones and table rate database. Click Export first to generate a backup file.' ); ?></p>
		</p>
		<hr>
		<?php } ?>
		<p>
			<form id='<?php echo WCSRI_SLUG; ?>-form-upload' method='post' enctype='multipart/form-data'>
				<input type='hidden' name='<?php echo WCSRI_SLUG; ?>-action' value='upload'>
				<p><input type='file' id='<?php echo WCSRI_FILE_UPLOAD_NAME; ?>' name='<?php echo WCSRI_FILE_UPLOAD_NAME; ?>'></p>
				<input type='button' value='<?php _e( 'Upload' ); ?>' class='button button-primary'<?php echo ($importDisabled) ? ' disabled="disabled"' : ''; ?>>
			</form>
			<p><?php _e( 'Upload shipping FROM a valid json file for Importing.' ); ?></p>
		</p>
		<hr>
		
		<p>
			<form id='<?php echo WCSRI_SLUG; ?>-form-export' method='post'>
				<input type='hidden' name='<?php echo WCSRI_SLUG; ?>-action' value='export'>
				<input type='submit' value='<?php _e( 'Export' ); ?>' class='button button-primary'<?php echo ($exportDisabled) ? ' disabled="disabled"' : ''; ?>>
			</form>	
		<p><?php _e( 'Export shipping TO a valid json export file.' ); ?></p>
		</p>
		<hr>
	</div>

<script type='text/javascript'>
(function($) {
	$('#<?php echo WCSRI_SLUG; ?>-form-upload input[type="button"]').click(function() {
		var ext = $('#<?php echo WCSRI_FILE_UPLOAD_NAME; ?>').val().split('.').pop().toLowerCase();
		if (ext != 'json') {
			alert('<?php _e( 'Please upload a valid .json file.' ); ?>');
			return;
		}
		$('#<?php echo WCSRI_SLUG; ?>-form-upload').submit();
	});
})(jQuery);
</script>
<?php } else { ?>
<div><?php _e( 'WooCommerce is required for WC Shipping Rates Importer to function properly. Please install and activate WooCommerce first.' ); ?></div>
<?php }  ?>
</div>