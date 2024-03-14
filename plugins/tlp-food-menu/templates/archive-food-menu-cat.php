<?php
/**
 * Template: Archive Food Menu.
 *
 * @package RT_FoodMenu
 */

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\RenderHelpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

get_header();
?>
<div class="fmp-container-fluid fmp-wrapper fmp-archive fmp fmp-hover-zoom_in fmp-image-top has-mobile-title has-mobile-image has-mobile-excerpt has-mobile-price" data-desktop-col="2" data-tab-col="1" data-mobile-col="1">
	<div data-title="Loading ..." class="fmp-row fmp-content-loader fmp-grid-by-cat-free-5 fmp-even">
		<div class="fmp-grids-wrapper">
			<?php
			$html     = null;
			$settings = get_option( TLPFoodMenu()->options['settings'] );
			$colClass = 'fmp-col-lg-6 fmp-col-md-6 fmp-col-sm-6 fmp-col-xs-12 even-grid-item fmp-grid-item fmp-ready-animation animated fadeIn';
			if ( have_posts() ) {
				$html .= '<div class="fmp-category-title-wrapper type-1">';
				$html .= '<h2 class="fmp-category-title"><span>' . single_cat_title( '', false ) . '</span></h2>';
				$html .= '</div>';
				$html .= '<div class="fmp-col-xs-12 fmp-grids-wrapper">';
				$html .= '<div class="fmp-row">';
				$count = 0;

				while ( have_posts() ) {
					the_post();

					$gTotal     = Fns::getPriceWithLabel( get_the_ID() );
					$thumbClass = has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail';

					if ( TLPFoodMenu()->has_pro() ) {
						$gTotal = \RT\FoodMenuPro\Helpers\FnsPro::fmpHtmlPrice( get_the_ID() );
					}

					$html .= '<div class="' . esc_attr( $colClass ) . '">';
					$html .= '<div class="fmp-food-item food-menu">';

					$html .= '<div class="fmp-image-wrap ' . $thumbClass . '">';
					$html .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';

					if ( has_post_thumbnail() ) {
						$html .= get_the_post_thumbnail( get_the_ID(), 'medium' );
					} else {
						$html .= "<img src='" . TLPFoodMenu()->assets_url() . 'images/demo-100x100.png' . "' alt='" . get_the_title() . "' />";
					}

					$html .= '</a>';
					$html .= '</div>';

					$html .= '<div class="fmp-content-wrap">';
					$html .= '<div class="fmp-title">';
					$html .= '<h3><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3>';
					$html .= '<span class="price">' . $gTotal . '</span>';
					$html .= '</div>';
					$html .= '<div class="fmp-body">';
					$html .= '<p>' . RenderHelpers::getExcerpt( get_the_excerpt(), 90, '...' ) . '</p>';
					$html .= '</div>';
					$html .= '</div>';

					$html .= '</div>';
					$html .= '</div>';
				}

				$html .= '</div>';
				$html .= '</div>';
			} else {
				$html .= '<p>' . esc_html__( 'No food found.', 'tlp-food-menu' ) . '</p>';
			}

			Fns::print_html( $html );
			?>
		</div>
	</div>
</div>

<?php
get_footer();
?>
