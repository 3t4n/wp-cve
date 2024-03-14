<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-section.php
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
 
class IntelliWidgetSection {

    var $post_id;
    var $box_id;
    var $title;
    
    function __construct( $post_id, $box_id ) {
        $this->post_id  = $post_id;
        $this->box_id   = $box_id;
    }
       
    function get_field_id( $field_name ) {
        return 'intelliwidget_' . $this->post_id . '_' . $this->box_id . '_' . $field_name;
    }
    
    function get_field_name( $field_name ) {
        return 'intelliwidget_' . $this->box_id . '_' . $field_name;
    }

    function set_title( $title ) {
        $this->title = $title;
    }
    function get_tab() {
        return apply_filters( 'intelliwidget_tab', '<li id="iw_tab_' . $this->post_id . '_' . $this->box_id . '" class="iw-tab">
        <a href="#iw_tabbed_section_' . $this->post_id . '_' . $this->box_id . '" title="' . esc_attr( $this->title ) . '">' . $this->box_id . '</a></li>', $this->post_id, $this->box_id );
    }

    function begin_section() {
        return apply_filters( 'intelliwidget_begin_section', '<div id="iw_tabbed_section_' . $this->post_id . '_' . $this->box_id . '" class="iw-tabbed-section">' );
    }
    
    function end_section() {
        return apply_filters( 'intelliwidget_end_section', '</div>' );
    }
    
    
}

