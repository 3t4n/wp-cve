<?php

namespace Entity;

class Form {

	private string $title;
	private string $slug;
	public string $description;
	private mixed $fields;
	private Field|null $content_field;

	/**
	 * @return Field|null
	 */
	public function get_content_field(): ?Field {
		return $this->content_field ?? null;
	}



	/**
	 * @param Field $content_field
	 */
	public function set_content_field( Field $content_field ): void {
		$this->content_field = $content_field;
	}

	/**
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function set_title( string $title ): void {
		$this->title = $title;
	}

	/**
	 * @param Field $field
	 */
	public function add_field(Field $field): void {
		$this->fields[] = $field;
	}

	/**
	 * @return mixed
	 */
	public function get_fields(): mixed {
		return $this->fields;
	}
	/**
	 * @return string
	 */
	public function get_slug(): string {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function set_slug( string $slug ): void {
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function set_description( string $description ): void {
		$this->description = $description;
	}

}