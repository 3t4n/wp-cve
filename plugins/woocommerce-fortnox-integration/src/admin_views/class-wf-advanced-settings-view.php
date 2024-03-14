<?php


namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\api\WF_Auth;
use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;


class WF_Advanced_Settings_View
{

    /**
     * Adds all required setting fields for Advanced View
     */
	public static function add_settings()
	{
		$page = "fortnox";

		WF_Admin_Settings::add_tab( [
			'page' => $page,
			'name' => "advanced",
			'title' => __( "Advanced", WF_Plugin::TEXTDOMAIN ),
		] );

		WF_Admin_Settings::add_section( [
			'page' => $page,
			'tab' => "advanced",
			'name' => "advanced",
			'title' => __( "Advanced settings", WF_Plugin::TEXTDOMAIN ),
		] );

		WF_Admin_Settings::add_field( [
			'page' => $page,
			'tab' => "advanced",
			'section' => "advanced",
			'title' => __( "Flush access token", WF_Plugin::TEXTDOMAIN ),
			'type' => "button",
			'button' => [
				'text' => __( "Flush access token", WF_Plugin::TEXTDOMAIN ),
			],
			'data' => [
				[
					'key' => "fortnox-bulk-action",
					'value' => "fortnox_flush_access_token"
				]
			],
			'description' => sprintf( __( 'Delete Fortnox access token. <a href="%s" target="_blank">Read more</a>', WF_Plugin::TEXTDOMAIN ), 'https://docs.wetail.io/woocommerce/fortnox-integration/skapa-nya-autentiseringsnycklar-accesstoken-logga-in-igen-till-fortnox/' )
		] );

		WF_Admin_Settings::add_field([
			'page' => $page,
			'tab' => 'advanced',
			'section' => 'advanced',
			'type' => 'info',
			'value' => __( 'This plugin has a bunch of actions and filters that developers can use to easily add/change data in the different stages of the flow.', WF_Plugin::TEXTDOMAIN ),
			'description' => __( 'For an updated listing, please refer to <a href="https://docs.wetail.io/woocommerce/fortnox-integration/advanced-actions-and-filters/" target="_blank">our docs</a>.', WF_Plugin::TEXTDOMAIN ),
			'title' => __( 'Hooks to use', WF_Plugin::TEXTDOMAIN ),
		]);

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "advanced",
            'section' => "advanced",
            'name' => "fortnox_access_token_oauth2",
            'title' => __( "Oauth Access Token", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "Access Token.", WF_Plugin::TEXTDOMAIN ),
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "advanced",
            'section' => "advanced",
            'name' => "fortnox_refresh_token",
            'title' => __( "Refresh Token", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "Refresh Token.", WF_Plugin::TEXTDOMAIN ),
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "advanced",
            'section' => "advanced",
            'name' => "fortnox_access_token_expiry_time",
            'title' => __( "Access Token Expiry time", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "Access Token Expiry time.", WF_Plugin::TEXTDOMAIN ),
        ] );

        // API key field
        WF_Admin_Settings::add_field([
            'page' => $page,
            'tab' => "advanced",
            'section' => "advanced",
            'name' => "fortnox_client_id",
            'title' => __("Fortnox Client ID", WF_Plugin::TEXTDOMAIN ),
            'description' => __( 'The Client ID used, do not edit if you do not have a multishop integration' , WF_Plugin::TEXTDOMAIN ),
            'type' => "dropdown",
            'options' => self::get_client_ids()
        ]);
	}

    public static function get_client_ids(){
        $func = function ( $item ) {

            return [
                "value" => $item,
                "label" => $item
            ];
        };
        $client_ids = array_map( $func, WF_Auth::CLIENT_IDS );
        array_unshift( $client_ids, [
            "value" => "",
            "label" => __( "Please select...", WF_Plugin::TEXTDOMAIN )
        ]);

        return $client_ids;
    }
}
