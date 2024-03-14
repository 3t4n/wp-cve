<?php
/**
 * 'th-slider' Shortcode
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpostahs_timeline_slider( $atts, $content = null ){

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ( $_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json' ) ) {
		return "[th-slider]";
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wpostahs-builder-shrt-prev">
					<div class="wpostahs-builder-shrt-title"><span>'.esc_html__('Timeline Slider View - Shortcode', 'timeline-and-history-slider').'</span></div>
					th-slider
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wpostahs-builder-shrt-prev">
					<div class="wpostahs-builder-shrt-title"><span>'.esc_html__('Timeline Slider View - Shortcode', 'timeline-and-history-slider').'</span></div>
					th-slider
				</div>';
	}

	extract(shortcode_atts(array(
		'limit'				=> 20,
		'category'			=> '',
		'dots'				=> 'true',
		'arrows'			=> 'true',
		'autoplay'			=> 'true',
		'autoplay_interval'	=> 3000,
		'speed'				=> 300,
		'fade'				=> 'false',
		'design'			=> 'design-1',
		'loop'				=> 'true',
		'rtl'				=> false,
		'centermode'		=> 'false',
		'slidestoshow'		=> 3,
		'lazyload'			=> '',
		'extra_class'		=> '',
		'className'			=> '',
		'align'				=> '',
	), $atts));

	$posts_per_page		= ! empty( $limit )				? $limit					: 20;
	$cat				= ( ! empty($category) )		? explode(',',$category)	: '';
	$dots				= ( $dots == 'false' )			? 'false'					: 'true';
	$arrows				= ( $arrows == 'false' )		? 'false'					: 'true';
	$autoplay			= ( $autoplay == 'false' )		? 'false'					: 'true';
	$autoplayInterval	= ! empty( $autoplay_interval ) ? $autoplay_interval		: 3000;
	$speed				= ! empty( $speed )				? $speed					: 300;
	$fade				= ( $fade == 'true' )			? 'true'					: 'false';
	$loop				= ( $loop == 'true' )			? 'true'					: 'false';
	$centermode			= ( $centermode == 'true' )		? 'true'					: 'false';
	$slidestoshow		= ! empty($slidestoshow)		? $slidestoshow				: 3;
	$lazyload			= ( $lazyload == 'ondemand' || $lazyload == 'progressive' )	? $lazyload	: ''; // ondemand or progressive
	$align				= ! empty( $align )				? 'align'.$align			: '';
	$extra_class		= $extra_class .' '. $align .' '. $className;
	$extra_class		= wpostahs_sanitize_html_classes( $extra_class );
	$design				= ( ! empty($design) )			? $design					: 'design-1';
	$design_file		= WPOSTAHS_DIR . '/templates/' . $design . '.php';

	// For RTL
	if( empty($rtl) && is_rtl() ) {
		$rtl = 'true';
	} elseif ( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}

	// Enqueus required script
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wpostahs-public-js' );

	ob_start();

	$unique				= wpostahs_get_unique();
	$post_type			= 'timeline_slider_post';
	$orderby			= 'post_date';
	$order				= 'ASC';
	$slider_as_nav_for	= "data-slider-nav-for='wpostahs-slider-for-{$unique}'";

	$args = array (
		'post_type'			=> $post_type, 
		'orderby'			=> $orderby, 
		'order'				=> $order,
		'posts_per_page'	=> $posts_per_page,
	);

	if($cat != ""){
		$args['tax_query'] = array(
					array(
						'taxonomy'	=> 'wpostahs-slider-category',
						'field'		=> 'term_id', 
						'terms'		=> $cat,
					) );
	}

	$query		= new WP_Query($args);
	$post_count	= $query->post_count;

	// Slider configuration and taken care of centermode
	$centermode		= ( $centermode == 'true' ) ? 'true' : 'false';
	$slidestoshow	= ( ! empty( $slidestoshow ) && $slidestoshow <= $post_count) 	? $slidestoshow : $post_count;

	// Slider configuration
	$slider_conf = compact('dots', 'arrows','loop', 'autoplay', 'autoplayInterval', 'speed', 'fade', 'rtl', 'centermode', 'slidestoshow', 'lazyload' );

	global $post;

	if ( $query->have_posts() ) : ?>
		<div class="wpostahs-slider-wrp <?php echo esc_attr($extra_class); ?>">
			<div class="wpostahs-slider wpostahs-slider-inner-wrp <?php echo 'wpostahs-slider-'.esc_attr($design); ?>">
				<?php 
					// Include shortcode html file
					if( $design_file ) {
						include( $design_file );
					}
				?>
			</div>
		<div class="wpostahs-slider-conf" data-conf="<?php echo htmlspecialchars(json_encode( $slider_conf )); ?>"></div>
		</div>
		<?php
	endif; 
	wp_reset_postdata(); // Reset WP Query
	return ob_get_clean();
}

add_shortcode( 'th-slider', 'wpostahs_timeline_slider' );