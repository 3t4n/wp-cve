<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow;

use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * Enables automation to run.
 */
interface Runner {

	public function initialize(): void;

	/**
	 * Runner needs to be triggered and start the process of setup, validation and execution of automation.
	 */
	public function run( DataLayer $data_layer ): void;

	public function get_automation(): Automation;

}
