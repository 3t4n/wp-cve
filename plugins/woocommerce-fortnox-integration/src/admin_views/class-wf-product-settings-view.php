<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;


class WF_Product_Settings_View{

    /**
     * Adds all required setting fields for Product Settings View
     */
    public static function add_settings()
    {

        $page = "fortnox";
        // class-wf-products tab
        WF_Admin_Settings::add_tab( [
            'page' => $page,
            'name' => "products",
            'title' => __( "Products", WF_Plugin::TEXTDOMAIN ),
        ] );

        // Accounting section
        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "products",
            'name' => "sync_price",
            'title' => __( "Synchronization", WF_Plugin::TEXTDOMAIN ),
            'description' => __( "Synchronization settings", WF_Plugin::TEXTDOMAIN )
        ] );

        // Preferences checkboxes fields
        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "products",
            'section' => "sync_price",
            'type' => "checkboxes",
            'options' => [
                [
                    'name' => "fortnox_auto_sync_products",
                    'label' => __( "Automatically synchronise products", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Whenever a product is changed in WooCommerce the change will be reflected in Fortnox.", WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_sync_master_product",
                    'label' => __( "Synchronise master product (must have SKU).", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "When checked, the master product of a variation will be synchronized as well.", WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_skip_product_variations",
                    'label' => __( "Do not synchronise product variations.", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "When checked, only the master product will be synchronized.", WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_do_not_sync_price",
                    'label' => __( "Do not sync product price on sync.", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Normally, the product price change in WooCommerce will update the price in Fortnox. This setting will disregard price updates in WooCommerce (although any order with this product will use the WooCommerce price).", WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_do_not_update_product_on_order_sync",
                    'label' => __( "Do not sync product on order sync.", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Normally, when synchronizing an order the products of this order will update their counterparts in Fortnox. This setting will prohibit updating the products in Fortnox.", WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_auto_generate_sku",
                    'label' => __( "Automatically generate an SKU for products with missing SKU.", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Fortnox articles need to have an SKU. If a WooCommerce order contains products without SKUs, this setting will make sure that they get SKUs generated from their title.", WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_enable_purchase_price",
                    'label' => __( "Enable purchase price on product view.", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Enable purchase price on product view.", WF_Plugin::TEXTDOMAIN ),
                ]
            ]
        ] );

        // Price list field
        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "products",
            'section' => "sync_price",
            'name' => "fortnox_default_price_list",
            'type' => "dropdown",
            'short' => true,
            'options' => self::get_fortnox_price_lists(),
            'placeholder' => "A",
            'title' => __( "Price list", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "Default Fortnox price list. Sets default price list to A when left empty.", WF_Plugin::TEXTDOMAIN )
        ] );
    }

    /** Returns Fortnox price lists from database
     * @return array
     */
    private static function get_fortnox_price_lists(){
        $fortnox_price_lists = get_option( 'fortnox_price_lists' );

        if ( $fortnox_price_lists ) {
            $price_lists = array_map( function( $fortnox_price_list ){
                return [
                    "value" => $fortnox_price_list,
                    "label" => $fortnox_price_list
                ];
            }, $fortnox_price_lists );

            array_unshift( $price_lists, [
                "value" => "",
                "label" => __( "Please select...", WF_Plugin::TEXTDOMAIN )
            ]);
        } else {
            $price_lists = [
                [
                    "value" => "",
                    "label" => __( "No price lists available in Fortnox", WF_Plugin::TEXTDOMAIN )
                ]
            ];
        }
        return $price_lists;
    }
}
