<?php

	if( !defined( 'ABSPATH' ) ){
	    exit;
	}

	# shortocde
	function pick_logofree_shortcode_reg( $atts, $content = null ){
		global $post;
		ob_start();
		extract( shortcode_atts( array('id' => ''), $atts ) );
		$postid = $atts['id'];

	    $image_name 				= get_post_meta($postid, 'image_name', true);
	    if(empty($image_name)){
	    	$image_name 			= array();
	    }
	    $bend_single_logo_name 		= get_post_meta($postid, 'bend_single_logo_name', true);
	    if(empty($bend_single_logo_name)){
	    	$bend_single_logo_name 	= array();
	    } 
	    $bend_single_logo_desc 		= get_post_meta($postid, 'bend_single_logo_desc', true);
	    if(empty($bend_single_logo_desc)){
	    	$bend_single_logo_desc 	= array();
	    }
	    $bend_single_logo_url 		= get_post_meta($postid, 'bend_single_logo_url', true);
	    if(empty($bend_single_logo_url)){
	    	$bend_single_logo_url 	= array();
	    }
	    $pkslogo_styles    		 	= get_post_meta($postid, 'pkslogo_styles', true);
    	$pkslogo_columns    		= get_post_meta($postid, 'pkslogo_columns', true);  
    	$pklslogo_margin_bottom    	= get_post_meta($postid, 'pklslogo_margin_bottom', true);
    	$pklslogo_margin_lfr    	= get_post_meta($postid, 'pklslogo_margin_lfr', true);
    	$pklslogo_bordersize    	= get_post_meta($postid, 'pklslogo_bordersize', true);
    	$pkslogo_borderstyles    	= get_post_meta($postid, 'pkslogo_borderstyles', true);
    	$pklslogo_borderclr    		= get_post_meta($postid, 'pklslogo_borderclr', true);
    	$pklslogo_border_hvrclr    	= get_post_meta($postid, 'pklslogo_border_hvrclr', true);
    	$pklslogo_title_color    	= get_post_meta($postid, 'pklslogo_title_color', true);
    	$pkls_logotitle_font_size   = get_post_meta($postid, 'pkls_logotitle_font_size', true);
    	$pkls_logotitle_transfrom   = get_post_meta($postid, 'pkls_logotitle_transfrom', true);
    	$pkls_logotitle_fontstyle   = get_post_meta($postid, 'pkls_logotitle_fontstyle', true);
    	$pkslogo_title_hide    		= get_post_meta($postid, 'pkslogo_title_hide', true);
    	$pkslogo_content_hide    	= get_post_meta($postid, 'pkslogo_content_hide', true);
    	$pklslogo_content_color    	= get_post_meta($postid, 'pklslogo_content_color', true);
    	$pkls_logocontent_size    	= get_post_meta($postid, 'pkls_logocontent_size', true);
    	$pkls_logocontent_transfrom = get_post_meta($postid, 'pkls_logocontent_transfrom', true);
    	$pkls_logocontent_fontstyle = get_post_meta($postid, 'pkls_logocontent_fontstyle', true);
    	$pklslogo_bag_color    		= get_post_meta($postid, 'pklslogo_bag_color', true);
    	$pkls_logo_padding_size    	= get_post_meta($postid, 'pkls_logo_padding_size', true);
    	$pkslogo_custom    			= get_post_meta($postid, 'pkslogo_custom', true);
    	$pkslogo_heights    		= get_post_meta($postid, 'pkslogo_heights', true);
    	$pkls_logotooltip    		= get_post_meta($postid, 'pkls_logotooltip', true);
    	$pkls_logotooltipclr    	= get_post_meta($postid, 'pkls_logotooltipclr', true);
    	$pkls_logotooltiptclr    	= get_post_meta($postid, 'pkls_logotooltiptclr', true);
    	$pkslogo_autoplayoptions    = get_post_meta($postid, 'pkslogo_autoplayoptions', true);
    	$pkslogo_autoplayspeed    	= get_post_meta($postid, 'pkslogo_autoplayspeed', true);
    	$pkslogo_dotsoptions    	= get_post_meta($postid, 'pkslogo_dotsoptions', true);
    	$pklslogo_dotcolor    		= get_post_meta($postid, 'pklslogo_dotcolor', true);
    	$pklslogo_dotactcolor    	= get_post_meta($postid, 'pklslogo_dotactcolor', true);
    	$pkslogo_arrowoptions   	= get_post_meta($postid, 'pkslogo_arrowoptions', true);
    	$pklslogo_arrowcolor   		= get_post_meta($postid, 'pklslogo_arrowcolor', true);
    	$pkslogo_pausehover   		= get_post_meta($postid, 'pkslogo_pausehover', true);
    	$pkslogo_displayitems   	= get_post_meta($postid, 'pkslogo_displayitems', true);
		$pkslogo_mediumitems		= get_post_meta($postid, 'pkslogo_mediumitems', true);
    	$pkslogo_smallitems			= get_post_meta($postid, 'pkslogo_smallitems', true);
    	$pkslogo_swipeoptions   	= get_post_meta($postid, 'pkslogo_swipeoptions', true);
    	$pkslogo_dragsoptions   	= get_post_meta($postid, 'pkslogo_dragsoptions', true);
   		$pkslogo_types    			= get_post_meta($postid, 'pkslogo_types', true);
   		$pkslogo_imggray    		= get_post_meta($postid, 'pkslogo_imggray', true);

		switch ( $pkslogo_styles ) {
		   	case '1':
		   		include pick_logo_free_plugin_dir.'templates/theme-one.php';
		    break;
		}
	   $myvariable_pages = ob_get_clean();
	   wp_reset_postdata();
	   return $myvariable_pages;
	}
	add_shortcode('piclogofree', 'pick_logofree_shortcode_reg');