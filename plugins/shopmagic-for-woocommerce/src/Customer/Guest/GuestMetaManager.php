<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectManager<GuestMeta>
 */
class GuestMetaManager extends ObjectManager {

	protected function get_columns(): array {
		return [
			'meta_id',
			'guest_id',
			'meta_key',
			'meta_value',
		];
	}

	protected function get_name(): string {
		return DatabaseTable::guest_meta();
	}

}
