<?php
class hap_walker extends Walker_Page {
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '';
    }
    // start element..
    
    public function start_el( &$output, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {

        $options = HAP_HIDE_ANY_PAGE::get_options();
        if ( $depth )
            $indent = str_repeat( ' &mdash; ', $depth );
        else
            $indent = '';

        $output .= '<div style="margin-bottom:10px">';
        $output .= '<span style="color:#ccc;">' . $indent . '</span>';
        $output .= '<input type="checkbox" name="hap_hideanypage_settings[hap_hideanypage][]" id="' . esc_attr( $page->ID )  . '" value="' . esc_attr( $page->ID ) . '"';
        if ( !empty( $options['hap_hideanypage'] ) && in_array( esc_attr( $page->ID ), $options['hap_hideanypage'] ) )
            $output .= ' checked="checked"';
        $output .= ' /><label for="' . esc_attr( $page->ID ) . '"><span style="font-weight:bold;">';
        $output .= apply_filters( 'the_title', esc_html( $page->post_title ), esc_attr( $page->ID ) ) . '</span></label> &middot <a href="' . get_permalink( esc_attr( $page->ID ) ) . '" target="_blank">'. __('View', 'hide-any-page') .'</a> | <a href="' . get_edit_post_link( esc_attr( $page->ID ) ) . '" target="_blank">'. __('Edit', 'hide-any-page') .'</a>';

    }
    // end element..
    public function end_el( &$output, $page, $depth = 0, $args = array() ) {
        $output .= '</div>';
    }
    // if element was a child, end level..
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '';
    }
}
