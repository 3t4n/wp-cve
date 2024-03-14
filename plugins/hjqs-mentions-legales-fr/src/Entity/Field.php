<?php

namespace Entity;

class Field {
	private string $label;
	private string $type;
	private ?string $helper;
	private string $option_key;
	private string $option_key_copy;
	private string|array|null $default_value;
	private ?array $choices;
	private string|array $value;
	private string $form_slug;
	private bool $is_content_field;


	/**
	 * @param $args
	 */

	public function __construct($args) {
		$this->label = $args['label'] ?? '';
		$this->type = $args['type'] ?? 'text';
		$this->helper = $args['helper'] ?? null;
		$this->option_key_copy = $args['option_key'] ?? '';
		$this->default_value = $args['default_value'] ?? null;
		$this->form_slug = $args['form_slug'] ?? '';
		$this->value = $args['value'] ?? '';
		$this->choices = $args['choices'] ?? [];
		$this->is_content_field = $args['is_content_field'] ?? false;

		$this->option_key = $this->form_slug . "[" .$args['option_key'] ."]";
	}


	/**
	 * @return string
	 */
	public function get_label(): string {
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function set_label( string $label ): void {
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function set_type( string $type ): void {
		$this->type = $type;
	}

	/**
	 * @return string|null
	 */
	public function get_helper(): ?string {
		return $this->helper;
	}

	/**
	 * @param string|null $helper
	 */
	public function set_helper( ?string $helper ): void {
		$this->helper = $helper;
	}

	/**
	 * @return string
	 */
	public function get_option_key(): string {
		return $this->option_key;
	}

	/**
	 * @param string $option_key
	 */
	public function set_option_key( string $option_key ): void {
		$this->option_key = $this->form_slug . "[".$option_key."]";
	}

	/**
	 * @return string|null
	 */
	public function get_default_value(): string|null {
		return $this->default_value;
	}

	/**
	 * @param string|array|null $default_value
	 */
	public function set_default_value( string|array|null $default_value ): void {
		$this->default_value = $default_value;
	}

	/**
	 * @return array|null
	 */
	public function get_choices(): ?array {
		return $this->choices;
	}

	/**
	 * @param array|null $choices
	 */
	public function set_choices( ?array $choices ): void {
		$this->choices = $choices;
	}

	/**
	 * @return string|array
	 */
	public function get_value(): string|array {
		$options = get_option($this->form_slug);
		return $options[ $this->option_key_copy ] ?? $this->value;
	}


	/**
	 * @return string
	 */
	public function get_form_slug(): string {
		return $this->form_slug;
	}

	/**
	 * @param string $form_slug
	 */
	public function set_form_slug( string $form_slug ): void {
		$this->form_slug = $form_slug;
	}


	/**
	 * @return string
	 */
	public function get_option_key_copy(): string {
		return $this->option_key_copy;
	}

	/**
	 * @return bool
	 */
	public function is_content_field(): bool {
		return $this->is_content_field;
	}

	/**
	 * @param bool $is_content_field
	 */
	public function set_is_content_field( bool $is_content_field ): void {
		$this->is_content_field = $is_content_field;
	}


}