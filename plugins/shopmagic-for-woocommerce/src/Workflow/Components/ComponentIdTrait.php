<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

/**
 * Introduced temporarily until NamedComponent class won't expose `get_id` method.
 * Serves as compatibility layer to set id for any elements which are registered with array keys
 * by now.
 *
 * Use hacky way of accessing private property for backward compatibility when action is
 * used as decorator and wrapped action is hold in `$this::$action` property.
 * @see shopmagic-delayed-actions
 */
trait ComponentIdTrait {

	/** @var string */
	private $id;

	public function get_id(): string {
		if ( property_exists( $this, 'action' ) ) {
			return \Closure::bind(function () {
				return $this->action->get_id();
			}, $this, $this)();
		}

		return $this->id;
	}

	public function set_id( $id ): void {
		if ( property_exists( $this, 'action' ) ) {
			\Closure::bind(function ($id) {
				return $this->action->set_id($id);
			}, $this, $this)((string) $id);
			return;
		}

		$this->id = (string) $id;
	}

}
