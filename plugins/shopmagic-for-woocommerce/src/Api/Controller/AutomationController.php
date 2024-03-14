<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Api\Normalizer\Denormalizer;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Api\Normalizer\Normalizer;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\PersisterException;
use WPDesk\ShopMagic\Components\Database\Abstraction\RequestToCriteria;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\Components\UrlGenerator\RestUrlGenerator;
use WPDesk\ShopMagic\Exception\AutomationNotFound;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationNotSaved;
use WPDesk\ShopMagic\Workflow\Automation\AutomationObjectManager;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;

class AutomationController {

	/** @var ObjectRepository<Automation> */
	private $repository;

	/** @var LoggerInterface */
	private $logger;

	/** @var Denormalizer<Automation> */
	private $denormalizer;

	/** @var Normalizer<Automation> */
	private $normalizer;

	/**
	 * @param ObjectRepository<Automation> $repository
	 * @param LoggerInterface              $logger
	 */
	public function __construct( ObjectRepository $repository, LoggerInterface $logger ) {
		$this->repository = $repository;
		$this->logger     = $logger;
	}

	/**
	 * @param Normalizer<Automation> $normalizer
	 *
	 * @return void
	 */
	public function set_normalizer( Normalizer $normalizer ): void {
		$this->normalizer = $normalizer;
	}

	/**
	 * @param Denormalizer<Automation> $denormalizer
	 *
	 * @return void
	 */
	public function set_denormalizer( Denormalizer $denormalizer ): void {
		$this->denormalizer = $denormalizer;
	}

