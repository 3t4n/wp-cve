<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin\Form\Fields;

use ShopMagicVendor\WPDesk\Forms\Field\BasicField;

/**
 * Frontend time imput.
 */
final class InputTimeField extends BasicField {

	public function get_template_name(): string {
		return 'input-time';
	}

}
