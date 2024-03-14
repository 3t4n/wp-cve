<?php 
$margin = '0 0 60px 225px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.5px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style .' points="67.4,100 2,100 34.6,2 100,2 "/>
<polygon ' . $pattern_style_2 .' points="65.4,98 0,98 32.6,0 98,0 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style .' points="67.4,100 2,100 34.6,2 100,2 "/>
<polygon ' . $pattern_style_2 .' points="65.4,98 0,98 32.6,0 98,0 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
