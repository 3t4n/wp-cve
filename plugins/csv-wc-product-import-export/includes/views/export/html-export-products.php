<div class="tool-box">
	<h3 class="title"><img src="<?php _e(PIECFW_PLUGIN_DIR_URL);?>assets/images/export.png" />&nbsp;<?php _e('Product Export', PIECFW_TRANSLATE_NAME); ?></h3>
	<div class="description">
		<ol>
			<li><?php _e('Export your simple, grouped, external and variable products using this tool. This exported CSV will be in an importable format.', PIECFW_TRANSLATE_NAME); ?></li>
			<li><?php _e('Click export to save your products to your computer.', PIECFW_TRANSLATE_NAME); ?></li>
		</ol>
	</div>
	<form action="<?php _e(admin_url('admin.php?page=piecfw_import_export&action=export')); ?>" method="post">
		<table class="form-table">
			<tr style="display: none;">
				<th>
					<label for="v_limit"><?php _e( 'Limit', PIECFW_TRANSLATE_NAME ); ?></label>
				</th>
				<td>
					<input type="text" name="limit" id="v_limit" placeholder="<?php _e('Unlimited', PIECFW_TRANSLATE_NAME); ?>" class="input-text" />
				</td>
			</tr>
			<tr style="display: none;">
				<th>
					<label for="v_offset"><?php _e( 'Offset', PIECFW_TRANSLATE_NAME ); ?></label>
				</th>
				<td>
					<input type="text" name="offset" id="v_offset" placeholder="<?php _e('0', PIECFW_TRANSLATE_NAME); ?>" class="input-text" />
				</td>
			</tr>
			<tr style="display: none;">
				<th>
					<label for="v_columns"><?php _e( 'Columns', PIECFW_TRANSLATE_NAME ); ?></label>
				</th>
				<td>
					<select id="v_columns" name="columns[]" data-placeholder="<?php _e('All Columns', PIECFW_TRANSLATE_NAME); ?>" class="wc-enhanced-select" multiple="multiple">
						<?php
							foreach ($post_columns as $key => $column) {
								_e('<option value="'.$key.'">'.$column.'</option>');
							}
							_e('<option value="images">'.__('Images (featured and gallery)', PIECFW_TRANSLATE_NAME).'</option>');
							_e('<option value="file_paths">'.__('Downloadable file paths', PIECFW_TRANSLATE_NAME).'</option>');
							_e('<option value="taxonomies">'.__('Taxonomies (cat/tags/shipping-class)', PIECFW_TRANSLATE_NAME).'</option>');
							_e('<option value="attributes">'.__('Attributes', PIECFW_TRANSLATE_NAME).'</option>');
							_e('<option value="meta">'.__('Meta (custom fields)', PIECFW_TRANSLATE_NAME).'</option>');

							if ( function_exists( 'woocommerce_gpf_install' ) )
								_e('<option value="gpf">'.__('Google Product Feed fields', PIECFW_TRANSLATE_NAME).'</option>');
						?>
						</select>
				</td>
			</tr>
			<tr style="display: none;">
				<th>
					<label for="v_include_hidden_meta"><?php _e( 'Include hidden meta data', PIECFW_TRANSLATE_NAME ); ?></label>
				</th>
				<td>
					<input type="checkbox" name="include_hidden_meta" id="v_include_hidden_meta" class="checkbox" />
				</td>
			</tr>
		</table>

		<p class="submit"><input type="submit" class="button" value="<?php _e('Click to export', PIECFW_TRANSLATE_NAME); ?>" /></p>

	</form>
</div>