<?php

/**
 * List Layout.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! function_exists( '__acadp_widget_render_categories_list' ) ) {
	function __acadp_widget_render_categories_list( $__attributes ) {
		if ( $__attributes['imm_child_only'] ) {		
			if ( $__attributes['term_id'] > $__attributes['parent'] && ! in_array( $__attributes['term_id'], $__attributes['ancestors'] ) ) {
				return;
			}			
		}

		$terms = get_terms( 
			'acadp_categories',
			array(
				'orderby'      => $__attributes['orderby'], 
				'order'        => $__attributes['order'],
				'hide_empty'   => $__attributes['hide_empty'], 
				'parent'       => $__attributes['term_id'],
				'hierarchical' => ! empty( $__attributes['hide_empty'] ) ? true : false
			) 
		);

		$html = '';
					
		if ( count( $terms ) > 0 ) {
			$has_indent = $__attributes['indent'];

			if ( $has_indent ) {
				$html .= '<ul class="acadp-m-0 acadp-ms-4 acadp-p-0 acadp-list-none">';
			} else {
				$html .= '<ul class="acadp-m-0 acadp-p-0 acadp-list-none">';
			}
							
			foreach ( $terms as $term ) {
				$__attributes['term_id'] = $term->term_id;
				$__attributes['indent'] = true;

				$count = 0;
				if ( ! empty( $__attributes['hide_empty'] ) || ! empty( $__attributes['show_count'] ) ) {
					$count = (int) acadp_get_listings_count_by_category( $term->term_id, $__attributes['pad_counts'] );
					if ( ! empty( $__attributes['hide_empty'] ) && 0 == $count ) continue;
				}

				$html .= '<li class="acadp-m-0 acadp-p-0 acadp-list-none">'; 
				$html .= '<div class="acadp-flex acadp-gap-1 acadp-items-center">';

				if ( $has_indent ) {
					$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="12px" height="12px" fill="currentColor" class="acadp-flex-shrink-0 rtl:acadp-rotate-180">
						<path fill-rule="evenodd" d="M10.21 14.77a.75.75 0 01.02-1.06L14.168 10 10.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
						<path fill-rule="evenodd" d="M4.21 14.77a.75.75 0 01.02-1.06L8.168 10 4.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
					</svg>';
				}

				$html .= sprintf(
					'<a href="%s">%s %s</a>',
					esc_url( acadp_get_category_page_link( $term ) ),
					esc_html( $term->name ),
					( ! empty( $__attributes['show_count'] ) ? ' (' . $count . ')' : '' )
				);

				$html .= '</div>';	
				
				$html .= __acadp_widget_render_categories_list( $__attributes );

				$html .= '</li>';	
			}	
			
			$html .= '</ul>';					
		}		
			
		return $html;
	}
}
?>

<div class="acadp acadp-widget acadp-categories acadp-layout-list">
    <?php 
	$attributes['indent'] = false;
	$categories_list = __acadp_widget_render_categories_list( $attributes ); 

	if ( ! empty( $categories_list ) ) {	
		echo $categories_list;			
	} else {
		echo '<span>' . __( 'No categories found', 'advanced-classifieds-and-directory-pro' ) . '</span>';
	}
	?>
</div>