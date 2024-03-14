<?php

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;



$page_layout = WReady_Helper::get_global_setting('shop_ready_pro_cart_page_layout', 'style2');
$cart_subtotal_label = WReady_Helper::get_global_setting('woo_ready_widget_cart_subtotal_label', 'Subtotal');
$apply_code_label = WReady_Helper::get_global_setting('woo_ready_widget_cart_apply_code_label', 'Apply Code');
$apply_coupon_label = WReady_Helper::get_global_setting('woo_ready_widget_cart_apply_coupon_label', 'Apply Coupon');
$coupon_label = WReady_Helper::get_global_setting('woo_ready_widget_cart_apply_coupon_label', 'Coupon:');
$update_cart = WReady_Helper::get_global_setting('woo_ready_widget_cart_update_cart_label', 'Update');

$remove_text_enable = WReady_Helper::get_global_setting('shop_ready_pro_widget_cart_remove_text_enable', 'yes');

if ($remove_text_enable == 'yes') {
	$remove_icon = WReady_Helper::get_global_setting('shop_ready_pro_widget_cart_remove_text', 'Remove');
} else {
	$remove_icon = shop_ready_render_icons(WReady_Helper::get_global_setting('shop_ready_pro_widget_cart_remove_icon', '&times;'));
}


?>

<?php if ($page_layout == 'default'): ?>

	<?php do_action('woocommerce_before_cart'); ?>

	<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
		<?php do_action('woocommerce_before_cart_table'); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-name">
						<?php esc_html_e('Productsd', 'shopready-elementor-addon'); ?>
					</th>
					<th class="product-price">
						<?php esc_html_e('Price', 'shopready-elementor-addon'); ?>
					</th>
					<th class="product-quantity">
						<?php esc_html_e('Quantity', 'shopready-elementor-addon'); ?>
					</th>
					<th class="product-subtotal">
						<?php echo esc_html($cart_subtotal_label); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php do_action('woocommerce_before_cart_contents'); ?>

				<?php
				foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
					$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
					$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

					if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
						$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
						?>
						<tr
							class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">



							<td class="product-thumbnail shop-ready-pro-style3">
								<div class="product-remove">
									<?php
									echo wp_kses_post(
										apply_filters(
											// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="rt-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
												esc_url(wc_get_cart_remove_url($cart_item_key)),
												esc_html__('Remove', 'shopready-elementor-addon'),
												esc_attr($product_id),
												esc_attr($_product->get_sku()),
												$cart_item_key,
												$remove_icon
											),
											$cart_item_key
										)
									);
									?>
								</div>
								<?php
								$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

								if (!$product_permalink) {
									echo wp_kses_post($thumbnail); // PHPCS: XSS ok.
								} else {
									printf('<a href="%s">%s</a>', esc_url($product_permalink), wp_kses_post($thumbnail)); // PHPCS: XSS ok.
								}
								?>
								<div class="product-name shop-ready-pro-style3"
									data-title="<?php esc_attr_e('Product', 'shopready-elementor-addon'); ?>">
									<?php
									if (!$product_permalink) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
									} else {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), esc_html($_product->get_name())), $cart_item, $cart_item_key));
									}

									do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

									// Meta data.
									echo wp_kses_post(wc_get_formatted_cart_item_data($cart_item)); // PHPCS: XSS ok.
						
									// Backorder notification.
									if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'shopready-elementor-addon') . '</p>', $product_id));
									}
									?>
								</div>
							</td>



							<td class="product-price" data-title="<?php esc_attr_e('Price', 'shopready-elementor-addon'); ?>">
								<?php
								echo wp_kses_post(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'shopready-elementor-addon'); ?>">
								<?php
								if ($_product->is_sold_individually()) {
									$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr($cart_item_key));
								} else {
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name' => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value' => $_product->get_max_purchase_quantity(),
											'min_value' => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										true
									);
								}

								echo wp_kses_post(apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item)); // PHPCS: XSS ok.
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'shopready-elementor-addon'); ?>">
								<?php
								echo wp_kses_post(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
								?>
							</td>
						</tr>
						<?php
					}
				}
				?>

				<?php do_action('woocommerce_cart_contents'); ?>

				<tr>
					<td colspan="6" class="actions">

						<?php if (wc_coupons_enabled()) { ?>
							<div class="coupon">
								<label for="coupon_code">
									<?php echo esc_html($coupon_label); ?>
								</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
									placeholder="<?php echo esc_attr($apply_code_label); ?>" /> <button type="submit"
									class="button" name="apply_coupon" value="<?php echo esc_attr($apply_coupon_label); ?>">
									<?php echo esc_html($apply_coupon_label); ?>
								</button>
								<?php do_action('woocommerce_cart_coupon'); ?>
							</div>
						<?php } ?>

						<button type="submit" class="button" name="update_cart"
							value="<?php echo esc_attr($update_cart); ?>">
							<?php echo esc_html($update_cart); ?>
						</button>

						<?php do_action('woocommerce_cart_actions'); ?>

						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</td>
				</tr>

				<?php do_action('woocommerce_after_cart_contents'); ?>
			</tbody>
		</table>

		<?php do_action('woocommerce_after_cart_table'); ?>
	</form>

	<?php do_action('woocommerce_before_cart_collaterals'); ?>

	<div class="cart-collaterals">
		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
		?>
	</div>

	<?php do_action('woocommerce_after_cart'); ?>

