<?php
/**
 * EventPrime Notification service class.
 */

defined( 'ABSPATH' ) || exit;

class EventM_Notification_Service {
    
    /**
     * Send booking confirmation email
     */
    public static function booking_confirmed( $booking_id ) {
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        $booking = $booking_controller->load_booking_detail($booking_id);
        $order_info = isset($booking->em_order_info) ? $booking->em_order_info : array();
        $tickets = isset($order_info['tickets']) ? $order_info['tickets'] : array();
        $sub_total = 0;
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        $mail_body = ( ! empty( $global_setting->booking_confirmed_email ) ? $global_setting->booking_confirmed_email : '' );
        $subject = ( ! empty( $global_setting->booking_confirm_email_subject ) ? $global_setting->booking_confirm_email_subject : esc_html__( 'Your booking is confirmed!', 'eventprime-event-calendar-management' ) );
        self::configure_mail();
        
        $booking_user_email = $booking_user_name = $to = '';
        $booking_user_phone = $user_first_name = $user_last_name = 'N/A';
        $user_id = isset( $booking->em_user ) ? (int)$booking->em_user : 0;
        if( $user_id ) {
            $user = get_userdata( $user_id );
            $booking_user_email = $to = $user->user_email;
            $booking_user_name = ( ! empty( $user->display_name) ) ? $user->display_name : $user->user_nicename;
            $user_first_name = get_user_meta( $user_id, 'first_name', true );
            $user_last_name = get_user_meta( $user_id, 'last_name', true );
            $booking_user_phone = get_user_meta($user_id, 'phone', true);
        }
        if( empty( $user_id ) && ep_enabled_guest_booking() ) {
            $booking_user_email = $to = isset($order_info['user_email']) ? $order_info['user_email'] :'';
            $booking_user_name = isset($order_info['user_name']) ? $order_info['user_name'] : '';
            $booking_user_phone = (isset($order_info['user_phone']) && !empty($order_info['user_phone']) ? $order_info['user_phone'] : 'N/A');
        }

        // if admin email from set in global settings
        if( isset( $global_setting->ep_admin_email_from ) && ! empty( $global_setting->ep_admin_email_from ) ){
            $admin_email = $global_setting->ep_admin_email_from;
            $from = get_bloginfo('name') . '<' . $admin_email . '>';
        }else{
            $from = get_bloginfo('name') . '<' . get_bloginfo('admin_email') . '>';
        }
        $headers[] = 'From: ' . $from;
        if( ! empty( $global_setting->send_booking_confirm_email ) && empty( $global_setting->disable_frontend_email ) ) {
            $gcal_link = self::gcal_link($booking->event_data, $booking->event_data->venue_details);
            $iCal_link = self::iCal_link($booking->event_data, $booking->event_data->venue_details);

            $mail_body = str_replace( "#ID", $booking_id, $mail_body );
            $mail_body = str_replace( "Event Name", $booking->em_name, $mail_body );
            if( isset( $booking->event_data->venue_details ) ) {
                $venue = $booking->event_data->venue_details;
                $mail_body = str_replace( "Venue Name", empty( $venue ) ? '' : $venue->name, $mail_body );
                $mail_body = str_replace( "Event Venue", empty( $venue ) ? '' : $venue->em_address, $mail_body );
            }
            if( isset( $booking->event_data->em_start_date ) ) {
                $event_date_time = esc_html( ep_timestamp_to_date( $booking->event_data->em_start_date, ep_get_datepicker_format(), 1 ) );
                if( ! empty( $booking->event_data->em_start_time ) ) {
                   $event_date_time .= ', ' . esc_html( ep_convert_time_with_format( $booking->event_data->em_start_time ) );
                }
                $mail_body = str_replace( "Event Date Time", empty( $event_date_time ) ? '' : $event_date_time, $mail_body );
            }
            // order item data
            $order_item_html = '';
            $order_item_style = "text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word;color:#737373;padding:12px";
            $ticket_sub_total = $offers = 0;
            // add ticket data
            foreach( $tickets as $ticket ) {
                if( ! empty( $ticket->offer ) ) {
                    $offers += $ticket->offer;
                }
                $order_item_html .= '<tr>';
                    $additional_fees = array();
                    if( isset( $ticket->additional_fee ) ) {
                        foreach( $ticket->additional_fee as $fees ) {
                            $additional_fees[] = $fees->label.' ('.ep_price_with_position($fees->price * $ticket->qty).')';
                        }
                    }
                    if( ! empty( $additional_fees ) ) {
                        $fees = implode(' | ', $additional_fees );
                    } else{
                        $fees = '';
                    }
                    $order_item_html .= '<td style="'.$order_item_style.'"><span>'.$ticket->name.'</span></td>';
                    $order_item_html .= '<td style="'.$order_item_style.'"><span>'.ep_price_with_position($ticket->price).' ' .$fees.'</span></td>';
                    $order_item_html .= '<td style="'.$order_item_style.'"><span>'.$ticket->qty.'</span></td>';
                    $order_item_html .= '<td style="'.$order_item_style.'"><span>'.ep_price_with_position( $ticket->subtotal ).'</span></td>';
                    
                    $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;
                $order_item_html .= '</tr>';
            }

            // add offers in discount
            if( ! empty( $offers ) ) {
                $order_info['discount'] += $offers;
            }

            $sub_total = $ticket_sub_total + $order_info['event_fixed_price'];
            $mail_body = str_replace( "<td>(order_item_data)</td>", $order_item_html, $mail_body );
                
            $mail_body = str_replace( "$(Discount)", ep_price_with_position( isset( $order_info['discount'] ) ? $order_info['discount'] : 0 ), $mail_body );
            $mail_body = str_replace( "$(Fixed Event Fee)", ep_price_with_position( $order_info['event_fixed_price'] ), $mail_body );
            $mail_body = str_replace( "$(Order Total)", ep_price_with_position( $order_info['booking_total'] ), $mail_body );
            $payment_gateway = ( ! empty( $booking->em_payment_method ) ? ucfirst( $booking->em_payment_method ) : 'N/A' );
            $mail_body = str_replace( "$(Payment Gateway)", ucfirst( $booking->em_payment_method ), $mail_body );
            $mail_body = str_replace( "$(Booking Status)", ucfirst( $booking->em_status ), $mail_body );
            $payment_note = ($payment_gateway == 'Offline') ? '' : 'N/A';
            $mail_body = str_replace( "$(Payment Note)", $payment_note, $mail_body );
            $mail_body = str_replace( "(User Email)", $to, $mail_body );
            $mail_body = str_replace( "{{gcal_link}}", $gcal_link, $mail_body );
            $mail_body = str_replace( "{{iCal_link}}", $iCal_link, $mail_body );

            $lastFoot = explode( '</tfoot>', $mail_body );
            $lastFootUpdate = $lastFoot[0];
            // attendee name data
            if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {
                $booking_attendees_field_labels = array();
                $i = 1;
                $attendee_name_html = '';
                foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                    $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
                    foreach( $attendee_data as $booking_attendees ) {
                        $attendee_name_html .= '<tr><th colspan="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">'.esc_html__( 'Attendees '.$i, 'eventprime-event-calendar-management' ).' </th>';
                            $attendee_name_html .= '<td colspan ="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">';
                                $booking_attendees_val = array_values( $booking_attendees );
                                foreach( $booking_attendees_field_labels as $label_keys => $labels ){
                                    $formated_val = ep_get_slug_from_string( $labels );
                                    $at_val = '---';
                                    foreach( $booking_attendees_val as $baval ) {
                                        if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                            $at_val = $baval[$formated_val];
                                            break;
                                        }
                                    }
                                    $attendee_name_html .= '<span>'. esc_html__( $labels, 'eventprime-event-calendar-management' ) .' : '. $at_val .' </span><br/>';
                                }
                            $attendee_name_html .= '</td>';
                        $attendee_name_html .= '</tr>';
                        $i++;
                    }
                }
                $lastFootUpdate .= $attendee_name_html;
            }
            $lastFootUpdate = apply_filters( 'event_magic_booking_confirmed_footer_contnent', $lastFootUpdate, $booking );
            $mail_body = $lastFootUpdate . '</tfoot>' . $lastFoot[1];
            // attachments
            $attachments = array();
            if( class_exists('EP_Offline') && $booking->em_payment_method == "offline" && isset($global_setting->send_ticket_on_payment_received) && $global_setting->send_ticket_on_payment_received == 1 ){
                $attachments = array();
            } else{
                $attachments = apply_filters( 'event_magic_booking_confirmed_notification_attachments', $attachments, $booking );
            }
            // Send email to user
            $sent = wp_mail( $to, $subject, $mail_body, $headers, $attachments );
            if ( count( $attachments ) != 0 ) {
                foreach ( $attachments as $pdf_url ) {
                    unlink( $pdf_url ); 
                }
            }
        }
        
        /* send Mail to Admin */   
        if( empty( $global_setting->disable_admin_email ) && $global_setting->send_admin_booking_confirm_email == 1 ) {
            $mail_body = $global_setting->admin_booking_confirmed_email;
            if( isset( $global_setting->ep_admin_email_to ) && ! empty( $global_setting->ep_admin_email_to ) ){
                $to = $global_setting->ep_admin_email_to; 
            }else{
                $to = get_option('admin_email'); 
            }
            $subject = ( ! empty( $global_setting->admin_booking_confirmed_email_subject ) ? $global_setting->admin_booking_confirmed_email_subject : esc_html__( 'New event booking', 'eventprime-event-calendar-management' ) );
            $mail_body = str_replace( "(user_email)", $booking_user_email, $mail_body );
            $mail_body = str_replace( "(event_name)", $booking->em_name, $mail_body );
            $mail_body = str_replace( "(event_date)", empty($event_date_time) ? '' : $event_date_time, $mail_body );
            $mail_body = str_replace( "(booking_id)", $booking_id, $mail_body );
            $booking_url = admin_url( "post.php?action=edit&post=".$booking_id );
            $view_order_url = '<a href="'.esc_url( $booking_url ).'" target="_blank">' . esc_html__('View Order', 'eventprime-event-calendar-management') . '</a>';
            $mail_body = str_replace( "(view_order)", $view_order_url, $mail_body );
            $event_date = $booking->event_data->em_start_date;
            $booking_date_time = esc_html( ep_timestamp_to_datetime( $booking->em_date ) );
            $mail_body = str_replace( "(booking_date)", empty($booking_date_time) ? '' : $booking_date_time, $mail_body );
            $mail_body = str_replace( "(subtotal)", ep_price_with_position($sub_total), $mail_body );
            $mail_body = str_replace( "(discount)", ep_price_with_position(isset($order_info['discount']) ? $order_info['discount'] : 0), $mail_body );
            $mail_body = str_replace( "(order_total)", ep_price_with_position($order_info['booking_total']), $mail_body );
            $payment_gateway = isset($booking->em_payment_method) ? ucfirst($booking->em_payment_method) : 'N/A';
            $mail_body = str_replace( "(payment_method)", $payment_gateway, $mail_body );
            $mail_body = str_replace( "(user_name)", $booking_user_name, $mail_body );
            $mail_body = str_replace( "(user_first_name)", $user_first_name, $mail_body );
            $mail_body = str_replace( "(user_last_name)", $user_last_name, $mail_body );
            $mail_body = str_replace( "(user_phone)", $booking_user_phone, $mail_body );
            
            if(isset($global_setting->admin_booking_confirm_email_attendees) && !empty($global_setting->admin_booking_confirm_email_attendees)){
                $lastFoot = explode( '</tbody>', $mail_body );
                $lastFootUpdate = $lastFoot[1];    
                // attendee name data
                if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {
                    $booking_attendees_field_labels = array();
                    $i = 1;
                    $attendee_name_html = '';
                    foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                        $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
                        foreach( $attendee_data as $booking_attendees ) {
                            $attendee_name_html .= '<tr><th style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">'.esc_html__( 'Attendees '.$i, 'eventprime-event-calendar-management' ).' </th>';
                                $attendee_name_html .= '<td style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">';
                                    $booking_attendees_val = array_values( $booking_attendees );
                                    foreach( $booking_attendees_field_labels as $label_keys => $labels ){
                                        $formated_val = ep_get_slug_from_string( $labels );
                                        $at_val = '---';
                                        foreach( $booking_attendees_val as $baval ) {
                                            if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                                $at_val = $baval[$formated_val];
                                                break;
                                            }
                                        }
                                        $attendee_name_html .= '<span>'. esc_html__( $labels, 'eventprime-event-calendar-management' ) .' : '. $at_val .' </span><br/>';
                                    }
                                $attendee_name_html .= '</td>';
                            $attendee_name_html .= '</tr>';
                            $i++;
                        }
                    }
                    $lastFoot[1] .= $attendee_name_html;
                }
            $mail_body = implode('', $lastFoot);
            
            }

            //Add CC to admin emails
            $mail_cc = ep_get_global_settings('admin_booking_confirmed_email_cc');
            if( ! empty( $mail_cc ) ) {
                $mails = explode( ",", $mail_cc );
                if( ! empty( $mails ) ) {
                    foreach( $mails as $mail ) {
                        $headers[] = 'CC: '.$mail;
                    }
                }
            }
        
            wp_mail( $to, $subject, $mail_body, $headers);
        }
    }
    
    /**
     * Send payment refund email
     */
    public static function booking_refund( $booking_id ) {
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        $booking = $booking_controller->load_booking_detail($booking_id);
        $order_info = isset($booking->em_order_info) ? $booking->em_order_info : array();
        $tickets = isset($order_info['tickets']) ? $order_info['tickets'] : array();
        
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        $mail_body = ( ! empty( $global_setting->booking_refund_email ) ? $global_setting->booking_refund_email : '' );
        $subject = ( ! empty( $global_setting->booking_refund_email_subject ) ? $global_setting->booking_refund_email_subject : esc_html__( 'Refund for your booking', 'eventprime-event-calendar-management' ) );
        self::configure_mail();
       
        $booking_user_email = $booking_user_name = $to = '';
        $user_id = isset($booking->em_user) ? (int) $booking->em_user : 0;
        if( $user_id ) {
            $user = get_userdata( $user_id );
            $booking_user_email = $to = $user->user_email;
            $booking_user_name = $user->display_name;
        }
        if(empty($user_id) && ep_enabled_guest_booking()){
            $booking_user_email = $to = isset( $order_info['user_email'] ) ? $order_info['user_email'] : '';
        }
        $from = get_bloginfo('name') . '<' . get_bloginfo('admin_email') . '>';
        $headers[] = 'From: ' . $from;
        if( ! empty( $global_setting->send_booking_refund_email ) && empty( $global_setting->disable_frontend_email ) ) {
            $mail_body = isset( $booking->order_info['seat_sequences'] ) ? str_replace( "(Seat No.)", implode( ',', $booking->order_info['seat_sequences'] ), $mail_body ) : str_replace( "(Seat No.)", "Standing Event", $mail_body );
            $mail_body = str_replace( "ID",$booking_id, $mail_body) ;
            $mail_body = str_replace( "Event Name", $booking->em_name, $mail_body ) ;
            if( isset($booking->event_data->venue_details ) ) {
                $venue = $booking->event_data->venue_details;
                $mail_body = str_replace( "Venue Name", empty( $venue ) ? '' : $venue->name, $mail_body ) ;
                $mail_body = str_replace( "Event Venue", empty( $venue ) ? '' : $venue->em_address, $mail_body ) ;
            }
            if( isset( $booking->event_data->em_start_date ) ) {
                $event_date_time = esc_html( ep_timestamp_to_date( $booking->event_data->em_start_date, ep_get_datepicker_format(), 1 ) );
                    if( ! empty( $booking->event_data->em_start_time ) ) {
                    $event_date_time .= ', ' . esc_html( ep_convert_time_with_format( $booking->event_data->em_start_time ) );
                }
                $mail_body = str_replace( "Event Date Time",empty($event_date_time) ? '' : $event_date_time, $mail_body) ;
            }
            $ticket_sub_total = $total_qty = 0;
            foreach( $tickets as $ticket ){
                $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;
                $total_qty = $total_qty + $ticket->qty;
            }
        
            $mail_body = str_replace( "$(Subtotal)", ep_price_with_position( isset($order_info['booking_total']) ? $order_info['booking_total'] : 0 ), $mail_body );
            $mail_body = str_replace( "(Quantity)", $total_qty, $mail_body );
            $mail_body = str_replace( "$(Price)", ep_price_with_position( $ticket_sub_total ), $mail_body );
            $mail_body = str_replace( "$(Discount)", ep_price_with_position( isset( $order_info['discount']) ? $order_info['discount'] : 0 ), $mail_body );
            $mail_body = str_replace( "$(Fixed Event Fee)", ep_price_with_position( $order_info['event_fixed_price'] ), $mail_body );
            $lastFoot = explode( '</tfoot>', $mail_body );
            $lastFootUpdate = $lastFoot[0];
            if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {
                $booking_attendees_field_labels = array();
                $i = 1;
                $attendee_name_html ='';
                foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                    $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
                    foreach( $attendee_data as $booking_attendees ) {
                        $attendee_name_html .= '<tr><th colspan="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">'.esc_html__( 'Attendees '.$i, 'eventprime-event-calendar-management' ).' </th>';
                        $attendee_name_html .= '<td colspan ="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">';
                        $booking_attendees_val = array_values( $booking_attendees );
                        foreach( $booking_attendees_field_labels as $label_keys => $labels ){
                            $formated_val = ep_get_slug_from_string( $labels );
                            $at_val = '---';
                            foreach( $booking_attendees_val as $baval ) {
                                if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                    $at_val = $baval[$formated_val];
                                    break;
                                }
                            }
                            $attendee_name_html .= '<span>'. esc_html__( $labels, 'eventprime-event-calendar-management' ) .' : '. $at_val .' </span><br/>';
                        }
                        $attendee_name_html .= '</td>';
                        $attendee_name_html .= '</tr>';
                        $i++;
                    }
                }
                $lastFootUpdate .=$attendee_name_html;
            }
            $lastFootUpdate = apply_filters( 'event_magic_booking_confirmed_footer_contnent', $lastFootUpdate, $booking );
            $mail_body = $lastFootUpdate . '</tfoot>' . $lastFoot[1];

            // Send to user
            $sent = wp_mail( $to, $subject, $mail_body, $headers );
        }

        //Admin notification
        //Add CC to admin emails
        if( empty( $global_setting->disable_admin_email ) ) {
            $mail_cc = ep_get_global_settings('booking_refund_email_cc');
            if( ! empty( $mail_cc ) ) {
                $mails = explode( ",", $mail_cc );
                if( ! empty( $mails ) ) {
                    foreach( $mails as $mail ) {
                        $headers[] = 'CC: '.$mail;
                    }
                }
            }
            $admin_email = get_option('admin_email');  
            $to = $admin_email; 
            $subject = sprintf(esc_html__( 'Booking Refund on Booking ID# %d', 'eventprime-event-calendar-management'), $booking_id );        
            $body = sprintf(esc_html__( 'A refund of %s has been issued to booking #%d for %s', 'eventprime-event-calendar-management'), ep_price_with_position( $ticket_sub_total ), $booking_id, $booking->em_name );
        
            wp_mail( $to, $subject, $body, $headers );
        }
    }

    /**
     * Send booking pending email
     */
    public static function booking_pending( $booking_id ) {
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        $booking = $booking_controller->load_booking_detail($booking_id);
        $order_info = isset($booking->em_order_info) ? $booking->em_order_info : array();
        $tickets = isset($order_info['tickets']) ? $order_info['tickets'] : array();
        
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        $mail_body = isset($global_setting->booking_pending_email) ? $global_setting->booking_pending_email : '';
        $subject = isset($global_setting->booking_pending_email_subject) ? $global_setting->booking_pending_email_subject : esc_html__( 'Your payment is pending', 'eventprime-event-calendar-management' );
        self::configure_mail();
        
        $booking_user_email = $booking_user_name = $to = '';
        $user_id = isset($booking->em_user) ? (int) $booking->em_user : 0;
        if($user_id){
            $user = get_userdata($user_id);
            $booking_user_email = $to = $user->user_email;
            $booking_user_name = $user->display_name;
        }
        if(empty($user_id) && ep_enabled_guest_booking()){
            $booking_user_email = $to = isset($order_info['user_email']) ? $order_info['user_email'] :'';
        }
        $from = get_bloginfo('name') . '<' . get_bloginfo('admin_email') . '>';
        $headers[] = 'From: ' . $from;
        if( ! empty( $global_setting->send_booking_pending_email ) && empty( $global_setting->disable_frontend_email ) ) {
            $mail_body = isset( $booking->order_info['seat_sequences'] ) ? str_replace( "(Seat No.)", implode( ',', $booking->order_info['seat_sequences']), $mail_body ) : str_replace( "(Seat No.)", "Standing Event", $mail_body );
            $mail_body = str_replace( "ID", $booking_id, $mail_body );
            $mail_body = str_replace( "Event Name", $booking->em_name, $mail_body );
            if( isset( $booking->event_data->venue_details ) ) {
                $venue = $booking->event_data->venue_details;
                $mail_body = str_replace( "Venue Name", empty( $venue ) ? '' : $venue->name, $mail_body );
                $mail_body = str_replace( "Event Venue", empty( $venue ) ? '' : $venue->em_address, $mail_body );
            }
            if( isset( $booking->event_data->em_start_date ) ) {
                $event_date_time = esc_html( ep_timestamp_to_date( $booking->event_data->em_start_date, ep_get_datepicker_format(), 1 ) );
                if( ! empty( $booking->event_data->em_start_time ) ) {
                    $event_date_time .= ', ' . esc_html( ep_convert_time_with_format( $booking->event_data->em_start_time ) );
                }
                $mail_body = str_replace( "Event Date Time", empty( $event_date_time ) ? '' : $event_date_time, $mail_body );
            }
            $ticket_sub_total = $total_qty = 0;
            foreach( $tickets as $ticket ) {
                $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;
                $total_qty = $total_qty + $ticket->qty;
            }
        
            $mail_body = str_replace( "$(Subtotal)", ep_price_with_position( ! empty( $order_info['booking_total'] ) ? $order_info['booking_total'] : 0 ), $mail_body );
            $mail_body = str_replace( "(Quantity)", $total_qty, $mail_body );
            $mail_body = str_replace( "$(Price)", ep_price_with_position($ticket_sub_total), $mail_body );
            $mail_body = str_replace( "$(Discount)", ep_price_with_position( isset($order_info['discount']) ? $order_info['discount'] : 0 ), $mail_body );
            $mail_body = str_replace( "$(Fixed Event Fee)", ep_price_with_position($order_info['event_fixed_price']), $mail_body );
            $lastFoot = explode( '</tfoot>', $mail_body );
            $lastFootUpdate = $lastFoot[0];
            if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {
                $booking_attendees_field_labels = array();
                $i = 1;
                $attendee_name_html ='';
                foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                    $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
                    foreach( $attendee_data as $booking_attendees ) {
                        $attendee_name_html .= '<tr><th colspan="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">'.esc_html__( 'Attendees '.$i, 'eventprime-event-calendar-management' ).' </th>';
                        $attendee_name_html .= '<td colspan ="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">';
                        $booking_attendees_val = array_values( $booking_attendees );
                        foreach( $booking_attendees_field_labels as $label_keys => $labels ){
                            $formated_val = ep_get_slug_from_string( $labels );
                            $at_val = '---';
                            foreach( $booking_attendees_val as $baval ) {
                                if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                    $at_val = $baval[$formated_val];
                                    break;
                                }
                            }
                            $attendee_name_html .= '<span>'. esc_html__( $labels, 'eventprime-event-calendar-management' ) .' : '. $at_val .' </span><br/>';
                        }
                        $attendee_name_html .= '</td>';
                        $attendee_name_html .= '</tr>';
                        $i++;
                    }
                }
                $lastFootUpdate .=$attendee_name_html;
            }
            
            $lastFootUpdate = apply_filters( 'event_magic_booking_confirmed_footer_contnent', $lastFootUpdate, $booking );
            $mail_body = $lastFootUpdate . '</tfoot>' . $lastFoot[1];

            //Send to user
            $sent = wp_mail( $to, $subject, $mail_body, $headers );
        }

        // Admin email
        //Add CC to admin emails
        if( empty( $global_setting->disable_admin_email ) ) {
            $mail_cc = ep_get_global_settings('booking_pending_email_cc');
            if( ! empty( $mail_cc ) ) {
                $mails = explode( ",", $mail_cc );
                if( ! empty( $mails ) ) {
                    foreach( $mails as $mail ){
                        $headers[] = 'CC: '.$mail;
                    }
                }
            }
            $to = get_option('admin_email');
            $subject = esc_html__( 'Booking Pending', 'eventprime-event-calendar-management' );        
            $body = sprintf( esc_html__( 'User %s has Booking Pending with Booking ID #%d.', 'eventprime-event-calendar-management' ), $booking_user_email, $booking_id );
            
            wp_mail( $to, $subject, $body, $headers );
        }
    }

    /**
     * Send booking cancelled email
     */
    public static function booking_cancel( $booking_id ) {
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        $booking = $booking_controller->load_booking_detail($booking_id);
        $order_info = isset($booking->em_order_info) ? $booking->em_order_info : array();
        $tickets = isset($order_info['tickets']) ? $order_info['tickets'] : array();
        
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        $mail_body = isset($global_setting->booking_cancelation_email) ? $global_setting->booking_cancelation_email : '';
        $subject = isset($global_setting->booking_cancelation_email_subject) ? $global_setting->booking_cancelation_email_subject : esc_html__( 'Your booking has been cancelled', 'eventprime-event-calendar-management' );
        self::configure_mail();
        
        $booking_user_email = $booking_user_name = $to = '';
        $user_id = isset($booking->em_user) ? (int) $booking->em_user : 0;
        if($user_id){
            $user = get_userdata($user_id);
            $booking_user_email = $to = $user->user_email;
            $booking_user_name = $user->display_name;
        }
        if(empty($user_id) && ep_enabled_guest_booking()){
            $booking_user_email = $to = isset($order_info['user_email']) ? $order_info['user_email'] :'';
        }
        $from = get_bloginfo('name') . '<' . get_bloginfo('admin_email') . '>';
        $headers[] = 'From: ' . $from;
        if( ! empty( $global_setting->send_booking_pending_email ) && empty( $global_setting->disable_frontend_email ) ) {
            $mail_body= isset($booking->order_info['seat_sequences']) ? str_replace("(Seat No.)",implode(',',$booking->order_info['seat_sequences']), $mail_body) : str_replace("(Seat No.)", "Standing Event", $mail_body);
            $mail_body = str_replace("ID",$booking_id, $mail_body);
            $mail_body = str_replace("Event Name", $booking->em_name, $mail_body);
            if(isset($booking->event_data->venue_details)){
                $venue = $booking->event_data->venue_details;
                $mail_body = str_replace("Venue Name",empty($venue) ? '' : $venue->name, $mail_body);
                $mail_body = str_replace("Event Venue",empty($venue) ? '' : $venue->em_address, $mail_body);
            }
            $ticket_sub_total = 0;
            $total_qty = 0;
            foreach($tickets as $ticket):
                $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;
                $total_qty = $total_qty + $ticket->qty;
            endforeach;
        
            $mail_body = str_replace("$(Subtotal)", ep_price_with_position( isset($order_info['booking_total']) ? $order_info['booking_total'] : 0 ), $mail_body);
            $mail_body = str_replace("(Quantity)", $total_qty, $mail_body);
            $mail_body = str_replace("$(Price)", ep_price_with_position($ticket_sub_total), $mail_body);
            $mail_body = str_replace("$(Discount)", ep_price_with_position( isset($order_info['discount']) ? $order_info['discount'] : 0 ), $mail_body);
            $mail_body = str_replace("$(Fixed Event Fee)", ep_price_with_position($order_info['event_fixed_price']), $mail_body);
        
            $lastFoot = explode( '</tfoot>', $mail_body );
            $lastFootUpdate = $lastFoot[0];
            // add attendees name in email
            if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {
                $booking_attendees_field_labels = array();
                $i=1;
                $attendee_name_html ='';
                foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                    $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
                    foreach( $attendee_data as $booking_attendees ) {
                        $attendee_name_html .= '<tr><th colspan="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">'.esc_html__( 'Attendees '.$i, 'eventprime-event-calendar-management' ).' </th>';
                        $attendee_name_html .= '<td colspan ="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px">';
                        $booking_attendees_val = array_values( $booking_attendees );
                        foreach( $booking_attendees_field_labels as $label_keys => $labels ){
                            $formated_val = ep_get_slug_from_string( $labels );
                            $at_val = '---';
                            foreach( $booking_attendees_val as $baval ) {
                                if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                    $at_val = $baval[$formated_val];
                                    break;
                                }
                            }
                            $attendee_name_html .= '<span>'. esc_html__( $labels, 'eventprime-event-calendar-management' ) .' : '. $at_val .' </span><br/>';
                        }
                        $attendee_name_html .= '</td>';
                        $attendee_name_html .= '</tr>';
                            $i++;
                    }
                }

                $lastFootUpdate .=$attendee_name_html;
            }
            $lastFootUpdate = apply_filters( 'event_magic_booking_confirmed_footer_contnent', $lastFootUpdate, $booking );
            $mail_body = $lastFootUpdate . '</tfoot>' . $lastFoot[1];

            // Send to user
            $sent = wp_mail( $to, $subject, $mail_body, $headers);
        }

        // Admin email
        //Add CC to admin emails
        if( empty( $global_setting->disable_admin_email ) ) {
            $mail_cc = ep_get_global_settings('booking_cancelation_email_cc');
            if( ! empty( $mail_cc ) ) {
                $mails = explode( ",", $mail_cc );
                if( ! empty( $mails ) ) {
                    foreach( $mails as $mail ) {
                        $headers[] = 'CC: '.$mail;
                    }
                }
            }
            $mail_body = file_get_contents( EP_BASE_DIR . 'includes/core/admin/settings/emailers/mail/admin_cancellation.php' );  
            $mail_body = str_replace( "Event Name", $booking->em_name, $mail_body );
            
            $admin_email = get_option('admin_email'); 
            $mail_body = str_replace( "#ID", $booking_id, $mail_body );
            $mail_body = str_replace( "(User Email)", $booking_user_email, $mail_body );
            $to = $admin_email;
            $subject = esc_html__( 'Booking Cancellation', 'eventprime-event-calendar-management' );
            wp_mail( $to, $subject, $mail_body ); 
        }
    }
    
    public static function user_registration( $user_data = null ) { 
        self::configure_mail();
        
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        
        $mail_body= $global_setting->registration_email_content;
        $mail_body = str_replace( "@username", $user_data->email, $mail_body );
        $mail_body = str_replace( "@first_name", get_user_meta( $user_data->user_id, 'first_name', true ), $mail_body );
        $mail_body = str_replace( "@last_name", get_user_meta( $user_data->user_id, 'last_name',true ), $mail_body );
        $mail_body = str_replace( "@phone", get_user_meta( $user_data->user_id, 'phone', true ), $mail_body );
        if( isset( $user_data->password ) ) {
            $mail_body .= esc_html__( 'Your auto generated password is', 'eventprime-event-calendar-management' ) . ' ' . $user_data->password;
        }
        $registration_email_subject = $global_setting->registration_email_subject;
        if( empty( $registration_email_subject ) ) {
            $registration_email_subject = esc_html__( 'User registration successful!', 'eventprime-event-calendar-management' );
        }
        
        if( ! empty( $user_data ) ) {
            wp_mail( $user_data->email, $registration_email_subject, $mail_body );

            $admin_email = get_option('admin_email'); 
            $to = $admin_email;
            $subject = esc_html__( 'New User Registered', 'eventprime-event-calendar-management' ); 
            $body = sprintf(esc_html__( 'New user %s has Registered', 'eventprime-event-calendar-management' ), $user_data->email );
            wp_mail( $to, $subject, $body );
            return true;
        }
        return false;
    }
    
    public static function reset_password_mail( $booking, $new_user_password ) {   
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        
        if(empty($booking->em_id)) return;

        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        $booking = $booking_controller->load_booking_detail($booking->em_id);
        $order_info = isset($booking->em_order_info) ? $booking->em_order_info : array();
        $tickets = isset($order_info['tickets']) ? $order_info['tickets'] : array();
        
        self::configure_mail();
        $user= get_user_by('ID', $booking->em_user);
        if(empty($user))
            return false;

        $mail_body= $global_setting->reset_password_mail;
        $mail_body = str_replace("@username",$user->user_email,$mail_body);
        $mail_body = str_replace("@password",$new_user_password,$mail_body);   
        
        $to = $user->user_email;
        $subject = esc_html__('New Password','eventprime-event-calendar-management');
        $body = $mail_body;
        wp_mail( $to, $subject, $body );
        
        $admin_email = get_option('admin_email'); 
        $to = $admin_email;
        $subject = esc_html__('Reset User Password','eventprime-event-calendar-management');        
        $body = sprintf( esc_html__('Password of user %s is Reset.', 'eventprime-event-calendar-management' ), $user->user_email );
        //wp_mail( $to, $subject, $body);
    }

    public static function event_submitted( $event_id ) {  
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        
        if( empty( $global_setting->send_event_submitted_email ) || ! empty( $global_setting->disable_admin_email ) ) return;

        if( empty( $global_setting->event_submitted_email ) || is_null( $global_setting->event_submitted_email ) ) {
            ob_start();
            include(EP_BASE_DIR . 'includes/core/admin/settings/emailers/mail/event_submitted.php');
            $global_setting->event_submitted_email = ob_get_clean();
        }
        $userEmail = wp_get_current_user()->user_email;
        if(empty($userEmail)){
            $userEmail = 'User';
        }
        self::configure_mail();
        $mail_body = $global_setting->event_submitted_email;
        $mail_body = str_replace( "@UserEmail", $userEmail, $mail_body );
        $mail_body = str_replace( "@EventName", get_the_title($event_id), $mail_body );
        $mail_body = str_replace( "@EventStartDate", ep_timestamp_to_date( get_post_meta( $event_id, 'em_start_date', true ) ),$mail_body );
        $mail_body = str_replace( "@EventEndDate", ep_timestamp_to_date( get_post_meta( $event_id, 'em_end_date', true ) ), $mail_body );
       
        /* Send Mail to Admin */    
        $from = get_bloginfo('name') . '<' . get_bloginfo('admin_email') . '>';
        $headers[] = 'From: ' . $from;
        //Add CC to admin emails
        $mail_cc = ep_get_global_settings('event_submitted_email_cc');
        if( ! empty( $mail_cc ) ) {
            $mails = explode( ",", $mail_cc );
            if( ! empty( $mails ) ) {
                foreach( $mails as $mail ) {
                    $headers[] = 'CC: '.$mail;
                }
            }
        }
        $to = get_option('admin_email');
        $subject = esc_html__( 'Event submitted successfully!', 'eventprime-event-calendar-management' );
        
        wp_mail( $to, $subject, $mail_body, $headers );
    }
    
    public static function event_approved( $event_id ) {
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_setting = $settings->ep_get_settings();
        if( empty( $global_setting->disable_frontend_email ) ) {
            $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
            $event = $event_controller->get_single_event($event_id);

            if( empty( $global_setting->send_event_approved_email ) ) return;
            
            if( empty( $global_setting->event_approved_email ) || is_null( $global_setting->event_approved_email ) ) {
                ob_start();
                include( EP_BASE_DIR . 'includes/core/admin/settings/emailers/mail/event_approved.html' );
                $global_setting->event_submitted_email = ob_get_clean();
            }
            
            self::configure_mail();
            $mail_body = $global_setting->event_approved_email;
            $mail_body = str_replace( "@UserName", get_the_author_meta('display_name',$event->em_user),$mail_body );
            $mail_body = str_replace( "@EventName", $event->em_name,$mail_body );
            $mail_body = str_replace( "@SiteURL", site_url(),$mail_body );
            $mail_body = str_replace( "@EventLink", $event->event_url,$mail_body );

            /* Send Mail to Event Author */
            $to = get_the_author_meta( 'user_email', $event->em_user );
            $subject = isset( $global_setting->event_approved_email_subject ) ? $global_setting->event_approved_email_subject : esc_html__( 'Your event is now live!', 'eventprime-event-calendar-management' );
        
            wp_mail( $to, $subject, $mail_body );
        }
    }

    private static function configure_mail() {  
        add_filter('wp_mail_content_type', 'ep_set_mail_content_type_html');
        add_filter('wp_mail_from', 'ep_set_mail_from');
        add_filter('wp_mail_from_name', 'ep_set_mail_from_name');
    }

    private static function gcal_link( $event, $venue ) {
        $gcal_starts = $gcal_ends = $gcal_details = $location = $calendar_url = '';
        $gcal_starts = ep_convert_event_date_time_to_timestamp( $event, 'start' );
        if( ! empty( $gcal_starts ) ) {
            $gcal_ends = ep_convert_event_date_time_to_timestamp( $event, 'end' );
        }
        $gcal_details = urlencode( wp_kses_post( $event->description ) );
        $calendar_url = 'https://www.google.com/calendar/event?action=TEMPLATE&text=' . urlencode( esc_attr( $event->name ) ) . '&dates=' . gmdate( 'Ymd\\THi00\\Z', esc_attr( $gcal_starts ) ) . '/' . gmdate('Ymd\\THi00\\Z', esc_attr( $gcal_ends ) ) . '&details=' . esc_attr( $gcal_details );
        if ( ! empty( $event->venue_details ) ) {
            $location = urlencode( $event->venue_details->em_address );
            if( ! empty( $location ) ) {
                $calendar_url .= '&location=' . esc_attr( $location );
            }
        }
        $html = '<div id="authorize-button" class="kf-event-add-calendar em_color dbfl">
            <a class="em-events-gcal em-events-button em-color em-bg-color-hover em-border-color" href="'.esc_url( $calendar_url ).'" target="_blank" title="'.esc_html__('Add To Google Calendar', 'eventprime-event-calendar-management').'">';
                $html .= esc_html__("Add To Google Calendar", 'eventprime-event-calendar-management');
            $html .= '</a>
        </div>';
        return $html;
    }

    private static function iCal_link($event, $venue) {
        $url = add_query_arg('event', $event->id, ep_get_custom_page_url('events_page'));
        $url .= '&download=ical';
        $html = '<div class="ep-ical-download em_color" title="'.esc_html__('+ iCal / Outlook export', 'eventprime-event-calendar-management').'"><a href="'.esc_url( $url ).'">'. esc_html__('+ iCal / Outlook export', 'eventprime-event-calendar-management').'</a></div>';
        return $html;
    }
}
