<?php
/**
 * EAPageReport.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report;

use Etracker\Reporting\Client;
use Etracker\Reporting\ReportingUtil;

/**
 * EAPageReport implements a subset of etrackers EAPage report.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class EAPageReport extends Report implements EAPageReportInterface {
	/**
	 * Query EAPage report via Client $api.
	 *
	 * @param Client       $api           already connected Client object.
	 * @param ReportConfig $report_config ReportConfig to override embedded one.
	 */
	public function __construct( Client $api, $report_config = null ) {
		parent::__construct( $api );
		$this->set_report_name( 'EAPage' );
		$this->report_config['limit']      = 1;
		$this->report_config['attributes'] = array( 'page_name' );
		$this->report_config['figures']    = array( 'unique_visits' );
		if ( is_a( $report_config, ReportConfig::class ) ) {
			// Allow to override embedded ReportConfig.
			foreach ( array( 'startDate', 'endDate' ) as $key ) {
				$this->report_config[ $key ] = $report_config[ $key ];
			}
		}
	}

	/**
	 * Query EAPage-Report for page $page_name.
	 *
	 * @param string $page_name Page Name to query report for.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_page_name( string $page_name ): EAPageReportInterface {
		// Set filter for page_name.
		$this->report_config['attributeFilter'] = json_encode(
			array(
				array(
					'input'       => $page_name,
					'type'        => 'exact',
					'attributeId' => 'page_name',
					'filterType'  => 'simple',
				),
			)
		);
		// Query and return report.
		return $this->fetch_report();
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
		$etracker_url = ReportingUtil::url2etracker_url( $url );

		$this->report_config['attributeFilter'] = json_encode(
			array(
				array(
					'input'       => $page_name,
					'type'        => 'exact',
					'attributeId' => 'page_name',
					'filter'      => 'include',
					'filterType'  => 'extended',
				),
				array(
					'input'       => $etracker_url,
					'type'        => 'exact',
					'attributeId' => 'url',
					'filter'      => 'include',
					'filterType'  => 'extended',
				),
			)
		);
		return $this->fetch_report();
	}

	/**
	 * Query EAPage-Report for URL $url.
	 *
	 * @param string $url URL of Page.
	 *
	 * @return EAPageReportInterface
	 */
	public function get_report_by_url( string $url ): EAPageReportInterface {
		$etracker_url = ReportingUtil::url2etracker_url( $url );

		$this->report_config['attributeFilter'] = json_encode(
			array(
				array(
					'input'       => $etracker_url,
					'type'        => 'exact',
					'attributeId' => 'url',
					'filter'      => 'include',
					'filterType'  => 'extended',
				),
			)
		);
		return $this->fetch_report();
	}

	/**
	 * Return UniqueVisits.
	 *
	 * Function returns NULL if no reporting available.
	 *
	 * @return int|null UniqueVisits.
	 */
	public function get_unique_visits() {
		return $this->get_first_value( 'unique_visits' );
	}
}
