<?php

namespace QuadLayers\QuadMenu\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Polylang ex QuadMenu_Polylang Class
 */
class Polylang {

	private static $instance;

	public function __construct() {
		if ( ! defined( 'POLYLANG_BASENAME' ) ) {
			return;
		}
		add_action( 'init', array( $this, 'locations' ), -11 );
		add_action( 'init', array( $this, 'active' ), -9 );
	}

	function locations() {

		global $quadmenu, $quadmenu_locations, $quadmenu_active_locations;

		if ( function_exists( 'pll_languages_list' ) ) {
			foreach ( pll_languages_list() as $lang ) {

				foreach ( $quadmenu_locations as $id => $location ) {

					if ( strpos( $id, "___{$lang}" ) !== false ) {
						unset( $quadmenu_locations[ $id ] );
					}
				}
			}
		}
	}

	function active() {

		global $quadmenu, $quadmenu_locations, $quadmenu_active_locations;

		if ( function_exists( 'pll_languages_list' ) ) {

			foreach ( $quadmenu_active_locations as $id => $theme ) {

				foreach ( pll_languages_list() as $lang ) {

					if ( strpos( $id, "___{$lang}" ) === false ) {
						$quadmenu_active_locations[ "{$id}___{$lang}" ] = $quadmenu_active_locations[ $id ];
					}
				}
			}
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
