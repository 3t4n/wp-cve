<?php 
$margin = '0 0 60px 225px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.5px; stroke-linejoin: round;"';
$pattern_svg = '
<polygon ' . $pattern_style .' points="66.7,100 0,100 33.3,0 100,0 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_svg = '
<polygon ' . $pattern_style .' points="66.7,100 0,100 33.3,0 100,0 "/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
