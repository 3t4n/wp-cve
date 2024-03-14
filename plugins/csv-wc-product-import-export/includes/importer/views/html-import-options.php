<div class="tool-box">
	<form action="<?php _e(admin_url( 'admin.php?import=' . $this->import_page . '&step=2&merge=' . $merge )); ?>" method="post">
		<?php wp_nonce_field( 'import-woocommerce' ); ?>
		<input type="hidden" name="import_id" value="<?php _e($this->id); ?>" />
		<?php if ( $this->file_url_import_enabled ) : ?>
		<input type="hidden" name="import_url" value="<?php _e($this->file_url); ?>" />
		<?php endif; ?>
		<h3><?php _e( 'Mapping Fields', PIECFW_TRANSLATE_NAME ); ?></h3>
		<div class="description">
			<ol><li><?php _e( 'You can map your imported columns to product fields.', PIECFW_TRANSLATE_NAME ); ?></p></li>
			</ol>
		</div><br>
		<table class="widefat widefat_importer">
			<thead>
				<tr>
					<th><?php _e( 'Mapping to', PIECFW_TRANSLATE_NAME ); ?></th>
					<th><?php _e( 'CSV Heading Column', PIECFW_TRANSLATE_NAME ); ?></th>
					<th><?php _e( 'CSV Example Value', PIECFW_TRANSLATE_NAME ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $row as $key => $value ) : ?>
				<tr>
					<td width="25%">
						<?php
							if ( strstr( $key, 'tax:' ) ) {

								$column = trim( str_replace( 'tax:', '', $key ) );
								printf(__('Taxonomy: <strong>%s</strong>', PIECFW_TRANSLATE_NAME), $column);

							} elseif ( strstr( $key, 'meta:' ) ) {

								$column = trim( str_replace( 'meta:', '', $key ) );
								printf(__('Custom Field: <strong>%s</strong>', PIECFW_TRANSLATE_NAME), $column);

							} elseif ( strstr( $key, 'attribute:' ) ) {

								$column = trim( str_replace( 'attribute:', '', $key ) );
								printf(__('Product Attribute: <strong>%s</strong>', PIECFW_TRANSLATE_NAME), sanitize_title( $column ) );

							} elseif ( strstr( $key, 'attribute_data:' ) ) {

								$column = trim( str_replace( 'attribute_data:', '', $key ) );
								printf(__('Product Attribute Data: <strong>%s</strong>', PIECFW_TRANSLATE_NAME), sanitize_title( $column ) );

							} elseif ( strstr( $key, 'attribute_default:' ) ) {

								$column = trim( str_replace( 'attribute_default:', '', $key ) );
								printf(__('Product Attribute default value: <strong>%s</strong>', PIECFW_TRANSLATE_NAME), sanitize_title( $column ) );

							} else {
								?>
								<select name="map_to[<?php _e($key); ?>]">
									<option value=""><?php _e( 'Do not import', PIECFW_TRANSLATE_NAME ); ?></option>
									<option value="import_as_images" <?php selected( $key, 'images' ); ?>><?php _e( 'Images/Gallery', PIECFW_TRANSLATE_NAME ); ?></option>
									<option value="import_as_meta"><?php _e( 'Custom Field with column name', PIECFW_TRANSLATE_NAME ); ?></option>
									<optgroup label="<?php _e( 'Taxonomies', PIECFW_TRANSLATE_NAME ); ?>">
										<?php
											foreach ($taxonomies as $taxonomy ) {
												if ( substr( $taxonomy, 0, 3 ) == 'pa_' ) continue;
													_e('<option value="tax:' . $taxonomy . '" ' . selected( $key, 'tax:' . $taxonomy, true ) . '>' . $taxonomy . '</option>');
											}
										?>
									</optgroup>
									<optgroup label="<?php _e( 'Attributes', PIECFW_TRANSLATE_NAME ); ?>">
										<?php
											foreach ($taxonomies as $taxonomy ) {
												if ( substr( $taxonomy, 0, 3 ) == 'pa_' )
													_e('<option value="attribute:' . $taxonomy . '" ' . selected( $key, 'attribute:' . $taxonomy, true ) . '>' . $taxonomy . '</option>');
											}
										?>
									</optgroup>
									<optgroup label="<?php _e( 'Map to parent (variations and grouped products)', PIECFW_TRANSLATE_NAME ); ?>">
										<option value="parent_sku" <?php selected( $key, 'parent_sku' ); ?>><?php _e( 'By SKU', PIECFW_TRANSLATE_NAME ); ?>: parent_sku</option>
									</optgroup>
									<optgroup label="<?php _e( 'Post data', PIECFW_TRANSLATE_NAME ); ?>">
										<option <?php selected( $key, 'post_type' ); ?>>post_type</option>
										<option <?php selected( $key, 'post_status' ); ?>>post_status</option>
										<option <?php selected( $key, 'post_title' ); ?>>post_title</option>
										<option <?php selected( $key, 'post_content' ); ?>>post_content</option>
										<option <?php selected( $key, 'post_excerpt' ); ?>>post_excerpt</option>
										<option <?php selected( $key, 'variation_description' ); ?>>variation_description</option>
									</optgroup>
									<optgroup label="<?php _e( 'Product data', PIECFW_TRANSLATE_NAME ); ?>">
										<option value="tax:product_type" <?php selected( $key, 'tax:product_type' ); ?>><?php _e( 'Type', PIECFW_TRANSLATE_NAME ); ?>: product_type</option>
										<option value="downloadable" <?php selected( $key, 'downloadable' ); ?>><?php _e( 'Type', PIECFW_TRANSLATE_NAME ); ?>: downloadable</option>
										<option value="virtual" <?php selected( $key, 'virtual' ); ?>><?php _e( 'Type', PIECFW_TRANSLATE_NAME ); ?>: virtual</option>
										<option value="sku" <?php selected( $key, 'sku' ); ?>><?php _e( 'SKU', PIECFW_TRANSLATE_NAME ); ?>: sku</option>
										<option value="visibility" <?php selected( $key, 'visibility' ); ?>><?php _e( 'Visibility', PIECFW_TRANSLATE_NAME ); ?>: visibility</option>
										<option value="featured" <?php selected( $key, 'featured' ); ?>><?php _e( 'Visibility', PIECFW_TRANSLATE_NAME ); ?>: featured</option>
										<option value="stock" <?php selected( $key, 'stock' ); ?>><?php _e( 'Inventory', PIECFW_TRANSLATE_NAME ); ?>: stock</option>
										<option value="stock_status" <?php selected( $key, 'stock_status' ); ?>><?php _e( 'Inventory', PIECFW_TRANSLATE_NAME ); ?>: stock_status</option>
										<option value="backorders" <?php selected( $key, 'backorders' ); ?>><?php _e( 'Inventory', PIECFW_TRANSLATE_NAME ); ?>: backorders</option>
										<option value="manage_stock" <?php selected( $key, 'manage_stock' ); ?>><?php _e( 'Inventory', PIECFW_TRANSLATE_NAME ); ?>: manage_stock</option>
										<option value="regular_price" <?php selected( $key, 'regular_price' ); ?>><?php _e( 'Price', PIECFW_TRANSLATE_NAME ); ?>: regular_price</option>
										<option value="sale_price" <?php selected( $key, 'sale_price' ); ?>><?php _e( 'Price', PIECFW_TRANSLATE_NAME ); ?>: sale_price</option>
										<option value="sale_price_dates_from" <?php selected( $key, 'sale_price_dates_from' ); ?>><?php _e( 'Price', PIECFW_TRANSLATE_NAME ); ?>: sale_price_dates_from</option>
										<option value="sale_price_dates_to" <?php selected( $key, 'sale_price_dates_to' ); ?>><?php _e( 'Price', PIECFW_TRANSLATE_NAME ); ?>: sale_price_dates_to</option>
										<option value="weight" <?php selected( $key, 'weight' ); ?>><?php _e( 'Dimensions', PIECFW_TRANSLATE_NAME ); ?>: weight</option>
										<option value="length" <?php selected( $key, 'length' ); ?>><?php _e( 'Dimensions', PIECFW_TRANSLATE_NAME ); ?>: length</option>
										<option value="width" <?php selected( $key, 'width' ); ?>><?php _e( 'Dimensions', PIECFW_TRANSLATE_NAME ); ?>: width</option>
										<option value="height" <?php selected( $key, 'height' ); ?>><?php _e( 'Dimensions', PIECFW_TRANSLATE_NAME ); ?>: height</option>
										<option value="tax_status" <?php selected( $key, 'tax_status' ); ?>><?php _e( 'Tax', PIECFW_TRANSLATE_NAME ); ?>: tax_status</option>
										<option value="tax_class" <?php selected( $key, 'tax_class' ); ?>><?php _e( 'Tax', PIECFW_TRANSLATE_NAME ); ?>: tax_class</option>
										<option value="upsell_skus" <?php selected( $key, 'upsell_skus' ); ?>><?php _e( 'Related Products', PIECFW_TRANSLATE_NAME ); ?>: upsell_skus</option>
										<option value="crosssell_skus" <?php selected( $key, 'crosssell_skus' ); ?>><?php _e( 'Related Products', PIECFW_TRANSLATE_NAME ); ?>: crosssell_skus</option>
										<option value="downloadable_files" <?php selected( $key, 'downloadable_files' ); ?>><?php _e( 'Downloads', PIECFW_TRANSLATE_NAME ); ?>: downloadable_files</option>
										<option value="download_limit" <?php selected( $key, 'download_limit' ); ?>><?php _e( 'Downloads', PIECFW_TRANSLATE_NAME ); ?>: download_limit</option>
										<option value="download_expiry" <?php selected( $key, 'download_expiry' ); ?>><?php _e( 'Downloads', PIECFW_TRANSLATE_NAME ); ?>: download_expiry</option>
										<option value="product_url" <?php selected( $key, 'product_url' ); ?>><?php _e( 'External', PIECFW_TRANSLATE_NAME ); ?>: product_url</option>
										<option value="button_text" <?php selected( $key, 'button_text' ); ?>><?php _e( 'External', PIECFW_TRANSLATE_NAME ); ?>: button_text</option>
										<?php do_action( 'piecfw_product_data_mapping', $key ); ?>
									</optgroup>
								</select>
								<?php
							}
						?>
					</td>
					<td width="25%"><?php _e($raw_headers[$key]); ?></td>
					<td><code><?php if ( $value != '' ) _e(esc_html( $value )); else _e('-'); ?></code></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button" value="<?php esc_attr_e( 'Submit', PIECFW_TRANSLATE_NAME ); ?>" />
			<input type="hidden" name="delimiter" value="<?php _e($this->delimiter); ?>" />
			<input type="hidden" name="merge_empty_cells" value="<?php _e($this->merge_empty_cells); ?>" />
		</p>
	</form>
</div>