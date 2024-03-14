<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Woo_Order_Chat {

    public function __construct() {
        if ( 'yes' != tochatbe_woo_order_button_option( 'status' ) ) {
            return;
        }

        add_action( 'admin_head', array( $this, 'dynamic_style' ) );
        add_action( 'admin_footer', array( $this, 'chat_popup' ) );
        add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'admin_order_data_after_billing_address' ), 20, 1 ); 

        // Order list button
        add_filter( 'manage_edit-shop_order_columns', array( $this, 'order_column' ) ); 
        add_action( 'manage_shop_order_posts_custom_column', array( $this, 'add_order_column_content' ) ); 

        add_action( 'wp_ajax_tochatbe_save_order_message', array( $this, 'save_order_message' ) );
        add_action( 'wp_ajax_nopriv_tochatbe_save_order_message', array( $this, 'save_order_message' ) );
    
        // Dashboard widget.
        add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
    }

    /**
     * Add Click to chat button in order page after billing address.
     *
     * @param WC_Order $order WooCommerce order.
     * @return void
     */
    public function admin_order_data_after_billing_address( $order ) {
        if ( ! $phone_number = $this->get_phone_number( $order ) ) {
            return;
        }
        
        $order_message = '';
        $order_status  = $order->get_status();

        if ( 'processing' === $order_status ) {
            $order_message = tochatbe_woo_order_button_option( 'pre_message_processing_order' );
        } else if ( 'cancelled' === $order_status ) {
            $order_message = tochatbe_woo_order_button_option( 'pre_message_canceled_order' );
        } else if ( 'completed' === $order_status ) {
            $order_message = tochatbe_woo_order_button_option( 'pre_message_completed_order' );
        } else {
            $woo_statuses = tochatbe_get_woo_order_statuses();

            if ( $woo_statuses ) {
                foreach ( $woo_statuses as $status => $label ) {
                    $status = str_replace( 'wc-', '', $status );
                    $status = str_replace( '-', '_', $status );

                    if ( str_replace( '-', '_', $order_status ) === $status ) {
                        $order_message = tochatbe_woo_order_button_option( 'pre_message_' . $status . '_order' );
                    }
                }
            }
        }

        // Apply order message placeholders.
        $order_message = $this->apply_order_placeholders( $order_message, $order );
        ?>
        <div 
            class="tochatbe-woo-order-btn" 
            data-tochat-order-billing-number="<?php echo $phone_number; // WPCS: XSS ok. ?>"
            data-tochat-order-message="<?php echo esc_textarea( $order_message ); ?>"
            data-tochat-order-id="<?php echo $order->get_id(); ?>"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
            </svg>
            <p>Click to chat</p>
        </div>
        <p><?php echo $this->get_last_order_message( $order->get_id() ) ?></p>
        <?php
    }

    /**
     * Display chat popup.
     * 
     * @return void
     */
    public function chat_popup() {
        if ( 'shop_order' != get_post_type() ) {
            return;
        }
        ?>
        <div class="tochatbe-woo-order-popup">
            <form action="#" method="post">
                <input type="hidden" name="order_id" value="<?php echo get_the_ID(); ?>">
                <div>
                    <label>Phone Number</label>
                    <input type="number" step="1" name="number" value="">
                    <p class="desc">Please add the country code before sending the message. Edit the user profile to keep the country code in the number. Just add numbers, no + sign.</p>
                </div>
                <div>
                    <label>Message</label>
                    <textarea name="message"></textarea>
                </div>
                <div>
                    <input type="submit" value="SEND">
                    <a href="javascript:;">CANCEL</a>
                </div>
            </form>
            <p>
                <a href="https://tochat.be/click-to-chat/2020/11/08/messages-to-recover-clients-from-wordpress-with-whatsapp/" tagret="_blank">Check out messages you can use to recover your clients.</a>
            </p>
        </div>
        <script>
            jQuery( document ).ready( function() {
                // Open popup
                jQuery( '.tochatbe-woo-order-btn' ).on( 'click', function( e ) {
                    e.preventDefault();
                    
                    var contactNumber = jQuery( this ).data( 'tochat-order-billing-number' );
                    var orderID       = jQuery( this ).data( 'tochat-order-id' );
                    var orderMessage  = jQuery( this ).data( 'tochat-order-message' );
                    
                    jQuery( '.tochatbe-woo-order-popup' ).find( '[name="number"]' ).val( contactNumber );
                    jQuery( '.tochatbe-woo-order-popup' ).find( '[name="order_id"]' ).val( orderID );
                    jQuery( '.tochatbe-woo-order-popup' ).find( '[name="message"]' ).val( orderMessage );
                    jQuery( '.tochatbe-woo-order-popup' ).show();
                } );

                // Close popup
                jQuery( '.tochatbe-woo-order-popup form a' ).on( 'click', function() {
                    jQuery( '.tochatbe-woo-order-popup' ).hide();
                } );

                // Send message
                jQuery( '.tochatbe-woo-order-popup form' ).on( 'submit', function( event )  {
                    event.preventDefault();
                    
                    var number  = jQuery( '[name="number"]' ).val();
                    var message = jQuery( '[name="message"]' ).val();
                    var ordeID = jQuery( '[name="order_id"]' ).val();

                    jQuery.ajax( {
                        url: tochatbeAdmin.ajax_url,
                        type: 'post',
                        data: {
                            'action': 'tochatbe_save_order_message',
                            'message' : message,
                            'security_token': tochatbeAdmin.security_token,
                            'order_id': ordeID
                        }
                    } );

                    window.open( 'https://api.whatsapp.com/send?phone=' + number + '&text=' + message + '' );

                    jQuery( '.tochatbe-woo-order-popup' ).hide();
                } );

            } );
        </script>
        <?php
    }

    /**
     * Add style.
     *
     * @return void
     */
    public function dynamic_style() {
        if ( 'shop_order' != get_post_type() ) {
            return;
        }
        ?>
        <style>
            .tochatbe-woo-order-btn {
                background-color: #25D366;
                color: #fff;
                padding: 5px 15px;
                display: inline-flex;
                border-radius: 4px;
                cursor: pointer !important;
            }

            .tochatbe-woo-order-btn svg {
                width: 18px;
                margin-right: 10px;
            }

            .tochatbe-woo-order-btn p {
                color: #fff !important;
                padding: 0;
                margin: 0;
                /* font-weight: 700; */
                font-size: 16px;
            }

            .tochatbe-woo-order-popup {
                width: 400px;
                max-width: 98%;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate( -50%, -50% );
                background-color: #fff;
                z-index: 9999;
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-shadow: 0 0 30px rgba( 0,0,0,.2 );
                display: none;
            }

            .tochatbe-woo-order-popup form > div {
                margin-bottom: 10px;
            }

            .tochatbe-woo-order-popup form > div > label {
                display: block;
                margin-bottom: 5px;
            }

            .tochatbe-woo-order-popup form input[type="number"],
            .tochatbe-woo-order-popup form textarea {
                width: 100%;
            }

            .tochatbe-woo-order-popup form textarea {
                height: 100px;
            }

            .tochatbe-woo-order-popup form input[type="submit"] {
                padding: 5px 10px;
                background-color: #25D366;
                border: 1px solid #25D366;
                color: #fff;
                font-weight: 700;
                cursor: pointer;
            }

            .tochatbe-woo-order-popup form a {
                text-decoration: none;
                padding: 5px 10px;
                color: #333 !important;
                font-weight: 700;
                cursor: pointer;
            }

            .tochatbe-woo-order-popup form .desc {
                color: #999;
                padding: 0;
                margin: 0;
                font-style: italic;
            }
        </style>
        <?php
    }

    public function order_column( $columns ) {
        $new_columns = array();

        foreach ( $columns as $column_name => $column_info ) {

            $new_columns[ $column_name ] = $column_info;

            if ( 'order_status' === $column_name ) {
                $new_columns['tochatbe_contact'] = 'Contact';
            }
        }

        return $new_columns;
    }

    public function add_order_column_content( $column ) {
        global $post;

        if ( 'tochatbe_contact' === $column ) {
            $order    = wc_get_order( $post->ID );

            $order_message = '';
            $order_status  = $order->get_status();

            if ( 'processing' === $order_status ) {
                $order_message = tochatbe_woo_order_button_option( 'pre_message_processing_order' );
            } else if ( 'cancelled' === $order_status ) {
                $order_message = tochatbe_woo_order_button_option( 'pre_message_canceled_order' );
            } else if ( 'completed' === $order_status ) {
                $order_message = tochatbe_woo_order_button_option( 'pre_message_completed_order' );
            } else {
                $woo_statuses = tochatbe_get_woo_order_statuses();

                if ( $woo_statuses && is_array( $woo_statuses ) ) {
                    foreach ( $woo_statuses as $status => $label ) {
                        $status = str_replace( 'wc-', '', $status );
                        $status = str_replace( '-', '_', $status );

                        if ( str_replace( '-', '_', $order_status ) === $status ) {
                            $order_message = tochatbe_woo_order_button_option( 'pre_message_' . $status . '_order' );
                        }
                    }
                }
            }

            // Apply order message placeholders.
            $order_message = $this->apply_order_placeholders( $order_message, $order );
            ?>
            <div 
                class="tochatbe-woo-order-btn no-link" 
                data-tochat-order-billing-number="<?php echo $this->get_phone_number( $order ); ?>"
                data-tochat-order-message="<?php echo esc_textarea( $order_message ); ?>"
                data-tochat-order-id="<?php echo $order->get_id(); ?>"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
                </svg>
                <p>Click to chat</p>
            </div>
            <p style="max-width: 300px"><?php echo $this->get_last_order_message( $post->ID ) ?></p>
            <?php
        }
    }

    /**
     * Save message to WooCommerce order note.
     *
     * @since 1.1.8
     * 
     * @return void
     */
    public function save_order_message() {
        check_ajax_referer( 'tochatbe_admin_security_token', 'security_token' );

        $message  = sanitize_textarea_field( wp_unslash( $_POST['message'] ) );
        $order_id = sanitize_text_field( wp_unslash( $_POST['order_id'] ) );

        $order = wc_get_order(  $order_id );

        $note = '<strong>WhatsApp:</strong> ' . $message;

        // Add the note
        $order->add_order_note( $note );
       
        wp_die();
    }

    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'tochatbe_dashboard_recent_orders_widget',
            'TOCHAT.BE Recent Orders',
            array( $this, 'dashboard_recent_orders' )
        ); 
    }

    public function dashboard_recent_orders() {
        ?>
            <style>
                .tochatbe-dashboard-table {
                    table-layout: fixed;
                    width: 100%;
                }

                .tochatbe-dashboard-table th,
                .tochatbe-dashboard-table td {
                    text-align: left;
                    padding-bottom: 10px;
                }

                .tochatbe-woo-order-btn {
                    background-color: #25D366;
                    color: #fff;
                    padding: 5px 10px;
                    display: inline-flex;
                    border-radius: 2px;
                    cursor: pointer !important;
                }

                .tochatbe-woo-order-btn svg {
                    width: 14px;
                    margin-right: 5px;
                }

                .tochatbe-woo-order-btn p {
                    color: #fff !important;
                    padding: 0;
                    margin: 0;
                    font-size: 14px;
                }

                .tochatbe-woo-order-popup {
                    width: 400px;
                    max-width: 98%;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate( -50%, -50% );
                    background-color: #fff;
                    z-index: 9999;
                    padding: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-shadow: 0 0 30px rgba( 0,0,0,.2 );
                    display: none;
                }

                .tochatbe-woo-order-popup form > div {
                    margin-bottom: 10px;
                }

                .tochatbe-woo-order-popup form > div > label {
                    display: block;
                    margin-bottom: 5px;
                }

                .tochatbe-woo-order-popup form input[type="number"],
                .tochatbe-woo-order-popup form textarea {
                    width: 100%;
                }

                .tochatbe-woo-order-popup form textarea {
                    height: 100px;
                }

                .tochatbe-woo-order-popup form input[type="submit"] {
                    padding: 5px 10px;
                    background-color: #25D366;
                    border: 1px solid #25D366;
                    color: #fff;
                    font-weight: 700;
                    cursor: pointer;
                }

                .tochatbe-woo-order-popup form a {
                    text-decoration: none;
                    padding: 5px 10px;
                    color: #333 !important;
                    font-weight: 700;
                    cursor: pointer;
                }

                .tochatbe-woo-order-popup form .desc {
                    color: #999;
                    padding: 0;
                    margin: 0;
                    font-style: italic;
                }
            </style>
            <p>Send a thank you note or reminder.</p>

            <?php
                $args = array(
                    'post_type'      => 'shop_order',
                    'posts_per_page' => '10',
                    'post_status'    => 'any'
                );
                
                $orders = get_posts( $args );
            ?>

            <?php if ( $orders ) : ?>
                <table class="tochatbe-dashboard-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $orders as $order ) : ?>
                        <?php $order = wc_get_order( $order->ID ); ?>
                        <?php
                            $order_message = '';
                            $order_status  = $order->get_status();
                
                            if ( 'processing' === $order_status ) {
                                $order_message = tochatbe_woo_order_button_option( 'pre_message_processing_order' );
                            } else if ( 'cancelled' === $order_status ) {
                                $order_message = tochatbe_woo_order_button_option( 'pre_message_canceled_order' );
                            } else if ( 'completed' === $order_status ) {
                                $order_message = tochatbe_woo_order_button_option( 'pre_message_completed_order' );
                            } else {
                                $woo_statuses = tochatbe_get_woo_order_statuses();

                                if ( $woo_statuses && is_array( $woo_statuses ) ) {
                                    foreach ( $woo_statuses as $status => $label ) {
                                        $status = str_replace( 'wc-', '', $status );
                                        $status = str_replace( '-', '_', $status );

                                        if ( str_replace( '-', '_', $order_status ) === $status ) {
                                            $order_message = tochatbe_woo_order_button_option( 'pre_message_' . $status . '_order' );
                                        }
                                    }
                                }
                            }

                            // Apply order message placeholders.
                            $order_message = $this->apply_order_placeholders( $order_message, $order );
                        ?>
                        <tr>
                            <td><?php echo $order->get_formatted_billing_full_name(); ?></td>
                            <td><?php echo $order->get_status(); ?></td>
                            <td>
                                <div 
                                    class="tochatbe-woo-order-btn" 
                                    data-tochat-order-billing-number="<?php echo $this->get_phone_number( $order ); // WPCS: XSS ok. ?>"
                                    data-tochat-order-message="<?php echo esc_textarea( $order_message ); ?>"
                                    data-tochat-order-id="<?php echo $order->get_id(); ?>"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
                                    </svg>
                                    <p>Click to chat</p>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="tochatbe-woo-order-popup">
                    <form action="#" method="post">
                        <input type="hidden" name="order_id" value="<?php echo get_the_ID(); ?>">
                        <div>
                            <label>Phone Number</label>
                            <input type="number" step="1" name="number" value="">
                            <p class="desc">Please add the country code before sending the message. Edit the user profile to keep the country code in the number. Just add numbers, no + sign.</p>
                        </div>
                        <div>
                            <label>Message</label>
                            <textarea name="message"></textarea>
                        </div>
                        <div>
                            <input type="submit" value="SEND">
                            <a href="javascript:;">CANCEL</a>
                        </div>
                    </form>
                    <p>
                        <a href="https://tochat.be/click-to-chat/2020/11/08/messages-to-recover-clients-from-wordpress-with-whatsapp/" tagret="_blank">Check out messages you can use to recover your clients.</a>
                    </p>
                </div>
                <script>
                    jQuery( document ).ready( function() {
                        // Open popup
                        jQuery( '.tochatbe-woo-order-btn' ).on( 'click', function( e ) {
                            e.preventDefault();
                            
                            var contactNumber = jQuery( this ).data( 'tochat-order-billing-number' );
                            var orderID       = jQuery( this ).data( 'tochat-order-id' );
                            var orderMessage  = jQuery( this ).data( 'tochat-order-message' );
                            
                            jQuery( '.tochatbe-woo-order-popup' ).find( '[name="number"]' ).val( contactNumber );
                            jQuery( '.tochatbe-woo-order-popup' ).find( '[name="order_id"]' ).val( orderID );
                            jQuery( '.tochatbe-woo-order-popup' ).find( '[name="message"]' ).val( orderMessage );
                            jQuery( '.tochatbe-woo-order-popup' ).show();
                        } );

                        // Close popup
                        jQuery( '.tochatbe-woo-order-popup form a' ).on( 'click', function() {
                            jQuery( '.tochatbe-woo-order-popup' ).hide();
                        } );

                        // Send message
                        jQuery( '.tochatbe-woo-order-popup form' ).on( 'submit', function( event )  {
                            event.preventDefault();
                            
                            var number  = jQuery( '[name="number"]' ).val();
                            var message = jQuery( '[name="message"]' ).val();
                            var ordeID = jQuery( '[name="order_id"]' ).val();

                            jQuery.ajax( {
                                url: tochatbeAdmin.ajax_url,
                                type: 'post',
                                data: {
                                    'action': 'tochatbe_save_order_message',
                                    'message' : message,
                                    'security_token': tochatbeAdmin.security_token,
                                    'order_id': ordeID
                                }
                            } );

                            window.open( 'https://api.whatsapp.com/send?phone=' + number + '&text=' + message + '' );

                            jQuery( '.tochatbe-woo-order-popup' ).hide();
                        } );

                    } );
                </script>
            <?php else : ?>
                <p style="text-align: center">No order found!</p>
            <?php endif; ?>
        <?php
    }

    /**
     * Get order last message with formatting.
     *
     * @param int $order_id Order ID.
     * @return string
     */
    protected function get_last_order_message( $order_id ) {
        $notes = $this->get_private_order_notes( $order_id );

        if ( ! $notes ) {
            return;
        }

        foreach ( $notes as $note ) {
            $content     = $note['note_content'];
            $timestamp   = strtotime( $note['note_date'] );
            $time_in_ago = human_time_diff( $timestamp, current_time( 'U' ) );

            if ( strpos( $content, 'WhatsApp' ) ) {
                return sprintf( 'Last message sent %s ago: <br />%s', $time_in_ago, $content );
            }
        }
    }

    /**
     * Get WooCommerce private notes.
     *
     * @param int $order_id Order ID.
     * @return array
     */
    protected function get_private_order_notes( $order_id ){
        global $wpdb;
    
        $table_perfixed = $wpdb->prefix . 'comments';
        $results        = $wpdb->get_results(
            "SELECT *
            FROM $table_perfixed
            WHERE  comment_post_ID = $order_id
            AND  comment_type LIKE  'order_note'
            ORDER BY comment_ID DESC"
        );
    
        foreach ( $results as $note ){
            $order_note[]  = array(
                'note_id'      => $note->comment_ID,
                'note_date'    => $note->comment_date,
                'note_author'  => $note->comment_author,
                'note_content' => $note->comment_content,
            );
        }

        return $order_note;
    }

    /**
     * Apply order placeholders.
     * 
     * @since 1.2.2
     *
     * @param string   $message Message.
     * @param WC_Order $order   WooCommerce order.
     * @return string
     */
    protected function apply_order_placeholders( $message, $order ) {
        $placeholders = array(
            '{full_name}'  => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            '{first_name}' => $order->get_billing_first_name(),
            '{last_name}'  => $order->get_billing_last_name(),
            '{order_id}'   => $order->get_id(),
        );

        return str_replace( array_keys( $placeholders ), array_values( $placeholders ), $message );
    }

    /**
     * Get phone number.
     * 
     * @since 1.2.2
     *
     * @param WC_Order $order WooCommerce order.
     * @return string
     */
    protected function get_phone_number( $order ) {
        $phone_number = '';

        if ( $order->get_billing_phone() ) {
            return $order->get_billing_phone();
        } else if ( $order->get_shipping_phone() ) {
            return $order->get_shipping_phone();
        } else {
            $user_id = $order->get_customer_id();

            if ( $user_id ) {
                $user_billing_phone  = get_user_meta( $user_id, 'billing_phone', true );
                $user_shipping_phone = get_user_meta( $user_id, 'shipping_phone', true );
    
                if ( $user_billing_phone ) {
                    return $user_billing_phone;
                } else if ( $user_shipping_phone ) {
                    return $user_shipping_phone;
                }
            }
        }

        return $phone_number;
    }

}

new TOCHATBE_Admin_Woo_Order_Chat;