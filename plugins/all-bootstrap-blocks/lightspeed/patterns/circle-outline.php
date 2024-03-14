<?php 
$margin = '0 0 0 0';
$margin_2 = '0 20px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.3px; stroke-linejoin: round;"';
$pattern_svg = '
	<circle ' . $pattern_style . ' cx="50" cy="50" r="50"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_svg = '
	<circle ' . $pattern_style . ' cx="50" cy="50" r="50"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
