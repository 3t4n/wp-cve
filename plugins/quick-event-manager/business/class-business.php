<?php

/**
 * @copyright (c) 2020.
 * @author            Alan Fuller (support@fullworks)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace Quick_Event_Manager\Plugin\Business;

use  WP_Query ;
use  WP_REST_Server ;
class Business
{
    private  $plugin_name ;
    private  $version ;
    /**
     * @param Freemius $freemius Object for freemius.
     */
    private  $freemius ;
    public function __construct( $plugin_name, $version, $freemius )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->freemius = $freemius;
    }
    
    public function hooks()
    {
        add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
        add_action( 'init', array( $this, 'cron_jobs' ) );
        add_action( 'qem_pending_cleardown', array( $this, 'pending_cleardown' ) );
    }
    
    public function rest_api_init()
    {
    }
    
    public function cron_jobs()
    {
        if ( !wp_next_scheduled( 'qem_pending_cleardown' ) ) {
            wp_schedule_event( time(), 'hourly', 'qem_pending_cleardown' );
        }
    }
    
    public function pending_cleardown()
    {
        $this->payment = qem_get_stored_payment();
        if ( !isset( $this->payment['usependingcleardown'] ) ) {
            return;
        }
        if ( 'checked' != $this->payment['usependingcleardown'] ) {
            return;
        }
        $args = array(
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $id = get_the_ID();
                $cost = get_post_meta( $id, 'event_cost', true );
                if ( empty($cost) ) {
                    continue;
                }
                $paypal = get_post_meta( $id, 'event_paypal', true );
                if ( !$paypal ) {
                    continue;
                }
                $message = get_option( 'qem_messages_' . $id );
                
                if ( !empty($message) ) {
                    foreach ( $message as $key => $attendee ) {
                        if ( 'Paid' != qem_get_element( $attendee, 'ipn', false ) ) {
                            
                            if ( time() - qem_get_element( $attendee, 'datetime_added', time() ) > 1800 ) {
                                unset( $message[$key] );
                                if ( !empty($this->payment['pendingcleardownmsg']) ) {
                                    $this->send_notification( qem_get_element( $attendee, 'youremail', false ), qem_get_element( $attendee, 'yourname', false ) );
                                }
                            }
                        
                        }
                    }
                    $message = array_values( $message );
                    update_option( 'qem_messages_' . $id, $message );
                }
            
            }
        }
        wp_reset_postdata();
    }
    
    private function send_notification( $youremail, $yourname )
    {
        global  $post ;
        if ( !$youremail ) {
            return;
        }
        $register = qem_get_stored_register();
        $subject = __( 'Payment not completed for', 'quick-event-manager' ) . get_the_title() . __( ' on ', 'quick-event-manager' ) . get_the_date();
        $headers = "Reply-To: " . $yourname . " <" . $youremail . ">\r\n" . "Content-Type: text/html; charset=\"utf-8\"\r\n";
        $content = '<p>' . $this->payment['pendingcleardownmsg'] . '</p>';
        $content .= '<p><a href="' . get_the_permalink() . '">' . get_the_permalink() . '</a></p>';
        $message = '<html>' . $content . '</html>';
        
        if ( $register['qemmail'] == 'smtp' ) {
            qem_send_smtp(
                $youremail,
                $subject,
                array(
                'yourname'  => $yourname,
                'youremail' => $youremail,
            ),
                $message
            );
        } else {
            add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
            wp_mail( $youremail, $subject, $message );
            remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
        }
    
    }
    
    public function set_html_content_type()
    {
        return 'text/html';
    }

}