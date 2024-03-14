<?php namespace MSMoMDP\Std\Core;

class GoogleGeo {

	private $apiKey;
	public function __construct( $apiKey ) {
		$this->apiKey = $apiKey;
	}

	public static function get_map_marker( array $geoData, bool $includeSourceData = false, string $preferedNameTag = 'geo-long-name' ) : array {
		$res = array();
		if ( $geoData ) {
			$lat = Arr::sget( $geoData, 'geo-gps-latitude' );
			$lng = Arr::sget( $geoData, 'geo-gps-longitude' );

			if ( $lat && $lng ) {
				$res = array(
					'position' => array(
						'lat' => $lat,
						'lng' => $lng,
					),
					'title'    => self::get_best_geo_name( $geoData, $preferedNameTag ),
				);
			}
			$sourceGeoData = Arr::sget( $geoData, 'geo-source-data', null );
			if ( $includeSourceData && ! empty( $sourceGeoData ) ) {
				$sourceGeoStructure = json_decode( $sourceGeoData, true );
				if ( $sourceGeoStructure && array_key_exists( 'results', $sourceGeoStructure ) && ! empty( $sourceGeoStructure['results'] ) > 0 ) {
					$res['geoSourceData'] = $sourceGeoStructure['results'][0];
				}
			}
		}
		return $res;
	}

	public static function get_best_geo_name( array $geoData, string $preferedTag = 'geo-long-name' ) : string {
		$res                  = '';
		$preferedTagsFallback = array( $preferedTag, 'geo-long-name', 'geo-short-name', 'geo-name-orig', 'geo-address' );

		foreach ( $preferedTagsFallback as $tag ) {
			$res = Arr::sget( $geoData, $tag, '' );
			if ( ! empty( $res ) ) {
				break;
			}
		}
		return $res;
	}

	public function get_data( string $geoNameOrig, string $address, string $components, string $geoParent = '', $preferedAddressType = '' ) {
		$googleMapApiKey = $this->apiKey;
		$addressSlug     = str_replace( ' ', '%20', $address );
		$componentsSlug  = str_replace( ' ', '%20', $components );
		$addressQuery    = ( empty( $addressSlug ) ) ? '' : 'address=' . $addressSlug . '&';
		$componentsQuery = ( empty( $componentsSlug ) ) ? '' : 'components=' . $componentsSlug . '&';

		$reqQuery       = "https://maps.googleapis.com/maps/api/geocode/json?${addressQuery}${componentsQuery}language=en&key=${googleMapApiKey}";
		$result         = self::get_initialized_data( $geoNameOrig, $geoParent );
		$googleRespJson = file_get_contents( $reqQuery );
		if ( $googleRespJson ) {
			$result['geo-source-data'] = $googleRespJson;
			$googleResp                = json_decode( $googleRespJson, true );
			$respStatus                = $googleResp['status'];
			if ( $respStatus == 'OK' && array_key_exists( 'results', $googleResp ) && count( $googleResp['results'] ) > 0 ) {
				$googleFirstRessult          = &$googleResp['results'][0];
				$result['geo-gps-latitude']  = Arr::get( $googleFirstRessult, 'geometry.location.lat', '' );
				$result['geo-gps-longitude'] = Arr::get( $googleFirstRessult, 'geometry.location.lng', '' );
				$result['geo-address']       = Arr::get( $googleFirstRessult, 'formatted_address', '' );
				$addressIdx                  = 0;
				if ( ! empty( $preferedAddressType ) ) {
					$addressResults = Arr::sget( $googleFirstRessult, 'address_components', null );
					if ( $addressResults ) {
						foreach ( $addressResults as $key => $val ) {
							$types = Arr::sget( $val, 'types' );
							if ( $types && in_array( $preferedAddressType, $types ) ) {
								$addressIdx = $key;
							}
						}
					}
				}
				$result['geo-short-name'] = Arr::get( $googleFirstRessult, "address_components.${addressIdx}.short_name", '' );
				$result['geo-long-name']  = Arr::get( $googleFirstRessult, "address_components.${addressIdx}.long_name", '' );
			}
		} else {
			$result['geo-source-data'] = "No Response from ${reqQuery}";
		}
		return $result;
	}

	public static function get_initialized_data( string $geoNameOrig = '', string $geoParent = '' ) {
		return array(
			'geo-name-orig'     => $geoNameOrig,
			'geo-long-name'     => '',
			'geo-short-name'    => '',
			'geo-address'       => '',
			'geo-gps-latitude'  => '',
			'geo-gps-longitude' => '',
			'geo-source-data'   => '',
			'geo-parent'        => $geoParent,
		);
	}
}
