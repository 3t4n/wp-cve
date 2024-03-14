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
 * CurrentYear implements a ReportConfigFilter to set reporting timespan to
 * to current year.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class CurrentYear implements ReportConfigFilterInterface {
	/**
	 * Get slug name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return 'current-year';
	}

	/**
	 * Get human readable name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_name(): string {
		return __( 'Year to date', 'etracker' );
	}

	/**
	 * Apply ReportConfigFilter to ReportConfig $report_config.
	 *
	 * @param ReportConfig $report_config ReportConfig to be modified.
	 *
	 * @return ReportConfig
	 */
	public static function apply_etracker_report_config_filter( ReportConfig $report_config ): ReportConfig {
		// Set startDate to first January of this year.
		$report_config['startDate'] = gmdate( 'Y-01-01' );
		// Keep `endDate` unmodified.
		// Return updated `$report_config`.
		return $report_config;
	}
}
