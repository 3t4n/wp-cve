<?php
/**
 * shortcode [wcpscwc_pdt_slider]
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Action for shortcode
 *
 * @since 2.5
 */
function wcpscwc_product_slider( $atts, $content = null ) {

	global $woocommerce_loop;

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ($_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json') ) {
		return '[products_slider]';
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wcpscwc-builder-shrt-prev">
					<div class="wcpscwc-builder-shrt-title"><span>'.esc_html__('Product Grid View - Shortcode', 'woo-product-slider-and-carousel-with-category').'</span></div>
					products_slider
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wcpscwc-builder-shrt-prev">
					<div class="wcpscwc-builder-shrt-title"><span>'.esc_html__('Product Grid View - Shortcode', 'woo-product-slider-and-carousel-with-category').'</span></div>
					products_slider
				</div>';
	}

	extract( shortcode_atts(array(
			'type'				=> 'products',
			'cats' 				=> '',
			'tax' 				=> 'product_cat',
			'limit' 			=> -1,
			'slide_to_show' 	=> 3,
			'slide_to_scroll' 	=> 3,
			'autoplay' 			=> 'true',
			'autoplay_speed' 	=> 3000,
			'speed' 			=> 300,
			'arrows' 			=> 'true',
			'dots' 				=> 'true',
			'rtl'  				=> '',
			'slider_cls'		=> 'products',
			'loop'				=> 'true',
			'order'				=> 'DESC',
			'orderby'			=> 'date',
			'extra_class'		=> '',
			'className'			=> '',
			'align'				=> '',
			'dev_param_1'		=> '',
			'dev_param_2'		=> '',
	), $atts, 'wcpscwc_pdt_slider') );

	$unique				= wcpscwc_get_unique();
	$limit				= wcpscwc_clean_number( $limit, -1, 'number' );
	$slide_to_show		= wcpscwc_clean_number( $slide_to_show, 3 );
	$slide_to_scroll	= wcpscwc_clean_number( $slide_to_scroll, 3 );
	$autoplay_speed		= wcpscwc_clean_number( $autoplay_speed, 3000 );
	$speed				= wcpscwc_clean_number( $speed, 300 );
	$autoplay			= ( $autoplay == 'false' )				? 'false'									: 'true';
	$arrows				= ( $arrows == 'false' )				? 'false'									: 'true';
	$dots				= ( $dots == 'false' )					? 'false'									: 'true';
	$loop				= ( $loop == 'false' )					? 'false'									: 'true';
	$cats				= ! empty( $cats )						? wcpscwc_clean( explode( ',', $cats ) )	: '';
	$tax				= ! empty( $tax )						? wcpscwc_clean( $tax ) 					: 'product_cat';
	$orderby			= ! empty( $orderby )					? wcpscwc_clean( $orderby ) 				: 'date';
	$order				= ( strtolower( $order ) == 'asc' ) 	? 'ASC' 									: 'DESC';
	$align				= ! empty( $align )						? "align {$align}"							: '';
	$slider_cls			= ! empty( $slider_cls )				? wcpscwc_sanitize_html_classes( $slider_cls ) : 'products';
	$extra_class		= wcpscwc_sanitize_html_classes( $extra_class .' '. $align .' '. $className );

	// For RTL
	if( empty( $rtl ) && is_rtl() ) {
		$rtl = 'true';
	} elseif( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}

	// Slider configuration
	$slider_conf = compact( 'slide_to_show', 'slide_to_scroll', 'autoplay', 'autoplay_speed', 'speed', 'arrows','dots', 'rtl', 'slider_cls', 'loop' );

	// Enqueue Scripts
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wcpscwc-public-jquery' );

	// setup query
	$args = array(
		'post_type'				=> 'product',
		'post_status' 			=> 'publish',
		'posts_per_page'		=> $limit,
		'order'					=> $order,
		'orderby'				=> $orderby,
		'ignore_sticky_posts'   => 1,
	);

	// Category Parameter
	if( ! empty( $cats ) ) {
		$args['tax_query'] = array(
								array( 
									'taxonomy' 	=> $tax,
									'terms' 	=> $cats,
									'field' 	=> 'id',
								));
	}

	if( $type == 'featured' ) {

		// Backward compatibility
		if( wcpscwc_wc_version('3.0', '<') ) {
			$args['meta_query'] = array(
									// get only products marked as featured
									array(
										'key'		=> '_featured',
										'value'		=> 'yes',
										'compare'	=> '=',
									)
								);
		} else {
			$args['tax_query'][] = array(
									'taxonomy'	=> 'product_visibility',
									'field'		=> 'name',
									'terms'		=> 'featured',
									'operator'	=> 'IN',
								);
		}

	} elseif( $type == 'bestselling' ) {

		$args['orderby']	= 'meta_value_num';
		$args['meta_query']	= array(
								array(
									'key'		=> 'total_sales',
									'value'		=> 0,
									'compare'	=> '>',
								));
	}

	// query database
	$products = new WP_Query( $args );

	ob_start();

	if( $products->have_posts() ) : ?>
		<div class="wcpscwc-product-slider-wrap <?php echo esc_attr( $extra_class ); ?>">
			<div class="woocommerce wcpscwc-product-slider" id="wcpscwc-product-slider-<?php echo esc_attr( $unique ); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $slider_conf ) ); ?>">
			<?php
				woocommerce_product_loop_start();

				while ( $products->have_posts() ) : $products->the_post(); 
					if( wcpscwc_wc_version() ) {
						wc_get_template_part( 'content', 'product' ); 
					} else {
						woocommerce_get_template_part( 'content', 'product' );
					}
				endwhile; // end of the loop

				woocommerce_product_loop_end();
			?>
			</div>
		</div>
	<?php endif;

	wp_reset_postdata();
	$content .= ob_get_clean();

	return $content;
}

// Add Shortcode Product Slider
add_shortcode( 'wcpscwc_pdt_slider', 'wcpscwc_product_slider' );