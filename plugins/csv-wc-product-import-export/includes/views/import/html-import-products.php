<div class="tool-box">
	<h3 class="title"><img src="<?php _e(PIECFW_PLUGIN_DIR_URL);?>assets/images/import.png" />&nbsp;<?php _e('Product Import', PIECFW_TRANSLATE_NAME); ?></h3>
	<div class="description">
		<ol>
			<li><?php _e('Import simple, grouped, external and variable products into WooCommerce using this tool.', PIECFW_TRANSLATE_NAME); ?></li>
			<li><?php _e('Upload a CSV from your computer. Click import your CSV as new products (existing products will be skipped).'); ?></li>
			<li><?php _e('Importing requires the <code>post_title</code> and <code>sku</code> columns.', PIECFW_TRANSLATE_NAME); ?></li>
			<li><?php _e('Variation must be mapped to a variable product via a <code>parent_sku</code> column in order to import successfully.', PIECFW_TRANSLATE_NAME); ?></li>
		</ol>
	</div>
	<p class="submit"><a class="button" href="<?php _e(admin_url('admin.php?import=piecfw')); ?>"><?php _e('Click next >', PIECFW_TRANSLATE_NAME); ?></a></p>
</div>