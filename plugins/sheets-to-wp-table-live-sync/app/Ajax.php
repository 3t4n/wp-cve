<?php
/**
 * Responsible for managing ajax endpoints.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for handling ajax endpoints.
 *
 * @since 2.12.15
 * @package SWPTLS
 */
class Ajax {

	/**
	 * Contains promotional wppool products.
	 *
	 * @var \SWPTLS\Ajax\FetchProducts
	 */
	public $products;

	/**
	 * Contains plugins notices ajax operations.
	 *
	 * @var \SWPTLS\Ajax\ManageNotices
	 */
	public $notices;

	/**
	 * Contains table delete ajax operations.
	 *
	 * @var \SWPTLS\Ajax\UdTables
	 */
	public $ud_tables;

	/**
	 * Contains plugin tables ajax operations.
	 *
	 * @var mixed
	 */
	public $tables;

	/**
	 * Contains plugin tabs ajax operations.
	 *
	 * @var mixed
	 */
	public $tabs;

	/**
	 * Contains plugin settings ajax endpoints.
	 *
	 * @var mixed
	 */
	public $settings;

	/**
	 * Class constructor.
	 *
	 * @since 2.12.15
	 */
	public function __construct() {
		$this->products = new \SWPTLS\Ajax\Products();
		$this->notices  = new \SWPTLS\Ajax\Notices();
		$this->tables   = new \SWPTLS\Ajax\Tables();
		$this->settings = new \SWPTLS\Ajax\Settings();
	}
}
