<?php

class iHomefinderVariable {
	
	const PREFIX = "{";
	const SUFFIX = "}";
	
	private $name;
	private $value;
	private $description;
	
	public function __construct($name, $value, $description) {
		$this->setName($name);
		$this->setValue($value);
		$this->setDescription($description);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getNameWithAffix() {
		return self::PREFIX . $this->getName() . self::SUFFIX;
	}
	
	public function setName($name) {
		if(!is_string($name)) {
			return;
		}
		$this->name = trim($name);
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		if(!is_string($value)) {
			return;
		}
		$this->value = trim($value);
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		if(!is_string($description)) {
			return;
		}
		$this->description = trim($description);
	}
	
}