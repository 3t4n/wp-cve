<table class="onestepcheckout-summary cclw-style-1">
    <thead>
        <tr>
            <th class="thumb"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
            <th class="qty"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
            <th class="total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<th class="removepro"><?php esc_html_e( 'Remove', 'woocommerce' ); ?></th>
        </tr>
    </thead>
	<tbody>
	<?php
	   do_action( 'woocommerce_review_order_before_cart_contents' ); 

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) )
				{
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				}
				?>
				
	<tr>
	
        <td class="thumb">
			<?php
			$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array( 50, 80 )), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
			?>
			
         
        </td>
		
        <td class="cclw_qty" nowrap="">
		
		<div class="wrapper_qty">
		<?php
		
		if(isset($checkout_options['cclw_skip_qty']) && $checkout_options['cclw_skip_qty'] == 'yes')
	    {	
		   echo $cart_item['quantity'];
		}
		else
		{
			?>
			<button type="button" class="cclwminus" >-</button>
			<input type="number" id="qty1" class="input-text qty text" step="1" min="1" max="<?php echo $_product->backorders_allowed() ? '' : $_product->get_stock_quantity();?>" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo $cart_item['quantity'];?>" title="Qty" size="4" inputmode="numeric">
			<button type="button" class="cclwplus" >+</button>
			<?php
		}
		?>
	
		</div>
		</td>
        
        <td class="total">
                       <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
		</td>
		 <td class="removepro">
		 	 <a href="<?php echo  esc_url( wc_get_cart_remove_url( $cart_item_key ) );?>" class="cclw_remove" title="Remove this item">x</a>				 
		 </td>	
	 
    </tr>
	<tr>
	<td colspan="4" class="name more_details" style="text-align:left !important;padding-left: 10px !important;">
	<?php  echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';          ?>
	 <div class="more_details_slide"><?php   echo wc_get_formatted_cart_item_data( $cart_item ); ?></div>
	
	</td>	
	</tr>
	
	<?php }
	do_action( 'woocommerce_review_order_after_cart_contents' ); 
	?>

	</tbody>
</table>	

