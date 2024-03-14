<?php 

if( !defined( 'ABSPATH' ) ){
    exit;
}

# rsbbox shortocde
function rsbbox_shortcode_register( $atts, $content = null ){
	wp_enqueue_style('rsbbox_fontawesome');
	wp_enqueue_style('rsbbox_main-css');
	wp_enqueue_script('rsbbox_service_js');

	$atts = shortcode_atts(
		array(
			'id' => '',
		), $atts
	);
	global $post;
	$post_id = $atts['id'];

	$rsbbox_catnames              = get_post_meta( $post_id, 'rsbbox_catnames', true);
	$rsbbox_theme_id              = get_post_meta( $post_id, 'rsbbox_theme_id', true);
	$rsbbox_columns               = get_post_meta( $post_id, 'rsbbox_columns', true);
	$rsbbox_itemsicons            = get_post_meta( $post_id, 'rsbbox_itemsicons', true);
	$rsbbox_colmargin_lr          = get_post_meta( $post_id, 'rsbbox_colmargin_lr', true);
	$rsbbox_marginbottom          = get_post_meta( $post_id, 'rsbbox_marginbottom', true);
	$rsbbox_alignment             = get_post_meta( $post_id, 'rsbbox_alignment', true);
	$rsbbox_itembg_color          = get_post_meta( $post_id, 'rsbbox_itembg_color', true);
	$rsbbox_hidetitle             = get_post_meta( $post_id, 'rsbbox_hidetitle', true);
	$rsbbox_titlesize             = get_post_meta( $post_id, 'rsbbox_titlesize', true);
	$rsbbox_itemtitle_color       = get_post_meta( $post_id, 'rsbbox_itemtitle_color', true);
	$rsbbox_itemtitleh_color      = get_post_meta( $post_id, 'rsbbox_itemtitleh_color', true);
	$rsbbox_padding_size          = get_post_meta( $post_id, 'rsbbox_padding_size', true);
	$rsbbox_hideicons             = get_post_meta( $post_id, 'rsbbox_hideicons', true);
	$rsbbox_itemicons_color       = get_post_meta( $post_id, 'rsbbox_itemicons_color', true);
	$rsbbox_itemiconsbg_color     = get_post_meta( $post_id, 'rsbbox_itemiconsbg_color', true);
	$rsbbox_iconsize              = get_post_meta( $post_id, 'rsbbox_iconsize', true);
	$rsbbox_iconheight            = get_post_meta( $post_id, 'rsbbox_iconheight', true);
	$rsbbox_hidereadmore          = get_post_meta( $post_id, 'rsbbox_hidereadmore', true);
	$rsbbox_hidelinks             = get_post_meta( $post_id, 'rsbbox_hidelinks', true);
	$rsbbox_linkopen              = get_post_meta( $post_id, 'rsbbox_linkopen', true);
	$rsbbox_contentsize           = get_post_meta( $post_id, 'rsbbox_contentsize', true);
	$rsbbox_conten_color          = get_post_meta( $post_id, 'rsbbox_conten_color', true);
	$rsbbox_moreoption_color      = get_post_meta( $post_id, 'rsbbox_moreoption_color', true);
	$rsbbox_moreoptionhover_color = get_post_meta( $post_id, 'rsbbox_moreoptionhover_color', true);
	$rsbbox_moresize              = get_post_meta( $post_id, 'rsbbox_moresize', true);
	# Slider All Options
	$rssbox_slide_autoplay        = get_post_meta($post_id, 'rssbox_slide_autoplay', true);
	$rssbox_slide_speeds          = get_post_meta($post_id, 'rssbox_slide_speeds', true);
	$rssbox_slide_stophovers      = get_post_meta($post_id, 'rssbox_slide_stophovers', true);
	$rssbox_slide_timeout         = get_post_meta($post_id, 'rssbox_slide_timeout', true);
	$rssbox_slide_items_alls      = get_post_meta($post_id, 'rssbox_slide_items_alls', true);
	$rssbox_slide_items_dsks      = get_post_meta($post_id, 'rssbox_slide_items_dsks', true);
	$rssbox_slide_items_dsksmall  = get_post_meta($post_id, 'rssbox_slide_items_dsksmall', true);
	$rssbox_slide_items_mob       = get_post_meta($post_id, 'rssbox_slide_items_mob', true);
	$rssbox_slide_loops           = get_post_meta($post_id, 'rssbox_slide_loops', true);
	$rssbox_slide_margins         = get_post_meta($post_id, 'rssbox_slide_margins', true);
	$rssbox_slide_navho_color     = get_post_meta($post_id, 'rssbox_slide_navho_color', true);
	$rssbox_slide_navi            = get_post_meta($post_id, 'rssbox_slide_navi', true);
	$rssbox_slide_navi_position   = get_post_meta($post_id, 'rssbox_slide_navi_position', true);
	$rssbox_slide_navtext_color   = get_post_meta($post_id, 'rssbox_slide_navtext_color', true);	
	$rssbox_slide_navbg_color     = get_post_meta($post_id, 'rssbox_slide_navbg_color', true);
	$rssbox_slide_pagi            = get_post_meta($post_id, 'rssbox_slide_pagi', true);
	$rssbox_slide_pagiposition    = get_post_meta($post_id, 'rssbox_slide_pagiposition', true);
	$rssbox_slide_pagi_color      = get_post_meta($post_id, 'rssbox_slide_pagi_color', true);
	$rssbox_slide_pagi_style      = get_post_meta($post_id, 'rssbox_slide_pagi_style', true);

	if( is_array( $rsbbox_catnames ) ){
		$rsbbox_cats =  array();
		$num = count( $rsbbox_catnames );
		for ( $j=0; $j<$num; $j++ ) {
			array_push( $rsbbox_cats, $rsbbox_catnames[$j] );
		}

		$args = array(
			'post_type' => 'tpwp_serviceboxs',
			'post_status' => 'publish',
			'posts_per_page' => 3,
		    'tax_query' => [
		        'relation' => 'OR',
		        [
		            'taxonomy' => 'rsbboxcat',
		            'field' => 'id',
		            'terms' => $rsbbox_cats,
		        ],
		        // [
		        //     'taxonomy' => 'rsbboxcat',
		        //     'field' => 'id',
		        //     'operator' => 'NOT EXISTS',
		        // ],
		    ],
		);
	} else {
		$args = array(
			'post_type' 		=> 'tpwp_serviceboxs',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> 3,
		);
	}

	$service_query = new WP_Query( $args );

	ob_start();

	switch ($rsbbox_theme_id) {
	    case '1':
	    	include __DIR__ . '/themes/theme-1.php';
	    break;
	    case '2':
	    	include __DIR__ . '/themes/theme-2.php';
	    break;
	    case '3':
	    	include __DIR__ . '/themes/theme-3.php';
	    break;
	    case '4':
	        include __DIR__ . '/themes/theme-4.php';
	    break;
	    case '5':
	    	include __DIR__ . '/themes/theme-5.php';
	    break;
	    case '6':
	    	include __DIR__ . '/themes/theme-6.php';
	    break;
	    case '7':
			include __DIR__ . '/themes/theme-7.php';
	    break;
	}

	$myvariable_pages = ob_get_clean();
	wp_reset_postdata();
	return $myvariable_pages;
}
add_shortcode( 'tpservicebox', 'rsbbox_shortcode_register' );