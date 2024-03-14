<?php
/**
 * Transaction repository.
 *
 * Handle transaction insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Repository;
use EverAccounting\Abstracts\Transaction;

defined( 'ABSPATH' ) || exit;

/**
 * Class Transactions
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Transactions extends Resource_Repository {

	/**
	 * Name of the table.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	const TABLE = 'ea_transactions';

	/**
	 * Table name.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $table = self::TABLE;

	/**
	 * A map of database fields to data types.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data_type = array(
		'id'             => '%d',
		'type'           => '%s',
		'payment_date'   => '%s',
		'amount'         => '%.4f',
		'currency_code'  => '%s', // protected.
		'currency_rate'  => '%.8f', // protected.
		'account_id'     => '%d',
		'document_id'    => '%d',
		'contact_id'     => '%d',
		'category_id'    => '%d',
		'description'    => '%s',
		'payment_method' => '%s',
		'reference'      => '%s',
		'attachment_id'  => '%d',
		'parent_id'      => '%d',
		'reconciled'     => '%d',
		'creator_id'     => '%d',
		'date_created'   => '%s',
	);

	/**
	 * Method to read a item from the database.
	 *
	 * @param Transaction $item Item object.
	 *
	 * @throws \Exception If there is an error reading from the database.
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
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d AND type =%s LIMIT 1;", $item->get_id(), $item->get_type() ) );
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

		$item->set_object_read( true );
		do_action( 'eaccounting_read_' . $item->get_object_type(), $item );
	}


}
