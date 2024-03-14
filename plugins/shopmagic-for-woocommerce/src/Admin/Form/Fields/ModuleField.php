<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Form\Fields;

use ShopMagicVendor\WPDesk\Forms\Field\CheckboxField;

class ModuleField extends CheckboxField {

	public function set_plugin_slug( string $plugin_slug ): self {
		$this->meta['plugin_slug'] = $plugin_slug;

		return $this;
	}

	public function get_template_name(): string {
		return '';
	}

	public function get_type(): string {
		return 'plugin-module';
	}
}
