<?php


namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;


class WF_Bulk_Settings_View
{
    /**
     * Adds all required setting fields for Bulk Settings View
     */
    public static function add_settings()
    {
        $page = "fortnox";

        WF_Admin_Settings::add_tab( [
            'page' => $page,
            'name' => "bulk-actions",
            'title' => __( "Bulk actions", WF_Plugin::TEXTDOMAIN ),
        ] );

        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "bulk-actions",
            'name' => "bulk-actions",
            'title' => __( "Bulk actions", WF_Plugin::TEXTDOMAIN ),
            'description' => __( "Useful bulk actions to perform retroactively between WooCommerce and Fortnox.", WF_Plugin::TEXTDOMAIN )
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "bulk-actions",
            'section' => "bulk-actions",
            'title' => __( "Sync products", WF_Plugin::TEXTDOMAIN ),
            'type' => "button",
            'button' => [
                'text' => __( "Sync products", WF_Plugin::TEXTDOMAIN ),
            ],
            'data' => [
                [
                    'key' => "fortnox-bulk-action",
                    'value' => "fortnox_sync_products"
                ],
                [
                    'key' => "modal",
                    'value' => true
                ]
            ],
            'description' => __( "Upload all products from WooCommerce to Fortnox. It may take a while to synchronise large shops.", WF_Plugin::TEXTDOMAIN )
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "bulk-actions",
            'section' => "bulk-actions",
            'title' => __( "Sync orders", WF_Plugin::TEXTDOMAIN ),
            'type' => "button",
            'button' => [
                'text' => __( "Sync orders", WF_Plugin::TEXTDOMAIN ),
            ],
            'data' => [
                [
                    'key' => "fortnox-bulk-action",
                    'value' => "fortnox_sync_orders_date_range"
                ],
                [
                    'key' => "modal",
                    'value' => true
                ]
            ],
            'description' => __( 'Sync orders placed in date range.', WF_Plugin::TEXTDOMAIN )
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "bulk-actions",
            'section' => "bulk-actions",
            'title' => __( "Fetch settings", WF_Plugin::TEXTDOMAIN ),
            'type' => "button",
            'button' => [
                'text' => __( "Fetch settings", WF_Plugin::TEXTDOMAIN ),
            ],
            'data' => [
                [
                    'key' => "fortnox-bulk-action",
                    'value' => "fortnox_get_settings"
                ]
            ],
            'description' => __( 'Get settings from Fortnox.', WF_Plugin::TEXTDOMAIN )
        ] );
    }
}
