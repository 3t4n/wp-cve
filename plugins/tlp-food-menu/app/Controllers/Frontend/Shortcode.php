<?php
/**
 * Frontend Shortcode Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Frontend;

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
 * Frontend Shortcode Class.
 */
class Shortcode {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * SCID
	 *
	 * @var integer
	 */
	private $scId;

	/**
	 * SC Scripts
	 *
	 * @var array
	 */
	private $scA = [];

	/**
	 * Class init.
	 *
	 * @return void
	 */
	protected function init() {
		add_shortcode( 'foodmenu', [ $this, 'render' ] );
		add_shortcode( 'rt-foodmenu', [ $this, 'render' ] );
		add_shortcode( 'foodmenu-single', [ $this, 'renderSingle' ] );
	}

	/**
	 * Renders Shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return mixed
	 */
	public function render( $atts ) {
		$scID = isset( $atts['id'] ) ? absint( $atts['id'] ) : null;

		if ( ! $scID && is_null( get_post( $scID ) ) ) {
			return;
		}

		if ( 0 === $scID ) {
			return;
		}

		$this->scId    = $scID;
		$scMeta        = get_post_meta( $scID );
		$rand          = absint( wp_rand() );
		$layoutID      = 'fmp-container-' . $rand;
		$html          = null;
		$containerAttr = null;
		$masonryG      = null;
		$preLoader     = null;
		$preLoaderHtml = null;
		$args          = [];
		$arg           = [];
		$itemsOnMobile = '';

		$metas = RenderHelpers::metaScBuilder( $scMeta );
		$arg   = RenderHelpers::argBuilder( $scID, $metas, $scMeta );

		$layout        = $metas['layout'];
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
			$mobileItems = unserialize( $mobileItems[0] );
		}

		if ( ! empty( $mobileItems ) && is_array( $mobileItems ) ) {
			foreach ( $mobileItems as $mobileItem ) {
				$itemsOnMobile .= ' has-mobile-' . $mobileItem;
			}
		}

		if ( ! in_array( $layout, array_keys( Options::scLayouts() ), true ) ) {
			$layout = 'layout-free';
		}

		$isIsotope  = preg_match( '/isotope/', $layout );
		$isCarousel = preg_match( '/carousel/', $layout );
		$isCat      = preg_match( '/grid-by-cat/', $layout );

		$dCol = 0 === $metas['dCols'] ? RenderHelpers::defaultColumns( $layout ) : $metas['dCols'];
		$tCol = 0 === $metas['tCols'] ? 2 : $metas['tCols'];
		$mCol = 0 === $metas['mCols'] ? 1 : $metas['mCols'];

		$containerAttr .= 'data-sc-id="' . absint( $scID ) . '" data-layout="' . esc_attr( $layout ) . '" data-desktop-col="' . absint( $dCol ) . '" data-tab-col="' . absint( $tCol ) . '" data-mobile-col="' . absint( $mCol ) . '"';

		if ( 'even' === $gridType ) {
			$masonryG = ' fmp-even';
		} elseif ( 'masonry' === $gridType ) {
			$masonryG = ' fmp-masonry';
		}

		if ( $isCarousel ) {
			$masonryG = ' fmp-even';
		}

		$settings        = get_option( TLPFoodMenu()->options['settings'] );
		$enablePreloader = isset( $settings['fmp_preloader'] ) ? $settings['fmp_preloader'] : true;

