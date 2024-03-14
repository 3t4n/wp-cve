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

	$thumbnail_id 	= get_term_meta( $term->term_id, 'thumbnail_id', true );
	$image_size  	= apply_filters( 'wpb_wcs_image_size', 'medium_large' );
	$thumbnail_img 	= wp_get_attachment_image_src( $thumbnail_id, $image_size );

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

	<?php
		do_action( 'wpb_woo_cat_slider_before_thumb', $term->term_id );

		if( $thumbnail_id ){
			printf( '<div class="wpb-woo-cat-item-image"><a href="%s"><img src="%s" alt="%s" class="wp-post-image" /></a></div>',esc_url( get_term_link( $term->term_id ) ),  esc_url( $thumbnail_img[0] ), esc_html( $term->name ) );
		}else{
			printf( '<div class="wpb-woo-cat-item-image"><a href="%s"><img src="%s" alt="%s" class="wp-post-image" /></a></div>',esc_url( get_term_link( $term->term_id ) ),  esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', WPB_WCS_TEXTDOMAIN ) );
		}

		do_action( 'wpb_woo_cat_slider_after_thumb', $term->term_id );
	?>

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

		<?php if( $need_btn ): ?>
			<a class="btn btn-primary button" href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>"><?php echo esc_html( $btn_text ) ?></a>
		<?php endif; ?>
	</div>

</div>