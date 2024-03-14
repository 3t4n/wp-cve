<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Wpdb_Trait' ) ) {
	return;
}

trait WC_Payever_Wpdb_Trait {

	/** @var wpdb */
	private $wpdb;

	/**
	 * @param wpdb $wpdb
	 * @return $this
	 * @internal
	 */
	public function set_wpdb( wpdb $wpdb ) {
		$this->wpdb = $wpdb;

		return $this;
	}

	/**
	 * @return wpdb
	 * @codeCoverageIgnore
	 */
	private function get_wpdb() {
		if ( null === $this->wpdb ) {
			global $wpdb;
			$this->wpdb = $wpdb;
		}

		return $this->wpdb;
	}
}
