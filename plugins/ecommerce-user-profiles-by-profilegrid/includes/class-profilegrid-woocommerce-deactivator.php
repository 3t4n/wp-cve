<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Woocommerce_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
            if (class_exists('Profile_Magic') ) 
            {
                $pmrequests = new PM_request;
                $profile_tabs = $pmrequests->pm_profile_tabs();
                $dbhandler = new PM_DBhandler;

                    foreach($profile_tabs as $key=>$tab)
                    {
                       $ids = array('pg-woocommerce_purchases','pg-woocommerce_cart','pg-woocommerce_reviews');
                        if(in_array($key,$ids))
                        {
                           unset($profile_tabs[$key]);
                        }

                    }

                $dbhandler->update_global_option_value('pm_profile_tabs_order_status',$profile_tabs);
            }
	}

}
