<?php

defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Handle interactions with the http://app.schemaapp.com server
 *
 * @author Mark van Berkel
 */
class SchemaServer {

		private $options;
		private $resource;
		public $resource_url	= '';
		public $transient_id	= '';
		public $transient		= false;
		public $data_sources	= array();

		const EDITOR = "https://app.schemaapp.com/editor";


		public function __construct( $uri = '' ) {
			$this->options = get_option( 'schema_option_name' );

			if ( ! empty( $uri ) ) {
				$this->resource = $uri;
			} else {
				// customUrl is captured via output from Filter hook. Otherwise, returns empty string
				$customUrl = apply_filters('hunch_schema_page_path_url', '');
				
				$this->resource = HunchSchema_Thing::getPermalink($customUrl);
			}
		}


		/**
		 * Get a resource's schema data from transient cache.
 		 *
 		 * @uses get_transient()
 		 * @param string $uri Post/Page permalink.
 		 * @param bool $pretty Whether to prettify JSON data or not.
 		 * @return string JSON encoded schema markup.
 		 */
		public function getResource( $uri = '', $pretty = false ) {
			if ( empty( $this->options['graph_uri'] ) ) {
				return '';
			}


			$resource = '';

			if ( ! empty( $uri ) ) {
				$resource = $uri;
			} elseif ( ! empty( $this->resource ) ) {
				$resource = $this->resource;
			} else {
				return '';
			}


			$account_id			= str_replace( array( 'http://schemaapp.com/db/', 'https://schemaapp.com/db/' ), '', $this->options['graph_uri'] );
			$this->resource_url	= $resource; // urldecode() not necessary as WP auto converts accent/special characters in permalink
			$this->data_sources	= array( "https://data.schemaapp.com/{$account_id}/" . trim( base64_encode( $this->resource_url ), '=' ) );
			$this->transient_id	= 'HunchSchema-Markup-' . md5( $this->resource_url );
			$transient			= get_transient( $this->transient_id );


			$data_sources_additional = ( array ) apply_filters( 'hunch_schema_markup_api_data_source', $account_id, $this->resource_url );

			if ( ! empty( $data_sources_additional ) ) {
				foreach ( $data_sources_additional as $data_source ) {
					if ( filter_var( $data_source, FILTER_VALIDATE_URL ) ) {
						$this->data_sources[] = $data_source;
					}
				}
			}


			// Only check if transient exists and allow for empty values
			if ( $transient !== false ) {
				$this->transient = true;

				if ( $pretty && ! empty( $transient ) && version_compare( phpversion(), '5.4.0', '>=' ) ) {
					$transient = wp_json_encode( json_decode( $transient ), JSON_PRETTY_PRINT );
				}

				return $transient;
			}


			if ( ! empty( $this->options['SchemaDefaultEditorMarkupBackgroundSync'] ) ) {
				// Check to avoid triggering of schedule event when request is originated from WP Cron
				if ( ! defined( 'DOING_CRON' ) || ! DOING_CRON ) {
					// Delegate API schema fetching to cron and pass requested resource as argument
					wp_schedule_single_event( time(), 'schema_app_cron_resource_from_api', array( $resource ) );
				}
			} else {
				return $this->getResourceFromAPI();
			}


			return '';
		}


		/**
		 * Get a resource's schema data through API. Markup returned by all data sources are merged together.
 		 *
 		 * @uses SchemaServer->getResource()
 		 * @uses wp_remote_get()
 		 * @uses set_transient()
 		 * @param string $uri Post/Page permalink. For Cron event supplying $uri will prepare necessary resource attributes by calling SchemaServer->getResource().
 		 * @return string JSON encoded schema markup.
 		 */
		public function getResourceFromAPI( $uri = '' ) {
			if ( $uri ) {
				// Prepare necessary resource attributes
				$this->getResource( $uri );
			}

			$schema_data = array();

			foreach ( $this->data_sources as $data_source ) {
				// Get content from data source and confirm successful response
				$request = wp_remote_get( $data_source );
				if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
					return '';
				}

				// Get source from headers if it exists
				$source = isset($request['headers']['x-amz-meta-source']) ? $request['headers']['x-amz-meta-source'] : null;

				$response = wp_remote_retrieve_body( $request );

				// Check if editor schema data is not empty or literal 'null' or empty json array
				if ( ! empty( $response ) && $response !== 'null' && $response !== '[]' ) {
					$response_json = json_decode( $response );
                    $schema_data[] = ['markup' => $response_json, 'source' => $source];
				}
			}

			if ( count( $schema_data ) == 1 ) {
				$schema_data = reset( $schema_data );
			}

			$schema_json = empty( $schema_data ) ? '' : wp_json_encode( $schema_data );

			// Expiry is 7 days for empty schema data or else 1 day
			$transient_expiry = empty( $schema_json ) ? 604800 : 86400;
			// First delete then set; set method only updates expiry time if transient already exists
			delete_transient( $this->transient_id );
			set_transient( $this->transient_id, $schema_json, $transient_expiry );

			return $schema_json;
		}


		/**
		 * Get the Link to Update a Resource that exists
		 * 
		 * @param type $uri
		 * @return string
		 */
		public function updateLink() {
			$link = self::EDITOR . "?resource=" . $this->resource;
			return $link;
		}

		/**
		 * Get the link to create a new resource
		 * 
		 * @param type $uri
		 * @return string
		 */
		public function createLink() {
			$link = self::EDITOR . "?create=" . $this->resource;
			return $link;
		}

		/**
		 * Activate Licenses, sends license key to Hunch Servers to confirm purchase 
		 * 
		 * @param type $params
		 */
		public function activateLicense( $params ) {
			$response = wp_remote_post( 'https://app.schemaapp.com/schemaApi/license/addSite', array(
				'sslverify' => false,
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body' => wp_json_encode( $params ),
			));

			$response_code = wp_remote_retrieve_response_code( $response );
			$response_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( in_array( $response_code, array( 200, 201 ) ) ) {
				return array( true, $response_data->status );
			} else {
				return array( false, "{$response_data->status} : {$response_data->error}" );
			}
		}

}
