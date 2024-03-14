<?php
if ( $query_result->have_posts() ) {
	while ( $query_result->have_posts() ) :
		$query_result->the_post();

		$params['item_classes']  = $this_shortcode->get_item_classes( $params );
		$params['button_params'] = $this_shortcode->generate_button_params( $params );

		qi_addons_for_elementor_list_sc_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'layouts/' . $layout, '', $params );
	endwhile; // End of the loop.
} else {
	// Include global posts not found
	qi_addons_for_elementor_template_part( 'content', 'templates/parts/posts-not-found' );
}

wp_reset_postdata();
