<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;


use WPDesk\ShopMagic\Workflow\FieldValuesBag;

/**
 * Event NullObject passed when no compatible Event is found.
 */
class NullEvent extends Event {

	/** @var string|null */
	private $missing_id;

	public function __construct( string $missing_id = null ) {
		$this->missing_id  = $missing_id;
		$this->fields_data = new FieldValuesBag();
	}

	/**
	 * @return mixed[]
	 */
	public function get_provided_data_domains(): array {
		return [];
	}

	public function get_id(): string {
		if ( $this->missing_id === null ) {
			return 'non_existing_event';
		}

		return $this->missing_id;
	}

	public function get_name(): string {
		return __( 'Event does not exists', 'shopmagic-for-woocommerce' );
	}

	public function get_group_slug(): string {
		return '';
	}

	public function get_description(): string {
		return '';
	}

	public function initialize(): void {
	}

	public function supports_deferred_check(): bool {
		return false;
	}

	/**
	 * @return mixed[]
	 */
	public function jsonSerialize(): array {
		return [];
	}

	public function set_from_json( array $serialized_json ): void {
	}
}
