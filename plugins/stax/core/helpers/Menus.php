<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Menus {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Menus
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return array
	 */
	public function getSlugs() {
		$menus = [];
		foreach ( get_terms( 'nav_menu', [ 'hide_empty' => false ] ) as $menu ) {
			$menus[] = [
				'name' => $menu->name,
				'slug' => $menu->slug
			];
		}

		return $menus;
	}

	/**
	 * @param $data
	 * @param bool $fallback
	 *
	 * @return false|string
	 */
	public function getBySlug( $data, $fallback = true ) {
		/*if ( ! in_array( $data['slug'], $this->getSlugsSimple() ) ) {
			return;
		}*/

		$menu = wp_nav_menu( [
			'menu'        => $data['slug'],
			'walker'      => new MenuWalker(),
			'depth'       => 5,
			'container'   => false,
			'menu_class'  => 'main-menu',
			'echo'        => false,
			'fallback_cb' => $fallback ? '\Stax\MenuWalker::fallback' : false,
		] );

		return $menu;
	}
}
