<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Utility service class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Register utility service.
 */
class Utility extends Base implements Service {
	use EmptyServiceInit;

	/**
	 * Recursively merge two arrays.
	 *
	 * @param array $a Array to merge with $defaults.
	 * @param array $b Array that serves as the defaults.
	 *
	 * @return array
	 */
	public function parse_args_r( $a, $b ) {
		if ( ! is_array( $a ) ) {
			$a = array();
		}

		if ( ! is_array( $b ) ) {
			$b = array();
		}

		foreach ( $a as $k => $v ) {
			if ( is_array( $v ) && isset( $b[ $k ] ) ) {
				$b[ $k ] = $this->parse_args_r( $v, $b[ $k ] );
			} else {
				$b[ $k ] = $v;
			}
		}

		return $b;
	}

	/**
	 * Enqueue styles and scripts based on options.
	 *
	 * @param array $options Options.
	 */
	public function enqueue( $options ) {
		$advanced = darkmodetg( 'options' )->get_main( 'advanced' );

		$key = 'front';

		$css = '';

		if ( $advanced['save'] && $options['fix_flick'] ) {
			$css .= ( 'html{opacity:1}html.dmtg-fade{opacity:0;background:' . esc_attr( $options['flick_bg_color'] ) . '}' );

			wp_register_script( 'darkmodetg-fade', '', array(), false, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NoExplicitVersion
			wp_enqueue_script( 'darkmodetg-fade' );
			wp_add_inline_script( 'darkmodetg-fade', '("true"===window.localStorage.darkmode)&&document.documentElement.classList.add("dmtg-fade");' );
		}

		if ( ! $advanced[ $key ]['override'] ) {
			$css .= '.darkmode--activated embed,.darkmode--activated iframe,.darkmode--activated img,.darkmode--activated video{filter:invert(100%)}.darkmode--activated embed:fullscreen,.darkmode--activated iframe:fullscreen,.darkmode--activated video:fullscreen{filter:invert(0%)}';

			$css .= '.darkmode--activated [style*="background-image: url"],.darkmode--activated [style*="background-image:url"]{filter:invert(100%)}';

			$css_cover = '.darkmode--activated .wp-block-cover[style*="background-image: url"] .wp-block-cover[style*="background-image: url"],.darkmode--activated .wp-block-cover[style*="background-image: url"] .wp-block-cover[style*="background-image:url"],.darkmode--activated .wp-block-cover[style*="background-image: url"] embed,.darkmode--activated .wp-block-cover[style*="background-image: url"] figure[class*=wp-duotone-],.darkmode--activated .wp-block-cover[style*="background-image: url"] iframe,.darkmode--activated .wp-block-cover[style*="background-image: url"] img,.darkmode--activated .wp-block-cover[style*="background-image: url"] video,.darkmode--activated .wp-block-cover[style*="background-image:url"] .wp-block-cover[style*="background-image: url"],.darkmode--activated .wp-block-cover[style*="background-image:url"] .wp-block-cover[style*="background-image:url"],.darkmode--activated .wp-block-cover[style*="background-image:url"] embed,.darkmode--activated .wp-block-cover[style*="background-image:url"] figure[class*=wp-duotone-],.darkmode--activated .wp-block-cover[style*="background-image:url"] iframe,.darkmode--activated .wp-block-cover[style*="background-image:url"] img,.darkmode--activated .wp-block-cover[style*="background-image:url"] video{filter:invert(0)}.darkmode--activated figure[class*=wp-duotone-]{filter:invert(1)}';

			$css .= apply_filters( 'darkmodetg_css_cover', $css_cover );

			if ( ( 'front' === $key ) && $options['skip_bg_img'] ) {
				$css .= 'body.custom-background.darkmode--activated .darkmode-background{background:#fff;mix-blend-mode:difference}';
			}

			if ( defined( 'STSSM_PLUGIN_VERSION' ) ) {
				$css .= '.darkmode--activated .stssm-social-icons{filter:invert(100%)}';
			}

			$css .= '.darkmode--activated .dmt-filter-1{filter:invert(1)!important}.darkmode--activated .dmt-filter-0{filter:invert(0)!important}';
		}

		if ( $advanced['hide_m'] ) {
			$css  = ( '@media (min-width:641px){' . $css . '}' );
			$css .= '@media (max-width:640px){.darkmode-toggle,.darkmode-layer,.darkmode-background{display:none!important}}';
		}

		if ( '' !== $css ) {
			// Add inline style for dynamic CSS.
			wp_register_style( 'darkmodetg', false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_enqueue_style( 'darkmodetg' );
			wp_add_inline_style( 'darkmodetg', apply_filters( 'darkmodetg_css', $css ) );
		}

		$in_footer = ! $options['in_head'];

		// Darkmode.js script.
		wp_register_script( 'darkmode-js', ( $this->plugin_url . 'assets/js/darkmode-js.min.js' ), array(), $this->plugin_ver, $in_footer );
		wp_enqueue_script( 'darkmode-js' );

		// Darkmode.js init script.
		wp_register_script( 'darkmodetg', ( $this->plugin_url . 'assets/js/dmtg.min.js' ), array( 'darkmode-js' ), $this->plugin_ver, $in_footer );
		wp_enqueue_script( 'darkmodetg' );

		$unset = 'unset';
		if ( 'bl' === $options['pos'] ) {
			$options['pos_b'] = ( $options['pos_b'] . 'px' );
			$options['pos_l'] = ( $options['pos_l'] . 'px' );
			$options['pos_t'] = $unset;
			$options['pos_r'] = $unset;
		} elseif ( 'br' === $options['pos'] ) {
			$options['pos_b'] = ( $options['pos_b'] . 'px' );
			$options['pos_l'] = $unset;
			$options['pos_t'] = $unset;
			$options['pos_r'] = ( $options['pos_r'] . 'px' );
		} elseif ( 'tl' === $options['pos'] ) {
			$options['pos_b'] = $unset;
			$options['pos_l'] = ( $options['pos_l'] . 'px' );
			$options['pos_t'] = ( $options['pos_t'] . 'px' );
			$options['pos_r'] = $unset;
		} else {
			$options['pos_b'] = $unset;
			$options['pos_l'] = $unset;
			$options['pos_t'] = ( $options['pos_t'] . 'px' );
			$options['pos_r'] = ( $options['pos_r'] . 'px' );
		}

		$options['width']    = ( $options['width'] . 'px' );
		$options['height']   = ( $options['height'] . 'px' );
		$options['border_r'] = ( $options['border_r'] . 'px' );

		$advanced['time'] = ( $advanced['time'] . 's' );

		wp_localize_script(
			'darkmodetg',
			'darkmodetg',
			array(
				'config' => apply_filters(
					'darkmodetg_config',
					array(
						'bottom'            => esc_attr( $options['pos_b'] ),
						'left'              => esc_attr( $options['pos_l'] ),
						'top'               => esc_attr( $options['pos_t'] ),
						'right'             => esc_attr( $options['pos_r'] ),
						'width'             => esc_attr( $options['width'] ),
						'height'            => esc_attr( $options['height'] ),
						'borderRadius'      => esc_attr( $options['border_r'] ),
						'fontSize'          => '16px',
						'time'              => esc_attr( $advanced['time'] ),
						'backgroundColor'   => 'transparent',
						'buttonColorDark'   => '#333333',
						'buttonColorLight'  => '#b3b3b3',
						'buttonColorTDark'  => '#ffffff',
						'buttonColorTLight' => '#000000',
						'saveInCookies'     => esc_attr( $advanced['save'] ),
						'fixFlick'          => esc_attr( $options['fix_flick'] ),
						'label'             => esc_attr( \wp_encode_emoji( '🌓' ) ),
						'autoMatchOsTheme'  => false,
						'buttonAriaLabel'   => esc_attr__( 'Toggle dark mode', 'dark-mode-toggle' ),
						'overrideStyles'    => esc_attr( $advanced[ $key ]['override'] ),
					),
					$key,
					$options,
					$advanced
				),
			)
		);
	}

	/**
	 * Get pro plugin buy link.
	 *
	 * @return string
	 */
	public function pro_buy() {
		return 'https://scriptstown.com/account/signup/dark-mode-toggle-pro';
	}

	/**
	 * Get pro plugin detail link.
	 *
	 * @return string
	 */
	public function pro_detail() {
		return 'https://scriptstown.com/wordpress-plugins/dark-mode-toggle-pro/';
	}
}
