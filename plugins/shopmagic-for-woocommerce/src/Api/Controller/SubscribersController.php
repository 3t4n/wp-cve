<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use ShopMagicVendor\League\Csv\CannotInsertRecord;
use ShopMagicVendor\League\Csv\Reader;
use ShopMagicVendor\League\Csv\Writer;
use WPDesk\ShopMagic\Api\Normalizer\SubscriberHydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\RequestToCriteria;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\Guest\GuestFactory;
use WPDesk\ShopMagic\Customer\Guest\GuestManager;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Marketing\Subscribers\CustomerSubscriberService;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriber;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriptionManager;

class SubscribersController {

	/** @var SubscriberObjectRepository */
	private $repository;

	public function __construct(
		SubscriberObjectRepository $repository
	) {
		$this->repository = $repository;
	}

	public function index( \WP_REST_Request $request, SubscriberHydrator $normalizer ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->repository->find_by( ...$this->parse_params( $request ) )
			                 ->map( \Closure::fromCallable( [ $normalizer, 'normalize' ] ) )
			                 ->to_array()
		);
	}

	private function parse_params( \WP_REST_Request $request ): array {
		$criteria = ( new RequestToCriteria() )
			->set_order_keys( [ 'created', 'updated' ] )
			->set_where_whitelist( [
				'type'   => [ 0, 1, "0", "1" ],
				'active' => [ 0, 1, "0", "1" ],
			] );

		[ $where, $order, $offset, $limit ] = $criteria->parse_request( $request );

		$filters = $request->get_param( 'filters' );

		if ( isset( $filters['list'] ) && is_numeric( $filters['list'] ) ) {
			$where['list_id'] = $filters['list'];
		}

		if ( isset( $filters['email'] ) ) {
			$where['email'] = [
				'field'     => 'email',
				'value'     => '%' . sanitize_text_field( $filters['email'] ) . '%',
				'condition' => 'LIKE',
			];
		}

		return [ $where, $order, $offset, $limit ];
	}

	public function get( int $id, \WP_REST_Request $request ): \WP_REST_Response {
		if ( str_contains( $request->get_header( 'Accept' ), 'text/csv' ) ) {
			return $this->export( $id );
		}

		return new \WP_REST_Response( [
			'title' => esc_html__( 'Currently, audience can be exported only as CSV.' ),
			'code'  => \WP_Http::NOT_IMPLEMENTED,
		], \WP_Http::NOT_IMPLEMENTED );
	}

	private function export( int $id ): \WP_REST_Response {
		$file_path   = $this->get_file_path();
		$writer      = Writer::createFromPath( $file_path, 'a+' );
		$subscribers = $this->repository->find_by( [ 'list_id' => $id ] )
		                                ->map( static function ( SingleListSubscriber $subscriber ): string {
			                                return $subscriber->get_email();
		                                } );

		foreach ( $subscribers as $subscriber ) {
			try {
				$writer->insertOne( [ $subscriber ] );
			} catch ( CannotInsertRecord $e ) {
			}
		}

		return new \WP_REST_Response( $writer->getContent(), \WP_Http::OK, [
			'Content-Type'        => 'text/csv',
			'Content-Disposition' => sprintf( 'attachment; filename="%s"', basename( $file_path ) ),
			'Content-Description' => 'File Transfer',
			'Content-Encoding'    => 'None',
		] );
	}

	private function get_file_path(): string {
		[ 'path' => $path ] = wp_upload_dir();

		return trailingslashit( $path ) . 'shopmagic-subscribers-' . gmdate( 'YmdHms' ) . '.csv';
	}

	public function count( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->repository->get_count( ...$this->parse_params( $request ) )
		);
	}

	public function import(
		int $id, // Target list ID
		\WP_REST_Request $request,
		CustomerSubscriberService $subscriber_service,
		CustomerRepository $customer_repository,
		GuestManager $guest_manager,
		GuestFactory $guest_factory
	): \WP_REST_Response {
		$type = $request->get_header( 'Content-Type' );
		if ( str_contains( $type, 'multipart/form-data' ) ) {
			return $this->import_from_file(
				$id,
				$request,
				$subscriber_service,
				$customer_repository,
				$guest_manager,
				$guest_factory
			);
		}

		return new \WP_REST_Response( [
			'title' => esc_html__( 'Currently, audience can be imported only from CSV.' ),
			'code'  => \WP_Http::NOT_IMPLEMENTED,
		], \WP_Http::NOT_IMPLEMENTED );
	}

	private function import_from_file(
		int $id,
		\WP_REST_Request $request,
		CustomerSubscriberService $subscriber_service,
		CustomerRepository $customer_repository,
		GuestManager $guest_manager,
		GuestFactory $guest_factory
	): \WP_REST_Response {
		$file = $request->get_file_params();
		if ( ! isset( $file['file'] ) ) {
			throw new HttpProblemException( [
				'title' => 'No file provided.',
			] );
		}

		$csv = Reader::createFromPath( $file['file']['tmp_name'] );

		$imported = 0;
		$errors   = 0;

		/* @note Using iterator_to_array as iterating over $csv fails after first record. */
		foreach ( iterator_to_array( $csv ) as [$email] ) {
			if ( ! is_email( $email ) ) {
				continue;
			}

			try {
				$customer_repository->find_by_email( $email );
			} catch ( CustomerNotFound $e ) {
				$guest = $guest_factory->from_email( $email );
				$guest_manager->save( $guest );
			}

			$success = $subscriber_service->subscribe( $email, $id );

			if ( $success ) {
				$imported += 1;
			} else {
				$errors += 1;
			}
		}

		return new \WP_REST_Response( [
			'imported' => $imported,
			'errors'   => $errors,
		] );
	}

	public function delete( int $id, SubscriptionManager $manager ): \WP_REST_Response {
		try {
			$subscription = $manager->find( $id );
		} catch ( CannotProvideItemException $e ) {
			throw new HttpProblemException( [
				'title'  => __( 'Could not find subscriber to delete.', 'shopmagic-for-woocommerce' ),
				'detail' => $e->getMessage(),
			], \WP_Http::NOT_FOUND );
		}
		$manager->delete( $subscription );

		return new \WP_REST_Response( null, \WP_Http::NO_CONTENT );
	}
}
