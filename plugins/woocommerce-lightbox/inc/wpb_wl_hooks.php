<?php

/**
 * Woocommerce Lighbox by WpBean
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


add_action( 'woocommerce_after_shop_loop_item', 'wpb_wl_hook_quickview_link', 11 );

function wpb_wl_hook_quickview_link(){
	echo '<div class="wpb_wl_preview_area"><span class="wpb_wl_preview open-popup-link" data-mfp-src="#wpb_wl_quick_view_'.get_the_id().'" data-effect="mfp-zoom-in">'. apply_filters( 'wpb_wl_quick_view_btn_text', esc_html__( 'Quick View', 'woocommerce-lightbox' ) ) .'</span></div>';
}


add_action( 'woocommerce_after_shop_loop_item', 'wpb_wl_hook_quickview_content' );

function wpb_wl_hook_quickview_content(){
	global $post, $woocommerce, $product;

	$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
	$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
	$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
	$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
	$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . $placeholder,
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	) );
	$attachment_ids = $product->get_gallery_image_ids();
	$gallery_id = rand(100,1000);

	?>
	<div id="wpb_wl_quick_view_<?php echo get_the_id(); ?>" class="mfp-hide mfp-with-anim wpb_wl_quick_view_content wpb_wl_clearfix product">
		<div class="wpb_wl_images">
			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
				<figure class="woocommerce-product-gallery__wrapper">
					<?php
					$attributes = array(
						'title'                   => $image_title,
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
					);

					if ( has_post_thumbnail() ) {
						$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '" data-fancybox="gallery-'. esc_attr( $gallery_id ) .'">';
						$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
						$html .= '</a></div>';
					} else {
						$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						$html .= '</div>';
					}

					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

					?>
				</figure>

				<?php if ( $attachment_ids && has_post_thumbnail() ): ?>
					<div class="thumbnails <?php echo esc_attr( 'columns-' . $columns ) ?>">
						<?php 
							$loop = 0;
							foreach ( $attachment_ids as $attachment_id ) {
								$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
								$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
								$image_title     = get_post_field( 'post_excerpt', $attachment_id );
								$gallery_classes = array();

								$attributes = array(
									'title'                   => $image_title,
									'data-src'                => $full_size_image[0],
									'data-large_image'        => $full_size_image[0],
									'data-large_image_width'  => $full_size_image[1],
									'data-large_image_height' => $full_size_image[2],
								);

								if ( $loop == 0 || $loop % $columns == 0 ){
									$gallery_classes[] = 'first';
								}

								if ( ( $loop + 1 ) % $columns == 0 ){
									$gallery_classes[] = 'last';
								}

								$gallery_classes = esc_attr( implode( ' ', $gallery_classes ) );

								$html  = '<a class="'. $gallery_classes .'" href="' . esc_url( $full_size_image[0] ) . '" data-fancybox="gallery-'. esc_attr( $gallery_id ) .'">';
								$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
						 		$html .= '</a>';

								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );

								$loop++;
							}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="wpb_wl_summary">
			<!-- Product Title -->
			<h2 class="wpb_wl_product_title"><?php the_title();?></h2>

			<!-- Product Price -->
			<?php if ( $price_html = $product->get_price_html() ) : ?>
				<span class="price wpb_wl_product_price"><?php echo $price_html; ?></span>
			<?php endif; ?>

			<!-- Product short description -->
			<?php woocommerce_template_single_excerpt();?>

			<!-- Product cart link -->
			<?php woocommerce_template_single_add_to_cart();?>

		</div>
	</div>
	<?php
}



/**
 * Add body class
 */

add_filter( 'body_class', 'wpb_wl_add_body_class' );
function wpb_wl_add_body_class( $classes ){

	$classes[] = 'wpb-wl-woocommerce';
	$classes[] = 'woocommerce';

	return $classes;
}