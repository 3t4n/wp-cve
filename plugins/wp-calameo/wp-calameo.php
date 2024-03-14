<?php
/*
Plugin Name: WP Calameo
Description: Embed Calameo publications & miniCalameo in a shortcode
Version: 2.1.8
Author: Calameo
*/

/*
Copyright 2009-2023 Calameo  (email : contact@calameo.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WP_Calameo {
	public function __construct() {
		add_shortcode( 'calameo', array( $this, 'calameo_shortcode' ) );
	}

	public function calameo_shortcode( $attributes ) {
		$attributes = shortcode_atts(
			array(
				'allowminiskin'       => '',
				'authid'			  => '',
				'apikey'              => '',
				'clickto'             => 'public',
				'clicktarget'         => '_self',
				'clicktourl'          => '',
				'code'                => '',
				'expires'             => '',
				'height'              => '400',
				'hidelinks'           => false,
				'ip'                  => '',
				'lang'				  => '',
				'locales'             => '',
				'mobiledirect'        => '',
				'mode'                => '',
				'page'                => '',
				'pagefxopacity'       => '',
				'pagefxopacityonzoom' => '',
				'showsharemenu'       => '',
				'signature'           => '',
				'skinurl'             => '',
				'styleurl'            => '',
				'title'               => 'View this publication on Calam&eacute;o',
				'url'                 => '',
				'view'                => '',
				'volume'              => '',
				'wmode'               => '',
				'width'               => '100%',
			), $attributes, 'calameo'
		);

		$attributes['showsharemenu'] = ( $attributes['showsharemenu'] == '' || $attributes['showsharemenu'] === '1' || $attributes['showsharemenu'] === 'true' ) ? 'true' : 'false';

		// Language
		$language = preg_match( '/^([a-z]+)/i', get_bloginfo( 'language' ), $regs );

		$languages = array(
			'en' => 'en',
			'fr' => 'fr',
			'es' => 'es',
			'de' => 'de',
			'it' => 'it',
			'pt' => 'pt',
			'ru' => 'ru',
		);

		if ( empty( $attributes['lang'] ) && ! empty( $language ) && ! empty( $languages[ $regs[0] ] ) ) {
			$attributes['lang'] = $languages[ $regs[0] ];
		}

		// Prepare viewer and link URLs
		$book_url    = 'http://calameo.com/books/' . $attributes['code'] . ( ! empty( $attributes['authid'] ) ? '?authid=' . $attributes['authid'] : '' );
		$home_url    = 'http://calameo.com';
		$publish_url = 'http://calameo.com/upload';
		$browse_url  = 'http://calameo.com/browse/weekly/?o=7&w=DESC';
		$viewer_url  = '//v.calameo.com/';

		// Preparing Flashvars
		$flashvars  = 'bkcode=' . $attributes['code'];
		$flashvars .= '&amp;language=' . $attributes['lang'];
		$flashvars .= '&amp;page=' . $attributes['page'];
		$flashvars .= '&amp;showsharemenu=' . $attributes['showsharemenu'];

		switch ( $attributes['mode'] ) {
			case 'mini':
				if ( empty( $attributes['width'] ) ) {
					$attributes['width'] = '240';
				}
				if ( empty( $attributes['height'] ) ) {
					$attributes['height'] = '150';
				}
				if ( empty( $attributes['clickto'] ) ) {
					$attributes['clickto'] = 'public';
				}
				if ( empty( $attributes['clicktarget'] ) ) {
					$attributes['clicktarget'] = '_self';
				}
				if ( empty( $attributes['clicktourl'] ) ) {
					$attributes['clicktourl'] = '';
				}
				if ( empty( $attributes['autoflip'] ) ) {
					$attributes['autoflip'] = '0';
				}
				if ( empty( $attributes['wmode'] ) ) {
					$attributes['wmode'] = 'transparent';
				}

				$flashvars .= '&amp;clickTo=' . rawurlencode( $attributes['clickto'] );
				$flashvars .= '&amp;clickTarget=' . rawurlencode( $attributes['clicktarget'] );
				$flashvars .= '&amp;clickToUrl=' . rawurlencode( $attributes['clicktourl'] );
				$flashvars .= '&amp;autoFlip=' . max( 0, intval( $attributes['autoflip'] ) );
				$flashvars .= '&amp;mode=mini';

				break;
			case 'viewer':
				$flashvars .= '&amp;mode=viewer';
				if ( ! empty( $attributes['mobiledirect'] ) ) {
					$flashvars .= '&amp;mobiledirect=' . $attributes['mobiledirect'];
				}
			default:
				if ( empty( $attributes['width'] ) ) {
					$attributes['width'] = '100%';
				}
				if ( empty( $attributes['height'] ) ) {
					$attributes['height'] = '400';
				}

				break;
		}

		if ( ! empty( $attributes['authid'] ) ) {
			$flashvars .= '&amp;authid=' . $attributes['authid'];
		}
		if ( ! empty( $attributes['view'] ) ) {
			$flashvars .= '&amp;view=' . $attributes['view'];
		}
		if ( ! empty( $attributes['wmode'] ) ) {
			$flashvars .= '&amp;wmode=' . $attributes['wmode'];
		}
		if ( ! empty( $attributes['allowminiskin'] ) ) {
			$flashvars .= '&amp;allowminiskin=' . $attributes['allowminiskin'];
		}
		if ( ! empty( $attributes['skinurl'] ) ) {
			$flashvars .= '&amp;skinurl=' . $attributes['skinurl'];
		}
		if ( ! empty( $attributes['styleurl'] ) ) {
			$flashvars .= '&amp;styleurl=' . $attributes['styleurl'];
		}
		if ( ! empty( $attributes['shareurl'] ) ) {
			$flashvars .= '&amp;shareurl=' . $attributes['shareurl'];
		}
		if ( ! empty( $attributes['locales'] ) ) {
			$flashvars .= '&amp;locales=' . $attributes['locales'];
		}
		if ( ! empty( $attributes['volume'] ) ) {
			$flashvars .= '&amp;volume=' . $attributes['volume'];
		}
		if ( ! empty( $attributes['pagefxopacity'] ) ) {
			$flashvars .= '&amp;pagefxopacity=' . $attributes['pagefxopacity'];
		}
		if ( ! empty( $attributes['pagefxopacityonzoom'] ) ) {
			$flashvars .= '&amp;pagefxopacityonzoom=' . $attributes['pagefxopacityonzoom'];
		}
		if ( ! empty( $attributes['ip'] ) ) {
			$flashvars .= '&amp;ip=' . $attributes['ip'];
		}
		if ( ! empty( $attributes['apikey'] ) ) {
			$flashvars .= '&amp;apikey=' . $attributes['apikey'];
		}
		if ( ! empty( $attributes['expires'] ) ) {
			$flashvars .= '&amp;expires=' . $attributes['expires'];
		}
		if ( ! empty( $attributes['signature'] ) ) {
			$flashvars .= '&amp;signature=' . $attributes['signature'];
		}
		// Sizes and units
		$attributes['widthUnit']  = ( strpos( $attributes['width'], '%' ) ) ? '' : 'px';
		$attributes['heightUnit'] = ( strpos( $attributes['height'], '%' ) ) ? '' : 'px';

		// Generate HTML embed code
		$html = '<div style="' . esc_attr(
					empty( $attributes['styles'] )
					?
					'text-align: center; width:' . $attributes['width'] . $attributes['widthUnit'] . '; margin: 12px auto;'
					:
					$attributes['styles']
				) . '">';

		if ( empty( $attributes['hidelinks'] ) ) {
			$html .= '<div style="margin: 4px 0px;"><a href="' . esc_attr($book_url) . '">' . esc_html($attributes['title']) . '</a></div>';
		}

		$html .= '<iframe src="' . esc_attr( $viewer_url . '?' . $flashvars ) . '" width="' . esc_attr($attributes['width']) . '" height="' . esc_attr($attributes['height']) . '" style="' . esc_attr(
			'width:' . $attributes['width'] . $attributes['widthUnit'] . ';height:' . $attributes['height'] . $attributes['heightUnit']
		) . '" frameborder="0" scrolling="no" allowtransparency allowfullscreen></iframe>';

		if ( empty( $attributes['hidelinks'] ) ) {
			$html .= '<div style="margin: 4px 0px; font-size: 90%;"><a rel="nofollow" href="' . esc_attr($publish_url) . '">Publish</a> at <a href="' . esc_attr($home_url) . '">Calam&eacute;o</a> or <a href="' . esc_attr($browse_url) . '">browse</a> the library.</div>';
		}

		$html .= '</div>';

		return $html;
	}
}

// Load the class on plugins_loaded hook
add_action( 'plugins_loaded', 'load_calameo_plugin' );
function load_calameo_plugin() {
	new WP_Calameo();
}
