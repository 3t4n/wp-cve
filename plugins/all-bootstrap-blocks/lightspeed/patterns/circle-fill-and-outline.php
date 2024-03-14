<?php 
$margin = '0 0 0 0';
$margin_2 = '0 20px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.3px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<circle ' . $pattern_style . ' cx="51.6" cy="51.6" r="48.4"/>
	<circle ' . $pattern_style_2 . ' cx="49.7" cy="49.7" r="48.4"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<circle ' . $pattern_style . ' cx="51.6" cy="51.6" r="48.4"/>
	<circle ' . $pattern_style_2 . ' cx="49.7" cy="49.7" r="48.4"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
