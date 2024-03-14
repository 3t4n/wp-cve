<?php
/*
Plugin Name: FlowCharts.ai: AI-Powered Website Chat Bot, Widget Surveys, Forms, Questionnaires, Decision Trees, Workflow, Support & Text Message from Website Visitors
Description: Transform your website with FlowCharts.ai – the ultimate tool to effortlessly craft dynamic Surveys, Forms, Decision Trees, Workflows, and more. Engage visitors with an AI-powered Chat Bot & Widget, and extend conversations via SMS, email, or even two-way texting even after they've left your site. Stay connected, stay responsive!
Version:     1.5
Author:      FlowCharts.ai
Author URI:  https://www.flowcharts.ai
Text Domain: flowcharts-ai
Domain Path: /languages
License:     GPLv2 or later
*/

defined( 'ABSPATH' ) or die;

define( 'FLOWCHARTS_AI_FILE', __FILE__ );
define( 'FLOWCHARTS_AI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'FLOWCHARTS_AI_VER', '1.2' );
define( 'FLOWCHARTS_AI_IMG_URL', plugins_url( 'assets/images/', __FILE__ ) );

if ( ! class_exists( 'FlowCharts_AI' ) ) {
	class FlowCharts_AI {
		private $optsgroup_name = 'flowcharts_ai_optsgroup';
		private $options_name = 'flowcharts_ai_options';

		public static function get_instance() {
			if ( self::$instance == null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		private static $instance = null;

		private function __clone() { }

		private function __wakeup() { }

		private function __construct() {
			// Properties
			$this->pagehook = null;
			$this->options = null;
			$this->allowed_html = array(
				'script' => array(
					'src' => array(),
					'async' => array(),
					'defer' => array(),
					'integrity' => array()
				),
				'style' => array(
					'rel' => array(),
					'href' => array(),
					'integrity' => array()
				),
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'p' => array(),
				'div' => array(),
				'span' => array()
			);

			// WP Actions
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
			add_action( 'wp_footer', array( $this, 'add_snippet' ) );

			// Shortcodes
			add_shortcode( 'flowcharts', array( $this, 'output_shortcode' ) );
		}

		public function init() {
			load_plugin_textdomain( 'flowcharts-ai', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function register_settings() {
			register_setting( $this->optsgroup_name, $this->options_name );
		}

		public function add_menu_item() {
			$this->pagehook = add_menu_page(
				__( 'FlowCharts.ai', 'flowcharts-ai' ),
				__( 'FlowCharts', 'flowcharts-ai' ),
				'manage_options',
				'flowcharts-ai',
				array( $this, 'render_options_page' )
			);
			add_action( 'load-' . $this->pagehook, array( $this, 'add_mb' ) );
		}

		public function add_mb() {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
			add_meta_box(
				'flowcharts-metabox-1',
				__( 'www.FlowCharts.ai Features & Benefits', 'flowcharts-ai' ),
				array( $this, 'mb1_content'),
				$this->pagehook . '-1',
				'normal',
				'core'
			);
			add_meta_box(
				'flowcharts-metabox-2',
				__( 'Sign Up to FlowCharts?', 'flowcharts-ai' ),
				array( $this, 'mb2_content'),
				$this->pagehook . '-2',
				'normal',
				'core'
			);
			add_meta_box(
				'flowcharts-metabox-3',
				__( 'Show us some Love', 'flowcharts-ai' ) . '<span class="heart"></span>',
				array( $this, 'mb3_content'),
				$this->pagehook . '-3',
				'normal',
				'core'
			);
		}

		public function mb1_content( $data ) {
			require( __DIR__ . '/metabox1.php' );
		}

		public function mb2_content( $data ) {
			require( __DIR__ . '/metabox2.php' );
		}

		public function mb3_content( $data ) {
			require( __DIR__ . '/metabox3.php' );
		}

		public function render_options_page() {
			require( __DIR__ . '/options.php' );
		}

		public function add_snippet() {

			$snippet = trim( $this->get_option( 'snippet' ) );
			if ( $snippet == '' ) return;

			$snippet;

			$location = ( int ) $this->get_option( 'location' );

			if ( $location == 0 && ( is_page() || is_single() || is_front_page() || is_home() ) ) echo wp_kses( $snippet, $this->allowed_html );

			if ( $location == 1 && ( is_page() || is_front_page() || is_home() ) ) echo wp_kses( $snippet, $this->allowed_html );

			if ( $location == 2 && ( is_single() || is_front_page() || is_home() ) ) echo wp_kses( $snippet, $this->allowed_html );

			if ( $location == 3 && ( is_front_page() || is_home() ) ) echo wp_kses( $snippet, $this->allowed_html );

			if ( $location == 4 && ( is_page() && ! is_front_page() && ! is_home() ) ) echo wp_kses( $snippet, $this->allowed_html );

			if ( $location == 5 && ( ! is_page() && ( is_single() || ! is_front_page() || ! is_home() ) ) ) echo wp_kses( $snippet, $this->allowed_html );

			if ( $location == 6 ) return;
		}

		public function output_shortcode( $atts, $content, $tag ) {
			$snippet = trim( $this->get_option( 'snippet' ) );
			if ( $snippet == '' ) return '';

			return $snippet;
		}

		private function get_option( $option_name, $default = '' ) {
			if ( is_null( $this->options ) ) $this->options = ( array ) get_option( $this->options_name, array() );
			if ( isset( $this->options[$option_name] ) ) return $this->options[$option_name];
			return $default;
		}

		private function escape_html( $string ) {
			return wp_kses( $string, array(
				'script' => array(
					'src' => array(),
					'async' => array(),
					'defer' => array(),
					'integrity' => array()
				),
				'style' => array(
					'rel' => array(),
					'href' => array(),
					'integrity' => array()
				),
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'p' => array(),
				'div' => array(),
				'span' => array()
			) );
		}
	}
}
FlowCharts_AI::get_instance();