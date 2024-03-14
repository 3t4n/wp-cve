<?php
/**
 * Helpers class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Helpers;

use RT\FoodMenu\Models\Fields;
use RT\FoodMenu\Models\ReSizer;
use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Helpers class.
 */
class Fns {

	/**
	 * Classes instatiation.
	 *
	 * @param array $classes Classes to init.
	 * @return void
	 */
	public static function instances( array $classes ) {
		if ( empty( $classes ) ) {
			return;
		}

		foreach ( $classes as $class ) {
			$class::get_instance();
		}
	}

	/**
	 * Nonce verify upon activity
	 *
	 * @return bool
	 */
	public static function verifyNonce() {
		$nonce     = isset( $_REQUEST[ self::nonceId() ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ self::nonceId() ] ) ) : null;
		$nonceText = self::nonceText();

		if ( ! wp_verify_nonce( $nonce, $nonceText ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Generate nonce text
	 *
	 * @return string
	 */
	public static function nonceText() {
		return 'fmp_nonce_secret';
	}

	/**
	 * Nonce Id generation
	 *
	 * @return string
	 */
	public static function nonceId() {
		return 'fmp_nonce';
	}

	/**
	 * Render.
	 *
	 * @param string  $template_name View name.
	 * @param array   $args View args.
	 * @param boolean $return View return.
	 * @return string|void
	 */
	public static function render( $template_name, $args = [], $return = false ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$template = [
			$template_name . '.php',
			"tlp-food-menu/{$template_name}.php",
			"food-menu-pro/{$template_name}.php",
		];

		$pro_path = TLPFoodMenu()->pro_templates_path() . $template_name . '.php';

		if ( locate_template( $template ) ) {
			$template_file = locate_template( $template );
		} elseif ( function_exists( 'FMP' ) && file_exists( $pro_path ) ) {
			$template_file = $pro_path;
		} else {
			$template_file = TLPFoodMenu()->templates_path() . $template_name . '.php';
		}

		if ( ! file_exists( $template_file ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $template_file ) ), '1.7.0' );

			return;
		}

		if ( $return ) {
			ob_start();
			include $template_file;

			return ob_get_clean();
		} else {
			include $template_file;
		}
	}

	/**
	 * Render view.
	 *
	 * @param string  $viewName View name.
	 * @param array   $args View args.
	 * @param boolean $return View return.
	 * @return string|void
	 */
	public static function renderView( $viewName, $args = [], $return = false ) {
		$viewName = str_replace( '.', '/', $viewName );

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$view_file = TLPFoodMenu()->plugin_path() . '/resources/' . $viewName . '.php';

		if ( ! file_exists( $view_file ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $view_file ) ), '1.7.0' );

