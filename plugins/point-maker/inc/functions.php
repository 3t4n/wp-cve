<?php
defined( 'ABSPATH' ) || exit;

function point_maker_BlackOrWhite ( $hex ) {
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	return ( ( ( $r * 0.299) + ( $g * 0.587) +  ( $b * 0.114)  ) > 186 ) ? "#000000" : "#ffffff" ;
}
