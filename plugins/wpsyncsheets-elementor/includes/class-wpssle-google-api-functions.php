<?php
/**
 * Main WPSyncSheetsElementor\WPSSLE_Google_API namespace.
 *
 * @since 1.0.0
 * @package wpsyncsheets-elementor
 */

namespace WPSyncSheetsElementor;

/**
 * Google API Method Class
 *
 * @since 1.0.0
 */
class WPSSLE_Google_API_Functions extends \WPSSLE_Google_API {

	/**
	 * Google Sheet Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	private static $instance_service = null;

	/**
	 * Google Drive Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	private static $instance_drive = null;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( self::checkcredenatials() ) {
			self::loadobject();
		}
	}

	/**
	 * Load Google API Library.
	 *
	 * @since 1.0.0
	 */
	public function loadobject() {
		self::$instance_service = self::get_client_object();
		self::$instance_drive   = self::get_drive_object();
	}

	/**
	 * Include Google API Library.
	 *
	 * @since 1.0.0
	 */
	public function wpssle_load_library() {
		if ( ! function_exists( 'composerRequired7e59b00b8fddc8385312f117ab4f39c' ) ) {
			require_once \WPSSLE_DIR . 'lib/vendor/autoload.php';
		}
	}

	/**
	 * Generate Google Sheet Object.
	 *
	 * @since 1.0.0
	 */
	public function get_client_object() {
		if ( null === self::$instance_service ) {
			$client                 = self::getClient();
			self::$instance_service = new \Google_Service_Sheets( $client );
		}
		return self::$instance_service;
	}

	/**
	 * Regenerate Google Sheet Object.
	 *
	 * @since 1.0.0
	 */
	public function refreshobject() {
		self::$instance_service = null;
		self::get_client_object();
	}

	/**
	 * Regenerate Google Drive Object.
	 *
	 * @since 1.0.0
	 */
	public function get_drive_object() {
		if ( null === self::$instance_drive ) {
			$client               = self::getClient();
			self::$instance_drive = new \Google_Service_Drive( $client );
		}
		return self::$instance_drive;
	}

	/**
	 * Check Google Credenatials.
	 *
	 * @since 1.0.0
	 */
	public function checkcredenatials() {
		$wpssle_google_settings_value = self::wpssle_option( 'wpsse_google_settings' );
		$clientid                     = isset( $wpssle_google_settings_value[0] ) ? $wpssle_google_settings_value[0] : '';
		$clientsecert                 = isset( $wpssle_google_settings_value[1] ) ? $wpssle_google_settings_value[1] : '';
		$auth_token                   = isset( $wpssle_google_settings_value[2] ) ? $wpssle_google_settings_value[2] : '';
		if ( empty( $clientid ) || empty( $clientsecert ) || empty( $auth_token ) ) {
			return false;
		} else {
			try {
				if ( self::getClient() ) {
					return true;
				} else {
					return false;
				}
			} catch ( Exception $e ) {
				return false;
			}
		}
	}

	/**
	 * Get meta vlaue.
	 *
	 * @param object $key plugin meta key.
	 * @param string $type boolean value.
	 */
	public static function wpssle_option( $key = '', $type = '' ) {
		$wpssle_old_keys     = self::wpssle_old_option_keys();
		$wpssle_oldmeta_keys = array_keys( $wpssle_old_keys );
		if ( in_array( $key, $wpssle_oldmeta_keys, true ) ) {
			self::wpssle_database_update();
		}
		$value = parent::wpssle_option( $key, $type );
		return $value;
	}

