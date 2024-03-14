<?php 
$margin = '0 0 20px 0';
$margin_2 = '0 10px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.6px; stroke-linejoin: round;"';
$pattern_svg = '
	<rect ' . $pattern_style .' width="100" height="100"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_svg = '
	<rect ' . $pattern_style .' width="100" height="100"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>