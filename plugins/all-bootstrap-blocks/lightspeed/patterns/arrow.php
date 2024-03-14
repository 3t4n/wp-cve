<?php 
$margin = '0 0 30px 0';
$pattern_style = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style . ' points="50.9,1.6 12.1,1.6 0.7,13 37.6,50 0.7,87 12.1,98.4 50.9,98.4 99.3,50 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0 0 0 -25px';
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
