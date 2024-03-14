<?php
/**
 * Plugin Name:       BDroppy
 * Plugin URI:        https://wordpress.org/plugins/bdroppy/
 * Description:       BDroppy Plugin
 * Version:           2.7.41
 * Author:            BDroppy Development Team
 * Author URI:        http://bdroppy.com
 * License:           MIT
 *
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ ."/src/Init/Functions.php";
require_once __DIR__.'/vendor/autoload.php';

use BDroppy\Init\Activator;
use BDroppy\Init\Core;
use BDroppy\Init\DeActivator;


if ( ! class_exists( 'BDroppyMain' ) ) :

class BDroppyMain {

    private static $instance;

    private function __construct()
    {
        define( 'BDROPPY_NAME', 'BDroppy' );
        define( 'BDROPPY_VERSION', '2.7.40' );
        define( 'BDROPPY_DB_VERSION', 16 );


        register_activation_hook(
            __FILE__,
            array( $this, 'activate' )
        );

        register_deactivation_hook(
            __FILE__,
            array( $this, 'deactivate' )
        );

        register_uninstall_hook(
            __FILE__,
            array( self::class, 'uninstall' )
        );

        self::BDroppy();
    }


    public static function BDroppy()
    {
        $plugin = new Core();
        $plugin->run();

    }


    public static function instance() {
        if ( is_null( ( self::$instance ) ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public static function uninstall() {
//        Uninstall::uninstall();
    }


    public function activate()
    {
         Activator::activate();
    }


    public function deactivate() {
       DeActivator::deActivate();
    }
}

endif;
BDroppyMain::instance();

