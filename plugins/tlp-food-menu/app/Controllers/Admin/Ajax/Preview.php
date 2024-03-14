<?php
/**
 * Preview Ajax Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Ajax;

use WP_Query;
use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\Options;
use RT\FoodMenu\Models\QueryArgs;
use RT\FoodMenu\Helpers\RenderHelpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Preview Ajax Class.
 */
class Preview {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_fmpPreviewAjaxCall', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$msg   = $data = null;
		$error = true;

		if ( Fns::verifyNonce() ) {
			$error = false;
			$scID  = isset( $_REQUEST['sc_id'] ) ? absint( $_REQUEST['sc_id'] ) : null;

			$rand          = absint( wp_rand() );
			$layoutID      = 'fmp-container-' . $rand;
			$html          = null;
			$containerAttr = null;
			$masonryG      = null;
			$args          = [];
			$arg           = [];
			$itemsOnMobile = '';

			$cImageSize = ! empty( $_REQUEST['fmp_custom_image_size'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_custom_image_size'] ) ) : [];

			$metas = [
				// Layout.
				'layout'             => ! empty( $_REQUEST['fmp_layout'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_layout'] ) ) : 'layout-free',
				'gridType'           => ! empty( $_REQUEST['fmp_grid_style'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_grid_style'] ) ) : 'even',

				// Columns.
				'dCols'              => ! empty( $_REQUEST['fmp_desktop_column'] ) ? absint( $_REQUEST['fmp_desktop_column'] ) : 0,
				'tCols'              => ! empty( $_REQUEST['fmp_tab_column'] ) ? absint( $_REQUEST['fmp_tab_column'] ) : 0,
				'mCols'              => ! empty( $_REQUEST['fmp_mobile_column'] ) ? absint( $_REQUEST['fmp_mobile_column'] ) : 0,

				// Image.
				'imgSize'            => isset( $_REQUEST['fmp_image_size'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_size'] ) ) : 'medium',
				'borderRadius'       => isset( $_REQUEST['fmp_image_radius'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_radius'] ) ) : 'default',
				'imageShape'         => isset( $_REQUEST['fmp_image_shape'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_shape'] ) ) : 'normal',
				'imagePosition'      => isset( $_REQUEST['fmp_image_position'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_position'] ) ) : 'top',

				// Excerpt.
				'excerpt_limit'      => isset( $_REQUEST['fmp_excerpt_limit'] ) ? absint( $_REQUEST['fmp_excerpt_limit'] ) : 0,
				'after_short_desc'   => ! empty( $_REQUEST['fmp_excerpt_custom_text'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_excerpt_custom_text'] ) ) : '',

				// Details Page.
				'link'               => ! empty( $_REQUEST['fmp_detail_page_link'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_detail_page_link'] ) ) : 0,
				'target'             => ! empty( $_REQUEST['fmp_detail_page_target'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_detail_page_target'] ) ) : '_self',

				// Filters.
				'postIn'             => ! empty( $_REQUEST['fmp_post__in'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_post__in'] ) ) : null,
				'postNotIn'          => ! empty( $_REQUEST['fmp_post__not_in'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_post__not_in'] ) ) : null,
				'limit'              => ( ( empty( $_REQUEST['fmp_limit'] ) || $_REQUEST['fmp_limit'] === '-1' ) ? 10000000 : absint( $_REQUEST['fmp_limit'] ) ),
				'source'             => ! empty( $_REQUEST['fmp_source'] ) ? $_REQUEST['fmp_source'] : TLPFoodMenu()->post_type,

				// Categories.
				'cats'               => ( isset( $_REQUEST['fmp_categories'] ) ? array_filter( array_map( 'absint', $_REQUEST['fmp_categories'] ) ) : [] ),
				'cats_title_type'    => ! empty( $_REQUEST['fmp_category_title_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_category_title_type'] ) ) : 'default',

				// Sorting.
				'order_by'           => isset( $_REQUEST['fmp_order_by'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_order_by'] ) ) : null,
				'order'              => isset( $_REQUEST['fmp_order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_order'] ) ) : null,

				// Pagination.
				'pagination'         => ! empty( $_REQUEST['fmp_pagination'] ) ? true : false,
				'posts_loading_type' => ! empty( $_REQUEST['fmp_pagination_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_pagination_type'] ) ) : 'pagination',
				'postsPerPage'       => isset( $_REQUEST['fmp_posts_per_page'] ) ? absint( $_REQUEST['fmp_posts_per_page'] ) : '',

				// Visibility.
				'items'              => ! empty( $_REQUEST['fmp_item_fields'] ) ? array_map( 'sanitize_text_field', $_REQUEST['fmp_item_fields'] ) : [],
				'mobileItems'        => ! empty( $_REQUEST['fmp_mobile_item_fields'] ) ? array_map( 'sanitize_text_field', $_REQUEST['fmp_mobile_item_fields'] ) : [],

				// Wrapper Class.
				'parentClass'        => ! empty( $_REQUEST['fmp_parent_class'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_parent_class'] ) ) : null,

				// Excerpt.
				'load_more_text'     => ! empty( $_REQUEST['fmp_load_more_button_text'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_load_more_button_text'] ) ) : esc_html__( 'Load More', 'tlp-food-menu' ),

				// Image.
				'customImgSize'      => ! empty( $cImageSize ) && is_array( $cImageSize ) ? $cImageSize : [],
				'imageShape'         => ! empty( $_REQUEST['fmp_image_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_type'] ) ) : 'normal',
				'defaultImgId'       => ! empty( $_REQUEST['fmp_placeholder_image'] ) ? absint( $_REQUEST['fmp_placeholder_image'] ) : null,
				'hoverIcon'          => ! empty( $_REQUEST['fmp_hover_icon'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_hover_icon'] ) ) : 0,

				// Animation.
				'animation'          => ! empty( $_REQUEST['fmp_image_hover'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_hover'] ) ) : 'zoom_in',

				// Popup.
				'linkType'           => ! empty( $_REQUEST['fmp_detail_page_link_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_detail_page_link_type'] ) ) : 'newpage',

				// Margin.
				'margin'             => ! empty( $_REQUEST['fmp_margin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_margin'] ) ) : 'default',
				'read_more'          => ! empty( $_REQUEST['fmp_read_more_button_text'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_read_more_button_text'] ) ) : esc_html__( 'Read More', 'tlp-food-menu' ),
			];

			$cOpt = ! empty( $_REQUEST['fmp_carousel_options'] ) ? array_map( 'sanitize_text_field', $_REQUEST['fmp_carousel_options'] ) : [];

			$arg = $this->argBuilder( $scID, $metas, $cOpt );

			$layout     = $metas['layout'];
			$isIsotope  = preg_match( '/isotope/', $layout );
			$isCarousel = preg_match( '/carousel/', $layout );
			$isCat      = preg_match( '/grid-by-cat/', $layout );

			$args = ( new QueryArgs() )->buildArgs( $scID, $metas, $isCarousel );

			$source            = isset( $_REQUEST['fmp_source'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_source'] ) ) : 'food-menu';
			$post_type         = ( $source && in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
			$arg['source']     = $post_type;
			$args['post_type'] = $post_type;

			$gridType      = $metas['gridType'];
			$animation     = isset( $metas['animation'] ) ? $metas['animation'] : 'zoom_in';
			$imagePosition = isset( $metas['imagePosition'] ) ? $metas['imagePosition'] : 'top';
			$pagination    = $metas['pagination'];
			$parentClass   = $metas['parentClass'];
			$linkType      = isset( $metas['linkType'] ) ? $metas['linkType'] : 'newpage';
			$hasModal      = 'popup' === $linkType ? true : false;
			$hasIcon       = $metas['hoverIcon'] ? true : false;
			$mobileItems   = ! empty( $metas['mobileItems'] ) ? $metas['mobileItems'] : array_keys( Options::fmpMobileItemFields() );

			if ( ! empty( $mobileItems ) && apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
				$mobileItems = unserialize( $mobileItems );
			}

			if ( ! empty( $mobileItems ) && is_array( $mobileItems ) ) {
				foreach ( $mobileItems as $mobileItem ) {
					$itemsOnMobile .= ' has-mobile-' . $mobileItem;
				}
			}

			if ( ! in_array( $layout, array_keys( Options::scLayouts() ), true ) ) {
				$layout = 'layout-free';
			}

			$dCol = 0 === $metas['dCols'] ? self::defaultColumns( $layout ) : $metas['dCols'];
			$tCol = 0 === $metas['tCols'] ? 2 : $metas['tCols'];
			$mCol = 0 === $metas['mCols'] ? 1 : $metas['mCols'];

			$containerAttr .= 'data-sc-id="' . absint( $scID ) . '" data-layout="' . esc_attr( $layout ) . '" data-desktop-col="' . absint( $dCol ) . '" data-tab-col="' . absint( $tCol ) . '" data-mobile-col="' . absint( $mCol ) . '"';

			if ( 'even' === $gridType ) {
				$masonryG = ' fmp-even';
			} elseif ( 'masonry' === $gridType ) {
				if ( ! $isCarousel ) {
					$masonryG = ' fmp-masonry';
				}
			}

			$preLoader     = ' fmp-pre-loader';
			$preLoaderHtml = '<div class="fmp-loading-overlay"></div><div class="fmp-loading fmp-ball-clip-rotate"><div></div></div>';

			if ( $isCarousel || $isIsotope ) {
				$preLoaderHtml = '<div class="fmp-loading-overlay full-op"></div><div class="fmp-loading fmp-ball-clip-rotate"><div></div></div>';
			}

			$containerClass  = 'fmp-container-fluid fmp-wrapper fmp';
			$containerClass .= ! empty( $animation ) ? ' fmp-hover-' . $animation : ' fmp-hover-zoom_in';
			$containerClass .= ! empty( $imagePosition ) ? ' fmp-image-' . $imagePosition : ' fmp-image-top';
			$containerClass .= ! empty( $imageShape ) ? ' fmp-image-' . $imageShape : ' fmp-img-normal';
			$containerClass .= ! empty( $itemsOnMobile ) ? $itemsOnMobile : '';
			$containerClass .= $hasIcon ? ' has-hover-icon' : ' no-hover-icon';

			if ( ! $isCat || 'grid-by-cat1' === $layout || 'grid-by-cat2' === $layout ) {
				$rowClass = 'fmp-row fmp-content-loader fmp-' . $layout . $masonryG . $preLoader;
			} else {
				$rowClass = 'fmp-row fmp-content-loader fmp-' . $layout . $preLoader;
			}

			if ( $isIsotope ) {
				$rowClass .= ' fmp-isotope-layout';
			}

			if ( $metas['read_more'] ) {
				$rowClass .= ' fmp-read-more-active';
			}

			if ( 'no' === $metas['margin'] ) {
				$rowClass .= ' fmp-no-margin';
			}

			$cssMeta = [
				'fmp_title_style'             => ! empty( $_REQUEST['fmp_title_style'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_title_style'] ) ) : [],
				'fmp_price_style'             => ! empty( $_REQUEST['fmp_price_style'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_price_style'] ) ) : [],
				'fmp_button_bg_color'         => ! empty( $_REQUEST['fmp_button_bg_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_button_bg_color'] ) ) : [],
				'fmp_button_bg_color_2'       => ! empty( $_REQUEST['fmp_button_bg_color_2'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_button_bg_color_2'] ) ) : [],
				'fmp_button_text_color'       => ! empty( $_REQUEST['fmp_button_text_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_button_text_color'] ) ) : [],
				'fmp_button_hover_bg_color'   => ! empty( $_REQUEST['fmp_button_hover_bg_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_button_hover_bg_color'] ) ) : [],
				'fmp_button_hover_bg_color_2' => ! empty( $_REQUEST['fmp_button_hover_bg_color_2'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_button_hover_bg_color_2'] ) ) : [],
				'fmp_button_hover_text_color' => ! empty( $_REQUEST['fmp_button_hover_text_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_button_hover_text_color'] ) ) : [],
				'fmp_button_typo'             => ! empty( $_REQUEST['fmp_button_typo'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_button_typo'] ) ) : [],
				'fmp_image_radius'            => ! empty( $_REQUEST['fmp_image_radius'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_image_radius'] ) ) : [],
				'fmp_border_color'            => ! empty( $_REQUEST['fmp_border_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_border_color'] ) ) : [],
				'fmp_category_style'          => ! empty( $_REQUEST['fmp_category_style'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_category_style'] ) ) : [],
				'fmp_content_wrap'            => ! empty( $_REQUEST['fmp_content_wrap'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_content_wrap'] ) ) : [],
				'fmp_section_wrap'            => ! empty( $_REQUEST['fmp_section_wrap'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_section_wrap'] ) ) : [],
				'fmp_primary_color'           => ! empty( $_REQUEST['fmp_primary_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_primary_color'] ) ) : [],
				'fmp_overlay_color'           => ! empty( $_REQUEST['fmp_overlay_color'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_overlay_color'] ) ) : [],
				'fmp_overlay_opacity'         => ! empty( $_REQUEST['fmp_overlay_opacity'] ) ? absint( $_REQUEST['fmp_overlay_opacity'] ) : 0,
				'fmp_short_description_style' => ! empty( $_REQUEST['fmp_short_description_style'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_short_description_style'] ) ) : [],
				'fmp_category_name_style'     => ! empty( $_REQUEST['fmp_category_name_style'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_category_name_style'] ) ) : [],
			];

			$beforeLoop = [
				'fmp_layout'                       => $layout,
				'fmp_source'                       => isset( $_REQUEST['fmp_source'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_source'] ) ) : 'food-menu',
				'fmp_categories'                   => $metas['cats'],
				'fmp_isotope_filter_tyle'          => isset( $_REQUEST['fmp_isotope_filter_tyle'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_isotope_filter_tyle'] ) ) : 'default',
				'fmp_isotope_filter_show_all_text' => isset( $_REQUEST['fmp_isotope_filter_show_all_text'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_isotope_filter_show_all_text'] ) ) : esc_html__( 'Show all', 'tlp-food-menu' ),
				'fmp_isotope_selected_filter'      => isset( $_REQUEST['fmp_isotope_selected_filter'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_isotope_selected_filter'] ) ) : null,
				'fmp_isotope_filter_show_all'      => ! empty( $_REQUEST['fmp_isotope_filter_show_all'] ),
				'fmp_isotope_search_filtering'     => ! empty( $_REQUEST['fmp_isotope_search_filtering'] ),
				'fmp_carousel_items_per_slider'    => isset( $_REQUEST['fmp_carousel_items_per_slider'] ) ? absint( $_REQUEST['fmp_carousel_items_per_slider'] ) : null,
				'fmp_carousel_speed'               => isset( $_REQUEST['fmp_carousel_speed'] ) ? absint( $_REQUEST['fmp_carousel_speed'] ) : 1000,
				'fmp_carousel_autoplay_timeout'    => isset( $_REQUEST['fmp_carousel_autoplay_timeout'] ) ? absint( $_REQUEST['fmp_carousel_autoplay_timeout'] ) : 5000,
				'fmp_carousel_options'             => isset( $_REQUEST['fmp_carousel_options'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['fmp_carousel_options'] ) ) : [],
			];

			$afterLoop = [
				'fmp_layout'           => $layout,
				'fmp_carousel_options' => $beforeLoop['fmp_carousel_options'],
			];

			$categoryArgs = [
				'fmp_source'            => $beforeLoop['fmp_source'],
				'fmp_custom_image_size' => $cImageSize,
				'fmp_placeholder_image' => $metas['defaultImgId'],
				'fmp_add_to_cart_text'  => ! empty( $_REQUEST['fmp_add_to_cart_text'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_add_to_cart_text'] ) ) : esc_html__( 'Add to cart', 'tlp-food-menu' ),
				'fmp_single_food_popup' => ! empty( $_REQUEST['fmp_single_food_popup'] ) ? true : false,
			];

			$html .= $this->layoutStyle( $layoutID, $cssMeta );
			$html .= '<div class="' . esc_attr( $containerClass ) . ' ' . esc_attr( $parentClass ) . '" id="' . esc_attr( $layoutID ) . '" ' . $containerAttr . '>';

			if ( $isCat ) {
				$html .= '<div data-title="' . esc_html__( 'Loading ...', 'tlp-food-menu' ) . '" class="' . esc_attr( $rowClass ) . '">';
				$html .= $this->renderCategoryLayouts( $metas, $categoryArgs, $args, $arg );
				$html .= $preLoaderHtml;
				$html .= '</div>';
			} else {
				$fmpQuery = new WP_Query( $args );

				if ( $fmpQuery->have_posts() ) {
					$html .= '<div data-title="' . esc_html__( 'Loading ...', 'tlp-food-menu' ) . '" class="' . esc_attr( $rowClass ) . '">';

					ob_start();
					do_action( 'rt_fm_preview_before_loop', $beforeLoop, $rand );
					$html .= ob_get_contents();
					ob_end_clean();

					while ( $fmpQuery->have_posts() ) {
						$fmpQuery->the_post();

						$loopArg = [
							'fmp_image_shape'           => $metas['imageShape'],
							'fmp_layout'                => $layout,
							'fmp_margin'                => $metas['margin'],
							'fmp_custom_image_size'     => $cImageSize,
							'fmp_placeholder_image'     => $metas['defaultImgId'],
							'fmp_read_more_button_text' => $metas['read_more'],
							'fmp_add_to_cart_text'      => isset( $_REQUEST['fmp_add_to_cart_text'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fmp_add_to_cart_text'] ) ) : null,
							'fmp_single_food_popup'     => ! empty( $_REQUEST['fmp_single_food_popup'] ) ? true : false,
						];

						// Loop Args.
						$arg = $this->loopArgBuilder( $arg, $metas, $loopArg, get_the_ID() );

						// Render Layout.
						$html .= Fns::render( 'layouts/' . $layout, $arg, true );
					}

					ob_start();
					do_action( 'rt_fm_preview_after_loop', $afterLoop );
					$html .= ob_get_contents();
					ob_end_clean();

					$html .= $preLoaderHtml;
					$html .= '</div>';

					if ( $pagination && ! $isCarousel ) {
						$renderPagination = RenderHelpers::renderPagination(
							$fmpQuery,
							$metas,
							$metas['limit'],
							$metas['postsPerPage'],
							$scID,
							$metas['posts_loading_type']
						);

						$html .= '<div class="fmp-col-xs-12">';
						$html .= '<div class="fmp-utility">';

						if ( 'pagination' === $metas['posts_loading_type'] || 'pagination_ajax' === $metas['posts_loading_type'] ) {
							$html .= $renderPagination;
						} elseif ( 'load_more' === $metas['posts_loading_type'] || 'load_on_scroll' === $metas['posts_loading_type'] ) {
							if ( TLPFoodMenu()->has_pro() ) {
								$html .= apply_filters( 'rtfm_pagination', $scID, $metas['posts_loading_type'], $metas['load_more_text'], $fmpQuery );
							}
						}

						$html .= '</div>';
						$html .= '</div>';
					}

					wp_reset_postdata();
				} else {
					$html .= '<p>' . esc_html__( 'No posts found.', 'tlp-food-menu' ) . '</p>';
				}
			}

			$html .= $preLoaderHtml;
			$html .= '</div>';

			$data = $html;
		} else {
			$msg = esc_html__( 'Security Error !!', 'tlp-food-menu' );
		}

		wp_send_json(
			[
				'error' => $error,
				'msg'   => $msg,
				'data'  => $data,
			]
		);

		die();
	}

	/**
	 * Layout Style
	 *
	 * @param int   $ID ID.
	 * @param array $scMeta SCMeta.
	 * @return string
	 */
	private function layoutStyle( $ID, $scMeta ) {
		$css  = null;
		$css .= "<style type='text/css' media='all'>";

		// Title.
		$title = ( ! empty( $scMeta['fmp_title_style'] ) ? $scMeta['fmp_title_style'] : [] );

		if ( ! empty( $title ) ) {
			$title_color       = ( ! empty( $title['color'] ) ? $title['color'] : null );
			$title_hover_color = ( ! empty( $title['hover_color'] ) ? $title['hover_color'] : null );
			$title_size        = ( ! empty( $title['size'] ) ? absint( $title['size'] ) : null );
			$title_weight      = ( ! empty( $title['weight'] ) ? $title['weight'] : null );
			$title_alignment   = ( ! empty( $title['align'] ) ? $title['align'] : null );

			$css .= "#{$ID} .fmp-title h3,";
			$css .= "#{$ID} .fmp-content h3 {";

			if ( $title_color ) {
				$css .= 'color:' . $title_color . ';';
			}
			if ( $title_size ) {
				$css .= 'font-size:' . $title_size . 'px;';
			}
			if ( $title_weight ) {
				$css .= 'font-weight:' . $title_weight . ';';
			}
			if ( $title_alignment ) {
				$css .= 'text-align:' . $title_alignment . ';';
			}
			$css .= '}';

			$css .= "#{$ID} .fmp-content h3:hover,";
			$css .= "#{$ID} h3.fmp-title:hover,";
			$css .= "#{$ID} .fmp-title h3:hover { ";

			if ( $title_hover_color ) {
				$css .= 'color:' . $title_hover_color . ';';
			}

			$css .= '}';
		}

		// Price.
		$price = ( ! empty( $scMeta['fmp_price_style'] ) ? $scMeta['fmp_price_style'] : [] );

		if ( ! empty( $price ) ) {
			$price_color     = ( ! empty( $price['color'] ) ? $price['color'] : null );
			$price_size      = ( ! empty( $price['size'] ) ? absint( $price['size'] ) : null );
			$price_weight    = ( ! empty( $price['weight'] ) ? $price['weight'] : null );
			$price_alignment = ( ! empty( $price['align'] ) ? $price['align'] : null );

			$css .= "#{$ID} .fmp-box .fmp-price,";
			$css .= "#{$ID} .fmp-box .price,";
			$css .= "#{$ID} .fmp-food-item .price,";
			$css .= "#{$ID} .fmp-layout8 .fmp-box-wrapper .fmp-price-wrapper .fmp-price,";
			$css .= "#{$ID} .fmp-layout1 .fmp-price-wrapper span.fmp-price,";
			$css .= "#{$ID} .fmp-content-wrap .price {";

			if ( $price_color ) {
				$css .= 'color:' . $price_color . ';';
			}

			if ( $price_size ) {
				$css .= 'font-size:' . $price_size . 'px;';
			}

			if ( $price_weight ) {
				$css .= 'font-weight:' . $price_weight . ';';
			}

			if ( $price_alignment ) {
				$css .= 'text-align:' . $price_alignment . ';';
			}

			$css .= '}';

			$css .= "#{$ID} .fmp-layout8 .fmp-box-wrapper .fmp-price-wrapper .fmp-price,";
			$css .= "#{$ID} .fmp-layout1 .fmp-price-wrapper span.fmp-price {";
			$css .= 'background:' . $price_color . ';';
			$css .= '}';

			$css .= "#{$ID} .fmp-layout1 .fmp-price-wrapper span.fmp-price::before {";
			$css .= 'border-right-color:' . $price_color . ';';
			$css .= '}';

			$css .= "#{$ID} .fmp-layout1 .fmp-price-wrapper span.fmp-price::after {";
			$css .= 'border-left-color:' . $price_color . ';';
			$css .= 'border-top-color:' . $price_color . ';';
			$css .= '}';
		}

		// Button bg color.
		$btnBg  = ( ! empty( $scMeta['fmp_button_bg_color'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_bg_color'] ) : null );
		$btnBg2 = ( ! empty( $scMeta['fmp_button_bg_color_2'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_bg_color_2'] ) : null );

		if ( empty( $btnBg2 ) ) {
			$css .= "#{$ID} a.fmp-btn-read-more::before,
			#{$ID} a.fmp-wc-add-to-cart-btn::before,
			#{$ID} .owl-theme .owl-dots .owl-dot span,
			#{$ID} .owl-theme .owl-nav [class*=owl-],
			#{$ID} .fmp-isotope-buttons button::before,
			#{$ID} .fmp-utility .fmp-load-more button::before,
			#{$ID} .fmp-pagination ul.pagination-list li span::before,
			#{$ID} .fmp-load-more::before,
			#{$ID} .fmp-carousel .swiper-arrow::before,
			#{$ID} .fmp-layout5 .fmp-price,
			#{$ID} .fmp-layout5 .fmp-price-box .fmp-attr-variation-wrapper,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button + .added_to_cart,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::before {";
			$css .= "background: $btnBg;";
			$css .= '}';
		} elseif ( $btnBg && $btnBg2 ) {
			$css .= "#{$ID} a.fmp-btn-read-more::before,
			#{$ID} a.fmp-wc-add-to-cart-btn::before,
			#{$ID} .owl-theme .owl-dots .owl-dot span,
			#{$ID} .owl-theme .owl-nav [class*=owl-],
			#{$ID} .fmp-isotope-buttons button::before,
			#{$ID} .fmp-utility .fmp-load-more button::before,
			#{$ID} .fmp-pagination ul.pagination-list li span::before,
			#{$ID} .fmp-load-more::before,
			#{$ID} .fmp-layout5 .fmp-price,
			#{$ID} .fmp-layout5 .fmp-price-box .fmp-attr-variation-wrapper,
			#{$ID} .fmp-carousel .swiper-arrow::before,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button + .added_to_cart,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::before {";
			$css .= "background: linear-gradient(94.5deg, $btnBg 16.12%, $btnBg2 58.97%);";
			$css .= '}';

			$css .= "#{$ID} .owl-theme .owl-dots .owl-dot span,
			#{$ID} .owl-theme .owl-nav [class*=owl-],
			#{$ID} .fmp-layout5 .fmp-price,
			#{$ID} .fmp-layout5 .fmp-price-box .fmp-attr-variation-wrapper,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button + .added_to_cart {";
			$css .= "background: $btnBg;";
			$css .= '}';
		}

		if ( $btnBg ) {
			$css .= "#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn,
			#{$ID} .fmp-layout5 .fmp-price-box .quantity .input-text.qty.text {";
			$css .= 'border-color:' . $btnBg . ';';
			$css .= '}';

			$css .= '.fmp-iso-filter.type-1 button::after {';
			$css .= 'border-top-color:' . $btnBg . ';';
			$css .= '}';
		}

		// button text color.
		$btnText = ( ! empty( $scMeta['fmp_button_text_color'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_text_color'] ) : null );

		if ( $btnText ) {
			$css .= "#{$ID} a.fmp-btn-read-more,
			#{$ID} a.fmp-wc-add-to-cart-btn,
			#{$ID} .owl-theme .owl-dots .owl-dot span,
			#{$ID} .owl-theme .owl-nav [class*=owl-],
			#{$ID} .fmp-isotope-buttons button,
			#{$ID} .fmp-utility .fmp-load-more button,
			#{$ID} .fmp-load-more::before,
			#{$ID} .fmp-carousel .swiper-arrow,
			#{$ID} .fmp-pagination ul.pagination-list li a,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn,
			#{$ID} .fmp-food-item .button {";
			$css .= 'color:' . $btnText . ';';
			$css .= '}';
		}

		// Button hover bg color.
		$btnHbg  = ( ! empty( $scMeta['fmp_button_hover_bg_color'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_hover_bg_color'] ) : null );
		$btnHbg2 = ( ! empty( $scMeta['fmp_button_hover_bg_color_2'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_hover_bg_color_2'] ) : null );

		if ( empty( $btnHbg2 ) ) {
			$css .= "#{$ID} a.fmp-btn-read-more::after,
			#{$ID} a.fmp-wc-add-to-cart-btn::after,
			#{$ID} .owl-theme .owl-nav [class*=owl-]:hover,
			#{$ID} .fmp-utility .fmp-load-more button::after,
			#{$ID} .owl-theme .owl-dots .owl-dot:hover span,
			#{$ID} .owl-theme .owl-dots .owl-dot.active span,
			#{$ID} .fmp-isotope-buttons button.selected::after,
			#{$ID} .fmp-load-more::after,
			#{$ID} .fmp-carousel .swiper-arrow::after,
			#{$ID} .fmp-isotope-buttons button::after,
			#{$ID} .fmp-pagination ul.pagination-list li.active span::after,
			#{$ID} .fmp-pagination ul.pagination-list li a::after,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn::after,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::after {";
			$css .= 'background:' . $btnHbg . ';';
			$css .= '}';
		} elseif ( $btnHbg && $btnHbg2 ) {
			$css .= "#{$ID} a.fmp-btn-read-more::after,
			#{$ID} a.fmp-wc-add-to-cart-btn::after,
			#{$ID} .owl-theme .owl-nav [class*=owl-]:hover,
			#{$ID} .fmp-utility .fmp-load-more button::after,
			#{$ID} .owl-theme .owl-dots .owl-dot:hover span,
			#{$ID} .owl-theme .owl-dots .owl-dot.active span,
			#{$ID} .fmp-isotope-buttons button.selected::after,
			#{$ID} .fmp-isotope-buttons button::after,
			#{$ID} .fmp-load-more::after,
			#{$ID} .fmp-carousel .swiper-arrow::after,
			#{$ID} .fmp-pagination ul.pagination-list li.active span::after,
			#{$ID} .fmp-pagination ul.pagination-list li a::after,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn::after,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::after {";
			$css .= "background: linear-gradient(94.5deg, $btnHbg 16.12%, $btnHbg2 58.97%);";
			$css .= '}';

			$css .= "#{$ID} .owl-theme .owl-nav [class*=owl-]:hover,
			#{$ID} .owl-theme .owl-dots .owl-dot:hover span,
			#{$ID} .owl-theme .owl-dots .owl-dot.active span {";
			$css .= 'background:' . $btnHbg . ';';
			$css .= '}';
		}

		if ( $btnHbg ) {
			$css .= "#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn:hover {";
			$css .= 'border-color:' . $btnHbg . ';';
			$css .= '}';
		}

		// Button hover text color.
		$btnHtext = ( ! empty( $scMeta['fmp_button_hover_text_color'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_hover_text_color'] ) : null );

		if ( $btnHtext ) {
			$css .= "#{$ID} a.fmp-btn-read-more:hover,
			#{$ID} a.fmp-wc-add-to-cart-btn:hover,
			#{$ID} .owl-theme .owl-nav [class*=owl-]:hover,
			#{$ID} .fmp-utility .fmp-load-more button:hover,
			#{$ID} .owl-theme .owl-dots .owl-dot:hover span,
			#{$ID} .owl-theme .owl-dots .owl-dot.active span,
			#{$ID} .fmp-isotope-buttons button.selected,
			#{$ID} .fmp-isotope-buttons button:hover,
			#{$ID} .fmp-carousel .swiper-arrow:hover,
			#{$ID} .fmp-pagination ul.pagination-list li.active span,
			#{$ID} .fmp-pagination ul.pagination-list li a:hover,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn:hover,
			#{$ID} .fmp-food-item .button:hover {";
			$css .= 'color:' . $btnHtext . ';';
			$css .= '}';
		}

		// Button Typography.
		$btn_typo = ( ! empty( $scMeta['fmp_button_typo'] ) ? $scMeta['fmp_button_typo'] : [] );

		if ( ! empty( $btn_typo ) ) {
			$btn_size   = ( ! empty( $btn_typo['size'] ) ? absint( $btn_typo['size'] ) : null );
			$btn_weight = ( ! empty( $btn_typo['weight'] ) ? $btn_typo['weight'] : null );

			$css .= "#{$ID} .fmp-iso-filter button,";
			$css .= "#{$ID} .fmp-btn-read-more,";
			$css .= "#{$ID} .fmp-load-more,";
			$css .= "#{$ID}.fmp-wrapper .fmp-food-item.product a.button,";
			$css .= "#{$ID} .fmp-wc-add-to-cart-btn { ";

			if ( $btn_size ) {
				$css .= 'font-size:' . $btn_size . 'px;';
			}
			if ( $btn_weight ) {
				$css .= 'font-weight:' . $btn_weight . ';';
			}
			$css .= '}';
		}

		// Image Border Radius.
		$img_border_radius = ! empty( $scMeta['fmp_image_radius'] ) ? $scMeta['fmp_image_radius'] : null;

		if ( ! empty( $img_border_radius ) ) {
			$css .= "#{$ID} .fmp-layout-free .fmp-food-item .fmp-image-wrap,";
			$css .= "#{$ID} .fmp-box-wrapper .fmp-box .fmp-image-wrap,";
			$css .= "#{$ID} .fmp-layout1 .fmp-box,";
			$css .= "#{$ID} .fmp-layout1 .fmp-box::before,";
			$css .= "#{$ID} .fmp-layout1 .fmp-box::after,";
			$css .= "#{$ID} .fmp-layout2 .fmp-box .fmp-img-wrapper:before,";
			$css .= "#{$ID} .fmp-layout8 .fmp-box-wrapper .fmp-box .fmp-image-wrap > a,";
			$css .= "#{$ID} .fmp-layout-free-4 .fmp-food-item .fmp-image-wrap,";
			$css .= "#{$ID} [class*=fmp-layout-free] .fmp-food-item .fmp-image-wrap,";
			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-food-item .fmp-image-wrap,";
			$css .= "#{$ID} .fmp-layout5 .fmp-box .fmp-image-wrap,";
			$css .= "#{$ID} .fmp-layout5 .fmp-box .fmp-image-wrap > a,";
			$css .= "#{$ID} .fmp-cat1 .fmp-media .fmp-image,";
			$css .= "#{$ID} [class*=fmp-grid-by-cat-free] .fmp-food-item .fmp-image-wrap { ";
			$css .= 'border-radius:' . esc_html( $img_border_radius );
			$css .= '}';
		}

		// Vertical Border.
		$center_border = ( ! empty( $scMeta['fmp_border_color'] ) ? Fns::sanitize_hex_color( $scMeta['fmp_border_color'] ) : [] );

		if ( $center_border ) {
			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-col-xs-12 > .fmp-row::after,";
			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-col-xs-12 > .fmp-row::before {";
			$css .= 'background-color:' . $center_border . ';';
			$css .= '}';
		}

		// Category Banner.
		$categoryBanner = ( ! empty( $scMeta['fmp_category_style'] ) ? array_filter( $scMeta['fmp_category_style'] ) : null );

		if ( ! empty( $categoryBanner ) ) {
			$bannerBgColor1  = ( ! empty( $categoryBanner['first_color'] ) ? $categoryBanner['first_color'] : null );
			$bannerBgColor2  = ( ! empty( $categoryBanner['second_color'] ) ? $categoryBanner['second_color'] : null );
			$bannerColor     = ( ! empty( $categoryBanner['text_color'] ) ? $categoryBanner['text_color'] : null );
			$bannerFont      = ( ! empty( $categoryBanner['size'] ) ? absint( $categoryBanner['size'] ) : null );
			$bannerWeight    = ( ! empty( $categoryBanner['weight'] ) ? $categoryBanner['weight'] : null );
			$bannerAlignment = ( ! empty( $categoryBanner['align'] ) ? $categoryBanner['align'] : null );

			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper.type-1 .fmp-category-title::before,";
			$css .= "#{$ID} .fmp-grid-by-cat-free .fmp-category-title-wrapper.type-1 .fmp-category-title::before {";

			if ( empty( $bannerBgColor2 ) ) {
				$css .= "background: $bannerBgColor1;";
			} elseif ( $bannerBgColor1 && $bannerBgColor2 ) {
				$css .= "background: linear-gradient(94.5deg, $bannerBgColor1 16.12%, $bannerBgColor2 58.97%);";
			}

			$css .= '}';

			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper .fmp-category-title::after,";
			$css .= "#{$ID} .fmp-grid-by-cat-free .fmp-category-title-wrapper .fmp-category-title::after {";

			if ( $bannerBgColor2 ) {
				$css .= "box-shadow: 0px 8px 30px $bannerBgColor2;";
			}

			$css .= '}';

			if ( $bannerColor ) {
				$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper .fmp-category-title,";
				$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper .fmp-category-title {";
				$css .= 'color:' . $bannerColor;
				$css .= '}';
			}

			if ( $bannerFont && $bannerWeight ) {
				$css .= "#{$ID} .fmp-cat1 .fmp-cat-title h2,";
				$css .= "#{$ID} .fmp-cat2 .fmp-cat-title h2,";
				$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper .fmp-category-title span, ";
				$css .= "#{$ID} .fmp-grid-by-cat-free .fmp-category-title-wrapper .fmp-category-title span { ";

				if ( $bannerFont ) {
					$css .= 'font-size:' . $bannerFont . 'px;';
				}

				if ( $bannerWeight ) {
					$css .= 'font-weight:' . $bannerWeight . ';';
				}

				$css .= '}';
			}

			if ( $bannerAlignment ) {
				$css .= "#{$ID} .fmp-cat1 .fmp-cat-title h2,";
				$css .= "#{$ID} .fmp-cat2 .fmp-cat-title h2,";
				$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper,";
				$css .= "#{$ID} .fmp-grid-by-cat-free .fmp-category-title-wrapper { ";
				$css .= 'text-align:' . $bannerAlignment . ';';
				$css .= '}';
			}
		}

		// Element / Content Wrap.
		$contentWrap = ( ! empty( $scMeta['fmp_content_wrap'] ) ? $scMeta['fmp_content_wrap'] : [] );

		if ( ! empty( array_filter( $contentWrap ) ) ) {
			$contentBgColor = ( ! empty( $contentWrap['bg_color'] ) ? $contentWrap['bg_color'] : null );
			$contentRadius  = ( ! empty( $contentWrap['border_radius'] ) ? $contentWrap['border_radius'] : null );

			$css .= "#{$ID} .fmp-layout2 .fmp-box,";
			$css .= "#{$ID} .fmp-grid-item .fmp-box,";
			$css .= "#{$ID} .fmp-layout4 .fmp-box .fmp-media,";
			$css .= "#{$ID} .fmp-layout5 .fmp-box,";
			$css .= "#{$ID} .fmp-food-item {";

			if ( $contentBgColor ) {
				$css .= 'background-color:' . $contentBgColor . ';';
			}

			if ( $contentRadius ) {
				$css .= 'border-radius:' . $contentRadius . ';';
			}

			$css .= '}';
		}

		// Full Section Wrap.
		$sectionWrap = ( ! empty( $scMeta['fmp_section_wrap'] ) ? $scMeta['fmp_section_wrap'] : [] );

		if ( ! empty( array_filter( $sectionWrap ) ) ) {
			$contentBgColor = ( ! empty( $sectionWrap['bg_color'] ) ? $sectionWrap['bg_color'] : null );
			$contentRadius  = ( ! empty( $sectionWrap['border_radius'] ) ? $sectionWrap['border_radius'] : null );

			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-background::before,";
			$css .= "#{$ID}:not([data-layout='grid-by-cat-free-2']) {";

			if ( $contentBgColor ) {
				$css .= 'background-color:' . $contentBgColor . ';';
			}

			if ( $contentRadius ) {
				$css .= 'border-radius:' . $contentRadius . ';';
			}

			$css .= '}';
		}

		// Primary Color.
		$primaryColor = ( ! empty( $scMeta['fmp_primary_color'] ) ? $scMeta['fmp_primary_color'] : null );

		if ( $primaryColor ) {
			// Primary Color.
			$css .= "#{$ID} { ";
			$css .= '--rtfm-primary-color:' . $primaryColor . ';';
			$css .= '}';

			// Cat layout.
			$css .= "#{$ID} .fmp-cat1 .fmp-cat-title:after,
					#{$ID} .fmp-layout2 .fmp-box .fmp-content .fmp-title:before,
					#{$ID} .fmp-layout5 .fmp-price-box .fmp-attr-variation-wrapper,
					#{$ID} .fmp-layout5 .fmp-price-box .fmp-price,
					#{$ID} .fmp-layout7 .fmp-box-wrapper .fmp-price-wrapper.is-variable::after,
					#{$ID} .fmp-layout7 .fmp-box-wrapper .fmp-price-wrapper:not(.is-variable) .fmp-price::after,
					#{$ID} .fmp-layout1 .fmp-box span.fmp-price { ";
			$css .= 'background :' . $primaryColor . ';';
			$css .= '}';

			$css .= "#{$ID} .fmp-cat1 .fmp-media-body h3, ";
			$css .= "#{$ID} .fmp-cat2 .fmp-box ul.menu-list li, ";
			$css .= "#{$ID} .fmp-cat1 .fmp-media-body h3 {";
			$css .= 'border-color :' . $primaryColor . ';';
			$css .= '}';
		}

		// Overlay.
		$overlay_color = ( ! empty( $scMeta['fmp_overlay_color'] ) ? Fns::rtHex2rgba( $scMeta['fmp_overlay_color'], ! empty( $scMeta['fmp_overlay_opacity'] ) ? absint( $scMeta['fmp_overlay_opacity'] ) / 100 : .8 ) : null );

		if ( $overlay_color ) {
			$css .= "#{$ID}.fmp-wrapper .fmp-image-wrap > a::before,";
			$css .= "#{$ID} .fmp-layout2 .fmp-box .fmp-img-wrapper:before,";
			$css .= "#{$ID} .fmp-layout-grid-by-cat2 .fmp-cat2 .fmp-cat-title:after {";

			if ( $overlay_color ) {
				$css .= 'background-color:' . $overlay_color . ';';
			}

			$css .= '}';

			$css .= "#{$ID} .fmp-layout1 .fmp-box::before {";
			$css .= 'background: linear-gradient(180deg, rgba(0, 0, 0, 0) 44.82%, ' . $primaryColor . ' 100%);';
			$css .= '}';

			$css .= "#{$ID} .fmp-layout1 .fmp-box::after {";
			$css .= 'background: linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, ' . $primaryColor . ' 100%);';
			$css .= '}';
		}

		// Short Description.
		$sDesc = ( ! empty( $scMeta['fmp_short_description_style'] ) ? $scMeta['fmp_short_description_style'] : [] );

		if ( ! empty( $sDesc ) ) {
			$sDesc_color     = ( ! empty( $sDesc['color'] ) ? $sDesc['color'] : null );
			$sDesc_color_h   = ( ! empty( $sDesc['hover_color'] ) ? $sDesc['hover_color'] : null );
			$sDesc_size      = ( ! empty( $sDesc['size'] ) ? absint( $sDesc['size'] ) : null );
			$sDesc_weight    = ( ! empty( $sDesc['weight'] ) ? $sDesc['weight'] : null );
			$sDesc_alignment = ( ! empty( $sDesc['align'] ) ? $sDesc['align'] : null );
			$css            .= "#{$ID} .fmp-box .fmp-title p,";
			$css            .= "#{$ID} .fmp-content-wrap > p,";
			$css            .= "#{$ID} .fmp-media-body > p,";
			$css            .= "#{$ID} .fmp-box li > p,";
			$css            .= "#{$ID} .fmp-body > p,";
			$css            .= "#{$ID} .fmp-content > p,";
			$css            .= "#{$ID} [class*=fmp-layout-free] .fmp-food-item .fmp-body p,";
			$css            .= "#{$ID} .fmp-media-body .info-part > p {";

			if ( $sDesc_color ) {
				$css .= 'color:' . $sDesc_color . ';';
			}

			if ( $sDesc_size ) {
				$css .= 'font-size:' . $sDesc_size . 'px;';
			}

			if ( $sDesc_weight ) {
				$css .= 'font-weight:' . $sDesc_weight . ';';
			}

			if ( $sDesc_alignment ) {
				$css .= 'text-align:' . $sDesc_alignment . ';';
			}

			$css .= '}';

			if ( $sDesc_color_h ) {
				$css .= "#{$ID} .fmp-box .fmp-title p:hover,";
				$css .= "#{$ID} .fmp-content-wrap > p:hover,";
				$css .= "#{$ID} .fmp-media-body > p:hover,";
				$css .= "#{$ID} .fmp-body > p:hover,";
				$css .= "#{$ID} .fmp-box li > p:hover,";
				$css .= "#{$ID} .fmp-content > p:hover,";
				$css .= "#{$ID} [class*=fmp-layout-free] .fmp-food-item .fmp-body p:hover,";
				$css .= "#{$ID} .fmp-media-body .info-part > p:hover {";
				$css .= 'color:' . $sDesc_color_h . ';';
				$css .= '}';
			}
		}

		// Category name.
		$cat = ( ! empty( $scMeta['fmp_category_name_style'] ) ? $scMeta['fmp_category_name_style'] : [] );

		if ( ! empty( $cat ) ) {
			$cat_color     = ( ! empty( $cat['color'] ) ? $cat['color'] : null );
			$cat_size      = ( ! empty( $cat['size'] ) ? absint( $cat['size'] ) : null );
			$cat_weight    = ( ! empty( $cat['weight'] ) ? $cat['weight'] : null );
			$cat_alignment = ( ! empty( $cat['align'] ) ? $cat['align'] : null );

			$css .= "#{$ID} .fmp-category-title,";
			$css .= "#{$ID} .fmp-cat-title h2 {";

			if ( $cat_color ) {
				$css .= 'color:' . $cat_color . ';';
			}

			if ( $cat_size ) {
				$css .= 'font-size:' . $cat_size . 'px;';
			}

			if ( $cat_weight ) {
				$css .= 'font-weight:' . $cat_weight . ';';
			}

			if ( $cat_alignment ) {
				$css .= 'text-align:' . $cat_alignment . ';';
			}

			$css .= '}';

			if ( $cat_color ) {
				$css .= "#{$ID} .fmp-cat-title p.cat-description {";
				$css .= 'color:' . $cat_color . ';';
				$css .= '}';
			}
		}

		$css .= '</style>';

		return $css;
	}

	/**
	 * Renders Category Layouts.
	 *
	 * @param array $metas Meta values.
	 * @param array $scMeta Meta values.
	 * @param array $args Query Args.
	 * @param array $arg Args.
	 * @return mixed
	 */
	private function renderCategoryLayouts( $metas, $scMeta, $args, $arg ) {
		$cats   = $metas['cats'];
		$catVar = [];
		$html   = null;

		$source           = $scMeta['fmp_source'];
		$post_type        = ( $source && in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
		$categoryTaxonomy = ( 'product' === $post_type ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];

		if ( ! empty( $cats ) && apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
			$cats = unserialize( $cats );
		}

		$catVar['hide_empty'] = true;

		if ( ! empty( $cats ) ) {
			$catVar['include'] = $cats;
		}

		if ( function_exists( 'get_term_meta' ) ) {
			$catVar['taxonomy']   = $categoryTaxonomy;
			$catVar['orderby']    = 'meta_value_num';
			$catVar['order']      = 'ASC';
			$metaKey              = ( 'product' === $categoryTaxonomy ) ? 'order' : '_order';
			$catVar['meta_query'] = [
				'relation' => 'OR',
				[
					'key'     => $metaKey,
					'compare' => 'NOT EXISTS',
				],
				[
					'key'  => $metaKey,
					'type' => 'NUMERIC',
				],
			];

			$terms = get_terms( $catVar );
		} else {
			$terms = get_terms( $categoryTaxonomy, $catVar );
		}

		if ( is_array( $terms ) && ! empty( $terms ) && empty( $terms['errors'] ) ) {
			foreach ( $terms as $term ) {
				if ( ! empty( $cats ) && is_array( $cats ) && ! in_array( $term->term_id, $cats ) ) {
					continue;
				}

				$taxQ   = [];
				$taxQ[] = [
					'taxonomy' => $categoryTaxonomy,
					'field'    => 'term_id',
					'terms'    => [ $term->term_id ],
				];

				$args['tax_query'] = $taxQ;

				$data['args']                   = $args;
				$data['taxonomy']               = $categoryTaxonomy;
				$data['excerpt_limit']          = $metas['excerpt_limit'];
				$data['after_short_desc']       = $metas['after_short_desc'];
				$data['imgSize']                = $metas['imgSize'];
				$data['term']                   = $term;
				$data['arg']                    = $arg;
				$data['args']['posts_per_page'] = -1;
				$data['gridType']               = 'masonry' === $metas['gridType'] ? ' fmp-masonry' : '';
				$data['read_more']              = isset( $metas['read_more'] ) ? $metas['read_more'] : null;
				$data['catsTitleType']          = 'default' === $metas['cats_title_type'] ? RenderHelpers::defaultCatTitle( $metas['layout'] ) : $metas['cats_title_type'];

				if ( TLPFoodMenu()->has_pro() ) {
					$customImg  = isset( $scMeta['fmp_custom_image_size'] ) && ! empty( $scMeta['fmp_custom_image_size'] ) ? $scMeta['fmp_custom_image_size'] : null;
					$defaultImg = isset( $scMeta['fmp_placeholder_image'] ) && ! empty( $scMeta['fmp_placeholder_image'] ) ? absint( $scMeta['fmp_placeholder_image'] ) : null;
					$cartText   = isset( $scMeta['fmp_add_to_cart_text'] ) && ! empty( $scMeta['fmp_add_to_cart_text'] ) ? esc_attr( $scMeta['fmp_add_to_cart_text'] ) : null;
					$popup      = isset( $scMeta['fmp_single_food_popup'] ) && ! empty( $scMeta['fmp_single_food_popup'] ) ? true : false;

					$data['defaultImgId']     = ! empty( $defaultImg ) ? $defaultImg : null;
					$data['customImgSize']    = ! empty( $customImg ) ? $customImg : null;
					$data['add_to_cart_text'] = ! empty( $cartText ) ? $cartText : esc_html__( 'Add to cart', 'tlp-food-menu' );

					if ( $metas['link'] && $popup ) {
						$data['arg']['anchorClass'] = ' fmp-popup';
					}
				}

				$html .= Fns::render( 'layouts/' . $metas['layout'], $data, true );
			}
		} else {
			$html .= '<p>' . esc_html__( 'No categories found', 'tlp-food-menu' ) . '</p>';
		}

		return $html;
	}

	private static function argBuilder( $iD, $metas, $carouselOpts ) {
		$arg                = [];
		$arg['class']       = null;
		$arg['grid']        = null;
		$arg['anchorClass'] = null;

		$layout = $metas['layout'];
		$link   = $metas['link'];

		$isIsotope  = preg_match( '/isotope/', $layout );
		$isCarousel = preg_match( '/carousel/', $layout );

		$dCol = 0 === $metas['dCols'] ? self::defaultColumns( $layout ) : $metas['dCols'];
		$tCol = 0 === $metas['tCols'] ? 2 : $metas['tCols'];
		$mCol = 0 === $metas['mCols'] ? 1 : $metas['mCols'];

		$dCol = 5 === $dCol ? '24' : round( 12 / $dCol );
		$tCol = 5 === $dCol ? '24' : round( 12 / $tCol );
		$mCol = 5 === $dCol ? '24' : round( 12 / $mCol );

		if ( $isCarousel ) {
			$dCol = $tCol = $mCol = 12;
		}

		$arg['grid']   = 'fmp-col-lg-' . $dCol . ' fmp-col-md-' . $dCol . ' fmp-col-sm-' . $tCol . ' fmp-col-xs-' . $mCol . ' ';
		$arg['class'] .= ' ' . $metas['gridType'] . '-grid-item ';

		$arg['class'] .= 'fmp-grid-item';

		if ( ! $isIsotope ) {
			$arg['class'] .= ' fmp-ready-animation animated fadeIn';
		}

		if ( $isCarousel ) {
			$arg['class'] .= ' swiper-slide';
		}

		if ( ! $link ) {
			$arg['link']        = false;
			$arg['anchorClass'] = ' fmp-disable';
		} else {
			$arg['link'] = true;
		}

		$arg['target'] = '_self';

		if ( $link && '_blank' === $metas['target'] ) {
			$arg['target'] = '_blank';
		}

		$arg['items'] = ! empty( $metas['items'] ) ? $metas['items'] : [];

		if ( ! empty( $arg['items'] ) && apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
			$arg['items'] = unserialize( $arg['items'] );
		}

		$arg['wc'] = class_exists( 'WooCommerce' ) ? true : false;

		$source          = get_post_meta( $iD, 'fmp_source', true );
		$post_type       = ( $source && in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
		$arg['taxonomy'] = ( 'product' === $post_type ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];
		$arg['source']   = $post_type;

		$arg['lazyLoad'] = false;

		if ( $isIsotope ) {
			$arg['class'] .= ' fmp-isotope-item';
		}

		if ( $isCarousel ) {
			$cOpt = ! empty( $scMeta['fmp_carousel_options'] ) ? $scMeta['fmp_carousel_options'] : [];
			$cOpt = ! empty( $scMeta['fmp_carousel_options'] ) ? $scMeta['fmp_carousel_options'] : [];

			$arg['class']   .= ' fmp-carousel-item';
			$arg['lazyLoad'] = ( in_array( 'lazy_load', $carouselOpts, true ) ? true : false );
		}

		// if ( 'circle' === $metas['imageShape'] ) {
		// 	$arg['class'] .= ' fmp-img-circle';
		// }

		if ( 'no' === $metas['margin'] ) {
			$arg['class'] .= ' no-margin';
		}

		if ( $metas['link'] && 'popup' === $metas['linkType'] ) {
			$arg['anchorClass'] .= ' fmp-popup';
		}

		return $arg;
	}

	public static function defaultColumns( $layout ) {
		$columns = 2;

		switch ( $layout ) {
			case 'layout1':
				$columns = 3;
				break;

			case 'layout3':
				$columns = 4;
				break;

			case 'layout5':
				$columns = 1;
				break;

			case 'layout6':
				$columns = 3;
				break;

			case 'layout8':
				$columns = 4;
				break;

			case 'carousel1':
				$columns = 3;
				break;

			case 'carousel2':
				$columns = 2;
				break;

			case 'carousel3':
				$columns = 4;
				break;

			case 'isotope1':
				$columns = 3;
				break;

			case 'isotope3':
				$columns = 4;
				break;
		}

		return $columns;
	}

	private static function loopArgBuilder( array $arg, array $meta, array $scMeta, int $postID, bool $lazyLoad = false ) {
		if ( empty( $meta ) && empty( $arg ) && ! $postID ) {
			return [];
		}

		$isIsotope = preg_match( '/isotope/', $meta['layout'] );

		$source           = $meta['source'];
		$post_type        = ( in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
		$categoryTaxonomy = ( 'product' === $post_type ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];

		$arg['sLink'] = [];

		$arg['pID']     = $postID;
		$arg['title']   = get_the_title();
		$arg['pLink']   = get_permalink();
		$arg['excerpt'] = RenderHelpers::getExcerpt( get_the_excerpt(), $meta['excerpt_limit'], $meta['after_short_desc'] );
		$arg['imgSize'] = $meta['imgSize'];

		if ( $isIsotope ) {
			$termAs = wp_get_post_terms(
				$postID,
				$categoryTaxonomy,
				[ 'fields' => 'all' ]
			);

			$isoFilter = null;

			if ( ! empty( $termAs ) ) {
				foreach ( $termAs as $term ) {
					$isoFilter .= ' iso_' . $term->term_id;
					$isoFilter .= ' ' . $term->slug;
				}
			}

			$arg['isoFilter'] = $isoFilter;
		}

		$image_shape = ! empty( $scMeta['fmp_image_shape'] ) ? $scMeta['fmp_image_shape'] : null;

		// if ( $image_shape == 'circle' ) {
		// 	$arg['class'] .= ' fmp-img-circle';
		// }

		$layout = ( ! empty( $scMeta['fmp_layout'] ) ? $scMeta['fmp_layout'] : 'layout-free' );

		if ( ! in_array( $layout, array_keys( Options::scLayout() ) ) ) {
			$layout = 'layout-free';
		}

		$isCarousel = preg_match( '/carousel/', $layout );
		$isIsotope  = preg_match( '/isotope/', $layout );

		if ( $isCarousel ) {
			$arg['class'] .= ' fmp-carousel-item';
		}

		if ( $isIsotope ) {
			$arg['class'] .= ' fmp-isotope-item';
		}

		$margin = ! empty( $scMeta['fmp_margin'] ) ? $scMeta['fmp_margin'] : 'default';

		if ( $margin == 'no' ) {
			$arg['class'] .= ' no-margin';
		}

		$cImageSize = $scMeta['fmp_custom_image_size'];

		$arg['customImgSize']    = ! empty( $cImageSize ) ? $cImageSize : null;
		$arg['defaultImgId']     = ( ! empty( $scMeta['fmp_placeholder_image'] ) ? absint( $scMeta['fmp_placeholder_image'] ) : null );
		$arg['read_more']        = ! empty( $scMeta['fmp_read_more_button_text'] ) ? esc_attr( $scMeta['fmp_read_more_button_text'] ) : esc_html__( 'Read More', 'tlp-food-menu' );
		$arg['add_to_cart_text'] = ! empty( $scMeta['fmp_add_to_cart_text'] ) ? esc_attr( $scMeta['fmp_add_to_cart_text'] ) : esc_html__( 'Add to cart', 'tlp-food-menu' );
		$popup                   = ! empty( $scMeta['fmp_single_food_popup'] ) ? true : false;
		$arg['popup']            = $popup ? true : false;

		if ( $arg['link'] && $popup ) {
			$arg['anchorClass'] = ' fmp-popup';
		}

		return $arg;
	}
}
