<?php

global $product, $post;

use Elementor\Plugin;

$post_type = $post->post_type; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

$get_all_product = array(
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
		$post         = get_post( $product_id, OBJECT ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		setup_postdata( $post );
		?>
		<div class="xpro-product-image-wrapper">
			<?php wc_get_template( 'single-product/product-image.php' ); ?>
		</div>
		<?php
		wp_reset_postdata();
	endif;
}
