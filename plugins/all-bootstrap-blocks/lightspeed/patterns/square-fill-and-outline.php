<?php 
$margin = '0 0 20px 0';
$margin_2 = '0 10px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.4px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<rect ' . $pattern_style .' x="2" y="2" width="98" height="98"/>
	<rect ' . $pattern_style_2 .' width="98" height="98"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<rect ' . $pattern_style .' x="2" y="2" width="98" height="98"/>
	<rect ' . $pattern_style_2 .' width="98" height="98"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>