			return;
		}

		if ( $return ) {
			ob_start();
			include $view_file;

			return ob_get_clean();
		} else {
			include $view_file;
		}
	}

	/**
	 * Decimal Formatting.
	 *
	 * @param string  $number Number.
	 * @param boolean $dp DP.
	 * @param boolean $trim_zeros Trim zero.
	 * @return string
	 */
	public static function format_decimal( $number, $dp = false, $trim_zeros = false ) {
		$locale   = localeconv();
		$decimals = [
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
		];

		if ( $dp !== false ) {
			$dp     = intval( $dp == '' ? self::get_price_decimals() : $dp );
			$number = number_format( floatval( $number ), $dp, '.', '' );
		}

		if ( $trim_zeros && strstr( $number, '.' ) ) {
			$number = rtrim( rtrim( $number, '0' ), '.' );
		}

		return $number;
	}

	/**
	 * Decimal price seperator.
	 *
	 * @return int
	 */
	public static function get_price_decimal_separator() {
		$settings  = get_option( TLPFoodMenu()->options['settings'] );
		$separator = ! empty( $settings['price_decimal_sep'] ) ? stripslashes( $settings['price_decimal_sep'] ) : null;

		return $separator ? $separator : '.';
	}

	/**
	 * Decimal price.
	 *
	 * @return int
	 */
	public static function get_price_decimals() {
		$settings = get_option( TLPFoodMenu()->options['settings'] );

		return ( ! empty( $settings['price_num_decimals'] ) ? ( absint( $settings['price_num_decimals'] ) > 0 ? absint( $settings['price_num_decimals'] ) : 2 ) : 2 );
	}

	/**
	 * This function will generate meta or setting field
	 *
	 * @param array $fields Fields.
	 * @return null|string
	 */
	public static function rtFieldGenerator( $fields = [] ) {
		$html = null;

		if ( is_array( $fields ) && ! empty( $fields ) ) {
			$fmField = new Fields();

			foreach ( $fields as $fieldKey => $field ) {
				$html .= $fmField->Field( $fieldKey, $field );
			}
		}

		return $html;
	}

	/**
	 * MetaField list for food Page
	 *
	 * @return array
	 */
	public static function singleFoodMetaFields() {
		return array_merge(
			Options::foodGeneralOptions(),
			Options::foodAdvancedOptions()
		);
	}

	/**
	 * MetaField list for food Page
	 *
	 * @return array
	 */
	public static function fmpAllSettingsFields() {
		$allSettings = array_merge(
			Options::generalSettings(),
			Options::detailPageSettings()
		);

		return apply_filters( 'rt_fm_setting_fields', $allSettings );
	}

	/**
	 * Generate MetaField Name list for shortCode Page
	 *
	 * @return array
	 */
	public static function fmpScMetaFields() {
		return array_merge(
			Options::scLayoutMetaFields(),
			Options::scResponsiveMetaFields(),
			Options::scPaginationFields(),
			Options::scCategoryTitleFields(),
			Options::scImageMetaFields(),
			Options::scExcerptMetaFields(),
			Options::scDetailsMetaFields(),
			Options::scFilterMetaFields(),
			Options::scItemFields(),
			Options::scStyleGeneralFields(),
			Options::scStyleContentFields(),
			Options::scStyleButtonBgColorFields(),
			Options::scStyleButtonColorFields(),
			Options::scStyleExtraFields()
		);
	}


	/**
	 * Sanitize field value
	 *
	 * @param array $field Field.
	 * @param null  $value Value.
	 *
	 * @return array|null
	 * @internal param $value
	 */
	public static function sanitize( $field = [], $value = null ) {
		$newValue = null;

		if ( ! is_array( $field ) ) {
			return $newValue;
		}

		$type = ( ! empty( $field['type'] ) ? $field['type'] : 'text' );

		if ( empty( $field['multiple'] ) ) {
			if ( $type == 'text' || $type == 'number' || $type == 'select' || $type == 'checkbox' || $type == 'radio' ) {
				$newValue = sanitize_text_field( $value );
			} elseif ( $type == 'price' ) {
				$newValue = ( '' === $value ) ? '' : self::format_decimal( $value );
			} elseif ( $type == 'url' ) {
				$newValue = esc_url( $value );
			} elseif ( $type == 'slug' ) {
				$newValue = sanitize_title_with_dashes( $value );
			} elseif ( $type == 'textarea' ) {
				$newValue = wp_kses_post( $value );
			} elseif ( $type == 'custom_css' ) {
				$newValue = esc_attr( $value );
			} elseif ( $type == 'colorpicker' ) {
				$newValue = self::sanitize_hex_color( $value );
			} elseif ( $type == 'image_size' ) {
				$newValue = [];

				foreach ( $value as $k => $v ) {
					$newValue[ $k ] = esc_attr( $v );
				}
			} elseif ( $type == 'group' ) {
				$newValue = [];

				foreach ( $value as $k => $v ) {
					if ( $k == 'bg_color' ) {
						$newValue[ $k ] = self::sanitize_hex_color( $v );
					} else {
						$newValue[ $k ] = self::sanitize( [ 'type' => 'text' ], $v );
					}
				}
			} elseif ( $type == 'category-style' ) {
				$newValue = [];

				foreach ( $value as $k => $v ) {
					if ( $k == 'first_color' || $k == 'second_color' ) {
						$newValue[ $k ] = self::sanitize_hex_color( $v );
					} else {
						$newValue[ $k ] = self::sanitize( [ 'type' => 'text' ], $v );
					}
				}
			} elseif ( $type == 'style' ) {
				$newValue = [];

				foreach ( $value as $k => $v ) {
					if ( $k == 'color' ) {
						$newValue[ $k ] = self::sanitize_hex_color( $v );
					} else {
						$newValue[ $k ] = self::sanitize( [ 'type' => 'text' ], $v );
					}
				}
			} else {
				$newValue = sanitize_text_field( $value );
			}
		} else {
			$newValue = [];

			if ( ! empty( $value ) ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $key => $val ) {
						if ( $type == 'style' && $key == 0 ) {
							if ( function_exists( 'sanitize_hex_color' ) ) {
								$newValue = sanitize_hex_color( $val );
							} else {
								$newValue[] = self::sanitize_hex_color( $val );
							}
						} else {
							$newValue[] = sanitize_text_field( $val );
						}
					}
				} else {
					$newValue[] = sanitize_text_field( $value );
				}
			}
		}

		return $newValue;
	}

	public static function sanitize_hex_color( $color ) {
		if ( function_exists( 'sanitize_hex_color' ) ) {
			return sanitize_hex_color( $color );
		} else {
			if ( '' === $color ) {
				return '';
			}

			// 3 or 6 hex digits, or the empty string.
			if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
				return $color;
			}
		}
	}

	/**
	 * Convert hexdec color string to rgb(a) string
	 *
	 * @param string $color Color.
	 * @param float  $opacity Opacity.
	 * @return string
	 */
	public static function rtHex2rgba( $color, $opacity = .5 ) {
		$default = 'rgb(0,0,0)';

		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided.
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values.
		if ( strlen( $color ) == 6 ) {
			$hex = [ $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] ];
		} elseif ( strlen( $color ) == 3 ) {
			$hex = [ $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] ];
		} else {
			return $default;
		}

		// Convert hexadec to rgb.
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(rgba or rgb).
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}

			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		// Return rgb(a) color string.
		return $output;
	}

	/**
	 *  Get all Category list for food-menu
	 *
	 * @return array
	 */
	public static function getAllFmpCategoryList() {
		global $post;

		$taxonomy = TLPFoodMenu()->taxonomies['category'];

		if ( $post ) {
			$source = get_post_meta( $post->ID, 'fmp_source', true );
			$source = ( $source && in_array( $source, array_keys( Options::scProductSource() ) ) ) ? $source : TLPFoodMenu()->post_type;

			if ( $source == 'product' && TLPFoodMenu()->isWcActive() ) {
				$taxonomy = 'product_cat';
			}
		}
		$terms    = [];
		$termList = get_terms( $taxonomy, [ 'hide_empty' => 0 ] );

		if ( is_array( $termList ) && ! empty( $termList ) && empty( $termList['errors'] ) ) {
			foreach ( $termList as $term ) {
				$terms[ $term->term_id ] = $term->name;
			}
		}

		return $terms;
	}

	/**
	 * Placeholder Image.
	 *
	 * @return string
	 */
	public static function placeholder_img_src() {
		return TLPFoodMenu()->assets_url() . 'images/placeholder.png';
	}

	/**
	 * Image Types.
	 *
	 * @return string
	 */
	public static function get_image_types() {
		return [
			'normal' => esc_html__( 'Normal', 'tlp-food-menu' ),
			'circle' => esc_html__( 'Circle', 'tlp-food-menu' ),
		];
	}

	/**
	 * Image Position.
	 *
	 * @return string
	 */
	public static function get_image_position() {
		return [
			'top'    => esc_html__( 'Top', 'tlp-food-menu' ),
			'center' => esc_html__( 'Center', 'tlp-food-menu' ),
			'bottom' => esc_html__( 'Bottom', 'tlp-food-menu' ),
		];
	}

	/**
	 * Category Title Types.
	 *
	 * @return string
	 */
	public static function get_category_title_types() {
		$types = [
			'default' => esc_html__( 'Layout Default', 'tlp-food-menu' ),
			'type-1'  => esc_html__( 'Type 1', 'tlp-food-menu' ),
			'type-2'  => esc_html__( 'Type 2', 'tlp-food-menu' ),
		];

		if ( TLPFoodMenu()->has_pro() ) {
			$types['type-3'] = esc_html__( 'Type 3', 'tlp-food-menu' );
			$types['type-4'] = esc_html__( 'Type 4', 'tlp-food-menu' );
		}

		return $types;
	}

	/**
	 * Image Types.
	 *
	 * @return string
	 */
	public static function get_details_types() {
		return [
			'newpage' => esc_html__( 'Single Page', 'tlp-food-menu' ),
			'popup'   => esc_html__( 'Pop Up', 'tlp-food-menu' ),
		];
	}

	/**
	 * Image Types.
	 *
	 * @return string
	 */
	public static function get_details_target() {
		return [
			'_self'  => esc_html__( 'Same Window', 'tlp-food-menu' ),
			'_blank' => esc_html__( 'New Window', 'tlp-food-menu' ),
		];
	}

	/**
	 * Image Hover.
	 *
	 * @return string
	 */
	public static function get_image_hover() {
		return [
			'zoom_in'  => esc_html__( 'Zoom In', 'tlp-food-menu' ),
			'zoom_out' => esc_html__( 'Zoom Out', 'tlp-food-menu' ),
			'none'     => esc_html__( 'None', 'tlp-food-menu' ),
		];
	}

	/**
	 * Get Image Sizes.
	 *
	 * @return array
	 */
	public static function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = [];

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, [ 'thumbnail', 'medium', 'large' ] ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = [
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				];
			}
		}

		$imgSize = [];
		foreach ( $sizes as $key => $img ) {
			$imgSize[ $key ] = ucfirst( $key ) . " ({$img['width']}*{$img['height']})";
		}

		return apply_filters( 'fmp_image_size', $imgSize );
	}

	/**
	 * Get Currency List.
	 *
	 * @return array
	 */
	public static function getCurrencyList() {
		$currencyList = [];

		foreach ( Options::currency_list() as $key => $currency ) {
			$currencyList[ $key ] = $currency['name'] . ' (' . $currency['symbol'] . ')';
		}

		return $currencyList;
	}

	/**
	 * Excerpt Max Character Length.
	 *
	 * @param int $charLength Character Length.
	 * @return string
	 */
	public static function the_excerpt_max_charlength( $charLength ) {
		$excerpt = get_the_excerpt();
		$html    = null;

		$charLength ++;

		if ( mb_strlen( $excerpt ) > $charLength ) {
			$subex   = mb_substr( $excerpt, 0, $charLength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

			if ( $excut < 0 ) {
				$html .= mb_substr( $subex, 0, $excut );
			} else {
				$html .= $subex;
			}
		} else {
			$html .= $excerpt;
		}

		return $html;
	}


	/**
	 * Word Limit.
	 *
	 * @param string $string Word.
	 * @param int    $word_limit Limit.
	 * @return string
	 */
	public static function string_limit_words( $string, $word_limit ) {
		$words = explode( ' ', $string );

		return implode( ' ', array_slice( $words, 0, $word_limit ) );
	}

	/**
	 * Get Price
	 *
	 * @param int $id Post ID.
	 * @return string
	 */
	public static function getPrice( $id = null ) {
		if ( $id ) {
			$id = absint( $id );
		} else {
			global $post;
			$id = $post->ID;
		}

		$regular_price   = get_post_meta( $id, '_regular_price', true );

		if ( ! TLPFoodMenu()->has_pro() ) {
			$settings = get_option( TLPFoodMenu()->options['settings'] );
			$trailing_zeroes = ! empty( $settings['trailing_zeroes'] ) ? 1 : 0;

			if ( ! empty( $trailing_zeroes ) ) {
				return (int) $regular_price;
			}
		}

		return $regular_price;
	}

	/**
	 * Get Currency.
	 *
	 * @return string
	 */
	public static function getCurrency() {
		$settings = get_option( TLPFoodMenu()->options['settings'] );
		$currency = ( isset( $settings['currency'] ) ? esc_attr( $settings['currency'] ) : 'USD' );

		return $currency;
	}

	/**
	 * Get Currency Symbol.
	 *
	 * @return string
	 */
	public static function getCurrencySymbol() {
		$currency = self::getCurrency();
		$cList    = Options::currency_list();

		return $cList[ $currency ]['symbol'];
	}

	/**
	 * Get Currency Position
	 *
	 * @return string
	 */
	public static function getCurrencyPosition() {
		$settings = get_option( TLPFoodMenu()->options['settings'] );

		return ( ! empty( $settings['currency_position'] ) ? esc_attr( $settings['currency_position'] ) : 'left' );
	}

	/**
	 * Get Price with Label
	 *
	 * @return string
	 */
	public static function getPriceWithLabel() {
		$price = self::getPrice();

		if ( $price ) {
			$symbol    = self::getCurrencySymbol();
			$currencyP = self::getCurrencyPosition();

			switch ( $currencyP ) {
				case 'left':
					$price = $symbol . $price;
					break;

				case 'right':
					$price = $price . $symbol;
					break;

				case 'left_space':
					$price = $symbol . ' ' . $price;
					break;

				case 'right_space':
					$price = $price . ' ' . $symbol;
					break;

				default:
					break;
			}
		}

		return apply_filters('rtfm_food_price_modifier',$price,get_the_ID());
	}

	public static function strip_tags_content( $text, $limit = 0, $tags = '', $invert = false ) {
		preg_match_all( '/<(.+?)[\s]*\/?[\s]*>/si', trim( $tags ), $tags );
		$tags = array_unique( $tags[1] );

		if ( is_array( $tags ) and count( $tags ) > 0 ) {
			if ( $invert == false ) {
				$text = preg_replace(
					'@<(?!(?:' . implode( '|', $tags ) . ')\b)(\w+)\b.*?>.*?</\1>@si',
					'',
					$text
				);
			} else {
				$text = preg_replace( '@<(' . implode( '|', $tags ) . ')\b.*?>.*?</\1>@si', '', $text );
			}
		} elseif ( $invert == false ) {
			$text = preg_replace( '@<(\w+)\b.*?>.*?</\1>@si', '', $text );
		}
		if ( $limit > 0 && strlen( $text ) > $limit ) {
			$text = substr( $text, 0, $limit );
		}

		return $text;
	}

	/**
	 * Call the Image resize model for resize function
	 *
	 * @param            $url
	 * @param null       $width
	 * @param null       $height
	 * @param null       $crop
	 * @param bool|true  $single
	 * @param bool|false $upscale
	 *
	 * @return array|bool|string
	 * @throws FmpException
	 */
	public static function rtImageReSize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		$rtResize = new ReSizer();
		return $rtResize->process( $url, $width, $height, $crop, $single, $upscale );
	}

	public static function getFeatureImage( $post_id = null, $fImgSize = 'medium', $defaultImgId = 0, $customImgSize = [], $lazy = false ) {
		$imgHtml = $imgSrc = $attachment_id = null;
		$cSize   = false;

		if ( $fImgSize == 'fmp_custom' ) {
			$fImgSize = 'full';
			$cSize    = true;
		}

		$aID        = get_post_thumbnail_id( $post_id );
		$post_title = get_the_title( $post_id );
		$img_alt    = trim( wp_strip_all_tags( get_post_meta( $aID, '_wp_attachment_image_alt', true ) ) );
		$alt_tag    = ! empty( $img_alt ) ? $img_alt : trim( wp_strip_all_tags( $post_title ) );
		$lazy_class = $lazy ? ' swiper-lazy' : '';
		$attr       = [
			'class' => 'fmp-feature-img' . $lazy_class,
			'alt'   => $alt_tag,
		];

		$actual_dimension = wp_get_attachment_metadata( $aID, true );

		if ( empty( $actual_dimension ) && $defaultImgId ) {
			$actual_dimension = wp_get_attachment_metadata( $defaultImgId, true );
		}

		$actual_w = ! empty( $actual_dimension['width'] ) ? $actual_dimension['width'] : '';
		$actual_h = ! empty( $actual_dimension['height'] ) ? $actual_dimension['height'] : '';

		if ( $aID ) {
			$imgHtml       = wp_get_attachment_image( $aID, $fImgSize, false, $attr );
			$attachment_id = $aID;
		}

		if ( ! $imgHtml && $defaultImgId ) {
			$imgHtml       = wp_get_attachment_image( $defaultImgId, $fImgSize, false, $attr );
			$attachment_id = $defaultImgId;
		}

		if ( $imgHtml && $cSize ) {
			preg_match( '@src="([^"]+)"@', $imgHtml, $match );

			$imgSrc = array_pop( $match );
			$w      = ! empty( $customImgSize['width'] ) ? absint( $customImgSize['width'] ) : null;
			$h      = ! empty( $customImgSize['height'] ) ? absint( $customImgSize['height'] ) : null;
			$c      = ! empty( $customImgSize['crop'] ) && $customImgSize['crop'] == 'soft' ? false : true;

			if ( $w && $h ) {
				if ( $w >= $actual_w || $h >= $actual_h ) {
					$w = 150;
					$h = 150;
					$c = true;
				}

				$image = self::rtImageReSize( $imgSrc, $w, $h, $c, false );

				if ( ! empty( $image ) ) {
					if ( $lazy ) {
						list( $src, $width, $height ) = $image;

						$hwstring         = image_hwstring( $width, $height );
						$attachment       = get_post( $attachment_id );
						$attr             = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $fImgSize );
						$attr['data-src'] = $src;
						$attr             = array_map( 'esc_attr', $attr );
						$imgHtml          = rtrim( "<img $hwstring" );

						foreach ( $attr as $name => $value ) {
							$imgHtml .= " $name=" . '"' . $value . '"';
						}

						$imgHtml .= ' />';
					} else {
						list( $src, $width, $height ) = $image;

						$hwstring    = image_hwstring( $width, $height );
						$attachment  = get_post( $attachment_id );
						$attr        = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $fImgSize );
						$attr['src'] = $src;
						$attr        = array_map( 'esc_attr', $attr );
						$imgHtml     = rtrim( "<img $hwstring" );

						foreach ( $attr as $name => $value ) {
							$imgHtml .= " $name=" . '"' . $value . '"';
						}

						$imgHtml .= ' />';
					}
				}
			}
		}

		if ( ! $imgHtml ) {
			$hwstring      = image_hwstring( 160, 160 );
			$attr          = isset( $attr['src'] ) ? apply_filters( 'wp_get_attachment_image_attributes', $attr, false, $fImgSize ) : [];
			$attr['class'] = 'default-img';
			$attr['src']   = esc_url( self::placeholder_img_src() );
			$attr['alt']   = esc_html__( 'Default Image', 'tlp-food-menu' );
			$imgHtml       = rtrim( "<img $hwstring" );

			foreach ( $attr as $name => $value ) {
				$imgHtml .= " $name=" . '"' . $value . '"';
			}

			$imgHtml .= ' />';
		}

		if ( $lazy ) {
			$imgHtml = $imgHtml . '<div class="swiper-lazy-preloader swiper-lazy-preloader"></div>';
		}

		$imgHtml = $imgHtml . '<i class="fmp-image-icon"></i>';

		return $imgHtml;
	}

	public static function getAttachedImage( $attach_id, $fImgSize = 'medium', $customImgSize = [] ) {
		$imgSrc = $image = null;
		$cSize  = false;

		if ( $fImgSize == 'fmp_custom' ) {
			$fImgSize = 'full';
			$cSize    = true;
		}

		if ( $attach_id ) {
			$image  = wp_get_attachment_image( $attach_id, $fImgSize );
			$imageS = wp_get_attachment_image_src( $attach_id, $fImgSize );
			$imgSrc = ! empty( $imageS[0] ) ? $imageS[0] : '';
		} else {
			$imgSrc = self::placeholder_img_src();
			$image  = "<img src='{$imgSrc}' />";
		}

		if ( $imgSrc && $cSize ) {
			$w = ( ! empty( $customImgSize['width'] ) ? absint( $customImgSize['width'] ) : null );
			$h = ( ! empty( $customImgSize['height'] ) ? absint( $customImgSize['height'] ) : null );
			$c = ( ! empty( $customImgSize['crop'] ) && $customImgSize['crop'] == 'soft' ? false : true );

			if ( $w && $h ) {
				$imgSrc = self::rtImageReSize( $imgSrc, $w, $h, $c );
				$image  = '<img src="' . esc_url( $imgSrc ) . '" />';
			}
		}

		return $image;
	}

	/**
	 * Returns the product categories.
	 *
	 * @param        $id
	 * @param string $sep (default: ', ')
	 * @param string $before (default: '')
	 * @param string $after (default: '')
	 *
	 * @return string
	 */
	public static function get_categories( $id, $sep = ', ', $before = '', $after = '' ) {
		return get_the_term_list( $id, TLPFoodMenu()->taxonomies['category'], $before, $sep, $after );
	}

	public static function get_shortCode_list() {
		$scList = null;
		$scQ    = get_posts(
			[
				'post_type'      => TLPFoodMenu()->shortCodePT,
				'order_by'       => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			]
		);

		if ( ! empty( $scQ ) ) {
			foreach ( $scQ as $sc ) {
				$scList[ $sc->ID ] = $sc->post_title;
			}
		}

		return $scList;
	}

	/**
	 * Promotion Product
	 *
	 * @param array $products Products.
	 * @return string
	 */
	public static function get_product_list_html( $products = [] ) {
		$html = null;

		if ( ! empty( $products ) ) {
			foreach ( $products as $type => $list ) {
				if ( ! empty( $list ) ) {
					$htmlProducts = null;
					foreach ( $list as $product ) {
						$image_url       = isset( $product['image_url'] ) ? $product['image_url'] : null;
						$image_thumb_url = isset( $product['image_thumb_url'] ) ? $product['image_thumb_url'] : null;
						$image_thumb_url = $image_thumb_url ? $image_thumb_url : $image_url;
						$price           = isset( $product['price'] ) ? $product['price'] : null;
						$title           = isset( $product['title'] ) ? $product['title'] : null;
						$url             = isset( $product['url'] ) ? $product['url'] : null;
						$buy_url         = isset( $product['buy_url'] ) ? $product['buy_url'] : null;
						$buy_url         = $buy_url ? $buy_url : $url;
						$doc_url         = isset( $product['doc_url'] ) ? $product['doc_url'] : null;
						$demo_url        = isset( $product['demo_url'] ) ? $product['demo_url'] : null;
						$feature_list    = null;

						$info_html = sprintf(
							'<div class="rt-product-info">%s%s%s</div>',
							$title ? sprintf( "<h3 class='rt-product-title'><a href='%s' target='_blank'>%s%s</a></h3>", esc_url( $url ), $title, $price ? ' ($' . $price . ')' : null ) : null,
							$feature_list,
							$buy_url || $demo_url || $doc_url ?
								sprintf(
									'<div class="rt-product-action">%s%s%s</div>',
									$buy_url ? sprintf( '<a class="rt-admin-btn button-primary" href="%s" target="_blank">%s</a>', esc_url( $buy_url ), esc_html__( 'Buy', 'tlp-food-menu' ) ) : null,
									$demo_url ? sprintf( '<a class="rt-admin-btn" href="%s" target="_blank">%s</a>', esc_url( $demo_url ), esc_html__( 'Demo', 'tlp-food-menu' ) ) : null,
									$doc_url ? sprintf( '<a class="rt-admin-btn" href="%s" target="_blank">%s</a>', esc_url( $doc_url ), esc_html__( 'Documentation', 'tlp-food-menu' ) ) : null
								)
								: null
						);

						$htmlProducts .= sprintf(
							'<div class="rt-product">%s%s</div>',
							$image_thumb_url ? sprintf(
								'<div class="rt-media"><img src="%s" alt="%s" /></div>',
								esc_url( $image_thumb_url ),
								esc_html( $title )
							) : null,
							$info_html
						);

					}

					$html .= sprintf( '<div class="rt-product-list">%s</div>', $htmlProducts );
				}
			}
		}

		return $html;
	}

	/**
	 * Returns true when viewing a product taxonomy archive.
	 *
	 * @return boolean
	 */
	public static function is_food_taxonomy() {
		return is_tax( get_object_taxonomies( TLPFoodMenu()->post_type ) );
	}

	/**
	 * Prints HTMl.
	 *
	 * @param string $html HTML.
	 * @param bool   $allHtml All HTML.
	 *
	 * @return mixed
	 */
	public static function print_html( $html, $allHtml = false ) {
		if ( $allHtml ) {
			echo stripslashes_deep( $html );
		} else {
			echo wp_kses_post( stripslashes_deep( $html ) );
		}
	}

	/**
	 * Allowed HTML for wp_kses.
	 *
	 * @param string $level Tag level.
	 *
	 * @return mixed
	 */
	public static function allowedHtml( $level = 'basic' ) {
		$allowed_html = [];

		switch ( $level ) {
			case 'basic':
				$allowed_html = [
					'b'      => [
						'class' => [],
						'id'    => [],
					],
					'i'      => [
						'class' => [],
						'id'    => [],
					],
					'u'      => [
						'class' => [],
						'id'    => [],
					],
					'br'     => [
						'class' => [],
						'id'    => [],
					],
					'em'     => [
						'class' => [],
						'id'    => [],
					],
					'span'   => [
						'class' => [],
						'id'    => [],
					],
					'strong' => [
						'class' => [],
						'id'    => [],
					],
					'hr'     => [
						'class' => [],
						'id'    => [],
					],
					'p'     => [
						'class' => [],
						'id'    => [],
					],
					'div'    => [
						'class' => [],
						'id'    => [],
					],
					'a'      => [
						'href'   => [],
						'title'  => [],
						'class'  => [],
						'id'     => [],
						'target' => [],
					],
				];
				break;

			case 'advanced':
				$allowed_html = [
					'b'      => [
						'class' => [],
						'id'    => [],
					],
					'i'      => [
						'class' => [],
						'id'    => [],
					],
					'u'      => [
						'class' => [],
						'id'    => [],
					],
					'br'     => [
						'class' => [],
						'id'    => [],
					],
					'em'     => [
						'class' => [],
						'id'    => [],
					],
					'span'   => [
						'class' => [],
						'id'    => [],
					],
					'strong' => [
						'class' => [],
						'id'    => [],
					],
					'hr'     => [
						'class' => [],
						'id'    => [],
					],
					'a'      => [
						'href'   => [],
						'title'  => [],
						'class'  => [],
						'id'     => [],
						'target' => [],
					],
					'input'  => [
						'type'  => [],
						'name'  => [],
						'class' => [],
						'value' => [],
					],
				];
				break;

			case 'image':
				$allowed_html = [
					'img' => [
						'src'      => [],
						'data-src' => [],
						'alt'      => [],
						'height'   => [],
						'width'    => [],
						'class'    => [],
						'id'       => [],
						'style'    => [],
						'srcset'   => [],
						'loading'  => [],
						'sizes'    => [],
					],
					'div' => [
						'class' => [],
					],
				];
				break;

			case 'anchor':
				$allowed_html = [
					'a' => [
						'href'  => [],
						'title' => [],
						'class' => [],
						'id'    => [],
						'style' => [],
					],
				];
				break;

			default:
				// code...
				break;
		}

		return $allowed_html;
	}

	/**
	 * Definition for wp_kses.
	 *
	 * @param string $string String to check.
	 * @param string $level Tag level.
	 *
	 * @return mixed
	 */
	public static function htmlKses( $string, $level ) {
		if ( empty( $string ) ) {
			return;
		}

		return wp_kses( $string, self::allowedHtml( $level ) );
	}
}
