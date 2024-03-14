<?php

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

if ( ! class_exists( 'RC_Settings_General' ) ) {

    class RC_Settings_General {

        function __construct() {}

        function RC_Settings_General() {
            $this->__construct();
        }

        function get_settings_sections( $tab = '' ) {

            global $rsc;
            $sections = array();

            $sections[ 'welcome' ] = array(
                array(
                    'name' => 'welcome_section',
                    'title' => sprintf( __( 'Thank you for choosing %s%s%s', 'rsc' ), '<strong>', $rsc->title, '</strong>' ),
                    'subtitle' => __( 'Simple solution for restricting the content on your WordPress website.', 'rsc' ),
                    'header' => '',
                    'footer' => '',
                    'description' => '',
                    'has_save_button' => false,
                ),
            );

            $sections[ 'general' ] = array(
                array(
                    'name' => 'general_messages',
                    'title' => __( 'General', 'rsc' ),
                    'subtitle' => '',
                    'header' => __( 'Set up messages that visitors will get if they don’t have permission to view the restricted content. You can set different messages based on the type of restriction.', 'rsc' ),
                    'footer' => '',
                    'description' => '',
                    'has_save_button' => true,
                ),
            );

            $sections[ 'tickera' ] = array(
                array(
                    'name' => 'tickera_messages',
                    'title' => __( 'Tickera', 'rsc' ),
                    'subtitle' => '',
                    'header' => __( 'Messages that visitors will get if they don’t have permission to view the content restricted by Tickera specific criteria', 'rsc' ),
                    'footer' => '',
                    'description' => '',
                    'has_save_button' => true,
                ),
            );

            $sections[ 'woocommerce' ] = array(
                array(
                    'name' => 'woocommerce_messages',
                    'title' => __( 'WooCommerce', 'rsc' ),
                    'subtitle' => '',
                    'header' => __( 'Messages that visitors will get if they don’t have permission to view the content restricted by WooCommerce specific criteria', 'rsc' ),
                    'footer' => '',
                    'description' => '',
                    'has_save_button' => true,
                ),
            );

            $sections[ 'edd' ] = array(
                array(
                    'name' => 'edd_messages',
                    'title' => __( 'Easy Digital Downloads', 'rsc' ),
                    'subtitle' => '',
                    'header' => __( 'Messages that visitors will get if they don’t have permission to view the content restricted by Easy Digital Downloads specific criteria', 'rsc' ),
                    'footer' => '',
                    'description' => '',
                    'has_save_button' => true,
                ),
            );

            if ( class_exists( 'TC' ) ) {

                global $tc;
                $tickera_warning_message = false;

                if ( apply_filters( 'tc_is_woo', false ) == true ) {//Tickera is in the Bridge mode
                    $tickera_title = $tc->title . ' (' . __( 'Bridge for WooCommerce', 'rsc' ) . ')';

                } else {

                    $tickera_title = $tc->title;
                    $tc_general_settings = get_option( 'tc_general_setting', false );

                    if ( ! isset( $tc_general_settings[ 'force_login' ] ) || $tc_general_settings[ 'force_login' ] !== 'yes' ) {
                        $tickera_warning_message = sprintf( __( 'WARNING: In order to track user purchases, you\'ll need to set "Force Login" option in %s to YES.', 'rsc' ), '<a href="' . admin_url( 'edit.php?post_type=tc_events&page=tc_settings' ) . '">' . sprintf( __( '%s Settings', 'rsc' ), $tc->title ) . '</a>' );
                    }
                }

                $sections[ 'tickera_messages_section' ][] = array(
                    'name' => 'tickera_messages',
                    'title' => $tickera_title,
                    'subtitle' => '',
                    'header' => '',
                    'footer' => '',
                    'description' => isset( $tickera_warning_message ) ? $tickera_warning_message : '',
                );
            }

            if ( class_exists( 'WooCommerce' ) ) {

                $sections[ 'woocommerce_messages_section' ][] = array(
                    'name' => 'woocommerce_messages',
                    'title' => __( 'WooCommerce', 'rsc' ),
                    'subtitle' => '',
                    'header' => '',
                    'footer' => '',
                );
            }

            if ( class_exists( 'Easy_Digital_Downloads' ) ) {

                $sections[ 'edd_messages_section' ][] = array(
                    'name' => 'edd_messages',
                    'title' => __( 'Easy Digital Downloads', 'rsc' ),
                    'subtitle' => '',
                    'header' => '',
                    'footer' => '',
                );
            }

            $sections = apply_filters( 'rc_settings_new_sections', $sections );
            return $sections[ $tab ];

        }

        function get_settings_general_fields() {

            $rsc_settings = get_option( 'rsc_settings', false );

            $restricted_content_settings_default_fields = array(
                array(
                    'field_name' => 'logged_in_message',
                    'field_title' => __( 'Logged In', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to non-logged in users when they attempt to view restricted content.', 'rsc' ),
                    'section' => 'general_messages',
                    'default_value' => __( 'You must log in to view this content.', 'rsc' ),
                ),
                array(
                    'field_name' => 'user_role_message',
                    'field_title' => __( 'User Role', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to users that do not have a required user role to view the content. ', 'rsc' ),
                    'section' => 'general_messages',
                    'default_value' => __( 'You don\'t have required permissions to view this content.', 'rsc' ),
                ),
                array(
                    'field_name' => 'capability_message',
                    'field_title' => __( 'Capability', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to users without required user capability to access restricted content.', 'rsc' ),
                    'section' => 'general_messages',
                    'default_value' => __( 'You don\'t have required permissions to view this content.', 'rsc' ),
                ),
                array(
                    'field_name' => 'author_message',
                    'field_title' => __( 'Post Author', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to a user who isn\'t an author of the page / post (if the content is available to it\'s authors only)', 'rsc' ),
                    'section' => 'general_messages',
                    'default_value' => __( 'This content is available only to it\'s author.', 'rsc' ),
                )
            );

            if ( class_exists( 'TC' ) ) {

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'tickera_any_ticket_type_message',
                    'field_title' => __( 'Any Ticket Type', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the user that haven\'t purchased any tickets.', 'rsc' ),
                    'section' => 'tickera_messages',
                    'default_value' => __( 'This content is restricted to the attendees only. Please purchase ticket(s) in order to access this content.', 'rsc' ),
                );

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'tickera_specific_ticket_type_message',
                    'field_title' => __( 'Specific Ticket Type', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the visitor that haven\'t purchased a ticket of the specific, required ticket type', 'rsc' ),
                    'field_description' => __( 'You can use <strong>[rsc_tc_ticket_type]</strong> placeholder for required ticket type(s).', 'rsc' ),
                    'section' => 'tickera_messages',
                    'default_value' => __( 'Only attendees who purchased following ticket type(s): [rsc_tc_ticket_type] can access this content.', 'rsc' ),
                );

                // Specific event option is not available for Bridge for WooCommerce because task would be to expensive for the database server
                if ( apply_filters( 'tc_is_woo', false ) == false ) {

                    $restricted_content_settings_default_fields[] = array(
                        'field_name' => 'tickera_specific_event_message',
                        'field_title' => __( 'Specific Event', 'rsc' ),
                        'field_type' => 'wp_editor',
                        'tooltip' => __( 'A message displayed to the visitor that haven\'t purchased a ticket for the specific, required event', 'rsc' ),
                        'field_description' => __( 'You can use <strong>[rsc_tc_event]</strong> placeholder for required event(s). Alternatively, you can use <strong>[rsc_tc_event_links]</strong> placeholder to show event titles with links.', 'rsc' ),
                        'section' => 'tickera_messages',
                        'default_value' => __( 'Only attendees who purchased ticket(s) for following event(s): [rsc_tc_event] can access this content.', 'rsc' ),
                    );
                }
            }

            if ( class_exists( 'WooCommerce' ) ) {

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'woo_any_product_message',
                    'field_title' => __( 'Any Product', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the user that haven\'t purchased any WooCommerce products.', 'rsc' ),
                    'section' => 'woocommerce_messages',
                    'default_value' => __( 'This content is restricted to the clients only. Please purchase any product in order to access this content.', 'rsc' ),
                );

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'woo_specific_product_message',
                    'field_title' => __( 'Specific Product', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the visitor that haven\'t purchased a specific, required product', 'rsc' ),
                    'field_description' => __( 'You can use <strong>[rsc_woo_product]</strong> placeholder for required WooCommerce product(s). Alternatively, you can use <strong>[rsc_woo_product_links]</strong> placeholder to show products with links.', 'rsc' ),
                    'section' => 'woocommerce_messages',
                    'default_value' => __( 'Only clients who purchased following product(s): [rsc_woo_product] can access this content.', 'rsc' ),
                );

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'woo_specific_product_limited_message',
                    'field_title' => __( 'Specific Product (Limited Time)', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the visitor whose access to the content has expired', 'rsc' ),
                    'field_description' => __( 'You can use <strong>[rsc_woo_product]</strong> placeholder for required WooCommerce product(s). Alternatively, you can use <strong>[rsc_woo_product_links]</strong> placeholder to show products with links.', 'rsc' ),
                    'section' => 'woocommerce_messages',
                    'default_value' => __( 'The access to this content is invalid or has expired. Please (re)purchase one of the following product(s): [rsc_woo_product_links] in order to get access to this content.', 'rsc' ),
                );
            }

            if ( class_exists( 'Easy_Digital_Downloads' ) ) {

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'edd_any_product_message',
                    'field_title' => __( 'Any Product', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the user that haven\'t purchased any EDD products.', 'rsc' ),
                    'section' => 'edd_messages',
                    'default_value' => __( 'This content is restricted to the clients only. Please purchase any product in order to access this content.', 'rsc' ),
                );

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'edd_specific_product_message',
                    'field_title' => __( 'Specific Product', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the visitor that haven\'t purchased a specific, required product', 'rsc' ),
                    'field_description' => __( 'You can use <strong>[rsc_edd_product]</strong> placeholder for required EDD product(s). Alternatively, you can use <strong>[rsc_edd_product_links]</strong> placeholder to show products with links.', 'rsc' ),
                    'section' => 'edd_messages',
                    'default_value' => __( 'Only clients who purchased following product(s): [rsc_edd_product] can access this content.', 'rsc' ),
                );

                $restricted_content_settings_default_fields[] = array(
                    'field_name' => 'edd_specific_product_limited_message',
                    'field_title' => __( 'Specific Product (Limited Time)', 'rsc' ),
                    'field_type' => 'wp_editor',
                    'tooltip' => __( 'A message displayed to the visitor whose access to the content has expired', 'rsc' ),
                    'field_description' => __( 'You can use <strong>[rsc_edd_product]</strong> placeholder for required EDD product(s). Alternatively, you can use <strong>[rsc_edd_product_links]</strong> placeholder to show products with links.', 'rsc' ),
                    'section' => 'edd_messages',
                    'default_value' => __( 'The access to this content is invalid or has expired. Please (re)purchase one of the following product(s): [rsc_edd_product_links] in order to get access to this content.', 'rsc' ),
                );
            }

            $restricted_content_settings_default_fields = apply_filters( 'rsc_settings_store_fields', $restricted_content_settings_default_fields );
            $default_fields = $restricted_content_settings_default_fields;
            return apply_filters( 'rsc_settings_general_fields', $default_fields );
        }
    }
}
?>
