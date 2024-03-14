<?php

use Elementor\Plugin;

global $product, $post;

$post_type = $post->post_type; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

$get_all_product     = array(
	'orderby'     => 'date',
	'numberposts' => - 1,
	'order'       => 'ASC',
	'return'      => 'ids',
	'status'      => 'publish',
);
$get_all_product_ids = wc_get_products( $get_all_product );

if ( empty( $product ) && Plugin::$instance->editor->is_edit_mode() && ( empty( $get_all_product_ids ) ) ) {
	?>
	<div class="xpro-alert xpro-alert-warning" role="alert">
		<span class="xpro-alert-title">
			<?php esc_html_e( 'Product Not Found', 'xpro-elementor-addons' ); ?>
		</span>
		<span class="xpro-alert-description">
				<?php esc_html_e( 'You dont have any product please add some product first. This text will disappear after closing the editor mode.', 'xpro-elementor-addons' ); ?>
		</span>
	</div>
	<?php
	return;
}

$args           = array(
	'limit'   => 1,
	'orderby' => 'date',
	'order'   => 'ASC',
	'return'  => 'ids',
	'status'  => 'publish',
);
$get_product_id = wc_get_products( $args );
if ( $get_product_id ) {
	$first_product_id = $get_product_id[0];
}
if ( is_single() && 'xpro-themer' !== $post_type && 'xpro_content' !== $post_type && 'product' === $post_type ) {
	$product_id = get_the_id();
} else {
	if ( ! empty( $get_product_id ) ) {
		$product_id = $first_product_id;
	}
}

if ( isset( $product_id ) && '' !== $product_id ) {
	$product = wc_get_product( $product_id );
	if ( $product ) :
		$product_data = $product->get_data();
		$post         = get_post( $product_id, OBJECT );  // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		setup_postdata( $post );
		do_action( 'xpro_elementor_woo_before_product' );
		?>

		<div class="xpro-woo-themer-module-wrapper woocommerce clearfix">
			<div class="xpro-woo-themer-module-layout-cls">
				<?php
				//rating star layout
				$rating_count = $product->get_rating_count();
				$review_count = $product->get_review_count();
				$average      = $product->get_average_rating();
				if ( $rating_count > 0 ) :
					?>
					<?php do_action( 'xpro_elementor_woo_themer_module_before_rating_wrap', $settings, $product ); ?>
					<div class="xpro-woo-product-rating-wrapper woocommerce-product-rating">
						<?php xpro_elementor_kses( wc_get_rating_html( $average, $rating_count ) ); ?>
						<?php if ( comments_open( $product_id ) ) : ?>
							<a class="woocommerce-rating-count" rel="nofollow">
								(
								<?php
								/* translators: %s: Title */
								printf( _n( '%s customer review', '%s customer reviews', $review_count, 'xpro-elementor-addons' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
								)
							</a>
						<?php endif ?>
					</div>
					<?php do_action( 'xpro_elementor_woo_themer_module_after_rating_wrap', $settings, $product ); ?>
					<?php
				else :
					if ( Plugin::$instance->editor->is_edit_mode() ) {
						?>
						<div class="xpro-woo-product-not-rated"><?php echo esc_html__( 'Product Not Rated Yet.', 'xpro-elementor-addons' ); ?></div>
						<?php
					}
				endif;
				?>
			</div>
		</div>
		<?php
		do_action( 'xpro_elementor_woo_after_product' );
		wp_reset_postdata();
	endif;
}
