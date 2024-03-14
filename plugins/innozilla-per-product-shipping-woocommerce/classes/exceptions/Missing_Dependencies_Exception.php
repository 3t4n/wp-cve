<?php

class IPPSW_Missing_Dependencies_Exception extends IPPSW_Exception {

	/** @var string[] */
	private $missing_plugin_names;

	/**
	 * @param string[] $missing_plugin_names Names of the plugins that our plugin depends on,
	 *                                       that were found to be inactive.
	 */
	public function __construct( $missing_plugin_names ) {
		$this->missing_plugin_names = $missing_plugin_names;
	}

	/**
	 * @return string[]
	 */
	public function get_missing_plugin_names() {
		return $this->missing_plugin_names;
	}

}