<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigFactory;
use WP_REST_Request;
use WP_REST_Response;

final class InjectLabelToResponseObject implements MiddlewareInterface {

	/**
	 * @var ConfigFactory
	 */
	private $config_factory;

	public function __construct( ConfigFactory $configFactory ) {
		$this->config_factory = $configFactory;
	}

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {
		$response = $handler->handle( $request );

		$data = $this->config_factory->make( (array) $response->get_data() );

//		\TreBiMeteo\log( $response->get_data() );

		foreach ( (array) $data->get( 'localita.previsione_giorno' ) as $key => $value ) {
			$value['tempo_medio']['temp_unit'] = '°C';
			$value['tempo_medio']['t_min_label'] = __( 'T. min.', '' );
			$value['tempo_medio']['t_max_label'] = __( 'T. max.', '' );
			$value['tempo_medio']['probabilita_prec_label'] = __( 'Rain probability', '' );
			$value['tempo_medio']['probabilita_prec_unit'] = '%';
			$value['tempo_medio']['venti_label'] = __( 'Wind speed', '' );
			$value['tempo_medio']['venti_unit_singular'] = 'knot';
			$value['tempo_medio']['venti_unit_plural'] = 'knots';

			$data->merge(
				[
					'localita'	=> [
						'previsione_giorno'	=> [
							$key => $value
						]
					]
				]
			);
		}

//		\TreBiMeteo\log( $data->get( 'localita.previsione_giorno' ) );

		$response->set_data( $data->toArray() );

		return $response;
	}
}
