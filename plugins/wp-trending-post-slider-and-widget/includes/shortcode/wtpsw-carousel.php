<?php
/**
 * 'wtpsw_carousel' Shortcode
 * 
 * @package WP Trending Post Slider and Widget
 * @since 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Handles Popuplar Post Gridbox
 * 
 * @since 1.5
 */
function wtpsw_popular_post_carousel( $atts, $content = null ) {

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ( $_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json' ) ) {
		return '[wtpsw_carousel]';
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wtpsw-builder-shrt-prev">
					<div class="wtpsw-builder-shrt-title"><span>'.esc_html__('Trending Post Slider - Shortcode', 'wtpsw').'</span></div>
					wtpsw_carousel
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wtpsw-builder-shrt-prev">
					<div class="wtpsw-builder-shrt-title"><span>'.esc_html__('Trending Post slider - Shortcode', 'wtpsw').'</span></div>
					wtpsw_carousel
				</div>';
	}

	global $wtpsw_options, $wtpsw_model, $wtpsw_view_by, $post;

	// Enqueue required script
	wp_dequeue_script( 'wtpsw-public-script' );
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wtpsw-public-script' );

	// Shortcode attributes
	extract( shortcode_atts( array(	
		'limit'						=> 10,
		'post_type'					=> 'post',
		'view_by'					=> 'views',
		'order'						=> 'DESC',
		'design'					=> 'design-1',
		'showdate'					=> 'true',
		'showauthor'				=> 'true',
		'showcontent'				=> 'false',
		'words_limit'				=> 40,
		'slides_to_show'			=> 3,
		'slides_to_scroll'			=> 1,
		'dots'						=> 'true',
		'arrows'					=> 'true',
		'speed'						=> 300,
		'autoplay'					=> 'true',
		'autoplayinterval'			=> 3000,
		'show_comment_count'		=> 'true',
		'hide_empty_comment_count'	=> 'false',
		'className'					=> '',
		'align'						=> '',
		'extra_class'				=> '',
	), $atts, 'wtpsw_carousel' ) );

	$prefix						= WTPSW_META_PREFIX;
	$supported_post_types		= wtpsw_get_option( 'post_types', array() );
	$unique						= wtpsw_get_unique();
	$limit						= ( ! empty( $limit ) && is_numeric( $limit ) )	? $limit			: 10;
	$post_type					= ( ! empty( $post_type ) && in_array( $post_type, $supported_post_types ) ) ? $post_type : '';
	$view_by					= ! empty( $view_by )							? $view_by			: 'views';
	$order						= ( strtolower($order ) == 'asc' )				? 'ASC'				: 'DESC';
	$slides_to_show				= ! empty( $slides_to_show )					? $slides_to_show	: 3;
	$slides_to_scroll			= ! empty( $slides_to_scroll )					? $slides_to_scroll	: 1;
	$speed						= ! empty( $speed )								? $speed			: 300;
	$autoplayinterval			= ! empty( $autoplayinterval )					? $autoplayinterval	: 3000;
	$hide_empty_comment_count	= ( $hide_empty_comment_count == 'true' )		? true				: false;
	$align						= ! empty( $align )								? 'align'.$align	: '';
	$extra_class				= $extra_class .' '. $align .' '. $className;
	$extra_class				= wtpsw_sanitize_html_classes( $extra_class );

	// If no valid post type is found
	if(empty($post_type) ) {
		return $content;
	}

	// Slider configuration 
	$slider_conf = compact( 'dots', 'arrows', 'autoplay', 'autoplayinterval', 'speed', 'slides_to_show', 'slides_to_scroll' );

	// Order By
	if( $view_by == 'comment' ){
		$orderby = 'comment_count';
	} elseif ( $view_by == 'views' ) {
		$orderby = 'meta_value_num';
	}

	$wtpsw_view_by = $orderby; // Assign to global variable for query filter

	$post_args = array(
						'post_type'			=> $post_type,
						'posts_per_page'	=> $limit,
						'order'				=> $order,
						'orderby'			=> $orderby
					);

	if( $view_by == 'views' ) {
		$post_args['meta_key'] = $prefix.'views';
	}

	// Wrps class variables
	$main_wrap = "wtpsw-post-carousel-{$unique}";
	$main_wrap .= " {$design}";
	$main_wrap .= " {$extra_class}";

	// Filter to change query where condition
	add_filter( 'posts_where', array( $wtpsw_model, 'wtpsw_query_where' ) );

	// Query to get post
	$wtpsw_posts = $wtpsw_model->wtpsw_get_posts( $post_args );

	// Remove Filter for change query where condition
	remove_filter( 'posts_where', array( $wtpsw_model, 'wtpsw_query_where' ) );

	ob_start();

	if( $wtpsw_posts->have_posts() ) : ?>

		<div id="wtpsw-carousel-<?php echo esc_attr($unique); ?>" class="wtpsw-post-carousel wtpsw-post-slider-init <?php echo esc_attr($main_wrap); ?>" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>">

			<?php while ($wtpsw_posts->have_posts()) : $wtpsw_posts->the_post();

				global $post;
				$wtpsw_post_stats	= array();
				$post_id			= isset($post->ID) ? $post->ID : '';
				$comment_text		= wtpsw_get_comments_number( $post->ID, $hide_empty_comment_count );

				// Design file
				include( WTPSW_DIR . '/templates/carousel/design-1.php' ); 

			endwhile; ?>
		</div>

	<?php
	endif;
	wp_reset_postdata(); // Reset WP Query
	$content .= ob_get_clean();
	return $content;
}

// Trending popular post carousel shortcode
add_shortcode( 'wtpsw_carousel', 'wtpsw_popular_post_carousel' );