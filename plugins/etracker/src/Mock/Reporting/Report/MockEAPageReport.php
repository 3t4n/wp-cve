<?php
/**
 * MockEAPageReport.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Mock\Reporting\Report;

use Etracker\Reporting\Report\EAPageReportInterface;
use Etracker\Reporting\Report\Report;

/**
 * Mock of EAPageReport.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class MockEAPageReport extends Report implements EAPageReportInterface {
	/**
	 * Variable used to store method name to query report from.
	 *
	 * @var string
	 */
	public $report_by = '';

	/**
	 * Query EAPage-Report for page $page_name.
	 *
	 * @param string $page_name Page Name to query report for.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_page_name( string $page_name ): EAPageReportInterface {
		$this->report_by = __METHOD__ . '(' . $page_name . ')';
		return $this;
	}

	/**
	 * Query EAPage-Report for URL $url.
	 *
	 * @param string $url       URL of Page.
	 * @param string $page_name Page Name.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_url_and_page_name( string $url, string $page_name ): EAPageReportInterface {
		$this->report_by = __METHOD__ . '(' . $url . ', ' . $page_name . ')';
		return $this;
	}

	/**
	 * Query EAPage-Report for URL $url.
	 *
	 * @param string $url URL of Page.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_url( string $url ): EAPageReportInterface {
		$this->report_by = __METHOD__ . '(' . $url . ')';
		return $this;
	}

	/**
	 * Return UniqueVisits.
	 *
	 * Function returns NULL if no reporting available.
	 *
	 * @return int|null UniqueVisits.
	 */
	public function get_unique_visits() {
		return 5;
	}
}
