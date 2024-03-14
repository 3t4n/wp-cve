<?php

class ContentAd__Includes__Admin__WP3_Menu_Fix {
	static function on_load() {
		add_filter( 'parent_file', array( __CLASS__, 'parent_file' ) );
		add_action( 'adminmenu', array( __CLASS__, 'adminmenu' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}
	static function  parent_file( $parent_file ) {
		ob_start();
		return $parent_file;
	}
	static function adminmenu() {
		$html = ob_get_clean();
		$html = preg_replace('#(\<a[^\>]*wp-has-submenu[^\>]*\>)Widgets(\<\/a\>)#', '${1}Content.Ad${2}', $html);
		echo $html;
	}
	static function admin_menu() {
		global $submenu;
		$menu_slug = 'edit.php?post_type=content_ad_widget';
		$submenu_slug = 'post-new.php?post_type=content_ad_widget';
		if ( !isset( $submenu[$menu_slug] ) ) {
			return false;
		}
		foreach ( $submenu[$menu_slug] as $i => $item ) {
			if ( $submenu_slug == $item[2] ) {
				unset( $submenu[$menu_slug][$i] );
				return $item;
			}
		}
	}
}