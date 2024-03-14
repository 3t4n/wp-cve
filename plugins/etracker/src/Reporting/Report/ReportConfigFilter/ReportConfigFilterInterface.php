<?php
/**
 * ReportConfigFilterInterface.
 *
 * @link       https://etracker.com
 * @since      2.4.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report\ReportConfigFilter;

use Etracker\Reporting\Report\ReportConfig;

/**
 * ReportConfigFilterInterface for etrackers reports.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
interface ReportConfigFilterInterface {
	/**
	 * Get slug name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_slug(): string;

	/**
	 * Get human readable name for this ReportConfigFilter.
	 *
	 * @return string
	 */
	public static function get_name(): string;

	/**
	 * Apply ReportConfigFilter to ReportConfig $report_config.
	 *
	 * @param ReportConfig $report_config ReportConfig to be modified.
	 *
	 * @return ReportConfig
	 */
	public static function apply_etracker_report_config_filter( ReportConfig $report_config ): ReportConfig;
}
