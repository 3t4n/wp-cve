<?php
global $product, $post;

$post_type = $post->post_type; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

$get_all_product = array(
	'orderby'     => 'date',
	'numberposts' => -1,
	'order'       => 'ASC',
	'return'      => 'ids',
	'status'      => 'publish'
);

$get_all_product_ids = wc_get_products( $get_all_product );

if ( empty( $product ) && \Elementor\Plugin::$instance->editor->is_edit_mode() && ( empty( $get_all_product_ids ) ) ) {
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
			$post         = get_post( $product_id, OBJECT ); //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			setup_postdata( $post );
			do_action( 'xpro_elementor_woo_before_product' );
			?>

		<div class="xpro-woo-themer-module-wrapper woocommerce clearfix">
			<div class="xpro-woo-themer-module-layout-cls">
				<div class="xpro-woo-product-price-cls">
					<?php xpro_elementor_kses( $product->get_price_html() ); ?>
				</div>
			</div>
		</div>
			<?php
			do_action( 'xpro_elementor_woo_after_product' );
			wp_reset_postdata();
		endif;
	}
