<?php
namespace Thim_EL_Kit\Modules;

use Thim_EL_Kit\SingletonTrait;

class Cache {

	use SingletonTrait;

	public $conditions = array();

	const OPTION_NAME = 'thim_ekits_option_conditions';

	const CONDITION_META_KEY = 'thim_ekits_conditions';

	public function __construct() {
		$this->refresh();
	}

	public function save() {
		return update_option( self::OPTION_NAME, $this->conditions );
	}

	public function refresh() {
		$this->conditions = get_option( self::OPTION_NAME, array() );
	}

	public function clear() {
		$this->conditions = array();
	}

	public function get( $type ) {
		return isset( $this->conditions[ $type ] ) ? $this->conditions[ $type ] : array();
	}

	public function add( $type, $conditions, $post_id ) {
		if ( $type ) {
			if ( ! isset( $this->conditions[ $type ] ) ) {
				$this->conditions[ $type ] = array();
			}
			$this->conditions[ $type ][ $post_id ] = $conditions;
		}

		return $this;
	}

	public function remove( $post_id ) {
		$post_id = absint( $post_id );

		foreach ( $this->conditions as $type => $templates ) {
			foreach ( $templates as $id => $template ) {
				if ( $post_id === $id ) {
					unset( $this->conditions[ $type ][ $id ] );
				}
			}
		}

		return $this;
	}

	public function regenerate() {
		$this->clear();

		$query = new \WP_Query(
			array(
				'posts_per_page' => - 1,
				'post_type'      => \Thim_EL_Kit\Custom_Post_Type::CPT,
				'fields'         => 'ids',
				'meta_key'       => self::CONDITION_META_KEY, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			)
		);

		foreach ( $query->posts as $post_id ) {
			$conditions = get_post_meta( $post_id, self::CONDITION_META_KEY, true );
			$type       = get_post_meta( $post_id, 'thim_elementor_type', true );

			$this->add( $type, $conditions, $post_id );
		}

		$this->save();

		return $this;
	}
}
