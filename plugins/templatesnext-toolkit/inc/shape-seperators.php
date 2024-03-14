<?php
/**
 * Shape Devider
 *
 * @package TemplatesNext ToolKit/inc
 */


/* Shape Deviders
================================================== */

if (!function_exists('txo_shape_seperator')) {
	function txo_shape_seperator($shape_seperator = '', $color_1 = '#FFFFFF', $color_2 = '#CCCCCC', $zindex = -1, $height = 100 ) {
		
		if( $shape_seperator == 'triangle' )
		{
			return txo_sd_triangle( $color_1, $color_2, $zindex, $height );
			
		} elseif ( $shape_seperator == 'slanted' )
		{
			return txo_sd_slanted( $color_1, $color_2, $zindex, $height );
			
		} elseif ( $shape_seperator == 'big-triangle-up' )
		{
			return txo_bigtriangle_up( $color_1, $color_2, $zindex, $height );
			
		} elseif ( $shape_seperator == 'big-triangle-dn' )
		{
			return txo_bigtriangle_dn( $color_1, $color_2, $zindex, $height );
			
		} elseif ( $shape_seperator == 'curve-up' )
		{
			return txo_curve_up( $color_1, $color_2, $zindex, $height );
			
		} elseif ( $shape_seperator == 'curve-dn' )
		{
			return txo_curve_dn( $color_1, $color_2, $zindex, $height );
			
		} elseif ( $shape_seperator == 'big-triangle-shadow' )
		{
			return txo_big_triangle_shadow( $color_1, $color_2, $zindex, $height );
		}
	}
}

if (!function_exists('txo_sd_triangle')) {
	function txo_sd_triangle( $color_1 = '#FFFFFF', $color_2 = '#CCCCCC', $zindex = -1, $height = 100 ) {
				
		$txo_sd = '';
		//$height = 100;
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);
			
		$txo_sd .= '<div class="txo-shape-devider ss-style-triangles '.$txo_rand.'" style="position: relative;">';
    	$txo_sd .= '<div class="txo-sd-spacer">';
    	$txo_sd .= '</div>';
		$txo_sd .= '</div>';

		wp_enqueue_style( 'txo-shape-divider' );
		
		$width = $height;
		$top = $bottom = ($width/2);
		$zindex = -1;
		
		$txo_sd_style .= '.ss-style-triangles.'.$txo_rand.'::before { background-color: '.$color_1.'; height: '.$height.'px; width: '.$width.'px; top: -'.$top.'px; }';
		$txo_sd_style .= '.ss-style-triangles.'.$txo_rand.'::after { background-color: '.$color_2.'; height: '.$height.'px; width: '.$width.'px; bottom: -'.$bottom.'px; z-index: '.$zindex.'; }';		
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );	
			
		return $txo_sd;
	}
}

if (!function_exists('txo_sd_slanted')) {
	function txo_sd_slanted( $color_1 = '#83b735', $color_2 = '#649f0c', $zindex = 1, $height = 100 ) {
				
		$txo_sd = '';
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);		
			
		$txo_sd .= '<div class="txo-shape-devider ss-style-doublediagonal '.$txo_rand.'">';
    	$txo_sd .= '<div class="txo-sd-spacer">';
    	$txo_sd .= '</div>';
		$txo_sd .= '</div>';
		
		wp_enqueue_style( 'txo-shape-divider' );
		
		$width = $height;
		//$zindex = 1; // Keep It Fixed
		$zindex = 3; // Keep It Fixed
		$top = $bottom = ($width/2);
		
		
		$txo_sd_style .= '.ss-style-doublediagonal.'.$txo_rand.' { z-index: '.$zindex.'; height: '.$height.'px; margin-top: -'.$height.'px; }';
		$txo_sd_style .= '.ss-style-doublediagonal.'.$txo_rand.'::before { z-index: '.($zindex).'; background-color: '.$color_1.'; }';
		$txo_sd_style .= '.ss-style-doublediagonal.'.$txo_rand.'::after { z-index: '.($zindex).'; background-color: '.$color_2.'; }';		
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );		
			
		return $txo_sd;
	}
}

if (!function_exists('txo_bigtriangle_up')) {
	function txo_bigtriangle_up( $color_1 = '#83b735', $color_2 = '#649f0c', $zindex = 1, $height = 100 ) {
				
		$txo_sd = '';
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);		

		$txo_sd .= '<div class="txo-shape-devider '.$txo_rand.'">';
		$txo_sd .= '<div class="txo-bigtriangle-up">';
		$txo_sd .= '</div>';
		$txo_sd .= '</div>';
		
		
		
		wp_enqueue_style( 'txo-shape-divider' );
		
		/*
		$height = 100;
		$margin_top = -100;
		*/
		$width = $height;
		
		$txo_sd_style .= '.'.$txo_rand.' .txo-bigtriangle-up { z-index: '.$zindex.'; background-color: '.$color_1.'; height: '.$height.'px; margin-top: -'.$height.'px; }';
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );		
			
		return $txo_sd;
	}
}


