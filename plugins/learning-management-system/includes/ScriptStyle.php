<?php

/**
 * Manages scripts and styles.
 *
 * @package Masteriyo
 *
 * @since 1.0.0
 */

namespace Masteriyo;

use Masteriyo\Constants;
use Masteriyo\Query\CourseCategoryQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Manages scripts and styles.
 *
 * @class Masteriyo\ScriptStyle
 */

class ScriptStyle {

	/**
	 * Scripts.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $scripts = array();

	/**
	 * Styles.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $styles = array();

	/**
	 * Localized scripts.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $localized_scripts = array();

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		 self::init_hooks();
		self::init_scripts();
		self::init_styles();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function init_hooks() {
		add_action( 'init', array( __CLASS__, 'after_wp_init' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_public_scripts_styles' ), PHP_INT_MAX - 10 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_admin_scripts_styles' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_public_localized_scripts' ), PHP_INT_MAX - 9 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_admin_localized_scripts' ) );
		add_action( 'current_screen', array( __CLASS__, 'override_wp_private_apis_script' ) );

		// Remove third party styles from learn page.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'remove_styles_from_learn_page' ), PHP_INT_MAX );
	}


	/**
	 * Override wp private apis script.
	 *
	 * @since 1.7.3
	 */
	public static function override_wp_private_apis_script() {
		if ( 'toplevel_page_masteriyo' === get_current_screen()->id && version_compare( get_bloginfo( 'version' ), '6.4', '>=' ) ) {
			$suffix = SCRIPT_DEBUG ? '.js' : '.min.js';
			wp_deregister_script( 'wp-private-apis' );
			wp_register_script( 'wp-private-apis', plugins_url( 'libs/private-apis' . $suffix, MASTERIYO_PLUGIN_FILE ), array(), MASTERIYO_VERSION, true );
		}
	}

	/**
	 * Initialization after WordPress is initialized.
	 *
	 * @since 1.3.0
	 */
	public static function after_wp_init() {
		self::register_block_scripts_and_styles();
		self::localize_block_scripts();
	}

	/**
	 * Get application version.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private static function get_version() {
		 return Constants::get( 'MASTERIYO_VERSION' );
	}

	/**
	 * Get asset name suffix.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.35
	 *
	 * @return array
	 */
	public static function get_asset_suffix() {
		 $version = Constants::get( 'MASTERIYO_VERSION' );

		if ( Constants::is_true( 'SCRIPT_DEBUG' ) ) {
			return ".{$version}";
		}
		return ".{$version}.min";
	}

	/**
	 * Get asset dependencies.
	 *
	 * @since 1.4.1
	 *
	 * @param string $asset_name
	 *
	 * @return array
	 */
	public static function get_asset_deps( $asset_name ) {
		$asset_filepath = Constants::get( 'MASTERIYO_PLUGIN_DIR' ) . "/assets/js/build/{$asset_name}.asset.php";

		if ( ! file_exists( $asset_filepath ) || ! is_readable( $asset_filepath ) ) {
			return array();
		}
		$asset = (array) require $asset_filepath;

		return masteriyo_array_get( $asset, 'dependencies', array() );
	}

