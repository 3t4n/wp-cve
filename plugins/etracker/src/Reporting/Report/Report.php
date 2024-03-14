<?php
/**
 * Report.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report;

use Etracker\Reporting\Exceptions\ReportQueryDataException;
use Etracker\Reporting\Exceptions\ReportQueryMetaException;
use Etracker\Reporting\Client;

/**
 * Abstract class for Reports.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
abstract class Report implements ReportInterface {
	/**
	 * Client object to talk to etrackers reporting API.
	 *
	 * @var Client
	 */
	protected $api = null;

	/**
	 * Report name to query.
	 *
	 * @var string
	 */
	protected $report_name = null;

	/**
	 * Reporting API response with reports meta_data.
	 *
	 * @var array
	 */
	protected $meta_data = null;

	/**
	 * Reporting API response with reporting data.
	 *
	 * @var array
	 */
	protected $report_data = null;

	/**
	 * Array with mapping data for each report column.
	 *
	 * @var array
	 */
	protected $id_map = null;

	/**
	 * ReportConfig used to query report.
	 *
	 * @var ReportConfig
	 */
	protected $report_config = null;

	/**
	 * Construct a Report.
	 *
	 * @param Client $api Connected client api object.
	 */
	public function __construct( Client $api ) {
		$this->api           = $api;
		$this->report_config = new ReportConfig();
	}

	/**
	 * Returns report name.
	 *
	 * @return string
	 */
	public function get_report_name(): string {
		return $this->report_name;
	}

	/**
	 * Sets report name.
	 *
	 * @param string $name Report name.
	 *
	 * @return void
	 */
	protected function set_report_name( string $name ) {
		$this->report_name = $name;
	}

	/**
	 * Get metaData id map.
	 *
	 * @return array
	 */
	private function get_meta_data_id_map(): array {
		// return already build id_map if exist.
		if ( isset( $this->id_map ) ) {
			return $this->id_map;
		}

		if ( ! isset( $this->meta_data ) ) {
			return false;
		}

		// build id_map.
		$this->id_map = array();
		foreach ( $this->meta_data as $key => $obj ) {
			array_push( $this->id_map, $obj->id );
		}

		// return id_map.
		return $this->id_map;
	}

	/**
	 * Converts report_data into array with named keys.
	 *
	 * Function returns false if report_data is not an array.
	 *
	 * @return void|bool
	 */
	protected function replace_report_data_keys() {
		// work on report_data.
		if ( ! is_array( $this->report_data ) ) {
			return false;
		}

		// replace key names within each nested array (report lines).
		$new_arr = array();
		foreach ( $this->report_data as $k => $v ) {
			$new_arr[ $k ] = is_array( $v ) ? array_combine( $this->get_meta_data_id_map(), $v ) : $v;
		}
		$this->report_data = $new_arr;
	}

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
	public function fetch_report_with_config( array $report_config ): Report {
		$report_name      = $this->get_report_name();
		$result_meta_data = $this->api->get( "report/$report_name/metaData", $report_config );

		if ( 200 == $result_meta_data->info->http_code ) {
			$this->meta_data = $result_meta_data->decode_response();
		} else {
			throw new ReportQueryMetaException( 'Error during query report metaData: ' . json_encode( $result_meta_data->info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . ' url: ' . $result_meta_data->url );
		}

		unset( $result_meta_data );

		$result_data = $this->api->get( "report/$report_name/data", $report_config );

		if ( 200 == $result_data->info->http_code ) {
			$this->report_data = $result_data->decode_response();
			// replace result keys with meta_data id field.
			$this->replace_report_data_keys();
		} else {
			throw new ReportQueryDataException( 'Error during query report data: ' . json_encode( $result_data->info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
		}

		unset( $result_data );

		return $this;
	}

	/**
	 * Fetch report with default ReportConfig for this object.
	 *
	 * @return Report
	 */
	public function fetch_report(): Report {
		return $this->fetch_report_with_config( $this->report_config->build() );
	}

	/**
	 * Filter Sum row from report_data.
	 *
	 * @return array report_data
	 */
	public function get_report_data_without_sum(): array {
		return array_filter(
			$this->report_data,
			function ( $data ) {
				if ( ! array_key_exists( 'tree_status', $data ) ) {
					return false;
				}
				if ( '=S' === $data['tree_status'] ) {
					return false;
				}
				return true;
			}
		);
	}

	/**
	 * Get first value from column $column.
	 *
	 * Function returns NULL if no reporting available.
	 *
	 * @param string $column Report column to return.
	 *
	 * @return string|int|null Reporting column value.
	 */
	public function get_first_value( string $column ) {
		$data = $this->get_report_data_without_sum();
		if ( empty( $data ) ) {
			return null;
		}
		return $data[1][ $column ];
	}

	/**
	 * Returns start date of report.
	 *
	 * @return string Start date.
	 */
	public function get_start_date(): string {
		$report_config = $this->report_config->build();
		return $report_config['startDate'];
	}

	/**
	 * Returns end date of report.
	 *
	 * @return string End date.
	 */
	public function get_end_date(): string {
		$report_config = $this->report_config->build();
		return $report_config['endDate'];
	}
}
