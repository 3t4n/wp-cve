<?php 
$margin = '0 0 -30px 40px';
$margin_2 = '0 20px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.5px; stroke-linejoin: round;"';
$pattern_svg = '
	<path ' . $pattern_style . ' d="M63.1,6.8c-0.2,0-0.4,0-0.4,0c-20,0-62.7,4.4-62.7,36.4c0.2,21.8,22,50,46,50c7.8,0,14.4-2.8,20.6-7.4
		c14.8-11,33.4-35.8,33.6-54.9v-0.2C100.1,16,90.1,6.8,63.1,6.8z"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_svg = '
	<path ' . $pattern_style . ' d="M63.1,6.8c-0.2,0-0.4,0-0.4,0c-20,0-62.7,4.4-62.7,36.4c0.2,21.8,22,50,46,50c7.8,0,14.4-2.8,20.6-7.4
		c14.8-11,33.4-35.8,33.6-54.9v-0.2C100.1,16,90.1,6.8,63.1,6.8z"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