	public function index( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->repository->find_by( ...$this->parse_params( $request ) )
					->map( \Closure::fromCallable( [ $this->normalizer, 'normalize' ] ) )
						->to_array()
		);
	}

	public function list_children( int $id, \WP_REST_Request $request ): \WP_REST_Response {
		[ $criteria, $order, $offset, $limit ] = $this->parse_params( $request );

		$criteria['post_parent'] = $id;

		// We need to overwrite 'any' as we do want trashed automations to be listed.
		if ( $criteria['post_status'] === 'any' ) {
			$criteria['post_status'] = [ 'publish', 'draft', 'trash' ];
		}

		return new \WP_REST_Response(
			$this->repository->find_by( $criteria, $order, $offset, $limit )
					->map( \Closure::fromCallable( [ $this->normalizer, 'normalize' ] ) )
						->to_array()
		);
	}

	private function parse_params( \WP_REST_Request $request ): array {
		$criteria = ( new RequestToCriteria() )
			->set_order_keys( [ 'name' ] );

		[ $_, $raw_order, $offset, $limit ] = $criteria->parse_request( $request );

		$filters = $request->get_param( 'filters' );
		if ( ! is_array( $filters ) ) {
			$filters = [];
		}

		$where = [];
		$order = [];

		if ( isset( $filters['status'] ) && in_array(
			$filters['status'],
			[ 'publish', 'draft', 'trash' ],
			true
		) ) {
			$where['post_status'] = $filters['status'];
		} else {
			$where['post_status'] = 'any';
		}

		if ( isset( $filters['event'] ) ) {
			$where['meta_key']   = '_event';
			$where['meta_value'] = sanitize_text_field( $filters['event'] );
		}

		if ( isset( $filters['name'] ) ) {
			$where['s'] = sanitize_text_field( $filters['name'] );
		}

		if ( isset( $filters['parent'] ) ) {
			$where['post_parent'] = abs( (int) $filters['parent'] );
		}

		if ( isset( $filters['ids'] ) ) {
			$where['post__in'] = array_map( 'absint', (array) $filters['ids'] );
		}

		if ( isset( $raw_order['name'] ) ) {
			$order['title'] = $raw_order['name'];
		}

		return [ $where, $order, $offset, $limit ];
	}

	/**
	 * @param int $id
	 *
	 * @return \WP_REST_Response
	 */
	public function show( int $id ): \WP_REST_Response {
		try {
			return new \WP_REST_Response(
				$this->normalizer->normalize(
					$this->repository->find( $id )
				)
			);
		} catch ( AutomationNotFound $e ) {
			throw new HttpProblemException(
				[
					'title'  => __( 'Automation not found', 'shopmagic-for-woocommerce' ),
					'detail' => sprintf(
						__( 'There is no automation with ID %d', 'shopmagic-for-woocommerce' ),
						$id
					),
				],
				\WP_Http::NOT_FOUND
			);
		}
	}

	public function delete(
		int $id,
		AutomationObjectManager $manager,
		AutomationRepository $repository
	): \WP_REST_Response {
		try {
			$automation = $repository->find( $id );
		} catch ( AutomationNotFound $e ) {
			throw new HttpProblemException(
				[
					'title' => $e->getMessage(),
				],
				\WP_Http::NOT_FOUND
			);
		} catch ( \Exception $e ) {
			throw new HttpProblemException(
				[
					'title' => 'You want to delete resource which is not automation.',
				],
				\WP_Http::FORBIDDEN
			);
		}
		try {
			$success = $manager->delete( $automation );
		} catch ( PersisterException $e ) {
			throw new HttpProblemException(
				[
					'title'  => esc_html__( 'You are forbidden to delete this automation.', 'shopmagic-for-woocommerce' ),
					'detail' => $e->getMessage(),
				],
				\WP_Http::FORBIDDEN,
				$e
			);
		}

		if ( $success === false ) {
			return new \WP_REST_Response(
				esc_html__( 'Could not delete automation', 'shopmagic-for-woocommerce' ),
				\WP_Http::UNPROCESSABLE_ENTITY
			);
		}

		return new \WP_REST_Response( null, \WP_Http::NO_CONTENT );
	}

	/**
	 * @param \WP_REST_Request        $request
	 * @param \wpdb                   $wpdb
	 * @param AutomationObjectManager $manager
	 *
	 * @return \WP_REST_Response
	 */
	public function create(
		\WP_REST_Request $request,
		\wpdb $wpdb,
		AutomationObjectManager $manager,
		RestUrlGenerator $url_generator
	): \WP_REST_Response {
		$this->logger->debug( 'Starting transaction to create new automation.' );
		try {
			$wpdb->query( 'START TRANSACTION' );
			$automation = $this->denormalizer->denormalize(
				array_merge(
					$request->get_json_params(),
					[ 'id' => null ] // ensure, we don't have dirty ID in request
				)
			);
			$manager->save( $automation );
			$wpdb->query( 'COMMIT' );
			$this->logger->debug( sprintf( 'Automation successfully saved with ID %d', $automation->get_id() ) );
		} catch ( AutomationNotSaved | InvalidArgumentException $e ) {
			$wpdb->query( 'ROLLBACK' );
			$this->logger->error( sprintf( 'Automation could not be created. Reason: %s', $e->getMessage() ) );

			throw new HttpProblemException(
				[
					'title'  => __( 'There are some issues with automation configuration', 'shopmagic-for-woocommerce' ),
					'detail' => $e->getMessage(),
				],
				\WP_Http::UNPROCESSABLE_ENTITY,
				$e
			);
		} catch ( PersisterException $e ) {
			$wpdb->query( 'ROLLBACK' );
			$this->logger->error( sprintf( 'Automation could not be created. Reason: %s', $e->getMessage() ) );

			throw new HttpProblemException(
				[
					'title'  => __( 'You are not allowed to save this automation.', 'shopmagic-for-woocommerce' ),
					'detail' => $e->getMessage(),
				],
				\WP_Http::FORBIDDEN,
				$e
			);
		} catch ( \Throwable $e ) {
			$wpdb->query( 'ROLLBACK' );
			$this->logger->error( sprintf( 'Automation could not be created. Reason: %s', $e->getMessage() ) );

			throw new HttpProblemException(
				[
					'title'  => __( 'Critical error during saving automation.', 'shopmagic-for-woocommerce' ),
					'detail' => $e->getMessage(),
				],
				\WP_Http::INTERNAL_SERVER_ERROR,
				$e
			);
		}

		$response = new \WP_REST_Response( $automation->get_id(), 201 );
		$response->header(
			'Location',
			$url_generator->generate( '/automations/' . $automation->get_id() )
		);

		return $response;
	}

	/**
	 * @param int                     $id
	 * @param \WP_REST_Request        $request
	 * @param \wpdb                   $wpdb
	 * @param AutomationObjectManager $manager
	 *
	 * @return \WP_REST_Response
	 */
	public function update(
		int $id,
		\WP_REST_Request $request,
		\wpdb $wpdb,
		AutomationObjectManager $manager
	): \WP_REST_Response {
		$this->logger->debug( sprintf( 'Starting transaction to update automation %d.', $id ) );
		try {
			$wpdb->query( 'START TRANSACTION' );
			$automation = $this->denormalizer->denormalize(
				array_merge(
					$request->get_json_params(),
					[ 'id' => $id ]
				)
			);
			$manager->save( $automation );
			$wpdb->query( 'COMMIT' );
			$this->logger->debug( sprintf( 'Automation successfully saved with ID %d', $id ) );

			return new \WP_REST_Response( $automation->get_id() );
		} catch ( AutomationNotSaved | InvalidArgumentException $e ) {
			$wpdb->query( 'ROLLBACK' );
			$this->logger->error( sprintf( 'Automation could not be created. Reason: %s', $e->getMessage() ) );

			throw new HttpProblemException(
				[
					'title'  => 'Error occurred while saving automation',
					'detail' => $e->getMessage(),
				],
				\WP_Http::UNPROCESSABLE_ENTITY
			);
		}
	}

	public function count( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response( $this->repository->count( ...$this->parse_params( $request ) ) );
	}
}
