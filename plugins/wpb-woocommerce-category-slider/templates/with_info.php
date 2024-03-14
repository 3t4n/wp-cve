<?php

/**
 * WPB WooCommerce Category Slider Plugin
 *
 * Template file for category slider with category info loop
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

<div class="wpb-woo-cat-item<?php echo esc_attr( $active ); ?>">

	<a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>">
		<h3><?php echo esc_html( $term->name ); ?></h3>
	</a>

	<?php if( $term->description && $need_description == 'on' ): ?>

		<div class="wpb-wcs-description">
			<?php echo esc_html( $term->description ); ?>
		</div>

	<?php endif; ?>

	<?php if( $need_btn ): ?>
		<a class="btn btn-primary button" href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>"><?php echo esc_html( $btn_text ) ?></a>
	<?php endif; ?>

</div>