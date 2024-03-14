<?php
    
    namespace Wpo\Services;
    
    // Prevent public access to this script
    defined( 'ABSPATH' ) or die( );

    if ( !class_exists( '\Wpo\Services\Dependency_Service' ) ) {
    
        class Dependency_Service {

            private static $instance = null;

            private $dependencies = array();

            private function __construct() {
            }

            public static function get_instance() {
                
                if ( empty( self::$instance ) ) {
                    self::$instance = new Dependency_Service();
                }

                return self::$instance;
            }

            public function add( $name, $dependency ) {
                $this->dependencies[ $name ] = $dependency;
            }

            public function get( $request_id, $name ) {
                
                if ( array_key_exists( $name, $this->dependencies ) ) {
                    return $this->dependencies[ $name ];
                }

                return false;
            }

            public function remove( $request_id, $name ) {
                
                if ( array_key_exists( $name, $this->dependencies ) ) {
                    unset( $this->dependencies[ $name ] );
                }
            }
        }
    }