<?php else: ?>

	<?php do_action('woocommerce_before_cart'); ?>
	<div data-layout="<?php echo esc_attr($page_layout); ?>"
		class="shop-ready-cart-inner-container <?php echo esc_attr($page_layout); ?> <?php echo esc_attr($page_layout == 'style3' ? 'display:flex flex-direction:column' : ''); ?>">
		<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
			<?php do_action('woocommerce_before_cart_table'); ?>

			<?php if ('style1' == $page_layout): ?>
				<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
					<thead>
						<tr>


							<th class="product-name">
								<?php esc_html_e('Product', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-price">
								<?php esc_html_e('Price', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-quantity">
								<?php esc_html_e('Quantity', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-subtotal">
								<?php echo esc_html($cart_subtotal_label); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php do_action('woocommerce_before_cart_contents'); ?>

						<?php
						foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
							$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
							$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

							if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
								$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
								?>
								<tr
									class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">



									<td class="product-thumbnail">
										<div class="product-remove">
											<?php
											echo wp_kses_post(
												apply_filters(
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													'woocommerce_cart_item_remove_link',
													sprintf(
														'<a href="%s" class="rt-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
														esc_url(wc_get_cart_remove_url($cart_item_key)),
														esc_html__('Remove', 'shopready-elementor-addon'),
														esc_attr($product_id),
														esc_attr($_product->get_sku()),
														esc_attr($cart_item_key),
														wp_kses_post($remove_icon),
													),
													$cart_item_key
												)
											);
											?>
										</div>
										<?php
										$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

										if (!$product_permalink) {
											echo wp_kses_post($thumbnail); // PHPCS: XSS ok.
										} else {
											printf('<a href="%s">%s</a>', esc_url($product_permalink), wp_kses_post($thumbnail)); // PHPCS: XSS ok.
										}
										?>
										<div class="product-name"
											data-title="<?php esc_attr_e('Product', 'shopready-elementor-addon'); ?>">
											<?php

											if (!$product_permalink) {
												echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
											} else {
												echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), esc_html($_product->get_name())), $cart_item, $cart_item_key));
											}

											do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

											// Meta data.
											echo wp_kses_post(wc_get_formatted_cart_item_data($cart_item)); // PHPCS: XSS ok.
							
											// Backorder notification.
											if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
												echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'shopready-elementor-addon') . '</p>', $product_id));
											}

											?>
										</div>
									</td>



									<td class="product-price" data-title="<?php esc_attr_e('Price', 'shopready-elementor-addon'); ?>">
										<?php
										echo wp_kses_post(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-quantity"
										data-title="<?php esc_attr_e('Quantity', 'shopready-elementor-addon'); ?>">
										<?php
										if ($_product->is_sold_individually()) {
											$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr($cart_item_key));
										} else {
											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name' => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value' => $_product->get_max_purchase_quantity(),
													'min_value' => '0',
													'product_name' => $_product->get_name(),
												),
												$_product,
												true
											);
										}

										echo wp_kses_post(apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item)); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-subtotal" data-title="<?php echo esc_html($cart_subtotal_label); ?>">
										<?php
										echo wp_kses_post(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>

						<?php do_action('woocommerce_cart_contents'); ?>

						<tr>
							<td colspan="6" class="actions">

								<?php if (wc_coupons_enabled()) { ?>
									<div class="coupon">
										<label for="coupon_code">
											<?php echo esc_html($coupon_label); ?>
										</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
											placeholder="<?php echo esc_html($apply_code_label); ?>" /> <button type="submit"
											class="button" name="apply_coupon" value="<?php echo esc_html($apply_coupon_label); ?>">
											<?php echo esc_html($apply_coupon_label); ?>
										</button>
										<?php do_action('woocommerce_cart_coupon'); ?>
									</div>
								<?php } ?>

								<button type="submit" class="button" name="update_cart"
									value="<?php echo esc_attr($update_cart); ?>">
									<?php echo esc_html($update_cart); ?>
								</button>

								<?php do_action('woocommerce_cart_actions'); ?>

								<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
							</td>
						</tr>

						<?php do_action('woocommerce_after_cart_contents'); ?>
					</tbody>
				</table>
			<?php elseif ('style2' == $page_layout): ?>
				<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
					<thead>
						<tr>
							<th class="product-name">
								<?php esc_html_e('Product', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-price">
								<?php esc_html_e('Price', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-quantity">
								<?php esc_html_e('Quantity', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-subtotal">
								<?php echo esc_html($cart_subtotal_label); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php do_action('woocommerce_before_cart_contents'); ?>

						<?php
						foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
							$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
							$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

							if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
								$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
								?>
								<tr
									class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">


									<td class="product-thumbnail">
										<?php

										$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

										if (!$product_permalink) {
											echo wp_kses_post($thumbnail); // PHPCS: XSS ok.
										} else {
											printf('<a href="%s">%s</a>', esc_url($product_permalink), wp_kses_post($thumbnail)); // PHPCS: XSS ok.
										}

										?>
										<div class="product_content-wrapersr-style2">
											<div class="product-name"
												data-title="<?php esc_attr_e('Product', 'shopready-elementor-addon'); ?>">
												<?php
												if (!$product_permalink) {
													echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
												} else {
													echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), esc_html($_product->get_name())), $cart_item, $cart_item_key));
												}

												do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

												// Meta data.
												echo wp_kses_post(wc_get_formatted_cart_item_data($cart_item)); // PHPCS: XSS ok.
								
												// Backorder notification.
												if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
													echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'shopready-elementor-addon') . '</p>', $product_id));
												}
												?>
											</div>
											<div class="product-remove">
												<?php
												echo wp_kses_post(
													apply_filters(
														// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														'woocommerce_cart_item_remove_link',
														sprintf(
															'<a href="%s" class="rt-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
															esc_url(wc_get_cart_remove_url($cart_item_key)),
															esc_html__('Remove', 'shopready-elementor-addon'),
															esc_attr($product_id),
															esc_attr($_product->get_sku()),
															$cart_item_key,
															$remove_icon
														),
														$cart_item_key
													)
												);
												?>
											</div>
										</div>

									</td>



									<td class="product-price" data-title="<?php esc_attr_e('Price', 'shopready-elementor-addon'); ?>">
										<?php
										echo esc_html(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-quantity"
										data-title="<?php esc_attr_e('Quantity', 'shopready-elementor-addon'); ?>">
										<?php
										if ($_product->is_sold_individually()) {
											$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr($cart_item_key));
										} else {
											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name' => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value' => $_product->get_max_purchase_quantity(),
													'min_value' => '0',
													'product_name' => $_product->get_name(),
												),
												$_product,
												true
											);
										}

										echo wp_kses_post(apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item)); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-subtotal"
										data-title="<?php esc_attr_e('Subtotal', 'shopready-elementor-addon'); ?>">
										<?php
										echo wp_kses_post(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>

						<?php do_action('woocommerce_cart_contents'); ?>

						<tr>
							<td colspan="6" class="actions">

								<?php if (wc_coupons_enabled()) { ?>
									<div class="coupon">
										<label for="coupon_code">
											<?php esc_html_e('Coupon:', 'shopready-elementor-addon'); ?>
										</label>
										<input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
											placeholder="<?php esc_attr_e('Coupon code', 'shopready-elementor-addon'); ?>" />
										<button type="submit" class="button" name="apply_coupon"
											value="<?php esc_attr_e('Apply coupon', 'shopready-elementor-addon'); ?>">
											<?php esc_attr_e('Apply coupon', 'shopready-elementor-addon'); ?>
										</button>
										<?php do_action('woocommerce_cart_coupon'); ?>
									</div>
								<?php } ?>

								<button type="submit" class="button" name="update_cart"
									value="<?php echo esc_attr($update_cart); ?>">
									<?php echo esc_html($update_cart); ?>
								</button>

								<?php do_action('woocommerce_cart_actions'); ?>

								<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
							</td>
						</tr>

						<?php do_action('woocommerce_after_cart_contents'); ?>
					</tbody>
				</table>
			<?php elseif ('style3' == $page_layout): ?>
				<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
					<thead>
						<tr>
							<th class="product-name">
								<?php esc_html_e('Product', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-price">
								<?php esc_html_e('Price', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-quantity">
								<?php esc_html_e('Quantity', 'shopready-elementor-addon'); ?>
							</th>
							<th class="product-subtotal">
								<?php echo esc_html($cart_subtotal_label); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php do_action('woocommerce_before_cart_contents'); ?>

						<?php
						foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
							$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
							$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

							if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
								$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
								?>
								<tr
									class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">



									<td class="product-thumbnail shop-ready-pro-style3">
										<div class="product-remove">
											<?php
											echo wp_kses_post(
												apply_filters(
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													'woocommerce_cart_item_remove_link',
													sprintf(
														'<a href="%s" class="rt-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
														esc_url(wc_get_cart_remove_url($cart_item_key)),
														esc_html__('Remove', 'shopready-elementor-addon'),
														esc_attr($product_id),
														esc_attr($_product->get_sku()),
														$cart_item_key,
														$remove_icon
													),
													$cart_item_key
												)
											);
											?>
										</div>
										<?php
										$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

										if (!$product_permalink) {
											echo wp_kses_post($thumbnail); // PHPCS: XSS ok.
										} else {
											printf('<a href="%s">%s</a>', esc_url($product_permalink), wp_kses_post($thumbnail)); // PHPCS: XSS ok.
										}
										?>
										<div class="product-name shop-ready-pro-style3"
											data-title="<?php esc_attr_e('Product', 'shopready-elementor-addon'); ?>">
											<?php
											if (!$product_permalink) {
												echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
											} else {
												echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), esc_html($_product->get_name())), $cart_item, $cart_item_key));
											}

											do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

											// Meta data.
											echo wp_kses_post(wc_get_formatted_cart_item_data($cart_item)); // PHPCS: XSS ok.
							
											// Backorder notification.
											if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
												echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'shopready-elementor-addon') . '</p>', $product_id));
											}
											?>
										</div>
									</td>



									<td class="product-price" data-title="<?php esc_attr_e('Price', 'shopready-elementor-addon'); ?>">
										<?php
										echo wp_kses_post(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-quantity"
										data-title="<?php esc_attr_e('Quantity', 'shopready-elementor-addon'); ?>">
										<?php
										if ($_product->is_sold_individually()) {
											$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr($cart_item_key));
										} else {
											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name' => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value' => $_product->get_max_purchase_quantity(),
													'min_value' => '0',
													'product_name' => $_product->get_name(),
												),
												$_product,
												true
											);
										}

										echo wp_kses_post(apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item)); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-subtotal"
										data-title="<?php esc_attr_e('Subtotal', 'shopready-elementor-addon'); ?>">
										<?php
										echo wp_kses_post(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key)); // PHPCS: XSS ok.
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>

						<?php do_action('woocommerce_cart_contents'); ?>

						<tr>
							<td colspan="6" class="actions">

								<?php if (wc_coupons_enabled()) { ?>
									<div class="coupon">
										<label for="coupon_code">
											<?php echo esc_html($coupon_label); ?>
										</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
											placeholder="<?php echo esc_attr($apply_code_label); ?>" /> <button type="submit"
											class="button" name="apply_coupon" value="<?php echo esc_attr($apply_coupon_label); ?>">
											<?php echo esc_html($apply_coupon_label); ?>
										</button>
										<?php do_action('woocommerce_cart_coupon'); ?>
									</div>
								<?php } ?>

								<button type="submit" class="button" name="update_cart"
									value="<?php echo esc_attr($update_cart); ?>">
									<?php echo esc_html($update_cart); ?>
								</button>

								<?php do_action('woocommerce_cart_actions'); ?>

								<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
							</td>
						</tr>

						<?php do_action('woocommerce_after_cart_contents'); ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php do_action('woocommerce_after_cart_table'); ?>
		</form>

		<?php do_action('woocommerce_before_cart_collaterals'); ?>


		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
		?>
	</div>
	<?php do_action('woocommerce_after_cart'); ?>

<?php endif; ?>