<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Api\Normalizer\OutcomeNormalizer;
use WPDesk\ShopMagic\Components\Database\Abstraction\RequestToCriteria;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeManager;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeRepository;

class OutcomesController {

	/** @var OutcomeRepository */
	private $repository;

	/** @var \WPDesk\ShopMagic\Api\Normalizer\OutcomeNormalizer */
	private $normalizer;

	public function __construct( OutcomeRepository $repository, OutcomeNormalizer $normalizer ) {
		$this->repository = $repository;
		$this->normalizer = $normalizer;
	}

	public function index( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->repository->find_by( ...$this->parse_params( $request ) )
			                 ->map( \Closure::fromCallable( [ $this->normalizer, 'normalize' ] ) )
			                 ->to_array()
		);
	}

	private function parse_params( \WP_REST_Request $request ): array {
		$criteria = ( new RequestToCriteria() )
			->set_order_keys( [ 'updated' ] );

		[ $where, $order, $offset, $limit ] = $criteria->parse_request( $request );

		$filters = $request->get_param( 'filters' );

		// By default, we look for finished automations.
		$where['finished'] = 1;

		if ( isset( $filters['status'] ) && in_array( $filters['status'], [ 'completed', 'failed' ] ) ) {
			$where['success'] = $filters['status'] === 'completed' ? 1 : 0;
		}

		if ( isset( $filters['automation'] ) && is_numeric( $filters['automation'] ) ) {
			$where['automation_id'] = $filters['automation'];
		}

		if ( empty( $order ) ) {
			$order['updated'] = 'DESC';
		}

		return [ $where, $order, $offset, $limit ];
	}

	public function show( int $id ): \WP_REST_Response {
		return new \WP_REST_Response( $this->normalizer->normalize( $this->repository->find( $id ) ) );
	}

	public function count( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response( $this->repository->get_count( ...$this->parse_params( $request ) ) );
	}

	public function delete( int $id, OutcomeManager $manager ): \WP_REST_Response {
		try {
			$outcome = $manager->find($id);
		} catch (CannotProvideItemException $e) {
			throw new HttpProblemException( [
				'title'  => __( 'Could not find outcome to delete.', 'shopmagic-for-woocommerce' ),
				'detail' => $e->getMessage(),
			], \WP_Http::NOT_FOUND );
		}
		$manager->delete($outcome);
		return new \WP_REST_Response(null, \WP_Http::NO_CONTENT);
	}
}
