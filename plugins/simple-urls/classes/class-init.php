<?php
/**
 * Declare class Helper
 *
 * @package Helper
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;
use LassoLite\Classes\Helper;

/**
 * Init
 */
class Init {
	/**
	 * Declare vars of Lasso_Init
	 *
	 * @var array $classes List of classes
	 */
	private $classes;

	/**
	 * Declare vars of Lasso_Init
	 *
	 * @var array $ajaxes List of ajaxes
	 */
	private $ajaxes;

	/**
	 * Declare vars of Lasso_Init
	 *
	 * @var array $hooks List of hooks
	 */
	private $hooks;

	/**
	 * Declare vars of Lasso_Init
	 *
	 * @var array $processes List of processes
	 */
	private $processes;

	/**
	 * Init constructor.
	 */
	public function __construct() {

		$this->init_site_id();

		$this->ajaxes = array(
			'\LassoLite\Pages\Ajax',
			'\LassoLite\Pages\Dashboard\Ajax',
			'\LassoLite\Pages\Import_Urls\Ajax',
			'\LassoLite\Pages\Url_Details\Ajax',
			'\LassoLite\Pages\Settings\Ajax',
			'\LassoLite\Pages\Groups\Ajax',
		);

		$this->hooks = array(
			'\LassoLite\Pages\Hook',
			'\LassoLite\Pages\Url_Details\Hook',
			'\LassoLite\Pages\Import_Urls\Hook',
		);

		$this->classes = array(
			'\LassoLite\Classes\Cron',
		);

		$this->processes = array(
			'\LassoLite\Classes\Processes\Amazon',
			'\LassoLite\Classes\Processes\Import_All',
			'\LassoLite\Classes\Processes\Revert_All',
		);

		$this->load_classes();
		$this->update_db();
	}

	/**
	 * Load classes
	 */
	public function load_classes() {
		$this->initalize_ajaxes();
		$this->initalize_hooks();
		$this->initalize_classes();
		$this->initalize_processes();
	}

	/**
	 * Register hooks
	 */
	public function initalize_hooks() {
		foreach ( $this->hooks as $hook_class ) {
			$hook_object = new $hook_class();
			$hook_object->register_hooks();
		}
	}

	/**
	 * Register Ajax hooks
	 */
	public function initalize_ajaxes() {
		foreach ( $this->ajaxes as $ajax_class ) {
			$ajax_object = new $ajax_class();
			$ajax_object->register_hooks();
		}
	}

	/**
	 * Create an object of class
	 */
	public function initalize_classes() {
		foreach ( $this->classes as $class ) {
			new $class();
		}
	}

	/**
	 * Create an object of class
	 */
	public function initalize_processes() {
		foreach ( $this->processes as $process ) {
			new $process();
		}
	}

	/**
	 * Init Lasso Lite site id
	 */
	public function init_site_id() {
		if ( empty( Helper::get_option( Constant::SITE_ID_KEY ) ) ) {
			$url_parts    = wp_parse_url( home_url() );
			$domain       = $url_parts['host'];
			$hash_site_id = md5( $domain );
			Helper::update_option( Constant::SITE_ID_KEY, $hash_site_id );
		}
	}

	/**
	 * Execute Update Database
	 *
	 * @return void
	 */
	public function update_db() {
		new Update_DB();
	}
}
