<?php
namespace QuadLayers\QuadMenu\Frontend\Walker;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}
use QuadLayers\QuadMenu\Frontend\Walker\QuadMenu_Item_Default;

/**
 * QuadMenuItemIcon Class
 */
class QuadMenu_Item_Icon extends QuadMenu_Item_Default {

	protected $type = 'icon';

	function init() {
		$this->args->has_caret = false;
		// $this->args->has_dropdown = $this->has_children = true;
		$this->args->has_title = $this->args->link_before = $this->args->link_after = false;
	}

	function get_title() {
		ob_start();
		?>
		<span class="quadmenu-text"></span>
		<?php

		return ob_get_clean();
	}

}
