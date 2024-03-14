<?php
/**
 * CurrentYear ReportConfigFilter.
 *
 * @link       https://etracker.com
 * @since      2.4.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report\ReportConfigFilter;

use Etracker\Reporting\Report\ReportConfig;

/**
 * Last30Days implements a ReportConfigFilter to set reporting timespan to
 * to last 30 days until yesterday.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Last30Days implements ReportConfigFilterInterface {
	/**
	 * Get slug name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return 'last-30-days';
	}

	/**
	 * Get human readable name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_name(): string {
		return __( 'Last 30 days', 'etracker' );
	}

	/**
	 * Apply ReportConfigFilter to ReportConfig $report_config.
	 *
	 * @param ReportConfig $report_config ReportConfig to be modified.
	 *
	 * @return ReportConfig
	 */
	public static function apply_etracker_report_config_filter( ReportConfig $report_config ): ReportConfig {
		// As last 30 days is the default, there is no need to change anything.
		// Always return `$report_config`.
		return $report_config;
	}
}
