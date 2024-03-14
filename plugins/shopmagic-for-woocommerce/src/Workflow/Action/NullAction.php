<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Persistence\Adapter\ArrayContainer;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;

/**
 * NullObject pattern. When no action is found this class is used.
 */
class NullAction extends Action {
	/** @var string|null */
	private $missing_id;

	public function __construct( string $missing_id = null ) {
		$this->missing_id  = $missing_id;
		$this->fields_data = new FieldValuesBag();
	}

	public function get_id(): string {
		if ( $this->missing_id === null ) {
			return 'non_existing_action';
		}

		return $this->missing_id;
	}

	public function get_name(): string {
		return '';
	}

	public function get_fields_data(): ContainerInterface {
		return new ArrayContainer();
	}

	public function execute( DataLayer $resources ): bool {
		return false;
	}

	/**
	 * @return mixed[]
	 */
	public function get_required_data_domains(): array {
		return [];
	}

	public function set_provided_data( DataLayer $resources ): void {
	}

	public function update_fields_data( $data ): void {
	}
}
