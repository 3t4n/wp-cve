<?php
namespace Enteraddons\Classes;
/**
 * Enteraddons helper class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
if( !defined( 'WPINC' ) ) {
    die;
}

class Cache_Manager {

	public static function init() {
		add_action( 'elementor/editor/after_save', [ __CLASS__, 'widgets_cache' ], 10, 2 );
		add_action( 'after_delete_post', [ __CLASS__, 'delete_cache' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_frontend' ] );
	}

	public static function widgets_cache( $post_id, $editor_data ) {
		$widgets = self::getWidgetsList( $editor_data );
		$Assets_Cache = new Assets_Cache( $post_id, $widgets );
		$Assets_Cache::putStyle();
	}

	public static function delete_cache( $post_id ) {
		$Assets_Cache = new Assets_Cache( $post_id );
		$Assets_Cache::deleteCacheFile();
	}

	public static function enqueue_frontend() {
		if( !\Enteraddons\Classes\Helper::is_elementor_edit_mode() ) {
			$Assets_Cache = new Assets_Cache( get_the_ID() );
			$Assets_Cache::enqueue();
		}
	}

	public static function getWidgetsList( $data ) {

		$widgets = [];
		foreach( new \RecursiveIteratorIterator( new \RecursiveArrayIterator( $data ), \RecursiveIteratorIterator::LEAVES_ONLY ) as $iteratorKey => $iteratorValue ){
			if( $iteratorKey == 'widgetType' ) {
				$widgets[] = self::purifyWidgetsName( $iteratorValue );
			}
		}
		return array_unique( $widgets );
	}

	public static function purifyWidgetsName( $string ) {
		return str_replace('enteraddons-', '', $string);
	}
} // End Class
