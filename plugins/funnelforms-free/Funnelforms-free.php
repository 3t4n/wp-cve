<?php

/*
Plugin Name: Funnelforms Free
Description: Create innovative forms and optimize your lead generation, recruiting, pricing calculation, appointment booking or product configuration! Get more ready-to-buy customers, acquire the right staff and digitize your business processes with intuitive and smart multi-step forms! Create high-quality forms with just a few clicks using drag & drop, without touching a line of code and lead your website visitors intuitively through a sales-boosting form funnel.
Author: Funnelforms
Author URI: https://funnelforms.io/
Author E-Mail: support@funnelforms.io
Text Domain: funnelforms-free
Domain Path: /languages/
Version: 3.7.2
*/


/*
 * Developed By:
 * CodeRevolution - https://coderevolution.de/
*/



if ( ! function_exists( 'ff_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ff_fs() {
        global $ff_fs;

        if ( ! isset( $ff_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $ff_fs = fs_dynamic_init( array(
                'id'                  => '10552',
                'slug'                => 'funnelforms-free',
                'type'                => 'plugin',
                'public_key'          => 'pk_021a19563c7f53f2eecb2b3fdb078',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'admin.php?page=af2_checklist',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $ff_fs;
    }

    // Init Freemius.
    ff_fs();
    // Signal that SDK was initiated.
    do_action( 'ff_fs_loaded' );
}

// Throw out if unallowed access
defined( 'ABSPATH' ) or die( 'NO!' );

if (!get_option('af2_free_version')) {
    add_option('af2_free_version', '0');
}

// Check if free version is active
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'Anfrageformular/Anfrageformular.php' ) ) {
    //die(__('You have installed an outdated version of the Funnelforms plugin! Please deactivate the free version first before you activate the Funnelforms Pro version!', 'funnelforms-free'));
}
if ( is_plugin_active( 'Funnelforms-pro/Funnelforms-pro.php' ) ) {
    update_option('af2_free_version', '0');

}
else {
    update_option('af2_free_version', '1');

    // Plugin DIR constants
    define( 'AF2F_PLUGIN', __FILE__ );
    define( 'AF2F_PLUGIN_DIR', untrailingslashit( dirname( AF2F_PLUGIN ) ) );
    define( 'AF2F_LANGUAGES_PATH', dirname(plugin_basename(AF2F_PLUGIN)).'/languages/' );


    // Include Constants
    require_once AF2F_PLUGIN_DIR.'/misc/constants.php';

    // Include ML
    require_once FNSF_AF2_MULTILANGUAGE_HANDLER_PATH;

    // Include WP Options
    require_once FNSF_AF2_WP_OPTIONS_PATH;

    // Include resource handler
    require_once FNSF_AF2_RESOURCE_HANDLER_PATH;

    // Include Admin handler
    require_once FNSF_AF2_ADMIN_HANDLER_PATH;

    // Include version migration
    require_once FNSF_AF2_VERSION_MIGRATION_PATH;

    // Include healthchecks
    require_once FNSF_AF2_HEALTHCHECK_PATH;

    // Frontend path
    require_once FNSF_AF2_FRONTEND_PATH;
}

