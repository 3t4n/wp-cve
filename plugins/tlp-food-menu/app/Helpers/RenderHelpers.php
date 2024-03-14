<?php
/**
 * Helpers class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Helpers;

use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Helpers class.
 */
class RenderHelpers {

	/**
	 * Registers required scripts.
	 *
	 * @param array $scripts Scripts to register.
	 * @return void
	 */
	public static function registerScripts( $scripts ) {
		$iso    = false;
		$caro   = false;
		$pagi   = false;
		$scroll = false;
		$script = [];
		$style  = [];
		$scId   = $scripts['scId'];

		array_push( $script, 'jquery' );

		foreach ( $scripts as $sc => $value ) {
			if ( ! empty( $sc ) ) {
				if ( 'isIsotope' === $sc ) {
					$iso = $value;
				}

				if ( 'isCarousel' === $sc ) {
					$caro = $value;
				}

				if ( 'hasPagination' === $sc ) {
					$pagi = $value;
				}

				if ( 'hasModal' === $sc ) {
					$scroll = $value;
				}
			}
		}

		if ( count( $scripts ) ) {
			$style  = apply_filters( 'rtfm_styles_list', $style );
			$script = apply_filters( 'rtfm_scripts_list', $script );

			/**
			 * Styles.
			 */
			array_push( $style, 'fm-frontend' );

			/**
			 * Scripts.
			 */
			array_push( $script, 'fm-frontend' );

			wp_enqueue_style( $style );
			wp_enqueue_script( $script );

			$nonce   = wp_create_nonce( Fns::nonceText() );
			$ajaxurl = '';

			if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
				$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
			} else {
				$ajaxurl .= admin_url( 'admin-ajax.php' );
			}

			wp_localize_script(
				'fm-frontend',
				'fmp',
				[
					'ajaxurl'     => esc_url( $ajaxurl ),
					'nonceID'     => esc_attr( Fns::nonceID() ),
					'nonce'       => esc_attr( $nonce ),
					'hasPro'      => TLPFoodMenu()->has_pro() ? 'true' : 'false',
					'wc_cart_url' => TLPFoodMenu()->isWcActive() ? wc_get_cart_url() : '',
				]
			);
		}

	}

	/**
	 * Default layout columns.
	 *
	 * @param int $layout Layout.
	 *
	 * @return int
	 */
	public static function defaultColumns( $layout ) {
		$columns = 2;

		switch ( $layout ) {
			case 'layout-free':
				$columns = 2;
				break;

			default:
				$columns = 2;
				break;
		}

		return apply_filters( 'rtfm_layout_default_columns', $columns, $layout );
	}

	/**
	 * Default Category Title Type.
	 *
	 * @param int $layout Layout.
	 *
	 * @return int
	 */
	public static function defaultCatTitle( $layout ) {
		$type = 3;

		switch ( $layout ) {
			case 'grid-by-cat-free':
				$type = 'type-1';
				break;

			case 'grid-by-cat-free-2':
				$type = 'type-3';
				break;

			case 'grid-by-cat-free-3':
				$type = 'type-2';
				break;

			case 'grid-by-cat-free-4':
				$type = 'type-1';
				break;

			case 'grid-by-cat-free-5':
				$type = 'type-1';
				break;

			default:
				$type = 'type-1';
				break;
		}

		return $type;
	}

	/**
	 * Default Category Title Type.
	 *
	 * @param int $layout Layout.
	 *
	 * @return int
	 */
	public static function defaultIsoButtons( $layout ) {
		$type = 'type-1';

		switch ( $layout ) {
			case 'isotope1':
				$type = 'type-1';
				break;

			case 'isotope2':
				$type = 'type-2';
				break;

			case 'isotope3':
				$type = 'type-3';
				break;

			case 'isotope4':
				$type = 'type-1';
				break;

			default:
				$type = 'type-1';
				break;
		}

		return $type;
	}

	/**
	 * Builds an array with field values (for shortcode).
	 *
	 * @param array $meta Field values.
	 * @return array
	 */
	public static function metaScBuilder( $meta ) {
		$metas = [
			// Layout.
			'layout'             => ! empty( $meta['fmp_layout'][0] ) ? esc_attr( $meta['fmp_layout'][0] ) : 'layout-free',
			'gridType'           => ! empty( $meta['fmp_grid_style'][0] ) ? esc_html( $meta['fmp_grid_style'][0] ) : 'even',

			// Columns.
			'dCols'              => ! empty( $meta['fmp_desktop_column'][0] ) ? absint( $meta['fmp_desktop_column'][0] ) : 0,
			'tCols'              => ! empty( $meta['fmp_tab_column'][0] ) ? absint( $meta['fmp_tab_column'][0] ) : 0,
			'mCols'              => ! empty( $meta['fmp_mobile_column'][0] ) ? absint( $meta['fmp_mobile_column'][0] ) : 0,

			// Image.
			'imgSize'            => isset( $meta['fmp_image_size'][0] ) ? $meta['fmp_image_size'][0] : 'medium',
			'borderRadius'       => isset( $meta['fmp_image_radius'][0] ) ? $meta['fmp_image_radius'][0] : 'default',
			'imageShape'         => isset( $meta['fmp_image_shape'][0] ) ? $meta['fmp_image_shape'][0] : 'normal',
			'imagePosition'      => isset( $meta['fmp_image_position'][0] ) ? $meta['fmp_image_position'][0] : 'top',
			'hoverIcon'          => ! empty( $meta['fmp_hover_icon'][0] ) ? $meta['fmp_hover_icon'][0] : 0,

			// Excerpt.
			'excerpt_limit'      => isset( $meta['fmp_excerpt_limit'][0] ) ? absint( $meta['fmp_excerpt_limit'][0] ) : 0,
			'after_short_desc'   => ! empty( $meta['fmp_excerpt_custom_text'][0] ) ? esc_html( $meta['fmp_excerpt_custom_text'][0] ) : '',

			// Details Page.
			'link'               => ! empty( $meta['fmp_detail_page_link'][0] ) ? $meta['fmp_detail_page_link'][0] : 0,
			'target'             => ! empty( $meta['fmp_detail_page_target'][0] ) ? esc_attr( $meta['fmp_detail_page_target'][0] ) : '_self',

			// Filters.
			'postIn'             => ! empty( $meta['fmp_post__in'][0] ) ? $meta['fmp_post__in'][0] : null,
			'postNotIn'          => ! empty( $meta['fmp_post__not_in'][0] ) ? $meta['fmp_post__not_in'][0] : null,
			'limit'              => ( ( empty( $meta['fmp_limit'][0] ) || $meta['fmp_limit'][0] === '-1' ) ? 10000000 : absint( $meta['fmp_limit'][0] ) ),
			'source'             => ! empty( $meta['fmp_source'] ) ? $meta['fmp_source'] : TLPFoodMenu()->post_type,

			// Categories.
			'cats'               => ( isset( $meta['fmp_categories'] ) ? array_filter( $meta['fmp_categories'] ) : [] ),
			'cats_title_type'    => ! empty( $meta['fmp_category_title_type'][0] ) ? esc_attr( $meta['fmp_category_title_type'][0] ) : 'default',

			// Sorting.
			'order_by'           => isset( $meta['fmp_order_by'][0] ) ? $meta['fmp_order_by'][0] : null,
			'order'              => isset( $meta['fmp_order'][0] ) ? $meta['fmp_order'][0] : null,

			// Pagination.
			'pagination'         => ! empty( $meta['fmp_pagination'][0] ) ? true : false,
			'posts_loading_type' => ! empty( $meta['fmp_pagination_type'][0] ) ? $meta['fmp_pagination_type'][0] : 'pagination',
			'postsPerPage'       => isset( $meta['fmp_posts_per_page'][0] ) ? absint( $meta['fmp_posts_per_page'][0] ) : '',

			// Visibility.
			'items'              => ! empty( $meta['fmp_item_fields'] ) ? $meta['fmp_item_fields'] : [],
			'mobileItems'        => ! empty( $meta['fmp_mobile_item_fields'] ) ? $meta['fmp_mobile_item_fields'] : [],

			// Wrapper Class.
			'parentClass'        => ! empty( $meta['fmp_parent_class'][0] ) ? trim( $meta['fmp_parent_class'][0] ) : null,
		];

		return apply_filters( 'rtfm_meta_sc_builder', $metas, $meta );
	}


	/**
	 * Builds an array with field values.
	 *
	 * @param array $meta Field values.
	 * @return array
	 */
	public static function metaBuilder( $meta ) {
		$metas = [
			// Layout.
			'layout'             => ! empty( $meta['fmp_layout'] ) ? esc_attr( $meta['fmp_layout'] ) : 'layout-free',
			'gridType'           => ! empty( $meta['fmp_grid_style'] ) ? esc_html( $meta['fmp_grid_style'] ) : 'even',

			// Columns.
			'dCols'              => ! empty( $meta['fmp_desktop_column'] ) ? absint( $meta['fmp_desktop_column'] ) : 0,
			'tCols'              => ! empty( $meta['fmp_tab_column'] ) ? absint( $meta['fmp_tab_column'] ) : 0,
			'mCols'              => ! empty( $meta['fmp_mobile_column'] ) ? absint( $meta['fmp_mobile_column'] ) : 0,

			// Image.
			'imgSize'            => isset( $meta['fmp_image_size'] ) ? $meta['fmp_image_size'] : 'medium',
			'borderRadius'       => isset( $meta['fmp_image_radius'] ) ? $meta['fmp_image_radius'] : 'default',
			'imageShape'         => isset( $meta['fmp_image_shape'] ) ? $meta['fmp_image_shape'] : 'normal',
			'imagePosition'      => isset( $meta['fmp_image_position'] ) ? $meta['fmp_image_position'] : 'top',

			// Excerpt.
			'excerpt_limit'      => isset( $meta['fmp_excerpt_limit'] ) ? absint( $meta['fmp_excerpt_limit'] ) : 0,
			'after_short_desc'   => ! empty( $meta['fmp_excerpt_custom_text'] ) ? esc_html( $meta['fmp_excerpt_custom_text'] ) : '',

			// Details Page.
			'link'               => ! empty( $meta['fmp_detail_page_link'] ) ? $meta['fmp_detail_page_link'] : 0,
			'target'             => ! empty( $meta['fmp_detail_page_target'] ) ? esc_attr( $meta['fmp_detail_page_target'] ) : '_self',

			// Filters.
			'postIn'             => ! empty( $meta['fmp_post__in'] ) ? $meta['fmp_post__in'] : null,
			'postNotIn'          => ! empty( $meta['fmp_post__not_in'] ) ? $meta['fmp_post__not_in'] : null,
			'limit'              => ( ( empty( $meta['fmp_limit'] ) || $meta['fmp_limit'] === '-1' ) ? 10000000 : absint( $meta['fmp_limit'] ) ),

			// Categories.
			'cats'               => ( isset( $meta['fmp_categories'] ) ? array_filter( $meta['fmp_categories'] ) : [] ),
			'cats_title_type'    => ! empty( $meta['fmp_category_title_type'] ) ? esc_attr( $meta['fmp_category_title_type'] ) : 'default',

			// Sorting.
			'order_by'           => isset( $meta['fmp_order_by'] ) ? $meta['fmp_order_by'] : null,
			'order'              => isset( $meta['fmp_order'] ) ? $meta['fmp_order'] : null,

			// Pagination.
			'pagination'         => ! empty( $meta['fmp_pagination'] ) ? true : false,
			'posts_loading_type' => ! empty( $meta['fmp_pagination_type'] ) ? $meta['fmp_pagination_type'] : 'pagination',
			'postsPerPage'       => isset( $meta['fmp_posts_per_page'] ) ? absint( $meta['fmp_posts_per_page'] ) : '',

			// Visibility.
			'items'              => ! empty( $meta['fmp_item_fields'] ) ? $meta['fmp_item_fields'] : [],
			'mobileItems'        => ! empty( $meta['fmp_mobile_item_fields'] ) ? $meta['fmp_mobile_item_fields'] : [],

			// Wrapper Class.
			'parentClass'        => ! empty( $meta['fmp_parent_class'] ) ? trim( $meta['fmp_parent_class'] ) : null,
		];

		return apply_filters( 'rtfm_meta_builder', $metas, $meta );
	}

	/**
	 * Builds an array with field values.
	 *
	 * @param string $iD SC ID.
	 * @param array  $metas Field values.
	 * @param array  $scMeta SC Field values.
	 * @return array
	 */
	public static function argBuilder( $iD, $metas, $scMeta ) {
		if ( empty( $metas ) ) {
			return [];
		}

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
			$arg['items'] = unserialize( $arg['items'][0] );
		}

		$arg['wc'] = class_exists( 'WooCommerce' ) ? true : false;

		$source          = get_post_meta( $iD, 'fmp_source', true );
		$post_type       = ( $source && in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
		$arg['taxonomy'] = ( 'product' === $post_type ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];
		$arg['source']   = $post_type;

		return apply_filters( 'rtfm_arg_builder', $arg, $metas, $scMeta );
	}

	/**
	 * Builds an array with meta values.
	 *
	 * @param array $arg Arg values.
	 * @param array $meta Meta values.
	 * @param int   $postID Post ID.
	 * @param bool  $lazyLoad Image lazy load.
	 * @return array
	 */
	public static function loopArgBuilder( array $arg, array $meta, array $scMeta, int $postID, bool $lazyLoad = false ) {
		if ( empty( $meta ) && empty( $arg ) && ! $postID ) {
			return [];
		}

		$isIsotope = preg_match( '/isotope/', $meta['layout'] );

		$source           = $meta['source'];
		$post_type        = ( in_array( $source[0], array_keys( Options::scProductSource() ), true ) ) ? $source[0] : TLPFoodMenu()->post_type;
		$categoryTaxonomy = ( 'product' === $post_type ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];

		$arg['sLink'] = [];

		$arg['pID']     = $postID;
		$arg['title']   = get_the_title();
		$arg['pLink']   = get_permalink();
		$arg['excerpt'] = self::getExcerpt( get_the_excerpt(), $meta['excerpt_limit'], $meta['after_short_desc'] );
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

		return apply_filters( 'rt_fm_shortcode_data', $arg, $meta, $scMeta, $postID );
	}

	/**
	 * Gets the excerpt
	 *
	 * @param string $excerpt Excerpt.
	 * @param int    $characterLimit Character Limit.
	 * @param string $afterText Text after excerpt.
	 * @return string
	 */
	public static function getExcerpt( $excerpt, $characterLimit, $afterText ) {
		if ( empty( $characterLimit ) ) {
			return $excerpt;
		}

		$characterLimit++;

		$text = '';

		if ( mb_strlen( $excerpt ) > $characterLimit ) {
			$subex   = mb_substr( wp_strip_all_tags( $excerpt ), 0, $characterLimit );
			$exwords = explode( ' ', $subex );
			$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

			if ( $excut < 0 ) {
				$text .= mb_substr( $subex, 0, $excut );
			} else {
				$text .= $subex;
			}
		} else {
			$text .= $excerpt;
		}

		$text = $text . esc_html( $afterText );

		return $text;
	}

	/**
	 * Layout CSS
	 *
	 * @param string $ID Layout ID.
	 * @param array  $scMeta Shortcode Meta.
	 * @return string
	 */
	public static function layoutStyle( $ID, $scMeta ) {
		$css  = null;
		$css .= "<style type='text/css' media='all'>";

		// Title
		$title = ( ! empty( $scMeta['fmp_title_style'][0] ) ? unserialize( $scMeta['fmp_title_style'][0] ) : [] );

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

		// Price
		$price = ( ! empty( $scMeta['fmp_price_style'][0] ) ? unserialize( $scMeta['fmp_price_style'][0] ) : [] );

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
		$btnBg  = ( ! empty( $scMeta['fmp_button_bg_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_bg_color'][0] ) : null );
		$btnBg2 = ( ! empty( $scMeta['fmp_button_bg_color_2'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_bg_color_2'][0] ) : null );

		if ( empty( $btnBg2 ) ) {
			$css .= "#{$ID} a.fmp-btn-read-more::before,
			#{$ID} a.fmp-wc-add-to-cart-btn::before,
			#{$ID} .fmp-utility .fmp-load-more button::before,
			#{$ID} .fmp-pagination ul.pagination-list li span::before,
			#{$ID} .fmp-load-more::before,
			#{$ID} .fmp-iso-filter.type-1 button,
			#{$ID} .fmp-iso-filter.type-2 button,
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
			#{$ID} .fmp-utility .fmp-load-more button::before,
			#{$ID} .fmp-pagination ul.pagination-list li span::before,
			#{$ID} .fmp-load-more::before,
			#{$ID} .fmp-layout5 .fmp-price,
			#{$ID} .fmp-layout5 .fmp-price-box .fmp-attr-variation-wrapper,
			#{$ID} .fmp-iso-filter.type-1 button,
			#{$ID} .fmp-iso-filter.type-2 button,
			#{$ID} .fmp-carousel .swiper-arrow::before,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button + .added_to_cart,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::before {";
			$css .= "background: linear-gradient(94.5deg, $btnBg 16.12%, $btnBg2 58.97%);";
			$css .= '}';

			$css .= "#{$ID} .fmp-layout5 .fmp-price,
			#{$ID} .fmp-layout5 .fmp-price-box .fmp-attr-variation-wrapper,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button + .added_to_cart {";
			$css .= "background: $btnBg;";
			$css .= '}';
		}

		if ( $btnBg ) {
			$css .= "#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn,
			#{$ID}.fmp-wrapper[id*=fmp-container-] .fmp-btn-read-more.type-2::before,
			#{$ID} .fmp-layout5 .fmp-price-box .quantity .input-text.qty.text {";
			$css .= 'border-color:' . $btnBg . ';';
			$css .= '}';

			$css .= "#{$ID} .fmp-iso-filter.type-1 button::after {";
			$css .= 'border-top-color:' . $btnBg . ';';
			$css .= '}';
		}

		// button text color.
		$btnText = ( ! empty( $scMeta['fmp_button_text_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_text_color'][0] ) : null );
		if ( $btnText ) {
			$css .= "#{$ID} a.fmp-btn-read-more,
			#{$ID} a.fmp-wc-add-to-cart-btn,
			#{$ID} .fmp-utility .fmp-load-more button,
			#{$ID} .fmp-load-more::before,
			#{$ID} .fmp-iso-filter button,
			#{$ID} .fmp-carousel .swiper-arrow,
			#{$ID} .fmp-pagination ul.pagination-list li a,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn,
			#{$ID} .fmp-food-item .button {";
			$css .= 'color:' . $btnText . ';';
			$css .= '}';
		}

		// Button hover bg color.
		$btnHbg  = ( ! empty( $scMeta['fmp_button_hover_bg_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_hover_bg_color'][0] ) : null );
		$btnHbg2 = ( ! empty( $scMeta['fmp_button_hover_bg_color_2'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_hover_bg_color_2'][0] ) : null );

		if ( empty( $btnHbg2 ) ) {
			$css .= "#{$ID} a.fmp-btn-read-more::after,
			#{$ID} a.fmp-wc-add-to-cart-btn::after,
			#{$ID} .fmp-utility .fmp-load-more button::after,
			#{$ID} .fmp-load-more::after,
			#{$ID} .fmp-carousel .swiper-arrow::after,
			#{$ID} .fmp-pagination ul.pagination-list li.active span::after,
			#{$ID} .fmp-pagination ul.pagination-list li a::after,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn::after,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::after {";
			$css .= 'background:' . $btnHbg . ';';
			$css .= '}';
		} elseif ( $btnHbg && $btnHbg2 ) {
			$css .= "#{$ID} a.fmp-btn-read-more::after,
			#{$ID} a.fmp-wc-add-to-cart-btn::after,
			#{$ID} .fmp-utility .fmp-load-more button::after,
			#{$ID} .fmp-load-more::after,
			#{$ID} .fmp-carousel .swiper-arrow::after,
			#{$ID} .fmp-pagination ul.pagination-list li.active span::after,
			#{$ID} .fmp-pagination ul.pagination-list li a::after,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn::after,
			#{$ID}.fmp-wrapper .fmp-food-item.product a.button::after {";
			$css .= "background: linear-gradient(94.5deg, $btnHbg 16.12%, $btnHbg2 58.97%);";
			$css .= '}';
		}

		if ( $btnHbg ) {
			$css .= "#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn:hover {";
			$css .= 'border-color:' . $btnHbg . ';';
			$css .= '}';
		}

		// Button hover text color.
		$btnHtext = ( ! empty( $scMeta['fmp_button_hover_text_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_hover_text_color'][0] ) : null );
		if ( $btnHtext ) {
			$css .= "#{$ID} a.fmp-btn-read-more:hover,
			#{$ID} a.fmp-wc-add-to-cart-btn:hover,
			#{$ID} .fmp-utility .fmp-load-more button:hover,
			#{$ID} .fmp-carousel .swiper-arrow:hover,
			#{$ID} .fmp-pagination ul.pagination-list li.active span,
			#{$ID} .fmp-pagination ul.pagination-list li a:hover,
			#{$ID} .fmp-layout5 .fmp-wc-add-to-cart-btn:hover,
			#{$ID} .fmp-food-item .button:hover {";
			$css .= 'color:' . $btnHtext . ';';
			$css .= '}';
		}

		// Button active bg color.
		$btnAbg  = ( ! empty( $scMeta['fmp_button_active_bg_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_active_bg_color'][0] ) : null );
		$btnAbg2 = ( ! empty( $scMeta['fmp_button_active_bg_color_2'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_active_bg_color_2'][0] ) : null );

		if ( empty( $btnAbg2 ) ) {
			$css .= "#{$ID} .fmp-isotope-buttons button::before,
			#{$ID} .fmp-isotope-buttons button.selected::after,
			#{$ID} .fmp-isotope-buttons button.selected,
			#{$ID} .fmp-isotope-buttons button:hover,
			#{$ID}.fmp-wrapper .fmp-carousel.swiper .swiper-pagination-bullet,
			#{$ID}.fmp-wrapper .fmp-carousel.swiper .swiper-pagination-bullet:hover,
			#{$ID} .fmp-isotope-buttons button::after {";
			$css .= 'background:' . $btnAbg . ';';
			$css .= '}';
		} elseif ( $btnAbg && $btnAbg2 ) {
			$css .= "#{$ID} .fmp-isotope-buttons button::before,
			#{$ID} .fmp-isotope-buttons button.selected::after,
			#{$ID} .fmp-isotope-buttons button.selected,
			#{$ID} .fmp-isotope-buttons button:hover,
			#{$ID}.fmp-wrapper .fmp-carousel.swiper .swiper-pagination-bullet,
			#{$ID}.fmp-wrapper .fmp-carousel.swiper .swiper-pagination-bullet:hover,
			#{$ID} .fmp-isotope-buttons button::after {";
			$css .= "background: linear-gradient(94.5deg, $btnAbg 16.12%, $btnAbg2 58.97%);";
			$css .= '}';
		}

		if ( $btnAbg ) {
			$css .= "#{$ID} .fmp-iso-filter.type-1 button::after {";
			$css .= 'border-top-color:' . $btnAbg . ';';
			$css .= '}';
		}

		// Button active text color.
		$btnAtext = ( ! empty( $scMeta['fmp_button_active_text_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_button_active_text_color'][0] ) : null );
		if ( $btnAtext ) {
			$css .= "#{$ID} .fmp-isotope-buttons button.selected {";
			$css .= 'color:' . $btnAtext . ';';
			$css .= '}';
		}

		// Button Typography.
		$btn_typo = ( ! empty( $scMeta['fmp_button_typo'][0] ) ? unserialize( $scMeta['fmp_button_typo'][0] ) : [] );

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
		$img_border_radius = ! empty( $scMeta['fmp_image_radius'][0] ) ? $scMeta['fmp_image_radius'][0] : null;

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
			$css .= "#{$ID} .fmp-layout2 .fmp-box .fmp-img-wrapper:before,";
			$css .= "#{$ID} [class*=fmp-grid-by-cat-free] .fmp-food-item .fmp-image-wrap { ";
			$css .= 'border-radius:' . esc_html( $img_border_radius );
			$css .= '}';
		}

		// Vertical Border.
		$center_border = ( ! empty( $scMeta['fmp_border_color'][0] ) ? Fns::sanitize_hex_color( $scMeta['fmp_border_color'][0] ) : [] );

		if ( $center_border ) {
			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-col-xs-12 > .fmp-row::after,";
			$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-col-xs-12 > .fmp-row::before {";
			$css .= 'background-color:' . $center_border . ';';
			$css .= '}';
		}

		// Category Banner.
		$categoryBanner = ( ! empty( $scMeta['fmp_category_style'][0] ) ? array_filter( unserialize( $scMeta['fmp_category_style'][0] ) ) : null );

		if ( ! empty( $categoryBanner ) ) {
			$bannerBgColor1  = ( ! empty( $categoryBanner['first_color'] ) ? $categoryBanner['first_color'] : null );
			$bannerBgColor2  = ( ! empty( $categoryBanner['second_color'] ) ? $categoryBanner['second_color'] : null );
			$bannerColor     = ( ! empty( $categoryBanner['text_color'] ) ? $categoryBanner['text_color'] : null );
			$bannerFont      = ( ! empty( $categoryBanner['size'] ) ? $categoryBanner['size'] : null );
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

				if ( 'center' === $bannerAlignment ) {
					$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper.type-2 .fmp-category-title {";
					$css .= '-webkit-box-pack: center;';
					$css .= '-ms-flex-pack: center;';
					$css .= 'justify-content: center;';
					$css .= '}';

					$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper.type-2 .fmp-category-title::before {";
					$css .= 'left: 50%;';
					$css .= '-webkit-transform: translateX(-50%);';
					$css .= 'transform: translateX(-50%);';
					$css .= '}';
				}

				if ( 'right' === $bannerAlignment ) {
					$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper.type-2 .fmp-category-title {";
					$css .= '-webkit-box-pack: end;';
					$css .= '-ms-flex-pack: end;';
					$css .= 'justify-content: flex-end;';
					$css .= '}';

					$css .= "#{$ID} [class*=grid-by-cat-free] .fmp-category-title-wrapper.type-2 .fmp-category-title::before {";
					$css .= 'left: auto;';
					$css .= 'right: 0;';
					$css .= '}';
				}
			}
		}

		// Element / Content Wrap.
		$contentWrap = ( ! empty( $scMeta['fmp_content_wrap'][0] ) ? unserialize( $scMeta['fmp_content_wrap'][0] ) : [] );

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
		$sectionWrap = ( ! empty( $scMeta['fmp_section_wrap'][0] ) ? unserialize( $scMeta['fmp_section_wrap'][0] ) : [] );

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

		ob_start();
		do_action( 'fmp_sc_custom_css', $ID, $scMeta );
		$css .= ob_get_clean();

		$css .= '</style>';

		return $css;
	}

	/**
	 * Renders pagination
	 *
	 * @param object $wpQuery WP_Query object.
	 * @param array  $meta Meta values.
	 * @param int    $limit Post limit.
	 * @param int    $perPage Posts per page.
	 * @return string
	 */
	public static function renderPagination( $wpQuery, $meta, $limit, $perPage, $scID, $type ) {
		$htmlUtility = null;
		$html        = null;
		$ajax        = false;
		$isIsotope   = preg_match( '/isotope/', $meta['layout'] );
		$isGrid      = preg_match( '/layout/', $meta['layout'] );
		$postPp      = $wpQuery->query_vars['posts_per_page'];
		$page        = $wpQuery->query_vars['paged'];
		$foundPosts  = $wpQuery->found_posts;
		$morePosts   = $foundPosts - ( $postPp * $page );
		$totalPage   = $wpQuery->max_num_pages;
		$foundPost   = $wpQuery->found_posts;

		if ( $limit && ( empty( $wpQuery->query['tax_query'] ) ) ) {
			$foundPost = $wpQuery->found_posts;

			if ( $perPage && $foundPost > $perPage ) {
				$foundPost = $limit;
				$totalPage = ceil( $foundPost / $perPage );
			}
		}

		if ( 'pagination' !== $type ) {
			$ajax = true;
		}

		$morePosts  = $foundPost - ( $postPp * $page );
		$foundPosts = $foundPost;
		$totalPage  = absint( $totalPage );
		$morePosts  = absint( $morePosts );

		$htmlUtility .= self::pagination( $totalPage, $postPp, $ajax, $scID );

		if ( $htmlUtility ) {
			$html .= '<div class="rt-pagination-wrap" data-total-pages="' . $totalPage . '" data-posts-per-page="' . $postPp . '" data-type="' . $meta['posts_loading_type'] . '">' . $htmlUtility . '</div>';
		}

		return $html;
	}

	public static function pagination( $pages = '', $range = 4, $ajax = false, $scID = '' ) {
		$html      = null;
		$showitems = ( $range * 2 ) + 1;

		global $paged;

		if ( is_front_page() ) {
			$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}

		if ( empty( $paged ) ) {
			$paged = 1;
		}

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;

			if ( ! $pages ) {
				$pages = 1;
			}
		}

		$ajaxClass = null;
		$dataAttr  = null;

		if ( $ajax ) {
			$ajaxClass = ' fmp-ajax';
			$dataAttr  = "data-sc-id='{$scID}' data-paged='1'";
		}

		if ( 1 != $pages ) {
			$html .= '<div class="fmp-pagination' . $ajaxClass . '" ' . $dataAttr . '>';
			$html .= '<ul class="pagination-list">';

			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				$html .= "<li><a data-paged='1' href='" . get_pagenum_link( 1 ) . "' aria-label='First'>&laquo;</a></li>";
			}

			if ( $paged > 1 && $showitems < $pages ) {
				$p     = $paged - 1;
				$html .= "<li><a data-paged='{$p}' href='" . get_pagenum_link( $p ) . "' aria-label='Previous'>&lsaquo;</a></li>";
			}

			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					$html .= ( $paged == $i ) ? '<li class="active"><span>' . $i . '</span></li>' : "<li><a data-paged='{$i}' href='" . get_pagenum_link( $i ) . "'>" . $i . '</a></li>';
				}
			}

			if ( $paged < $pages && $showitems < $pages ) {
				$p     = $paged + 1;
				$html .= "<li><a data-paged='{$p}' href=\"" . get_pagenum_link( $paged + 1 ) . "\"  aria-label='Next'>&rsaquo;</a></li>";
			}

			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				$html .= "<li><a data-paged='{$pages}' href='" . get_pagenum_link( $pages ) . "' aria-label='Last'>&raquo;</a></li>";
			}

			$html .= '</ul>';
			$html .= '</div>';
		}

		return $html;
	}
}
