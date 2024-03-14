<?php

namespace QuadLayers\QuadMenu;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}
/**
 * Locations Class ex QuadMenu_Locations
 */
class Locations {

	private static $instance;

	public function __construct() {

		$this->locations();

		$this->dev();

		add_action( 'init', array( $this, 'active' ), -10 );

		add_action( 'admin_init', array( $this, 'save' ), 999 );
	}

	function dev() {

		register_nav_menus(
			array(
				'quadmenu_dev' => 'QuadMenu Dev',
			)
		);

		unset( $GLOBALS['quadmenu_locations']['quadmenu_dev'] );
	}

	function locations() {

		global $quadmenu_locations;

		$quadmenu_locations = get_option( QUADMENU_DB_LOCATIONS, array() );
	}

	function active() {

		global $quadmenu, $quadmenu_locations, $quadmenu_active_locations;

		$quadmenu_active_locations = array( 'quadmenu_dev' => true );

		if ( ! empty( $quadmenu ) && is_array( $quadmenu_locations ) && count( $quadmenu_locations ) ) {

			foreach ( $quadmenu_locations as $id => $location ) {
				if ( ! empty( $quadmenu[ $id . '_integration' ] ) && ! empty( $quadmenu[ $id . '_theme' ] ) ) {
					$quadmenu_active_locations[ $id ] = $quadmenu[ $id . '_theme' ];
				}
			}
		}
	}

	public function save() {

		global $_wp_registered_nav_menus, $quadmenu, $quadmenu_locations;

		if ( ! empty( $quadmenu ) && is_array( $_wp_registered_nav_menus ) && count( $_wp_registered_nav_menus ) ) {

			$quadmenu_locations = array();

			foreach ( $_wp_registered_nav_menus as $location => $name ) {

				$quadmenu_locations[ $location ] = array(
					'name' => $name,
				);
			}

			update_option( QUADMENU_DB_LOCATIONS, $quadmenu_locations );
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

