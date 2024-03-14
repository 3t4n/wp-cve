<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Catalog_Mode' ) ) {

    class WModes_Catalog_Mode {

        private $mode_engine;

        public function __construct() {

            $this->mode_engine = new WModes_Catalog_Mode_Engine();

            add_filter( 'wmodes/process-data', array( $this->mode_engine, 'process_data' ), 40, 2 );
            add_filter( 'wmodes/process-product-data', array( $this->mode_engine, 'process_product_data' ), 40, 1 );

            add_filter( 'wmodes/process-product-hash', array( $this->mode_engine, 'process_product_hash' ), 40, 2 );
        }

        public function get_product_modes( $product_data, $data ) {

            if ( !isset( $data[ 'modes' ] ) ) {

                return $product_data;
            }

            $merged_modes = array();

            foreach ( $data[ 'modes' ] as $key => $modes ) {

                $merged_modes = $this->merge_modes( $merged_modes, $modes );
            }

            $product_data[ 'modes' ] = $merged_modes;

            return $product_data;
        }

        public function get_site_modes( $site_data, $data ) {

            if ( !isset( $data[ 'modes' ] ) ) {

                return $site_data;
            }

            $merged_modes = array();

            foreach ( $data[ 'modes' ] as $key => $modes ) {

                $merged_modes = $this->merge_modes( $merged_modes, $modes );
            }

            $site_data[ 'modes' ] = $merged_modes;

            return $site_data;
        }

        private function merge_modes( $merged_modes, $modes ) {

            foreach ( $modes as $key => $mode ) {

                if ( !isset( $mode[ 'mode_type' ] ) ) {

                    continue;
                }

                $mode_type = $mode[ 'mode_type' ];

                if ( !isset( $mode[ $mode_type ] ) ) {

                    continue;
                }

                if ( !count( $mode[ $mode_type ] ) ) {

                    continue;
                }

                $merged_modes[ $mode_type ] = $this->map_keys( $key, $mode[ $mode_type ] );
            }

            return $merged_modes;
        }

        private function map_keys( $mode_id, $mode ) {

            $opt = array(
                'id' => $mode_id
            );

            foreach ( $mode as $key => $value ) {

                $opt[ $key ] = $value;
            }

            return $opt;
        }

    }

}