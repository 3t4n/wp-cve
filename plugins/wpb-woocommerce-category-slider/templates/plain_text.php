<?php

/**
 * WPB WooCommerce Category Slider Plugin
 *
 * Template file for category slider plain text loop
 *
 * Author: WpBean
 */
	
?>

<?php 
	extract($data->atts);
	$term = $data->term;

	$active 			= '';
	$queried_object 	= get_queried_object();
	if($queried_object && $queried_object->term_id == $term->term_id){
		$active = ' wpb-wcs-current-cat';
	}
?>

<div class="wpb-woo-cat-item <?php echo esc_attr( $active ); ?>">
	<a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>">

		<?php echo esc_html( $term->name ); ?>

		<?php if( $need_cat_count == 'on' ): ?>
			<span class="wpb-woo-cat-item-count">(<?php echo esc_html( $term->count ); ?>)</span>
		<?php endif; ?>

	</a>
</div>