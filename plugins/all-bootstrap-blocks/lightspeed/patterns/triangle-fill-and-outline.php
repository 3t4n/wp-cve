<?php 
$margin = '0 0 20px 0';
$margin_2 = '0 10px'; 
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.4px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style . ' points="45,0 0,100 45,100 100,100 "/>
	<polygon ' . $pattern_style_2 . ' points="55,0 0,100 55,100 100,100 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '-25px 0 0 0'; 
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style . ' points="45,0 0,100 45,100 100,100 "/>
	<polygon ' . $pattern_style_2 . ' points="55,0 0,100 55,100 100,100 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>