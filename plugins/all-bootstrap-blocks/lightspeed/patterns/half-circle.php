<?php
$margin = '0 0 40px 0';
$margin_2 = '0 20px'; 
$pattern_style = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
<path ' . $pattern_style . ' d="M0,0v50c0,27.6,22.4,50,50,50h50V0H0z"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
