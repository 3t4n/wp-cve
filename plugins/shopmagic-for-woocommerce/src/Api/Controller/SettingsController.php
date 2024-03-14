<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Admin\Form\FieldsCollection;
use WPDesk\ShopMagic\Admin\Settings\SettingsCollection;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\JsonSchemaNormalizer;

class SettingsController {

	/** @var SettingsCollection */
	private $settings;

	/** @var JsonSchemaNormalizer */
	private $normalizer;

	public function __construct(
		SettingsCollection $settings,
		JsonSchemaNormalizer $normalizer
	) {
		$this->settings = $settings;
		$this->normalizer = $normalizer;
	}

	public function index(): \WP_REST_Response {
		$result = [];
		foreach ( $this->settings as $tab ) {
			$container = $tab::get_settings_persistence();
			$tab->set_data( $container );
			$fields                         = $this->normalizer->normalize(
				new FieldsCollection( $tab->get_fields() )
			);
			$raw_data                       = $tab->get_data();
			$keys                           = array_map( [ $this, 'encode' ], array_keys( $raw_data ) );
			$data                           = array_combine( $keys, array_values( $raw_data ) );
			$result[ $tab::get_tab_slug() ] = [
				'label'  => $tab->get_tab_name(),
				'fields' => $fields,
				'data'   => $data,
			];
		}

		return new \WP_REST_Response( $result );
	}

	public function update( \WP_REST_Request $request ): \WP_REST_Response {
		$payload = $request->get_json_params();
		foreach ( $this->settings as $tab ) {
			$raw_data = $payload[ $tab::get_tab_slug() ] ?? [];
			$keys     = array_map( [ $this, 'decode' ], array_keys( $raw_data ) );
			$data     = array_combine( $keys, array_values( $raw_data ) );
			$tab->handle_request( $data );
			$container = $tab::get_settings_persistence();
			$tab_data  = $tab->get_data();
			array_walk(
				$tab_data,
				static function ( $value, $key ) use ( $container ): void {
					if ( ! empty( $key ) ) {
						$container->set( $key, $value );
					}
				}
			);
		}

		return new \WP_REST_Response( true );
	}

	private function encode( string $name ): string {
		return str_replace(
			'.',
			'~2',
			$name
		);
	}

	private function decode( string $name ): string {
		return str_replace(
			'~2',
			'.',
			$name
		);
	}

}
