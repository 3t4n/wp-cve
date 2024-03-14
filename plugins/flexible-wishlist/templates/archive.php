<?php
/**
 * @var mixed[]  $wishlists              {
 * @type int     $id                     .
 * @type string  $name                   .
 * @type string  $url                    .
 * @type bool    $is_default             .
 * @type int     $items_count            .
 * @type string  $created_at             .
 * @type string  $endpoint_update_name   .
 * }
 * @var string   $action_toggle_default  .
 * @var string   $action_remove_wishlist .
 * @var string   $action_create_wishlist .
 * @var string[] $i18n                   {
 * @type string  $create_wishlist_input  .
 * @type string  $create_wishlist_button .
 * }
 * @package WPDesk\FlexibleWishlist
 */

?>

<?php do_action( 'flexible_wishlist/template_archive/before_page' ); ?>
<div class="woocommerce">
	<?php if ( isset( $_GET['success'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="woocommerce-message" role="alert">
			<?php echo esc_html( __( 'Your action have been processed successfully.', 'flexible-wishlist' ) ); ?>
		</div>
	<?php endif; ?>
	<?php do_action( 'flexible_wishlist/template_archive/before_table' ); ?>
	<div class="woocommerce-cart-form">
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" data-fw-table>
			<thead>
			<tr>
				<th><?php echo esc_html( __( 'Name', 'flexible-wishlist' ) ); ?></th>
				<th><?php echo esc_html( __( 'Primary choice for added products', 'flexible-wishlist' ) ); ?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $wishlists as $index => $wishlist ) : ?>
				<tr class="woocommerce-cart-form__cart-item"
					data-fw-wishlist-id="<?php echo esc_attr( $wishlist['id'] ); ?>">
					<td data-title="<?php echo esc_attr( __( 'Name', 'flexible-wishlist' ) ); ?>">
						<form action="<?php echo esc_url( $wishlist['endpoint_update_name'] ); ?>" method="post"
							data-fw-form>
							<input type="text" class="input-text"
								name="wishlist_name"
								value="<?php echo esc_attr( $wishlist['name'] ); ?>"
								placeholder="<?php echo esc_attr( __( 'Enter name', 'flexible-wishlist' ) ); ?>"
								data-fw-form-input>
						</form>
						<p>
							<small>
								<?php
								echo esc_html(
								/* translators: %s: date */
									sprintf( __( 'Created on: %s', 'flexible-wishlist' ), $wishlist['created_at'] )
								);
								?>
							</small>
						</p>
					</td>
					<td data-title="<?php echo esc_attr( __( 'Primary choice for added products', 'flexible-wishlist' ) ); ?>">
						<form action="" method="post">
							<input type="hidden" name="wishlist_id"
								value="<?php echo esc_attr( $wishlist['id'] ); ?>">
								<?php if ( ! $wishlist['is_default'] ) : ?>
									<button type="submit" class="button alt" name="fw_action"
										value="<?php echo esc_attr( $action_toggle_default ); ?>">
										<?php echo esc_html( __( 'Set as primary', 'flexible-wishlist' ) ); ?>
									</button>
								<?php else : ?>
									<button type="submit" class="button" name="fw_action"
										value="<?php echo esc_attr( $action_toggle_default ); ?>">
										<?php echo esc_html( __( 'Set as non-primary', 'flexible-wishlist' ) ); ?>
									</button>
								<?php endif; ?>
							</button>
						</form>
					</td>
					<td style="text-align: center;">
						<a href="<?php echo esc_url( $wishlist['url'] ); ?>" class="button alt">
							<?php
							echo esc_html(
							/* translators: %s: items count */
								sprintf( __( 'Show items (%s)', 'flexible-wishlist' ), $wishlist['items_count'] )
							);
							?>
						</a>
					</td>
					<td style="text-align: center;">
						<form action="" method="post">
							<input type="hidden" name="fw_action"
								value="<?php echo esc_attr( $action_remove_wishlist ); ?>">
							<input type="hidden" name="wishlist_id"
								value="<?php echo esc_attr( $wishlist['id'] ); ?>">
							<a href="#" class="remove"
								data-fw-remove-wishlist="<?php echo esc_attr( __( 'Are you sure you want to delete?', 'flexible-wishlist' ) ); ?>">&times;</a>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td colspan="4" class="actions">
					<div class="coupon">
						<form action="" method="post">
							<input type="text" class="input-text" name="wishlist_name"
								placeholder="<?php echo esc_attr( $i18n['create_wishlist_input'] ); ?>"
								required />
							<button type="submit" class="button" name="fw_action"
								value="<?php echo esc_attr( $action_create_wishlist ); ?>">
								<?php echo esc_html( $i18n['create_wishlist_button'] ); ?>
							</button>
						</form>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<?php do_action( 'flexible_wishlist/template_archive/after_table' ); ?>
</div>
<?php do_action( 'flexible_wishlist/template_archive/after_page' ); ?>
