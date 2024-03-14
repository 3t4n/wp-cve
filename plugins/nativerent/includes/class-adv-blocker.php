<?php

namespace NativeRent;

use Exception;
use WP_Rocket\Admin\Options_Data;
use WP_Rocket\Engine\Optimization\DelayJS\HTML;
use WP_Rocket\Engine\Optimization\DynamicLists\DataManager;
use WP_Rocket\Plugin;

use function base64_encode;
use function class_exists;
use function get_option;
use function is_array;
use function is_callable;
use function preg_replace_callback;
use function sprintf;
use function stripos;
use function version_compare;

use const PHP_VERSION;

/**
 * Adb blocker class.
 */
class Adv_Blocker {
	/**
	 * Adv block patterns list
	 *
	 * @var string[]
	 */
	private $patterns;

	/**
	 * WP Rocket delay JS option.
	 *
	 * @var bool
	 */
	private $rocket_delay_js = false;

	/**
	 * WP Rocket HTML instance.
	 *
	 * @var HTML|null
	 */
	private $rocket_html = null;

	/**
	 * Constructor.
	 *
	 * @param  string[] $patterns  Adv patterns.
	 */
	public function __construct( $patterns ) {
		$this->patterns = $patterns;
		$this->check_wp_rocket_delay_js();
	}

	/**
	 * Check WP Rocket delay js settings.
	 *
	 * @return void
	 */
	private function check_wp_rocket_delay_js() {
		if (
			version_compare( PHP_VERSION, '7.0.0' ) < 0 ||
			! class_exists( Plugin::class ) ||
			! class_exists( HTML::class ) ||
			! class_exists( Options_Data::class )
		) {
			return;
		}

		try {
			$opts = get_option( 'wp_rocket_settings' );
			if ( is_array( $opts ) && isset( $opts['delay_js'] ) ) {
				$this->rocket_html     = new HTML(
					new Options_Data( $opts ),
					class_exists( DataManager::class ) ? new DataManager() : null
				);
				$this->rocket_delay_js = true;
			}
		} catch ( \Throwable $e ) {
			$this->rocket_delay_js = false;
		}
	}

	/**
	 * Block adv by patterns.
	 *
	 * @param  string                $content         HTML content.
	 * @param  callable(): bool|null $stop_condition  Stop condition callback.
	 *
	 * @return string Modified HTML.
	 */
	public function block( $content, $stop_condition = null ) {
		if ( ! is_array( $this->patterns ) ) {
			return $content;
		}

		foreach ( $this->patterns as $pattern ) {
			$modified = @preg_replace_callback( $pattern, array( $this, 'replace_handler' ), $content );
			$content  = ! empty( $modified ) ? $modified : $content;
			if ( is_callable( $stop_condition ) && $stop_condition() ) {
				break;
			}
		}

		return $content;
	}

	/**
	 * Callback for `preg_replace_callback`.
	 *
	 * @param  array $matches  Matches of `preg_replace_callback`.
	 *
	 * @return string
	 */
	public function replace_handler( $matches ) {
		try {
			$match = $this->match_processing( $matches[0] );
		} catch ( Exception $e ) {
			$match = $matches[0];
		}
		if ( empty( $match ) ) {
			$match = $matches[0];
		}

		return sprintf(
			'<meta property="nativerent-block" class="nRent_block_ce40f5ef6e84e162" content="%s"/>',
			base64_encode( $match )
		);
	}

	/**
	 * Every match processing function.
	 *
	 * @param  string $match  Match by pattern.
	 *
	 * @return string
	 */
	private function match_processing( $match ) {
		// WP Rocket delay JS.
		return $this->rocket_processing( $match );
	}

	/**
	 * Fixes for WP Rocket delay JS feature.
	 *
	 * @note Only PHP >= 7.0
	 *
	 * @param  string $match  Pattern match.
	 *
	 * @return string
	 */
	private function rocket_processing( $match ) {
		if ( ! $this->rocket_delay_js || stripos( $match, 'type="rocketlazyloadscript"' ) ) {
			return $match;
		}

		try {
			return $this->rocket_html->delay_js( $match );
		} catch ( \Throwable $e ) {
			return $match;
		}
	}
}
