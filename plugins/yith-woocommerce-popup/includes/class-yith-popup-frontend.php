<?php
/**
 * Frontend class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Popup
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YPOP_INIT' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'YITH_Popup_Frontend' ) ) {
	/**
	 * YITH_Popup_Frontend class
	 *
	 * @since 1.0.0
	 */
	class YITH_Popup_Frontend {
		/**
		 * Single instance of the class
		 *
		 * @var YITH_Popup_Frontend
		 * @since 1.0.0
		 */
		protected static $instance;


		/**
		 * The name of the cookie never_show_again for newsletter popup preferences.
		 *
		 * @var string $never_show_cookie_name
		 */
		public $never_show_again_cookie_name = '';

		/**
		 * The name of the cookie show_next_time for newsletter popup preferences
		 *
		 * @var string
		 */
		public $show_next_time_cookie_name = '';

		/**
		 * Current post
		 *
		 * @var int
		 */
		private $current_post = 0;

		/**
		 * Current popup
		 *
		 * @var int
		 */
		private $current_popup = 0;


		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_Popup_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Constructor.
		 *
		 * @return YITH_Popup_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'template_redirect', array( $this, 'init' ) );

			// custom styles and javascripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
		}


		/**
		 * Init function
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function init() {
			$post_id = $this->get_current_post();
			$this->get_current_popup();
			$post_type = get_post_type( $this->current_popup );

			$this->never_show_again_cookie_name = 'ypopup-hide-popup-forever-' . YITH_Popup()->get_option( 'ypop_cookie_var' ) . '-' . $this->current_popup;
			$this->show_next_time_cookie_name   = 'ypopup-hide-popup-' . YITH_Popup()->get_option( 'ypop_cookie_var' ) . '-' . $this->current_popup;

			$enabled = YITH_Popup()->get_option( 'ypop_enable' );

			if ( $enabled && wp_is_mobile() ) {
				$enabled = YITH_Popup()->get_option( 'ypop_enable_in_mobile' );
			}

			if ( 'yes' === $enabled && ! isset( $_COOKIE[ $this->never_show_again_cookie_name ] ) &&
				(
					YITH_Popup()->get_option( 'ypop_hide_policy' ) === 'always' ||
					! isset( $_COOKIE[ $this->show_next_time_cookie_name ] )
				)
				&&
				( YITH_Popup()->post_type_name === $post_type || YITH_Popup()->get_option( 'ypop_enabled_everywhere' ) === 'yes' || (
					is_array( YITH_Popup()->get_option( 'ypop_popup_pages' ) ) &&
					in_array( $post_id, YITH_Popup()->get_option( 'ypop_popup_pages' ) ) //phpcs:ignore
				) ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_popup_styles_scripts' ), 11 );
				add_action( 'wp_footer', array( $this, 'get_popup_template' ) );

			}

		}


		/**
		 * Enqueue Scripts and Styles
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function enqueue_styles_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'ypop_cookie', YITH_YPOP_ASSETS_URL . '/js/jquery.cookie' . $suffix . '.js', array( 'jquery' ), YITH_YPOP_VERSION, false );
			wp_enqueue_script( 'ypop_popup', YITH_YPOP_ASSETS_URL . '/js/jquery.yitpopup' . $suffix . '.js', array( 'jquery' ), YITH_YPOP_VERSION, false );
			wp_enqueue_style( 'ypop_frontend', YITH_YPOP_ASSETS_URL . '/css/frontend.css', false, YITH_YPOP_VERSION );
		}

		/**
		 * Enqueue Scripts and Styles
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function enqueue_popup_styles_scripts() {

			$popup = $this->current_popup;

			$enabled   = (int) get_post_meta( $popup, '_enable_popup', true );
			$enabled   = 1 === $enabled ? 'yes' : 'no';
			$post_type = get_post_type( $popup );

			if ( 'yes' !== $enabled || YITH_Popup()->post_type_name !== $post_type ) {
				return;
			}

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'ypop_frontend', YITH_YPOP_ASSETS_URL . '/js/frontend' . $suffix . '.js', array( 'jquery' ), YITH_YPOP_VERSION, false );

			$expired = YITH_Popup()->get_option( 'ypop_hide_days' );

			$position           = get_post_meta( $popup, '_position', true );
			$leave_page_message = get_post_meta( $popup, '_leave_page_message', true );

			wp_localize_script(
				'ypop_frontend',
				'ypop_frontend_var',
				array(
					'never_show_again_cookie_name' => $this->never_show_again_cookie_name,
					'show_next_time_cookie_name'   => $this->show_next_time_cookie_name,
					'expired'                      => $expired,
					'leave_page_message'           => $leave_page_message,
					'ismobile'                     => wp_is_mobile(),
				)
			);

			$css      = get_post_meta( $popup, '_ypop_css', true );
			$js       = get_post_meta( $popup, '_ypop_javascript', true );
			$template = get_post_meta( $popup, '_template_name', true );
			$css_file = $this->get_popup_template_url( $template, 'css/style.css' );

			if ( $css_file ) {
				wp_enqueue_style( "ypop_{$template}", $css_file, false, YITH_YPOP_VERSION );
				if ( ! empty( $css ) ) {
					wp_add_inline_style( "ypop_{$template}", $css );
				}
			}

			wp_enqueue_style( 'font-awesome' );
			if ( ! empty( $js ) ) {
				wc_enqueue_js( $js );
			}
		}



		/**
		 * Return the popup template of the current page
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function get_popup_template() {
			$popup_id = $this->get_current_popup();

			$popup    = get_post( $popup_id );
			$template = '/themes/' . get_post_meta( $popup_id, '_template_name', true );

			$template_path = $this->get_popup_template_path( $template, 'markup.php' );
			if ( $template_path ) {
				$hiding_text = YITH_Popup()->get_option( 'ypop_hide_text' );
				include $template_path;
			};

		}

		/**
		 * Returns the url of the template for popup
		 *
		 * @param string $folder .
		 * @param string $file .
		 *
		 * @return string
		 * @since  1.0
		 */
		public function get_popup_template_path( $folder, $file ) {
			$plugin_url   = YITH_YPOP_TEMPLATE_PATH . "{$folder}/{$file}";
			$template_url = ( ( defined( 'YIT' ) ) ? YIT_THEME_TEMPLATES_PATH : get_template_directory() ) . "/ypop{$folder}/{$file}";
			$child_url    = ( ( defined( 'YIT' ) ) ? str_replace( get_template_directory(), get_stylesheet_directory(), YIT_THEME_TEMPLATES_PATH ) : get_stylesheet_directory() ) . "/ypop{$folder}/{$file}";

			foreach ( array( 'child_url', 'template_url', 'plugin_url' ) as $var ) {
				if ( file_exists( ${$var} ) ) {
					return ${$var};
				}
			}

			return false;
		}

		/**
		 * Returns the url of the template for popup
		 *
		 * @param string $template .
		 * @param string $file .
		 *
		 * @return string
		 * @since 1.0
		 */
		private function get_popup_template_url( $template, $file ) {
			$plugin_path   = YITH_YPOP_TEMPLATE_PATH . "/themes/{$template}/{$file}";
			$template_path = ( ( defined( 'YIT' ) ) ? YIT_THEME_TEMPLATES_PATH : get_template_directory() ) . "/ypop/themes/{$template}/{$file}";
			$child_path    = ( ( defined( 'YIT' ) ) ? str_replace( get_template_directory(), get_stylesheet_directory(), YIT_THEME_TEMPLATES_PATH ) : get_stylesheet_directory() ) . "/ypop/themes/{$template}/{$file}";

			$plugin_url   = YITH_YPOP_TEMPLATE_URL . "/themes/{$template}/{$file}";
			$template_url = ( ( defined( 'YIT' ) ) ? YITH_YPOP_TEMPLATE_URL : get_template_directory_uri() ) . "/ypop/themes/{$template}/{$file}";
			$child_url    = ( ( defined( 'YIT' ) ) ? str_replace( get_template_directory_uri(), get_stylesheet_directory_uri(), YIT_THEME_TEMPLATES_PATH ) : get_stylesheet_directory_uri() ) . "/ypop/themes/{$template}/{$file}";

			foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
				if ( file_exists( ${$var} ) ) {
					$url = str_replace( 'path', 'url', $var );
					return ${$url};
				}
			}

			return false;
		}

		/**
		 * Return the current popup
		 */
		public function get_current_popup() {
			$everywhere = ( YITH_Popup()->get_option( 'ypop_enabled_everywhere' ) === 'yes' ) ? true : false;
			$pages      = array();
			if ( ! $everywhere ) {
				$pages = YITH_Popup()->get_option( 'ypop_popup_pages' );
			}

			$default_popup = YITH_Popup()->get_option( 'ypop_popup_default' );

			if ( $this->current_post ) {
				$welcome   = get_post_meta( $this->current_post, '_welcome_popup', true );
				$post_type = get_post_type( $this->current_post );

				// for preview.
				if ( YITH_Popup()->post_type_name === $post_type ) {
					$this->current_popup = $this->current_post;
					return $this->current_popup;
				}

				if ( $everywhere ) {
					if ( 'disable' === $welcome ) {
						$this->current_popup = 0;
					} elseif ( 'default' !== $welcome || '' === $welcome ) {
						$this->current_popup = $default_popup;
					} else {
						$this->current_popup = $welcome;
					}
				} else {
					if ( ! empty( $pages ) && is_array( $pages ) && in_array( $this->current_post, $pages ) ) { //phpcs:ignore
						if ( 'default' !== $welcome || '' !== $welcome ) {
							$this->current_popup = $default_popup;
						} else {
							$this->current_popup = $welcome;
						}
					} else {
						if ( 'default' !== $welcome && '' !== $welcome ) {
							$this->current_popup = $welcome;
						} else {
							$this->current_popup = 0;
						}
					}
				}
			} else {
				$this->current_popup = $default_popup;
			}

			return $this->current_popup;
		}

		/**
		 * Return the current post
		 *
		 * @return int|mixed|void
		 */
		public function get_current_post() {
			global $wp_query;
			$post = $wp_query->get_queried_object();

			if ( function_exists( 'WC' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
				$post_id = wc_get_page_id( 'shop' );
			} elseif ( ! empty( $post ) ) {
				$post_id = $post->ID;
			} else {
				$post_id = 0;
			}

			$this->current_post = $post_id;

			return $this->current_post;
		}
	}

	/**
	 * Unique access to instance of YITH_Popup_Frontend class
	 *
	 * @return YITH_Popup_Frontend
	 */
	function YITH_Popup_Frontend() { //phpcs:ignore
		return YITH_Popup_Frontend::get_instance();
	}
}
