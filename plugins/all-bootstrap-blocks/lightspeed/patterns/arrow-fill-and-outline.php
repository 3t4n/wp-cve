<?php 
$margin = '0 0 30px 0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.4px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style . ' points="51.9,3.5 13.8,3.5 2.7,14.7 38.8,51 2.7,87.2 13.8,98.4 51.9,98.4 99.3,51 "/>
<polygon ' . $pattern_style_2 . ' points="49.9,1.6 11.9,1.6 0.7,12.7 36.9,49 0.7,85.3 11.9,96.4 49.9,96.4 97.3,49 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );


$margin = '0 0 0 -25px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style . ' points="51.9,3.5 13.8,3.5 2.7,14.7 38.8,51 2.7,87.2 13.8,98.4 51.9,98.4 99.3,51 "/>
<polygon ' . $pattern_style_2 . ' points="49.9,1.6 11.9,1.6 0.7,12.7 36.9,49 0.7,85.3 11.9,96.4 49.9,96.4 97.3,49 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>