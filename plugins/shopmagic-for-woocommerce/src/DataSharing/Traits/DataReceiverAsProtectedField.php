<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing\Traits;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

trait DataReceiverAsProtectedField {

	/**
	 * @var DataLayer|null
	 * @deprecated 3.0 Renamed to $resources
	 */
	protected $provided_data;

	/** @var DataLayer|null */
	protected $resources;

	public function set_provided_data( DataLayer $resources ): void {
		$this->provided_data = $resources;
		$this->resources     = $resources;
	}
}
