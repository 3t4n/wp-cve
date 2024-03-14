<?php

class ThemeRain_Fonts {

	public function __construct() {
		add_filter( 'themerain_get_fonts', array( $this, 'get_all_fonts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ), 20 );
	}

	public function enqueue_editor() {
		$this->enqueue_frontend();
	}

	public function enqueue_frontend() {
		$this->custom_fonts_frontend();
		$this->adobe_fonts_frontend();
		$this->google_fonts_frontend();
	}

	public static function get_custom_fonts() {
		$custom_fonts = get_option( 'themerain_custom_fonts' );

		if ( empty( $custom_fonts ) ) {
			return;
		}

		foreach ( $custom_fonts as $font ) {
			$name = $font['name'];
			$slug = esc_attr( 'cf-' ) . sanitize_title( $name );

			$fonts[$slug] = $name;
		}

		return $fonts;
	}

	public static function get_adobe_fonts() {
		$adobe_fonts = get_option( 'themerain_adobe_fonts' );
		$fonts       = array();

		if ( isset( $adobe_fonts['list'] ) && $adobe_fonts['list'] ) {
			foreach ( $adobe_fonts['list'] as $font ) {
				$name = $font['name'];
				$slug = esc_attr( 'af-' ) . sanitize_title( $name );
	
				$fonts[$slug] = $name;
			}
		}

		return $fonts;
	}

	public static function get_google_fonts() {
		$google_fonts = self::get_google_fonts_details();

		if ( empty( $google_fonts ) ) {
			return;
		}

		foreach ( $google_fonts as $id => $font ) {
			$slug         = esc_attr( 'gf-' ) . $id;
			$fonts[$slug] = $font['f'];
		}

		return $fonts;
	}

	public static function get_all_fonts() {
		$fonts        = array();
		$custom_fonts = self::get_custom_fonts();
		$adobe_fonts  = self::get_adobe_fonts();
		$google_fonts = self::get_google_fonts();

		if ( $custom_fonts ) {
			$fonts['Custom Fonts'] = $custom_fonts;
		}

		if ( $adobe_fonts ) {
			$fonts['Adobe Fonts'] = $adobe_fonts;
		}

		if ( $google_fonts ) {
			$fonts['Google Fonts'] = $google_fonts;
		}

		return $fonts;
	}

	public static function get_theme_fonts() {
		$sections = apply_filters( 'themerain_customizer', array() );
		$fonts    = array();

		foreach ( $sections as $section ) {
			foreach ( $section['controls'] as $control ) {
				if ( 'fonts' === $control['type'] ) {
					$id    = $control['id'];
					$std   = isset( $control['std'] ) ? $control['std'] : '';
					$value = get_theme_mod( $id ) ? get_theme_mod( $id ) : $std;

					$fonts[] = $value;
				}
			}
		}

		return $fonts;
	}

	public function custom_fonts_frontend() {
		$custom_fonts = get_option( 'themerain_custom_fonts' );

		if ( is_array( $custom_fonts ) && $custom_fonts ) {
			$font_face = null;

			foreach ( $custom_fonts as $key => $item ) {
				$id  = sanitize_title( $item['name'] );

				if ( $id && isset( $item['src'] ) ) {
					$src = sprintf( 'url("%s") format("woff2")', $item['src'] );

					$font_face .= sprintf(
						'@font-face { font-family: "%s"; src: %s; font-weight: %s; font-style: %s; } ',
						$id,
						$src,
						$item['weight'],
						$item['style']
					);
				}
			}

			if ( $font_face ) {
				wp_add_inline_style( 'themerain-style', $font_face );
				wp_add_inline_style( 'themerain-style-editor', $font_face );
			}
		}
	}

	public function adobe_fonts_frontend() {
		$adobe_fonts = get_option( 'themerain_adobe_fonts' );
		$id          = ( isset( $adobe_fonts['id'] ) ) ? $adobe_fonts['id'] : '';

		if ( isset( $adobe_fonts['list'] ) && $adobe_fonts['list'] ) {
			wp_enqueue_style( 'themerain-adobe-fonts', sprintf( 'https://use.typekit.net/%s.css', $id ) );
		}
	}

	public function google_fonts_frontend() {
		$theme_fonts  = self::get_theme_fonts();
		$google_fonts = self::get_google_fonts_details();
		$families     = array();

		foreach ( $theme_fonts as $font_id ) {
			$font_id = str_replace( 'gf-', '', $font_id );

			if ( array_key_exists( $font_id, $google_fonts ) ) {
				$name    = $google_fonts[$font_id]['f'];
				$weights = $google_fonts[$font_id]['v'];

				$families[] = $name . ':' . implode( ',', $weights );
			}
		}

		if ( is_array( $families ) && $families ) {
			$query_args = array(
				'family'  => urlencode( implode( '|', array_unique( $families ) ) ),
				'subset'  => urlencode( 'latin,latin-ext' ),
				'display' => urlencode( 'swap' )
			);

			$google_fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

			wp_enqueue_style( 'themerain-google-fonts', $google_fonts_url );
		}
	}

	public static function get_google_fonts_details() {
		$fonts_json  = file_get_contents( plugin_dir_path( __FILE__ ) . 'google-fonts.json' );
		$fonts_array = json_decode( $fonts_json, true );

		foreach ( $fonts_array['items'] as $key => $font ) {
			$variants_remove = [ '100', '100italic', '200', '200italic', '800', '800italic', '900', '900italic' ];
			$font['v'] = array_diff( $font['v'], $variants_remove );
			$fonts_array['items'][$key] = $font;
		}

		foreach ( $fonts_array['items'] as $font ) {
			$id = trim( strtolower( str_replace( ' ', '-', $font['f'] ) ) );
			$fonts[$id] = $font;
		}

		return $fonts;
	}

}

new ThemeRain_Fonts();
