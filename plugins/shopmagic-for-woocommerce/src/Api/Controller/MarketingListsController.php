<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Admin\CommunicationList\CommunicationListSettingsMetabox;
use WPDesk\ShopMagic\Admin\CommunicationList\FormShortcodeMetabox;
use WPDesk\ShopMagic\Admin\Form\FieldsCollection;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\JsonSchemaNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\MarketingList\MarketingListNormalizer;
use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Components\Database\Abstraction\PersisterException;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\Components\UrlGenerator\RestUrlGenerator;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListObjectManager;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;

class MarketingListsController {

	/** @var AudienceListRepository */
	private $repository;

	/** @var LoggerInterface */
	private $logger;

	public function __construct( AudienceListRepository $repository, LoggerInterface $logger ) {
		$this->repository = $repository;
		$this->logger     = $logger;
	}

	public function index( MarketingListNormalizer $normalizer, \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->repository->find_by( ...$this->parse_params( $request ) )
			                 ->map( \Closure::fromCallable( [ $normalizer, 'normalize' ] ) )
			                 ->to_array()
		);
	}

	public function count( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response( $this->repository->count( ...$this->parse_params( $request ) ) );
	}

	public function show( int $id, MarketingListNormalizer $normalizer ): \WP_REST_Response {
		return new \WP_REST_Response( $normalizer->normalize( $this->repository->find( $id ) ) );
	}

	public function delete(
		int $id,
		AudienceListObjectManager $manager
	): \WP_REST_Response {
		try {
			$list   = $this->repository->find( $id );
			$result = $manager->delete( $list );
		} catch ( EntityNotFound $e ) {
			throw new HttpProblemException( [
				'title'  => esc_html__( 'Newsletter list not found.', 'shopmagic-for-woocommerce' ),
				'detail' => $e->getMessage(),
			], \WP_Http::NOT_FOUND );
		} catch ( PersisterException $e ) {
			throw new HttpProblemException( [
				'title'  => esc_html__( 'Could not delete newsletter list.', 'shopmagic-for-woocommerce' ),
				'detail' => $e->getMessage(),
			], \WP_Http::UNPROCESSABLE_ENTITY );
		}

		if ( $result === false ) {
			throw new HttpProblemException( [
				'title' => esc_html__( 'Could not delete communication list', 'shopmagic-for-woocommerce' ),
			], \WP_Http::UNPROCESSABLE_ENTITY );
		}

		return new \WP_REST_Response( null, \WP_Http::NO_CONTENT );
	}

	public function create(
		\WP_REST_Request $request,
		\wpdb $wpdb,
		AudienceListObjectManager $manager,
		MarketingListNormalizer $normalizer,
		RestUrlGenerator $url_generator
	): \WP_REST_Response {
		$this->logger->debug( 'Starting transaction to create new marketing list.' );
		try {
			$wpdb->query( 'START TRANSACTION' );
			$list = $normalizer->denormalize( $request->get_json_params() );
			$manager->save( $list );
			$wpdb->query( 'COMMIT' );
			$this->logger->debug( sprintf( 'List successfully saved with ID %d', $list->get_id() ) );
		} catch ( \RuntimeException $e ) {
			$wpdb->query( 'ROLLBACK' );
			$this->logger->error( sprintf( 'List could not be created. Reason: %s', $e->getMessage() ) );

			return new \WP_REST_Response( [
				"title"  => esc_html__( "List could not be created" ),
				"code"   => \WP_Http::UNPROCESSABLE_ENTITY,
				"detail" => $e->getMessage(),
			], \WP_Http::UNPROCESSABLE_ENTITY );
		}

		$response = new \WP_REST_Response( $list->get_id(), \WP_Http::CREATED );
		$response->header(
			'Location',
			$url_generator->generate( '/marketing-lists/' . $list->get_id() )
		);

		return $response;
	}

	public function update(
		int $id,
		\WP_REST_Request $request,
		\wpdb $wpdb,
		AudienceListObjectManager $manager,
		MarketingListNormalizer $normalizer
	): \WP_REST_Response {
		$this->logger->debug( sprintf( 'Starting transaction to update marketing list %d.', $id ) );
		try {
			$wpdb->query( 'START TRANSACTION' );
			$list = $normalizer->denormalize( $request->get_json_params() );
			$manager->save( $list );
			$wpdb->query( 'COMMIT' );
			$this->logger->debug( sprintf( 'Marketing list successfully saved with ID %d', $id ) );

			return new \WP_REST_Response( $list->get_id() );
		} catch ( \RuntimeException $e ) {
			$wpdb->query( 'ROLLBACK' );
			$this->logger->error( sprintf( 'Marketing list could not be created. Reason: %s', $e->getMessage() ) );

			return new \WP_REST_Response( [
				"title"  => esc_html__( "List could not be created", 'shopmagic-for-woocommerce' ),
				"code"   => \WP_Http::UNPROCESSABLE_ENTITY,
				"detail" => $e->getMessage(),
			], \WP_Http::UNPROCESSABLE_ENTITY );
		}
	}

	public function subscribers_count( int $id, SubscriberObjectRepository $subscribers_repository ): \WP_REST_Response {
		$count = $subscribers_repository->get_count( [ 'list_id' => $id ] );

		return new \WP_REST_Response( $count );
	}

	public function fields(
		CommunicationListSettingsMetabox $metabox,
		JsonSchemaNormalizer $normalizer
	): \WP_REST_Response {
		return new \WP_REST_Response( $normalizer->normalize( $metabox->get_fields() ) );
	}

	public function shortcode_fields(
		FormShortcodeMetabox $metabox,
		JsonSchemaNormalizer $normalizer
	): \WP_REST_Response {
		return new \WP_REST_Response( $normalizer->normalize( new FieldsCollection( $metabox->get_fields() ) ) );
	}

	private function parse_params( \WP_REST_Request $request ): array {
		$page      = $request->get_param( 'page' );
		$page_size = $request->get_param( 'pageSize' );
		$filters   = $request->get_param( 'filters' );

		$criteria = [];
		if ( isset( $filters['status'] ) && in_array( $filters['status'], [
				'publish',
				'draft',
				'trash',
			], true ) ) {
			$criteria['post_status'] = $filters['status'];
		} else {
			$criteria['post_status'] = 'any';
		}

		if ( isset( $filters['type'] ) && in_array( $filters['type'], [
				'opt_in',
				'opt_out',
			], true ) ) {
			$criteria['meta_key']   = 'type';
			$criteria['meta_value'] = $filters['type'];
		}

		if ( isset( $filters['name'] ) ) {
			$criteria['s'] = sanitize_text_field( $filters['name'] );
		}

		return [ $criteria, [], ( ( $page - 1 ) * $page_size ), $page_size ];
	}

}
