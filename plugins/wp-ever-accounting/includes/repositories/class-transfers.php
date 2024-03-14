<?php
/**
 * Transfer repository.
 *
 * Handle transfer insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Repository;

use EverAccounting\Models\Account;
use EverAccounting\Models\Payment;
use EverAccounting\Models\Revenue;
use EverAccounting\Models\Transfer;

defined( 'ABSPATH' ) || exit;

/**
 * Class Transfers
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Transfers extends Resource_Repository {
	/**
	 * Table name.
	 *
	 * @var string
	 */
	const TABLE = 'ea_transfers';

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
		'id'           => '%d',
		'income_id'    => '%d',
		'expense_id'   => '%d',
		'creator_id'   => '%d',
		'date_created' => '%s',
	);

	/*
	|--------------------------------------------------------------------------
	| CRUD Methods
	|--------------------------------------------------------------------------
	*/
	/**
	 * Method to create a new item in the database.
	 *
	 * @param Transfer $transfer Item object.
	 *
	 * @throws \Exception When item cannot be created.
	 */
	public function insert( &$transfer ) {
		global $wpdb;
		try {
			$wpdb->query( 'START TRANSACTION' );
			$from_account = new Account( $transfer->get_from_account_id() );
			$to_account   = new Account( $transfer->get_to_account_id() );
			$expense      = new Payment( $transfer->get_expense_id() );
			$expense->set_props(
				array(
					'account_id'     => $transfer->get_from_account_id(),
					'payment_date'   => $transfer->get_date(),
					'amount'         => $transfer->get_amount(),
					'description'    => $transfer->get_description(),
					'category_id'    => $transfer->get_category_id(),
					'payment_method' => $transfer->get_payment_method(),
					'reference'      => $transfer->get_reference(),
				)
			);
			$expense->save();
			$transfer->set_expense_id( $expense->get_id() );

			$amount = $transfer->get_amount();
			if ( $from_account->get_currency_code() !== $to_account->get_currency_code() ) {
				$expense_currency = eaccounting_get_currency( $from_account->get_currency_code() );
				$income_currency  = eaccounting_get_currency( $to_account->get_currency_code() );
				$amount           = eaccounting_price_convert( $amount, $from_account->get_currency_code(), $to_account->get_currency_code(), $expense_currency->get_rate(), $income_currency->get_rate() );
			}

			$income = new Revenue( $transfer->get_income_id() );
			$income->set_props(
				array(
					'account_id'     => $to_account->get_id(),
					'payment_date'   => $transfer->get_date(),
					'amount'         => $amount,
					'description'    => $transfer->get_description(),
					'category_id'    => $transfer->get_category_id(),
					'payment_method' => $transfer->get_payment_method(),
					'reference'      => $transfer->get_reference(),
				)
			);
			$income->save();
			$transfer->set_income_id( $income->get_id() );

			$values  = array();
			$formats = array();

			$fields = $this->data_type;

			foreach ( $fields as $key => $format ) {
				$method         = "get_$key";
				$values[ $key ] = $transfer->$method( 'edit' );
				$formats[]      = $format;
			}
			$inserting = false;
			if ( $transfer->exists() ) {
				unset( $formats[0] );
				unset( $values['id'] );
				$result = $wpdb->update(
					$wpdb->prefix . $this->table,
					wp_unslash( $values ),
					array(
						'id' => $transfer->get_id(),
					),
					$formats,
					'%d'
				);
			} else {
				$inserting = true;
				$result    = $wpdb->insert( $wpdb->prefix . $this->table, wp_unslash( $values ), $formats );
			}

			if ( false === $result ) {
				throw new \Exception( $wpdb->last_error );
			}
			if ( $inserting ) {
				$transfer->set_id( $wpdb->insert_id );
			}
			$transfer->apply_changes();
			$transfer->clear_cache();
			do_action( 'eacccounting_insert_' . $transfer->get_object_type(), $transfer, $values );
			$wpdb->query( 'COMMIT' );

			return true;
		} catch ( \Exception $e ) {
			$wpdb->query( 'ROLLBACK' );
			throw new \Exception( $e->getMessage() );
		}

	}


	/**
	 * Method to read a item from the database.
	 *
	 * @param Transfer $item Item object.
	 *
	 * @throws \Exception When item cannot be read.
	 */
	public function read( &$item ) {
		global $wpdb;
		$table = $wpdb->prefix . $this->table;

		$item->set_defaults();

		if ( ! $item->get_id() ) {
			$item->set_id( 0 );

			return false;
		}

		// Maybe retrieve from the cache.
		$raw_item = wp_cache_get( $item->get_id(), $item->get_cache_group() );

		// If not found, retrieve from the db.
		if ( false === $raw_item ) {
			$raw_item = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $item->get_id() )
			);
			// Update the cache with our data.
			wp_cache_set( $item->get_id(), $raw_item, $item->get_cache_group() );
		}

		if ( ! $raw_item ) {
			$item->set_id( 0 );

			throw new \Exception( __( 'Transfer data corrupted', 'wp-ever-accounting' ) );
		}

		foreach ( array_keys( $this->data_type ) as $key ) {
			$method = "set_$key";
			$item->$method( $raw_item->$key );
		}

		try {
			$income  = eaccounting_get_revenue( $item->get_income_id() );
			$expense = eaccounting_get_payment( $item->get_expense_id() );
			if ( $income ) {
				$item->set_to_account_id( $income->get_account_id() );
			}
			if ( $expense ) {
				$item->set_from_account_id( $expense->get_account_id() );
				$item->set_amount( $expense->get_amount() );
				$item->set_date( $expense->get_payment_date() );
				$item->set_payment_method( $expense->get_payment_method() );
				$item->set_description( $expense->get_description() );
				$item->set_reference( $expense->get_reference() );
			}
			$item->set_object_read( true );
			do_action( 'eaccounting_read_' . $item->get_object_type(), $item );
		} catch ( \Exception $e ) {
			throw new \Exception( $e->getMessage() );
		}

	}


	/**
	 * Method to update an item in the database.
	 *
	 * @param Transfer $item Subscription object.
	 *
	 * @throws \Exception When item cannot be updated.
	 */
	public function update( &$item ) {
		return $this->insert( $item );
	}

	/**
	 * Method to delete a subscription from the database.
	 *
	 * @param Transfer $item Transaction object.
	 */
	public function delete( &$item ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . 'ea_transactions', array( 'id' => $item->get_income_id() ) );
		$wpdb->delete( $wpdb->prefix . 'ea_transactions', array( 'id' => $item->get_expense_id() ) );

		parent::delete( $item );
	}
}
