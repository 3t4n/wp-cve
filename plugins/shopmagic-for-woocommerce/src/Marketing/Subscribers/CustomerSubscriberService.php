<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers;

use WPDesk\ShopMagic\Exception\SubscriptionNotFound;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriberHydrator;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriptionManager;

class CustomerSubscriberService {

	/** @var SubscriptionManager */
	private $manager;

	/** @var SingleListSubscriberHydrator */
	private $hydrator;

	/** @var ListSubscriber\SubscriberObjectRepository */
	private $repository;

	public function __construct(
		SubscriptionManager $manager,
		SingleListSubscriberHydrator $hydrator
	) {
		$this->manager = $manager;
		$this->repository = $this->manager->get_repository();
		$this->hydrator = $hydrator;
	}

	/**
	 * Although it's not required, this method expects that we already have customer in our system.
	 * Otherwise, subscription data will contain only customer email, without further possibility to enrich them.
	 */
	public function subscribe( string $email, int $target_list ): bool {
		if ( $this->repository->is_subscribed_to_list( $email, $target_list ) ) {
			return false;
		}

		try {
			$customer_status = $this->repository->get_subscribed_to_list( $email, $target_list );
		} catch ( SubscriptionNotFound $e ) {
			$customer_status = $this->hydrator->create_for_email_and_list( $email, $target_list );
		}

		$customer_status->set_active( true );

		return $this->manager->save( $customer_status );
	}

}
