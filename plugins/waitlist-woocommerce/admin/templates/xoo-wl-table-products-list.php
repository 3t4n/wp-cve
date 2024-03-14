<div class="xoo-wl-table-container">
	<div class="xoo-wl-notices"></div>
	<table id="xoo-wl-products-table" class="display xoo-wl-table">
		<thead>

			<tr>
				<th><?php _e( 'Product', 'waitlist-woocommerce' ); ?></th>
				<th><?php _e( 'Stock Status', 'waitlist-woocommerce' ); ?></th>
				<th><?php _e( 'Total Quantity', 'waitlist-woocommerce' ); ?></th>
				<th><?php _e( 'Total Users', 'waitlist-woocommerce' ); ?></th>
				<th class="no-sort"><?php _e( 'Actions', 'waitlist-woocommerce' ); ?></th>
			</tr>

			<tbody>
			<?php foreach ( $rows as $productRow ) {
				$product_id 	= (int) $productRow->product_id;
				$product 		= wc_get_product( $product_id );

				if( !$product || !is_object( $product ) ) continue;
				
				$edit_link 		= $product->is_type('variation') ? get_edit_post_link( $product->get_parent_id() ) : get_edit_post_link( $product_id );

				$product_title  = apply_filters( 'xoo_wl_admin_products_table_product_title', $product->get_formatted_name(), $productRow );



			?>
				<tr data-product_id="<?php echo (int) $product_id; ?>">

					<td class="xoo-wltd-pname">
						<div class="xoo-wl-pimg">
							<span class="dashicons dashicons-no-alt xoo-wl-remove-row"></span>
							<?php echo wp_kses_post( $product->get_image() ); ?>
							<a href="<?php echo esc_url( $edit_link ); ?>" target="_blank"><span><?php echo esc_html( $product_title ); ?></span></a>
						</div>
					</td>

					<td><?php echo esc_html( $product->get_stock_status() ); ?></td>

					<td><?php echo esc_html( $productRow->quantity ); ?></td>

					<td><?php echo esc_html( $productRow->entries );?></td>

					<td><span class="xoo-wl-bis-btn xoo-wl-table-btn"><?php _e( 'Send Email', 'waitlist-woocommerce' ); ?></span> <a href="<?php echo esc_url( $_SERVER['REQUEST_URI'] ).'&product='.$product_id; ?>" class="xoo-wl-vu-btn xoo-wl-table-btn"><?php _e( 'View', 'waitlist-woocommerce' ); ?></span></td>

				</tr>

			<?php }; ?>

			</tbody>
		</thead>
	</table>
</div>