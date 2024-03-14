<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectRepository<TrackedEmail>
 */
class TrackedEmailRepository extends ObjectRepository {

	protected function get_name(): string {
		return DatabaseTable::tracked_emails();
	}
}
