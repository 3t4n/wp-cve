<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Tracker\Provider;

/**
 * Provides info about events/filter/actions/placeholders from extensions.
 */
class ExtensionDataProvider implements \WPDesk_Tracker_Data_Provider {
	/**
	 * @inheritDoc
	 */
	public function get_data() {
		return [
			'shopmagic_extensions' => [
				'events'       => array_keys( apply_filters( 'shopmagic/core/events', [] ) ),
				'actions'      => array_keys( apply_filters( 'shopmagic/core/actions', [] ) ),
				'filters'      => array_keys( apply_filters( 'shopmagic/core/filters', [] ) ),
				'placeholders' => array_keys( apply_filters( 'shopmagic/core/placeholders', [] ) ),
			],
		];
	}
}

