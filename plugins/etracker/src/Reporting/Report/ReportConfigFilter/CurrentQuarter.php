<?php
/**
 * CurrentQuarter ReportConfigFilter.
 *
 * @link       https://etracker.com
 * @since      2.4.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report\ReportConfigFilter;

use Etracker\Reporting\Report\ReportConfig;

/**
 * CurrentQuarter implements a ReportConfigFilter to set reporting timespan to
 * to current quarter.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class CurrentQuarter implements ReportConfigFilterInterface {
	/**
	 * Get slug name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return 'current-quarter';
	}

	/**
	 * Get human readable name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_name(): string {
		return __( 'Quarter to date', 'etracker' );
	}

	/**
	 * Apply ReportConfigFilter to ReportConfig $report_config.
	 *
	 * @param ReportConfig $report_config ReportConfig to be modified.
	 *
	 * @return ReportConfig
	 */
	public static function apply_etracker_report_config_filter( ReportConfig $report_config ): ReportConfig {
		// Calculate first month of this quarter.
		$first_month_of_quarter    = ( ceil( gmdate( 'n' ) / 3 ) * 3 ) - 2;
		$first_day_of_this_quarter = gmdate( 'Y-m-d', strtotime( gmdate( 'Y' ) . '-' . $first_month_of_quarter . '-1 GMT' ) );

		// Update $report_config startDate field.
		$report_config['startDate'] = $first_day_of_this_quarter;
		// Keep `endDate` unmodified.
		// Return updated `$report_config`.
		return $report_config;
	}
}
