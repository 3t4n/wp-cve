<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-walker.php - IntelliWidget Walker Class
 * based in part on code from Wordpress core post-template.php
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
class IntelliWidgetWalker extends Walker {
	/**
	 * @see Walker::$tree_type
	 */
	var $tree_type = 'page';

	/**
	 * @see Walker::$db_fields
	 * @var array
	 */
	var $db_fields = array ( 'parent' => 'post_parent', 'id' => 'ID' );

	/**
	 * @see Walker::start_el()
	 */
	function start_el( &$output, $page, $depth = 0, $args = array(), $id = 0 ) {
        if ( isset( $args[ 'profiles_only' ] ) && $args[ 'profiles_only' ] && empty( $page->has_profile ) ) return;
		$pad = str_repeat( '-&nbsp;', $depth );

		$output .= "\t<option class=\"level-$depth\" value=\"$page->ID\"";
		if ( in_array( $page->ID, $args[ 'page' ] ) )
			$output .= ' selected="selected"';
		$output .= '>';
		$title = substr( $pad . $page->post_title, 0, 60 ) . ' (' . ucwords( str_replace( '_', ' ', $page->post_type ) ) . ')';
		$output .= esc_html( $title );
		$output .= "</option>\n";
	}
}
