<?php

/**
 * 
 * 
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('IfSo_PageVisitItemConverterStrategy')) {

	require_once('ifso-item-converter-strategy.php');
	require_once(plugin_dir_path ( __DIR__ ) . 'timed-item/ifso-page-visit-timed-item.php');

	class IfSo_PageVisitItemConverterStrategy extends IfSo_ItemConverterStrategy {

		public function convert_to_model( $item ) {
			if ( ! $this->validate( $item ) )
				throw new InvalidArgumentException('IfSo_PageVisitItemConverterStrategy::convert_to_model item');

			$saved_at = $item['saved_at'];
			$saved_until = $item['saved_until'];
			$page = $item['page'];

			return new IfSo_PageVisitTimedItem( $saved_at, $saved_until, $page );
		}

		public function convert_to_array( $item ) {
			if ( ! is_a( $item, 'IfSo_PageVisitTimedItem' ) )
				throw new InvalidArgumentException('IfSo_PageVisitItemConverterStrategy::convert_to_array item');

			$saved_at = $item->get_saved_at();
			$saved_until = $item->get_saved_until();
			$page = $item->get_page();

			return array(
				'saved_at' => $saved_at,
				'saved_until' => $saved_until,
				'page' => $page
			);
		}

		private function validate( $item ) {
			$is_valid = true;

			if ( ! is_array( $item ) )
				$is_valid = false;
			else if ( ! $this->is_assoc_array( $item ) )
				$is_valid = false;
			else if ( ! array_key_exists( 'saved_at', $item ) )
				$is_valid = false;
			else if ( ! array_key_exists( 'saved_until', $item ) )
				$is_valid = false;
			else if ( ! array_key_exists( 'page', $item ) )
				$is_valid = false;

			return $is_valid;
		}

		private function is_assoc_array($arr) {
		    if (array() === $arr) return false;
		    return array_keys($arr) !== range(0, count($arr) - 1);
		}
	}
}