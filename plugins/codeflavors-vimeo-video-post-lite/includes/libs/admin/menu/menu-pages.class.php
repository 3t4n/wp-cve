<?php
namespace Vimeotheque\Admin\Menu;

use Vimeotheque\Admin\Page\Page_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Menu_Pages
 * @ignore
 */
class Menu_Pages {
	/**
	 * @var Page_Interface[]
	 */
	private $pages;

	/**
	 * Menu_Pages constructor.
	 *
	 * @param Page_Interface $page
	 * @param null|Page_Interface|string $parent
	 * @param string $capability
	 */
	public function __construct(  Page_Interface $page, /*Page_Interface*/ $parent = NULL, $capability = 'activate_plugins'  ) {
		$this->register_page( $page, $parent, $capability );

		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	/**
	 * @return Page_Interface[]
	 */
	public function get_pages() {
		return $this->pages;
	}

	/**
	 * @param string $slug
	 *
	 * @return bool|Page_Interface
	 */
	public function get_page( $slug ){
		if( isset( $this->pages[ $slug ] ) ){
			return $this->pages[ $slug ];
		}

		return false;
	}

	/**
	 * @param Page_Interface $page
	 *
	 * @param string $before_slug - page slug to insert before
	 *
	 * @return false|string
	 */
	public function register_page( Page_Interface $page, $before_slug = '' ) {
		if( !empty( $before_slug ) && $this->get_page( $before_slug ) ){
			$keys = array_keys( $this->pages );
			$values = array_values( $this->pages );
			$_key = array_search( $before_slug, $keys );
			array_splice( $keys, $_key, 0, $page->get_menu_slug() );
			array_splice( $values, $_key, 0, [$page] );
			$this->pages = array_combine( $keys, $values );
		}else {
			$this->pages[ $page->get_menu_slug() ] = $page;
		}
	}

	public function unregister_page( $slug ){
		if( did_action( 'admin_menu' ) ){
			_doing_it_wrong( __FUNCTION__, 'Method must be called before action "admin_menu" is fired by WordPress.', '2.0' );
			return;
		}

		if( array_key_exists( $slug, $this->pages ) ){
			unset( $this->pages[ $slug ] );
		}else{
			trigger_error( sprintf( 'Page with slug %s could not be found in plugin registered pages.', $slug ), E_USER_ERROR );
		}
	}

	/**
	 * Register menu pages
	 */
	public function add_menu(){
		foreach( $this->pages as $page ){
			if ( null !== $page->get_parent() || false === $page->get_parent() ) {

				if( $page->get_parent() instanceof Page_Interface ){
					$_parent = $page->get_parent()->get_menu_slug();
				}elseif ( is_string( $page->get_parent() ) ){
					$_parent = $page->get_parent();
				}else{
					$_parent = false;
				}

				$p = add_submenu_page(
					$_parent,
					$page->get_page_title(),
					$page->get_menu_title(),
					$page->get_capability(),
					$page->get_menu_slug(),
					[ $page, 'get_html' ]
				);
			} else {
				$p = add_menu_page(
					$page->get_page_title(),
					$page->get_menu_title(),
					$page->get_capability(),
					$page->get_menu_slug(),
					[ $page, 'get_html' ]
				);
			}

			add_action( 'load-' . $p, [ $page, 'on_load' ] );
			$page->set_page_id( $p );
		}
	}

}