	/**
	 * Update meta value.
	 *
	 * @param object $key plugin meta key.
	 * @param string $value plugin meta value.
	 */
	public static function wpssle_update_option( $key = '', $value = '' ) {
		$wpssle_old_keys     = self::wpssle_old_option_keys();
		$wpssle_oldmeta_keys = array_keys( $wpssle_old_keys );
		if ( in_array( $key, $wpssle_oldmeta_keys, true ) ) {
			self::wpssle_database_update();
		}
		$value = parent::wpssle_update_option( $key, $value );
		return $value;
	}
	/**
	 * Update old meta key, new meta key array.
	 */
	public static function wpssle_old_option_keys() {
		return array(
			'wpsse_google_accessToken' => 'elementorsheets_google_accessToken',
			'wpsse_google_settings'    => 'elementorsheets_google_settings',
		);
	}
	/**
	 * Update old meta key to new meta key
	 */
	public static function wpssle_database_update() {
		$wpssle_is_updated = self::wpssle_option( 'wpsse_database_updated' );
		$wpssle_old_keys   = self::wpssle_old_option_keys();
		global $wpdb;
		if ( ! $wpssle_is_updated ) {
			$table_name = $wpdb->prefix . 'options';
			foreach ( $wpssle_old_keys as $newkey => $oldkey ) {
				// @codingStandardsIgnoreStart
				$sql    = $wpdb->prepare( "UPDATE `$table_name` SET `option_name`=%s WHERE `option_name`=%s", $newkey, $oldkey );
				$result = $wpdb->get_results( $sql );
				// @codingStandardsIgnoreEnd
			}
			self::wpssle_update_option( 'wpsse_database_updated', 1 );
		}
	}