	/**
	 * Initialize the scripts.`
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private static function init_scripts() {
		$account_src               = self::get_asset_url( '/assets/js/build/masteriyo-account.js' );
		$backend_src               = self::get_asset_url( '/assets/js/build/masteriyo-backend.js' );
		$learn_src                 = self::get_asset_url( '/assets/js/build/masteriyo-interactive.js' );
		$single_course_src         = self::get_asset_url( '/assets/js/build/single-course.js' );
		$courses_src               = self::get_asset_url( '/assets/js/build/courses.js' );
		$admin_src                 = self::get_asset_url( '/assets/js/build/admin.js' );
		$login_form_src            = self::get_asset_url( '/assets/js/build/login-form.js' );
		$checkout_src              = self::get_asset_url( '/assets/js/build/checkout.js' );
		$ask_review_src            = self::get_asset_url( '/assets/js/build/ask-review.js' );
		$jquery_block_ui_src       = self::get_asset_url( '/assets/js/build/jquery-block-ui.js' );
		$ask_usage_tracking_src    = self::get_asset_url( '/assets/js/build/usage-tracking.js' );
		$deactivation_feedback_src = self::get_asset_url( '/assets/js/build/deactivation-feedback.js' );

		if ( masteriyo_is_development() ) {
			$account_src               = 'http://localhost:3000/dist/account.js';
			$backend_src               = 'http://localhost:3000/dist/backend.js';
			$learn_src                 = 'http://localhost:3000/dist/interactive.js';
			$single_course_src         = self::get_asset_url( '/assets/js/frontend/single-course.js' );
			$courses_src               = self::get_asset_url( '/assets/js/frontend/courses.js' );
			$admin_src                 = self::get_asset_url( '/assets/js/admin/admin.js' );
			$login_form_src            = self::get_asset_url( '/assets/js/frontend/login-form.js' );
			$checkout_src              = self::get_asset_url( '/assets/js/frontend/checkout.js' );
			$ask_review_src            = self::get_asset_url( '/assets/js/frontend/ask-review.js' );
			$jquery_block_ui_src       = self::get_asset_url( '/assets/js/frontend/jquery-block-ui.js' );
			$ask_usage_tracking_src    = self::get_asset_url( '/assets/js/frontend/usage-tracking.js' );
			$deactivation_feedback_src = self::get_asset_url( '/assets/js/admin/deactivation-feedback.js' );
		}

		/**
		 * Filters the scripts.
		 *
		 * @since 1.0.0
		 *
		 * @param array $scripts List of scripts.
		 */
		self::$scripts = apply_filters(
			'masteriyo_enqueue_scripts',
			array(
				'dependencies'          => array(
					'src'      => self::get_asset_url( '/assets/js/build/masteriyo-dependencies.js' ),
					'context'  => array( 'admin', 'public' ),
					'callback' => function () {
						return masteriyo_is_production() && ( masteriyo_is_admin_page() || masteriyo_is_learn_page() || ( is_user_logged_in() && masteriyo_is_account_page() ) );
					},
				),
				'blocks'                => array(
					'src'           => self::get_asset_url( '/assets/js/build/blocks.js' ),
					'context'       => 'blocks',
					'deps'          => array_merge( self::get_asset_deps( 'blocks' ), array( 'jquery', 'wp-dom-ready', 'wp-hooks', 'wp-keyboard-shortcuts' ) ),
					'register_only' => true,
				),
				'admin'                 => array(
					'src'      => $admin_src,
					'deps'     => array_merge( self::get_asset_deps( 'masteriyo-backend' ), array( 'wp-core-data', 'wp-components', 'wp-element', 'wp-editor', 'wp-rich-text', 'wp-format-library' ) ),
					'context'  => 'admin',
					'callback' => 'masteriyo_is_admin_page',
				),
				'backend'               => array(
					'src'      => $backend_src,
					'deps'     => array_merge( self::get_asset_deps( 'masteriyo-backend' ), array( 'wp-core-data', 'wp-components', 'wp-element', 'wp-editor', 'wp-rich-text', 'wp-format-library' ) ),
					'context'  => 'admin',
					'callback' => 'masteriyo_is_admin_page',
				),
				'single-course'         => array(
					'src'      => $single_course_src,
					'deps'     => array( 'jquery' ),
					'context'  => 'public',
					'callback' => function () {
						return masteriyo_is_single_course_page() || isset( $_GET['masteriyo-load-single-course-js'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					},
				),
				'courses'               => array(
					'src'      => $courses_src,
					'deps'     => array( 'jquery' ),
					'context'  => 'public',
					'callback' => function () {
						return masteriyo_is_courses_page() || isset( $_GET['masteriyo-load-courses-js'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					},
				),
				'account'               => array(
					'src'      => $account_src,
					'deps'     => array_merge( self::get_asset_deps( 'masteriyo-backend' ), array( 'wp-core-data', 'wp-components', 'wp-element' ) ),
					'version'  => self::get_version(),
					'context'  => 'public',
					'callback' => function () {
						return is_user_logged_in() && masteriyo_is_account_page();
					},
				),
				'login-form'            => array(
					'src'      => $login_form_src,
					'deps'     => array( 'jquery' ),
					'version'  => self::get_version(),
					'context'  => 'public',
					'callback' => function () {
						return masteriyo_is_load_login_form_assets() ||
							( ! is_user_logged_in() && masteriyo_get_setting( 'single_course.display.course_visibility' ) );
					},
				),
				'checkout'              => array(
					'src'      => $checkout_src,
					'deps'     => array( 'jquery', 'masteriyo-jquery-block-ui' ),
					'version'  => self::get_version(),
					'context'  => 'public',
					'callback' => 'masteriyo_is_checkout_page',
				),
				'ask-review'            => array(
					'src'      => $ask_review_src,
					'deps'     => array( 'jquery' ),
					'version'  => self::get_version(),
					'context'  => 'admin',
					'callback' => 'masteriyo_is_show_review_notice',
				),
				'learn'                 => array(
					'src'      => $learn_src,
					'deps'     => array_merge( self::get_asset_deps( 'masteriyo-interactive' ), array( 'wp-data', 'wp-core-data', 'wp-components', 'wp-element' ) ),
					'version'  => self::get_version(),
					'context'  => 'public',
					'callback' => 'masteriyo_is_learn_page',
				),
				'jquery-block-ui'       => array(
					'src'      => $jquery_block_ui_src,
					'version'  => self::get_version(),
					'context'  => 'public',
					'callback' => 'masteriyo_is_checkout_page',
				),
				'ask-usage-tracking'    => array(
					'src'      => $ask_usage_tracking_src,
					'deps'     => array( 'jquery' ),
					'version'  => self::get_version(),
					'context'  => 'admin',
					'callback' => function () {
						return masteriyo_show_usage_tracking_notice();
					},
				),
				'deactivation-feedback' => array(
					'src'      => $deactivation_feedback_src,
					'deps'     => array( 'jquery' ),
					'version'  => self::get_version(),
					'context'  => 'admin',
					'callback' => function () {
						$screen = get_current_screen();

						return $screen && in_array( $screen->id, array( 'plugins', 'plugins-network' ), true );
					},
				),
			)
		);
	}

	/**
	 * Initialize the styles.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private static function init_styles() {
		/**
		 * Filters the styles.
		 *
		 * @since 1.0.0
		 *
		 * @param array $styles List of styles.
		 */
		self::$styles = apply_filters(
			'masteriyo_enqueue_styles',
			array(
				'public'                => array(
					'src'     => self::get_asset_url( '/assets/css/public.css' ),
					'has_rtl' => false,
					'context' => 'public',
				),
				'dependencies'          => array(
					'src'     => self::get_asset_url( '/assets/js/build/masteriyo-dependencies.css' ),
					'has_rtl' => false,
					'context' => 'admin',
				),
				'block'                 => array(
					'src'      => self::get_asset_url( '/assets/css/block.css' ),
					'has_rtl'  => false,
					'context'  => 'admin',
					'callback' => function () {
						$screen = get_current_screen();

						return $screen && ( $screen->is_block_editor() || 'customize' === $screen->id );
					},
				),
				'review-notice'         => array(
					'src'      => self::get_asset_url( '/assets/css/review-notice.css' ),
					'has_rtl'  => false,
					'context'  => 'admin',
					'callback' => 'masteriyo_is_show_review_notice',
				),
				'allow-usage-notice'    => array(
					'src'      => self::get_asset_url( '/assets/css/allow-usage-notice.css' ),
					'has_rtl'  => false,
					'context'  => 'admin',
					'callback' => function () {
						return masteriyo_show_usage_tracking_notice();
					},
				),
				'deactivation-feedback' => array(
					'src'      => self::get_asset_url( '/assets/css/deactivation-feedback.css' ),
					'has_rtl'  => false,
					'context'  => 'admin',
					'callback' => function () {
						$screen = get_current_screen();

						return $screen && in_array( $screen->id, array( 'plugins', 'plugins-network' ), true );
					},
				),
			)
		);
	}

	/**
	 * Get styles according to context.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Style/Script context (admin, public  none, etc.)
	 *
	 * @return array
	 */
	public static function get_styles( $context ) {
		// Set default values.
		$styles = array_map(
			function ( $style ) {
				return array_replace_recursive( self::get_default_style_options(), $style );
			},
			self::$styles
		);

		// Filter according to admin or public static context.
		$styles = array_filter(
			$styles,
			function ( $style ) use ( $context ) {
				return in_array( $context, (array) $style['context'], true );
			}
		);

		return $styles;
	}

	/**
	 * Get scripts.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Script context. (admin, public,static  none).
	 *
	 * @return array
	 */
	public static function get_scripts( $context ) {
		// Set default values.
		$scripts = array_map(
			function ( $script ) {
				return array_replace_recursive( self::get_default_script_options(), $script );
			},
			self::$scripts
		);

		// Filter according to admin or public static context.
		$scripts = array_filter(
			$scripts,
			function ( $script ) use ( $context ) {
				return in_array( $context, (array) $script['context'], true );
			}
		);

		return $scripts;
	}

	/**
	 * Default script options.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_default_script_options() {
		/**
		 * Filters the default options for a script.
		 *
		 * @since 1.0.0
		 *
		 * @param array $options The default options.
		 */
		return apply_filters(
			'masteriyo_get_default_script_options',
			array(
				'src'           => '',
				'deps'          => array( 'jquery' ),
				'version'       => self::get_version(),
				'context'       => 'none',
				'in_footer'     => true,
				'register_only' => false,
				'callback'      => '',
			)
		);
	}

	/**
	 * Default style options.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_default_style_options() {
		/**
		 * Filters the default options for a style.
		 *
		 * @since 1.0.0
		 *
		 * @param array $options The default options.
		 */
		return apply_filters(
			'masteriyo_get_default_style_options',
			array(
				'src'           => '',
				'deps'          => array(),
				'version'       => self::get_version(),
				'media'         => 'all',
				'has_rtl'       => false,
				'context'       => array( 'none' ),
				'in_footer'     => true,
				'register_only' => false,
				'callback'      => '',
			)
		);
	}

