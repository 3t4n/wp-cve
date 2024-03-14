<?php
/**
 * Invoice repository.
 *
 * Handle invoice insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Repository;
use EverAccounting\Abstracts\Document;
use EverAccounting\Models\Document_Item;

defined( 'ABSPATH' ) || exit;

/**
 * Class Accounts
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Documents extends Resource_Repository {
	/**
	 * The table name.
	 *
	 * @var string
	 */
	const TABLE = 'ea_documents';

	/**
	 * The table name.
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
		'id'              => '%d',
		'document_number' => '%s',
		'type'            => '%s',
		'order_number'    => '%s',
		'status'          => '%s',
		'issue_date'      => '%s',
		'due_date'        => '%s',
		'payment_date'    => '%s',
		'category_id'     => '%d',
		'contact_id'      => '%d',
		'address'         => '%s',
		'currency_code'   => '%s',
		'currency_rate'   => '%.8f',
		'discount'        => '%.4f',
		'discount_type'   => '%s',
		'subtotal'        => '%.4f',
		'total_tax'       => '%.4f',
		'total_discount'  => '%.4f',
		'total_fees'      => '%.4f',
		'total_shipping'  => '%.4f',
		'total'           => '%.4f',
		'tax_inclusive'   => '%d',
		'note'            => '%s',
		'terms'           => '%s',
		'attachment_id'   => '%d',
		'key'             => '%s',
		'parent_id'       => '%d',
		'creator_id'      => '%d',
		'date_created'    => '%s',
	);


	/**
	 * Get the next available number.
	 *
	 * @param Document $document Document object.
	 * @since 1.1.0
	 * @return int
	 */
	public function get_next_number( &$document ) {
		global $wpdb;
		$max = (int) $wpdb->get_var( $wpdb->prepare( "select max(id) from {$wpdb->prefix}ea_documents WHERE type=%s", $document->get_type() ) );
		return $max + 1;
	}

	/**
	 * Read order items of a specific type from the database for this order.
	 *
	 * @param Document $document Order object.
	 *
	 * @return array
	 */
	public function get_items( $document ) {
		global $wpdb;
		if ( ! $document->get_id() ) {
			return array();
		}

		// Get from cache if available.
		$cache_key = 'query:document-items' . md5( $document->get_id() ) . ':' . wp_cache_get_last_changed( 'ea_document_items' );
		$items     = wp_cache_get( $cache_key, 'ea_document_items' );
		if ( false === $items ) {
			$items = $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ea_document_items WHERE document_id = %d ORDER BY id;", $document->get_id() )
			);
			foreach ( $items as $item ) {
				wp_cache_set( 'document-item-' . $item->id, $item, 'ea_document_items' );
			}
			if ( 0 < $document->get_id() ) {
				wp_cache_set( $cache_key, $items, 'ea_document_items' );
			}
		}
		$results = array();
		foreach ( $items as $item ) {
			$results[ $item->id ] = new Document_Item( $item );
		}

		return $results;
	}

	/**
	 * Delete Invoice Items.
	 *
	 * @since 1.1.0
	 *
	 * @param Document_Item $item Item object.
	 */
	public function delete_items( $item ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . Document_Items::TABLE, array( 'document_id' => $item->get_id() ) );
		eaccounting_cache_set_last_changed( 'ea_document_items' );
	}

	/**
	 * Delete Invoice notes.
	 *
	 * @since 1.1.0
	 *
	 * @param Document $item Document object.
	 */
	public function delete_notes( $item ) {
		global $wpdb;
		$wpdb->delete(
			$wpdb->prefix . Notes::TABLE,
			array(
				'parent_id' => $item->get_id(),
				'type'      => $item->get_type(),
			)
		);
		eaccounting_cache_set_last_changed( 'ea_notes' );
	}

	/**
	 * Delete transactions.
	 *
	 * @param Document $item Document object.
	 * @since 1.1.0 Delete all related transactions.
	 */
	public function delete_transactions( $item ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . Transactions::TABLE, array( 'document_id' => $item->get_id() ) );
		eaccounting_cache_set_last_changed( 'ea_transactions' );
	}

	/**
	 * Delete items.
	 *
	 * @param \EverAccounting\Abstracts\Resource_Model $item Item object.
	 * @since 1.1.0
	 */
	public function delete( &$item ) {
		$this->delete_items( $item );
		$this->delete_notes( $item );
		$this->delete_transactions( $item );
		parent::delete( $item );
	}
}
