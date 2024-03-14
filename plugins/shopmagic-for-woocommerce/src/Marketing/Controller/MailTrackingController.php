<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Controller;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\Ramsey\Uuid\Uuid;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmail;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailHydrator;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailObjectManager;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailRepository;

class MailTrackingController {

	/** @var TrackedEmailRepository */
	private $repository;

	/** @var TrackedEmailHydrator */
	private $factory;

	/** @var TrackedEmailObjectManager */
	private $manager;

	/** @var LoggerInterface */
	private $logger;

	public function __construct(
		TrackedEmailObjectManager $manager,
		TrackedEmailRepository $repository,
		TrackedEmailHydrator $factory,
		LoggerInterface $logger
	) {
		$this->manager    = $manager;
		$this->repository = $repository;
		$this->factory    = $factory;
		$this->logger     = $logger;
	}

	public function click( \WP_REST_Request $request ): \WP_HTTP_Response {
		$this->logger->debug( 'Received request to track email click.' );

		$original_uri = esc_url_raw( $request->get_param( 'l' ) );
		$message_id   = $request->get_param( 'c' );

		if ( $original_uri === null || $message_id === null ) {
			$this->logger->warning( 'Received invalid request to track email click.' );

			return new \WP_HTTP_Response(
				esc_html__( 'Invalid request', 'shopmagic-for-woocommerce' ),
				\WP_Http::NOT_ACCEPTABLE
			);
		}

		try {
			$uuid = Uuid::fromString( $message_id );
		} catch ( \Exception $e ) {
			$this->logger->warning( 'Message UUID reference {uuid} is invalid.', [ 'uuid' => $message_id ] );

			return new \WP_HTTP_Response(
				esc_html__( 'Invalid request', 'shopmagic-for-woocommerce' ),
				\WP_Http::NOT_ACCEPTABLE
			);
		}
		/** @var TrackedEmail $tracked_email */
		try {
			$tracked_email = $this->repository->find_one_by( [ 'message_id' => $uuid->toString() ] );
		} catch ( CannotProvideItemException $e ) {
			$this->logger->warning(
				'Missing message UUID {uuid} reference in database. Redirecting to original URL.',
				[ 'uuid' => $message_id ]
			);

			return new \WP_HTTP_Response(
				null,
				\WP_Http::FOUND,
				[
					'Location' => esc_url_raw( $original_uri ),
				]
			);
		}

		if ( ! $tracked_email->is_opened() ) {
			$tracked_email->mark_opened();
		}

		$tracked_email->append_click(
			$this->factory->new_click( $tracked_email, $original_uri )
		);

		$saved = $this->manager->save( $tracked_email );

		if ( $saved ) {
			$this->logger->debug( 'Saved click tracking data.' );
		} else {
			$this->logger->warning( 'Failed to save click tracking data.' );
		}

		return new \WP_HTTP_Response(
			null,
			\WP_Http::FOUND,
			[
				'Location' => esc_url_raw( $original_uri ),
			]
		);
	}

	public function open( \WP_REST_Request $request ): \WP_HTTP_Response {
		$this->logger->debug( 'Received request to track email open.' );
		$message_id = $request->get_param( 'c' );

		if ( $message_id === null ) {
			$this->logger->warning( 'Received invalid request to track email open.' );

			return new \WP_HTTP_Response(
				esc_html__( 'Invalid request', 'shopmagic-for-woocommerce' ),
				\WP_Http::NOT_ACCEPTABLE
			);
		}

		try {
			$uuid = Uuid::fromString( $message_id );
		} catch ( \Exception $e ) {
			$this->logger->warning( 'Message UUID reference {uuid} is invalid.', [ 'uuid' => $message_id ] );

			return new \WP_HTTP_Response(
				esc_html__( 'Invalid request', 'shopmagic-for-woocommerce' ),
				\WP_Http::NOT_ACCEPTABLE
			);
		}
		/** @var TrackedEmail $tracked_email */
		try {
			$tracked_email = $this->repository->find_one_by( [ 'message_id' => $uuid->toString() ] );
		} catch ( \Exception $e ) {
			$this->logger->warning(
				'Missing message UUID {uuid} reference in database.',
				[ 'uuid' => $message_id ]
			);

			return $this->pixel_ok_response();
		}

		if ( ! $tracked_email->is_opened() ) {
			$tracked_email->mark_opened();
		}

		$saved = $this->manager->save( $tracked_email );

		if ( $saved ) {
			$this->logger->debug( 'Saved open tracking data.' );
		} else {
			$this->logger->warning( 'Failed to save open tracking data.' );
		}

		return $this->pixel_ok_response();
	}

	public function pixel_ok_response(): \WP_HTTP_Response {
		return new \WP_HTTP_Response(
			sprintf(
				'%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c',
				71, 73, 70, 56, 57, 97, 1, 0, 1, 0, 128, 255, 0, 192, 192, 192, 0, 0, 0, 33, 249, 4, 1, 0, 0, 0, 0, 44, 0, 0, 0, 0, 1, 0, 1, 0, 0, 2, 2, 68, 1, 0, 59
			),
			\WP_Http::OK,
			[
				'Content-Type'              => 'image/gif',
				'Content-Length'            => 42,
				'Pragma'                    => 'public',
				'Expires'                   => '0',
				'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0, private',
				'Content-Disposition'       => 'attachment; filename="blank.gif"',
				'Content-Transfer-Encoding' => 'binary',
			]
		);
	}

}
