<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin\Form\Fields;

use WPDesk\ShopMagic\FormField\BasicField;

/**
 * Notice field for items available in pro version.
 */
final class ProItemInfoField extends BasicField {

	public function get_type(): string {
		return 'advertisement';
	}

	public function get_template_name(): string {
		return 'pro-event-info';
	}

	public function should_override_form_template(): bool {
		return true;
	}
}
