<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

use ShopMagicVendor\WPDesk\Persistence\FallbackFromGetTrait;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;

class PostMetaContainer implements PersistentContainer {
	use FallbackFromGetTrait;

	/** @var int */
	protected $post_id;

	/** @var array|string[] */
	protected $data;

	public function __construct( int $id ) {
		$this->post_id = $id;
		$this->data    = array_map(
			static function ( $item ) {
				return maybe_unserialize( $item[0] );
			},
			get_post_meta( $this->post_id ) ?: []
		);
	}

	public function set( string $id, $value ): bool {
		if ( $this->has( $id ) && ! $this->is_changed( $value, $this->get( $id ) ) ) {
			return false;
		}

		if ( $value === null ) {
			$this->delete( $id );
		}

		$result = update_post_meta( $this->post_id, $id, $value );

		if ( $result === false ) {
			throw new \RuntimeException( sprintf( 'Failed to set data for key: %s. Post: %d', $id, $this->post_id ) );
		}

		return (bool) $result;
	}

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function has( $id ): bool {
		return isset( $this->data[ $id ] );
	}

	private function is_changed( $new_value, $old_value ): bool {
		if ( is_bool( $new_value ) ) {
			$old_value = $old_value === '1';
		}

		if ( is_numeric( $new_value ) && is_numeric( $old_value ) ) {
			return (int) $new_value !== (int) $old_value;
		}

		return $new_value !== $old_value;
	}

	public function get( $id ) {
		if ( $this->has( $id ) ) {
			return $this->data[ $id ];
		}

		throw new \RuntimeException( \sprintf( 'Element %s not exists for resource %d!', $id, $this->post_id ) );
	}

	public function delete( string $id ) {
		delete_post_meta( $this->post_id, $id );
	}

	public function get_all() {
		return array_map( static function ( $item ) {
			if ( $item === 'yes' ) {
				return true;
			}

			if ( $item === 'no' ) {
				return false;
			}

			return $item;
		}, $this->data );
	}
}
