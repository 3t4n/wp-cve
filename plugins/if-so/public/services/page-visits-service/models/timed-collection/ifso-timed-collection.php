<?php
/**
 * 
 * Defines a custom collection of items, managed by:
 *		- persist strategy : defines how to save + load items
 *		- item converter : defines how those items represented in the system
 *
 * @author Matan Green
 */

if (!class_exists('IfSo_TimedCollection')) {
	class IfSo_TimedCollection {
		protected $cached_models;
		protected $persistStrategy;
		protected $itemConverterStrategy;
		protected $save_time;

		public function __construct( $persistStrategy,
									 $itemConverterStrategy,
									 $save_time ) {
			$this->persistStrategy = $persistStrategy;
			$this->itemConverterStrategy = $itemConverterStrategy;
			$this->save_time = $save_time;
		}

		/* API Methods */

		public function get_models() {
			if ( isset( $this->cached_models ) )
				return $this->cached_models;

			$raw_items = $this->persistStrategy->get_items();
			$models = $this->convert_raw_to_models( $raw_items );
			$this->cache_models( $models );
			$this->invalidate( $models );

			return $this->cached_models;
		}

		public function persist( $models ) {
			$raw_items = $this->convert_models_to_raw( $models );
			$this->persistStrategy->persist( $raw_items );
			$this->cache_models( $models );
		}

		/* Helper Methods */

		private function invalidate( $models ) {
			$new_models = array();
			$is_persisted = false;

			foreach ( $models as $model ) {
				if ( $model->invalidate( $this->save_time ) )
					$new_models[] = $model;
				else
					$is_persisted = true;
			}

			if ( $is_persisted )
				$this->persist( $new_models );

			return $is_persisted;
		}

		private function convert_models_to_raw( $models ) {
			$raw_items = array();

			foreach ( $models as $model ) {
				try {
					$raw_items[] = 
						$this->itemConverterStrategy->convert_to_array( $model );
				} catch (InvalidArgumentException $ex) {}
			}

			return $raw_items;
		}

		private function convert_raw_to_models( $raw_items ) {
			$models = array();

			foreach ( $raw_items as $item ) {
				try {
					$models[] = 
						$this->itemConverterStrategy->convert_to_model( $item );
				} catch (InvalidArgumentException $ex) {}
			}

			return $models;
		}

		private function cache_models( $models ) {
			$this->cached_models = $models;
		}
	}
}