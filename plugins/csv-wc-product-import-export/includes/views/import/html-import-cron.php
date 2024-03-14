<?php
global $wpdb;
$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
$size       = size_format( $bytes );
$upload_dir = wp_upload_dir();
$freq = PIECFW_FREQ;
?>
<div class="tool-box">
	<p class="submit"><a class="docs button-primary" href="<?php _e(PIECFW_PLUGIN_DIR_URL . 'assets/file/sample-products.csv'); ?>"><?php _e('Download Sample Products', PIECFW_TRANSLATE_NAME); ?></a>
	<div class="import_form_details">
		<h3 class="title"><img src="<?php _e(PIECFW_PLUGIN_DIR_URL);?>assets/images/clock.png" />&nbsp;<?php _e('CRON Scheduler', PIECFW_TRANSLATE_NAME); ?></h3>
		<div class="description">
			<ol>
				<li><?php _e( 'Upload a CSV file containing product data to import the contents into your shop via Cron Job.', PIECFW_TRANSLATE_NAME ); ?></li>
				<li><?php _e( 'Choose a CSV file to upload, then click Upload a file and save.', PIECFW_TRANSLATE_NAME ); ?></li>
			</ol>
		</div>
		<?php if ( ! empty( $upload_dir['error'] ) ) : ?>
			<div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
			<p><strong><?php _e($upload_dir['error']); ?></strong></p></div>
		<?php else : ?>
			<form id="cron-upload-form" method="post" action="">
				<table class="form-table">
					<tbody>	
						<tr>
							<th>
								<label for="upload"><?php _e( 'Schedule date & time:' ); ?></label>
							</th>
							<td>
								<input type="text" id="start_date" name="start_date" placeholder="yyyy/mm/dd hh:mm" autocomplete="off" readonly />
							</td>
						</tr>	
						<tr>
							<th>
								<label for="upload"><?php _e( 'Schedule frequency:' ); ?></label>
							</th>
							<td>
								<select name="frequency" id="frequency">
									<?php
									foreach($freq as $fk=>$fv){
										?>
										<option value="<?php _e($fk);?>"><?php _e($fv);?></option>
										<?php
									}
									?>
								</select>
							</td>
						</tr>					
						<tr>
							<th>
								<label for="upload"><?php _e( 'Choose a file:' ); ?></label>
							</th>
							<td>
								<input type="file" id="piecfw_import" name="piecfw_import" size="25" onChange="getFileNameWithExt(event)" />
								<small><?php printf( __('Maximum size: %s' ), $size ); ?></small>
							</td>
						</tr>					
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" class="button" id="cron_upload_file" value="<?php esc_attr_e( 'Upload a file and save' ); ?>" />
				</p>
			</form>
		<?php endif; ?>

		<div id="cron_list_table"></div>
		<input type="hidden" id="cron_sort" value="" />
		<input type="hidden" id="cron_order" value="" />
		<input type="hidden" id="cron_paged" value="1" />
	</div>
</div>