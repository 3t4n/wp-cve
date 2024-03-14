<?php
/**
 * Template: Grid by Category.
 *
 * @package RT_FoodMenu
 */

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\RenderHelpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

extract( $arg );

$gridQuery = new WP_Query( $args );

$html  = '<div class="fmp-grids-wrapper">';
$html .= "<div class='fmp-category-title-wrapper $catsTitleType'><h2 class='fmp-category-title'><span>{$term->name}</span></h2></div>";
$html .= '<div class="fmp-col-xs-12 fmp-grids-wrapper">';
$html .= '<div class="fmp-row' . $gridType . '">';

while ( $gridQuery->have_posts() ) {
	$gridQuery->the_post();
	$id          = get_the_ID();
	$pLink       = get_the_permalink();
	$image       = Fns::getFeatureImage( $id, $imgSize );
	$excerpt     = RenderHelpers::getExcerpt( get_the_excerpt(), $excerpt_limit, $after_short_desc );
	$add_to_cart = null;

	if ( TLPFoodMenu()->has_pro() ) {
		$image = Fns::getFeatureImage( $id, $imgSize, $defaultImgId, $customImgSize );
	}

	if ( $source == 'product' && $wc == true ) {
		$_product = wc_get_product( $id );
		$price    = $_product->get_price_html();
		$product  = $_product = wc_get_product( $id );
		$pType    = $_product->get_type();

		if ( $_product->is_purchasable() ) {
			if ( $_product->is_in_stock() ) {
				ob_start();

				woocommerce_template_loop_add_to_cart();
				$add_to_cart .= apply_filters( 'rtfm_add_to_cart_btn', ob_get_contents(), $pLink, $id, $pType, $add_to_cart_text, $items, $anchorClass );

				ob_end_clean();
			}
		}
	} else {
		$price = Fns::getPriceWithLabel( $id );

		if ( TLPFoodMenu()->has_pro() ) {
			$price = \RT\FoodMenuPro\Helpers\FnsPro::fmpHtmlPrice( $id );
		}
	}

	$wooClass = 'product' === $source ? ' woo-template' : null;

	$html .= "<div class='{$grid} {$class}'>";
	$html .= "<div class='fmp-food-item {$source}'>";

	if ( in_array( 'image', $items, true ) ) {
		$html .= '<div class="fmp-image-wrap">';
		if ( ! $link ) {
			$html .= $image;
		} else {
			$html .= '<a class="' . esc_attr( $anchorClass ) . '" data-id="' . esc_attr( $id ) . '" href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '">' . $image . '</a>';
		}
		$html .= '</div>';
	}

	$html .= '<div class="fmp-content-wrap">';
	$html .= '<div class="fmp-title' . $wooClass . '">';

	if ( in_array( 'title', $items, true ) ) {
		if ( ! $link ) {
			$html .= '<h3 class="no-link">' . get_the_title() . '</h3>';
		} else {
			$html .= '<h3><a class="' . esc_attr( $anchorClass ) . '" data-id="' . esc_attr( $id ) . '" href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3>';
		}
	}

	if ( in_array( 'price', $items, true ) ) {
		$html .= '<span class="price">' . wp_kses_post( $price ) . '</span>';
	}

	$html .= '</div>';
	$html .= '<div class="fmp-body">';

	if ( in_array( 'excerpt', $items, true ) ) {
		$html .= '<p>' . $excerpt . '</p>';
	}

	$html .= '</div>';
	$html .= '<div class="fmp-footer' . $wooClass . '">';

	if ( in_array( 'read_more', $items, true ) && $link ) {
		$html .= '<a href="' . get_permalink() . '" data-id="' . esc_attr( $id ) . '" class="' . esc_attr( $anchorClass ) . ' fmp-btn-read-more type-1">' . esc_html( $read_more ) . '</a>';
	}

	if ( 'product' === $source ) {
		$html .= '<div class="fmp-add-to-cart rt-pos-r rt-d-flex">';

		if ( in_array( 'add_to_cart', $items, true ) || ! TLPFoodMenu()->has_pro() ) {
			$html .= stripslashes_deep( $add_to_cart );
		}

		$html .= '</div>';
	}

	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
}

wp_reset_postdata();

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

Fns::print_html( $html );
