<?php

if ( $query_result->have_posts() ) {
	while ( $query_result->have_posts() ) :
		$query_result->the_post();

		$params['image_dimension'] = $this_shortcode->get_slider_item_image_dimension( $params );
		$params['item_classes']    = $this_shortcode->get_item_classes( $params );

		echo apply_filters( 'qi_addons_for_elementor_filter_blog_slider_sc_layout_path', qi_addons_for_elementor_get_list_sc_template_part( 'blog/shortcodes/blog-slider', 'layouts/' . $layout, get_post_format(), $params ), $layout, get_post_format(), $params );
	endwhile; // End of the loop.
} else {
	// Include global posts not found
	qi_addons_for_elementor_template_part( 'content', 'templates/parts/posts-not-found' );
}

wp_reset_postdata();
