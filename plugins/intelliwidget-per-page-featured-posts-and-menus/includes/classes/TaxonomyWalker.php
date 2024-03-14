<?php
/**
 * IntelliWidgetTaxonomyWalker class.
 *
 * @extends Walker
 * @class   IntelliWidgetTaxonomyWalker
 * @package IntelliWidget
 * @author  Lilaea Media
 * portions adapted from Product Category List Walker Copyright 2011 WooThemes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class IntelliWidgetTaxonomyWalker extends Walker {

    var $tree_type = 'category';
    var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id' );

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "<ul class='intelliwidget-taxonomy-children children'>\n";
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "</ul>\n";
    }

    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $output .= '<li class="intelliwidget-term intelliwidget-term-' . $object->term_id;

        if ( $args[ 'current_term_id' ] == $object->term_id )
            $output .= ' intelliwidget-current-term';

        elseif ( $args[ 'current_ancestors' ] && $args[ 'current_term_id' ] && $object->term_id == current( $args[ 'current_ancestors' ] ) )
            $output .= ' intelliwidget-current-term-parent';

        elseif ( $args[ 'current_ancestors' ] && $args[ 'current_term_id' ] && in_array( $object->term_id, $args[ 'current_ancestors' ] ) )
            $output .= ' intelliwidget-current-term-ancestor';

        $output .=  '"><a href="' . get_term_link( ( int ) $object->term_id, $object->taxonomy ) . '">' . __( $object->name ) . '</a>';
        if ( $args[ 'show_count' ] )
            $output .= ' <span class="count">(' . $object->count . ')</span>';

        if ( $args[ 'show_descr' ] && strlen( $object->description ) )
            $output .= ' <span class="intelliwidget-term-description">' . $object->description . '</span>';
    }

    function end_el( &$output, $object, $depth = 0, $args = array() ) {

        $output .= "</li>\n";

    }

    function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

        if ( !$element )
            return;

        $id_field = $this->db_fields[ 'id' ];
        $display = (
            !$args[ 0 ][ 'current_only' ] ||
            1 == $args[ 0 ][ 'current_only' ] ||
            (
                2 == $args[ 0 ][ 'current_only' ] && 
                (
                    $args[ 0 ][ 'current_term_id' ] == $element->parent
                    //|| $args[ 0 ][ 'current_term_id' ] == $element->$id_field
                    //|| in_array( $element->parent, $args[ 0 ][ 'current_ancestors' ] ) 
                )
            )
        );
        if ( ! $args[ 0 ][ 'current_only' ] // show all terms
                || ( ( $args[ 0 ][ 'current_only' ] )
                && ( $element->parent == 0 
                || $args[ 0 ][ 'current_term_id' ] == $element->parent 
                || in_array( $element->parent, $args[ 0 ][ 'current_ancestors' ] ) 
                ) ) // show only current term
            ):
            if ( $display ):             
                if ( is_array( $args[ 0 ] ) )
                    $args[ 0 ][ 'has_children' ] = ! empty( $children_elements[ $element->$id_field ] );
                $cb_args = array_merge( array( &$output, $element, $depth ), $args );
                call_user_func_array( array( &$this, 'start_el' ), $cb_args );
            endif;
            $id = $element->$id_field;
            if ( ( $max_depth == 0 || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {
                foreach( $children_elements[ $id ] as $child ){
                    if ( $display && !isset( $newlevel ) ) {
                        $newlevel = TRUE;
                        $cb_args = array_merge( array( &$output, $depth ), $args );
                        call_user_func_array( array( &$this, 'start_lvl' ), $cb_args );
                    }
                    $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
                }
                unset( $children_elements[ $id ] );
            }

            if ( $display && isset( $newlevel ) && $newlevel ){
                $cb_args = array_merge( array( &$output, $depth ), $args );
                call_user_func_array( array( &$this, 'end_lvl' ), $cb_args );
            }
            if ( $display ):
                $cb_args = array_merge( array( &$output, $element, $depth ), $args );
                call_user_func_array( array( &$this, 'end_el' ), $cb_args );
            endif;
        endif;
    }

}