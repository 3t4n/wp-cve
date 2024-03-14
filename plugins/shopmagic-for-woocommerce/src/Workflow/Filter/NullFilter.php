<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter;

use WPDesk\ShopMagic\Workflow\FieldValuesBag;

/**
 * Filter that lets everything go.
 */
class NullFilter extends Filter {

	/** @var string|null */
	private $missing_id;

	public function __construct(string $missing_id = null) {
		$this->missing_id = $missing_id;
		$this->fields_data = new FieldValuesBag();
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		return [];
	}

	public function get_id(): string {
		if ( $this->missing_id === null) {
			return 'non_existing_filter';
		}


		return $this->missing_id;
	}

	public function passed(): bool {
		return true;
	}

	public function get_group_slug(): string {
		return '';
	}

	public function get_name(): string {
		return __( 'Filter does not exists', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return mixed[]
	 */
	public function get_required_data_domains(): array {
		return [];
	}
}
