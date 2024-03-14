<?php
/**
 * Document_Items repository.
 *
 * Handle Invoice Item insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Repository;

defined( 'ABSPATH' ) || exit;

/**
 * Class InvoiceItems
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Document_Items extends Resource_Repository {
	/**
	 * Table name
	 *
	 * @var string
	 */
	const TABLE = 'ea_document_items';

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
		'id'            => '%d',
		'document_id'   => '%d',
		'item_id'       => '%d',
		'item_name'     => '%s',
		'price'         => '%.4f',
		'quantity'      => '%.2f',
		'subtotal'      => '%.4f',
		'tax_rate'      => '%.4f',
		'discount'      => '%.4f',
		'tax'           => '%.4f',
		'total'         => '%.4f',
		'currency_code' => '%s',
		'extra'         => '%s',
		'date_created'  => '%s',
	);


}
