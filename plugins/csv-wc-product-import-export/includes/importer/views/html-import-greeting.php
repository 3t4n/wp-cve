<div class="tool-box">
	<div class="import_form_details">
		<div class="description">
			<ol>
				<li><?php _e( 'Upload a CSV file containing product data to import the contents into your shop.', PIECFW_TRANSLATE_NAME ); ?></li>
				<li><?php _e( 'Choose a CSV file to upload, then click Upload a file and import.', PIECFW_TRANSLATE_NAME ); ?></li>
			</ol>
		</div>
		<?php if ( ! empty( $upload_dir['error'] ) ) : ?>
			<div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
			<p><strong><?php _e($upload_dir['error']); ?></strong></p></div>
		<?php else : ?>
			<form id="import-upload-form2" method="post" action="">
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label for="upload"><?php _e( 'Choose a file:' ); ?></label>
							</th>
							<td>
								<input type="file" id="upload2" name="import" size="25" onChange="getFileNameWithExt(event)" />
								<input type="hidden" name="max_file_size" value="<?php _e($bytes); ?>" />
								<small><?php printf( __('Maximum size: %s' ), $size ); ?></small>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" class="button" value="<?php esc_attr_e( 'Upload a file and import' ); ?>" />
				</p>
			</form>
			<form style="display: none;" enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php _e(esc_attr(wp_nonce_url($action,'import-upload')));?>">
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label for="upload"><?php _e( 'Choose a file:' ); ?></label>
							</th>
							<td>
								<input type="file" id="upload" name="import" size="25" />
								<input type="hidden" id="filename" name="filename" value="">
								<input type="hidden" name="action" value="save" />
								<input type="hidden" name="max_file_size" value="<?php _e($bytes); ?>" />
								<small><?php printf( __('Maximum size: %s' ), $size ); ?></small>
							</td>
						</tr>
						<?php if ( $this->file_url_import_enabled ) : ?>
						<tr >
							<th>
								<label for="file_url"><?php _e( 'OR enter path to file:', PIECFW_TRANSLATE_NAME ); ?></label>
							</th>
							<td>
								<?php _e(' ' . ABSPATH . ' '); ?><input type="text" id="file_url" name="file_url" size="50" />
							</td>
						</tr>
						<?php endif; ?>
						<tr style="display: none;">
							<th><label><?php _e( 'Delimiter', PIECFW_TRANSLATE_NAME ); ?></label><br/></th>
							<td><input type="text" name="delimiter" placeholder="," size="2" /></td>
						</tr>
						<tr style="display: none;">
							<th><label><?php _e( 'Merge empty cells', PIECFW_TRANSLATE_NAME ); ?></label><br/></th>
							<td><input type="checkbox" name="merge_empty_cells" placeholder="," size="2" /> <span class="description"><?php _e( 'Check this box to merge empty cells - otherwise (when merging) the empty cells will be ignored when importing things such as attributes.', PIECFW_TRANSLATE_NAME ); ?></span></td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" class="button" value="<?php esc_attr_e( 'Upload a file and import' ); ?>" />
				</p>
			</form>
		<?php endif; ?>
	</div>
</div>