<?php

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Admin\Admin;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use WP_Error;

if( !defined('ABSPATH') ){
	die();
}

/**
 * Admin page base class, all pages should extend from this
 * @ignore
 */
abstract class Page_Abstract implements Page_Interface {

	/**
	 * @var Admin
	 */
	private $admin;

	/**
	 * Page slug
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Page title
	 *
	 * @var string
	 */
	private $page_title;

	/**
	 * Menu title
	 *
	 * @var string
	 */
	private $menu_title;

	/**
     * Parent page
     *
	 * @var bool|string|Page_Interface
	 */
	private $parent = false;

	/**
     * Capability needed to view page
     *
	 * @var string
	 */
	private $capability = '';

	/**
	 * Menu page ID
	 *
	 * @var string
	 */
	private $page_id;

	/**
	 * The current item ID being edited/deleted
	 *
	 * @var null|int
	 */
	private $item_id = null;

	/**
	 * @var null|WP_Error
	 */
	private $error = null;

	/**
	 * Page_Abstract constructor.
	 *
	 * @param Admin $admin
	 * @param $page_title
	 * @param $menu_title
	 * @param $slug
	 * @param bool $parent
	 * @param string $capability
	 */
	public function __construct( Admin $admin, $page_title, $menu_title, $slug, $parent = false, $capability = 'activate_plugins' ){
		$this->admin = $admin;
		$this->slug = $slug;
		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->parent = $parent;
		$this->capability = $capability;

		add_action( 'admin_notices', [ $this, 'notice' ] );
	}

	/**
	 * Callback for admin notices action
	 */
	public function notice() {
		if( ( $this->get_var( 'message' ) || is_wp_error( $this->get_error() ) ) && $this->is_current_page() ){
		    $class = is_wp_error( $this->get_error() ) ? 'notice-error' : 'notice-success is-dismissible';
			?>
			<div class="notice <?php echo $class;?>">
				<p><?php echo is_wp_error( $this->get_error() ) ? $this->get_error()->get_error_message() : $this->get_message( $this->get_var( 'message' ) ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Override in child implementations with the messages that are passed
	 *
	 * @param $code
	 */
	public function get_message( $code ){}

	/**
	 * @return Plugin
	 */
	public function get_plugin() {
		return $this->admin->get_plugin();
	}

	/**
	 * @return Admin
	 */
	public function get_admin() {
		return $this->admin;
	}

	/**
	 * @return string
	 */
	public function get_menu_slug() {
		return $this->slug;
	}

	/**
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function get_menu_page( $echo = true ) {
		$menu_page_url =  menu_page_url( $this->get_menu_slug(), false );
		if( $echo ){
			echo $menu_page_url;
		}
		return $menu_page_url;
	}

	/**
	 * Returns the page title
	 * @return string
	 */
	public function get_page_title() {
		return $this->page_title;
	}

	/**
	 * Returns the menu title
	 * @return string
	 */
	public function get_menu_title() {
		return $this->menu_title;
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	protected function get_var( $name ) {
		return Helper::get_var( $name );
	}

	/**
	 * Sets item ID from GET
	 *
	 * @param string $var_name
	 */
	protected function set_item_id( $var_name = 'item_id' ) {
		if( Helper::get_var( 'item_id' ) ) {
			$this->item_id = absint( $this->get_var( $var_name ) );
		}
	}

	/**
	 * @return int|null
	 */
	public function get_item_id() {
		return $this->item_id;
	}

	/**
	 * Set the page ID that is returned by add_menu_page()/add_submenu_page()
	 *
	 * @param string $page_id
	 *
	 * @return string
	 */
	public function set_page_id( $page_id ) {
		$this->page_id = $page_id;
	}

	/**
	 * @return string
	 */
	public function get_page_id() {
		return $this->page_id;
	}

	/**
	 * @return bool|string|Page_Interface
	 */
	public function get_parent() {
		return $this->parent;
	}

	/**
	 * @return string
	 */
	public function get_capability() {
		return $this->capability;
	}

	/**
	 * Determines if current admin page is the same with the page registered.
	 * Useful to check if hooking to admin_notices and don't want to do additional checking in class
	 * to see if the registered page is the same with the one calling the callback.
     *
     * @return bool
	 */
	protected function is_current_page() {
		$screen = get_current_screen();
		return $this->get_page_id() == $screen->id;
	}

	/**
     * Set an error
     *
	 * @param $code
	 * @param $message
	 * @param null $data
	 */
	protected function set_error( $code, $message, $data = null ) {
	    $this->error = new WP_Error( $code, $message, $data );
    }

	/**
     * Get the error
     *
	 * @return WP_Error|null
	 */
    protected function get_error() {
	    return $this->error;
    }
}