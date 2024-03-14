<?php 
$margin = '0 0 20px 0';
$margin_2 = '0 10px'; 
$pattern_style = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<polygon ' . $pattern_style . ' points="50,0 0,100 50,100 100,100 "/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '-25px 0 0 0'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
