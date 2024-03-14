<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarStyle' ) ) {

	class WpssoRarStyle {

		private $p;	// Wpsso class object.
		private $a;	// WpssoRar class object.
		private $doing_dev = false;
		private $file_ext  = 'min.css';
		private $version   = '';

		/*
		 * Instantiated by WpssoRar->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->doing_dev = SucomUtilWP::doing_dev();
			$this->file_ext  = $this->doing_dev ? 'css' : 'min.css';
			$this->version   = WpssoRarConfig::get_version() . ( $this->doing_dev ? gmdate( '-ymd-His' ) : '' );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}

		public function enqueue_styles() {

			if ( ! $post_id = get_the_ID() ) {

				return;

			} elseif ( ! WpssoRarComment::is_rating_enabled( $post_id ) ) {

				return;
			}

			$sel_color = $this->p->options[ 'rar_star_color_selected' ];
			$def_color = $this->p->options[ 'rar_star_color_default' ];

			wp_enqueue_style( 'wpsso-rar-style',
				WPSSORAR_URLPATH . 'css/style.' . $this->file_ext,
					array(), $this->version );

			$custom_style_css = '
				@font-face {
					font-family:"WpssoStar";
					font-weight:normal;
					font-style:normal;
					src: url("' . WPSSO_URLPATH . 'fonts/star.eot?' . $this->version . '");
					src: url("' . WPSSO_URLPATH . 'fonts/star.eot?' . $this->version . '#iefix") format("embedded-opentype"),
						url("' . WPSSO_URLPATH . 'fonts/star.woff?' . $this->version . '") format("woff"),
						url("' . WPSSO_URLPATH . 'fonts/star.ttf?' . $this->version . '") format("truetype"),
						url("' . WPSSO_URLPATH . 'fonts/star.svg?' . $this->version . '#star") format("svg");
				}
				.wpsso-rar .star-rating::before { color:' . $def_color . '; }
				.wpsso-rar .star-rating span::before { color:' . $sel_color . '; }
				.wpsso-rar p.select-star a::before { color:' . $def_color . '; }
				.wpsso-rar p.select-star a:hover ~ a::before { color:' . $def_color . '; }
				.wpsso-rar p.select-star:hover a::before { color:' . $sel_color . '; }
				.wpsso-rar p.select-star.selected a.active::before { color:' . $sel_color . '; }
				.wpsso-rar p.select-star.selected a.active ~ a::before { color:' . $def_color . '; }
				.wpsso-rar p.select-star.selected a:not(.active)::before { color:' . $sel_color . '; }
			';

			$custom_style_css = SucomUtil::minify_css( $custom_style_css, $filter_prefix = 'wpsso' );

			wp_add_inline_style( 'wpsso-rar-style', $custom_style_css );
		}
	}
}
