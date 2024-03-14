<?php 
$margin = '0 0 20px 0';
$margin_2 = '0 20px'; 
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.4px; stroke-linejoin: round; fill-rule:evenodd;clip-rule:evenodd;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.25;"';
$pattern_svg = '
	<path ' . $pattern_style . ' d="M51.5,1.1C24.8,1.1,3,22.7,3,49.3c0,8.6,2.3,17,6.6,24.4L1.2,98.2c-0.2,0.5,0,1,0.3,1.4c0.3,0.3,0.6,0.4,1,0.4
	c0.1,0,0.3,0,0.4-0.1l25.6-8.1c7,3.8,15,5.7,23,5.7c26.7,0,48.5-21.6,48.5-48.2C100,22.8,78.2,1.1,51.5,1.1z"/>
	<path ' . $pattern_style_2 . ' d="M50.5,0.1C23.8,0.1,2,21.7,2,48.3c0,8.6,2.3,17,6.6,24.4L0.2,97.2c-0.2,0.5,0,1,0.3,1.4c0.3,0.3,0.6,0.4,1,0.4
	c0.1,0,0.3,0,0.4-0.1l25.6-8.1c7,3.8,15,5.7,23,5.7C77.2,96.5,99,75,99,48.3C99,21.8,77.2,0.1,50.5,0.1z"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern.php' );

$margin = '0'; 
$pattern_style = 'fill="none" style="stroke: ' . $pattern_color . '; stroke-width: 0.2px; stroke-linejoin: round; fill-rule:evenodd;clip-rule:evenodd;"';
$pattern_style_2 = 'fill="' . $pattern_color . '" style="opacity: 0.25;"';
$pattern_svg = '
	<path ' . $pattern_style . ' d="M51.5,1.1C24.8,1.1,3,22.7,3,49.3c0,8.6,2.3,17,6.6,24.4L1.2,98.2c-0.2,0.5,0,1,0.3,1.4c0.3,0.3,0.6,0.4,1,0.4
	c0.1,0,0.3,0,0.4-0.1l25.6-8.1c7,3.8,15,5.7,23,5.7c26.7,0,48.5-21.6,48.5-48.2C100,22.8,78.2,1.1,51.5,1.1z"/>
	<path ' . $pattern_style_2 . ' d="M50.5,0.1C23.8,0.1,2,21.7,2,48.3c0,8.6,2.3,17,6.6,24.4L0.2,97.2c-0.2,0.5,0,1,0.3,1.4c0.3,0.3,0.6,0.4,1,0.4
	c0.1,0,0.3,0,0.4-0.1l25.6-8.1c7,3.8,15,5.7,23,5.7C77.2,96.5,99,75,99,48.3C99,21.8,77.2,0.1,50.5,0.1z"/>
'; 

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/pattern-media.php' );
?>
