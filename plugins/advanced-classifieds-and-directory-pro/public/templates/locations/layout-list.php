<?php

/**
 * Layout List.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! function_exists( '__acadp_render_locations_list' ) ) {
	function __acadp_render_locations_list( $__attributes ) {	
		if ( $__attributes['depth'] <= 0 ) {
			return;
		}
			
		$terms = get_terms( 
			'acadp_locations',
			array(
				'orderby'      => $__attributes['orderby'], 
				'order'        => $__attributes['order'],
				'hide_empty'   => ! empty( $__attributes['hide_empty'] ) ? 1 : 0, 
				'parent'       => $__attributes['term_id'],
				'hierarchical' => false
			) 
		);
		
		$html = '';
					
		if ( count( $terms ) > 0 ) {			
			$__attributes['depth'] = $__attributes['depth'] - 1;
				
			if ( $__attributes['indent'] ) {
				$html .= '<ul class="acadp-m-0 acadp-ms-4 acadp-p-0 acadp-list-none">';
			} else {
				$html .= '<ul class="acadp-m-0 acadp-p-0 acadp-list-none">';
			}
								
			foreach ( $terms as $term ) {
				$__attributes['term_id'] = $term->term_id;
				$__attributes['indent'] = true;

				$count = (int) acadp_get_listings_count_by_location( $term->term_id, $__attributes['pad_counts'] );					
				
				$html .= '<li class="acadp-m-0 acadp-p-0 acadp-list-none">'; 

				$html .= '<div class="acadp-flex acadp-gap-1 acadp-items-center">';
				$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="12px" height="12px" fill="currentColor" class="acadp-flex-shrink-0 rtl:acadp-rotate-180">
					<path fill-rule="evenodd" d="M10.21 14.77a.75.75 0 01.02-1.06L14.168 10 10.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
					<path fill-rule="evenodd" d="M4.21 14.77a.75.75 0 01.02-1.06L8.168 10 4.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
				</svg>';
				
				$html .= sprintf( 
					'<a href="%s">%s %s</a>', 
					esc_url( acadp_get_location_page_link( $term ) ),
					esc_html( $term->name ),
					( ! empty( $__attributes['show_count'] ) ? '(' . $count . ')' : '' )
				);

				$html .= '</div>';	
				
				$html .= __acadp_render_locations_list( $__attributes );

				$html .= '</li>';	
			}	
				
			$html .= '</ul>';					
		}		
				
		return $html;
	}
}
?>

<div class="acadp acadp-locations acadp-layout-list">
	<ul class="acadp-grid acadp-grid-cols-1 acadp-m-0 acadp-p-0 acadp-list-none md:acadp-grid-cols-<?php echo (int) $attributes['columns']; ?> md:acadp-gap-6">
		<?php			
		$attributes['depth'] = (int) $attributes['depth'] - 1;
			
		foreach ( $terms as $term ) {			
			$attributes['term_id'] = $term->term_id;
			$attributes['indent'] = false;

			$count = (int) acadp_get_listings_count_by_location( $term->term_id, $attributes['pad_counts'] );					

			echo '<li class="acadp-m-0 acadp-p-0 acadp-list-none">';

			echo sprintf( 
				'<a href="%s">%s %s</a>', 
				esc_url( acadp_get_location_page_link( $term ) ),
				esc_html( $term->name ),
				( ! empty( $attributes['show_count'] ) ? '(' . $count . ')' : '' )
			);
			
			echo __acadp_render_locations_list( $attributes );

			echo '</li>';					
		}
		?>
	</ul>
</div>

<?php 
// Share buttons
include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/share-buttons.php' );