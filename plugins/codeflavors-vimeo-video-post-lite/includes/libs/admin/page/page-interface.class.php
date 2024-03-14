<?php

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Admin\Admin;
use Vimeotheque\Plugin;

if( !defined('ABSPATH') ){
	die();
}

/**
 * Admin page Interface
 * @ignore
 */
interface Page_Interface{
	/**
	 * Returns the page output
	 * @return string
	 */
	public function get_html();

	/**
	 * The page "on_load" callback function
	 * @return mixed
	 */
	public function on_load();

	/**
	 * Returns the page title
	 * @return string
	 */
	public function get_page_title();

	/**
	 * Returns the menu title
	 * @return string
	 */
	public function get_menu_title();

	/**
	 * Returns the menu slug
	 * @return string
	 */
	public function get_menu_slug();

	/**
	 * @param bool $echo
	 *
	 * @return mixed
	 */
	public function get_menu_page( $echo = true );

	/**
	 * @return Plugin
	 */
	public function get_plugin();

	/**
	 * @return Admin
	 */
	public function get_admin();

	/**
	 * Set the page ID returned by WP add_menu_page
	 *
	 * @param $page_id
	 *
	 * @return string
	 */
	public function set_page_id( $page_id );

	/**
	 * Returns the page ID set previously by $this->set_page_id()
	 *
	 * @return string
	 */
	public function get_page_id();

	/**
	 * Return capability needed to view page
	 *
	 * @return string
	 */
	public function get_capability();

	/**
	 * Return page parent
	 *
	 * @return false|string|Page_Interface
	 */
	public function get_parent();
}
