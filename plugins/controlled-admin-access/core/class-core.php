<?php

namespace WPRuby_CAA\Core;

use WPRuby_CAA\Core\App\Backend\Endpoints\Endpoints_Factory;
use WPRuby_CAA\Core\Features\Login_Controller;
use WPRuby_CAA\Core\Features\Menu_Blocker;
use WPRuby_CAA\Core\Features\Menu_Filter;
use WPRuby_CAA\Core\Features\Utilities_Filter;
use WPRuby_CAA\Core\Migrations\Migrator;
use WPRuby_CAA\Core\Dto\Menu_Item;
use WPRuby_CAA\Core\App\Frontend\App_Page;

class Core {

	protected static $_instance = null;

	public static function get_instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->load_actions();

		Login_Controller::boot();
		Endpoints_Factory::boot();
		Menu_Filter::boot();
		Menu_Blocker::boot();
		Utilities_Filter::boot();
		App_Page::get_instance();
	}

	private function load_actions() {

		add_action( 'admin_init', [ $this, 'process_migrations' ] );
		add_action( 'admin_init', [ $this, 'store_admin_menu' ] );
	}

	public function process_migrations()
	{
		Migrator::boot();
	}

	public function store_admin_menu() {
		global $submenu, $menu;
		$menu_items = [];

		if (!$menu) {
			return;
		}

		foreach ( $menu as $key => $item ) {
			if ( isset( $item[4] ) && strpos( $item[4], 'wp-menu-separator' ) === 0 ) {
				continue;
			}

			$menuObject = Menu_Item::make( $item[5], $item[0], $item[2] );

			if ( $menuObject->shouldBeIgnored() ) {
				continue;
			}

			if ( isset( $submenu[ $item[2] ] ) ) {
				foreach ( $submenu[ $item[2] ] as $sub_item ) {
					$menuObject->addSubItem( Menu_Item::make( $sub_item[2], $sub_item[0], $sub_item[2] ) );
				}
			}

			$menu_items[] = $menuObject->toArray();
		}

		if ( count( $menu_items ) > 0 ) {
			update_option( Constants::CAA_ALL_MENU_SLUGS, $menu_items );
		}

	}

}
