<?php
/**
 * Inline Style generator
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$zindex        = ! empty( $param['zindex'] ) ? $param['zindex'] : '9';
$zindex        = ( $zindex === '9' ) ? '9999' : $zindex;
$height        = ! empty( $param['height'] ) ? $param['height'] : '40';
$iconsize      = ! empty( $param['iconsize'] ) ? $param['iconsize'] : '24';
$fontsize      = ! empty( $param['fontsize'] ) ? $param['fontsize'] : '24';
$bwidth        = ! empty( $param['bwidth'] ) ? $param['bwidth'] . 'px' : '0';
$bcolor        = ! empty( $param['bcolor'] ) ? $param['bcolor'] : 'rgba(0,0,0,0.75)';
$gap           = ! empty( $param['gap'] ) ? $param['gap'] . 'px' : '0';

$css = "#side-menu-{$id} {
		--sm-z-index: {$zindex};
        --sm-item-height: {$height}px;
	    --sm-icon-width: {$height}px;
	    --sm-icon-size: {$iconsize}px;
	    --sm-label-size: {$fontsize}px;
	    --sm-border-width: {$bwidth}px;
	    --sm-border-color: {$bcolor};
	    --sm-button-space: {$gap};
}";

$count_items = ( ! empty( $param['menu_1']['item_type'] ) ) ? count( $param['menu_1']['item_type'] ) : '0';

if ( $count_items > 0 ) {
	for ( $i = 1; $i <= $count_items; $i ++ ) {
		$ii  = $i - 1;
		$color = !empty( $param['menu_1']['color'][ $ii ] ) ? $param['menu_1']['color'][ $ii ] : '#ffffff';
		$iconcolor = !empty( $param['menu_1']['iconcolor'][ $ii ] ) ? $param['menu_1']['iconcolor'][ $ii ] : '#ffffff';
		$bcolor = !empty( $param['menu_1']['bcolor'][ $ii ] ) ? $param['menu_1']['bcolor'][ $ii ] : '#00494f';
		$hbcolor = !empty( $param['menu_1']['hbcolor'][ $ii ] ) ? $param['menu_1']['hbcolor'][ $ii ] : '#80b719';
		$css .= "#side-menu-{$id} .sm-list .sm-item:nth-child({$i}) {
			--sm-color: {$color};
		    --sm-icon-color: {$iconcolor};
		    --sm-background: {$bcolor};
			--sm-hover-background: {$hbcolor};
		}";

	}
}

if ( ! empty( $param['include_mobile'] ) ) {
	$screen = ! empty( $param['screen'] ) ? $param['screen'] : 480;

	$css .= "
		@media only screen and (max-width: {$screen}px){
			#side-menu-{$id } {
				display:none;
			}
		}";
}

if ( ! empty( $param['include_more_screen'] ) ) {
	$screen_more = ! empty( $param['screen_more'] ) ? $param['screen_more'] : 1200;

	$css .= "
		@media only screen and (min-width: {$screen_more}px){
			#side-menu-{$id} {
				display:none;
			}
		}";
}

$css = trim( preg_replace( '~\s+~s', ' ', $css ) );