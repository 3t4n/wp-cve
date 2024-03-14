<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Validator;

use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Filter\FilterLogic;

/**
 * Validates, if filters passing.
 */
final class FiltersValidator extends WorkflowValidator {

	/** @var FilterLogic|null */
	private $filters;

	public function __construct( FilterLogic $filters = null ) {
		if ( null !== $filters ) {
			@trigger_error(
				sprintf(
					'Passing filters to %s constructor is deprecated since 3.0.13. Pass it to valid() method instead, wrapped in %s.',
					self::class,
					DataLayer::class
				),
				E_USER_DEPRECATED
			);
		}
		$this->filters = $filters;
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

		if ( $resources === null ) {
			$filters = $this->filters;
		} elseif ( ! $resources->has( Automation::class ) ) {
			$filters = $this->filters;
		} else {
			$filters = $resources->get( Automation::class )->get_filters_group();
		}

		if ( $filters === null ) {
			// If we receive no filters at all, passthrough.
			return parent::valid( $resources );
		}

		$filters->set_provided_data( $resources );
		if ( ! $filters->passed() ) {
			return false;
		}

		return parent::valid( $resources );
	}

}
