<?php
/**
 * Sheet class for Stock Sync with Google Sheet for WooCommerce.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Use Google API.

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Sheet') ) {

	/**
	 * Sheet class for Stock Sync with Google Sheet for WooCommerce.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class Sheet extends Base {
		/**
		 * Credentials for Google API.
		 *
		 * @var array
		 */
		protected $credentials;

		/**
		 * Spreadsheet URL
		 *
		 * @var string
		 */
		protected $spreadsheet_url;

		/**
		 * Spreadsheet ID
		 *
		 * @var string
		 */
		protected $spreadsheet_id;

		/**
		 * Spreadsheet Tab ID
		 *
		 * @var string
		 */
		protected $sheet_id;

		/**
		 * Spreadsheet Tab Name
		 *
		 * @var string
		 */
		protected $sheet_tab;

		/**
		 * Constructor.
		 *
		 * @param string $sheet_id Spreadsheet ID.
		 * @param string $sheet_tab Spreadsheet Tab.
		 * @throws \Exception If plugin is not ready to use.
		 */
		public function __construct( $sheet_id = null, $sheet_tab = null ) {

			/**
			 * Check if plugin is ready to use
			 */

			if ( ssgsw()->is_plugin_ready() === false ) {
				throw new \Exception('Plugin is not ready to use.');
			}

			/**
			 * Default credentials
			 */
			$this->credentials = ssgsw_get_option('credentials');

			/**
			 * The Spreadsheet
			 */
			$this->spreadsheet_url = ssgsw_get_option('spreadsheet_url');

			$this->spreadsheet_id = $sheet_id ?? ssgsw_get_option('spreadsheet_id');

			if ( ! $this->spreadsheet_id ) {
				// Parse Spreadsheet ID from URL using regex.
				preg_match('/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $this->spreadsheet_url, $matches);
				$this->spreadsheet_id = $matches[1] ?? null;
			}

			/**
			 * Single Sheet
			 */
			$this->sheet_tab = $sheet_tab ?? ssgsw_get_option('sheet_tab');
			$this->sheet_id  = $sheet_tab ?? ssgsw_get_option('sheet_id');
		}

		/**
		 * Set Sheet ID.
		 *
		 * @param string $spreadsheet_id Spreadsheet ID.
		 * @return $this
		 */
		public function setID( $spreadsheet_id = null ) {
			if ( $spreadsheet_id ) {
				$this->spreadsheet_id = $spreadsheet_id;
			}

			return $this;
		}

		/**
		 * Set Sheet Tab Name.
		 *
		 * @param string $sheet_tab Spreadsheet Tab.
		 * @return $this
		 */
		public function setTab( $sheet_tab = null ) {
			if ( $sheet_tab ) {
				$this->sheet_tab = $sheet_tab;
			}

			return $this;
		}

		/**
		 * Generate access token for google sheet access.
		 *
		 * @return mixed
		 */
		protected function generate_access_token() {
			try {
				$credentials = $this->credentials;
				if ( is_array( $credentials ) ) {
					if ( ! array_key_exists( 'client_email', $credentials ) ) {
						return false;
					}
					if ( ! array_key_exists( 'private_key', $credentials ) ) {
						return false;
					}
					$client_email = $credentials['client_email'];
					$private_key = $credentials['private_key'];
					$now = time();
					$exp = $now + 3600;
					$payload = json_encode(
						[
							'iss' => $client_email,
							'aud' => 'https://oauth2.googleapis.com/token',
							'iat' => $now,
							'exp' => $exp,
							'scope' => 'https://www.googleapis.com/auth/spreadsheets',
						]
					);

					$header = json_encode([
						'alg' => 'RS256',
						'typ' => 'JWT',
					]);

					$base64_url_header = str_replace([ '+', '/', '=' ], [ '-', '_', '' ], base64_encode($header));
					$base64_url_payload = str_replace([ '+', '/', '=' ], [ '-', '_', '' ], base64_encode($payload));

					$signature = '';
					openssl_sign($base64_url_header . '.' . $base64_url_payload, $signature, $private_key, 'SHA256');
					$base64_url_signature = str_replace([ '+', '/', '=' ], [ '-', '_', '' ], base64_encode($signature));

					$jwt = $base64_url_header . '.' . $base64_url_payload . '.' . $base64_url_signature;

					$token_url = 'https://oauth2.googleapis.com/token';
					$body = [
						'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
						'assertion' => $jwt,
					];

					$response = wp_remote_post(
						$token_url, [
							'body' => $body,
						]
					);

					$response_body = wp_remote_retrieve_body($response);
					$token_data = json_decode($response_body, true);
					if ( is_array($token_data) ) {
						if ( array_key_exists( 'access_token', $token_data ) ) {
							$access_token = $token_data['access_token'];
							return $access_token;
						}
					}
					return false;
				} else {
					return false;
				}
			} catch ( \Exception $e ) {
				return false;
			}
		}
		/**
		 * Generate token access token.
		 *
		 * @return string
		 */
		public function get_token() {
			return $this->generate_access_token();
		}
		/**
		 * Get values from Google Sheet by range using wp_remote_post.
		 *
		 * @param string $range Range.
		 * @param string $dimension Dimension.
		 * @param string $sheet_tab Sheet Tab.
		 * @return array|false An array of values or false if there's an error.
		 */
		public function get_values( $range = null, $dimension = 'ROWS', $sheet_tab = null ) {
			if ( ! $range ) {
				return false;
			}

			if ( ! $sheet_tab ) {
				$sheet_tab = $this->sheet_tab;
			} else {
				$this->sheet_tab = $sheet_tab;
			}

			$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $this->spreadsheet_id . '/values/' . urlencode($sheet_tab . '!' . $range);
			$args = [
				'method' => 'GET',
				'headers' => [
					'Authorization' => 'Bearer ' . $this->get_token(),
				],
				'timeout' => 300,
			];

			$response = wp_remote_request($url, $args);

			if ( is_wp_error($response) ) {
				return false;
			}
			$response_body = wp_remote_retrieve_body($response);
			$response_data = json_decode($response_body, true);
			if ( isset( $response_data['values'] ) ) {
				return $response_data['values'];
			}
			return false;
		}

		/**
		 * Get first column's value from Google Sheet using wp_remote_request.
		 *
		 * @return array|false An array of values or false if there's an error.
		 */
		public function get_first_columns() {
			$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $this->spreadsheet_id . '/values/' . urlencode($this->sheet_tab . '!A:A');
			$args = [
				'method' => 'GET',
				'headers' => [
					'Authorization' => 'Bearer ' . $this->get_token(),
				],
				'timeout' => 300,
			];
			$response = wp_remote_request($url, $args);
			if ( is_wp_error( $response ) ) {
				return false;
			}
			$response_body = wp_remote_retrieve_body($response);
			$response_data = json_decode($response_body, true);
			if ( isset( $response_data['values'] ) ) {
				return $response_data['values'];
			}
			return false;
		}
		/**
		 * Get first column's value from Google Sheet using wp_remote_request.
		 *
		 * @return array|false An array of values or false if there's an error.
		 */
		public function get_first_columns2() {
			$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $this->spreadsheet_id . '/values/' . urlencode($this->sheet_tab . '!A:C');
			$args = [
				'method' => 'GET',
				'headers' => [
					'Authorization' => 'Bearer ' . $this->get_token(),
				],
				'timeout' => 300,
			];
			$response = wp_remote_request($url, $args);
			if ( is_wp_error( $response ) ) {
				return false;
			}
			$response_body = wp_remote_retrieve_body($response);
			$response_data = json_decode($response_body, true);
			if ( isset( $response_data['values'] ) ) {
				return $response_data['values'];
			}
			return false;
		}
		/**
		 * Updates values in Google Sheet by range using wp_remote_post.
		 *
		 * @param string $row_number Row number.
		 * @param array  $values Values.
		 * @param string $dimension Dimension.
		 * @return bool True if the update was successful, false otherwise.
		 */
		public function update_single_row_values( $row_number = null, $values = null, $dimension = null ) {
			if ( ! $row_number || ! $values ) {
				return false;
			}
			$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $this->spreadsheet_id . '/values/' . urlencode($this->sheet_tab . '!' . $row_number . ':' . $row_number) . '?valueInputOption=USER_ENTERED';

			$args = array(
				'method' => 'PUT',
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->get_token(),
					'Content-Type' => 'application/json',
				),
				'body' => json_encode(array(
					'values' => [ $values ],
				)),
				'timeout' => 300,
			);

			$response = wp_remote_request($url, $args);

			if ( is_wp_error($response) ) {
				return false;
			}

			$response_body = wp_remote_retrieve_body($response);
			$response_data = json_decode($response_body, true);
			if ( isset($response_data['updates']['updatedRows']) && $response_data['updates']['updatedRows'] > 0 ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get columns letter
		 *
		 * @param string $column_number columnnumber.
		 */
		public function getColumnLetter( $column_number ) {
			$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$column_letter = '';

			while ( $column_number > 0 ) {
				$column_number--;
				$column_letter = $letters[ $column_number % 26 ] . $column_letter;
				$column_number = intval($column_number / 26);
			}

			return $column_letter;
		}
		/**
		 * Delete single row value using wp_remote_post.
		 *
		 * @param int $row_number Row number to delete (starting from 1).
		 * @return bool
		 */
		public function delete_single_row( $row_number = 2 ) {
			if ( ! $row_number ) {
				return false;
			}

			$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $this->spreadsheet_id . ':batchUpdate';

			$request = array(
				'deleteDimension' => array(
					'range' => array(
						'sheetId' => $this->sheet_id,
						'dimension' => 'ROWS',
						'startIndex' => $row_number - 1,
						'endIndex' => $row_number,
					),
				),
			);

			$args = array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->get_token(),
					'Content-Type' => 'application/json',
				),
				'body' => json_encode(array(
					'requests' => array( $request ),
				)),
				'timeout' => 300,
			);

			$response = wp_remote_request($url, $args);

			if ( is_wp_error($response) ) {
				return false;
			}

			$response_code = wp_remote_retrieve_response_code($response);

			if ( 200 === $response_code ) {
				return true;
			} else {
				return false;
			}
		}
		/**
		 * Append new row to Google Sheets using wp_remote_post.
		 *
		 * @param array  $data Data to append as a new row.
		 * @param string $type Type of append (e.g., 'test' or 'deleted_product').
		 * @return bool True if successful, false on failure.
		 */
		public function append_new_row( $data, $type = 'test' ) {
			if ( ! $data ) {
				return false;
			}
			try {
				$access_token = $this->get_token();
				$spreadsheet_id     = $this->spreadsheet_id;
				$sheet_name = $this->sheet_tab;
				$api_url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$sheet_name}:append?valueInputOption=USER_ENTERED";
				$data = array(
					'values' => [ $data ],
				);
				$request_data = array(
					'majorDimension' => 'ROWS',
					'values' => $data['values'],
				);
				$headers = array(
					'Authorization' => "Bearer {$access_token}",
					'Content-Type' => 'application/json',
				);
				$response = wp_remote_post(
					$api_url, array(
						'headers' => $headers,
						'body' => json_encode($request_data),
						'timeout' => 300,
					)
				);
				$response_body = wp_remote_retrieve_body($response);
				$response_data = json_decode($response_body, true);
				if ( isset($response_data['updates']['updatedRows']) ) {
					if ( 'deleted_product' === $type ) {
						$this->sort_google_sheet_data_wp_remote($spreadsheet_id, $access_token );
					}
					return true;
				} else {
					return false;
				}
			} catch ( \Throwable $error ) {
				return false;
			}
		}
		/**
		 * Sort Google Sheet data based on the first column using wp_remote_post.
		 *
		 * @param string $spreadsheet_id The ID of the Google Spreadsheet.
		 * @param string $access_token The access token for authorization.
		 *
		 * @return bool True if successful, false on failure.
		 */
		public function sort_google_sheet_data_wp_remote( $spreadsheet_id, $access_token ) {
			try {
				$sort_range = array(
					'sheetId' => $this->sheet_id,
					'startRowIndex' => 1,
					'endRowIndex' => 0,
					'startColumnIndex' => 0,
					'endColumnIndex' => null,
				);

				$sort_spec = array(
					'dimensionIndex' => 0,
					'sortOrder' => 'ASCENDING',
				);

				$sort_range_request = array(
					'sortRange' => array(
						'range' => $sort_range,
						'sortSpecs' => array( $sort_spec ),
					),
				);

				$batch_update_request = array(
					'requests' => array( $sort_range_request ),
				);
				$sort_range['endRowIndex'] = null;
				$sort_range_request['sortRange']['range'] = $sort_range;
				$batch_update_request['requests'] = array( $sort_range_request );
				// Build the URL for the batch update.
				$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $spreadsheet_id . ':batchUpdate';

				// Prepare the request arguments.
				$headers = array(
					'Authorization' => 'Bearer ' . $access_token,
					'Content-Type' => 'application/json',
				);

				$body = json_encode($batch_update_request);

				$args = array(
					'body' => $body,
					'headers' => $headers,
					'method' => 'POST',
					'timeout' => 300,
				);
				$response = wp_remote_request($url, $args);
				if ( is_wp_error($response) ) {
					return false;
				}
				$response_code = wp_remote_retrieve_response_code($response);
				if ( 200 === $response_code ) {
					return true;
				} else {
					return false;
				}
			} catch ( \Exception $e ) {
				return false;
			}
		}
		/**
		 * Get rows from google sheet by range.
		 *
		 * @param string $range Range.
		 * @param string $sheet_tab Sheet Tab.
		 */
		public function get_rows( $range = null, $sheet_tab = null ) {
			if ( ! $range ) {
				return false;
			}
			return $this->get_values($range, 'ROWS', $sheet_tab);
		}

		/**
		 * Get columns from google sheet by range.
		 *
		 * @param string $range Range.
		 * @param string $sheet_tab Sheet Tab.
		 * @return array|bool
		 */
		public function get_columns( $range = null, $sheet_tab = null ) {
			if ( ! $range ) {
				return false;
			}
			return $this->get_values($range, 'COLUMNS', $sheet_tab);
		}
		/**
		 * Updates values in google sheet by range.
		 *
		 * @param  string $range     Range.
		 * @param  array  $values    Values.
		 * @param  string $dimension Dimension.
		 * @return mixed
		 */
		public function update_values( $range = null, $values = null, $dimension = null ) {
			if ( ! $range || ! $values ) {
				return false;
			}
			try {
				$access_token = $this->get_token();
				$this->reset_sheet($access_token);
				$spreadsheet_id     = $this->spreadsheet_id;
				$sheet_name = $this->sheet_tab;
				$api_url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$sheet_name}:append?valueInputOption=USER_ENTERED";
				$data = array(
					'values' => $values,
				);
				$request_data = array(
					'majorDimension' => 'ROWS',
					'values' => $data['values'],
				);
				$headers = array(
					'Authorization' => "Bearer {$access_token}",
					'Content-Type' => 'application/json',
				);
				$response = wp_remote_post(
					$api_url, array(
						'headers' => $headers,
						'body' => json_encode($request_data),
						'timeout' => 300,
					)
				);
				$response_body = wp_remote_retrieve_body($response);
				$response_data = json_decode($response_body, true);
				if ( isset($response_data['updates']['updatedRows']) ) {
					   return true;
				} else {
					return false;
				}
			} catch ( \Throwable $error ) {
				return $error;
			}
		}

		/**
		 * Updates rows in google sheet by range.
		 *
		 * @param string $range Range.
		 * @param array  $values Values.
		 * @return mixed
		 */
		public function update_row_values( $range = null, $values = null ) {

			if ( ! $range || ! $values ) {
				return false;
			}

			return $this->update_values($range, $values, 'ROWS');
		}

		/**
		 * Updates columns in google sheet by range.
		 *
		 * @param string $range Range.
		 * @param array  $values Values.
		 * @return mixed
		 */
		public function update_row_columns( $range = null, $values = null ) {
			if ( ! $range || ! $values ) {
				return false;
			}
			return $this->update_values($range, $values, 'COLUMNS');
		}
		/**
		 * Initializes the Google Sheets API service.
		 *
		 * @throws \Exception If the API client library is not found.
		 * @return mixed
		 */
		public function initialize() {
			try {
				$sheets = $this->get_sheet_tab();
				$sheet = array_filter(
					$sheets, function ( $sheet ) {
						return $sheet->properties->title === $this->sheet_tab;
					}
				);

				/**
				 * Getting Sheet ID of working sheet
				 */
				if ( ! $sheet ) {
					   // if no sheet title matched, create new one with the title of the sheet.
					   $response = $this->create_sheet_tab($this->sheet_tab);

					   $sheet = $response->replies[0]->addSheet;
				} else {
					$sheet = array_values($sheet)[0];
				}

				/**
				 * Save working Sheet ID to database for later use.
				 */

				ssgsw_update_option('sheet_id', $sheet->properties->sheetId);

				$updated = $this->sync_sheet_headers();
				return $updated;

			} catch ( \Exception $e ) {
				throw new \Exception( esc_html__('Unable to access Google Sheet. Please check required permissions.', 'stock-sync-with-google-sheet-for-woocommerce') );
			}
		}
		/**
		 * Creates a new sheet tab.
		 *
		 * @param string $sheet_name Sheet Name.
		 */
		public function create_sheet_tab( $sheet_name = null ) {
			if ( ! $sheet_name ) {
				$sheet_name = $this->sheet_tab;
			}
			try {
				$access_token = $this->get_token();
				$spreadsheet_id     = $this->spreadsheet_id;
				$api_url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}:batchUpdate";
				$headers = array(
					'Authorization' => "Bearer {$access_token}",
					'Content-Type' => 'application/json',
				);
				$request_body = json_encode(
					array(
						'requests' => array(
							array(
								'addSheet' => array(
									'properties' => array(
										'title' => $sheet_name,
									),
								),
							),
						),
					)
				);
				$response = wp_remote_post(
					$api_url, array(
						'headers' => $headers,
						'body' => $request_body,
						'timeout' => 300,

					)
				);
				$response_body = wp_remote_retrieve_body($response);
				$response_data = json_decode($response_body, true);
				return $response_data;
			} catch ( \Exception $e ) {
				return false;
			}
		}
		/**
		 * Syncs sheet headers
		 *
		 * @return mixed
		 */
		public function sync_sheet_headers() {
			try {
				$column   = new Column();
				$keys     = $column->get_column_names();
				$response = $this->update_row_values('A1', [ $keys ]);
				return $response;
			} catch ( \Exception $e ) {
				return false;
			}
		}
		/**
		 * Resets sheet.
		 *
		 * @return mixed
		 */
		public function reset_sheet() {
			try {
				$spreadsheet_id = $this->spreadsheet_id;
				$sheet_name     = $this->sheet_tab;
				$api_url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$sheet_name}:clear";
				if ( empty($access_token) ) {
					$access_token = $this->get_token();
				}
				$headers = array(
					'Authorization' => "Bearer {$access_token}",
				);
				$response = wp_remote_post(
					$api_url, array(
						'headers' => $headers,
					)
				);
				$response_code = wp_remote_retrieve_body($response);
				if ( 204 === $response_code ) {
					   return true;
				} else {
					return false;
				}
			} catch ( \Exception $e ) {
				return $e;
			}
		}
		/**
		 * Freeze or unfreeze headers in Google Sheets using wp_remote_post.
		 *
		 * @param bool $freeze Whether to freeze headers (true) or unfreeze (false).
		 * @return bool True if successful, false on failure.
		 */
		public function freeze_headers( $freeze = true ) {
			try {
				$frozen_row_count = $freeze ? 1 : 0;
				$frozen_column_count = $freeze ? 1 : 0;
				// Build the batch update request to freeze/unfreeze headers.
				$batch_update_request = array(
					'requests' => array(
						array(
							'updateSheetProperties' => array(
								'properties' => array(
									'sheetId' => $this->sheet_id,
									'gridProperties' => array(
										'frozenRowCount' => $frozen_row_count,
										'frozenColumnCount' => $frozen_column_count,
									),
								),
								'fields' => 'gridProperties.frozenRowCount,gridProperties.frozenColumnCount',
							),
						),
					),
				);

				// Build the URL.
				$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . $this->spreadsheet_id . ':batchUpdate';

				// Prepare the request arguments.
				$args = array(
					'method' => 'POST',
					'headers' => array(
						'Authorization' => 'Bearer ' . $this->get_token(),
						'Content-Type' => 'application/json',
					),
					'body' => json_encode($batch_update_request),
					'timeout' => 300,
				);

				// Send the POST request.
				$response = wp_remote_post($url, $args);

				if ( is_wp_error($response) ) {
					return false;
				}

				$response_code = wp_remote_retrieve_response_code($response);

				if ( 200 === $response_code ) {
					return true;
				} else {
					return false;
				}
			} catch ( \Exception $e ) {
				return false;
			}
		}
		/**
		 * Get sheet all tab
		 *
		 * @return array
		 */
		public function get_sheet_tab() {
			$access_token = $this->get_token();
			$spreadsheet_id = $this->spreadsheet_id;
			$api_url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}?access_token={$access_token}";
			$headers = array(
				'Authorization' => "Bearer {$access_token}",
			);
			$response = wp_remote_get($api_url, array( 'headers' => $headers ));

			if ( is_wp_error($response) ) {
				return $response->get_error_message();
			} else {
				$response_body = wp_remote_retrieve_body($response);
				$data = json_decode($response_body, true);
				$sheet_titles = [];
				if ( isset($data['sheets']) ) {
					foreach ( $data['sheets'] as $sheet ) {
						$sheet_titles[] = $sheet['properties']['title'];
					}
				}
				return $sheet_titles;
			}
		}
	}
}
