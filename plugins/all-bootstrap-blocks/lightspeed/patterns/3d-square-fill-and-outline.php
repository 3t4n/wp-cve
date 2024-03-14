<?php 
$margin = '0 0 -35px 0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.4px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style .' points="9.7,30.2 53.1,4.5 96.5,28.9 96.5,74.6 53.1,99.7 9.7,74.6 "/>
	<polygon ' . $pattern_style_2 .' points="7.8,28.3 51.2,2.6 94.6,27 94.6,72.6 51.2,97.8 7.8,72.6 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '-10px 0 0 -20px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style .' points="9.7,30.2 53.1,4.5 96.5,28.9 96.5,74.6 53.1,99.7 9.7,74.6 "/>
	<polygon ' . $pattern_style_2 .' points="7.8,28.3 51.2,2.6 94.6,27 94.6,72.6 51.2,97.8 7.8,72.6 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>