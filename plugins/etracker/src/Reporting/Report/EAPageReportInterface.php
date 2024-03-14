<?php
/**
 * EAPageReportInterface.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report;

/**
 * EAPageReportInterface for etrackers EAPage report.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
interface EAPageReportInterface extends ReportInterface {
	/**
	 * Query EAPage-Report for page $page_name.
	 *
	 * @param string $page_name Page Name to query report for.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_page_name( string $page_name ): EAPageReportInterface;

	/**
	 * Query EAPage-Report for URL $url.
	 *
	 * @param string $url       URL of Page.
	 * @param string $page_name Page Name.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_url_and_page_name( string $url, string $page_name ): EAPageReportInterface;

	/**
	 * Query EAPage-Report for URL $url.
	 *
	 * @param string $url URL of Page.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_url( string $url ): EAPageReportInterface;

	/**
	 * Return UniqueVisits.
	 *
	 * Function returns NULL if no reporting available.
	 *
	 * @return int|null UniqueVisits.
	 */
	public function get_unique_visits();
}
