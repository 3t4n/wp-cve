<?php
class AREOI_HAF_Walker_Nav_Menu_Primary extends Walker_Nav_Menu 
{
    public function start_lvl( &$output, $depth = 0, $args = null ) 
    {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
 
        $classes = array( 'dropdown-menu dropdown-menu-end' );

        $class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
 
        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) 
    {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent  = str_repeat( $t, $depth );
        $output .= "$indent</ul>{$n}";
    }
 
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) 
    {
        $menu_item = $data_object;
 
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
 
        $classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
        $classes[] = 'menu-item-' . $menu_item->ID;

        $args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

        $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
        $class_names .= ( $args->walker->has_children ) ? ' dropdown' : '';
        $class_names .= ( $depth > 0 ) ? ' dropdown-item' : '';
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
 
        $output .= $indent . '<li' . $id . $class_names . '>';
 
        $atts           = array();
        $atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
        $atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
        if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $menu_item->xfn;
        }
        $atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
        $atts['aria-current'] = $menu_item->current ? 'page' : '';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );
 
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // $attributes .= ( $args->walker->has_children ) ? ' data-bs-toggle="dropdown" aria-expanded="false"' : '';

        $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

        $title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );
        
        $dropdown = ( $args->walker->has_children ) ? ' <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>' : '';

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $dropdown;
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) 
    {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }
 
}