	/**
	 * Generate token for the user and refresh the token if it's expired.
	 *
	 * @param int $flag for getting error code.
	 * @return array
	 */
	public function getClient( $flag = 0 ) {

		$this->wpssle_load_library();
		$wpssle_google_settings_value = self::wpssle_option( 'wpsse_google_settings' );

		$clientid     = $wpssle_google_settings_value[0] ? $wpssle_google_settings_value[0] : '';
		$clientsecert = $wpssle_google_settings_value[1] ? $wpssle_google_settings_value[1] : '';
		$auth_token   = $wpssle_google_settings_value[2] ? $wpssle_google_settings_value[2] : '';
		$client       = new \Google_Client();
		$client->setApplicationName( 'WPSyncSheets For Elementor - Elementor Google Spreadsheet Addon' );
		$client->setScopes( \Google_Service_Sheets::SPREADSHEETS_READONLY );
		$client->setScopes( \Google_Service_Drive::DRIVE_METADATA_READONLY );
		$client->addScope( \Google_Service_Sheets::SPREADSHEETS );
		$client->setClientId( $clientid );
		$client->setClientSecret( $clientsecert );
		$client->setRedirectUri( esc_html( admin_url( 'admin.php?page=wpsyncsheets-elementor' ) ) );
		$client->setAccessType( 'offline' );
		$client->setApprovalPrompt( 'force' );
		// Load previously authorized credentials from a database.
		try {
			if ( empty( $auth_token ) ) {
				$auth_url = $client->createAuthUrl();
				return $auth_url;
			}
			$wpssle_accesstoken = parent::wpssle_option( 'wpsse_google_accessToken' );

			if ( ! empty( $wpssle_accesstoken ) ) {
				$accesstoken = json_decode( $wpssle_accesstoken, true );
			} else {
				if ( empty( $auth_token ) ) {
					$auth_url = $client->createAuthUrl();
					return $auth_url;
				} else {
					$authcode = trim( $auth_token );
					// Exchange authorization code for an access token.
					$accesstoken = $client->fetchAccessTokenWithAuthCode( $authcode );
					// Store the credentials to disk.
					parent::wpssle_update_option( 'wpsse_google_accessToken', wp_json_encode( $accesstoken ) );
				}
			}

			// Check for invalid token.
			if ( is_array( $accesstoken ) && isset( $accesstoken['error'] ) && ! empty( $accesstoken['error'] ) ) {
				if ( $flag ) {
					return $accesstoken['error'];
				}
				return false;
			}

			$client->setAccessToken( $accesstoken );
			// Refresh the token if it's expired.
			if ( $client->isAccessTokenExpired() ) {
				// save refresh token to some variable.
				$refreshtokensaved = $client->getRefreshToken();

				$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );

				// pass access token to some variable.
				$accesstokenupdated = $client->getAccessToken();

				// append refresh token.
				$accesstokenupdated['refresh_token'] = $refreshtokensaved;
				// Set the new acces token.
				$accesstoken = $refreshtokensaved;
				parent::wpssle_update_option( 'wpsse_google_accessToken', wp_json_encode( $accesstokenupdated ) );
				$accesstoken = json_decode( wp_json_encode( $accesstokenupdated ), true );
				$client->setAccessToken( $accesstoken );
			}
		} catch ( Exception $e ) {
			if ( $flag ) {
				return $e->getMessage();
			} else {
				return false;
			}
		}
		return $client;
	}

	/**
	 * Fetch Spreadsheet list from Google Drive.
	 *
	 * @param array $sheetarray Spreadsheet array.
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function get_spreadsheet_listing( $sheetarray = array() ) {
		// Print the names and IDs of files.
		$optparams = array(
			'fields' => 'nextPageToken, files(id, name, mimeType)',
			'q'      => "mimeType='application/vnd.google-apps.spreadsheet' and trashed = false",
		);

		$results = self::$instance_drive->files->listFiles( $optparams );

		if ( count( $results->getFiles() ) > 0 ) {
			foreach ( $results->getFiles() as $file ) {
				$sheetarray[ $file->getId() ] = $file->getName();
			}
		}
		$sheetarray['new'] = esc_html__( 'Create New Spreadsheet', 'wpsse' );
		return $sheetarray;
	}

	/**
	 * Retrieve the list of sheets from the Google Spreadsheet.
	 *
	 * @param string $spreadsheetid Spreadsheet id.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function get_sheet_listing( $spreadsheetid = '' ) {
		self::refreshobject();
		return parent::get_sheets( self::$instance_service, $spreadsheetid );
	}

	/**
	 * Fetch row from Google Sheet.
	 *
	 * @param array $spreadsheetid Spreadsheet ID.
	 * @param array $sheetname Sheet Name.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function get_row_list( $spreadsheetid, $sheetname ) {
		self::refreshobject();
		$param                  = array();
		$param['spreadsheetid'] = trim( $spreadsheetid );
		$param['sheetname']     = trim( $sheetname );
		return parent::get_values( self::$instance_service, $param );
	}

	/**
	 * Create sheet array.
	 *
	 * @param object $response_object google sheet object.
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function get_sheet_list( $response_object ) {
		$sheets = array();
		foreach ( $response_object->getSheets() as $key => $value ) {
			$sheets[ $value['properties']['title'] ] = $value['properties']['sheetId'];
		}
		return $sheets;
	}

	/**
	 * Create sheet array.
	 *
	 * @param object $response_object google sheet object.
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function get_sheetname_list( $response_object ) {
		$sheets = array();
		foreach ( $response_object->getSheets() as $key => $value ) {
			$sheets[] = $value['properties']['title'];
		}
		return $sheets;
	}

	/**
	 * Create sheet array.
	 *
	 * @param object $response_object google sheet object.
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function get_sheetid_list( $response_object ) {
		$sheets = array();
		foreach ( $response_object->getSheets() as $key => $value ) {
			$sheets[ $value['properties']['sheetId'] ] = $value['properties']['title'];
		}
		return $sheets;
	}

	/**
	 * Create insertDimension Object.
	 *
	 * @param array $param contains sheetid,startindex,endindex.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function insertdimensionobject( $param = array() ) {
		$requests           = new \Google_Service_Sheets_Request(
			array(
				'insertDimension' => array(
					'range' => array(
						'sheetId'    => $param['sheetid'],
						'dimension'  => 'ROWS',
						'startIndex' => $param['startindex'],
						'endIndex'   => $param['endindex'],
					),
				),
			)
		);
		$batchupdaterequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
			array(
				'requests' => $requests,
			)
		);
		return $batchupdaterequest;
	}

	/**
	 * Create insertDimension Object.
	 *
	 * @param array $param contains sheetid,startindex,endindex.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function insertdimensioncolumnobject( $param = array() ) {
		$requests           = new \Google_Service_Sheets_Request(
			array(
				'insertDimension' => array(
					'range'             => array(
						'sheetId'    => $param['sheetid'],
						'dimension'  => 'COLUMNS',
						'startIndex' => $param['startindex'],
						'endIndex'   => $param['endindex'],
					),
					'inheritFromBefore' => true,
				),
			)
		);
		$batchupdaterequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
			array(
				'requests' => $requests,
			)
		);
		return $batchupdaterequest;
	}

	/**
	 * Freeze Row Object
	 *
	 * @param int $sheetid Sheet ID.
	 * @param int $wpssle_freeze 0 - Unfreeze Row, 1 - Freeze Row.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function freezeobject( $sheetid = 0, $wpssle_freeze = 0 ) {
		$requestbody = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
			array(
				'requests' => array(
					'updateSheetProperties' => array(
						'properties' => array(
							'sheetId'        => $sheetid,
							'gridProperties' => array(
								'frozenRowCount' => $wpssle_freeze,
							),
						),
						'fields'     => 'gridProperties.frozenRowCount',
					),
				),
			)
		);
		return $requestbody;
	}

	/**
	 * Google_Service_Sheets_Spreadsheet Object
	 *
	 * @param string $spreadsheetname Spreadsheet Name.
	 * @param string $sheetname Sheet Name.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function newspreadsheetobject( $spreadsheetname = '', $sheetname = '' ) {
		$requestbody = new \Google_Service_Sheets_Spreadsheet(
			array(
				'properties' => array(
					'title' => $spreadsheetname,
				),
				'sheets'     => array(
					'properties' => array(
						'title' => $sheetname,
					),
				),
			)
		);
		return $requestbody;
	}

	/**
	 * Prepare parameter array.
	 *
	 * @param string $spreadsheetid Spreadsheet Name.
	 * @param string $range Sheet Name.
	 * @param array  $requestbody requestbody param.
	 * @param array  $params array.
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function setparamater( $spreadsheetid = '', $range = '', $requestbody = array(), $params = array() ) {
		$param                  = array();
		$param['spreadsheetid'] = $spreadsheetid;
		$param['range']         = $range;
		$param['requestbody']   = $requestbody;
		$param['params']        = $params;
		return $param;
	}

	/**
	 * Create Google_Service_Sheets_ValueRange Object.
	 *
	 * @param array $values_data Values Array.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function valuerangeobject( $values_data = array() ) {
		$requestbody = new \Google_Service_Sheets_ValueRange( array( 'values' => $values_data ) );
		return $requestbody;
	}

	/**
	 * Google_Service_Sheets_Spreadsheet Object
	 *
	 * @param string $spreadsheetname Spreadsheet Name.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function createspreadsheetobject( $spreadsheetname = '' ) {
		$wpssle_requestbody = new \Google_Service_Sheets_Spreadsheet(
			array(
				'properties' => array(
					'title' => $spreadsheetname,
				),
			)
		);
		return $wpssle_requestbody;
	}

	/**
	 * Google_Service_Sheets_BatchUpdateSpreadsheetRequest Object
	 *
	 * @param string $wpssle_sheetname Sheet Name.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function createsheetobject( $wpssle_sheetname = '' ) {
		$wpssle_requestbody = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
			array(
				'requests' => array(
					'addSheet' => array(
						'properties' => array(
							'title' => $wpssle_sheetname,
						),
					),
				),
			)
		);
		return $wpssle_requestbody;
	}

	/**
	 * Google_Service_Sheets_BatchUpdateSpreadsheetRequest Object
	 *
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function deletesheetobject() {
		$wpssle_requestbody = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
			array(
				'requests' => array(
					'deleteSheet' => array(
						'sheetId' => 0,
					),
				),
			)
		);
		return $wpssle_requestbody;
	}

	/**
	 * Create Google_Service_Sheets_ClearValuesRequest Object.
	 *
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function clearobject() {
		$requestbody = new \Google_Service_Sheets_ClearValuesRequest();
		return $requestbody;
	}

	/**
	 * Insert new column, Freeze first row to google spreadsheet.
	 *
	 * @param array $param contains spreadsheetid,requestbody.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function formatsheet( $param = array() ) {
		return parent::batchupdate( self::$instance_service, $param );
	}

	/**
	 * Update entry to google sheet.
	 *
	 * @param array $param contains spreadsheetid, range, requestbody, params.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function updateentry( $param = array() ) {
		return parent::update_entry( self::$instance_service, $param );
	}

	/**
	 * Append entry to google sheet.
	 *
	 * @param array $param contains spreadsheetid, range, requestbody, params.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function appendentry( $param = array() ) {
		return parent::append_entry( self::$instance_service, $param );
	}

	/**
	 * Create new spreadsheet in Google Drive.
	 *
	 * @param array $requestbody requestbody object.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function createspreadsheet( $requestbody = array() ) {
		return parent::create_spreadsheet( self::$instance_service, $requestbody );
	}

	/**
	 * Clear Sheet Value.
	 *
	 * @param array $param spreadsheetid,sheetname,requestbody.
	 * @since 1.0.0
	 *
	 * @return object.
	 */
	public function clear( $param = array() ) {
		return parent::clearsheet( self::$instance_service, $param );
	}
}
