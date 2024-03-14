<?php

use ShopEngine\Utils\Helper;

defined('ABSPATH') || exit;
?>

<div class="shopengine-cart-table">
	<?php do_action('woocommerce_before_cart'); ?>

	<form class="shopengine-cart-form woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
	
		<?php do_action('woocommerce_before_cart_table'); ?>
		
		<!-- shopengine cart table start -->
		<div class="shopengine-table">

			<!-- -------------------------------
			shopengine cart table  head start 
			------------------------------------->
			<div class="shopengine-table__head">
				<div class="shopengine-table__head--th product-name"><?php echo esc_html($settings['shopengine_cart_table_title']['desktop']) ?></div>
				<div class="shopengine-table__head--th product-price"><?php echo esc_html($settings['shopengine_cart_table_price']['desktop']) ?></div>
				<div class="shopengine-table__head--th product-quantity"><?php echo esc_html($settings['shopengine_cart_table_quantity']['desktop']) ?></div>
				<div class="shopengine-table__head--th product-subtotal"><?php echo esc_html($settings['shopengine_cart_table_subtotal']['desktop']) ?></div>
			</div> <!-- shopengine cart table  head end -->
			
			<!---------------------------------------
			shopengine cart table  body start
			------------------------------------- -->
			<div class="shopengine-table__body">
			<?php do_action('woocommerce_before_cart_contents'); ?>
			<?php
			foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
					?>
					<!-- shopengine cart table  body item start -->
					<div class="shopengine-table__body-item" <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
						
						<!-- 
							@class : table-first-body-column
							@content : remove button, thumbnail, product name
							-->

						<div class="shopengine-table__body-item--td table-first-body-column">
							

							<!-- Product Thumbnail and remove button together -->
							<div class="product-thumbnail" data-title="<?php esc_attr_e('Image', 'shopengine-gutenberg-addon'); ?>"> <?php 
								$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
								if(!$product_permalink) {
									shopengine_content_render(Helper::render( $thumbnail ));
								} else {
									shopengine_content_render( sprintf('<a href="%s">%s Oh dear</a>', esc_url($product_permalink), $thumbnail) );
								} ?>
							
								<!-- remove button -->
								<div class="product-remove">
									<?php
									echo wp_kses_post(apply_filters('woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
											esc_url(wc_get_cart_remove_url($cart_item_key)),
											esc_html__('Remove this item', 'shopengine-gutenberg-addon'),
											esc_attr($product_id),
											esc_attr($_product->get_sku())
										),
										$cart_item_key
									));
									?>
								</div>	
							</div>

							
						</div>

						<!-- product name -->
						<div class="shopengine-table__body-item--td product-name"  data-title="<?php esc_attr_e('Product', 'shopengine-gutenberg-addon'); ?>">
								<?php
								if(!$product_permalink) {
									echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
								} else {
									echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
								}

								do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

								// Meta data.
								shopengine_content_render(wc_get_formatted_cart_item_data($cart_item)); 

								// Backorder notification.
								if($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
									echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'shopengine-gutenberg-addon') . '</p>', $product_id));
								}
								?>

						</div>

						<!-- product price -->
						<div class="shopengine-table__body-item--td product-price" data-title="<?php esc_attr_e('Price', 'shopengine-gutenberg-addon'); ?>">
							<?php
							shopengine_content_render(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key)); 
							?>
						</div>
						
						<!-- product quantity -->
						<div class="shopengine-table__body-item--td product-quantity" data-title="<?php esc_attr_e('Quantity', 'shopengine-gutenberg-addon'); ?>">
							<div class="shopengine-cart-quantity">
								<?php
								if($_product->is_sold_individually()) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									echo "<span class='minus-button'>&minus;</span>";
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
									echo "<span class='plus-button'>&plus;</span>";
								}

								shopengine_content_render(apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item));
								?>
							</div>

						</div>

						<!-- product subtotal -->
						<div class="shopengine-table__body-item--td product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'shopengine-gutenberg-addon'); ?>">
							<?php
							shopengine_content_render(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key));
							?>
						</div>

					</div> <!-- shopengine cart table  body item end -->
					<?php
				}
			}
			?>

			<?php do_action('woocommerce_cart_contents'); ?>
			<?php do_action('woocommerce_after_cart_contents'); ?>

			</div> <!-- shopengine cart table  body end -->

			<!--------------------------------
			shopengine cart table footer start
			------------------------------- -->
			<div class="shopengine-table__footer">
				
				<div class="button-group-left">
					<button class="return-to-shop shopengine-footer-button">
						<i class="fas fa-long-arrow-alt-left" style="font-style: normal;"></i>
						<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
							<?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', esc_html($settings['shopengine_continue_shopping']['desktop']) ) );  ?>
						</a>
					</button>
				</div>
				
				<div class="button-group-right">
					<button type="submit" class="button update-cart-btn shopengine-footer-button" name="update_cart">
						<i class="fas fa-redo" style="font-style: normal;"></i>
						<?php echo esc_html($settings['shopengine_update_cart']['desktop']);?>
					</button>

					<button class="shopengine-footer-button clear-btn" type="submit" name="empty_cart">
					<i class="far fa-trash-alt" style="font-style: normal;"></i>
						<?php echo esc_html($settings['shopengine_clear_all']['desktop']);?>
					</button>
				</div>
				<?php do_action('woocommerce_cart_actions'); ?>
				<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>

			</div> <!-- shopengine cart table footer end -->

		</div> <!-- shopengine cart table  end -->
		<?php do_action('woocommerce_after_cart_table'); ?>
	</form>

	<?php do_action('woocommerce_after_cart'); ?>
</div>