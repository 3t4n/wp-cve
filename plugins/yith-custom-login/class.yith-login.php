<?php
/**
 * Main class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Custom Login
 * @version 1.0.2
 */

if ( !defined( 'YITH_LOGIN' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Login' ) ) {
    /**
     * YITH Custom Login
     *
     * @since 0.9.0
     */
    class YITH_Login {
        /**
         * Plugin version
         *
         * @var string
         * @since 0.9.0
         */
        public $version = '1.2.0';

        /**
         * Plugin object
         *
         * @var string
         * @since 0.9.0
         */
        public $obj = null;

        /**
         * Constructor
         *
         * @return mixed|YITH_Login_Admin|YITH_Login_Frontend
         * @since 0.9.0
         */
        public function __construct() {
            if( is_admin() ) {
                $this->obj = new YITH_Login_Admin( $this->version );
            } else {
                $this->obj = new YITH_Login_Frontend( $this->version );
            }

            return $this->obj;
        }
    }
}