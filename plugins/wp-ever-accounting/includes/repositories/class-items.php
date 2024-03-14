<?php
/**
 * Item repository.
 *
 * Handle item insert, update, delete & retrieve from database.
 *
 * @version   1.1.0
 * @package   EverAccounting\Repositories
 */

namespace EverAccounting\Repositories;

use EverAccounting\Abstracts\Resource_Repository;

defined( 'ABSPATH' ) || exit;

/**
 * Class Accounts
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Items extends Resource_Repository {
	/**
	 * Table name
	 *
	 * @var string
	 */
	const TABLE = 'ea_items';

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
		'id'             => '%d',
		'name'           => '%s',
		'sku'            => '%s',
		'description'    => '%s',
		'sale_price'     => '%.4f',
		'purchase_price' => '%.4f',
		'quantity'       => '%f',
		'category_id'    => '%d',
		'sales_tax'      => '%.4f',
		'purchase_tax'   => '%.4f',
		'thumbnail_id'   => '%d',
		'enabled'        => '%d',
		'creator_id'     => '%d',
		'date_created'   => '%s',
	);




}
