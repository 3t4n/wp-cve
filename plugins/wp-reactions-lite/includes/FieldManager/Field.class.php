<?php

namespace WP_Reactions\Lite\FieldManager;

abstract class Field {
	protected $name;
	protected $id;
	protected $label;
	protected $value;
	protected $classes;
	protected $disabled;

	public function addClasses( $classes ) {
		$this->classes = $classes;
		return $this;
	}

	public function setId( $id ) {
		$this->id = $id;
		return $this;
	}

	public function setLabel( $label ) {
		$this->label = $label;
		return $this;
	}

	public function setName( $name ) {
		$this->name = $name;
		return $this;
	}

	public function setValue( $value ) {
		$this->value = $value;
		return $this;
	}

	public function setDisabled( $disabled ) {
		$this->disabled = $disabled;
		return $this;
	}

	public function getClasses() {
		return $this->classes;
	}

	public function getId() {
		return $this->id;
	}

	public function getLabel() {
		return $this->label;
	}

	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}

	public function getDisabled() {
		return $this->disabled;
	}

	public abstract function build();
}