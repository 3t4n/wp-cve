<?php

/**
 * Fired during plugin activation
 *
 * @link       https://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.1
 * @package    AdUnblocker
 * @subpackage AdUnblocker/includes
 * @author     Digital Apps <support@digitalapps.com>
 */
class AdUnblocker_Activator {

    public static function activate() {
        
        $defaults = AdUnblocker::get_defaults();

        add_option( 
            'adunblocker-options', 
            $defaults 
        );

        add_option( 
            'adunblocker-install-date', 
            date( 'Y-m-d h:i:s' ) 
        );
        
        add_option( 
            'adunblocker-review-notify', 
            'no' 
        );

        set_transient( 'daau-activation-admin-notice', true, 5 );

    }

}