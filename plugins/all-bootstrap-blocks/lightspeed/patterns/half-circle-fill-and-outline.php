<?php 
$margin = '0 0 40px 0';
$margin_2 = '0 20px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.6px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<path ' . $pattern_style . ' d="M2,2v49c0,27.1,22,49,49,49h49V2H2z"/>
<path ' . $pattern_style_2 . ' d="M0,0v49c0,27.1,22,49,49,49h49V0H0z"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.3px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<path ' . $pattern_style . ' d="M2,2v49c0,27.1,22,49,49,49h49V2H2z"/>
<path ' . $pattern_style_2 . ' d="M0,0v49c0,27.1,22,49,49,49h49V0H0z"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
