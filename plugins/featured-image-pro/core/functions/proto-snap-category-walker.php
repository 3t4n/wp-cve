<?php
/**
 * Proto Category Walker - Creates multi-checkbox for Categories
 *
 * @category  utility
 * @package featured-image-pro
 * @author  Adrian Jones <adrian@shooflysolutions.com>
 * @license MIT
 * @link http:://www.shooflysolutions.com
 * */
 defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once ABSPATH . 'wp-admin/includes/template.php';
if ( !class_exists( 'Proto_Snap_Category_Checklist_Widget_01' ) ):
	/**
	 * Proto_Snap_Category_Checklist_Widget_01 class.
	 *
	 * @extends Walker_Category_Checklist
	 */
	class Proto_Snap_Category_Checklist_Widget_01 extends Walker_Category_Checklist {
	private $name; //Element Name
	private $id; //Element Id
	private $widget; //Is this for a widget?
	/**
	 * __construct function.
	 *
	 * @access public
	 * @param string  $name   (default: '') - element name
	 * @param string  $id     (default: '') - element id
	 * @param boolean $widget (default: TRUE)  - true if this is a widget (not shortcode)
	 * @return void
	 */
	function __construct( $name = '', $id = '', $widget = TRUE ) {
		$this->name = $name;
		$this->id = $id;
		$this->widget = $widget;
	}
	/**
	 * start_el function.
	 *
	 * @access public
	 * @param string  &$output - multi-checkbox-element
	 * @param string  $cat     - category
	 * @param int     $depth   (default: 0) - checkbox level depth
	 * @param array   $args    (default: array()) - arguments
	 * @param int     $id      (default: 0) id
	 * @return start of element
	 */
	function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {
		extract( $args );
		if ( $this->widget )
			$name = $this->name .'[' . $cat->term_id . ']';
		else
			$name = $this->name;
		$id = 'in-' . $this->id . '-' . $cat->term_id;
		$class = '';
		if ( empty( $taxonomy ) ) $taxonomy = 'category';
		$class = in_array( $cat->term_id, $popular_cats ) ? " class='popular-category category_depth_$depth'" : " class='category_depth_$depth'";;
		$checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
		$disabled = disabled( empty( $args['disabled'] ), false, false );
		$output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>
            <label class='selectit'>
                <input value='{$cat->term_id}' type='checkbox' class='proto_snap_category cstmzr-checkbox' name='$name' id='$id'  $checked $disabled/> "   . esc_html( apply_filters( 'the_category', $cat->name ) )
			. '</label>';
	}
}
endif;