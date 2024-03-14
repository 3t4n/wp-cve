<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );



if ( !class_exists( 'WPSF_Ajax_Admin' ) ) {

    class WPSF_Ajax_Admin extends WPSF_Library {

        function __construct() {
            /**
             * Form save ajax
             */
            add_action( 'wp_ajax_wpsf_form_save_action', array( $this, 'form_settings_save_action' ) );
            add_action( 'wp_ajax_nopriv_wpsf_form_save_action', array( $this, 'permission_denied' ) );

            /**
             * Form delete ajax
             *
             */
            add_action( 'wp_ajax_wpsf_form_delete_action', array( $this, 'form_delete_action' ) );
            add_action( 'wp_ajax_nopriv_wpsf_form_delete_action', array( $this, 'permission_denied' ) );

            /**
             * Form copy ajax
             *
             */
            add_action( 'wp_ajax_wpsf_form_copy_action', array( $this, 'form_copy_action' ) );
            add_action( 'wp_ajax_nopriv_wpsf_form_copy_action', array( $this, 'permission_denied' ) );


            /**
             * Subscriber delete ajax
             *
             */
            add_action( 'wp_ajax_wpsf_subscriber_delete_action', array( $this, 'subscriber_delete_action' ) );
            add_action( 'wp_ajax_nopriv_wpsf_subscriber_delete_action', array( $this, 'permission_denied' ) );
        }

        function form_settings_save_action() {
            if ( $this->admin_ajax_nonce_verify() ) {

                $form_data = $_POST['form_data'];
                parse_str( $form_data, $form_data );
                $form_data = stripslashes_deep( $form_data );
                $form_alias = sanitize_text_field( $form_data['form_alias'] );
                $form_title = sanitize_text_field( $form_data['form_title'] );
                $form_status = (!empty( $form_data['form_status'] )) ? 1 : 0;
                $form_details = $form_data['form_details'];
                /**
                 * General Settings
                 */
                $new_form_details['general']['double_optin'] = (!empty( $form_details['general']['double_optin'] )) ? 1 : 0;
                $new_form_details['general']['optin_confirmation_message'] = $this->sanitize_escaping_linebreaks( $form_details['general']['optin_confirmation_message'] );
                $new_form_details['general']['success_message'] = sanitize_text_field( $form_details['general']['success_message'] );
                $new_form_details['general']['required_error_message'] = sanitize_text_field( $form_details['general']['required_error_message'] );
                $new_form_details['general']['error_message'] = sanitize_text_field( $form_details['general']['error_message'] );
                /**
                 * Form Settings
                 */
                // Heading Fields
                $new_form_details['form']['heading']['show'] = (!empty( $form_details['form']['heading']['show'] )) ? 1 : 0;
                $new_form_details['form']['heading']['text'] = sanitize_text_field( $form_details['form']['heading']['text'] );

                // Sub Heading Fields
                $new_form_details['form']['sub_heading']['show'] = (!empty( $form_details['form']['sub_heading']['show'] )) ? 1 : 0;
                $new_form_details['form']['sub_heading']['text'] = sanitize_text_field( $form_details['form']['sub_heading']['text'] );

                // Name Fields
                $new_form_details['form']['name']['show'] = (!empty( $form_details['form']['name']['show'] )) ? 1 : 0;
                $new_form_details['form']['name']['required'] = (!empty( $form_details['form']['name']['required'] )) ? 1 : 0;
                $new_form_details['form']['name']['label'] = sanitize_text_field( $form_details['form']['name']['label'] );

                // Email Fields
                $new_form_details['form']['email']['label'] = sanitize_text_field( $form_details['form']['email']['label'] );

                // Terms and agreement
                $new_form_details['form']['terms_agreement']['show'] = (!empty( $form_details['form']['terms_agreement']['show'] )) ? 1 : 0;
                $new_form_details['form']['terms_agreement']['agreement_text'] = wp_kses_post( $form_details['form']['terms_agreement']['agreement_text'] );

                // Subscribe Button
                $new_form_details['form']['subscribe_button']['button_text'] = sanitize_text_field( $form_details['form']['subscribe_button']['button_text'] );

                //Footer Text
                $new_form_details['form']['footer']['show'] = (!empty( $form_details['form']['footer']['show'] )) ? 1 : 0;
                $new_form_details['form']['footer']['footer_text'] = wp_kses_post( $form_details['form']['footer']['footer_text'] );

                /**
                 * Layout Settings
                 */
                $new_form_details['layout']['template'] = sanitize_text_field( $form_details['layout']['template'] );
                $new_form_details['layout']['form_width'] = sanitize_text_field( $form_details['layout']['form_width'] );
                $new_form_details['layout']['display_type'] = sanitize_text_field( $form_details['layout']['display_type'] );
                $new_form_details['layout']['popup_trigger_text'] = sanitize_text_field( $form_details['layout']['popup_trigger_text'] );

                /**
                 * Email Settings
                 */
                $new_form_details['email']['from_email'] = sanitize_email( $form_details['email']['from_email'] );
                $new_form_details['email']['from_name'] = sanitize_text_field( $form_details['email']['from_name'] );
                $new_form_details['email']['confirmation_email_subject'] = sanitize_text_field( $form_details['email']['confirmation_email_subject'] );
                $new_form_details['email']['confirmation_email_message'] = $this->sanitize_escaping_linebreaks( $form_details['email']['confirmation_email_message'] );


                $new_form_details = (!is_serialized( $new_form_details )) ? maybe_serialize( $new_form_details ) : '';
                if ( empty( $form_title ) || empty( $form_alias ) ) {
                    $response['status'] = 403;
                    $response['message'] = esc_html__( 'Form title or Alias cannot be empty.', 'wp-subscription-forms' );
                } else {
                    $form_id = (!empty( $form_data['form_id'] )) ? intval( $form_data['form_id'] ) : 0;
                    if ( $this->is_alias_available( $form_alias, $form_id ) ) {
                        global $wpdb;
                        if ( !empty( $form_id ) ) {
                            //update if form id is available in the form
                            $update_check = $wpdb->update( WPSF_FORM_TABLE, array( 'form_title' => $form_title,
                                'form_alias' => $form_alias,
                                'form_details' => $new_form_details,
                                'form_status' => $form_status,
                                    ), array( 'form_id' => $form_id ), array( '%s', '%s', '%s', '%s' ), array( '%d' )
                            );

                            $response['status'] = 200;
                            $response['message'] = esc_html__( 'Form updated successfully.', 'subsribe-to-download' );
                        } else {
                            $insert_check = $wpdb->insert( WPSF_FORM_TABLE, array( 'form_title' => $form_title,
                                'form_alias' => $form_alias,
                                'form_details' => $new_form_details,
                                'form_status' => $form_status,
                                    ), array( '%s', '%s', '%s', '%s' )
                            );
                            if ( $insert_check ) {
                                $form_id = $wpdb->insert_id;
                                $response['status'] = 200;
                                $response['message'] = esc_html__( 'Form added successfully. Redirecting...', 'subsribe-to-download' );
                                $response['redirect_url'] = admin_url( 'admin.php?page=wp-subscription-forms&action=edit_form&form_id=' . $form_id );
                            } else {
                                $response['status'] = 403;
                                $response['message'] = esc_html__( 'Something went wrong. Please try again later.', 'subsribe-to-download' );
                            }
                        }
                    } else {
                        $response['status'] = 403;
                        $response['message'] = esc_html__( 'Form alias already used. Please use some other alias.', 'wp-subscription-forms' );
                    }
                }
                die( json_encode( $response ) );
            } else {
                $this->permission_denied();
            }
        }

        function form_delete_action() {
            if ( $this->admin_ajax_nonce_verify() ) {
                $form_id = intval( $_POST['form_id'] );
                global $wpdb;
                $delete_check = $wpdb->delete( WPSF_FORM_TABLE, array( 'form_id' => $form_id ), array( '%d' ) );
                if ( $delete_check ) {
                    $response['status'] = 200;
                    $response['message'] = esc_html__( 'Form deleted successfully', 'wp-subscription-forms' );
                } else {
                    $response['status'] = 403;
                    $response['message'] = esc_html__( 'There occurred some error. Please try again later.', 'wp-subscription-forms' );
                }
                echo json_encode( $response );
                die();
            } else {
                $this->permission_denied();
            }
        }

        function form_copy_action() {
            if ( $this->admin_ajax_nonce_verify() ) {
                $form_id = intval( $_POST['form_id'] );
                global $wpdb;
                $copy_check = $this->copy_form( $form_id );
                if ( $copy_check ) {
                    $response['status'] = 200;
                    $response['message'] = esc_html__( 'Form copied successfully.Redirecting..', 'wp-subscription-forms' );
                    $response['redirect_url'] = admin_url( 'admin.php?page=wp-subscription-forms' );
                } else {
                    $response['status'] = 403;
                    $response['message'] = esc_html__( 'There occurred some error. Please try again later.', 'wp-subscription-forms' );
                }
                echo json_encode( $response );
                die();
            } else {
                $this->permission_denied();
            }
        }

        function subscriber_delete_action() {
            if ( $this->admin_ajax_nonce_verify() ) {
                $subscriber_id = intval( $_POST['subscriber_id'] );
                global $wpdb;
                $delete_check = $wpdb->delete( WPSF_SUBSCRIBERS_TABLE, array( 'subscriber_id' => $subscriber_id ), array( '%d' ) );
                if ( $delete_check ) {
                    $response['status'] = 200;
                    $response['message'] = esc_html__( 'Subscriber deleted successfully', 'wp-subscription-forms' );
                } else {
                    $response['status'] = 403;
                    $response['message'] = esc_html__( 'There occurred some error. Please try again later.', 'wp-subscription-forms' );
                }
                echo json_encode( $response );
                die();
            } else {
                $this->permission_denied();
            }
        }

    }

    new WPSF_Ajax_Admin();
}
