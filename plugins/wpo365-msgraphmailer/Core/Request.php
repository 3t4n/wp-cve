<?php

    namespace Wpo\Core;
    
    // Prevent public access to this script
    defined( 'ABSPATH' ) or die();

    if ( !class_exists( '\Wpo\Core\Request' ) ) {

        class Request {

            private $id;

            private $storage = array();

            public function __construct( $id ) {
                $this->id = $id;
            }

            public function current_request_id() {
                return $this->id;
            }

            public function set_item( $key, $value ) {

                if ( !is_string( $key ) ) {
                    return false;
                }

                if ( empty( $key ) ) {
                    return false;
                }

                $this->storage[ $key ] = $value;

                return true;
            }

            public function get_item( $key ) {

                if ( !is_string( $key ) || empty( $key ) ) {
                    return false;
                }

                if ( array_key_exists( $key, $this->storage ) ) {
                    return $this->storage[ $key ];
                }

                return false;
            }

            public function remove_item( $key ) {

                if ( !is_string( $key ) || empty( $key ) ) {
                    return false;
                }

                if ( array_key_exists( $key, $this->storage ) ) {
                    unset( $this->storage[ $key ] );
                    return true;
                }

                return false;
            }

            public function clear() {
                $this->storage = array();
            }
        }
    } 
