<?php

namespace KraftPlugins\DemoImporterPlus;

class DemoAPI {
	protected static string $endpoint = '';

	public function __construct () {
		self::$endpoint = rtrim( trim( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI ), '/' ) . '/wp-json/demoimporterplusapi/v1/dipa-demos/';
	}


	public static function fetch ( $id ) {

		if ( $data = get_transient( "demo_importer_plus_import_data_{$id}" ) ) {
			return (object)$data;
		}

		$response = wp_remote_get( self::$endpoint . $id );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		return (object)$data;
	}
}
