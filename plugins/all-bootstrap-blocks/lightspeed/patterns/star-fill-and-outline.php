<?php 
$margin = '0 0 -100px 0';
$margin_2 = '0 10px'; 
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.4px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style . ' points="81.2,97.5 52.3,76.1 23.3,97.5 34.2,63.1 5.3,41.7 41,41.7 52.3,7.3 63.6,41.7 99.7,41.7 70.7,63.1 "/>
	<polygon ' . $pattern_style_2 . ' points="79.5,96.7 50.5,75.3 21.6,96.7 32.5,62.3 3.6,40.9 39.2,40.9 50.5,6.5 61.8,40.9 97.9,40.9 69,62.3 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '-10px 0 0 0'; 
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style . ' points="81.2,97.5 52.3,76.1 23.3,97.5 34.2,63.1 5.3,41.7 41,41.7 52.3,7.3 63.6,41.7 99.7,41.7 70.7,63.1 "/>
	<polygon ' . $pattern_style_2 . ' points="79.5,96.7 50.5,75.3 21.6,96.7 32.5,62.3 3.6,40.9 39.2,40.9 50.5,6.5 61.8,40.9 97.9,40.9 69,62.3 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>