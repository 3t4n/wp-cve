<?php
/**
 * WPSSLE_Google_API class.
 *
 * @file class-google-api.php
 *
 * @package wpsyncsheets-elementor
 */

/**
 * WPSSLE_Google_API class.
 *
 * @since 1.0.0
 */
abstract class WPSSLE_Google_API {
	/**
	 * Get meta vlaue.
	 *
	 * @param object $key plugin meta key.
	 * @param string $type boolean value.
	 */
	public static function wpssle_option( $key = '', $type = '' ) {
		if ( is_multisite() ) {
			$value = get_site_option( $key, $type );
		} else {
			$value = get_option( $key, $type );
		}
		return $value;
	}

	/**
	 * Update meta value.
	 *
	 * @param object $key plugin meta key.
	 * @param string $value plugin meta value.
	 */
	public static function wpssle_update_option( $key = '', $value = '' ) {
		if ( is_multisite() ) {
			update_site_option( $key, $value );
		} else {
			update_option( $key, $value );
		}
	}

	/**
	 * Retrieve the list of sheets from the Google Spreadsheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param string $spreadsheetid Spreadsheet id.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function get_sheets( $service, $spreadsheetid ) {
		return $service->spreadsheets->get( $spreadsheetid );
	}

	/**
	 * Create New Spreadsheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param object $requestbody Google_Service_Sheets_Spreadsheet object.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function create_spreadsheet( $service, $requestbody ) {
		return $service->spreadsheets->create( $requestbody );
	}

	/**
	 * Append new entry to google sheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param array  $param spreadsheetid,range,requestbody,params.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function append_entry( $service, $param ) {
		return $service->spreadsheets_values->append( $param['spreadsheetid'], $param['range'], $param['requestbody'], $param['params'] );
	}

	/**
	 * Update entry to google sheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param array  $param spreadsheetid,range,requestbody,params.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function update_entry( $service, $param ) {
		return $service->spreadsheets_values->update( $param['spreadsheetid'], $param['range'], $param['requestbody'], $param['params'] );
	}


	/**
	 * Update entry to google sheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param array  $param spreadsheetid,requestbody.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function batchupdate( $service, $param ) {
		return $service->spreadsheets->batchUpdate( $param['spreadsheetid'], $param['requestbody'] );
	}

	/**
	 * Get rows from google sheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param array  $param spreadsheetid,sheetname.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function get_values( $service, $param ) {
		return $service->spreadsheets_values->get( $param['spreadsheetid'], $param['sheetname'] );
	}

	/**
	 * Clear google sheet.
	 *
	 * @param object $service Google_Service_Sheets.
	 * @param array  $param spreadsheetid,sheetname.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function clearsheet( $service, $param ) {
		return $service->spreadsheets_values->clear( $param['spreadsheetid'], $param['sheetname'], $param['requestbody'] );
	}
}
