<?php
/**
 * Template to render popups on frontend
 *
 * @var $id
 */

$styles = array();
if( themify_popup_check( 'popup_width' ) ) {
	if( $style === 'classic' ) {
		$styles[] = "body.themify-popup-showing-{$id} .mfp-wrap .mfp-inline-holder .mfp-content{width:" . themify_popup_get( 'popup_width' ) . themify_popup_get( 'popup_width_unit', 'px' ) . '!important}';
	} elseif( $style === 'slide-out' ) {
		$styles[] = "#themify-popup-{$id}{width:" . themify_popup_get( 'popup_width' ) . themify_popup_get( 'popup_width_unit', 'px' ) . '!important}';
	}
}
if( themify_popup_check( 'popup_height' ) && ! themify_popup_check( 'popup_auto_height' ) ) {
	if( $style === 'classic' ) {
		$styles[] = "body.themify-popup-showing-{$id} .mfp-wrap .mfp-inline-holder .mfp-content{height:" . themify_popup_get( 'popup_height' ) . themify_popup_get( 'popup_height_unit', 'px' ) . '!important}';
	} elseif( $style === 'slide-out' ) {
		$styles[] = "#themify-popup-{$id}{height:" . themify_popup_get( 'popup_height' ) . themify_popup_get( 'popup_height_unit', 'px' ) . '!important}';
	}
}
if( themify_popup_check( 'popup_overlay_color' ) ) {
	$styles[] = "body.themify-popup-showing-{$id} .mfp-bg{background-color: " . themify_popup_get( 'popup_overlay_color' ) . '}';
}
$styles[] = themify_popup_get_custom_css();

if( ! empty( $styles ) ) {
	printf( '<style>%s</style>', implode( "\n", $styles ) );
}