<?php

class Bnav_Register_Menu_Location {
    // Register menu location
    public function bnav_menu_location() {
        register_nav_menus( array(
            'bnav_bottom_nav' => __( 'BNAV Bottom Menu', 'wp-bnav' ),
        ));
    }

    // Add css to sub menu
    public function bnav_submenu_classnames( $classes, $args, $depth ) {
        if ( 'bnav_bottom_nav' === $args->theme_location ) {
            $default_class_name_key = array_search( 'sub-menu', $classes );
            if ( false !== $default_class_name_key ) {
                unset( $classes[ $default_class_name_key ] );
            }
            $classes[] = 'sub-menu';
            $classes[] = "depth-{$depth}";
        }

        return $classes;
    }
}