<?php 
$margin = '0 0 60px 225px';
$pattern_style = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<polygon ' . $pattern_style .' points="66.7,100 0,100 33.3,0 100,0 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>