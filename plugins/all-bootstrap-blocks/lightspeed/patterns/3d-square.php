<?php 
$margin = '0 0 -55px 0';
$pattern_style = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style .' points="5.7,27.6 50.1,1.3 94.5,26.3 94.5,73 50.1,98.7 5.7,73 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>