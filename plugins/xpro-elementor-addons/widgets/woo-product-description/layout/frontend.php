<?php

use Elementor\Plugin;

if ( class_exists( 'WooCommerce' ) ) {
	$limit = ( 'excerpt' === $settings['content_type'] && $settings['limit']['size'] ) ? $settings['limit']['size'] : '';

	global $product, $post;
	$post_type      = $post->post_type; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
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
	if ( is_single() && 'xpro-themer' !== $post_type && 'xpro-content' !== $post_type && 'product' === $post_type ) {
		$product_id = get_the_id();
	} else {
		if ( ! empty( $get_product_id ) ) {
			$product_id = $first_product_id;
		}
	}

	?>
	<div class="xpro-elementor-content">
		<div class="xpro-woo-product-desc-cls">
			<?php
			if ( 'excerpt' === $settings['content_type'] ) :
				if ( ! empty( $product_id ) ) :
					$excerpt = get_the_excerpt( $product_id );
					xpro_elementor_kses( wp_trim_words( wp_strip_all_tags( $excerpt ), $limit ) );
				else :
					if ( Plugin::$instance->editor->is_edit_mode() && empty( $product_id ) ) {
						$content = '<p>This is a dummy text that will be replaced with the excerpt.</p>';
						echo wp_kses_post( $content );
					}
				endif;
			else :
				if ( ! empty( $product_id ) ) :
					xpro_elementor_kses( get_post_field( 'post_content', $product_id ) );
				else :
					if ( Plugin::$instance->editor->is_edit_mode() && empty( $product_id ) ) {
						$content = '<p>This is a dummy text that will be replaced with the content.</p>';
						echo wp_kses_post( $content );
					}
				endif;
			endif;
			?>
		</div>
	</div>
	<?php
}
