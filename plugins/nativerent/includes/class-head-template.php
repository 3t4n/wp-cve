<?php
/**
 * Class for rendering head integration template.
 *
 * @package nativerent;
 */

namespace NativeRent;

use WP_Rocket\Plugin;

use function class_exists;
use function defined;
use function esc_url_raw;
use function getenv;
use function in_array;
use function is_array;
use function json_encode;
use function plugins_url;
use function preg_quote;
use function preg_replace;
use function rawurlencode;
use function sprintf;
use function str_replace;
use function strpos;
use function time;
use function wp_unslash;

use const NATIVERENT_PLUGIN_FILE;

defined( 'ABSPATH' ) || exit;

/**
 * Class Head_Template
 */
class Head_Template {
	/**
	 * Default URL to static files
	 *
	 * @var string
	 */
	private static $default_static_host = 'https://static.nativerent.ru';

	/**
	 * Path to nativerent JS file
	 *
	 * @var string
	 */
	private static $default_js_path = '/js/codes/nativerent.v2.js';

	/**
	 * Path to NTGB script.
	 *
	 * @var string
	 */
	private static $ntgb_js_path = '/js/codes/ntgb.v1.js';

	/**
	 * Head Integration Mark
	 *
	 * @var string
	 */
	private static $integration_class = 'nativerent-integration-head';

	/**
	 * Some data-attributes for skipping 3rd-party optimizations.
	 *
	 * @note data-no-optimize — especially for Lightspeed Cache plugin and WP Rocket.
	 * @note data-skip-moving - WP Fastest Cache optimization skip.
	 * @var string
	 */
	private static $script_data_props = 'data-no-optimize="1" data-skip-moving="true"';

	/**
	 * Current site UUID.
	 *
	 * @var string
	 */
	protected $site_id;

	/**
	 * Monetization instance.
	 *
	 * @var Monetizations
	 */
	protected $monetizations;

	/**
	 * Current ad-units config.
	 *
	 * @var array{regular?: array, ntgb?: array}
	 */
	protected $units_config;

	/**
	 * Constructor.
	 *
	 * @param  string                               $site_id        Site ID.
	 * @param  Monetizations                        $monetizations  Monetizations instance.
	 * @param  array{regular?: array, ntgb?: array} $units_config   Current ad-units config.
	 */
	public function __construct(
		$site_id,
		Monetizations $monetizations,
		$units_config = array()
	) {
		$this->site_id       = $site_id;
		$this->monetizations = $monetizations;
		$this->units_config  = is_array( $units_config )
			? $this->units_config_filtration( $units_config )
			: array();
	}

	/**
	 * Default static host getter.
	 *
	 * @return string
	 */
	public static function get_static_host() {
		$static_host = getenv( 'NATIVERENT_STATIC_HOST' );
		if ( ! empty( $static_host ) ) {
			return esc_url_raw( wp_unslash( $static_host ) );
		}

		return self::$default_static_host;
	}

	/**
	 * Get full URL to nativerent script.
	 *
	 * @return string
	 */
	public static function get_main_js_url() {
		return self::get_static_host() . self::$default_js_path;
	}

	/**
	 * Get full URL to NTGB script.
	 *
	 * @return string
	 */
	public static function get_ntgb_js_url() {
		return self::get_static_host() . self::$ntgb_js_path;
	}

	/**
	 * Get full URL of content.js
	 *
	 * @return string
	 */
	public static function get_plugin_js_url() {
		return preg_replace(
			'/^https?\:/ui',
			'',
			plugins_url( 'static/content.js?ver=' . urlencode( NATIVERENT_PLUGIN_VERSION ), NATIVERENT_PLUGIN_FILE )
		);
	}

	/**
	 * Prevent double injection (which is possible on cached pages).
	 *
	 * @param  string $html  Full HTML code.
	 *
	 * @return string
	 */
	public static function disintegration_template( $html ) {
		$pattern = 'class="' . self::$integration_class;

		if ( strpos( $html, $pattern ) > - 1 ) {
			// Remove our scripts.
			$html = preg_replace(
				'/<script[^>]+' . preg_quote( $pattern, '/' ) . '[^>]*>[\s\S]*?<\/script>/is',
				'',
				$html
			);

			// Remove our link tag.
			$html = preg_replace(
				'/<link[^>]+' . preg_quote( $pattern, '/' ) . '[^>]*>/is',
				'',
				$html
			);

			// Remove comment.
			$html = str_replace( '<!--noptimize--><!--/noptimize-->', '', $html );
		}

		return $html;
	}

	/**
	 * Render template.
	 *
	 * @return string
	 */
	public function render() {
		$integration_code = '';

		if ( ! $this->monetizations->is_regular_rejected() ) {
			// NOTE: the script will automatically download NTGB if needed.
			$integration_code .= $this->get_regular_head_template();
		} elseif ( ! $this->monetizations->is_ntgb_rejected() ) {
			// Only NTGB script.
			$integration_code .= $this->get_ntgb_head_template();
		}

		// NRentCounter init script.
		$integration_code .= $this->get_counter_init_template();

		// Ad-units arrangement configuration.
		$integration_code .= $this->render_units_config();

		// Plugin script for arrangement blocks and unblocking other adv scripts.
		$integration_code .= self::get_plugin_js_template();

		return $this->template_wrapper( $integration_code );
	}

