<?php
/**
 * `sp_testimonials_slider` Shortcode
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to handle testimonial slider shortcode
 * 
 * @since 1.0
 */
function wptww_get_testimonial_slider( $atts, $content = null ){

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ($_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json') ) {
		return '<div class="wtwp-builder-shrt-prev">
					<div class="wtwp-builder-shrt-title"><span>'.esc_html__( 'Testimonials Slider - Shortcode', 'wp-testimonial-with-widget' ).'</span></div>
					[sp_testimonials_slider]
				</div>';
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wtwp-builder-shrt-prev">
					<div class="wtwp-builder-shrt-title"><span>'.esc_html__( 'Testimonials Slider - Shortcode', 'wp-testimonial-with-widget' ).'</span></div>
					sp_testimonials_slider
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wtwp-builder-shrt-prev">
					<div class="wtwp-builder-shrt-title"><span>'.esc_html__( 'Testimonials Slider - Shortcode', 'wp-testimonial-with-widget' ).'</span></div>
					sp_testimonials_slider
				</div>';
	}

	extract(shortcode_atts(array(
		'limit' 			=> -1,
		'design'            => 'design-1',
		'orderby' 			=> 'date',
		'order' 			=> 'DESC',
		'slides_column'     => 1,
		'slides_scroll'     => 1,
		'category' 			=> '',
		'display_client' 	=> true,
		'display_avatar' 	=> true,
		'display_job' 		=> true,
		'display_company' 	=> true,
		'image_style'       => 'circle',
		'dots'     			=> "true",
		'arrows'     		=> "true",
		'autoplay'     		=> "true",
		'autoplay_interval' => 3000,
		'speed'             => 300,
		'size' 				=> 100,
		'display_quotes'	=> 'true',
		'adaptive_height'   => 'false',
		'rtl'				=> false,
		'className'			=> '',
		'align'				=> '',
		'extra_class'		=> '',
	), $atts, 'sp_testimonials_slider'));

	$unique = wtwp_get_unique();

	$testimonialsdesign	= wptww_designs();
	$limit				= ! empty( $limit ) 					? $limit 									: -1;
	$design 			= ( $design && ( array_key_exists( trim( $design ), $testimonialsdesign ) ) ) ? trim( $design ) : 'design-1';
	$orderby			= ! empty( $orderby ) 					? $orderby 									: 'date';
	$order				= ( strtolower( $order ) == 'asc' ) 	? 'ASC' 									: 'DESC';
	$slides_column		= ! empty( $slides_column ) 			? $slides_column							: 1;
	$slides_scroll		= ! empty( $slides_scroll ) 			? $slides_scroll 							: 1;
	$category 			= ! empty( $category )					? explode(',',$atts['category']) 			: '';
	$display_client 	= ( $display_client == 'true' ) 		? 1 										: 0;
	$display_avatar 	= ( $display_avatar == 'true' ) 		? 1 										: 0;
	$display_job 		= ( $display_job == 'true' ) 			? 1 										: 0;
	$display_company	= ( $display_company == 'true' ) 		? 1 										: 0;
	$display_quotes		= ( $display_quotes == 'true' ) 		? 1 										: 0;
	$image_style 		= ( $image_style == 'circle' ) 			? 'wptww-circle' 							: 'wptww-square';
	$dots 				= ( $dots == 'true' ) 					? 'true' 									: 'false';
	$arrows				= ( $arrows == 'true' ) 				? 'true' 									: 'false';
	$adaptive_height	= ( $adaptive_height == 'true' ) 		? 'true' 									: 'false';
	$autoplay			= ( $autoplay == 'true' ) 				? 'true' 									: 'false';
	$autoplay_interval 	= ! empty( $autoplay_interval ) 		? $autoplay_interval 						: 3000;
	$speed				= ! empty( $speed ) 					? $speed									: 300;
	$size 				= ! empty( $size  ) 					? $size  									: 100;
	$align				= ! empty( $align )						? 'align'.$align							: '';
	$extra_class		= $extra_class .' '. $align .' '. $className;
	$extra_class		= wtwp_sanitize_html_classes( $extra_class );

	// For RTL
	if( empty( $rtl ) && is_rtl() ) {
		$rtl = 'true';
	} elseif ( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}

	// Shortcode file
	$testimonials_design_file_path 	= WTWP_DIR . '/templates/designs/' . $design . '.php';
	$design_file 					= ( file_exists( $testimonials_design_file_path ) ) ? $testimonials_design_file_path : '';

	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wtwp-public-script' );

	// Slider configuration
	$slider_conf = compact( 'slides_column', 'slides_scroll', 'dots', 'arrows', 'autoplay', 'autoplay_interval', 'rtl', 'speed', 'adaptive_height');

	// Taking some globals
	global $post;

	// Query Parameter
	$args = array (
		'post_type' 		=> WTWP_POST_TYPE,
		'post_status'		=> array( 'publish' ),
		'order' 			=> $order,
		'orderby' 			=> $orderby,
		'posts_per_page' 	=> $limit,
	);

	// Category Parameter
	if( ! empty( $category ) ) {

		$args['tax_query'] = array(
								array(
									'taxonomy' 	=> WTWP_CAT,
									'field' 	=> 'term_id',
									'terms' 	=> $category,
								));

	} 

	// WP Query
	$query		= new WP_Query($args);
	$post_count = $query->post_count;
	ob_start();
	?>
	<div class="wtwp-testimonials-slider-wrp wptww-clearfix <?php echo esc_attr( $extra_class ); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $slider_conf ) ); ?>">
		<div id="wptww-testimonials-slidelist-<?php echo esc_attr( $unique ); ?>" class="wptww-testimonials-slidelist <?php echo esc_attr( $design ); ?>">
		<?php
		// If post is there
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$author_image		= wtwp_get_image( $post->ID, $size, $image_style );
				$author				= get_post_meta( $post->ID, '_testimonial_client', true );
				$job_title			= get_post_meta( $post->ID, '_testimonial_job', true );
				$company			= get_post_meta( $post->ID, '_testimonial_company', true );
				$url				= get_post_meta( $post->ID, '_testimonial_url', true );
				$testimonial_title	= get_the_title();
				$css_class 			= 'wptww-quote';

				// Add a CSS class if no image is available.
				if ( isset( $post->image ) && ( '' == $post->image ) ) {
					$css_class .= ' no-image';
				}
				// Include shortcode html file
				if( $design_file ) {
					include( $design_file );
					}
			endwhile;
		} ?>
		</div>
	</div>

	<?php
	wp_reset_postdata(); // Reset WP Query

	$content .= ob_get_clean();
	return $content;
}

// Testimonial Slider Shortcode
add_shortcode( 'sp_testimonials_slider', 'wptww_get_testimonial_slider' );