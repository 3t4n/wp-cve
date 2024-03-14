<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * Main reason of action existence is a possibility of performing some action. This is a definition of that capability.
 */
interface ActionLogic {
	/**
	 * Execute the action job.
	 *
	 * @return bool Action MUST return true if execution was successful.
	 *              Action MAY return false for unsuccessful execution,
	 *              but only for expected cases, when no further explanation is required.
	 * @throws ActionExecutionFailure Action could not be executed because of client error.
	 */
	public function execute( DataLayer $resources ): bool;
}
