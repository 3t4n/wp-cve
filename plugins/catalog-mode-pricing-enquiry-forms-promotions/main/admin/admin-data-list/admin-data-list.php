<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Data_List' ) ) {

    require_once (dirname( __FILE__ ) . '/admin-data-list-mbx-product-variations.php');
    require_once (dirname( __FILE__ ) . '/admin-data-list-label-designs.php');
    require_once (dirname( __FILE__ ) . '/admin-data-list-textblock-designs.php');
    require_once (dirname( __FILE__ ) . '/admin-data-list-view-locations.php');
    
    class WModes_Admin_Data_List {

        public static function init() {
            add_filter( 'reon/get-data-list', array( new self(), 'get_data_list' ), 10, 2 );
        }

        public static function get_data_list( $result, $data_args ) {

            $db_source = explode( ':', $data_args[ 'source' ] );
            $data_args[ 'source' ] = '';
            if ( count( $db_source ) >= 2 && $db_source[ 0 ] == 'wmodes' ) {
                if ( count( $db_source ) > 2 ) {
                    $n_src = array();
                    for ( $i = 2; $i < count( $db_source ); $i++ ) {
                        $n_src[] = $db_source[ $i ];
                    }
                    $data_args[ 'source' ] = implode( ':', $n_src );
                }

                return apply_filters( 'wmodes-admin/get-data-list-' . $db_source[ 1 ], $result, $data_args );
            }


            return $result;
        }

    }

    WModes_Admin_Data_List::init();
}