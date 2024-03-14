<?php
/**
 * The Slider Layout.
 *
 * The file is to prove markup for the slider layout.
 *
 * @since 1.2.1
 * @package Woo_Category_Slider
 * @subpackage public/layout
 */

defined( 'ABSPATH' ) || exit;

// Slider Section ( Container ) Starts.
$output .= '<div id="sp-wcsp-slider-section-' . $post_id . '" class="sp-wcsp-slider-section' . $container_class . $preloader_class . '" >';

// Navigation.
if ( ( 'show' === $navigation || 'hide_mobile' === $navigation ) && 'slider' === $layout_preset ) {
	$output .= '<div class="sp-wcsp-button"><div class="sp-wcsp-button-prev"><i class="fa fa-angle-left"></i></div><div class="sp-wcsp-button-next"><i class="fa fa-angle-right"></i></div></div>';
}

// Slider Wrapper Starts.
$output .= '<div id="sp-wcsp-wrapper-' . $post_id . '" class="' . $wrapper_class . '">';


$cat_count = 0;

if ( ! empty( $wcs_terms ) ) {
	foreach ( $wcs_terms as $wcs_term ) {

		require SP_WCS_PATH . '/public/layout/partials/content.php';
	}
}
$output .= '</div>'; // .sp-wcsp-wrapper Ends.

if ( ( 'show' === $pagination || 'hide_mobile' === $pagination ) && 'slider' === $layout_preset ) {
	$output .= '<div class="sp-wcsp-pagination"></div>';
}

$output .= '</div></div>';// .sp-wcsp-slider-section .sp-wcsp-slider-area Ends.
