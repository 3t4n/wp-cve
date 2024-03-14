<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'woocommerce_product_builder_single_top', $products, $max_page );
?>
<div class="woocommerce-product-builder-wrapper">
	<div class="woocommerce-product-builder-content">
		<?php
		do_action( 'woocommerce_product_builder_single_product_content_before', $products, $max_page );
		?>
		<div class="woopb-products">
			<?php
			if ( count( $products ) ) {
				global $post, $product;
				$original_post_id = $post->ID;
				?>

				<?php foreach ( $products as $product_id ) { ?>
					<div class="woopb-product">
						<?php
						$product = wc_get_product( $product_id );
						$post    = get_post( $product_id );
						do_action( 'woocommerce_product_builder_single_product_content', $original_post_id ); ?>
					</div>
				<?php }
				wp_reset_postdata();
				?>
			<?php } else {
				echo '<h2>' . esc_html__( 'Products are not found.', 'woo-product-builder' ) . '</h2>';
			} ?>
		</div>
		<?php do_action( 'woocommerce_product_builder_single_product_content_after', $products, $max_page ); ?>
	</div>
</div>
<?php do_action( 'woocommerce_product_builder_single_bottom', $products, $max_page ); ?>
