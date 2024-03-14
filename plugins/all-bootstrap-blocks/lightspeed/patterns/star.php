<?php 
$margin = '0 0 -100px 0';
$margin_2 = '0 10px'; 
$pattern_style = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style . ' points="80.2,97.5 49.8,74.9 19.3,97.5 30.8,61.3 0.3,38.7 37.9,38.7 49.8,2.5 61.7,38.7 99.7,38.7 69.2,61.3 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '-10px 0 0 0'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
