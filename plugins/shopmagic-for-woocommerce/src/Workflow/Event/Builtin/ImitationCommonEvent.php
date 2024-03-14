<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin;

use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\Event;

/**
 * Base class for events that shows info about PRO upgrades.
 */
abstract class ImitationCommonEvent extends Event {

	public function get_fields(): array {
		return [ new ProItemInfoField() ];
	}

	public function initialize(): void {
	}

	public function get_group_slug(): string {
		return Groups::PRO;
	}

	public function jsonSerialize(): array {
		return [];
	}

	public function set_from_json( array $serialized_json ): void {
	}

	public function get_provided_data_domains(): array {
		return [];
	}

	public function update_fields_data( $data ): void {
	}
}
