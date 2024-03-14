<?php
namespace BetterLinks;

/**
 * Summery - Perform Import , Export
 */
class Tools {

	/**
	 * Initialize import and export
	 */
	public function __construct() {
		$this->init_import();
		$this->init_export();
	}

	/**
	 * Export
	 */
	public function init_export() {
		new Tools\Export();
	}
	/**
	 * Import
	 */
	public function init_import() {
		new Tools\Import();
	}
}
