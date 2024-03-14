<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectRepository<GuestMeta>
 */
final class GuestMetaRepository extends ObjectRepository {

	protected function get_name(): string {
		return DatabaseTable::guest_meta();
	}

	protected function get_primary_key(): array {
		return [ 'meta_id' ];
	}
}
