<?php 

// This is required to be sure Walker_Category_Checklist class is available
require_once ABSPATH . 'wp-admin/includes/template.php';
/**
 * Custom walker to print category checkboxes for widget forms
 */
class Walker_Category_Checklist_Widget extends Walker_Category_Checklist {

	private $name;
	private $id;
	
	/*
	 * This function is used to initialize the variables
	 */
	function __construct( $name = '', $id = '' ){
		$this->name = $name;
		$this->id = $id;
	}

	/*
	 * This function is used to list the Categories and according to the checkbox save the state of the checked box
	 */
	function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {
		extract($args);
		if ( empty($taxonomy) ) $taxonomy = 'category';
		$class = in_array( $cat->term_id, $popular_cats ) ? ' class="popular-category"' : '';
		$id = $this->id . '-' . $cat->term_id;
		$checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
		if($cat->count != 0)
		$output .= "<li id='{$taxonomy}-{$cat->term_id}'$class>"
		. '<label class="selectit"><input value="'
        . $cat->term_id . '" type="checkbox" name="' . $this->name
        . '[]" id="in-'. $id . '"' . $checked
        . disabled( empty( $args['disabled'] ), false, false ) . ' /> '
        . esc_html( apply_filters('the_category', $cat->name ))
      	. '</li></label>';
	}
}
?>