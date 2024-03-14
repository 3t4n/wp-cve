<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use WPDesk\ShopMagic\Database\DatabaseTable;

class TrackedClickObjectManager extends \WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager {

	protected function get_columns(): array {
		return [
			'id',
			'message_id',
			'original_uri',
			'clicked_at'
		];
	}

	protected function get_name(): string {
		return DatabaseTable::tracked_emails_clicks();
	}

}
