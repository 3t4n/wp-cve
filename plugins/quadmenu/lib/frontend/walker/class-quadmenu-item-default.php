<?php
namespace QuadLayers\QuadMenu\Frontend\Walker;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}
use QuadLayers\QuadMenu\Frontend\Walker\QuadMenu_Item;

/**
 * QuadMenuItemDefault Class
 */
class QuadMenu_Item_Default extends QuadMenu_Item {

	protected $type = 'default';

	function get_start_el() {

		$item_output = '';

		$this->add_item_classes();

		$this->add_item_classes_prefix();

		$this->add_item_classes_current();

		$this->add_item_classes_quadmenu();

		$id = $this->get_item_id();

		$class = $this->get_item_classes();

		$item_output .= '<li' . $id . $class . '>';

		$this->add_link_atts();

		$this->add_link_atts_toggle();

		$item_output .= $this->get_link();

		return $item_output;
	}

}
