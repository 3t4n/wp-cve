<?php
/**
 * Loads the Google Fonts used in Woofunnels blocks in the frontend.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'BWF_Google_Fonts' ) ) {
	#[AllowDynamicProperties]

  class BWF_Google_Fonts {

		public static $google_fonts = [];

		function __construct() {
			add_filter( 'render_block', array( $this, 'gather_google_fonts' ), 10, 2 );
			add_action( 'wp_footer', array( $this, 'enqueue_frontend_block_fonts' ) );
		}

		public function gather_google_fonts( $block_content, $block ) {

			/** return if admin panel */
			if ( is_admin() ) {
				return $block_content;
			}

			if ( $this->is_woofunnel_block( $block['blockName'] ) && is_array( $block['attrs'] ) ) {
				foreach ( $block['attrs'] as $attr_name => $font_name ) {
					if ( preg_match( '/font$/i', $attr_name ) ) {
						if ( isset( $font_name['desktop'] ) && isset( $font_name['desktop']['family'] ) && ! empty( $font_name['desktop']['family'] ) ) {
							self::register_font( $font_name['desktop']['family'] );
						}
						if ( isset( $font_name['tablet'] ) && isset( $font_name['tablet']['family'] ) && ! empty( $font_name['tablet']['family'] ) ) {
							self::register_font( $font_name['tablet']['family'] );
						}
						if ( isset( $font_name['mobile'] ) && isset( $font_name['mobile']['family'] ) && ! empty( $font_name['mobile']['family'] ) ) {
							self::register_font( $font_name['mobile']['family'] );
						}
					}
				}
			}

			return $block_content;
		}

		public function enqueue_frontend_block_fonts() {
			self::enqueue_google_fonts( array_unique( self::$google_fonts ) );
		}

		public static function is_web_font( $font_name ) {
			return ! in_array( strtolower( $font_name ), [ 'serif', 'sans-serif', 'monospace', 'serif-alt' ], true );
		}

		public static function is_system_font( $font_name ) {
			$standard_font = file_exists( 'standard-fonts.php' ) ? include 'standard-fonts.php' : null;
			if ( $standard_font ) {
				foreach ( $standard_font as $value ) {
					if ( strtolower( $font_name ) === strtolower( $value['value'] ) ) {
						return true;
					}
				}
			}

			return false;
		}

		public function is_woofunnel_block( $block_name ) {
			if ( is_null( $block_name ) ) {
				return false;
			}

			return strpos( $block_name, 'bwfblocks/' ) === 0 || strpos( $block_name, 'sling-block/' ) === 0;
		}

		public static function register_font( $font_name ) {
			if ( ! self::is_web_font( $font_name ) ) {
				return;
			}
			if ( self::is_system_font( $font_name ) ) {
				return;
			}

			if ( ! in_array( $font_name, self::$google_fonts, true ) ) {
				// Allow themes to disable enqueuing fonts, helpful for custom fonts.
				if ( apply_filters( 'bwfblock_enqueue_font', true, $font_name ) ) {
					self::$google_fonts[] = $font_name;
				}
			}
		}

		/**
		 * Based on: https://github.com/elementor/elementor/blob/bc251b81afb626c4c47029aea8a762566524a811/includes/frontend.php#L647
		 */
		public static function enqueue_google_fonts( $google_fonts, $handle = 'bwfblock-google-fonts' ) {
			global $post;
			$default_font = get_post_meta( $post->ID, 'bwfblock_default_font', true );

			if ( ! count( $google_fonts ) && empty( $default_font ) ) {
				return;
			}

			// Add default in block font set
			if ( ! empty( $default_font ) ) {
				$google_fonts[] = $default_font;
				$google_fonts   = array_unique( $google_fonts );
			}

			foreach ( $google_fonts as &$font ) {

				if ( ! empty( $font ) ) {
					$font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
				}
			}

			$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $google_fonts ) );

			$subsets = [
				'ru_RU' => 'cyrillic',
				'bg_BG' => 'cyrillic',
				'he_IL' => 'hebrew',
				'el'    => 'greek',
				'vi'    => 'vietnamese',
				'uk'    => 'cyrillic',
				'cs_CZ' => 'latin-ext',
				'ro_RO' => 'latin-ext',
				'pl_PL' => 'latin-ext',
			];

			$locale = get_locale();
			if ( isset( $subsets[ $locale ] ) ) {
				$fonts_url .= '&subset=' . $subsets[ $locale ];
			}

			wp_enqueue_style( $handle, $fonts_url ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

			if ( ! empty( $default_font ) ) {
				$default_font_selector = apply_filters( 'bwfblock_default_font_selector', 'body, body *' );
				$default_font_css      = "$default_font_selector{font-family:$default_font;}";

				wp_add_inline_style( $handle, $default_font_css );
			}
		}

	}

	new BWF_Google_Fonts();
}
