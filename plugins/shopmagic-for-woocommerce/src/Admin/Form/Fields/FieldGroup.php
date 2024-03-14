<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin\Form\Fields;

use ShopMagicVendor\WPDesk\Forms\Field;
use ShopMagicVendor\WPDesk\Forms\Field\BasicField;

final class FieldGroup extends BasicField {

	/** @var \ShopMagicVendor\WPDesk\Forms\Field[] */
	private $fields;

	/**
	 * @param \ShopMagicVendor\WPDesk\Forms\Field[] $fields
	 */
	public function __construct( array $fields = [] ) {
		$this->fields = $fields;
	}

	/** @return Field[] */
	public function get_fields(): array {
		return $this->fields;
	}

	public function get_template_name(): string {
		return 'compound-field';
	}

	public function should_override_form_template(): bool {
		return true;
	}

}