	/**
	 * Get template for regular NR head integration.
	 *
	 * @return string
	 */
	private function get_regular_head_template() {
		$tmpl   = '';
		$js_url = self::get_main_js_url();

		// Additional script for working with WP Rocket delay JS.
		if ( class_exists( Plugin::class ) ) {
			$tmpl .= sprintf(
				'<script class="%s" type="text/javascript" %s>' .
				'Array.from(["keydown","mousedown","mousemove","touchmove","touchstart","touchend","wheel"])' .
				'.forEach(function(e){window.addEventListener(e,function(){window.NRentRocketDOMContentLoaded=!0},{once:!0})});'
				.
				'</script>',
				self::$integration_class,
				self::$script_data_props
			);
		}

		// Preload tag.
		$tmpl .= sprintf(
			'<link rel="preload" as="script" href="%s" class="%s" crossorigin />',
			$js_url,
			self::$integration_class
		);

		// Main script.
		$tmpl .= sprintf(
			'<script class="%s" src="%s" onerror="%s" %s async crossorigin></script>', //phpcs:ignore
			self::$integration_class,
			$js_url,
			/** @lang JavaScript */
			'(window.NRentPlugin=window.NRentPlugin||[]).push(\'error_loading_script\')',
			self::$script_data_props
		);

		return $tmpl;
	}

	/**
	 * Get template for NTGB head integration.
	 *
	 * @return string
	 */
	private function get_ntgb_head_template() {
		$tpml        = '';
		$ntgb_js_url = self::get_ntgb_js_url();

		// Preload tag.
		$tpml .= sprintf(
			'<link rel="preload" as="script" href="%s" class="%s" crossorigin />',
			$ntgb_js_url,
			self::$integration_class
		);

		// NTGB script.
		$tpml .= sprintf(
			'<script class="%s" src="%s" %s async crossorigin></script>', //phpcs:ignore
			self::$integration_class,
			$ntgb_js_url,
			self::$script_data_props
		);

		return $tpml;
	}

	/**
	 * Get NR counter init template.
	 *
	 * @return string
	 */
	private function get_counter_init_template() {
		return sprintf(
			'<script class="%s" type="text/javascript" %s>' .
			'(window.NRentCounter=window.NRentCounter||[]).push({id:"%s",lightMode:%s,created:%d})' .
			'</script>',
			self::$integration_class,
			self::$script_data_props,
			$this->site_id,
			'undefined',
			time()
		);
	}

	/**
	 * Template of `content.js` integration.
	 *
	 * @return string
	 */
	public static function get_plugin_js_template() {
		return sprintf(
			'<script class="%s" src="%s" %s defer></script>', //phpcs:ignore
			self::$integration_class,
			self::get_plugin_js_url(),
			self::$script_data_props
		);
	}

	/**
	 * Wrapper for rendered template.
	 *
	 * @param  string $tmpl  Complete template.
	 *
	 * @return string
	 */
	private function template_wrapper( $tmpl ) {
		return '<!--noptimize-->' . str_replace( array( "\n", "\t" ), '', $tmpl ) . '<!--/noptimize-->';
	}

	/**
	 * Render ad-units config.
	 *
	 * @return string
	 */
	protected function render_units_config() {
		if ( ! is_array( $this->units_config ) || empty( $this->units_config ) ) {
			return '';
		}

		$pushes = array();
		foreach ( $this->units_config as $units_type => $type_config ) {
			if ( 'ntgb' === $units_type ) {
				$type_config = Options::get_active_ntgb_units( $type_config );
			}
			foreach ( $type_config as $type => $config ) {
				$ad_unit = array();
				if ( 'ntgb' === $units_type ) {
					$ad_unit['type']   = $units_type;
					$ad_unit['unitId'] = (string) $type;
				} else {
					$ad_unit['type'] = $type;
				}
				$ad_unit['insert']       = in_array( @$config['insert'], array( 'before', 'inside', 'after' ) )
					? $config['insert']
					: 'after';
				$ad_unit['autoSelector'] = rawurlencode( @$config['autoSelector'] );
				$ad_unit['selector']     = rawurlencode( @$config['customSelector'] );
				$ad_unit['settings']     = array();
				if ( 'popupTeaser' === $type ) {
					$ad_unit['insert']             = 'inside';
					$ad_unit['autoSelector']       = 'body';
					$ad_unit['settings']['mobile'] = array();
					if ( @$config['settings']['mobileTeaser'] ) {
						$ad_unit['settings']['mobile'][] = 'teaser';
					}
					if ( @$config['settings']['mobileFullscreen'] ) {
						$ad_unit['settings']['mobile'][] = 'fullscreen';
					}
					$ad_unit['settings']['desktop'] = array();
					if ( @$config['settings']['desktopTeaser'] ) {
						$ad_unit['settings']['desktop'][] = 'teaser';
					}
				}
				$pushes[] = 'window.NRentAdUnits.push(' . json_encode( $ad_unit ) . ')';
			}
		}

		return sprintf(
			'<script class="%s" %s>' .
			'!0!==window.NRentAdUnitsLoaded&&(window.NRentAdUnitsLoaded=!0,window.NRentAdUnits=[],%s)' .
			'</script>',
			self::$integration_class,
			self::$script_data_props,
			implode( ',', $pushes )
		);
	}

	/**
	 * AdUnits config filtration by monetization.
	 *
	 * @param  array{regular: array, ntgb: array} $config  AdUnits config.
	 *
	 * @return array Filtered config.
	 */
	private function units_config_filtration( $config ) {
		// If REGULAR is rejected, then delete config items.
		if ( $this->monetizations->is_regular_rejected() && isset( $config['regular'] ) ) {
			unset( $config['regular'] );
		}

		// If NTGB is rejected, then delete config items.
		if ( $this->monetizations->is_ntgb_rejected() && isset( $config['ntgb'] ) ) {
			unset( $config['ntgb'] );
		}

		return $config;
	}

	/**
	 * Convert to string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}
}
