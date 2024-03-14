<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Validator;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * Base class in chain of request for validating current automation.
 * FIXME: Current implementation implies that all validators are
 * stateful, getting data from their constructors, while it would be
 * the best to pass DataLayer as single source of data to all validators
 * in `valid` method.
 */
class WorkflowValidator {
	// This trait is inlined to reflect the deprecation.
	// Include statement is left there for reference, but this will be removed in 4.0.0.
	// use DataReceiverAsProtectedField;

	/**
	 * @var DataLayer|null
	 * @deprecated 3.0 Renamed to $resources
	 */
	protected $provided_data;

	/** @var DataLayer|null */
	protected $resources;

	/**
	 * @deprecated 3.0.13 DataLayer should not be set as optional dependency.
	 */
	public function set_provided_data( DataLayer $resources ): void {
		$this->provided_data = $resources;
		$this->resources     = $resources;
	}

	/** @var WorkflowValidator|null */
	private $next;

	/**
	 * @param WorkflowValidator $validator
	 *
	 * @return WorkflowValidator
	 * @deprecated 3.0.13 Validators should be pushed onto stack instead of replaced.
	 */
	final public function add_validator( WorkflowValidator $validator ): WorkflowValidator {
		@trigger_error( 'add_validator() is deprecated since 3.0.13 and will be removed in 4.0.0. Use push() instead.', E_USER_DEPRECATED );

		$this->next = $validator;

		return $validator;
	}

	final public function push( WorkflowValidator $validator ): WorkflowValidator {
		if ( $this->next === null ) {
			$this->next = $validator;

			return $this->next;
		}

		return $this->next->push( $validator );
	}

	/**
	 * TODO: This signature should change to `valid( DataLayer $resources ): bool`
	 *
	 * @param $resources DataLayer This will be introduced in 4.0.0.
	 */
	public function valid(/* DataLayer $resources */ ): bool {
		if ( empty( func_get_args() ) ) {
			@trigger_error(
				sprintf(
					'Calling %s without arguments is deprecated since 3.0.13 and will be removed in 4.0.0. Pass %s as argument instead.',
					__CLASS__ . '::' . __METHOD__,
					DataLayer::class
				),
				E_USER_DEPRECATED
			);
			// We assume that DataLayer is already passed to this validator.
			$data_layer = $this->resources;
		} else {
			$data_layer = func_get_arg( 0 );
			// For the time-being we also treat parameter as nullable.
			if ( $data_layer === null ) {
				$data_layer = $this->resources;
			}
			// Null values cannot throw and exception for BC reasons.
			if ( $data_layer !== null && ! $data_layer instanceof DataLayer ) {
				throw new \InvalidArgumentException(
					sprintf(
						'Argument 1 passed to %s must be an instance of %s, %s given.',
						__CLASS__ . '::' . __METHOD__,
						DataLayer::class,
						get_debug_type( $data_layer )
					)
				);
			}
		}

		if ( $this->next === null ) {
			return true;
		}

		if ( $data_layer !== null ) {
			$this->next->set_provided_data( $data_layer );
		}

		return $this->next->valid( $data_layer );
	}
}
