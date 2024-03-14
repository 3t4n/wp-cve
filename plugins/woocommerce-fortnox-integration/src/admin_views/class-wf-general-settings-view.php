<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\api\WF_Company_Information;
use src\fortnox\WF_Plugin;
use src\wetail\admin\WF_Admin_Settings;

class WF_General_Settings_View{

    /**
     * Adds all required setting fields for General Settings View
     */
    public static function add_settings()
    {
        $page = "fortnox";

        // General tab
        WF_Admin_Settings::add_tab([
            'page' => $page,
            'name' => "general",
            'title' => __("General", WF_Plugin::TEXTDOMAIN )
        ]);

        // API section
        WF_Admin_Settings::add_section([
            'page' => $page,
            'tab' => "general",
            'name' => "api",
            'title' => __("Integration credentials", WF_Plugin::TEXTDOMAIN ),
            'description' => __( 'Your credentials to communicate with Fortnox, <a href="https://vimeo.com/107836260" target="_blank">see instructions</a>.', WF_Plugin::TEXTDOMAIN )
        ]);

        // API key field
        WF_Admin_Settings::add_field([
            'page' => $page,
            'tab' => "general",
            'section' => "api",
            'name' => "fortnox_license_key",
            'title' => __("Wetail license key", WF_Plugin::TEXTDOMAIN ),
            'description' => __( 'Your Wetail license key that you got in the confirmation mail of your order.<br>If you haven\'t signed up yet, use <a href="https://wetail.io/service/integrationer/woocommerce-fortnox/" target="_blank">this link</a>.' , WF_Plugin::TEXTDOMAIN ),
            'after' =>
                '<a href="#" class="button fortnox-check-connection">' . __("Save and check", WF_Plugin::TEXTDOMAIN ) . '</a> ' .
                '<span class="spinner fortnox-spinner"></span><span class="alert"></span>'
        ]);

	    WF_Admin_Settings::add_field([
		    'page' => $page,
		    'tab' => "general",
		    'section' => "api",
		    'name' => "fortnox_organization_number",
		    'title' => __("Organization number", WF_Plugin::TEXTDOMAIN ),
		    'description' => __('Add your organization number here' , WF_Plugin::TEXTDOMAIN ),
		    'after' =>
			    '<a href="#" class="button fortnox-check-connection">' . __("Register", WF_Plugin::TEXTDOMAIN ) . '</a> ' .
			    '<span class="spinner fortnox-spinner"></span><span class="alert"></span>'
	    ]);

        // class-wf-products section
        WF_Admin_Settings::add_section([
            'page' => $page,
            'tab' => "general",
            'name' => "debug",
        ]);

        WF_Admin_Settings::add_field([
            'page' => $page,
            'tab' => 'general',
            'section' => 'debug',
            'type' => 'checkboxes',
            'title' => __( 'Debug', WF_Plugin::TEXTDOMAIN ),
            'options' => [
                [
                    'name' => 'fortnox_debug_log',
                    'label' => __( 'Activate logging', WF_Plugin::TEXTDOMAIN ),
                    'description' => __( 'Unnecessary logging can clog your system resources.', WF_Plugin::TEXTDOMAIN ) . ' <span class="red warning">' . __( 'Turn off when not debugging!', WF_Plugin::TEXTDOMAIN ) . '</span><br>' . __( 'The debug log can be found in <b>WooCommerce</b> -> <b>Status</b> -> <b>Logs</b>', WF_Plugin::TEXTDOMAIN )
                ]
            ]
        ]);

        $organization_number = ( $organization_number = self::get_connected_org_number() )  ? $organization_number : '<span class="red warning">' . __( 'NOT CONNECTED', WF_Plugin::TEXTDOMAIN ) . '</span>';
	    WF_Admin_Settings::add_field([
		    'page' => $page,
		    'tab' => 'general',
		    'section' => 'debug',
		    'type' => 'info',
		    'value' => $organization_number,
		    'title' => __( 'Organization registration number in Fortnox<br>(if connected)', WF_Plugin::TEXTDOMAIN ),
		    'tooltip' => __( "This is a read-only information that shows the organization registration number of the company in Fortnox that you are connected to.", WF_Plugin::TEXTDOMAIN )
	    ]);
    }

    private static function get_connected_org_number(){
        $organization_number = get_option( 'fortnox_connected_organization_number' );
        if( $organization_number ){
            return $organization_number;
        }

        $organization_number =  WF_Company_Information::get_organization_number();

        if( $organization_number ){
            update_option( 'fortnox_connected_organization_number', $organization_number );
            return $organization_number;
        }
    }
}
