<?php

/*

Plugin Name: wp tarteaucitron.js self Hosted

Plugin URI: https://gitlab.com/d-public/wp-tarteaucitron.js

Description: Plugin d'intégration du service opten source Tarteaucitron.js dans Wordpress pour se conformer à la législation sur les cookies et le RGPD.

Version: 1.2.4

Author: rdorian

Author URI: https://gitlab.com/d-public/wp-tarteaucitron.js

Donate link: https://paypal.me/riccidorian/

License: MIT

*/

class tarteaucitron_plugin
{
    public function __construct()
    {
        # include necessary files
        include_once plugin_dir_path(__FILE__) . '/class.tac-frontend.php';

        include_once plugin_dir_path(__FILE__) . 'admin/class.tac-admin-scripts.php';
        include_once plugin_dir_path(__FILE__) . 'admin/class.tac-admin-services.php';
        include_once plugin_dir_path(__FILE__) . 'admin/class.tac-admin-languages.php';

        include_once  plugin_dir_path( __FILE__) . 'admin/class.tac-admin.php';


        # initiate the instances
        Tac_frontend::init();
        Tac_admin::init();
    }

}

function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=tac-admin-menu">' . __( 'Settings' ) . '</a>';
    $documentationlink = '<a href="https://tarteaucitron.ml" target="_blank">' . __( 'Documentation' ) . '</a>';
    array_unshift($links, $documentationlink);
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );

new tarteaucitron_plugin();
