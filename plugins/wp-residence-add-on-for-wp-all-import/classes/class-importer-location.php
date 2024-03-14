<?php
if ( ! class_exists( 'WPAI_WP_Residence_Location_Importer' ) ) {
    class WPAI_WP_Residence_Location_Importer extends WPAI_WP_Residence_Property_Importer {
        
		protected $add_on;
		public $helper;

        public function __construct( RapidAddon $addon_object ) {
			$this->add_on = $addon_object;
			$this->helper = new WPAI_WP_Residence_Add_On_Helper();
        }

        public function import( $post_id, $data, $import_options, $article ) {
			$field            = '_property_location_search';
			$address          = $data[$field];
			$lat              = $data['_property_latitude'];
			$long             = $data['_property_longitude'];
			$street_name      = null;
			$street_number    = null;
			$country          = null;
			$zip              = null;			
			$api_key          = null;
			$empty_api_key    = FALSE;
			$geocoding_failed = false;

			//  build search query
			if ( $data['location_settings'] == 'search_by_address' ) {

				$search = ( !empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

			} else {

				$search = ( !empty( $lat ) && !empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

			}

			// build api key
			if ( $data['location_settings'] == 'search_by_address' || $data['location_settings'] == 'search_by_coordinates' ) {

				if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {

					$api_key = '&key=' . $data['address_google_developers_api_key'];

				} elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {

					$api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

				}

			}

			// if all fields are updateable and $search has a value
			if ( empty( $article['ID'] ) or ( $this->can_update_meta( $field, $import_options ) && $this->can_update_meta( 'property_latitude', $import_options ) && $this->can_update_meta( 'property_longitude', $import_options ) && !empty ( $search ) ) ) {

				$this->helper->log( 'Updating Map Location' );

				if ( empty( $api_key ) ) {
					// You can't use the geocoding API without an API key anymore. Fail.
					$this->fail( 'empty_api_key' );
					return;
				}

				// build $request_url for api call
				$request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;

				$curl = curl_init();

				curl_setopt( $curl, CURLOPT_URL, $request_url );

				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

				$json = curl_exec( $curl );

				curl_close( $curl );

				// parse api response
				if ( !empty( $json ) ) {

					$details = json_decode( $json, true );

					if ( array_key_exists( 'status', $details ) ) {
						if ( $details['status'] == 'INVALID_REQUEST' || $details['status'] == 'ZERO_RESULTS' || $details['status'] == 'REQUEST_DENIED' || $details['status'] == 'OVER_QUERY_LIMIT' ) {
							// The request didn't work, go to fail.
							$this->fail( 'error', $details );
							return;
						}
					}

					$lat  = $details['results'][0]['geometry']['location']['lat'];
					$long = $details['results'][0]['geometry']['location']['lng'];
					$address = $details['results'][0]['formatted_address'];

					$components = $details['results'][0]['address_components'];

					foreach ( $components as $key => $data ) {

						if ( $data['types'][0] == 'street_number' ) {
							$street_number = $data['short_name'];
							continue;
						}

						if ( $data['types'][0] == 'route' ) {
							$street_name = $data['short_name'];
							continue;
						}

						if ( $data['types'][0] == 'country' ) {
							$country = $data['long_name'];
							continue;
						}

						if ( $data['types'][0] == 'postal_code' ) {
							$zip = $data['short_name'];
							continue;
						}

					}
					
					// update location fields
					$fields = array(
						'property_latitude'  => $lat,
						'property_longitude' => $long,
						'property_address'   => $street_number . ' ' . $street_name,
						'property_zip'       => $zip,
						'property_country'   => $country
					);

					$this->helper->log( '- Got location data from Geocoding API: ' . $request_url );
					$serialized_geocoding_data = json_encode( $fields );
					$this->helper->log( '- Geocoding data received: ' . $serialized_geocoding_data );
					$this->helper->log( '- Updating latitude and longitude' );

					foreach ( $fields as $key => $value ) {

						if ( empty( $article['ID'] ) or $this->can_update_meta( $key, $import_options ) ) {

							$this->helper->update_meta( $post_id, $key, $value );

						}
					}
				}
			}
        }

        public function fail( $type, $details = array() ) {
            switch ( $type ) {
                case 'empty_api_key':
                    $this->helper->log( "WARNING Geocoding failed because there is no API key in the import template." );
                    break;

                case 'error':
                    $this->helper->log( 'WARNING Geocoding failed with status: ' . $details['status'] );
                    if ( array_key_exists( 'error_message', $details ) ) {
                        $this->helper->log( 'WARNING Geocoding error message: ' . $details['error_message'] );
                    }
                    break;
            }
        }
    }
}
