<?php
namespace CoderExpert\Corona;

class Helper {
    /**
     * Get installed WordPress Plugin List
     * @return void
     */
    public static function get_plugins(){
        if( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return get_plugins();
    }
    /**
     * Get views for front-end display
     *
     * @param string $name  it will be file name only from the views folder.
     * @param [type] $data
     * @return void
     */
    public static function views( $name, $data = null ){
        $helper = self::class;
        $file = CE_CORONA_PATH . 'classes/admin/views/' . $name;
        if( \is_readable( $file ) ) {
            include_once $file;
        }
    }
}