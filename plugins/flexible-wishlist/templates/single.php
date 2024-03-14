<?php
/**
 * @var mixed[]           $items                    {
 * @type int              $id                       .
 * @type int|null         $product_id               .
 * @type string|null      $product_desc             .
 * @type \WC_Product|null $product                  .
 * @type string|null      $add_to_cart_url          .
 * @type int              $quantity                 .
 * @type string           $created_at               .
 * @type string           $endpoint_update_quantity .
 * @type string           $endpoint_remove_item     .
 * }
 * @var string            $single_url               .
 * @var string            $archive_url              .
 * @type int              $wishlist_id              .
 * @var bool              $is_author                .
 * @type bool             $quantity_enabled         .
 * @type string           $action_create_item       .
 * @type string[]         $allowed_socials          .
 * @var string[]          $i18n                     {
 * @type string           $create_idea_input        .
 * @type string           $create_idea_button       .
 * @type string           $back_to_archive          .
 * }
 * @package WPDesk\FlexibleWishlist
 */

?>

<?php do_action( 'flexible_wishlist/template_single/before_page' ); ?>
<div class="woocommerce">
	<p>
		<a href="<?php echo esc_attr( $archive_url ); ?>">
			<?php echo esc_html( sprintf( '&laquo; %s', $i18n['back_to_archive'] ) ); ?>
		</a>
	</p>
	<?php if ( isset( $_GET['success'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="woocommerce-message" role="alert">
			<?php echo esc_html( __( 'Your action have been processed successfully.', 'flexible-wishlist' ) ); ?>
		</div>
	<?php endif; ?>
	<?php do_action( 'flexible_wishlist/template_single/before_table' ); ?>
	<div class="woocommerce-cart-form">
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" data-fw-table>
			<thead>
			<tr>
				<th colspan="2"><?php echo esc_html( __( 'Product', 'flexible-wishlist' ) ); ?></th>
				<th><?php echo esc_html( __( 'Price', 'flexible-wishlist' ) ); ?></th>
				<?php if ( $quantity_enabled ) : ?>
					<th><?php echo esc_html( __( 'Quantity', 'flexible-wishlist' ) ); ?></th>
				<?php endif; ?>
				<th>&nbsp;</th>
				<?php if ( $is_author ) : ?>
					<th>&nbsp;</th>
				<?php endif; ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $items as $index => $item ) : ?>
				<tr class="woocommerce-cart-form__cart-item" data-fw-item-id="<?php echo esc_attr( $item['id'] ); ?>">
					<?php if ( $item['product'] ) : ?>
						<td data-title="<?php echo esc_attr( __( 'Product', 'flexible-wishlist' ) ); ?>">
							<a href="<?php echo esc_url( $item['product']->get_permalink() ); ?>">
								<?php echo wp_kses_post( $item['product']->get_image() ); ?>
							</a>
						</td>
						<td>
							<a href="<?php echo esc_url( $item['product']->get_permalink() ); ?>">
								<?php echo esc_html( $item['product']->get_name() ); ?>
							</a>
							<p>
								<small>
									<?php
									echo esc_html(
									/* translators: %s: date */
										sprintf( __( 'Added on: %s', 'flexible-wishlist' ), $item['created_at'] )
									);
									?>
								</small>
							</p>
						</td>
						<td data-title="<?php echo esc_attr( __( 'Price', 'flexible-wishlist' ) ); ?>">
							<?php echo wp_kses_post( $item['product']->get_price_html() ); ?>
						</td>
					<?php else : ?>
						<td colspan="3" data-title="<?php echo esc_attr( __( 'Product', 'flexible-wishlist' ) ); ?>">
							<?php echo esc_html( $item['product_desc'] ); ?>
							<p>
								<small>
									<?php
									echo esc_html(
									/* translators: %s: date */
										sprintf( __( 'Added on: %s', 'flexible-wishlist' ), $item['created_at'] )
									);
									?>
								</small>
							</p>
						</td>
					<?php endif; ?>
					<?php if ( $quantity_enabled ) : ?>
						<td class="product-quantity"
							data-title="<?php echo esc_attr( __( 'Quantity', 'flexible-wishlist' ) ); ?>">
							<?php if ( $is_author ) : ?>
								<form action="<?php echo esc_url( $item['endpoint_update_quantity'] ); ?>" method="post"
									data-fw-form>
									<input type="number" class="input-text qty"
										name="item_quantity"
										step="1" min="1" max="" inputmode="numeric" autocomplete="off"
										value="<?php echo esc_attr( $item['quantity'] ); ?>"
										data-fw-form-input>
								</form>
							<?php else : ?>
								<input type="number" class="input-text qty"
									value="<?php echo esc_attr( $item['quantity'] ); ?>"
									disabled>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<?php if ( $item['product'] ) : ?>
						<td style="text-align: center;">
							<?php if ( ! $item['product']->is_purchasable() ) : ?>
								<button type="button" class="button" disabled>
									<?php echo esc_html( __( 'Out of stock', 'flexible-wishlist' ) ); ?>
								</button>
							<?php elseif ( strpos( $item['add_to_cart_url'], 'add-to-cart=' ) !== false ) : ?>
								<a href="<?php echo esc_url( $item['add_to_cart_url'] ); ?>"
									class="button alt add_to_cart_button ajax_add_to_cart"
									data-product_id="<?php echo esc_attr( $item['product_id'] ); ?>"
									data-quantity="<?php echo esc_attr( $item['quantity'] ); ?>">
									<?php echo esc_html( $item['product']->add_to_cart_text() ); ?>
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $item['add_to_cart_url'] ); ?>"
									class="button alt">
									<?php echo esc_html( $item['product']->add_to_cart_text() ); ?>
								</a>
							<?php endif; ?>
						</td>
					<?php else : ?>
						<td style="text-align: center;">
							<form action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="s" value="<?php echo esc_attr( $item['product_desc'] ); ?>">
								<input type="hidden" name="post_type" value="product">
								<button type="submit" class="button alt">
									<?php echo esc_html( __( 'Search products', 'flexible-wishlist' ) ); ?>
								</button>
							</form>
						</td>
					<?php endif; ?>
					<?php if ( $is_author ) : ?>
						<td style="text-align: center;">
							<form action="<?php echo esc_url( $item['endpoint_remove_item'] ); ?>"
								method="post"
								data-fw-remove-item>
								<a href="#" class="remove">&times;</a>
							</form>
							<a href="#add-to-wishlist"
								class="fw-button fw-button--inline"
								data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>"
								data-product-idea="<?php echo esc_attr( $item['product_desc'] ); ?>"
								data-wishlist-id="<?php echo esc_attr( $wishlist_id ); ?>">
								<span class="fw-button-icon fw-button-icon--copy"></span>
							</a>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td colspan="6" class="actions">
					<?php if ( $is_author ) : ?>
						<div class="coupon">
							<form action="" method="post">
								<input type="hidden" name="wishlist_id"
									value="<?php echo esc_attr( $wishlist_id ); ?>">
								<input type="text" class="input-text" name="item_idea"
									placeholder="<?php echo esc_attr( $i18n['create_idea_input'] ); ?>"
									required />
								<button type="submit" class="button" name="fw_action"
									value="<?php echo esc_attr( $action_create_item ); ?>">
									<?php echo esc_html( $i18n['create_idea_button'] ); ?>
								</button>
							</form>
						</div>
					<?php endif; ?>
					<button type="button" class="button" fw-add-all-available>
						<?php echo esc_attr( __( 'Add available to cart', 'flexible-wishlist' ) ); ?>
					</button>
				</td>
			</tr>
			<?php if ( $allowed_socials ) : ?>
				<tr>
					<td colspan="6" class="fw-table-share">
						<div><?php esc_html_e( 'Share:', 'flexible-wishlist' ); ?></div>
						<ul class="fw-share-items">
							<?php if ( in_array( 'facebook', $allowed_socials, true ) ) : ?>
								<li class="fw-share-item">
									<a href="https://facebook.com/sharer/sharer.php?u=<?php echo esc_attr( rawurlencode( $single_url ) ); ?>"
										target="_blank"
										class="fw-share-button fw-share-button--facebook"></a>
								</li>
							<?php endif; ?>
							<?php if ( in_array( 'twitter', $allowed_socials, true ) ) : ?>
								<li class="fw-share-item">
									<a href="https://twitter.com/intent/tweet/?url=<?php echo esc_attr( rawurlencode( $single_url ) ); ?>"
										target="_blank"
										class="fw-share-button fw-share-button--twitter"></a>
								</li>
							<?php endif; ?>
							<?php if ( in_array( 'pinterest', $allowed_socials, true ) ) : ?>
								<li class="fw-share-item">
									<a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_attr( rawurlencode( $single_url ) ); ?>"
										target="_blank"
										class="fw-share-button fw-share-button--pinterest"></a>
								</li>
							<?php endif; ?>
							<?php if ( in_array( 'whatsapp', $allowed_socials, true ) ) : ?>
								<li class="fw-share-item">
									<a href="https://wa.me/?text=<?php echo esc_attr( rawurlencode( $single_url ) ); ?>"
										target="_blank"
										class="fw-share-button fw-share-button--whatsapp"></a>
								</li>
							<?php endif; ?>
							<?php if ( in_array( 'email', $allowed_socials, true ) ) : ?>
								<li class="fw-share-item">
									<a href="mailto:?body=<?php echo esc_attr( rawurlencode( $single_url ) ); ?>"
										target="_blank"
										class="fw-share-button fw-share-button--email"></a>
								</li>
							<?php endif; ?>
						</ul>
					</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php do_action( 'flexible_wishlist/template_single/after_table' ); ?>
</div>
<?php do_action( 'flexible_wishlist/template_single/after_page' ); ?>
