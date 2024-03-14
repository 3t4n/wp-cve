<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Data_List_View_Locations' ) ) {

    class WModes_Admin_Data_List_View_Locations {

        public static function init() {

            add_filter( 'wmodes-admin/get-data-list-view_locations', array( new self(), 'get_data_list' ), 10, 2 );
        }

        public static function get_data_list( $result, $data_args ) {

            $source = $data_args[ 'source' ];

            $view_positions = WModes_Views::get_view_locations();

            if ( !isset( $view_positions[ $source ] ) ) {

                return $result;
            }

            foreach ( $view_positions[ $source ] as $key => $view_position ) {

                if ( !isset( $view_position[ 'show_in_admin' ] ) || !$view_position[ 'show_in_admin' ] ) {
                    
                    continue;
                }

                $title = $key;

                if ( isset( $view_position[ 'title' ] ) ) {

                    $title = $view_position[ 'title' ];
                }

                $result[ $key ] = $title;
            }

            return $result;
        }

    }

    WModes_Admin_Data_List_View_Locations::init();
}