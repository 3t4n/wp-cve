<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Form\Fields;

class ActionField extends \ShopMagicVendor\WPDesk\Forms\Field\NoValueField {

	/** @var string */
	private $callback;

	public function __construct() {
		parent::__construct();
		$this->is_readonly();
	}

	public function get_template_name(): string {
		return 'button';
	}

	public function get_callback(): string {
		return $this->callback;
	}

	public function set_callback( string $callback_url ): self {
		$this->callback = $callback_url;

		return $this;
	}


}
