<?php

/*
Plugin Name: Calculate Values with Shortcodes
Plugin URI: https://www.wp-tweaks.com
Description: Calculates math expressions even with shortcodes
Version: 2.3
Author: Bhagwad Park
Author URI: https://www.wp-tweaks.com
*/

function calculate_func( $atts, $content = null) {

    $atts = shortcode_atts( array(
		'dec' => '0',
		'int' => ''
	), $atts, 'calculate' );
	
    if ( ! class_exists( 'EvalMath' ) ) {
    require_once( 'evalmath.class.php' );
    }
	
    $content = str_replace('&#8211;', "-", $content);
	$expression = do_shortcode($content);

    $expression = strip_tags($expression);
	
    $m = new EvalMath;
    $result = $m->evaluate($expression);
    $result = round($result,$atts['dec']);
    
     if ($atts['int'] != '') {
        setlocale(LC_MONETARY, $atts['int']);
	$formatStyle=NumberFormatter::DECIMAL;
	$formatter= new NumberFormatter($atts['int'], $formatStyle);
	$result = $formatter->format($result);
    }
    
    return $result;
    
}
add_shortcode( 'calculate', 'calculate_func' );