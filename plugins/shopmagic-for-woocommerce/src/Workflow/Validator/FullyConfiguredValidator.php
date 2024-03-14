<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Validator;

use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Event\NullEvent;

/**
 * Validates, if automation has real event and at least one action.
 */
final class FullyConfiguredValidator extends WorkflowValidator {

	/** @var Automation|null */
	private $automation;

	public function __construct( Automation $automation = null ) {
		if ( $automation !== null ) {
			@trigger_error(
				sprintf(
					'Passing automation to %s constructor is deprecated since 3.0.13. Pass it to valid() method instead, wrapped in %s.',
					self::class,
					DataLayer::class
				),
				E_USER_DEPRECATED
			);
		}

		$this->automation = $automation;
	}

	/**
	 * @param $resources DataLayer|null
	 */
	public function valid( DataLayer $resources = null ): bool {
		if ( $resources === null ) {
			@trigger_error(
				sprintf(
					'Calling %s without arguments is deprecated since 3.0.13 and will be removed in 4.0.0. Pass %s as argument instead.',
					__CLASS__ . '::' . __METHOD__,
					DataLayer::class
				),
				E_USER_DEPRECATED
			);
			// We assume that DataLayer is already passed to this validator.
			$resources = $this->resources;
		}

		// Use deprecated constructor argument if no DataLayer is passed.
		if ( $resources === null ) {
			$automation = $this->automation;
		} else {
			if ( ! $resources->has( Automation::class ) ) {
				return false;
			}
			$automation = $resources->get( Automation::class );
		}

		if ( $automation === null ) {
			return false;
		}

		if ( $automation->get_event() instanceof NullEvent ) {
			return false;
		}

		if ( \count( $automation->get_actions() ) <= 0 ) {
			return false;
		}

		return parent::valid( $resources );
	}
}
