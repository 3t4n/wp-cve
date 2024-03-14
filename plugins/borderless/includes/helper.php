<?php

// don't load directly
defined( 'ABSPATH' ) || exit;


// Color Pallete - Light Colors
function luminanceLight($hexcolor, $percent)
{
	if ( strlen( $hexcolor ) < 6 ) {
		$hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
	}
	$hexcolor = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) );
	
	foreach ($hexcolor as $i => $color) {
		$from = $percent < 0 ? 0 : $color;
		$to = $percent < 0 ? $color : 255;
		$pvalue = ceil( ($to - $from) * $percent );
		$hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
	}
	
	return '#' . implode($hexcolor);
}

// Color Pallete - Dark Colors
function luminanceDark($hexcolor, $percent)
{
	if ( strlen( $hexcolor ) < 6 ) {
		$hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
	}
	$hexcolor = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) );
	
	foreach ($hexcolor as $i => $color) {
		$from = $percent < 0 ? 0 : $color;
		$to = $percent < 0 ? $color : 0;
		$pvalue = ceil( ($to - $from) * $percent );
		$hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
	}
	
	return '#' . implode($hexcolor);
}