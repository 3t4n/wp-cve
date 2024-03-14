<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;


class WF_Automation_Settings_View{

    /**
     * Adds all required setting fields for Automation Settings View
     */
    public static function add_settings()
    {
        $page = "fortnox";

        // Automation settings tab
        WF_Admin_Settings::add_tab( [
            'page' => $page,
            'name' => "automations",
            'title' => __( "Automations", WF_Plugin::TEXTDOMAIN )
        ] );

	    $order_status_select = array_map( function( $status, $title ){
		    return [
			    "value" => substr( $status, 3),
			    "label" => $title
		    ];
	    }, array_keys( wc_get_order_statuses() ), wc_get_order_statuses() );

	    array_unshift( $order_status_select, [
		    "value" => "",
		    "label" => __( "Only manual synchronization", WF_Plugin::TEXTDOMAIN )
	    ]);

	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "automations",
		    'section' => "automations",
		    'name' => "fortnox_sync_on_status",
		    'title' => __( "Automatically sync on order status", WF_Plugin::TEXTDOMAIN ),
		    'type' => "dropdown",
		    'options' => $order_status_select,
		    'tooltip' => __( "Whenever an order gets the selected status it will automatically be synced to Fortnox.", WF_Plugin::TEXTDOMAIN ),
		    'description' => __( "Not selecting an order status will mean that all synchronization has to be done manually!", WF_Plugin::TEXTDOMAIN ),
	    ] );

        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "automations",
            'name' => "flow",
            //'title' => __( "Invoice settings", WF_Plugin::TEXTDOMAIN ),
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "automations",
            'section' => "flow",
            'type' => "checkboxes",
            'title' => __( "Invoice settings", WF_Plugin::TEXTDOMAIN ),
            'options' => [
                [
                    'name' => "fortnox_auto_create_order_invoice",
                    'label' => __( "Create invoice when order successfully synchronised", WF_Plugin::TEXTDOMAIN ),
	                'tooltip' => __( "This will create an invoice in Fortnox for each synchronized order.", WF_Plugin::TEXTDOMAIN )
                ],
	            [
		            'name' => "fortnox_auto_send_order_invoice",
		            'label' => __( "Automatically send Fortnox invoice to customer", WF_Plugin::TEXTDOMAIN ),
		            'tooltip' => __( "The above option only creates the invoices. By selecting this option, the invoices will automatically be sent to the customer as well.", WF_Plugin::TEXTDOMAIN )
	            ],
                [
                    'name' => "fortnox_auto_post_order_invoice",
                    'label' => __( "Automatically book created invoice", WF_Plugin::TEXTDOMAIN ),
                    //TODO: print the default receivable account in the tooltip
                    'tooltip' => __( 'This option will book the invoice with the Fortnox default receivable account.', WF_Plugin::TEXTDOMAIN ),
                    'description' => __( 'When an invoice is booked it cannot be changed. Selecting this option will automate the process, but <b class="red warning">changes will not be possible to any invoices</b>.', WF_Plugin::TEXTDOMAIN )
                ],
                [
                    'name' => "fortnox_auto_set_invoice_as_paid",
                    'label' => __( "Automatically register a payment for each created invoice", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( "Card payments, PayPal, Klarna, Swish etc will charge the customer directly and not allow an order if no payment has been made, meaning you can automate the payment registration process.", WF_Plugin::TEXTDOMAIN )
                ]
            ]
        ] );

        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "automations",
            'name' => "refund",
            //'title' => __( "Refund flow", WF_Plugin::TEXTDOMAIN ),
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "automations",
            'section' => "refund",
            'type' => "checkboxes",
            'title' => __( "Refund settings", WF_Plugin::TEXTDOMAIN ),
            'options' => [
                [
                    'name' => "credit_note_on_refund",
                    'label' => __( "Create a refund order in Fortnox on WooCommerce refunds", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( 'This option will create a refund order in Fortnox if a refund is made in WooCommerce. Partial refunds will be refunded partially.', WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_auto_create_refund_invoice",
                    'label' => __( "Create invoice when successfully synchronised refund", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( 'This option will create a corresponding refund invoice (credit note).', WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_auto_post_refund_invoice",
                    'label' => __( "Automatically book refund invoice", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( 'This option will book the refund invoice with the Fortnox default receivable account.', WF_Plugin::TEXTDOMAIN ),
                ],
                [
                    'name' => "fortnox_auto_set_refund_invoice_as_paid",
                    'label' => __( "Automatically set refund invoice as paid", WF_Plugin::TEXTDOMAIN ),
                    'tooltip' => __( 'This option will set the refund invoice as paid', WF_Plugin::TEXTDOMAIN ),
                ]
            ]
        ] );
    }
}
