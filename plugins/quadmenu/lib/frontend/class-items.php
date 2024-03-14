<?php
namespace QuadLayers\QuadMenu\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

/**
 * Items Class ex QuadMenu_Items
 */
class Items {

	public static $instance;

	function __construct() {

		add_filter( 'quadmenu_item_object_class', array( $this, 'item_object_class' ), 10, 4 );

		require_once QUADMENU_PLUGIN_DIR . 'lib/frontend/walker/class-quadmenu-walker.php';
	}

	function item_object_class( $class, $item, $id, $auto_child = '' ) {

		if ( ! isset( $item->quadmenu ) ) {
			return $class;
		}

		switch ( $item->quadmenu ) {

			case 'mega':
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Mega';
				break;

			case 'column';
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Column';
			break;

			case 'widget';
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Widget';
			break;

			case 'icon';
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Icon';
			break;

			case 'search';
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Search';
			break;

			case 'cart';
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Cart';
			break;

			case 'post_type';
				$class = '\\QuadLayers\\QuadMenu\\Frontend\\Walker\\QuadMenu_Item_Post_Type';
			break;
		}

		return $class;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

// new QuadMenu_Items();
