<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;


class WF_Order_Settings_View{

    /**
     * Adds all required setting fields for Order Settings View
     */
    public static function add_settings()
    {
        $page = "fortnox";

        WF_Admin_Settings::add_tab( [
            'page' => $page,
            'name' => "order",
            'title' => __( "Orders", WF_Plugin::TEXTDOMAIN )
        ] );

	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "order",
		    'section' => "order",
		    'type' => "checkboxes",
		    'title' => __( "Misc. order settings", WF_Plugin::TEXTDOMAIN ),
		    'options' => [
			    [
				    'name' => "fortnox_write_payment_type_to_ordertext",
				    'label' => __( "Add payment type to order text", WF_Plugin::TEXTDOMAIN ),
				    'tooltip' => __( "Writes order payment type to ordertext field in Fortnox.", WF_Plugin::TEXTDOMAIN )
			    ],
			    [
				    'name' => "fortnox_write_customer_notes_to_ordertext",
				    'label' => __( "Copy customer notes to order text", WF_Plugin::TEXTDOMAIN ),
				    'tooltip' => __( "Writes customer notes to ordertext field in Fortnox.", WF_Plugin::TEXTDOMAIN )
			    ],
                [
                    'name' => "fortnox_copy_remarks_to_invoice",
                    'label' => __( "Copy order text to invoice", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Will copy the ordertext field to invoices in Fortnox.", WF_Plugin::TEXTDOMAIN )
                ],
			    [
				    'name' => "fortnox_get_currency_rate",
				    'label' => __( "Get currency rate for currencies other than SEK", WF_Plugin::TEXTDOMAIN ),
				    'tooltip' => __( "Get currency rate for order if the currency isn't SEK. Please note that the currency rate is set from Fortnox settings.", WF_Plugin::TEXTDOMAIN )
			    ],
			    [
				    'name' => "show_organization_number_field_in_billing_address_form",
				    'label' => __( "Display organization registration number in checkout", WF_Plugin::TEXTDOMAIN ),
				    'tooltip' => __( "Extra field for Company organization registration number shows in the Checkout form.", WF_Plugin::TEXTDOMAIN )
			    ],
                [
                    'name' => "wf_do_not_sync_customer_on_update",
                    'label' => __( "Do not update customer on order synchronisation", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "If customer exists in Fortnox, do not update customer on order synchronisation.", WF_Plugin::TEXTDOMAIN )
                ],
		        [
                    'name' => "fortnox_add_customer_notes_to_order",
                    'label' => __( "Adds customer notes to order", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Adds customer notes to order.", WF_Plugin::TEXTDOMAIN )
                ],
		        [
                    'name' => "fortnox_email_synchronization_errors",
                    'label' => __( "If an error occurs an email will be sent to shop admin", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "If an error occurs an email will be sent to shop admin", WF_Plugin::TEXTDOMAIN )
                ],
		    ]
	    ] );

	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "order",
		    'section' => "order",
		    'type' => "checkboxes",
		    'title' => __( "Active modules", WF_Plugin::TEXTDOMAIN ),
		    'options' => [
			    [
				    'name' => "fortnox_has_warehouse_module",
				    'label' => __( "Fortnox Warehouse module", WF_Plugin::TEXTDOMAIN ),
				    'description' => __( '<b class="red warning">IMPORTANT!</b> Select this option if you have the Fortnox Warehouse module activated.', WF_Plugin::TEXTDOMAIN )
			    ]
		    ]
	    ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "order",
            'section' => "order",
            'name' => "fortnox_warehouse_delivery_status",
            'title' => __( "Delivery status", WF_Plugin::TEXTDOMAIN ),
            'type' => "dropdown",
            'short' => true,
            'options' => self::get_delivery_statuses()
        ] );

	    if( ! is_plugin_active( 'woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php' ) ) {
		    // Sequential order number
		    WF_Admin_Settings::add_field( [
			    'page' => $page,
			    'tab' => "order",
			    'section' => "order",
			    'name' => "fortnox_order_number_prefix",
			    'type' => "number",
			    'min' => '1',
			    'max' => '99999999',
			    'title' => __( "Sequential order number", WF_Plugin::TEXTDOMAIN ),
			    'tooltip' => __( "Sequential number to prepend to WooCommerce order number", WF_Plugin::TEXTDOMAIN )
		    ] );
	    }

	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "order",
		    'section' => "order",
		    'name' => "fortnox_cost_center",
		    'short' => true,
		    'title' => __( "Cost center", WF_Plugin::TEXTDOMAIN ),
		    'tooltip' => __( "Adds cost center for an order to use in bookkeeping. This is used within different sale channels in Fortnox.", WF_Plugin::TEXTDOMAIN ),
	    ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "order",
            'section' => "order",
            'name' => "fortnox_administration_fee_names",
            'short' => true,
            'title' => __( "Administration fee name", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "Use this setting to set specific fees as administration fee. If you are using multiple names, use comma as separator.", WF_Plugin::TEXTDOMAIN )
        ] );
    }

    /** Returns delivery statuses
     * @return array
     */
    private static function get_delivery_statuses(){
        $delivery_statuses = [
            [
                "value" => 'delivery',
                "label" => __( "Delivery", WF_Plugin::TEXTDOMAIN )
            ],
            [
                "value" => 'reservation',
                "label" => __( "Reservation", WF_Plugin::TEXTDOMAIN )
            ]
        ];

        array_unshift( $delivery_statuses, [
            "value" => "",
            "label" => __( "Please select...", WF_Plugin::TEXTDOMAIN )
        ]);

        return $delivery_statuses;
    }
}
