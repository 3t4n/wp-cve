<?php

namespace QuadLayers\WOOCCM\Model;

/**
 * Model Class
 */
class Model {

	protected $table = '';
	private $cache   = array();


	protected function get_args() {
		return array();
	}

	protected function get_next_id() {
		$items = $this->get_items();

		if ( count( $items ) ) {
			return max( array_keys( $items ) ) + 1;
		}

		return 0;
	}

	// Singular
	protected function add_item( $item_data ) {
		$id              = $this->get_next_id();
		$item_data['id'] = $id;

		$items = $this->get_items();

		$items[ $id ] = $item_data;

		return $this->save_items( $items );
	}

	protected function get_item( $id = null ) {
		$items = $this->get_items();

		if ( isset( $items[ $id ] ) ) {
			return $items[ $id ];
		}
	}

	protected function update_item( $item_data = null ) {
		if ( ! isset( $item_data['id'] ) ) {
			return false;
		}

		$items = $this->get_items();

		if ( ! isset( $items[ $item_data['id'] ] ) ) {
			return false;
		}

		$current_data = $items[ $item_data['id'] ];
		/**
		 * Get saved data to prevent missing fields [required, order, clear, disabled]
		 */
		// TODO: 6.1.3
		// $items[ $item_data['id'] ] = array_replace_recursive( (array) $current_data, $item_data );
		$items[ $item_data['id'] ] = array_merge( (array) $current_data, $item_data );

		return $this->save_items( $items );
	}

	protected function delete_item( $id = null ) {
		$items = $this->get_items();
		if ( $items ) {
			if ( count( $items ) > 0 ) {
				unset( $items[ $id ] );
				return $this->save( $items );
			}
		}
	}

	// Plural
	protected function get_items() {
		$items = $this->get();

		// make sure each item has all values
		if ( is_array( $items ) ) {
			if ( count( $items ) ) {
				foreach ( $items as $id => $item ) {
					// $items[ $id ] = array_replace_recursive( $this->get_args(), $item );
					$items[ $id ] = $item + $this->get_args();
				}
				return $items;
			}
		}

		return array();
	}

	protected function save_items( $items ) {

		if ( is_array( $items ) ) {

			foreach ( $items as $id => $item ) {

				if ( ! isset( $item['id'] ) ) {
					unset( $items[ $id ] );
				}
				// $items[ $id ] = array_replace_recursive( $this->get_args(), $item );
				$items[ $id ] = $item + $this->get_args();
			}

			return $this->save( $items );
		}
	}

	// Core
	protected function save( $data = null ) {
		if ( ! $this->table ) {
			error_log( 'Model can\'t be accesed directly' );
			die();
		}

		$this->cache[ $this->table ] = $data;

		return update_option( $this->table, $data );
	}

	protected function get() {
		if ( ! $this->table ) {
			error_log( 'Model can\'t be accesed directly' );
			die();
		}

		if ( ! isset( $this->cache[ $this->table ] ) ) {
			$this->cache[ $this->table ] = get_option( $this->table, $this->get_defaults() );

		}

		return $this->cache[ $this->table ];
	}

	protected function delete() {
		delete_option( $this->table );
		// update_option($this->table, $this->get_defaults());
	}
}
