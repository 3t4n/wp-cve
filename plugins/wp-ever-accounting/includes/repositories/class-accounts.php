<?php
/**
 * Account repository.
 *
 * Handle account insert, update, delete & retrieve from database.
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
class Accounts extends Resource_Repository {
	/**
	 * Table name
	 *
	 * @var string
	 */
	const TABLE = 'ea_accounts';

	/**
	 * Get table name.
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
		'currency_code'   => '%s',
		'name'            => '%s',
		'number'          => '%s',
		'opening_balance' => '%.4f',
		'bank_name'       => '%s',
		'bank_phone'      => '%s',
		'bank_address'    => '%s',
		'thumbnail_id'    => '%d',
		'enabled'         => '%d',
		'creator_id'      => '%d',
		'date_created'    => '%s',
	);

}
