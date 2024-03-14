<?php

/**
 * @copyright (c) 2020.
 * @author            Alan Fuller (support@fullworks)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace Quick_Event_Manager\Plugin\Control;

use  Quick_Event_Manager\Plugin\Business\Display_Eventbrite_Integration ;
use  Quick_Event_Manager\Plugin\UI\Admin\Admin ;
use  Quick_Event_Manager\Plugin\UI\Admin\Admin_Attendees ;
use  Quick_Event_Manager\Plugin\UI\Admin\Admin_Reports ;
use  Quick_Event_Manager\Plugin\UI\Admin\Admin_Settings ;
use  Quick_Event_Manager\Plugin\UI\User\FrontEnd ;
use  Quick_Event_Manager\Plugin\Business\Business ;
class Plugin
{
    private  $plugin_name ;
    private  $version ;
    /**
     * @param \Freemius $freemius Object for freemius.
     */
    private  $freemius ;
    public function __construct( $plugin_name, $version, $freemius )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->freemius = $freemius;
    }
    
    public function run()
    {
        require_once QUICK_EVENT_MANAGER_PLUGIN_DIR . 'legacy/quick-event-manager.php';
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_admin_attendees_hooks();
        $this->define_business_hooks();
        $this->define_integration_hooks();
        $this->define_public_hooks();
        $this->version_upgrades();
    }
    
    private function version_upgrades()
    {
        $version = get_option( 'qem_version' );
        update_option( 'qem_version', $this->version );
    }
    
    private function set_locale()
    {
        add_action( 'init', function () {
            load_plugin_textdomain( $this->plugin_name, false, basename( QUICK_EVENT_MANAGER_PLUGIN_DIR ) . '/languages/' );
        } );
    }
    
    private function define_admin_hooks()
    {
        $admin = new Admin( $this->plugin_name, $this->version, $this->freemius );
        $admin->hooks();
    }
    
    private function define_admin_attendees_hooks()
    {
        $admin_attendees = new Admin_Attendees( $this->plugin_name, $this->version, $this->freemius );
        $admin_attendees->hooks();
    }
    
    private function define_public_hooks()
    {
        $public = new FrontEnd( $this->plugin_name, $this->version, $this->freemius );
        $public->hooks();
    }
    
    private function define_business_hooks()
    {
        $business = new Business( $this->plugin_name, $this->version, $this->freemius );
        $business->hooks();
    }
    
    private function define_integration_hooks()
    {
    }

}