<?php

$product     = $this->get_product( get_post_type() );
$editor_mode = ( \Elementor\Plugin::$instance->editor->is_edit_mode() || is_preview() );

if ( empty($product) && $editor_mode ) {
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

if ( ! $product ) {
	return;
}

if ( $editor_mode ) {

	global $wp_query, $post;
	$main_query = clone $wp_query;
	$main_post  = clone $post;

	$wp_query = new \WP_Query( array() );

}

woocommerce_template_single_add_to_cart();

if ( $editor_mode ) {
	$wp_query = $main_query;
	$post     = $main_post;
	wp_reset_postdata();
}


