<?php

/**
 * Class for Admin Notifications.
 *
 * @package Terms_And_Conditions_Per_Product
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If class exists, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Admin_Notices' ) ) {
    class TACPP4_Terms_Conditions_Admin_Notices {
        const SEEN_NOTICES_OPTION_NAME = 'tacpp_seen_notices';

        public function __construct() {
            add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );

            add_action( 'wp_ajax_tacpp_hide_notice', array( $this, 'tacpp_hide_notice' ) );

            add_action( 'admin_notices', array( $this, 'show_rate_me_admin_notices' ) );

        }

        /**
         * Returns an array of TACPP admin notices.
         *
         * @return mixed
         */
        public function get_admin_notices() {
            $admin_notices = array(
                'tacpp-premium-notice' => array(
                    'text'     => sprintf( __( 'Terms and Conditions per product now have a <a href=%s target="_blank">premium version</a> that allows <strong>terms and conditions per product category</strong> as well as opening the terms in a <strong>modal</strong>.', 'terms-and-conditions-per-product' ), esc_html( 'https://tacpp-pro.com/' ) ),
                    'user_cap' => 'activate_plugins',
                    'is_free'  => true,
                ),
            );

            return apply_filters( 'tacpp4_terms_conditions_admin_notices_get_admin_notices',
                $admin_notices );
        }

        /**
         * Show admin notices.
         */
        public function show_admin_notices() {
            $admin_notices = $this->get_admin_notices();

            if ( ! empty( $admin_notices ) ) {

                $seen_notices = $this->get_seen_notices();

                foreach ( $admin_notices as $notice_id => $notice ) {
                    // Skip for paid users
                    if ( $notice['is_free'] && tacppp_fs()->is_paying_or_trial() ) {
                        continue;
                    } // Skip for free users
                    else if ( ! $notice['is_free'] && ! tacppp_fs()->is_paying_or_trial() ) {
                        continue;
                    }

                    // Skip if notice is already seen
                    if ( in_array( $notice_id, $seen_notices ) ) {
                        continue;
                    }

                    $this->show_admin_notice( $notice, $notice_id );
                }
            }
        }


        /**
         * HTML for admin notice.
         *
         * @param        $notice
         * @param string $notice_id
         */
        public function show_admin_notice( $notice, $notice_id = '' ) {

            if ( is_array( $notice ) ) {
                $text = ( isset( $notice['text'] ) ? wp_kses_post( $notice['text'] ) : '' );
            } else {
                $text = wp_kses_post( $notice );
            }
            ?>
			<div id="<?php echo esc_html( $notice_id ); ?>" class="tacpp-admin-notice notice notice-success is-dismissible">
				<p><?php echo $text; ?></p>
			</div>
            <?php
        }

        /**
         * Ajax callback to hide admin notices.
         */
        public function tacpp_hide_notice() {
            $nonce     = sanitize_text_field( $_POST['nonce'] );
            $notice_id = trim( sanitize_text_field( $_POST['noticeID'] ) );

            // Security Validate Nonce
            if ( ! wp_verify_nonce( $nonce, 'tacpp-ajax-nonce' ) ) {
                $response['success'] = false;
                $response['content'] = 'Security check failed';
                echo json_encode( $response );
                wp_die();
            }

            // Check Notice ID
            if ( empty( $notice_id ) ) {
                $response['success'] = false;
                $response['content'] = 'No notice ID';
                echo json_encode( $response );
                wp_die();
            }

            // Update hidden notices
            $this->update_seen_notices( $notice_id );

            $response['success'] = true;
            $response['content'] = 'Notice hidden';
            echo json_encode( $response );
            wp_die();

        }

        /**
         * Get an array of all seen notice IDs
         *
         * @return mixed
         */

        public function get_seen_notices() {
            $notices = (array) get_user_meta( get_current_user_id(),
                self::SEEN_NOTICES_OPTION_NAME, true );

            $notices = array_filter( $notices );

            return apply_filters( 'tacpp4_terms_conditions_get_seen_notices',
                $notices );
        }

        /**
         * Update seen notices in DB so they do not appear again
         *
         * @param $notice_id
         */
        public function update_seen_notices( $notice_id ) {
            $notice_id    = sanitize_text_field( $notice_id );
            $seen_notices = $this->get_seen_notices();

            // Add notice to hidden notices
            if ( ! in_array( $notice_id, $seen_notices ) ) {
                $seen_notices[] = $notice_id;
            }

            $seen_notices = array_filter( $seen_notices );

            // Update DB option
            update_user_meta(
                get_current_user_id(),
                self::SEEN_NOTICES_OPTION_NAME,
                $seen_notices
            );
        }

        /**
         * Returns an array of TACPP admin notices.
         *
         * @return mixed
         */
        public function get_rate_me_admin_notices() {
            $text = "<p>" . __( '<strong>Awesome!</strong> You have added <strong>[number] custom Terms and Conditions</strong> to your products using the <strong>Terms and Conditions Per Product</strong> plugin. Would you <strong>help us spread the word</strong> by giving it a <strong>5-star rating</strong> on WordPress?', 'terms-and-conditions-per-product' ) . "</p>";
            $text .= "<p>" . sprintf( __( '<a href="%s" target="_blank">Ok, you deserve it.</a>', 'terms-and-conditions-per-product' ), esc_html( 'https://wordpress.org/support/plugin/terms-and-conditions-per-product/reviews/#new-post' ) ) . "<br>";
            $text .= "<a href='#' class='close-notice'>" . __( 'Already did.', 'terms-and-conditions-per-product' ) . "</a></p>";

            $admin_notices = array(
                'tacpp-rate-me-notice' => array(
                    'text'     => $text,
                    'user_cap' => 'manage_options',
                ),
            );

            return apply_filters( 'tacpp4_terms_conditions_admin_notices_get_admin_notices',
                $admin_notices );
        }

        /**
         * Show Rate Me Admin notices.
         */
        public function show_rate_me_admin_notices() {
            global $wpdb;

            // How many times should we wait before showing the notice?
            $show_after_times = array( 5, 20, 50 );

                       // Get how many custom terms exist
            $query   = "SELECT COUNT(meta_id) FROM {$wpdb->postmeta} WHERE meta_key ='" . TACPP4_Terms_Conditions_Per_Product::$meta_key . "' AND meta_value != ''";
            $counter = $wpdb->get_var( $query );

            // Filter array to only show notices after certain number of terms.
            foreach ( $show_after_times as $key => $after_time ) {
				if ( $counter < $after_time ) {
					unset( $show_after_times[ $key ] );
                }
            }

            $admin_notices = $this->get_rate_me_admin_notices();

            if ( ! empty( $admin_notices ) ) {

                $seen_notices = $this->get_seen_notices();

                foreach ( $admin_notices as $notice_id => $notice ) {
                    foreach ( $show_after_times as $show_after_time ) {

                        $notice_id = $notice_id . '-' . $show_after_time;
                        $text      = str_replace( '[number]', $show_after_time, $notice['text'] );

                        // Skip if notice is already seen
                        if ( in_array( $notice_id, $seen_notices ) ) {
                            continue;
                        }

                        // Show the notice
                        $this->show_admin_notice( $text, $notice_id );
                        //break;
                    }

                }
            }
        }

    }

    new TACPP4_Terms_Conditions_Admin_Notices();
}
