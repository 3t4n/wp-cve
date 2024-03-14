<?php
/**
 * ReportConfigFilterFactory.
 *
 * @link       https://etracker.com
 * @since      2.4.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report\ReportConfigFilter;

/**
 * ReportConfigFilterFactory.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class ReportConfigFilterFactory {
	/**
	 * Array of ReportConfigFilter instances.
	 *
	 * @var array
	 */
	protected static $filters = array();

	/**
	 * Register ReportConfigFilter instance.
	 *
	 * @param ReportConfigFilterInterface $report_config_filter ReportConfigFilter to register.
	 *
	 * @return void
	 */
	public static function register_filter( ReportConfigFilterInterface $report_config_filter ) {
		self::$filters[ $report_config_filter->get_slug() ] = $report_config_filter;
	}

	/**
	 * Return instance of ReportConfigFilter with slug $slug.
	 *
	 * @param string $slug Slug of ReportConfigFilter to return.
	 *
	 * @return ReportConfigFilterInterface
	 */
	public static function get_instance( string $slug ): ReportConfigFilterInterface {
		return self::$filters[ $slug ];
	}

	/**
	 * Get list of slugs of registered ReportConfigFilters.
	 *
	 * @return array List of slugs of registered ReportConfigFilters.
	 */
	public static function get_slugs(): array {
		return array_keys( self::$filters );
	}

	/**
	 * Returns True if ReportConfigFilter with slug $slug has been registered.
	 *
	 * @param string $slug The slug $slug we are looking for.
	 *
	 * @return boolean Returns true if filter with slug $slug has been registered or false.
	 */
	public static function has_filter_with_slug( string $slug ): bool {
		return array_key_exists( $slug, self::$filters );
	}
}
