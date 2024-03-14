<?php
/**
 * Resource_Repository
 *
 * An Abstract class for repository class.
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Abstracts
 */

namespace EverAccounting\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Class Resource_Repository
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Abstracts
 */
abstract class Resource_Repository {
	/**
	 * A map of database fields to data types.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data_type = array();

	/**
	 * Meta type. This should match up with
	 * the types available at https://developer.wordpress.org/reference/functions/add_metadata/.
	 * WP defines 'post', 'user', 'comment', and 'term'.
	 *
	 * @var string
	 */
	protected $meta_type = false;

	/**
	 * This only needs set if you are using a custom metadata type.
	 *
	 * @var string
	 */
	protected $object_id_field_for_meta = '';

	/**
	 * Data stored in meta keys, but not considered "meta" for an object.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Meta data which should exist in the DB, even if empty.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $must_exist_meta_keys = array();

	/**
	 * A map of meta keys to data props.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $meta_key_to_props = array();

	/**
	 * Get and store terms from a taxonomy.
	 *
	 * @since  1.1.0
	 * @param  Resource_Model|integer $object object or object ID.
	 * @param  string                 $taxonomy Taxonomy name e.g. product_cat.
	 * @return array of terms
	 */
	protected function get_term_ids( $object, $taxonomy ) {
		if ( is_numeric( $object ) ) {
			$object_id = $object;
		} else {
			$object_id = $object->get_id();
		}
		$terms = get_the_terms( $object_id, $taxonomy );
		if ( false === $terms || is_wp_error( $terms ) ) {
			return array();
		}
		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Returns an array of meta for an object.
	 *
	 * @since  1.10
	 * @param  Resource_Model $object object.
	 * @return array
	 */
	public function read_meta( &$object ) {
		if ( $this->meta_type ) {
			global $wpdb;
			$db_info       = $this->get_db_info();
			$raw_meta_data = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT {$db_info['meta_id_field']} as meta_id, meta_key, meta_value
				FROM {$db_info['table']}
				WHERE {$db_info['object_id_field']} = %d
				ORDER BY {$db_info['meta_id_field']}",
					$object->get_id()
				)
			);

			$this->internal_meta_keys = array_merge( array_map( array( $this, 'prefix_key' ), $object->get_data_keys() ), $this->internal_meta_keys );
			$meta_data                = array_filter( $raw_meta_data, array( $this, 'exclude_internal_meta_keys' ) );
			return apply_filters( "eaccounting_{$this->meta_type}_read_meta", $meta_data, $object, $this );
		}
		return array();
	}

	/**
	 * Deletes meta based on meta ID.
	 *
	 * @since  1.1.0
	 * @param  Resource_Model $object object.
	 * @param  \stdClass      $meta (containing at least ->id).
	 */
	public function delete_meta( &$object, $meta ) {
		delete_metadata_by_mid( $this->meta_type, $meta->id );
	}

	/**
	 * Add new piece of meta.
	 *
	 * @since  1.1.0
	 * @param  Resource_Model $object object.
	 * @param  \stdClass      $meta (containing ->key and ->value).
	 * @return int meta ID
	 */
	public function add_meta( &$object, $meta ) {
		return add_metadata( $this->meta_type, $object->get_id(), $meta->key, is_string( $meta->value ) ? wp_slash( $meta->value ) : $meta->value, false );
	}

	/**
	 * Update meta.
	 *
	 * @since  1.1.0
	 * @param  Resource_Model $object object.
	 * @param  \stdClass      $meta (containing ->id, ->key and ->value).
	 */
	public function update_meta( &$object, $meta ) {
		update_metadata_by_mid( $this->meta_type, $meta->id, $meta->value, $meta->key );
	}

	/**
	 * Table structure is slightly different between meta types, this function will return what we need to know.
	 *
	 * @since  1.1.0
	 * @return array Array elements: table, object_id_field, meta_id_field
	 */
	protected function get_db_info() {
		global $wpdb;

		$meta_id_field = 'meta_id'; // users table calls this umeta_id so we need to track this as well.
		$table         = $wpdb->prefix;

		// If we are dealing with a type of metadata that is not a core type, the table should be prefixed.
		if ( ! in_array( $this->meta_type, array( 'post', 'user', 'comment', 'term' ), true ) ) {
			$table .= 'ea_';
		}

		$table          .= $this->meta_type . 'meta';
		$object_id_field = $this->meta_type . '_id';

		// Figure out our field names.
		if ( 'user' === $this->meta_type ) {
			$meta_id_field = 'umeta_id';
			$table         = $wpdb->usermeta;
		}

		if ( ! empty( $this->object_id_field_for_meta ) ) {
			$object_id_field = $this->object_id_field_for_meta;
		}

		return array(
			'table'           => $table,
			'object_id_field' => $object_id_field,
			'meta_id_field'   => $meta_id_field,
		);
	}

	/**
	 * Internal meta keys we don't want exposed as part of meta_data. This is in
	 * addition to all data props with _ prefix.
	 *
	 * @since 1.1.0
	 *
	 * @param string $key Prefix to be added to meta keys.
	 * @return string
	 */
	protected function prefix_key( $key ) {
		return '_' === substr( $key, 0, 1 ) ? $key : '_' . $key;
	}

	/**
	 * Callback to remove unwanted meta data.
	 *
	 * @param object $meta Meta object to check if it should be excluded or not.
	 * @return bool
	 */
	protected function exclude_internal_meta_keys( $meta ) {
		return ! in_array( $meta->meta_key, $this->internal_meta_keys, true ) && 0 !== stripos( $meta->meta_key, 'wp_' );
	}

	/**
	 * Gets a list of props and meta keys that need updated based on change state
	 * or if they are present in the database or not.
	 *
	 * @param Resource_Model $object            The object.
	 * @param array          $meta_key_to_props A mapping of meta keys => prop names.
	 * @param string         $meta_type         The internal WP meta type (post, user, etc).
	 *
	 * @return array        A mapping of meta keys => prop names, filtered by ones that should be updated.
	 */
	protected function get_props_to_update( $object, $meta_key_to_props, $meta_type ) {
		$props_to_update = array();
		$changed_props   = $object->get_changes();

		// Props should be updated if they are a part of the $changed array or don't exist yet.
		foreach ( $meta_key_to_props as $meta_key => $prop ) {
			if ( array_key_exists( $prop, $changed_props ) || ! metadata_exists( $meta_type, $object->get_id(), $meta_key ) ) {
				$props_to_update[ $meta_key ] = $prop;
			}
		}

		return $props_to_update;
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @param Resource_Model $object The object.
	 * @param string         $meta_key Meta key to update.
	 * @param mixed          $meta_value Value to save.
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_post_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->must_exist_meta_keys, true ) ) {
			$updated = delete_post_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_post_meta( $object->get_id(), $meta_key, $meta_value );
		}

		return (bool) $updated;
	}

	/**
	 * Return list of internal meta keys.
	 *
	 * @since 1.1.0
	 * @return array
	 */
	public function get_internal_meta_keys() {
		return $this->internal_meta_keys;
	}

	/**
	 * Read object data.
	 *
	 * @param Resource_Model $object object.
	 * @since 1.1.0
	 */
	protected function read_object_meta( &$object ) {
		$id    = $object->get_id();
		$props = array();

		foreach ( $this->meta_key_to_props as $meta_key => $prop ) {
			$props[ $prop ] = get_post_meta( $id, $meta_key, true );
		}

		// Set object properties.
		$object->set_props( $props );

		// Gets extra data associated with the object if needed.
		foreach ( $object->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $object, $function ) ) ) {
				$object->{$function}( get_post_meta( $object->get_id(), $key, true ) );
			}
		}
	}

	/**
	 * Helper method that updates all the post meta for an object based on it's settings in the Resource_Model class.
	 *
	 * @param Resource_Model $object object.
	 * @since 1.1.0
	 */
	protected function update_object_meta( &$object ) {

		$updated_props   = array();
		$props_to_update = $this->get_props_to_update( $object, $this->meta_key_to_props, $this->meta_type );
		$object_type     = $object->get_object_type();

		foreach ( $props_to_update as $meta_key => $prop ) {
			$value   = $object->{"get_$prop"}( 'edit' );
			$value   = is_string( $value ) ? wp_slash( $value ) : $value;
			$updated = $this->update_or_delete_post_meta( $object, $meta_key, $value );

			if ( $updated ) {
				$updated_props[] = $prop;
			}
		}

		do_action( "eaccounting_{$object_type}_object_updated_props", $object, $updated_props );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Methods
	|--------------------------------------------------------------------------
	*/
	/**
	 * Method to create a new item in the database.
	 *
	 * @param Resource_Model $item Item object.
	 *
	 * @throws \Exception When invalid data is found.
	 */
	public function insert( &$item ) {
		global $wpdb;

		$values  = array();
		$formats = array();

		$fields = $this->data_type;
		unset( $fields['id'] );

		foreach ( $fields as $key => $format ) {
			$method         = "get_$key";
			$data           = $item->$method();
			$values[ $key ] = is_array( $data ) ? maybe_serialize( $data ) : $data;
			$formats[]      = $format;
		}

		$result = $wpdb->insert( $wpdb->prefix . $this->table, wp_unslash( $values ), $formats );
		if ( false === $result ) {
			throw new \Exception( $wpdb->last_error );
		}
		if ( $result ) {
			$item->set_id( $wpdb->insert_id );
			$item->save_meta_data();
			$item->apply_changes();
			do_action( 'eacccounting_insert_' . $item->get_object_type(), $item, $values );

			return true;
		}

		return false;
	}

	/**
	 * Method to read a item from the database.
	 *
	 * @param Resource_Model $item Item object.
	 *
	 * @throws \Exception When invalid data is found.
	 */
	public function read( &$item ) {
		global $wpdb;
		$table = $wpdb->prefix . $this->table;

		$item->set_defaults();

		if ( ! $item->get_id() ) {
			$item->set_id( 0 );
			throw new \Exception( $wpdb->last_error );
		}

		// Get from cache if available.
		$data = wp_cache_get( $item->get_id(), $item->get_cache_group() );

		if ( false === $data ) {
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d LIMIT 1;", $item->get_id() ) );
			wp_cache_set( $item->get_id(), $data, $item->get_cache_group() );
		}

		if ( ! $data ) {
			$item->set_id( 0 );
			return;
		}

		foreach ( array_keys( $this->data_type ) as $key ) {
			$method = "set_$key";
			$item->$method( maybe_unserialize( $data->$key ) );
		}
		$item->read_meta_data();
		$item->set_object_read( true );
		do_action( 'eaccounting_read_' . $item->get_object_type(), $item );
	}

	/**
	 * Method to update an item in the database.
	 *
	 * @param Resource_Model $item Subscription object.
	 *
	 * @throws \Exception When invalid data is found.
	 */
	public function update( &$item ) {
		global $wpdb;
		$table   = $wpdb->prefix . $this->table;
		$changes = $item->get_changes();
		$values  = array();
		$formats = array();
		$item->save_meta_data();
		foreach ( $this->data_type as $key => $format ) {
			if ( array_key_exists( $key, $changes ) ) {
				$method         = "get_$key";
				$data           = $item->$method();
				$values[ $key ] = is_array( $data ) ? maybe_serialize( $data ) : $data;
				$formats[]      = $format;
			}
		}

		if ( empty( $values ) ) {
			return;
		}

		if ( false === $wpdb->update(
			$table,
			wp_unslash( $values ),
			array(
				'id' => $item->get_id(),
			),
			$formats,
			'%d'
		) ) {
			throw new \Exception( $wpdb->last_error );
		}

		// Apply the changes.
		$item->apply_changes();

		// Delete cache.
		$item->clear_cache();
		// Fire a hook.
		do_action( 'eaccounting_update_' . $item->get_object_type(), $item->get_id(), $item, $changes );
	}

	/**
	 * Method to delete a subscription from the database.
	 *
	 * @param Resource_Model $item Item object.
	 */
	public function delete( &$item ) {
		global $wpdb;
		$table = $wpdb->prefix . $this->table;
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$table} WHERE id = %d", $item->get_id() ) );
		if ( $this->meta_type ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}ea_{$this->meta_type}meta WHERE {$this->meta_type}_id = %d", $item->get_id() ) );
		}

		// Delete cache.
		$item->clear_cache();
		// Fire a hook.
		do_action( 'eaccounting_delete_' . $item->get_object_type(), $item->get_id(), $item );
		$item->set_id( 0 );
		$item->set_defaults();
	}

	/*
	|--------------------------------------------------------------------------
	| Additional Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get columns.
	 *
	 * @return int[]|string[]
	 */
	public static function get_columns() {
		$self = new static();
		return array_keys( $self->data_type );
	}
}
