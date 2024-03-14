<?php
/**
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('IfSo_PageVisitsTimedCollection')) {

	require_once('ifso-timed-collection.php');

	class IfSo_PageVisitsTimedCollection extends IfSo_TimedCollection {

		public function add_page( $page, $saved_until ) {
			if ( $this->is_page_exists( $page ) )
				return;

			$new_page_array = array(
				'saved_at' => time(),
				'saved_until' => $saved_until,
				'page' => $page
			);
			$new_page_model = 
				$this->itemConverterStrategy->convert_to_model($new_page_array);

			$models = $this->get_models();
			$models[] = $new_page_model;
			$this->persist( $models );
		}

		public function remove_page( $page ) {
			if ( ! $this->is_page_exists( $page ) )
				return;

			$models = $this->get_models();
			foreach ($models as $key => $model) {
				if ( $model->is_equal( $page ) ) {
					unset($models[$key]);
					break;
				}
			}

			$this->persist( $models );
		}

		public function is_page_exists( $page ) {
			$models = $this->get_models();

			foreach ( $models as $model ) {
				if ( $model->is_equal( $page ) )
					return true;
			}

			return false;
		}
	}
}