<?php

// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Borderless_Elementor_Assets' ) ) {
	
	class Borderless_Elementor_Assets {

		private static $instance = null;

		public function init() {

			add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_styles' ) );

			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'icon_font_styles' ) );
			add_action( 'elementor/preview/enqueue_styles',      array( $this, 'icon_font_styles' ) );
		
		}

		
		public function register_styles() {

			// Start Temp Style
			wp_register_style( 
				'borderless-elementor-style', 
				BORDERLESS__STYLES . 'elementor.min.css', 
				false, 
				BORDERLESS__VERSION
			);
			// End Temp Style

			wp_register_style( 
				'elementor-widget-animated-text', 
				BORDERLESS__STYLES . 'elementor/elementor-widget-animated-text.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'elementor-widget-hero', 
				BORDERLESS__STYLES . 'elementor/elementor-widget-hero.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'elementor-widget-portfolio',
				BORDERLESS__STYLES . 'elementor/elementor-widget-portfolio.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'elementor-widget-slider',
				BORDERLESS__STYLES . 'elementor/elementor-widget-slider.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'elementor-widget-split-hero', 
				BORDERLESS__STYLES . 'elementor/elementor-widget-split-hero.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'borderless-elementor-flickity-style',
				BORDERLESS__LIB . 'flickity/flickity.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'borderless-elementor-flickity-fullscreen-style',
				BORDERLESS__LIB . 'flickity/flickity-fullscreen.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style( 
				'borderless-elementor-flickity-fade-style',
				BORDERLESS__LIB . 'flickity/flickity-fade.css', 
				false, 
				BORDERLESS__VERSION
			);

			wp_register_style(
				'font-awesome-5',
				ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
				false,
				BORDERLESS__VERSION
			);

		}


		public function register_scripts() {

			wp_register_script(
				'borderless-elementor-appear-script',
				BORDERLESS__LIB . 'appear.js', [ 'elementor-frontend' ], 
				'1.0.0', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-flickity-script', 
				BORDERLESS__LIB . 'flickity/flickity.js', [ 'elementor-frontend' ], 
				'2.2.2', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-flickity-fullscreen-script', 
				BORDERLESS__LIB . 'flickity/flickity-fullscreen.js', [ 'elementor-frontend' ], 
				'1.1.1', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-flickity-fade-script', 
				BORDERLESS__LIB . 'flickity/flickity-fade.js', [ 'elementor-frontend' ], 
				'1.0.0', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-flickity-as-nav-for-script', 
				BORDERLESS__LIB . 'flickity/flickity-as-nav-for.js', [ 'elementor-frontend' ], 
				'2.0.2', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-isotope-script', 
				BORDERLESS__LIB . 'isotope.js', [ 'elementor-frontend' ], 
				'3.0.6', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-marquee-script', 
				BORDERLESS__LIB . 'marquee.js', [ 'elementor-frontend' ], 
				'1.5.2', 
				true 
			);

			wp_register_script(
				'borderless-elementor-progressbar-script',
				BORDERLESS__LIB . 'progressbar.js', [ 'elementor-frontend' ], 
				'1.1.0', 
				true 
			);

			wp_register_script( 
				'borderless-elementor-typewriterjs-script', 
				BORDERLESS__LIB . 'typewriterjs.js', [ 'elementor-frontend' ], 
				'2.18.0', 
				true 
			);

		}

		public function enqueue_scripts() {

			wp_enqueue_script(
				'borderless-elementor-script',
				BORDERLESS__SCRIPTS . 'borderless-elementor.min.js', [ 'elementor-frontend' ], 
				BORDERLESS__VERSION, 
				true 
			);
		}

		public function icon_font_styles() {
			
			wp_enqueue_style(
				'borderless-icon-font',
				BORDERLESS__STYLES . 'borderless-icon-font.css', 
				false, 
				BORDERLESS__VERSION
			);

		}

		public static function get_instance() {
			
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
		
	}
	
}

function borderless_elementor_assets() {
	return Borderless_Elementor_Assets::get_instance();
}