<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
if ( !class_exists( 'STUL_Library' ) ) {

    class STUL_Library {

        /**
         * Exit for unauthorized access
         *
         * @since 1.0.0
         */
        function permission_denied() {
            die( 'No script kiddies please!!' );
        }

        /**
         * Prints array in the pre format
         *
         * @param array $array
         * @since 1.0.0
         */
        function print_array( $array ) {
            echo "<pre>";
            print_r( $array );
            echo "</pre>";
        }

        /**
         * Sanitizes Multi Dimensional Array
         * @param array $array
         * @param array $sanitize_rule
         * @return array
         *
         * @since 1.0.0
         */
        function sanitize_array( $array = array(), $sanitize_rule = array() ) {
            if ( !is_array( $array ) || count( $array ) == 0 ) {
                return array();
            }

            foreach ( $array as $k => $v ) {
                if ( !is_array( $v ) ) {

                    $default_sanitize_rule = (is_numeric( $k )) ? 'html' : 'text';
                    $sanitize_type = isset( $sanitize_rule[$k] ) ? $sanitize_rule[$k] : $default_sanitize_rule;
                    $array[$k] = $this->sanitize_value( $v, $sanitize_type );
                }
                if ( is_array( $v ) ) {
                    $array[$k] = $this->sanitize_array( $v, $sanitize_rule );
                }
            }

            return $array;
        }

        /**
         * Sanitizes Value
         *
         * @param type $value
         * @param type $sanitize_type
         * @return string
         *
         * @since 1.0.0
         */
        function sanitize_value( $value = '', $sanitize_type = 'text' ) {
            switch( $sanitize_type ) {
                case 'html':
                    $allowed_html = wp_kses_allowed_html( 'post' );
                    return $this->sanitize_html( $value );
                    break;
                case 'to_br':
                    return $this->sanitize_escaping_linebreaks( $value );
                    break;
                default:
                    return sanitize_text_field( $value );
                    break;
            }
        }

        /**
         * Ajax nonce verification for ajax in admin
         *
         * @return bolean
         * @since 1.0.0
         */
        function admin_ajax_nonce_verify() {
            if ( !empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'stul_ajax_nonce' ) ) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Ajax nonce verification for ajax in frontend
         *
         * @return bolean
         * @since 1.0.0
         */
        function ajax_nonce_verify() {
            if ( !empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'stul_frontend_ajax_nonce' ) ) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Returns the default email message
         *
         * @return string
         *
         * @since 1.0.0
         */
        function get_default_email_message() {
            $default_email_message = esc_html__( sprintf( 'Hello There,

Thank you for subscribing in our %s website. Please click on below link to unlock the content:

#unlock_link

Thank you', esc_attr( get_bloginfo( 'name' ) ) ), 'subscribe-to-unlock-lite' );
            return $default_email_message;
        }

        /**
         * Returns the default unlock link message
         *
         * @return string
         *
         * @since 1.0.0
         */
        function get_default_unlock_link_message() {
            $default_unlock_link_message = esc_html__( 'Congratulations!!

Your email has been verified and the locked content has been unlocked.

Please refresh the previous page to view the locked content.

Thank you', 'subscribe-to-unlock-lite' );
            return $default_unlock_link_message;
        }

        /**
         * Sanitizes the content by bypassing allowed html
         *
         * @param string $text
         * @return string
         *
         * @since 1.0.0
         */
        function sanitize_html( $text, $br_omit = false ) {
            $allowed_html = wp_kses_allowed_html( 'post' );
            if ( $br_omit ) {
                unset( $allowed_html['br'] );
            }
            return wp_kses( $text, $allowed_html );
        }

        /**
         * Sanitizes field by converting line breaks to <br /> tags
         *
         * @since 1.0.0
         *
         * @return string $text
         */
        function sanitize_escaping_linebreaks( $text ) {
            $text = $this->sanitize_html( $text, true );
            $text = implode( "<br \>", explode( "\n", $text ) );
            return $text;
        }

        /**
         * Outputs by converting <Br/> tags into line breaks
         *
         * @since 1.0.0
         *
         * @return string $text
         */
        function output_converting_br( $text ) {
            $text = $this->sanitize_html( $text, true );
            $text = implode( "\n", explode( "<br \>", $text ) );
            return $text;
        }

        /**
         * Gets the subscriber row from email
         *
         * @param string $email
         *
         * @return object/boolean
         *
         * @since 1.0.0
         */
        function get_subscriber_row_by_email( $email ) {
            global $wpdb;
            $subscriber_table = STUL_SUBSCRIBERS_TABLE;
            $subscriber_row = $wpdb->get_row( $wpdb->prepare( "select * from $subscriber_table where subscriber_email = %s", $email ) );
            return $subscriber_row;
        }

        /**
         * Returns the default From Email
         *
         * @return string
         *
         * @since 1.0.0
         */
        function get_default_from_email() {
            $site_url = site_url();
            $find_h = '#^http(s)?://#';
            $find_w = '/^www\./';
            $replace = '';
            $output = preg_replace( $find_h, $replace, $site_url );
            $output = preg_replace( $find_w, $replace, $output );
            return 'noreply@' . $output;
        }

        /**
         * Generates a unique unlock key
         *
         * @return string
         *
         * @since 1.0.0
         */
        function generate_unlock_key() {
            $current_date_time = date( 'Y-m-d H:i:s' );
            $unlock_key = md5( $current_date_time );
            return $unlock_key;
        }

        /**
         * Returns unlock link message
         *
         * @global object $wpdb
         * @param string $unlock_key
         * @return string/boolean
         *
         * @since 1.0.0
         */
        function get_unlock_link_message( $unlock_key ) {
            $form_details = get_option( 'stul_settings' );

            $unlock_link_message = (!empty( $form_details['general']['unlock_link_message'] )) ? $form_details['general']['unlock_link_message'] : '';
            return $unlock_link_message;
        }

        /**
         * Returns the file name from download URL
         *
         * @param string $url
         * @return string
         *
         * @since 1.0.0
         */
        function get_file_name_from_url( $url ) {
            $url = untrailingslashit( $url );
            $url_array = explode( '/', $url );
            $file_name = end( $url_array );
            return $file_name;
        }

        function check_if_already_subscribed( $unlock_key ) {
            $unlock_key = sanitize_text_field( $unlock_key );
            global $wpdb;
            $subscriber_table = STUL_SUBSCRIBERS_TABLE;
            $subscriber_count = $wpdb->get_var( "SELECT COUNT(*) FROM $subscriber_table WHERE subscriber_unlock_key like '$unlock_key'" );
            if ( $subscriber_count == 0 ) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * Prints Display None
         *
         * @param string $parameter1
         * @param string $parameter2
         *
         * @since 1.0.0
         */
        function display_none( $parameter1, $parameter2 ) {
            if ( $parameter1 != $parameter2 ) {
                echo 'style="display:none"';
            }
        }

        function change_verification_status( $unlock_key ) {
            global $wpdb;
            $wpdb->update( STUL_SUBSCRIBERS_TABLE, array( 'subscriber_verification_status' => 1 ), array( 'subscriber_unlock_key' => $unlock_key ), array( '%d' ), array( '%s' ) );
        }

    }

    $GLOBALS['stul_library'] = new STUL_Library();
}
