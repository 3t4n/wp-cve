<?php
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
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
$ins_layout = ! empty( insopt( 'ins-layout-options' ) ) ? insopt( 'ins-layout-options' ) : '1';
do_action( 'woocommerce_before_cart' ); ?>
<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<div class="ins-checkout-body ins-content">
		<div class="ins-cart-content-wrap">
			<!-- Single Cart item title Start -->
			<div class="ins-cart-item-heading">
				<span class="ins-cart-item-heading-remove"></span>
				<span class="ins-cart-item-heading-title">
					<?php esc_html_e( 'Product', 'woocommerce' ); ?>
				</span>
				<span class="ins-cart-item-heading-price">
					<?php esc_html_e( 'Price', 'woocommerce' ); ?>
				</span>
				<span class="ins-cart-item-heading-quantity">
					<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>
				</span>
				<span class="ins-cart-item-heading-total">
					<?php esc_html_e( 'Subtotal', 'woocommerce' ); ?>
				</span>
			</div>

			<div class="ins-single-cart-wrap">
				<!-- Single Cart item end Start -->

				<?php do_action( 'woocommerce_before_cart_table' ); ?>
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$variation_id = $cart_item['variation_id'];
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<!-- Single Cart Item Start -->
						<div class="ins-single-cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>"
							data-cart-item-key="<?php echo $cart_item_key ?>"
							data-product-id="<?php echo $cart_item['product_id'] ?>">
							<div class="ins-cart-remove">

								<span class="ins-cart-item-remove product-remove">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-variation_id="%s" data-product_sku="%s">
                                                <span class="ins-single-item-remove"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.83366 2.33329V0.666626H13.167V2.33329H17.3337V3.99996H15.667V16.5C15.667 16.721 15.5792 16.9329 15.4229 17.0892C15.2666 17.2455 15.0547 17.3333 14.8337 17.3333H3.16699C2.94598 17.3333 2.73402 17.2455 2.57774 17.0892C2.42146 16.9329 2.33366 16.721 2.33366 16.5V3.99996H0.666992V2.33329H4.83366ZM4.00033 3.99996V15.6666H14.0003V3.99996H4.00033ZM6.50033 6.49996H8.16699V13.1666H6.50033V6.49996ZM9.83366 6.49996H11.5003V13.1666H9.83366V6.49996Z" fill="#535E70"/>
                                                </svg></span>
                                            </a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $variation_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
								</span>

							</div>
							<div class="ins-cart-item-product">
								<div class="ins-cart-item-image">
									<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
									}
									?>
								</div>
								<div class="ins-cart-item-title" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
									<?php

									$productName = $_product->get_name();

									if ( strlen( $productName ) > 30 ) {
										$limitedName = substr( $productName, 0, 30 ) . "...";
									} else {
										$limitedName = $productName;
									}

									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $limitedName, $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $limitedName ), $cart_item, $cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
							
									// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
									}
									?>
								</div>
							</div>
							<div class="ins-cart-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</div>
							<div class="ins-cart-item-quantity ins-cart-qty-wrap">
								<?php
								if ( $_product->is_sold_individually() ) {
									$min_quantity = 1;
									$max_quantity = 1;
								} else {
									$min_quantity = 0;
									$max_quantity = $_product->get_max_purchase_quantity();
								}
								$minus_icon = '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_173_413)">
                                <rect x="4.66699" y="9.16663" width="11.6667" height="1.66667" fill="#494E5C"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_173_413">
                                <rect width="20" height="20" fill="white" transform="translate(0.5)"/>
                                </clipPath>
                                </defs>
                                </svg>';

								$plus_icon = '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_173_410)">
                                <path d="M9.66699 9.16663V4.16663H11.3337V9.16663H16.3337V10.8333H11.3337V15.8333H9.66699V10.8333H4.66699V9.16663H9.66699Z" fill="#494E5C"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_173_410">
                                <rect width="20" height="20" fill="white" transform="translate(0.5)"/>
                                </clipPath>
                                </defs>
                                </svg>';


								$product_quantity = '<button type="button" class="minus ins-cart-minus">' . $minus_icon . '</button>';
								$product_quantity .= woocommerce_quantity_input(
									array(
										'input_name' => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value' => $max_quantity,
										'min_value' => $min_quantity,
										'product_name' => $_product->get_name(),
									),
									$_product,
									false
								);
								$product_quantity .= '<button type="button" class="plus ins-cart-plus">' . $plus_icon . '</button>';

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
								?>

							</div>
							<div class="ins-cart-item-total" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
								<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</div>

						</div>
						<!-- Single Cart Item End -->
						<?php
					}
				}
				?>
			</div>
		</div>

		<?php echo apply_filters( 'ins_show_items_upsells', '' ); ?>
	</div>

	<?php do_action( 'woocommerce_cart_contents' ); ?>



	<!-- Cart Footer Content -->
	<div class="ins-cart-footer-wrap">
		<div class="ins-cart-footer-content">
			<div class="ins-footer-cart-button">
				<div class="ins-cart-coupon">
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<!-- <label for="coupon_code"><?php //esc_html_e( 'Coupon:', 'woocommerce' );  ?></label>  -->
							<input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
								placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />

							<button type="submit"
								class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
								name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">
								<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>
							</button>

							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit"
						class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> ins-cart-coupon-updated-cart"
						name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
						<?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
					</button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>
				<div class="ins-empty-cart-button">
					<button class="ins-empty-cart">
						<?php esc_attr_e( 'Empty Cart', 'instantio' ); ?>
					</button>
				</div>
			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>

			<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

			<div class="ins-cart-collaterals cart-collaterals">
				<?php

				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */

				// do_action( 'woocommerce_cart_collaterals' );
				
				woocommerce_cart_totals();
				?>
			</div>
		</div>
	</div>

</form>



<?php //do_action( 'woocommerce_after_cart' );  ?>