if (!function_exists('txo_bigtriangle_dn')) {
	function txo_bigtriangle_dn( $color_1 = '#83b735', $color_2 = '#649f0c', $zindex = 1, $height = 100 ) {
				
		$txo_sd = '';
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);

		$txo_sd .= '<div class="txo-shape-devider '.$txo_rand.'">';
		$txo_sd .= '<div class="txo-bigtriangle-dn">';
		$txo_sd .= '</div>';
		$txo_sd .= '</div>';
		
		
		
		wp_enqueue_style( 'txo-shape-divider' );
		
		/*
		$height = 100;
		*/
		$margin_top = 0;
		
		$width = $height;
		$zindex = 10;
		
		$txo_sd_style .= '.'.$txo_rand.' .txo-bigtriangle-dn { z-index: '.$zindex.'; background-color: '.$color_1.'; height: '.$height.'px; margin-top: '.$margin_top.'px; }';
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );		
			
		return $txo_sd;
	}
}

if (!function_exists('txo_curve_up')) {
	function txo_curve_up( $color_1 = '#83b735', $color_2 = '#649f0c', $zindex = 1, $height = 100 ) {
				
		$txo_sd = '';
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);

		$txo_sd .= '<div class="txo-shape-devider '.$txo_rand.'">';		
		$txo_sd .= '<svg id="curveUpColor" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="'.$height.'" viewBox="0 0 100 100" preserveAspectRatio="none">';
    	$txo_sd .= '<path d="M0 100 C 20 0 50 0 100 100 Z" />';
		$txo_sd .= '</svg>';
		$txo_sd .= '</div>';
		
		
		wp_enqueue_style( 'txo-shape-divider' );
		
		$margin_top = $height;
		//$zindex = 1; // Keep it fixed for now
		
		$txo_sd_style .= '.'.$txo_rand.' #curveUpColor { display: block; position: absolute; z-index: 1; margin-top: -'.$margin_top.'px; }';
		$txo_sd_style .= '.'.$txo_rand.' #curveUpColor path { fill: '.$color_1.'; stroke: '.$color_1.';}';
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );		
			
		return $txo_sd;
	}
}


if (!function_exists('txo_curve_dn')) {
	function txo_curve_dn( $color_1 = '#83b735', $color_2 = '#649f0c', $zindex = 1, $height = 100 ) {
				
		$txo_sd = '';
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);

		$txo_sd .= '<div class="txo-shape-devider '.$txo_rand.'">';
		$txo_sd .= '<svg id="curveDownColor" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="'.$height.'" viewBox="0 0 100 100" preserveAspectRatio="none">';
		$txo_sd .= '<path d="M0 0 C 50 100 80 100 100 0 Z" />';
		$txo_sd .= '</svg>';
		$txo_sd .= '</div>';		
		
		wp_enqueue_style( 'txo-shape-divider' );
		
		$margin_top = 0;
		$width = $height;
		//$zindex = 1;  // Keep it fixed for now
		
		$txo_sd_style .= '.'.$txo_rand.' #curveDownColor { display: block; position: absolute; z-index: 1; margin-top: '.$margin_top.'px; }';
		$txo_sd_style .= '.'.$txo_rand.' #curveDownColor path { fill: '.$color_1.'; stroke: '.$color_1.';}';
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );		
			
		return $txo_sd;
	}
}

if (!function_exists('txo_big_triangle_shadow')) {
	function txo_big_triangle_shadow( $color_1 = '#83b735', $color_2 = '#649f0c', $zindex = -1, $height = 100 ) {
				
		$txo_sd = '';
		$txo_sd_style = '';
		$txo_rand = 'random-'.rand(1111, 9999);

		$txo_sd .= '<div class="txo-shape-devider '.$txo_rand.'">';
		$txo_sd .= '<svg id="bigTriangleShadow" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="'.$height.'" viewBox="0 0 100 100" preserveAspectRatio="none">';
		$txo_sd .= '<path id="trianglePath1" d="M0 0 L50 100 L100 0 Z" />';
		$txo_sd .= '<path id="trianglePath2" d="M50 100 L100 40 L100 0 Z" />';
		$txo_sd .= '</svg>';
		$txo_sd .= '</div>';		
		
		wp_enqueue_style( 'txo-shape-divider' );
		
		$margin_top = 0;
		$width = $height;
		$zindex = 10;
		
		$txo_sd_style .= '.'.$txo_rand.' #bigTriangleShadow { position: absolute; z-index: 1; margin-top: '.$margin_top.'px; padding: 0px; }';
		$txo_sd_style .= '.'.$txo_rand.' #trianglePath1 { fill: '.$color_1.'; stroke: '.$color_1.'; }';
		$txo_sd_style .= '.'.$txo_rand.' #trianglePath2 { fill: '.$color_2.'; stroke: '.$color_2.'; }';
		
		wp_add_inline_style( 'txo-shape-divider', $txo_sd_style );		
			
		return $txo_sd;
	}
}
