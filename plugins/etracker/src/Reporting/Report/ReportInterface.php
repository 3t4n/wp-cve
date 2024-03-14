<?php
/**
 * ReportInterface.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report;

use Etracker\Reporting\Client;

/**
 * ReportInterface for etrackers reports.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
interface ReportInterface {
	/**
	 * Construct a Report.
	 *
	 * @param Client $api Connected client api object.
	 */
	public function __construct( Client $api );

	/**
	 * Returns report name.
	 *
	 * @return string
	 */
	public function get_report_name(): string;

	/**
	 * Fetch report with report_config.
	 *
	 * @param array $report_config Report configuration.
	 *
	 * @throws ReportQueryMetaException Thrown on error while fetching report meta data.
	 * @throws ReportQueryDataException Thrown on error while fetching report data.
	 *
	 * @return Report
	 */
	public function fetch_report_with_config( array $report_config ): Report;

	/**
	 * Fetch report with default ReportConfig for this object.
	 *
	 * @return Report
	 */
	public function fetch_report(): Report;

	/**
	 * Filter Sum row from report_data.
	 *
	 * @return array report_data
	 */
	public function get_report_data_without_sum(): array;

	/**
	 * Get first value from column $column.
	 *
	 * Function returns NULL if no reporting available.
	 *
	 * @param string $column Report column to return.
	 *
	 * @return string|int|null Reporting column value.
	 */
	public function get_first_value( string $column );

	/**
	 * Returns start date of report.
	 *
	 * @return string Start date.
	 */
	public function get_start_date(): string;

	/**
	 * Returns end date of report.
	 *
	 * @return string End date.
	 */
	public function get_end_date(): string;
}
