<?php

/**
 * 
 * 
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('IfSo_PageVisitTimedItem')) {

	require_once('ifso-timed-item.php');

	class IfSo_PageVisitTimedItem extends IfSo_TimedItem {
		protected $page;

		public function __construct( $saved_at, $saved_until, $page ) {
			parent::__construct( $saved_at, $saved_until );
			$this->page = $page;
		}

		public function get_page() {
			return $this->page;
		}

		public function is_equal( $page ) {
			return ( $this->get_page() == $page );
		}

	}
}