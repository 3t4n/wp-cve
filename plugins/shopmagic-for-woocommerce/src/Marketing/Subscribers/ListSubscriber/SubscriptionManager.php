<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectManager<SingleListSubscriber>
 */
class SubscriptionManager extends ObjectManager {

	protected function get_columns(): array {
		return [
			'id',
			'list_id',
			'email',
			'active',
			'type',
			'created',
			'updated',
		];
	}

	public function save( object $item ): bool {
		$result =  parent::save( $item );

		if ( $item->is_active() ) {
			do_action('shopmagic/core/event/manual/optin', $item );
		} else {
			do_action('shopmagic/core/event/manual/optout', $item );
		}

		return $result;
	}

	protected function get_name(): string {
		return DatabaseTable::subscribers();
	}
}
