<?php
/**
 * Currency repository.
 *
 * Handle currency insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Abstracts\Resource_Repository;
use EverAccounting\Models\Currency;

defined( 'ABSPATH' ) || exit;

/**
 * Class Currencies
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Currencies extends Resource_Repository {
	/**
	 * Table name
	 *
	 * @var string
	 */
	const OPTION = 'eaccounting_currencies';

	/**
	 * Get table name.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $option = self::OPTION;

	/**
	 * A map of database fields to data types.
	 *
	 * @since 1.1.0
	 * @var array
	 */
	protected $data_type = array(
		'id'                 => '%d',
		'name'               => '%s',
		'code'               => '%s',
		'rate'               => '%f',
		'precision'          => '%d',
		'symbol'             => '%s',
		'subunit'            => '%d',
		'position'           => '%s',
		'decimal_separator'  => '%s',
		'thousand_separator' => '%s',
		'date_created'       => '%s',
	);

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
	 * @throws \Exception If item is not an instance of Resource_Model.
	 */
	public function insert( &$item ) {
		$values = array();

		$fields = $this->data_type;
		foreach ( $fields as $key => $format ) {
			$method         = "get_$key";
			$data           = $item->$method();
			$value          = is_array( $data ) ? maybe_serialize( $data ) : $data;
			$values[ $key ] = sprintf( $format, $value );
		}
		$currencies = $this->get_currencies();
		if ( ! $currencies->where( 'code', $item->get_code() )->count() ) {
			$id           = wp_generate_uuid4();
			$values['id'] = $id;
			$currencies->push( $values );
			update_option( self::OPTION, $currencies->all() );
			wp_cache_delete( 'ea_currencies', 'ea_currencies' );
			$item->set_id( $id );
			$item->apply_changes();
			$item->clear_cache();
			$item->set_object_read( true );
			do_action( 'eacccounting_insert_' . $item->get_object_type(), $item, $values );
			return true;
		}
	}

	/**
	 * Method to read a item from the database.
	 *
	 * @param Currency $item Item object.
	 *
	 * @throws \Exception If item is not an instance of Resource_Model.
	 */
	public function read( &$item ) {
		if ( empty( $item->get_code() ) ) {
			$item->set_defaults();
			$item->set_object_read( false );

			return;
		}

		$codes      = $this->get_codes();
		$currencies = $this->get_currencies();
		$saved      = $currencies->where( 'code', $item->get_code() );
		$currency   = $saved->merge( $codes->where( 'code', $item->get_code() ) )->first();
		$currency   = array_merge(
			array(
				'date_created' => null,
				'id'           => null,
			),
			$currency
		);

		foreach ( array_keys( $this->data_type ) as $key ) {
			$method = "set_$key";
			$item->$method( maybe_unserialize( $currency[ $key ] ) );
		}

		if ( ! empty( $currencies->where( 'code', $item->get_code() )->count() ) ) {
			$item->set_id( $item->get_id() );
			$item->set_object_read( $saved );
			do_action( 'eaccounting_read_' . $item->get_object_type(), $item );
		}
	}

	/**
	 * Method to update an item in the database.
	 *
	 * @param Resource_Model $item Subscription object.
	 *
	 * @throws \Exception If item is not an instance of Resource_Model.
	 */
	public function update( &$item ) {
		$changes = $item->get_changes();
		if ( empty( $changes ) ) {
			return;
		}

		$currencies = $this->get_currencies();
		$currencies = $currencies->each(
			function ( $currency ) use ( $item, $changes ) {
				if ( $item->get_code() === $currency['code'] ) {
					$currency = array_merge( $currency, $changes );
				}

				return $currency;
			}
		)->all();

		update_option( self::OPTION, $currencies );
		wp_cache_delete( 'ea_currencies', 'ea_currencies' );

		// Apply the changes.
		$item->apply_changes();
		// Fire a hook.
		do_action( 'eaccounting_update_' . $item->get_object_type(), $changes, $item );
	}

	/**
	 * Method to delete a subscription from the database.
	 *
	 * @param Resource_Model $item Subscription object.
	 */
	public function delete( &$item ) {
		$code       = $item->get_code();
		$currencies = $this->get_currencies()->reject(
			function ( $currency ) use ( $code ) {
				return $currency['code'] === $code;
			}
		)->all();
		update_option( self::OPTION, $currencies );
		wp_cache_delete( 'ea_currencies', 'ea_currencies' );
		// Delete cache.
		$item->clear_cache();
		// Fire a hook.
		do_action( 'eaccounting_delete_' . $item->get_object_type(), $item->get_id(), $item->get_data(), $item );
		$item->set_id( 0 );
	}

	/**
	 * Get raw currencies.
	 *
	 * @since 1.1.0
	 * @return \EverAccounting\Collection
	 */
	public function get_currencies() {
		$currencies = wp_cache_get( 'ea_currencies', 'ea_currencies' );
		if ( false === $currencies ) {
			$currencies = get_option( self::OPTION, array() );
			wp_cache_set( 'ea_currencies', $currencies, 'ea_currencies' );
		}

		return eaccounting_collect( $currencies );
	}

	/**
	 * Get all the codes.
	 *
	 * @since 1.1.0
	 * @return \EverAccounting\Collection
	 */
	public function get_codes() {
		return eaccounting_collect( array_values( eaccounting_get_currency_codes() ) );
	}

}