		if ( $enablePreloader ) {
			$preLoader     = ' fmp-pre-loader';
			$preLoaderHtml = '<div class="fmp-loading-overlay"></div><div class="fmp-loading fmp-ball-clip-rotate"><div></div></div>';

			if ( $isCarousel || $isIsotope ) {
				$preLoaderHtml = '<div class="fmp-loading-overlay full-op"></div><div class="fmp-loading fmp-ball-clip-rotate"><div></div></div>';
			}
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

		$rowClass = apply_filters( 'rt_fm_row_class', $rowClass, $metas );

		$html .= RenderHelpers::layoutStyle( $layoutID, $scMeta );
		$html .= '<div class="' . esc_attr( $containerClass ) . ' ' . esc_attr( $parentClass ) . '" id="' . esc_attr( $layoutID ) . '" ' . $containerAttr . '>';

		$args = ( new QueryArgs() )->buildArgs( $scID, $metas, $isCarousel );

		if ( $isCat ) {
			$html .= '<div data-title="' . esc_html__( 'Loading ...', 'tlp-food-menu' ) . '" class="' . esc_attr( $rowClass ) . '">';
			$html .= $this->renderCategoryLayouts( $metas, $scMeta, $args, $arg );
			$html .= $preLoaderHtml;
			$html .= '</div>';
		} else {
			$fmpQuery = new WP_Query( $args );

			if ( $fmpQuery->have_posts() ) {
				$html .= '<div data-title="' . esc_html__( 'Loading ...', 'tlp-food-menu' ) . '" class="' . esc_attr( $rowClass ) . '">';

				ob_start();
				do_action( 'rt_fm_sc_before_loop', $scMeta, $rand );
				$html .= ob_get_contents();
				ob_end_clean();

				while ( $fmpQuery->have_posts() ) {
					$fmpQuery->the_post();

					// Loop Args.
					$arg = RenderHelpers::loopArgBuilder( $arg, $metas, $scMeta, get_the_ID() );

					// Render Layout.
					$html .= Fns::render( 'layouts/' . $layout, $arg, true );
				}

				ob_start();
				do_action( 'rt_fm_sc_after_loop', $scMeta );
				$html .= ob_get_contents();
				ob_end_clean();

				$html .= $preLoaderHtml;
				$html .= '</div>';

				if ( $pagination && ! $isCarousel ) {
					$renderPagination = RenderHelpers::renderPagination(
						$fmpQuery,
						$metas,
						$scMeta['fmp_limit'][0],
						$scMeta['fmp_posts_per_page'][0],
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

		$html .= '</div>';

		$isIsotope                        = ' fmp-masonry' === $masonryG ? true : $isIsotope;
		$scriptGenerator                  = [];
		$scriptGenerator['scId']          = $scID;
		$scriptGenerator['layout']        = $layoutID;
		$scriptGenerator['rand']          = $rand;
		$scriptGenerator['isIsotope']     = $isIsotope;
		$scriptGenerator['isCarousel']    = $isCarousel;
		$scriptGenerator['hasPagination'] = $pagination;
		$scriptGenerator['hasModal']      = $hasModal;

		add_action(
			'wp_footer',
			static function () use ( $scriptGenerator ) {
				RenderHelpers::registerScripts( $scriptGenerator );
			}
		);

		return $html;
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

		$source           = get_post_meta( $this->scId, 'fmp_source', true );
		$post_type        = ( $source && in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
		$categoryTaxonomy = ( 'product' === $post_type ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];

		if ( ! empty( $cats ) && apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
			$cats = unserialize( $cats[0] );
		}

		$catVar['hide_empty'] = true;

		if ( ! empty( $cats ) ) {
			$catVar['include'] = $cats;
		}

		if ( function_exists( 'get_term_meta' ) ) {
			$catVar['taxonomy']   = $categoryTaxonomy;

			if ( TLPFoodMenu()->has_pro() ) {
				if ( 'product' !== $categoryTaxonomy ) {
					$catVar['orderby']    = 'meta_value_num';
				}

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
			}

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
					$customImg  = isset( $scMeta['fmp_custom_image_size'] ) && ! empty( $scMeta['fmp_custom_image_size'][0] ) ? unserialize( $scMeta['fmp_custom_image_size'][0] ) : null;
					$defaultImg = isset( $scMeta['fmp_placeholder_image'] ) && ! empty( $scMeta['fmp_placeholder_image'][0] ) ? absint( $scMeta['fmp_placeholder_image'][0] ) : null;
					$cartText   = isset( $scMeta['fmp_add_to_cart_text'] ) && ! empty( $scMeta['fmp_add_to_cart_text'][0] ) ? esc_attr( $scMeta['fmp_add_to_cart_text'][0] ) : null;
					$popup      = isset( $scMeta['fmp_single_food_popup'] ) && ! empty( $scMeta['fmp_single_food_popup'][0] ) ? true : false;

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

	/**
	 * Single Page Shortcode
	 *
	 * @param array $atts Attributes.
	 * @return mixed
	 */
	public function renderSingle( $atts ) {
		$html = null;

		$atts = shortcode_atts(
			[
				'id' => null,
			],
			$atts,
			'foodmenu-single'
		);

		return $html;
	}
}
