<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

define('WP_JSSOR_SLIDER_DEBUG', false);
define('WP_JSSOR_SLIDER_DIAGNOSTIC', false);
define('WP_JSSOR_SLIDER_BUILD_ENABLED', false);

/**
 * @link   https://www.jssor.com
 * @author jssor
 */
class WP_Jssor_Slider_Condition
{
    public static function enqueue_admin_init_script()
    {
        wp_enqueue_script( 'jssor-slider-admin-init-script', WP_JSSOR_SLIDER_URL . 'interface/admin/js/wp.jssor.slider.admin.init.js', array(), WP_JSSOR_SLIDER_VERSION, false );
    }

    public static function get_push_server_scripts()
    {
        return array(
            'wp-jssor-push-server-init-script' => WP_JSSOR_SLIDER_URL.'public/script/wp.jssor.push.server.init.js'
            );
    }

    public static function get_push_server_script_paths()
    {
        return array(
            'wp-jssor-push-server-init-script' => 'public/script/wp.jssor.push.server.init.js'
            );
    }
}
