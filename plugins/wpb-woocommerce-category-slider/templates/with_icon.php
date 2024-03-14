<?php

/**
 * WPB WooCommerce Category Slider Plugin
 *
 * Template file for category slider with icon loop
 *
 * Author: WpBean
 */
	
?>

<?php

	extract($data->atts);
	$term = $data->term;
	
	$term_meta = get_option( "taxonomy_$term->term_id" );

	$icon_class = '';
	if( is_array($term_meta) && array_key_exists('wpb_wcs_cat_icons', $term_meta) ){
		$icon_class = $term_meta['wpb_wcs_cat_icons'];
	}

	$child_cat_args = array(
		'taxonomy'           => 'product_cat',
		'hide_empty'		 => 0,
		'child_of'			 => $term->term_id
	);

	$chaid_cat_terms = get_terms( $child_cat_args );

	$active 			= '';
	$queried_object 	= get_queried_object();
	if($queried_object && $queried_object->term_id == $term->term_id){
		$active = ' wpb-wcs-current-cat';
	}
?>

<div class="wpb-woo-cat-item<?php echo esc_attr( $active ); ?>">

	<?php if( $icon_class ): ?>

		<div class="wpb-woo-cat-item-icon">
			<a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>"><i class="fa-3x <?php echo esc_attr( $icon_class ); ?>"></i></a>
		</div>

	<?php endif; ?>

	<div class="wpb-woo-cat-item-content">

		<h3><a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>"><?php echo esc_html( $term->name ); ?></a></h3>	

		<?php if( $term->description && $need_description == 'on' ): ?>

			<div class="wpb-wcs-description">
				<?php echo esc_html( $term->description ); ?>
			</div>

		<?php endif; ?>

		<?php if( !empty( $chaid_cat_terms ) && $need_child_cat == 'on' ): ?>

			<ul class="wpb-wcs-sub-categories">
			   	<?php
			   		foreach ( $chaid_cat_terms as $chaid_cat_term ) {
			   			printf( '<li><a href="%s">%s</a></li>', esc_url( get_term_link( $chaid_cat_term->term_id ) ), $chaid_cat_term->name );
			   		}
			   	?>
			</ul>

		<?php endif; ?>

	</div>
	
</div>