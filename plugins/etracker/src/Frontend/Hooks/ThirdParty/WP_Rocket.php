<?php
/**
 * File contains a class to handle WP_Rocket integration.
 *
 * @link       https://etracker.com
 * @since      2.1.0
 *
 * @package    Etracker
 */

namespace Etracker\Frontend\Hooks\ThirdParty;

/**
 * 3rdParty integration for WP_Rocket.
 *
 * Use WP_Rocket hooks and filters to ensure etracker tracking works as expected.
 *
 * @since      2.1.0
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class WP_Rocket implements ThirdPartyIntegrationInterface {
	/**
	 * Get subscribed filters.
	 *
	 * @inheritDoc
	 */
	public static function get_subscribed_filters() {
		return array(
			'rocket_minify_excluded_external_js' => array(
				array(
					// phpcs:disable Squiz.PHP.CommentedOutCode.Found
					// @see Etracker\Plugin\Loader::add_filter
					'component' => self::class,
					'callback'  => 'rocket_minify_excluded_external_js',
					// Could also set $priority and $accepted_args.
				),
			),
		);
	}

	/**
	 * Filter WP-Rocket excluded external js patterns.
	 *
	 * WP-Rocket allows us to add our external js patterns to their list to prevent
	 * etrackers e.js to be minified for the whole site.
	 *
	 * @since 2.1.0
	 *
	 * @param array $current_excluded_js Array with currently excluded external js.
	 *
	 * @return array Array of excluded js patterns.
	 */
	public static function rocket_minify_excluded_external_js( $current_excluded_js ) {
		if ( ! is_array( $current_excluded_js ) ) {
			return $current_excluded_js;
		}

		// Add code.etracker.com to list of excluded_external_js.
		$current_excluded_js[] = 'code.etracker.com';

		return $current_excluded_js;
	}
}
