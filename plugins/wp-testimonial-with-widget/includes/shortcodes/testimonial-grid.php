<?php 
/**
 * `sp_testimonials` Shortcode
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to handle testimonial shortcode
 * 
 * @since 1.0
 */
function wptww_get_testimonial( $atts, $content = null ){

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ($_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json') ) {
		return '<div class="wtwp-builder-shrt-prev">
					<div class="wtwp-builder-shrt-title"><span>'.esc_html__( 'Testimonials - Shortcode', 'wp-testimonial-with-widget' ).'</span></div>
					[sp_testimonials]
				</div>';
	}

	extract(shortcode_atts(array(
		'limit' 			=> -1,
		'design'            => 'design-1',
		'per_row' 			=> 3,
		'orderby' 			=> 'date',
		'order' 			=> 'DESC',
		'category' 			=> '',
		'display_client' 	=> true,
		'display_avatar' 	=> true,
		'display_job' 		=> true,
		'display_company' 	=> true,
		'image_style'       => 'circle',
		'size' 				=> 100,
		'display_quotes'	=> 'true',
		'className'			=> '',
		'align'				=> '',
		'extra_class'		=> '',
	), $atts, 'sp_testimonials' ) );

	$testimonialsdesign	= wptww_designs();
	$limit				= ! empty( $limit ) 					? $limit 									: -1;
	$per_row		    = ! empty( $per_row ) 					? $per_row 									: 3;
	$design 			= ( $design && ( array_key_exists( trim( $design ), $testimonialsdesign ) ) ) ? trim( $design ) 	: 'design-1';
	$orderby			= ! empty( $orderby ) 					? $orderby 									: 'date';
	$order				= ( strtolower( $order ) == 'asc' ) 	? 'ASC' 									: 'DESC';
	$category 			= ! empty( $category )					? explode( ',',$atts['category'] ) 			: '';
	$display_client 	= ( $display_client == 'true' ) 		? 1 										: 0;
	$display_avatar 	= ( $display_avatar == 'true' ) 		? 1 										: 0;
	$display_job 		= ( $display_job == 'true' ) 			? 1 										: 0;
	$display_company	= ( $display_company == 'true' ) 		? 1 										: 0;
	$display_quotes		= ( $display_quotes == 'true' ) 		? 1 										: 0;
	$image_style 		= ( $image_style == 'circle' ) 			? 'wptww-circle' 							: 'wptww-square';
	$size 				= ! empty( $size  ) 					? $size  									: 100;
	$align				= ! empty( $align )						? 'align'.$align							: '';
	$extra_class		= $extra_class .' '. $align .' '. $className;
	$extra_class		= wtwp_sanitize_html_classes( $extra_class );

	// Shortcode file
	$testimonials_design_file_path 	= WTWP_DIR . '/templates/designs/' . $design . '.php';
	$design_file 					= ( file_exists( $testimonials_design_file_path ) ) ? $testimonials_design_file_path : '';

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
	$query		= new WP_Query( $args );
	$post_count = $query->post_count;

	ob_start();
	?>

	 <div class="wptww-testimonials-list <?php echo esc_attr( $design ); ?> wptww-clearfix <?php echo esc_attr( $extra_class ); ?>">
		<?php
		// If post is there
		if ( $query->have_posts() ) {
			$count = 0;
			while ( $query->have_posts() ) : $query->the_post();
				$count++;

				$author_image		= wtwp_get_image( $post->ID, $size, $image_style );
				$author				= get_post_meta( $post->ID, '_testimonial_client', true );
				$job_title			= get_post_meta( $post->ID, '_testimonial_job', true );
				$company			= get_post_meta( $post->ID, '_testimonial_company', true );
				$url				= get_post_meta( $post->ID, '_testimonial_url', true );
				$testimonial_title	= get_the_title();

				$css_class = 'wptww-quote';

				// Add a CSS class if no image is available.
				if ( isset( $post->image ) && ( '' == $post->image ) ) {
					$css_class .= ' no-image';
				}
				if ( is_numeric($per_row) ) {
					if($per_row == 1){
						$per_row_grid = 12;
					}else if($per_row == 2){
						$per_row_grid = 6;
					}
					else if($per_row == 3){
						$per_row_grid = 4;
					}
					else if($per_row == 4){
						$per_row_grid = 3;
					}
					 else{
						$per_row_grid = $per_row;
					}
					$css_class .= ' wp-medium-'.$per_row_grid.' wpcolumns';
				}

				$css_class	.= ( $count % $per_row == 1 )	? ' wptww-first' : '';
				$css_class	.= ( $count % $per_row == 0 )	? ' wptww-last'	: '';

				// Include shortcode html file
				if( $design_file ) {
					include( $design_file );
					}

			endwhile;
		} ?>
	</div>
	<?php
	wp_reset_postdata(); // Reset WP Query

	$content .= ob_get_clean();
	return $content;
}

// Testimonial Grid Shortcode
add_shortcode( 'sp_testimonials', 'wptww_get_testimonial' );