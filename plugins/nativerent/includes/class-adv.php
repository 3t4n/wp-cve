<?php
/**
 * Native Rent integration class
 *
 * @package nativerent
 */

namespace NativeRent;

use function microtime;
use function stripos;
use function strpos;

use const PHP_EOL;

defined( 'ABSPATH' ) || exit;

/**
 * Class Adv
 */
class Adv {
	const PROCESSING_TIMEOUT_SEC = 0.15;

	/**
	 * Content Integration Pattern
	 *
	 * @var string
	 */
	protected static $content_integration_pattern = 'nativerent-content-integration';

	/**
	 * Content Integration Tag
	 *
	 * @var string
	 */
	protected static $content_integration_tag = '<div class="nativerent-content-integration"></div>';

	/**
	 * Microtime of processing start
	 *
	 * @var float
	 */
	private static $processing_starts;

	/**
	 * Prepare to integration of ad units in post content.
	 *
	 * @param string $content HTML of content.
	 *
	 * @return string
	 */
	public static function content_integration( $content ) {

		/**
		 * Check if we're inside the main loop in a single Post.
		 */
		if ( is_singular() && is_main_query() && ! self::is_content_integrated( $content ) ) {
			return self::$content_integration_tag . PHP_EOL . $content;
		}

		return $content;
	}

	/**
	 * Check if content integration tag is present in $content
	 *
	 * @param string $content Content.
	 *
	 * @return bool
	 */
	public static function is_content_integrated( $content = '' ) {
		return ( false !== strpos( $content, self::$content_integration_pattern ) );
	}

	/**
	 * Include integration code on page.
	 *
	 * @param string        $buffer     HTML content.
	 * @param Monetizations $monetizations Monetizations instance.
	 *
	 * @return array|string|string[]|null
	 */
	public static function head_integration( $buffer, Monetizations $monetizations ) {
		// Do nothing on pages without our integration tag.
		if ( ! self::is_content_integrated( $buffer ) ) {
			return $buffer;
		}

		$site_id = Options::get( 'siteID' );
		if ( empty( $site_id ) ) {
			return $buffer;
		}

		// Create head integration template instance.
		$head_template = new Head_Template( $site_id, $monetizations, Options::get_adunits_config() );

		// Add HTTP2 Header for script preload.
		if ( ! $monetizations->is_regular_rejected() ) {
			self::add_http2_header( $head_template::get_main_js_url() );

		} elseif ( ! $monetizations->is_ntgb_rejected() ) {
			self::add_http2_header( $head_template::get_ntgb_js_url() );
		}

		return preg_replace(
			'/<head(\s[^>]*|)>/i',
			"<head$1>\n{$head_template->render()}",
			$head_template::disintegration_template( $buffer ),
			1
		);
	}

	/**
	 * Handler for ob_start. Include integration code, check adv status and block or unblock adv on page.
	 *
	 * @param string        $buffer Raw page content.
	 * @param Monetizations $monetizations Monetizations instance.
	 *
	 * @return string
	 */
	public static function processing( $buffer, $monetizations ) {
		self::$processing_starts = microtime( true );

		// Stop processing on unprocessable pages.
		if ( ! self::is_content_integrated( $buffer ) ) {
			return $buffer;
		}

		// Include integration code on page.
		$buffer = self::head_integration( $buffer, $monetizations );

		// Filter adv on pages with our adv.
		if (
			! $monetizations->is_regular_rejected() &&
			! self::check_to_stop() &&
			self::check_content_script_integration( $buffer )
		) {
			$blocker = new Adv_Blocker( Options::get_adv_patterns() );
			$buffer  = $blocker->block(
				$buffer,
				function () {
					return self::check_to_stop();
				}
			);
		}

		return $buffer;
	}

	/**
	 * Check integration of `content.js` script.
	 *
	 * @param string $buffer Full HTML of page.
	 *
	 * @return bool
	 */
	private static function check_content_script_integration( $buffer ) {
		return stripos( $buffer, Head_Template::get_plugin_js_template() ) !== false;
	}

	/**
	 * Check to stop
	 *
	 * @return bool
	 */
	private static function check_to_stop() {
		$processing_time = ( microtime( true ) - self::$processing_starts );

		return ( $processing_time >= self::PROCESSING_TIMEOUT_SEC );
	}

	/**
	 * Sends HTTP/2 push header
	 *
	 * @param string $js_url Script URL.
	 *
	 * @return void
	 */
	public static function add_http2_header( $js_url = '' ) {
		if ( headers_sent() || empty( $js_url ) ) {
			return;
		}

		header( 'Link: <' . $js_url . '>; rel=preload; as=script; crossorigin', false );
	}
}
