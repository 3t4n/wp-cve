<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Exception\SubscriptionNotFound;

/**
 * @extends ObjectRepository<SingleListSubscriber>
 */
class SubscriberObjectRepository extends ObjectRepository {

	public function is_subscribed_to_list( string $email, int $list_id ): bool {
		return $this->get_count(
				[
					'email'   => $email,
					'list_id' => $list_id,
					'active'  => '1',
				]
			) > 0;
	}

	/**
	 * @param string $email
	 * @param int    $list_id
	 *
	 * @return SingleListSubscriber
	 */
	public function get_subscribed_to_list(
		string $email,
		int $list_id
	): object {
		try {
			return $this->find_one_by(
				[
					'email'   => $email,
					'list_id' => $list_id,
				]
			);
		} catch ( CannotProvideItemException $e ) {
			throw new SubscriptionNotFound( sprintf( esc_html__( 'User %1$s is not subscribed to list %2$d',
				'shopmagic-for-woocommerce' ),
				$email,
				$list_id ) );
		}
	}

	protected function get_name(): string {
		return DatabaseTable::subscribers();
	}
}
