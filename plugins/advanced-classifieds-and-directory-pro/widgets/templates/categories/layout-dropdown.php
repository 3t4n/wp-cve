<?php

/**
 * Layout dropdown.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! function_exists( '__acadp_widget_render_categories_options' ) ) {
	function __acadp_widget_render_categories_options( $__attributes ) {
		if ( $__attributes['imm_child_only'] ) {		
			if ( $__attributes['term_id'] > $__attributes['parent'] && ! in_array( $__attributes['term_id'], $__attributes['ancestors'] ) ) {
				return;
			}			
		}
		
		$term_slug = get_query_var( 'acadp_category' );
		
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
			$prefix = $__attributes['prefix'];

			foreach ( $terms as $term ) {
				$__attributes['term_id'] = $term->term_id;

				$count = 0;
				if ( ! empty( $__attributes['hide_empty'] ) || ! empty( $__attributes['show_count'] ) ) {
					$count = (int) acadp_get_listings_count_by_category( $term->term_id, $__attributes['pad_counts'] );
					if ( ! empty( $__attributes['hide_empty'] ) && 0 == $count ) continue;
				}
			
				$html .= sprintf( 
					'<option value="%s" %s>%s %s', 
					esc_attr( $term->slug ), 
					selected( $term->slug, $term_slug, false ),
					$prefix . esc_html( $term->name ),
					( ! empty( $__attributes['show_count'] ) ? ' (' . $count . ')' : '' )
				);

				$__attributes['prefix'] = $prefix . '—';
				$html .= __acadp_widget_render_categories_options( $__attributes );

				$html .= '</option>';	
			}					
		}		
			
		return $html;
	}
}
?>

<div class="acadp acadp-widget acadp-categories acadp-layout-dropdown">
	<?php 
	$attributes['prefix'] = '';
	$dropdown_options = __acadp_widget_render_categories_options( $attributes );
	
	if ( ! empty( $dropdown_options ) ) : ?>
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="form">
			<select name="acadp_categories" class="acadp-form-control acadp-form-select" onchange="if ( this.options[ this.selectedIndex ].value !== '' ) this.form.submit();">
				<option value="">— <?php esc_html_e( 'Select category', 'advanced-classifieds-and-directory-pro' ); ?> —</option>
				<?php echo $dropdown_options; ?>
			</select>
		</form>
	<?php else : ?>
		<span>
			<?php esc_html_e( 'No categories found', 'advanced-classifieds-and-directory-pro' ); ?>
		</span>
	<?php endif; ?>
</div>