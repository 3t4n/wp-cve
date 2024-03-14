<?php

if (!defined('ABSPATH')) {
	exit;
}

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

$cart = isset($_REQUEST['cart']) ? sanitize_text_field($_REQUEST['cart']) : null;

$page_refresh = WReady_Helper::get_global_setting('shop_ready_mini_cart_widget_remove_item_refresh', 'no');
$title_limit_plural = WReady_Helper::get_global_setting('woo_ready_mini_cart_title_limit_plural', '3');
$_layout = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_content_layout', 'style1');
$update_button_text = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_update_button_text', 'Update');
$update_button_enable = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_update_button', 'yes');
$price_show = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_price_show', 'yes');
$qty_show = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_qty_show', 'yes');
$qty_input_show = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_qty_input_show', 'yes');

global $woocommerce;

if (!is_null($cart) && isset($cart['key'])) {
	$qty = is_numeric($cart['qty']) && $cart['qty'] > 0 ? $cart['qty'] : 1;
	$woocommerce->cart->set_quantity($cart['key'], $qty);
	$woocommerce->cart->set_session();
}

foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

	$_product = $cart_item['data'];
	$product_id = $cart_item['product_id'];

	if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('shop_ready_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {

		$product_name = $_product->get_name();
		$thumbnail = $_product->get_image();

		$product_price = WC()->cart->get_product_price($_product);
		$product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';



		?>
		<?php if ($_layout == 'style1'): ?>

			<div data-layout="style1" data-id="<?php echo esc_attr(uniqid()); ?>" class="woo-ready-mini-cart-item display:flex">
				<?php



				echo wp_kses_post(
					sprintf(
						'<a href="%s" class="remove %s " aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
						esc_url(wc_get_cart_remove_url($cart_item_key)),
						$page_refresh == 'yes' ? 'remove_from_cart_button' : '',
						esc_attr__('Remove', 'shopready-elementor-addon'),
						esc_attr($product_id),
						esc_attr($cart_item_key),
						esc_attr($_product->get_sku())
					)
				);

				?>
				<?php if (empty($product_permalink)): ?>
					<span class="wr-mini-cart-thumb">
						<?php echo wp_kses_post($thumbnail); ?>
					</span>
				<?php else: ?>
					<a class="wr-mini-cart-thumb" href="<?php echo esc_url($product_permalink); ?>">
						<?php echo wp_kses_post($thumbnail); ?>
					</a>
				<?php endif; ?>
				<a class="wr-mini-cart-title" href="<?php echo esc_url($product_permalink); ?>">
					<?php echo wp_kses_post(wp_trim_words($product_name, $title_limit_plural, '')); ?>
				</a>
				<?php echo wc_get_formatted_cart_item_data($cart_item); ?>

				<?php

				if ($qty_show == 'yes' && $price_show == 'yes') {
					echo wp_kses_post('<span class="product-quantity">' . sprintf('<span class="wr-product-qty"> %s &times; </span> %s', $cart_item['quantity'], $product_price) . '</span>');
				} elseif ($qty_show == 'yes' && $price_show != 'yes') {
					echo wp_kses_post('<span class="product-quantity">' . sprintf('<span class="wr-product-qty"> %s </span>', $cart_item['quantity']) . '</span>');
				} elseif ($qty_show != 'yes' && $price_show == 'yes') {
					echo wp_kses_post('<span class="product-quantity">' . sprintf('%s', $product_price) . '</span>');
				}

				?>
				<?php

				if ($qty_input_show == 'yes') {
					$product_quantity = shop_ready_sr_wc_quantity_input(
						array(
							'input_name' => "cart[{$cart_item_key}][qty]",
							'input_value' => $cart_item['quantity'],
							'max_value' => $_product->get_max_purchase_quantity(),
							'min_value' => '1',
							'item_key' => $cart_item_key,
							'product_name' => $_product->get_name(),
						),
						$_product,
						true
					);
				}

				?>
			</div>

		<?php elseif ('style2' == $_layout): ?>
			<div data-layout="style2" data-default="true" data-id="<?php echo esc_attr(uniqid()); ?>"
				class="woo-ready-mini-cart-item display:flex">
				<div class="shop-ready-mini-cart-image-wrapper">
					<?php

					echo wp_kses_post(
						sprintf(
							'<a href="%s" class="remove %s " aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
							esc_url(wc_get_cart_remove_url($cart_item_key)),
							$page_refresh == 'yes' ? 'remove_from_cart_button' : '',
							esc_attr__('Remove', 'shopready-elementor-addon'),
							esc_attr($product_id),
							esc_attr($cart_item_key),
							$_product->get_sku()
						)
					);

					?>

					<?php if (empty($product_permalink)): ?>
						<span class="wr-mini-cart-thumb">
							<?php echo wp_kses_post($thumbnail); ?>
						</span>
					<?php else: ?>
						<a class="wr-mini-cart-thumb" href="<?php echo esc_url($product_permalink); ?>">
							<?php echo wp_kses_post($thumbnail); ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="shop-ready-mini-cart-content">
					<a class="wr-mini-cart-title" href="<?php echo esc_url($product_permalink); ?>">
						<?php echo wp_trim_words($product_name, $title_limit_plural, ''); ?>
					</a>
					<?php echo wc_get_formatted_cart_item_data($cart_item); ?>

					<?php

					if ($qty_show == 'yes' && $price_show == 'yes') {
						echo wp_kses_post('<span class="product-quantity">' . sprintf('<span class="wr-product-qty"> %s &times; </span> %s', esc_html($cart_item['quantity']), $product_price) . '</span>');
					} elseif ($qty_show == 'yes' && $price_show != 'yes') {
						echo wp_kses_post('<span class="product-quantity">' . sprintf('<span class="wr-product-qty"> %s </span>', $cart_item['quantity']) . '</span>');
					} elseif ($qty_show != 'yes' && $price_show == 'yes') {
						echo wp_kses_post('<span class="product-quantity">' . sprintf('%s', $product_price) . '</span>');
					}

					?>
					<?php

					if ($qty_input_show == 'yes') {
						$product_quantity = shop_ready_sr_wc_quantity_input(
							array(
								'input_name' => "cart[{$cart_item_key}][qty]",
								'input_value' => $cart_item['quantity'],
								'max_value' => $_product->get_max_purchase_quantity(),
								'min_value' => '1',
								'item_key' => $cart_item_key,
								'product_name' => $_product->get_name(),
							),
							$_product,
							true
						);
					}
					?>
				</div>
			</div>

		<?php elseif ('style3' == $_layout): ?>
			<div data-layout="style3" data-default="true" data-id="<?php echo esc_attr(uniqid()); ?>"
				class="woo-ready-mini-cart-item display:flex flex-direction:column-reverse">

				<div class="shop-ready-mini-cart-content">
					<a class="wr-mini-cart-title" href="<?php echo esc_url($product_permalink); ?>">
						<?php echo wp_trim_words($product_name, $title_limit_plural, ''); ?>
					</a>
					<?php echo wc_get_formatted_cart_item_data($cart_item); ?>

					<?php

					if ($qty_show == 'yes' && $price_show == 'yes') {
						echo wp_kses_post('<span class="product-quantity">' . sprintf('<span class="wr-product-qty"> %s &times; </span> %s', esc_html($cart_item['quantity']), esc_html($product_price)) . '</span>');
					} elseif ($qty_show == 'yes' && $price_show != 'yes') {
						echo wp_kses_post('<span class="product-quantity">' . sprintf('<span class="wr-product-qty"> %s </span>', esc_html($cart_item['quantity'])) . '</span>');
					} elseif ($qty_show != 'yes' && $price_show == 'yes') {
						echo wp_kses_post('<span class="product-quantity">' . sprintf('%s', esc_html($product_price)) . '</span>');
					}

					?>
					<?php
					if ($qty_input_show == 'yes') {
						$product_quantity = shop_ready_sr_wc_quantity_input(
							array(
								'input_name' => "cart[{$cart_item_key}][qty]",
								'input_value' => $cart_item['quantity'],
								'max_value' => $_product->get_max_purchase_quantity(),
								'min_value' => '1',
								'item_key' => $cart_item_key,
								'product_name' => $_product->get_name(),
							),
							$_product,
							true
						);
					}
					?>
				</div>
				<div class="shop-ready-mini-cart-image-wrapper">

					<?php if (empty($product_permalink)): ?>
						<span class="wr-mini-cart-thumb">
							<?php echo wp_kses_post($thumbnail); ?>
						</span>
					<?php else: ?>
						<a class="wr-mini-cart-thumb" href="<?php echo esc_url($product_permalink); ?>">
							<?php echo wp_kses_post($thumbnail); ?>
						</a>
					<?php endif; ?>
					<?php

					echo wp_kses_post(
						sprintf(
							'<a href="%s" class="remove %s " aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
							esc_url(wc_get_cart_remove_url($cart_item_key)),
							$page_refresh == 'yes' ? 'remove_from_cart_button' : '',
							esc_attr__('Remove', 'shopready-elementor-addon'),
							esc_attr($product_id),
							esc_attr($cart_item_key),
							$_product->get_sku()
						)
					);

					?>
				</div>
			</div>
		<?php endif; ?>
	<?php
	}

	$data['div.' . $cart_item_key] = WC()->cart->get_product_subtotal($_product, $cart_item['quantity']);
}

if ($update_button_enable == 'yes' && WC()->cart->get_cart_contents_count() > 0):
	?>
	<div class="shop-ready-minicart-update-btn-wrapper">
		<button class="shop-ready-mini-cart-update-button">
			<?php echo wp_kses_post($update_button_text); ?>
		</button>
	</div>
	<?php

endif;
