<?php

if ( ! empty( $taxonomy_items ) ) {
	foreach ( $taxonomy_items as $taxonomy_item ) {
		$params['category_slug'] = $taxonomy_item->slug;
		$params['category_name'] = $taxonomy_item->name;
		$params['category_id']   = $taxonomy_item->term_id;
		$params['item_classes']  = $this_shortcode->get_item_classes( $params );

		qi_addons_for_elementor_list_sc_template_part( 'plugins/woocommerce/shortcodes/product-category-list', 'layouts/' . $layout, '', $params );
	}
} else {
	// Include global posts not found
	qi_addons_for_elementor_template_part( 'content', 'templates/parts/posts-not-found' );
}
