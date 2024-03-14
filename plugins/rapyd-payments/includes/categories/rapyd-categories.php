<?php

class SingletonCategories {
	private static $instance = null;
	private $categories;
	
	private function __construct() {
	}
	
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new SingletonCategories();
		}
		return self::$instance;
	}
	
	public function getCategories() {
		return $this->categories;
	}
	
	public function setCategories( $categories ) {
		$this->categories = $categories;
	}
}
