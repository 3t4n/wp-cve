<?php
/**
 * Customer repository.
 *
 * Handle customer insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Repository;
use EverAccounting\Abstracts\Contact;

defined( 'ABSPATH' ) || exit;

/**
 * Class Contacts
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Contacts extends Resource_Repository {
	/**
	 * Name of the table.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const TABLE = 'ea_contacts';

	/**
	 * Table name.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $table = self::TABLE;

	/**
	 * Meta type.
	 *
	 * @var string
	 */
	protected $meta_type = 'contact';

	/**
	 * Cache group.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $cache_group = self::TABLE;

	/**
	 * A map of database fields to data types.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data_type = array(
		'id'            => '%d',
		'user_id'       => '%d',
		'name'          => '%s',
		'company'       => '%s',
		'email'         => '%s',
		'phone'         => '%s',
		'website'       => '%s',
		'vat_number'    => '%s',
		'birth_date'    => '%s',
		'street'        => '%s',
		'city'          => '%s',
		'state'         => '%s',
		'postcode'      => '%s',
		'country'       => '%s',
		'type'          => '%s',
		'currency_code' => '%s',
		'thumbnail_id'  => '%d',
		'enabled'       => '%d',
		'creator_id'    => '%d',
		'date_created'  => '%s',
	);

	/**
	 * Method to read a item from the database.
	 *
	 * @param Contact $item Item object.
	 */
	public function read( &$item ) {
		global $wpdb;
		$table = $wpdb->prefix . $this->table;

		$item->set_defaults();

		if ( ! $item->get_id() ) {
			$item->set_id( 0 );
			return;
		}

		// Get from cache if available.
		$data = wp_cache_get( $item->get_id(), $item->get_cache_group() );

		if ( false === $data ) {
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d LIMIT 1;", $item->get_id() ) );
			wp_cache_set( $item->get_id(), $data, $item->get_cache_group() );
		}

		if ( ! $data || $data->type !== $item->get_type() ) {
			$item->set_id( 0 );
			return;
		}

		foreach ( array_keys( $this->data_type ) as $key ) {
			$method = "set_$key";
			$item->$method( $data->$key );
		}

		$item->set_object_read( true );
		do_action( 'eaccounting_read_' . $item->get_object_type(), $item );
	}
}
