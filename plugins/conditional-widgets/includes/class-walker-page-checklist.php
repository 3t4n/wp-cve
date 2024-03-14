<?php
/**
 * @author    Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2011 - 2015 Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   CETS\Conditional_Widgets
 */

/**
 * Walker class for processing the Page checklists
 */
class Conditional_Widgets_Walker_Page_Checklist extends Walker {

	var $tree_type	 = 'page';
	var $db_fields	 = array(
		'parent' => 'post_parent',
		'id'     => 'ID',
	);

	function __construct( $selected = array() ) {
		//$this->checked should be an array
		$this->checked = $selected;
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent<ul class='children'>\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$output .= "\n<li>";
		$output .= '<label class="selectit">';
		$output .= "<input value='$page->ID' type='checkbox' name='cw_selected_pages[]' " . checked( in_array( $page->ID, $this->checked ), true, false ) . " /> " . esc_html( $page->post_title ) . "</label>";
	}

	function end_el( &$output, $category, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

} // END class Conditional_Widgets_Walker_Page_Checklist