	/**
	 * Return asset URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Assets path.
	 *
	 * @return string
	 */
	private static function get_asset_url( $path ) {
		/**
		 * Filters asset URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The asset URL.
		 * @param string $path The relative path to the plugin directory.
		 */
		return apply_filters( 'masteriyo_get_asset_url', plugins_url( $path, Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ), $path );
	}

	/**
	 * Register a script for use.
	 *
	 * @since 1.0.0
	 *
	 * @uses   wp_register_script()
	 * @param  string   $handle    Name of the script. Should be unique.
	 * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param  string[] $deps      An array of registered script handles this script depends on.
	 * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 */
	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = '', $in_footer = true ) {
		wp_register_script( "masteriyo-{$handle}", $path, $deps, $version, $in_footer );
	}

	/**
	 * Register and enqueue a script for use.
	 *
	 * @since 1.0.0
	 *
	 * @uses   wp_enqueue_script()
	 * @param  string   $handle    Name of the script. Should be unique.
	 * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param  string[] $deps      An array of registered script handles this script depends on.
	 * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 */
	private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = '', $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
			wp_register_script( "masteriyo-{$handle}", $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( "masteriyo-{$handle}" );
	}

	/**
	 * Register a style for use.
	 *
	 *
	 * @since 1.0.0
	 *
	 * @uses   wp_register_style()
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '( orientation: portrait )' and '( max-width: 640px )'.
	 * @param  boolean  $has_rtl If has RTL version to load too.
	 */
	private static function register_style( $handle, $path, $deps = array(), $version = '', $media = 'all', $has_rtl = false ) {
		if ( ! isset( self::$styles[ $handle ] ) ) {
			self::$styles[ $handle ] = array(
				'src'     => $path,
				'deps'    => $deps,
				'version' => $version,
				'media'   => $media,
				'has_rtl' => $has_rtl,
			);
		}
		wp_register_style( "masteriyo-{$handle}", $path, $deps, $version, $media );

		if ( $has_rtl ) {
			wp_style_add_data( "masteriyo-{$handle}", 'rtl', 'replace' );
		}
	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @since 1.0.0
	 *
	 * @uses   wp_enqueue_style()
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '( orientation: portrait )' and '( max-width: 640px )'.
	 * @param  boolean  $has_rtl If has RTL version to load too.
	 */
	private static function enqueue_style( $handle, $path = '', $deps = array(), $version = '', $media = 'all', $has_rtl = false ) {
		if ( ! in_array( $handle, self::$styles, true ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media, $has_rtl );
		}
		wp_enqueue_style( "masteriyo-{$handle}" );
	}

	/**
	 * Load public static scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public static function load_public_scripts_styles() {
		$scripts = self::get_scripts( 'public' );
		$styles  = self::get_styles( 'public' );

		foreach ( $scripts as $handle => $script ) {
			if ( true === (bool) $script['register_only'] ) {
				self::register_script( $handle, $script['src'], $script['deps'], $script['version'] );
				continue;
			}

			if ( empty( $script['callback'] ) ) {
				self::enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			} elseif ( is_callable( $script['callback'] ) && call_user_func_array( $script['callback'], array() ) ) {
				self::enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			}
		}

		foreach ( $styles as $handle => $style ) {
			if ( true === (bool) $style['register_only'] ) {
				self::register_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
				continue;
			}

			if ( empty( $style['callback'] ) ) {
				self::enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			} elseif ( is_callable( $style['callback'] ) && call_user_func_array( $style['callback'], array() ) ) {
				self::enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			}
		}

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'masteriyo-learn', 'masteriyo', Constants::get( 'MASTERIYO_LANGUAGES' ) );
			wp_set_script_translations( 'masteriyo-account', 'masteriyo', Constants::get( 'MASTERIYO_LANGUAGES' ) );
		}

		self::load_custom_inline_styles();
		self::load_block_styles();

		// Load dashicons in frontend.
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Load inline custom colors.
	 *
	 * @since 1.0.4
	 */
	public static function load_custom_inline_styles() {
		$primary_color = masteriyo_get_setting( 'general.styling.primary_color' );

		// Bail early if the primary color is not set.
		if ( empty( trim( $primary_color ) ) ) {
			return;
		}

		$primary_light = masteriyo_color_luminance( $primary_color, 0.3 );
		$primary_dark  = masteriyo_color_luminance( $primary_color, -0.05 );

		$custom_css = "
			:root {
				--masteriyo-color-primary: ${primary_color};
				--masteriyo-color-primary-light: ${primary_light};
				--masteriyo-color-primary-dark: ${primary_dark};
				--masteriyo-color-btn-blue-hover: ${primary_light};
			}
		";
		wp_add_inline_style( 'masteriyo-public', $custom_css );

		// Fixes adminbar issue on learn page. @see https://wordpress.org/support/topic/course-lesson-page-mobile-responsiveness/
		$custom_css = '
			@media screen and (max-width: 600px){
				.masteriyo-interactive-page #wpadminbar {
					position: fixed;
				}
			}
		';
		wp_add_inline_style( 'admin-bar', $custom_css );

		// Fixes adminbar issue on user account page.
		$custom_css = '
			@media screen and (max-width: 600px){
				.masteriyo-account-page #wpadminbar {
					position: fixed;
				}
			}
		';
		wp_add_inline_style( 'admin-bar', $custom_css );
	}

	/**
	 * Load custom inline styles on admin page.
	 *
	 * @since 1.5.0
	 *
	 * @return void
	 */
	public static function load_custom_admin_inline_styles() {
		if ( ! current_user_can( 'manage_masteriyo_settings' ) ) {
			return;
		}

		$custom_css = '
				#toplevel_page_masteriyo ul.wp-submenu li:last-child a {
						color: #27e527 !important;
				}';

		wp_add_inline_style( 'masteriyo-dependencies', $custom_css );
	}

	/**
	 * Load block styles.
	 *
	 * @since 1.3.0
	 */
	public static function load_block_styles() {
		$post_id  = get_the_ID();
		$settings = masteriyo_get_settings();

		wp_add_inline_style( 'masteriyo-public', $settings->get( 'general.widgets_css' ) );

		if ( empty( $post_id ) ) {
			return;
		}
		$css = get_post_meta( $post_id, '_masteriyo_css', true );

		if ( empty( $css ) ) {
			return;
		}
		wp_add_inline_style( 'masteriyo-public', $css );
	}

	/**
	 * Register block scripts and styles.
	 *
	 * @since 1.3.0
	 */
	public static function register_block_scripts_and_styles() {
		global $pagenow;

		if ( ( is_admin() && 'widgets.php' === $pagenow ) ) {
			return;
		}

		$scripts = self::get_scripts( 'blocks' );
		$styles  = self::get_styles( 'blocks' );

		foreach ( $scripts as $handle => $script ) {
			if ( true === (bool) $script['register_only'] ) {
				self::register_script( $handle, $script['src'], $script['deps'], $script['version'] );
				continue;
			}

			if ( empty( $script['callback'] ) ) {
				self::enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			} elseif ( is_callable( $script['callback'] ) && call_user_func_array( $script['callback'], array() ) ) {
				self::enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			}
		}

		foreach ( $styles as $handle => $style ) {
			if ( true === (bool) $style['register_only'] ) {
				self::register_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
				continue;
			}

			if ( empty( $style['callback'] ) ) {
				self::enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			} elseif ( is_callable( $style['callback'] ) && call_user_func_array( $style['callback'], array() ) ) {
				self::enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			}
		}

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'masteriyo-blocks', 'masteriyo', Constants::get( 'MASTERIYO_LANGUAGES' ) );
		}
	}

	/**
	 * Localize block scripts.
	 *
	 * @since 1.3.0
	 */
	public static function localize_block_scripts() {
		global $pagenow;
		$args       = array(
			'order'   => 'ASC',
			'orderby' => 'name',
			'number'  => '',
		);
		$query      = new CourseCategoryQuery( $args );
		$categories = $query->get_categories();

		/**
		 * Filters the localized gutenberg block scripts.
		 *
		 * @since 1.3.0
		 *
		 * @param array $scripts The localized scripts.
		 */
		self::$localized_scripts = apply_filters(
			'masteriyo_localized_block_scripts',
			array(
				'blocks' => array(
					'name' => '_MASTERIYO_BLOCKS_DATA_',
					'data' => array(
						'categories'      => array_map(
							function ( $category ) {
								return $category->get_data();
							},
							$categories
						),
						'isWidgetsEditor' => 'widgets.php' === $pagenow ? 'yes' : 'no',
						'isCustomizer'    => 'customize.php' === $pagenow ? 'yes' : 'no',
					),
				),
			)
		);

		foreach ( self::$localized_scripts as $handle => $script ) {
			\wp_localize_script( "masteriyo-{$handle}", $script['name'], $script['data'] );
		}
	}

	/**
	 * Load public static scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public static function load_admin_scripts_styles() {
		$scripts = self::get_scripts( 'admin' );
		$styles  = self::get_styles( 'admin' );

		global $post;

		if ( masteriyo_is_admin_page() ) {
			wp_enqueue_style( 'wp-edit-post' );
			wp_enqueue_style( 'wp-format-library' );
			wp_tinymce_inline_scripts();

			wp_add_inline_style( 'wp-edit-post', 'html.wp-toolbar { background-color: #F7FAFC; }' );
			wp_add_inline_script(
				'wp-blocks',
				sprintf( 'wp.blocks.setCategories( %s );', wp_json_encode( get_block_categories( $post ) ) ),
				'after'
			);
		}

		foreach ( $scripts as $handle => $script ) {
			if ( true === (bool) $script['register_only'] ) {
				self::register_script( $handle, $script['src'], $script['deps'], $script['version'] );
				continue;
			}

			if ( empty( $script['callback'] ) ) {
				self::enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			} elseif ( is_callable( $script['callback'] ) && call_user_func_array( $script['callback'], array() ) ) {
				self::enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			}
		}

		foreach ( $styles as $handle => $style ) {
			if ( true === (bool) $style['register_only'] ) {
				self::register_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
				continue;
			}

			if ( empty( $style['callback'] ) ) {
				self::enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			} elseif ( is_callable( $style['callback'] ) && call_user_func_array( $style['callback'], array() ) ) {
				self::enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			}
		}

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'masteriyo-admin', 'masteriyo', Constants::get( 'MASTERIYO_LANGUAGES' ) );
			wp_set_script_translations( 'masteriyo-backend', 'masteriyo', Constants::get( 'MASTERIYO_LANGUAGES' ) );
		}
		self::load_custom_admin_inline_styles();
	}

	/**
	 * Load admin localized scripts.
	 *
	 * @since 1.0.0
	 */
	public static function load_admin_localized_scripts() {
		 $courses_page = get_post( masteriyo_get_page_id( 'courses' ) );
		$courses_slug  = ! is_null( $courses_page ) ? $courses_page->post_name : '';

		$account_page = get_post( masteriyo_get_page_id( 'account' ) );
		$account_slug = ! is_null( $account_page ) ? $account_page->post_name : '';

		$checkout_page = get_post( masteriyo_get_page_id( 'checkout' ) );
		$checkout_slug = ! is_null( $checkout_page ) ? $checkout_page->post_name : '';

		$user = masteriyo_get_current_user();

		/**
		 * Filters the localized admin scripts.
		 *
		 * @since 1.0.0
		 *
		 * @param array $scripts The localized scripts.
		 */
		self::$localized_scripts = apply_filters(
			'masteriyo_localized_admin_scripts',
			array(
				'backend'               => array(
					'name' => '_MASTERIYO_',
					'data' => array(
						'rootApiUrl'                => esc_url_raw( untrailingslashit( rest_url() ) ),
						'nonce'                     => wp_create_nonce( 'wp_rest' ),
						'review_notice_nonce'       => wp_create_nonce( 'masteriyo_review_notice_nonce' ),
						'allow_usage_notice_nonce'  => wp_create_nonce( 'masteriyo_allow_usage_notice_nonce' ),
						'ajax_url'                  => admin_url( 'admin-ajax.php' ),
						'home_url'                  => home_url(),
						'pageSlugs'                 => array(
							'courses'  => $courses_slug,
							'account'  => $account_slug,
							'checkout' => $checkout_slug,
						),
						'currency'                  => array(
							'code'     => masteriyo_get_currency(),
							'symbol'   => html_entity_decode( masteriyo_get_currency_symbol( masteriyo_get_currency() ) ),
							'position' => masteriyo_get_setting( 'payments.currency.currency_position' ),
						),
						'imageSizes'                => get_intermediate_image_sizes(),
						'countries'                 => array_map( 'html_entity_decode', masteriyo( 'countries' )->get_countries() ),
						'states'                    => array_filter( masteriyo( 'countries' )->get_states() ),
						'show_review_notice'        => masteriyo_bool_to_string( masteriyo_is_show_review_notice() ),
						'show_allow_usage_notice'   => masteriyo_bool_to_string( masteriyo_show_usage_tracking_notice() ),
						'total_posts'               => count_user_posts( get_current_user_id() ),
						'settings'                  => masteriyo_get_setting( 'general' ),
						'current_user'              => $user ? masteriyo_array_except( $user->get_data(), array( 'password' ) ) : null,
						'canDeleteCourseCategories' => masteriyo_bool_to_string( current_user_can( 'delete_course_categories' ) ),
						'isOpenAIKeyFound'          => masteriyo_bool_to_string( masteriyo_get_setting( 'advance.openai.api_key' ) ? true : false ),
						'singleCourseTemplates'     => array(),
						'courseArchiveTemplates'    => array(),
						'onBoardingPageUrl'         => admin_url( 'index.php?page=masteriyo-onboard' ),
						'isCurrentUserAdmin'        => masteriyo_bool_to_string( masteriyo_is_current_user_admin() ),
						'editorStyles'              => function_exists( 'get_block_editor_theme_styles' ) && masteriyo_is_admin_page() ? get_block_editor_theme_styles() : (object) array(),
						'editorSettings'            => function_exists( 'get_block_editor_settings' ) && masteriyo_is_admin_page() ? get_block_editor_settings( array(), new \WP_Block_Editor_Context() ) : (object) array(),
						'defaultEditor'             => masteriyo_get_setting( 'general.editor.default_editor' ),
					),
				),
				'ask-review'            => array(
					'name' => '_MASTERIYO_ASK_REVIEW_DATA_',
					'data' => array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'masteriyo_review_notice_nonce' ),
					),
				),
				'ask-usage-tracking'    => array(
					'name' => '_MASTERIYO_ASK_ALLOW_USAGE_DATA_',
					'data' => array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'masteriyo_allow_usage_notice_nonce' ),
					),
				),
				'deactivation-feedback' => array(
					'name' => '_MASTERIYO_DEACTIVATION_FEEDBACK_DATA_',
					'data' => array(
						'ajax_url'       => admin_url( 'admin-ajax.php' ),
						'error_messages' => array(
							'select_at_least_one' => 'Please select at least one option from the list.',
						),
					),
				),
			)
		);

		foreach ( self::$localized_scripts as $handle => $script ) {
			\wp_localize_script( "masteriyo-{$handle}", $script['name'], $script['data'] );
		}
	}

	/**
	 * Load public static localized scripts.
	 *
	 * @since 1.0.0
	 */
	public static function load_public_localized_scripts() {
		/**
		 * Filters the localized public scripts.
		 *
		 * @since 1.0.0
		 *
		 * @param array $scripts The localized scripts.
		 */
		self::$localized_scripts = apply_filters(
			'masteriyo_localized_public_scripts',
			array(
				'account'       => array(
					'name' => '_MASTERIYO_',
					'data' => array(
						'rootApiUrl'              => esc_url_raw( untrailingslashit( rest_url() ) ),
						'current_user_id'         => get_current_user_id(),
						'nonce'                   => wp_create_nonce( 'wp_rest' ),
						'labels'                  => array(
							'save'                   => __( 'Save', 'masteriyo' ),
							'saving'                 => __( 'Saving...', 'masteriyo' ),
							'profile_update_success' => __( 'Your profile was updated successfully.', 'masteriyo' ),
						),
						'currency'                => array(
							'code'     => masteriyo_get_currency(),
							'symbol'   => html_entity_decode( masteriyo_get_currency_symbol( masteriyo_get_currency() ) ),
							'position' => masteriyo_get_setting( 'payments.currency.currency_position' ),
						),
						'urls'                    => array(
							'logout'       => wp_logout_url( get_home_url() ),
							'account'      => masteriyo_get_page_permalink( 'account' ),
							'courses'      => masteriyo_get_page_permalink( 'courses' ),
							'home'         => home_url(),
							'myCourses'    => admin_url( 'admin.php?page=masteriyo#/courses' ),
							'addNewCourse' => admin_url( 'admin.php?page=masteriyo#/courses/add-new-course' ),
							'webhooks'     => admin_url( 'admin.php?page=masteriyo#/webhooks' ),
						),
						'isCurrentUserStudent'    => masteriyo_bool_to_string( masteriyo_is_current_user_student() ),
						'isCurrentUserInstructor' => masteriyo_bool_to_string( masteriyo_is_current_user_instructor() ),
						'isInstructorActive'      => masteriyo_bool_to_string( masteriyo_is_instructor_active() ),
						'isUserEmailVerified'     => masteriyo_bool_to_string( masteriyo_is_user_email_verified() ),
						'settings'                => masteriyo_get_setting( 'general' ),
						'pagesVisibility'         => masteriyo_get_setting( 'accounts_page' ),
						'isCurrentUserAdmin'      => masteriyo_bool_to_string( masteriyo_is_current_user_admin() ),
						'PasswordProtectedNonce'  => wp_create_nonce( 'masteriyo_course_password_protected_nonce' ),
						'ajaxUrl'                 => admin_url( 'admin-ajax.php' ),
					),
				),
				'login-form'    => array(
					'name' => '_MASTERIYO_',
					'data' => array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'masteriyo_login_nonce' ),
						'labels'   => array(
							'sign_in'    => __( 'Sign In', 'masteriyo' ),
							'signing_in' => __( 'Signing In...', 'masteriyo' ),
						),
					),
				),
				'single-course' => array(
					'name' => 'masteriyo_data',
					'data' => array(
						'rootApiUrl'               => esc_url_raw( rest_url() ),
						'nonce'                    => wp_create_nonce( 'wp_rest' ),
						'password_protected_nonce' => wp_create_nonce( 'masteriyo_course_password_protected_nonce' ),
						'reviews_listing_nonce'    => wp_create_nonce( 'masteriyo_course_reviews_infinite_loading_nonce' ),
						'rating_indicator_markup'  => masteriyo_get_rating_indicators_markup( 'masteriyo-rating-input-icon' ),
						'max_course_rating'        => masteriyo_get_max_course_rating(),
						'review_deleted_notice'    => masteriyo_get_template_html( 'notices/review-deleted.php' ),
						'retake_url'               => ( isset( $GLOBALS['course'] ) && is_a( $GLOBALS['course'], '\Masteriyo\Models\Course' ) ) ? $GLOBALS['course']->get_retake_url() : '',
						'labels'                   => array(
							'type_confirm'             => __( 'Type CONFIRM to proceed.', 'masteriyo' ),
							'try_again'                => __( 'Try again', 'masteriyo' ),
							'submit'                   => __( 'Submit', 'masteriyo' ),
							'update'                   => __( 'Update', 'masteriyo' ),
							'delete'                   => __( 'Delete', 'masteriyo' ),
							'submitting'               => __( 'Submitting...', 'masteriyo' ),
							'deleting'                 => __( 'Deleting...', 'masteriyo' ),
							'reply_to'                 => __( 'Reply to', 'masteriyo' ),
							'edit_reply'               => __( 'Edit reply', 'masteriyo' ),
							'edit_review'              => __( 'Edit review', 'masteriyo' ),
							'submit_success'           => __( 'Submitted successfully.', 'masteriyo' ),
							'update_success'           => __( 'Updated successfully.', 'masteriyo' ),
							'delete_success'           => __( 'Deleted successfully.', 'masteriyo' ),
							'expand_all'               => __( 'Expand All', 'masteriyo' ),
							'collapse_all'             => __( 'Collapse All', 'masteriyo' ),
							'loading'                  => __( 'Loading...', 'masteriyo' ),
							'load_more_reviews_failed' => __( 'Failed to load more reviews', 'masteriyo' ),
							'see_more_reviews'         => __( 'See more reviews', 'masteriyo' ),
							'password_not_empty'       => __( 'Please enter a password.', 'masteriyo' ),
						),
						'ajaxURL'                  => admin_url( 'admin-ajax.php' ),
						'course_id'                => ( isset( $GLOBALS['course'] ) && is_a( $GLOBALS['course'], '\Masteriyo\Models\Course' ) ) ? $GLOBALS['course']->get_id() : 0,
						'course_review_pages'      => isset( $GLOBALS['course'] ) ? masteriyo_get_course_reviews_infinite_loading_pages_count( $GLOBALS['course'] ) : 0,
					),
				),
				'checkout'      => array(
					'name' => '_MASTERIYO_CHECKOUT_',
					'data' => array(
						'ajaxURL'             => admin_url( 'admin-ajax.php' ),
						'checkoutURL'         => add_query_arg( array( 'action' => 'masteriyo_checkout' ), admin_url( 'admin-ajax.php' ) ),
						'i18n_checkout_error' => esc_html__( 'Error processing checkout. Please try again.', 'masteriyo' ),
						'is_checkout'         => true,
						'mto_ajax_url'        => '/?masteriyo-ajax=%%endpoint%%',
						'countries'           => array_map( 'html_entity_decode', masteriyo( 'countries' )->get_countries() ),
						'states'              => array_filter( masteriyo( 'countries' )->get_states() ),
					),
				),
				'learn'         => array(
					'name' => '_MASTERIYO_',
					'data' => array(
						'rootApiUrl'                   => esc_url_raw( rest_url() ),
						'nonce'                        => wp_create_nonce( 'wp_rest' ),
						'urls'                         => array(
							'logout'  => wp_logout_url( get_home_url() ),
							'account' => masteriyo_get_page_permalink( 'account' ),
							'courses' => masteriyo_get_page_permalink( 'courses' ),
							'home'    => home_url(),
						),
						'logo'                         => masteriyo_get_setting( 'learn_page.general.logo_id' ) ? masteriyo_get_learn_page_logo_data() : masteriyo_get_custom_logo_data(),
						'siteTitle'                    => get_bloginfo( 'name' ),
						'userAvatar'                   => is_user_logged_in() ? masteriyo_get_current_user()->profile_image_url() : '',
						'qaEnable'                     => masteriyo_get_setting( 'learn_page.display.enable_questions_answers' ),
						'isUserLoggedIn'               => is_user_logged_in(),
						'settings'                     => masteriyo_get_setting( 'general' ),
						'current_user_id'              => get_current_user_id(),
						'autoLoadNextContent'          => masteriyo_bool_to_string( masteriyo_get_setting( 'learn_page.general.auto_load_next_content' ) ),
						'showCompleteQuizButtonOnFail' => masteriyo_bool_to_string( masteriyo_get_setting( 'quiz.display.quiz_completion_button' ) ),
						'quizReviewButtonVisibility'   => masteriyo_get_setting( 'quiz.display.quiz_review_visibility' ),
						'quizAccess'                   => masteriyo_get_setting( 'quiz.general.quiz_access' ),
						'showSidebarInitially'         => masteriyo_bool_to_string( masteriyo_get_setting( 'learn_page.display.show_sidebar_initially' ) ),
					),
				),
				'courses'       => array(
					'name' => 'masteriyo_data',
					'data' => array(
						'ajaxURL'                  => admin_url( 'admin-ajax.php' ),
						'password_protected_nonce' => wp_create_nonce( 'masteriyo_course_password_protected_nonce' ),
						'labels'                   => array(
							'password_not_empty' => __( 'Please enter a password.', 'masteriyo' ),
						),
					),
				),

			)
		);
		foreach ( self::$localized_scripts as $handle => $script ) {
			\wp_localize_script( "masteriyo-{$handle}", $script['name'], $script['data'] );
		}
	}

	/**
	 * Remove styles in learn page.
	 *
	 * @since 1.0.0
	 * @since 1.5.37 Renamed from 'remove_styles_scripts_in_learn_page' to 'remove_styles_from_learn_page'
	 *
	 * @return void
	 */
	public static function remove_styles_from_learn_page() {
		global $wp_styles;

		// Bail early if the page is not learn.
		if ( ! masteriyo_is_learn_page() ) {
			return;
		}

		$whitelist = self::get_whitelist_styles_in_learn_page();

		// Dequeue blacklist styles
		foreach ( $wp_styles->registered as $style ) {
			if ( ! in_array( $style->handle, $whitelist, true ) ) {
				wp_deregister_style( $style->handle );
			}
		}

		foreach ( $wp_styles->queue as $handle ) {
			if ( ! in_array( $handle, $whitelist, true ) ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	/**
	 * Get the list of whitelist styles in learn page.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_whitelist_styles_in_learn_page() {
		/**
		  * Filters the whitelisted styles for learn page.
		  *
		  * @since 1.5.32
		  *
		  * @param array $styles List of style handles.
		  */
		return array_unique(
			apply_filters(
				'masteriyo_whitelist_styles_learn_page',
				array(
					'masteriyo-learn',
					'masteriyo-dependencies',
					'colors',
					'common',
					'forms',
					'admin-menu',
					'dashboard',
					'list-tables',
					'edit',
					'revisions',
					'media',
					'themes',
					'about',
					'nav-menus',
					'widgets',
					'site-icon',
					'l10n',
					'code-editor',
					'site-health',
					'wp-admin',
					'login',
					'install',
					'wp-color-picker',
					'customize-controls',
					'customize-widgets',
					'customize-nav-menus',
					'buttons',
					'dashicons',
					'admin-bar',
					'wp-auth-check',
					'editor-buttons',
					'mediea-views',
					'wp-pointer',
					'customize-preview',
					'wp-embed-template-ie',
					'imgareaselect',
					'wp-jquery-ui-dialog',
					'mediaelement',
					'wp-mediaelement',
					'thickbox',
					'wp-codemirror',
					'deprecated-media',
					'farbtastic',
					'jcrop',
					'colors-fresh',
					'open-sans',
					'wp-editor-font',
					'wp-block-library-theme',
					'wp-edit-blocks',
					'wp-block-editor',
					'wp-block-library',
					'wp-block-directory',
					'wp-components',
					'wp-editor',
					'wp-format-library',
					'wp-list-resuable-blocks',
					'wp-nux',
					'wp-block-library-theme',
					'wp-block-library',
					// Support translatepress plugin language switch floater.
					'trp-language-switcher-style',
					'trp-floater-language-switcher-style',
					'everest-forms-general',
					'user-registration-general',
					'contact-form-7',
					'query-monitor',
				)
			)
		);
	}
}
