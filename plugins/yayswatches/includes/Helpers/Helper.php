<?php

namespace Yay_Swatches\Helpers;

defined( 'ABSPATH' ) || exit;

class Helper {

	public static function sanitize_array( $var = array() ) {
		if ( is_array( $var ) ) {
			return array_map( 'self::sanitize_array', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

	public static function sanitize( $var = array() ) {
		return wp_kses_post_deep( $var['data'] );
	}

	public static function get_current_theme_active() {
		$theme = get_option( 'template' );
		return strtolower( $theme );
	}

	public static function get_current_url() {
		global $wp;
		if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$query_string = sanitize_text_field( $_SERVER['QUERY_STRING'] );
			$current_url  = add_query_arg( $query_string, '', home_url( $wp->request ) );
		} else {
			$current_url = add_query_arg( array(), home_url( $wp->request ) );
		}
		return $current_url;
	}

	public static function is_product_page() {
		return is_singular( 'product' );
	}

	public static function get_allow_html() {
		$rules = array(
			'select' => array(
				'id'                    => array(),
				'class'                 => array(),
				'name'                  => array(),
				'data-show_option_none' => array(),
				'data-attribute_name'   => array(),
			),
			'option' => array(
				'id'       => array(),
				'class'    => array(),
				'value'    => array(),
				'selected' => array(),
			),
		);
		return $rules;
	}

	public static function get_terms_attribute_not_exists( $name = false, $args1 = array(), $args2 = array() ) {
		$data = array_reduce(
			$args1,
			function ( $res, $key ) use ( $name, $args2 ) {
				$res[ $key ] = esc_html( apply_filters( 'woocommerce_variation_option_name', $key, null, $name, $args2 ) );

				return $res;
			},
			array()
		);
		return $data;

	}

	public static function get_all_terms_by_sort( $product_id, $pa_attribute ) {
		$terms = wc_get_product_terms(
			$product_id,
			$pa_attribute,
			array(
				'fields' => 'all',
			)
		);
		return is_wp_error( $terms ) ? false : $terms;
	}

	public static function get_button_style( $styles = array(), $type = 'button' ) {
		$buttonBorderRadius = '4px';
		$buttonFontSize     = '14px';
		$buttonSize         = '11px 16px';
		switch ( $styles['buttonSize'] ) {
			case 'small':
				$buttonBorderRadius = '3px';
				$buttonFontSize     = '12px';
				$buttonSize         = '7px 12px';
				break;
			case 'large':
				$buttonBorderRadius = '6px';
				$buttonFontSize     = '16px';
				$buttonSize         = '15px 25px';
				break;
			default:
				break;
		}
		$data_styles = '--yay-swatches-button:' . $styles['buttonNormalColor'] .
		  ';--yay-swatches-button-active:' . $styles['buttonActiveColor'] .
		  ';--yay-swatches-button-border:' . $styles['borderNormalColor'] .
		  ';--yay-swatches-button-border-active:' . $styles['borderActiveColor'] .
		  ';--yay-swatches-button-text:' . $styles['textNormalColor'] .
		  ';--yay-swatches-button-text-active:' . $styles['textActiveColor'] .
		  ';--yay-swatches-button-border-radius:' . $buttonBorderRadius .
		  ';--yay-swatches-button-font-size:' . $buttonFontSize .
		  ';--yay-swatches-button-padding:' . $buttonSize . ';';
		  return $data_styles;
	}

	public static function get_swatch_size( $styles = array() ) {
		switch ( $styles['swatchSize'] ) {
			case 'small':
				return '28px';
			case 'medium':
				return '38px';
			case 'large':
				return '48px';
			case 'custom':
				return $styles['swatchCustomSize'] . 'px';
			default:
				return '28px';
		}
	}

	public static function get_image_swatch_style( $styles = array() ) {
		$swatchSize  = self::get_swatch_size( $styles );
		$data_styles = '--yay-swatches-swatch-border:' . $styles['borderNormalColor'] .
		  ';--yay-swatches-swatch-border-active:' . $styles['borderActiveColor'] .
		  ';--yay-swatches-swatch-border-radius:' . ( 'circle' === $styles['swatchStyle'] ? '100%' : '4px' ) .
		  ';--yay-swatches-swatch-size:' . $swatchSize . ';';
		return $data_styles;
	}

	public static function get_color_style( $styles = array(), $args = array() ) {
		$imagePosition = 'center';
		$imageSize     = 'cover';
		switch ( $styles['imagePosition'] ) {
			case 'top':
				$imagePosition = 'center top';
				break;
			case 'bottom':
				$imagePosition = 'center bottom';
				break;
			case 'bottom':
				$imagePosition = 'center center';
				$imageSize     = 'contain';
				break;
			default:
				break;
		}
		if ( isset( $args['swatch_image'] ) && ! empty( $args['swatch_image'] ) ) {
			$data_styles = 'background:url(' . $args['swatch_image'] . ')' .
			';background-position:' . $imagePosition .
			';background-repeat: no-repeat;background-color: transparent;background-size:' . $imageSize . ';';
		} else {
			$is_dual_color = 'true' === strtolower( $args['swatch_show_hide'] ) || '1' === strtolower( $args['swatch_show_hide'] );
			if ( isset( $args['swatch_show_hide'] ) && $is_dual_color ) {
				$data_styles = 'border-radius:' . ( 'circle' === $styles['swatchStyle'] ? '50%' : '4px' ) .
				';background:linear-gradient(135deg,' . $args['swatch_color'] . ' 50%, ' . $args['swatch_dual_color'] . ' 50%);';
			} else {
				$data_styles = 'background:' . $args['swatch_color'] . ';';
			}
		}
		return $data_styles;
	}

	public static function get_colors_list() {
		$colors = array(
			'aliceblue'              => '#F0F8FF',
			'alice-blue'             => '#F0F8FF',
			'antiquewhite'           => '#FAEBD7',
			'antique-white'          => '#FAEBD7',
			'aqua'                   => '#00FFFF',
			'aquamarine'             => '#7FFFD4',
			'azure'                  => '#F0FFFF',
			'beige'                  => '#F5F5DC',
			'bisque'                 => '#FFE4C4',
			'black'                  => '#000000',
			'blanchedalmond'         => '#FFEBCD',
			'blanched-almond'        => '#FFEBCD',
			'blue'                   => '#0000FF',
			'blueviolet'             => '#8A2BE2',
			'blue-violet'            => '#8A2BE2',
			'brown'                  => '#A52A2A',
			'burlywood'              => '#DEB887',
			'cadetblue'              => '#5F9EA0',
			'cadet-blue'             => '#5F9EA0',
			'chartreuse'             => '#7FFF00',
			'chocolate'              => '#D2691E',
			'coral'                  => '#FF7F50',
			'cornflowerblue'         => '#6495ED',
			'cornflower-blue'        => '#6495ED',
			'cornsilk'               => '#FFF8DC',
			'crimson'                => '#DC143C',
			'cyan'                   => '#00FFFF',
			'darkblue'               => '#00008B',
			'dark-blue'              => '#00008B',
			'darkcyan'               => '#008B8B',
			'dark-cyan'              => '#008B8B',
			'darkgoldenrod'          => '#B8860B',
			'dark-goldenrod'         => '#B8860B',
			'darkgray'               => '#A9A9A9',
			'dark-gray'              => '#A9A9A9',
			'darkgrey'               => '#A9A9A9',
			'dark-grey'              => '#A9A9A9',
			'darkgreen'              => '#006400',
			'dark-green'             => '#006400',
			'darkkhaki'              => '#BDB76B',
			'dark-khaki'             => '#BDB76B',
			'darkmagenta'            => '#8B008B',
			'dark-magenta'           => '#8B008B',
			'darkolivegreen'         => '#556B2F',
			'dark-olivegreen'        => '#556B2F',
			'dark-olive-green'       => '#556B2F',
			'darkorange'             => '#FF8C00',
			'dark-orange'            => '#FF8C00',
			'darkorchid'             => '#9932CC',
			'dark-orchid'            => '#9932CC',
			'darkred'                => '#8B0000',
			'dark-red'               => '#8B0000',
			'darksalmon'             => '#E9967A',
			'dark-salmon'            => '#E9967A',
			'darkseagreen'           => '#8FBC8F',
			'dark-seagreen'          => '#8FBC8F',
			'darkslateblue'          => '#483D8B',
			'dark-slateblue'         => '#483D8B',
			'dark-slate-blue'        => '#483D8B',
			'darkslategray'          => '#2F4F4F',
			'dark-slategray'         => '#2F4F4F',
			'dark-slate-gray'        => '#2F4F4F',
			'darkslategrey'          => '#2F4F4F',
			'dark-slategrey'         => '#2F4F4F',
			'dark-slate-grey'        => '#2F4F4F',
			'darkturquoise'          => '#00CED1',
			'darkt-urquoise'         => '#00CED1',
			'darkviolet'             => '#9400D3',
			'dark-violet'            => '#9400D3',
			'deeppink'               => '#FF1493',
			'deep-pink'              => '#FF1493',
			'deepskyblue'            => '#00BFFF',
			'deep-skyblue'           => '#00BFFF',
			'deep-sky-blue'          => '#00BFFF',
			'dimgray'                => '#696969',
			'dim-gray'               => '#696969',
			'dimgrey'                => '#696969',
			'dim-grey'               => '#696969',
			'dodgerblue'             => '#1E90FF',
			'dodger-blue'            => '#1E90FF',
			'firebrick'              => '#B22222',
			'fire-brick'             => '#B22222',
			'floralwhite'            => '#FFFAF0',
			'floral-white'           => '#FFFAF0',
			'forestgreen'            => '#228B22',
			'forest-green'           => '#228B22',
			'fuchsia'                => '#FF00FF',
			'gainsboro'              => '#DCDCDC',
			'ghostwhite'             => '#F8F8FF',
			'ghost-white'            => '#F8F8FF',
			'gold'                   => '#FFD700',
			'goldenrod'              => '#DAA520',
			'gray'                   => '#808080',
			'grey'                   => '#808080',
			'green'                  => '#008000',
			'greenyellow'            => '#ADFF2F',
			'green-yellow'           => '#ADFF2F',
			'honeydew'               => '#F0FFF0',
			'hotpink'                => '#FF69B4',
			'hot-pink'               => '#FF69B4',
			'indianred'              => '#CD5C5C',
			'indian-red'             => '#CD5C5C',
			'indigo'                 => '#4B0082',
			'ivory'                  => '#FFFFF0',
			'khaki'                  => '#F0E68C',
			'lavender'               => '#E6E6FA',
			'lavenderblush'          => '#FFF0F5',
			'lavender-blush'         => '#FFF0F5',
			'lawngreen'              => '#7CFC00',
			'lawn-green'             => '#7CFC00',
			'lemonchiffon'           => '#FFFACD',
			'lemon-chiffon'          => '#FFFACD',
			'lightblue'              => '#ADD8E6',
			'light-blue'             => '#ADD8E6',
			'lightcoral'             => '#F08080',
			'light-coral'            => '#F08080',
			'lightcyan'              => '#E0FFFF',
			'light-cyan'             => '#E0FFFF',
			'lightgoldenrodyellow'   => '#FAFAD2',
			'light-goldenrod-yellow' => '#FAFAD2',
			'lightgray'              => '#D3D3D3',
			'light-gray'             => '#D3D3D3',
			'lightgrey'              => '#D3D3D3',
			'light-grey'             => '#D3D3D3',
			'lightgreen'             => '#90EE90',
			'light-green'            => '#90EE90',
			'lightpink'              => '#FFB6C1',
			'light-pink'             => '#FFB6C1',
			'lightsalmon'            => '#FFA07A',
			'light-salmon'           => '#FFA07A',
			'lightseagreen'          => '#20B2AA',
			'light-sea-green'        => '#20B2AA',
			'lightskyblue'           => '#87CEFA',
			'light-sky-blue'         => '#87CEFA',
			'lightslategray'         => '#778899',
			'light-slate-gray'       => '#778899',
			'lightslategrey'         => '#778899',
			'light-slate-grey'       => '#778899',
			'lightsteelblue'         => '#B0C4DE',
			'light-steel-blue'       => '#B0C4DE',
			'lightyellow'            => '#FFFFE0',
			'light-yellow'           => '#FFFFE0',
			'lime'                   => '#00FF00',
			'limegreen'              => '#32CD32',
			'lime-green'             => '#32CD32',
			'linen'                  => '#FAF0E6',
			'magenta'                => '#FF00FF',
			'maroon'                 => '#800000',
			'mediumaquamarine'       => '#66CDAA',
			'medium-aquamarine'      => '#66CDAA',
			'mediumblue'             => '#0000CD',
			'medium-blue'            => '#0000CD',
			'mediumorchid'           => '#BA55D3',
			'medium-orchid'          => '#BA55D3',
			'mediumpurple'           => '#9370DB',
			'medium-purple'          => '#9370DB',
			'mediumseagreen'         => '#3CB371',
			'medium-sea-green'       => '#3CB371',
			'mediumslateblue'        => '#7B68EE',
			'medium-slate-blue'      => '#7B68EE',
			'mediumspringgreen'      => '#00FA9A',
			'medium-spring-green'    => '#00FA9A',
			'mediumturquoise'        => '#48D1CC',
			'mediumt-urquoise'       => '#48D1CC',
			'mediumvioletred'        => '#C71585',
			'medium-violet-red'      => '#C71585',
			'midnightblue'           => '#191970',
			'midnight-blue'          => '#191970',
			'mintcream'              => '#F5FFFA',
			'mint-cream'             => '#F5FFFA',
			'mistyrose'              => '#FFE4E1',
			'misty-rose'             => '#FFE4E1',
			'moccasin'               => '#FFE4B5',
			'navajowhite'            => '#FFDEAD',
			'navajo-white'           => '#FFDEAD',
			'navy'                   => '#000080',
			'oldlace'                => '#FDF5E6',
			'olive'                  => '#808000',
			'olivedrab'              => '#6B8E23',
			'olive-drab'             => '#6B8E23',
			'orange'                 => '#FFA500',
			'orangered'              => '#FF4500',
			'orange-red'             => '#FF4500',
			'orchid'                 => '#DA70D6',
			'palegoldenrod'          => '#EEE8AA',
			'pale-goldenrod'         => '#EEE8AA',
			'palegreen'              => '#98FB98',
			'pale-green'             => '#98FB98',
			'paleturquoise'          => '#AFEEEE',
			'pale-turquoise'         => '#AFEEEE',
			'palevioletred'          => '#DB7093',
			'pale-violet-red'        => '#DB7093',
			'papayawhip'             => '#FFEFD5',
			'papaya-whip'            => '#FFEFD5',
			'peachpuff'              => '#FFDAB9',
			'peach-puff'             => '#FFDAB9',
			'peru'                   => '#CD853F',
			'pink'                   => '#FFC0CB',
			'plum'                   => '#DDA0DD',
			'powderblue'             => '#B0E0E6',
			'powder-blue'            => '#B0E0E6',
			'purple'                 => '#800080',
			'rebeccapurple'          => '#663399',
			'rebecca-purple'         => '#663399',
			'red'                    => '#FF0000',
			'rosybrown'              => '#BC8F8F',
			'rosy-brown'             => '#BC8F8F',
			'royalblue'              => '#4169E1',
			'royal-blue'             => '#4169E1',
			'saddlebrown'            => '#8B4513',
			'saddle-brown'           => '#8B4513',
			'salmon'                 => '#FA8072',
			'sandybrown'             => '#F4A460',
			'sandy-brown'            => '#F4A460',
			'seagreen'               => '#2E8B57',
			'sea-green'              => '#2E8B57',
			'seashell'               => '#FFF5EE',
			'sea-shell'              => '#FFF5EE',
			'sienna'                 => '#A0522D',
			'silver'                 => '#C0C0C0',
			'skyblue'                => '#87CEEB',
			'sky-blue'               => '#87CEEB',
			'slateblue'              => '#6A5ACD',
			'slate-blue'             => '#6A5ACD',
			'slategray'              => '#708090',
			'slate-gray'             => '#708090',
			'slategrey'              => '#708090',
			'slate-grey'             => '#708090',
			'snow'                   => '#FFFAFA',
			'springgreen'            => '#00FF7F',
			'spring-green'           => '#00FF7F',
			'steelblue'              => '#4682B4',
			'steel-blue'             => '#4682B4',
			'tan'                    => '#D2B48C',
			'teal'                   => '#008080',
			'thistle'                => '#D8BFD8',
			'tomato'                 => '#FF6347',
			'turquoise'              => '#40E0D0',
			'violet'                 => '#EE82EE',
			'wheat'                  => '#F5DEB3',
			'white'                  => '#FFFFFF',
			'whitesmoke'             => '#F5F5F5',
			'white-smoke'            => '#F5F5F5',
			'yellow'                 => '#FFFF00',
			'yellowgreen'            => '#9ACD32',
			'yellow-green'           => '#9ACD32',
		);
		return $colors;
	}

	public static function get_default_swatch_customize_settings() {
		return array(
			'borderActiveColor' => '#fff',
			'borderNormalColor' => '#000',
			'imagePosition'     => 'fit',
			'imageSize'         => 'thumbnail',
			'swatchSize'        => 'medium',
			'swatchCustomSize'  => '50',
			'swatchStyle'       => 'circle',
			'swatchTooltip'     => 'disable',
		);
	}

	public static function get_default_button_customize_settings() {
		return array(
			'borderActiveColor' => '#4f5354',
			'borderNormalColor' => '#e1e3e5',
			'buttonActiveColor' => '#4f5354',
			'buttonNormalColor' => '#ffffff',
			'textActiveColor'   => '#ffffff',
			'textNormalColor'   => '#3c434a',
			'buttonSize'        => 'medium',
		);
	}

	public static function get_default_sold_out_settings() {
		return array(
			'soldOutShowHideOptions' => 'show',
			'soldOutShowStyle'       => 'cross',
			'soldOutHideStyle'       => 'automatic',
		);
	}

	public static function get_default_collection_customize_settings() {
		return array(
			'swatchSize'       => 'small',
			'swatchCustomSize' => '50',
			'pictureSize'      => 'thumbnail',
			'label'            => 'show',
			'limit'            => 'hide',
			'numberSwatches'   => 3,
			'numberButton'     => 3,
			'actionPlus'       => 'none',
		);
	}

	public static function get_image_id_by_variation_id( $variations = array(), $attribute_data = array() ) {
		$image_id = false;
		foreach ( $variations as $variation_id ) {
			if ( get_post_meta( $variation_id, 'attribute_' . $attribute_data['attribute_slug'], true ) === $attribute_data['term_slug'] ) {
				$image_id = get_post_thumbnail_id( $variation_id );
				break;
			}
		}
		return $image_id;
	}

}
