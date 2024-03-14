<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

class PostMetaBag extends ParameterBag {

	/** @var int */
	private $post_id;

	public function __construct( int $id ) {
		$this->post_id = $id;
		$parameters    = array_map(
			static function ( $item ) {
				return maybe_unserialize( $item[0] );
			},
			get_post_meta( $this->post_id ) ?: []
		);
		parent::__construct( $parameters );
	}

	public function remove( string $key ) {
		delete_post_meta( $this->post_id, $key );
	}

	public function set( string $key, $value ): bool {
		parent::set( $key, $value );

		if ( $this->has( $key ) && ! $this->is_changed( $value, $this->get( $key ) ) ) {
			return false;
		}

		if ( $value === null ) {
			$this->remove( $key );
		}

		$result = update_post_meta( $this->post_id, $key, $value );

		if ( $result === false ) {
			throw new \RuntimeException( sprintf( 'Failed to set data for key: %s. Post: %d', $key, $this->post_id ) );
		}

		return (bool) $result;
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

}
