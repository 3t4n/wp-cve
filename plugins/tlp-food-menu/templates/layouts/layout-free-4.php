<?php
/**
 * Template: Layout 2 (Free).
 *
 * @package RT_FoodMenu
 */

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$add_to_cart = null;

if ( $source == 'product' && $wc == true ) {
	global $product;

	$product = $_product = wc_get_product( $pID );
	$price   = $_product->get_price_html();
	$pType   = $_product->get_type();

	if ( $_product->is_purchasable() ) {
		if ( $_product->is_in_stock() ) {
			ob_start();

			woocommerce_template_loop_add_to_cart();
			$add_to_cart .= apply_filters( 'rtfm_add_to_cart_btn', ob_get_contents(), $pLink, $pID, $pType, $add_to_cart_text, $items );

			ob_end_clean();
		}
	}
} else {
	$price = Fns::getPriceWithLabel( $pID );

	if ( TLPFoodMenu()->has_pro() ) {
		$price = \RT\FoodMenuPro\Helpers\FnsPro::fmpHtmlPrice( $pID );
	}
}

$wooClass = 'product' === $source ? ' woo-template' : null;
$class    .= ' fmp-item-' . $pID;
?>
<div class="<?php echo esc_attr( $grid . ' ' . $class ); ?>">
	<div class='fmp-food-item <?php echo esc_attr( $source ); ?>'>
		<?php
		$html = '';

		if ( in_array( 'image', $items, true ) ) {
			$html .= '<div class="fmp-image-wrap">';
			$image = Fns::getFeatureImage( $pID, $imgSize );

			if ( TLPFoodMenu()->has_pro() ) {
				$image = Fns::getFeatureImage( $pID, $imgSize, $defaultImgId, $customImgSize );
			}

			if ( ! $link ) {
				$html .= $image;
			} else {
				$html .= '<a class="' . esc_attr( $anchorClass ) . '" data-id="' . esc_attr( $pID ) . '" href="' . esc_url( get_permalink() ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( get_the_title() ) . '">' . $image . '</a>';
			}

			$html .= '</div>';
		}

		$html .= '<div class="fmp-content-wrap">';
		$html .= '<div class="fmp-title' . $wooClass . '">';

		if ( in_array( 'title', $items, true ) ) {
			if ( ! $link ) {
				$html .= '<h3>' . get_the_title() . '</h3>';
			} else {
				$html .= '<h3><a class="' . esc_attr( $anchorClass ) . '" data-id="' . esc_attr( $pID ) . '" href="' . get_permalink() . '"  target="' . esc_attr( $target ) . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3>';
			}

			if ( in_array( 'price', $items, true ) ) {
				$html .= '<span class="price">' . wp_kses_post( $price ) . '</span>';
			}
		}

		$html .= '</div>';
		$html .= '<div class="fmp-body">';

		if ( in_array( 'excerpt', $items, true ) ) {
			$html .= '<p>' . $excerpt . '</p>';
		}

		$html .= '</div>';
		$html .= '<div class="fmp-footer' . $wooClass . '">';

		if ( TLPFoodMenu()->has_pro() && in_array( 'read_more', $items, true ) && $link ) {
			$html .= '<a href="' . esc_url( $pLink ) . '"  target="' . esc_attr( $target ) . '" data-id="' . esc_attr( $pID ) . '" class="' . esc_attr( $anchorClass ) . ' fmp-btn-read-more">' . esc_html( $read_more ) . '</a>';
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

		Fns::print_html( $html );
		?>
	</div>
</div>
