<?php

/**
 * 
 * 
 *
 * @author Matan Green
 */

if (!class_exists('IfSo_TimedItem')) {
	abstract class IfSo_TimedItem {
		protected $saved_at;
		protected $saved_until;

		public function __construct( $saved_at, $saved_until ) {
			$this->saved_at = $saved_at;
			$this->saved_until = $saved_until;
		}

		public function invalidate( $save_time ) {
			$this->saved_until = $this->get_saved_at() + $save_time;
			return ( $this->saved_until >= time() );
		}

		public function get_saved_until() {
			return $this->saved_until;
		}

		public function get_saved_at() {
			return $this->saved_at;
		}

		abstract public function is_equal( $item );
	}
}