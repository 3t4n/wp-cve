<?php 
$margin = '0 0 -30px 40px';
$margin_2 = '0 20px';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.5px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<path ' . $pattern_style . ' d="M63.8,8.5c-0.2,0-0.4,0-0.4,0C43.8,8.5,2,12.8,2,44.2c0.2,21.4,21.6,49,45.1,49c7.6,0,14.1-2.7,20.2-7.3
		c14.5-10.8,32.7-35.1,32.9-53.8v-0.2C100.1,17.5,90.3,8.5,63.8,8.5z"/>
	<path ' . $pattern_style_2 . ' d="M61.9,6.5c-0.2,0-0.4,0-0.4,0C41.9,6.5,0,10.8,0,42.2c0.2,21.4,21.6,49,45.1,49c7.6,0,14.1-2.7,20.2-7.3
		C79.8,73.2,98,48.9,98.2,30.2V30C98.1,15.5,88.3,6.5,61.9,6.5z"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0';
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.3;"';
$pattern_svg = '
	<path ' . $pattern_style . ' d="M63.8,8.5c-0.2,0-0.4,0-0.4,0C43.8,8.5,2,12.8,2,44.2c0.2,21.4,21.6,49,45.1,49c7.6,0,14.1-2.7,20.2-7.3
		c14.5-10.8,32.7-35.1,32.9-53.8v-0.2C100.1,17.5,90.3,8.5,63.8,8.5z"/>
	<path ' . $pattern_style_2 . ' d="M61.9,6.5c-0.2,0-0.4,0-0.4,0C41.9,6.5,0,10.8,0,42.2c0.2,21.4,21.6,49,45.1,49c7.6,0,14.1-2.7,20.2-7.3
		C79.8,73.2,98,48.9,98.2,30.2V30C98.1,15.5,88.3,6.5,61.9,6.5z"/>
'; 
include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
