<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Api\Normalizer\GuestNormalizer;
use WPDesk\ShopMagic\Components\Database\Abstraction\RequestToCriteria;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\Guest\GuestManager;
use WPDesk\ShopMagic\Customer\Guest\GuestRepository;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;

class CustomerController {

	/** @var CustomerRepository */
	private $repository;

	public function __construct( CustomerRepository $repository ) {
		$this->repository = $repository;
	}

	public function index(): \WP_REST_Response {
		return new \WP_REST_Response( $this->repository->find_all() );
	}

	public function guests(
		\WP_REST_Request $request,
		GuestRepository $repository,
		GuestNormalizer $normalizer
	): \WP_REST_Response {
		return new \WP_REST_Response(
			$repository->find_by( ...$this->parse_params( $request ) )
			           ->map( \Closure::fromCallable( [ $normalizer, 'normalize' ] ) )
			           ->to_array()
		);
	}

	private function parse_params( \WP_REST_Request $request ): array {
		$criteria = ( new RequestToCriteria() )
			->set_order_keys( [ 'updated' ] );

		[ $where, $order, $offset, $limit ] = $criteria->parse_request( $request );

		if ( empty( $order ) ) {
			$order['updated'] = 'DESC';
		}

		return [ $where, $order, $offset, $limit ];
	}

	public function guests_count(
		\WP_REST_Request $request,
		GuestRepository $repository
	): \WP_REST_Response {
		return new \WP_REST_Response(
			$repository->get_count( ...$this->parse_params( $request ) )
		);
	}

	public function delete_guest( int $id, GuestManager $manager ): \WP_REST_Response {
		try {
			$guest = $manager->find($id);
		} catch ( CannotProvideItemException $e ) {
			throw new HttpProblemException( [
				'title'  => __( 'Could not find guest to delete.', 'shopmagic-for-woocommerce' ),
				'detail' => $e->getMessage(),
			], \WP_Http::NOT_FOUND );
		}
		$manager->delete($guest);
		return new \WP_REST_Response(null, \WP_Http::NO_CONTENT);
	}
}
