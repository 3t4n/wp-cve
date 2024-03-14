<?php
/*
 *  Responsive Portfolio Image Gallery 1.2
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
function rcpig_shortcode_function( $atts ){
	extract(shortcode_atts(array(
		'orderby'	=> 'none', // portfolio orderby
		'order'		=> '', // portfolio order
		'post_id'	=> get_the_ID()
	), $atts, 'rcpig-gallery'));

	ob_start();
	$portfolio_id = rand( 10,1000 );
	global $post;
	$rcpig_enable = rcpig_get_option( 'rcpig_enable_portfolio_', 'rcpig_general', 'show' );
	$rcpig_number_of_post = rcpig_get_option( 'rcpig_number_of_post_', 'rcpig_general', -1 );
	$rcpig_post_order = rcpig_get_option( 'rcpig_post_order_', 'rcpig_general', 'DESC' );
	$rcpig_post_order_by = rcpig_get_option( 'rcpig_post_order_by_', 'rcpig_general', 'date' );

	// Include selected categiry form portfolio.
	$rcpig_cat_includes = rcpig_get_option( 'rcpig_cat_include_', 'rcpig_general', array('') );

	$rcpig_taxonomy = 'rcpig-category';
	if( isset($rcpig_cat_includes) && is_array($rcpig_cat_includes) ){
		foreach ($rcpig_cat_includes as $key => $value) {
			$value = str_replace(' ', '-', $value);
			$rcpig_cats_in[] = $value;
		}
	}

	$args = array(
		'post_type' => 'rcpig',
		'post_status' => 'publish',
		'posts_per_page' => $rcpig_number_of_post,
		'orderby' => $rcpig_post_order_by,
		'order' => $rcpig_post_order,
		'tax_query' => array(
			array(
				'taxonomy' => $rcpig_taxonomy,
				'terms' => $rcpig_cats_in,
				'field' => 'id'
			)
		)
	);

	$loop = new WP_Query( $args );
	if ( $loop->have_posts() && $rcpig_enable == 'show' ) {
		echo '<div id="rcpig_'.$portfolio_id.'">';
			echo '<div id="rcpig_grid_demo"></div>';
			$rcpig_image_width = rcpig_get_option( 'rcpig_image_width_', 'rcpig_general', 250 );
			$rcpig_image_height = rcpig_get_option( 'rcpig_image_height_', 'rcpig_general', 250 );
			$hover_direction = rcpig_get_option( 'rcpig_hover_direction_', 'rcpig_advanced', 'true' );
			$hover_inverse = rcpig_get_option( 'rcpig_hover_inverse_', 'rcpig_advanced', 'false' );
			$filter_effect = rcpig_get_option( 'rcpig_filter_effect_', 'rcpig_advanced', 'popup' );
			$hover_effect = rcpig_get_option( 'rcpig_hover_effect_', 'rcpig_advanced', 'none' );
			$rcpig_hide_excerpt = rcpig_get_option( 'rcpig_hide_excerpt_', 'rcpig_advanced', 'show' );
			$expand_height = rcpig_get_option( 'rcpig_expanding_height_', 'rcpig_advanced', 500 );
			$rcpig_show_wrapper = rcpig_get_option( 'rcpig_show_wrapper_', 'rcpig_advanced', 'show' ); ?>

			<style type="text/css">div#rcpig_<?php echo $portfolio_id; ?> .og-fullimg img {height:<?php echo $expand_height; ?>px}<?php if($hover_effect == 'zoompan') { ?>div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid li {width: <?php echo $rcpig_image_width; ?>px;height: <?php echo $rcpig_image_height; ?>px}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li > a img {-webkit-transition: all 0.2s linear;-moz-transition: all 0.2s linear;-o-transition: all 0.2s linear;-ms-transition: all 0.2s linear;transition: all 0.2s linear}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li:hover > a img {-webkit-transform: scale(1.1,1.1);-moz-transform: scale(1.1,1.1);-o-transform: scale(1.1,1.1);-ms-transform: scale(1.1,1.1);transform: scale(1.1,1.1)}<?php } elseif($hover_effect == 'zoomhide') { ?>div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid li {width: <?php echo $rcpig_image_width; ?>px;height: <?php echo $rcpig_image_height; ?>px}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li > a img {-webkit-transform: scaleY(1);-moz-transform: scaleY(1);-o-transform: scaleY(1);-ms-transform: scaleY(1);transform: scaleY(1);-webkit-transition: all 0.7s ease-in-out;-moz-transition: all 0.7s ease-in-out;-o-transition: all 0.7s ease-in-out;-ms-transition: all 0.7s ease-in-out;transition: all 0.7s ease-in-out}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li:hover > a img {-webkit-transform: scale(10);-moz-transform: scale(10);-o-transform: scale(10);-ms-transform: scale(10);transform: scale(10);-ms-filter: "progid: DXImageTransform.Microsoft.Alpha(Opacity=0)";filter: alpha(opacity=0);opacity: 0}<?php } elseif($hover_effect == 'shrink') { ?>div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid li {width: <?php echo $rcpig_image_width; ?>px;height: <?php echo $rcpig_image_height; ?>px}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li > a img {-webkit-transition: opacity 0.35s, -webkit-transform 0.35s;transition: opacity 0.35s, transform 0.35s;-webkit-transform: scale(1.15);transform: scale(1.15)}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li:hover > a img {opacity: 0.5;-webkit-transform: scale(1);transform: scale(1)}<?php } elseif($hover_effect == 'slideout') { ?>div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid li {width: <?php echo $rcpig_image_width; ?>px;height: <?php echo $rcpig_image_height; ?>px}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li > a img {-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;-o-transition: all 0.3s ease-in-out;-ms-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out}div#rcpig_<?php echo $portfolio_id; ?> ul.og-grid > li:hover > a img {-webkit-transform: translateX(300px);-moz-transform: translateX(300px);-o-transform: translateX(300px);-ms-transform: translateX(300px);transform: translateX(300px)}<?php } ?><?php if($rcpig_show_wrapper == 'hide') { ?>div#rcpig_<?php echo $portfolio_id; ?> .rcpigslide-wrapper, div#rcpig_<?php echo $portfolio_id; ?> .og-details .infosep {display:none}<?php } ?><?php if($rcpig_hide_excerpt == 'hide') { ?>div#rcpig_<?php echo $portfolio_id; ?> .og-expander p {display:none}<?php } ?></style>

<script type="text/javascript">
	jQuery(function(){
		jQuery("#rcpig_grid_demo").rcpig_grid({
			'showAllText' : 'All',
			'filterEffect': '<?php echo $filter_effect; ?>',
			'hoverDirection': <?php echo $hover_direction; ?>,
			'hoverDelay': 0,
			'hoverInverse': <?php echo $hover_inverse; ?>,
			'expandingSpeed': 500,
			'expandingHeight': <?php echo $expand_height; ?>,
			'items' :
			[<?php
			$temp = '';
			while ( $loop->have_posts() ) : $loop->the_post();
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full' );
				//resize & crop the image
				$image_thumb = aq_resize( $img_url, $rcpig_image_width, $rcpig_image_height, true, true, true );
				$portfolio_title = get_the_title();
				$portfolio_title = (strlen($portfolio_title) > 28) ? substr($portfolio_title,0,26).'...' : $portfolio_title;
				$portfolio_excerpt = rcpig_excerpt('rcpig_excerptlength', 'rcpig_excerpt_more');
				$taxonomy = 'rcpig-category';
				$terms = get_the_terms( $post->ID, $taxonomy );
				$rcpig_portfolio = get_post_meta( $post->ID, '_multi_img_array', true );
				$portfolio_images = explode(",", $rcpig_portfolio);
				$rcpig_button_text = get_post_meta( $post->ID, '_first_button', true ) != '' ? get_post_meta( $post->ID, '_first_button', true ) : 'Read More';
				$rcpig_button_link = get_post_meta( $post->ID, '_first_button_link', true ) != '' ? get_post_meta( $post->ID, '_first_button_link', true ) : get_post_permalink();
				$rcpig_button_tab = get_post_meta( $post->ID, '_first_button_tab', true ) != '' ? get_post_meta( $post->ID, '_first_button_tab', true ) : 'false';
				if($terms) :
?>
				{
					'title'         : "<?php echo $portfolio_title; ?>",
					'description'   : "<?php echo $portfolio_excerpt; ?>",
					'thumbnail'     : ['<?php echo $image_thumb; ?>', <?php foreach ( $portfolio_images as $gallery ) { $gallery_url = wp_get_attachment_url( $gallery, 'full' ); $gallery_thumb = aq_resize( $gallery_url, $rcpig_image_width, $rcpig_image_height, true, true, true ); ?>'<?php echo $gallery_thumb; ?>', <?php } ?>],
					'large'         : ['<?php echo $img_url; ?>', <?php foreach ( $portfolio_images as $gallery ) { $gallery_url = wp_get_attachment_url( $gallery, 'full' ); ?>'<?php echo $gallery_url; ?>', <?php } ?>],
					'button_list'   :
					[
						{ 'title':"<?php echo $rcpig_button_text; ?>", 'url':'<?php echo $rcpig_button_link; ?>', 'new_window' : <?php echo $rcpig_button_tab; ?> }
					],
					'tags' : 		  ['<?php foreach($terms as $term) { if(in_array($term->term_id, $rcpig_cat_includes)) { $catname = $temp . str_replace(' ', '-', $term->name); echo $catname; $temp = ', '; } } ?>']
				},<?php
				endif;
			endwhile; ?>
			]
		});
	});
</script><?php
		echo '</div>';
	} else {
		_e( 'No portfolio found. Make sure that the display portfolio is enabled in portfolio settings.', 'rcpig' );
	}
	wp_reset_postdata();
	wp_reset_query();
	return ob_get_clean();
}
add_shortcode( 'rcpig-gallery','rcpig_shortcode_function' );
?>