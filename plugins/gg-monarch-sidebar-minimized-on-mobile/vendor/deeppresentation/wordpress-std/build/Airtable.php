<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Std\Core\Str;
use MSMoMDP\Std\DataFlow\Convertor;

class Airtable {


	private $dataRetrievalOptions;
	private $lastReqTime  = null;
	private $oneReqLimSec = PHP_INT_MAX;
	public function __construct( $dataRetrievalOptions ) {
		$this->dataRetrievalOptions = $dataRetrievalOptions;
		$this->oneReqLimSec         = 1.0 / $dataRetrievalOptions['apiRequestRateLimPerSec'];
	}

	public function get_raw_table( $tableName ) {
		$apiKey = $this->dataRetrievalOptions['apikey'];
		$appId  = $this->dataRetrievalOptions['appid'];
		$apiUrl = $this->dataRetrievalOptions['apiUrl'];

		$http_params = array();

		$http_headers = array(
			'Authorization' => 'Bearer ' . $apiKey,
			'Content-Type'  => 'application/json',
		);

			// Initialize the offset.
		$offset = '';

		$records = array();

		// Make calls to Airtable, until all of the data has been retrieved...
		while ( ! is_null( $offset ) ) :

			$http_params['offset'] = $offset;

			// Specify the URL to call.
			$url = rtrim( $apiUrl, '/' )
				. '/' . $appId
				. '/' . rawurlencode( $tableName )
				. '?' . http_build_query( $http_params );

			$args = array(
				'timeout'     => 60,
				'redirection' => 5,
				'blocking'    => true,
				'headers'     => $http_headers,
				'cookies'     => array(),
				'body'        => null,
				'compress'    => false,
				'decompress'  => true,
				'sslverify'   => true,
			);

			// request limit handeling
			if ( $this->lastReqTime ) {
				$timeDiff = time() - $this->lastReqTime;
				if ( $timeDiff < $this->oneReqLimSec ) {
					sleep( $this->oneReqLimSec - $timeDiff + 0.05 );
				}
			}
			$this->lastReqTime = time();

			$response      = wp_remote_get( $url, $args );
			$response_code = wp_remote_retrieve_response_code( $response );

			if ( is_wp_error( $response ) || $response_code != 200 ) {

				error_log( 'Travelseason Airtable HTTP request error: ' . wp_remote_retrieve_response_message( $response ) );

				return false;

			} else {

				$body = json_decode( $response['body'], true ); // use the content

				// When getting a table, we'll build an array of records,
				// when getting a record, we'll just return the record.
				if ( isset( $body['records'] ) ) {

					$records = array_merge( $records, $body['records'] );
					// Adjust the offset.
					// Airtable returns NULL when the final batch of records has been returned.
					$offset = ( isset( $body['offset'] ) ) ? $body['offset'] : null;

				} else {

					$records = $body;
					$offset  = null;
				}
			}

		endwhile;

		return $records;
	}

	// TODO separate to generic table and airtable


	private function add_filtered_fields_from_airtable_record( &$result, $rawAirTableRecord, $fieldsFilter, $fieldConvertActions, $recordIdTag = 'id', $keepAllPathInRes = true, $defaultValue = '', $recordIdOverride = null ) {
		$recordId            = $recordIdOverride ?? Arr::get( $rawAirTableRecord, $recordIdTag );
		$result[ $recordId ] = array();
		foreach ( $fieldsFilter as $field ) {
			if ( ! empty( $field ) ) {
				$val = $defaultValue;
				if ( isset( $rawAirTableRecord ) ) {
					if ( isset( $fieldConvertActions ) && array_key_exists( $field, $fieldConvertActions ) ) {
						$val = Convertor::process( Arr::get( $rawAirTableRecord, $field ), $fieldConvertActions[ $field ] ) ?? $defaultValue;
						if ( $keepAllPathInRes ) {
							Arr::set( $result[ $recordId ], $field, $val, true );
						} else {
							$result[ $recordId ] = array_merge( $result[ $recordId ], Arr::as_array( $val ) );
							//Arr::set($result[$recordId], $field, is_null($val) ? $defaultValue : $val, true);
						}
					} else {
						$val = Arr::get( $rawAirTableRecord, $field ) ?? $defaultValue;  // $rawAirTableRecord['fields'][$field];
						if ( $keepAllPathInRes ) {
							Arr::set( $result[ $recordId ], $field, $val, true );
						} else {
							$fieldName = Str::separed_last_part( $field );
							if ( ! empty( $fieldName ) ) {
								$result[ $recordId ][ $fieldName ] = $val;
							}
						}
					}
				}
			}
		}
	}

	public function restructurize_airtable_data_to_column_array(
		$rawAirTable,
		$recordIdTag,
		$recordIdFilter,
		$fieldsFilter,
		$fieldConvertActions = null,
		$keepAllPathInRes = true,
		$insertRecordLegendColumn = false,
		$recordIdIsKey = false,
		$recordLegendColumnName = 'Record Legend'
	) {
		$result = array();
		if ( $insertRecordLegendColumn ) {
			// row legend in the first column
			$result[ $recordLegendColumnName ] = array();
			foreach ( $fieldsFilter as $field ) {
				Arr::set( $result[ $recordLegendColumnName ], $field, Str::separed_last_part( $field ) ?? '', true );
			}
		}
		if ( empty( $recordIdFilter ) || $recordIdFilter == '*' ) {
			// all rows - all data columns
			foreach ( $rawAirTable as $key => $rawAirTableRecord ) {
				$this->add_filtered_fields_from_airtable_record(
					$result,
					$rawAirTableRecord,
					$fieldsFilter,
					$fieldConvertActions,
					$recordIdTag,
					$keepAllPathInRes,
					'',
					$recordIdIsKey ? $key : null
				);
			}
		} else {
			// rows selected by filter - all data columns
			foreach ( $recordIdFilter as $recordIdFilterItem ) {
				$matchedRawAirTableRow = null;
				if ( isset( $rawAirTable ) && is_array( $rawAirTable ) ) {
					foreach ( $rawAirTable as $rawAirTableRecord ) {
						$recordId = Arr::get( $rawAirTableRecord, $recordIdTag );
						if ( $recordId == $recordIdFilterItem ) {
							$matchedRawAirTableRow = $rawAirTableRecord;
							break;
						}
					}
				}
				if ( ! isset( $matchedRawAirTableRow ) ) {
					// to create empty object if not found in db
					$matchedRawAirTableRow = array( $recordIdTag => $recordIdFilterItem );
				}
				$this->add_filtered_fields_from_airtable_record(
					$result,
					$matchedRawAirTableRow,
					$fieldsFilter,
					$fieldConvertActions,
					$recordIdTag,
					$keepAllPathInRes,
					'',
					$recordIdIsKey ? $matchedRawAirTableRow . key() : null
				);
			}
		}
		return $result;
	}

}
