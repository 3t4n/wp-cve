<?php

namespace BDroppy\Init;

if ( ! defined( 'ABSPATH' ) ) exit;

class Constant {

    public static function defineConstant() {


        if ( ! defined( 'BDROPPY_PATH' ) ) {
            define( 'BDROPPY_PATH', trailingslashit( plugin_dir_path( dirname( dirname( __FILE__ ) ) ) ) );
        }

        if ( ! defined( 'BDROPPY_URL' ) ) {
            define( 'BDROPPY_URL', trailingslashit( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ) );
        }

        if ( ! defined( 'BDROPPY_CSS' ) ) {
            define( 'BDROPPY_CSS', trailingslashit( BDROPPY_URL ) . 'assets/css/' );
        }

        if ( ! defined( 'BDROPPY_JS' ) ) {
            define( 'BDROPPY_JS', trailingslashit( BDROPPY_URL ) . 'assets/js/' );
        }

        if ( ! defined( 'BDROPPY_IMG' ) ) {
            define( 'BDROPPY_IMG', trailingslashit( BDROPPY_URL ) . 'assets/image/' );
        }

    }
}
