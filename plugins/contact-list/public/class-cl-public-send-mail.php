<?php

class ContactListPublicSendMail
{
    public function cl_send_mail_public()
    {
        global  $wpdb ;
        $s = get_option( 'contact_list_settings' );
        $contact_id = ( isset( $_POST['contact_id'] ) ? intval( $_POST['contact_id'] ) : '' );
        $c = get_post_custom( $contact_id );
        // Preprocess & format mail data
        $subject = ( isset( $_POST['subject'] ) ? stripslashes( sanitize_text_field( $_POST['subject'] ) ) : '' );
        $sender_name = ( isset( $_POST['sender_name'] ) ? sanitize_text_field( $_POST['sender_name'] ) : '' );
        $sender_email = ( isset( $_POST['sender_email'] ) ? sanitize_email( $_POST['sender_email'] ) : '' );
        $mail_cnt = ( isset( $_POST['mail_cnt'] ) ? intval( $_POST['mail_cnt'] ) : '' );
        $body = '';
        if ( $sender_name ) {
            $body .= sanitize_text_field( __( 'Sent by:', 'contact-list' ) ) . ' ' . sanitize_text_field( $sender_name );
        }
        if ( $sender_email && is_email( $sender_email ) ) {
            $body .= " &lt;" . sanitize_email( $sender_email ) . "&gt;";
        }
        if ( $sender_name || $sender_email ) {
            $body .= "<br /><br />";
        }
        $body .= ( isset( $_POST['body'] ) ? nl2br( sanitize_textarea_field( stripslashes( $_POST['body'] ) ) ) : '' );
        $is_premium = 0;
        if ( !$is_premium ) {
            $body .= '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin-top: 20px;">
                <tr>
                  <td style="border-top: 1px solid #eee; color: #bbb; padding-top: 3px; width: 100%;">
                    ' . sanitize_text_field( __( 'Sent by', 'contact-list' ) ) . ' ' . '<a style="color: #ABC0E7; text-decoration: none;" href="https://www.contactlistpro.com/">' . sanitize_text_field( __( 'Contact List for WordPress', 'contact-list' ) ) . '</a>' . '
                  </td>
                </tr>
                </table>';
        }
        $recipient_emails = '';
        $recipient_emails_array = [];
        if ( isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] ) ) {
            $recipient_emails_array[] = sanitize_email( $c['_cl_email'][0] );
        }
        
        if ( isset( $c['_cl_notify_emails'] ) ) {
            $notify_emails = explode( ',', sanitize_text_field( $c['_cl_notify_emails'][0] ) );
            foreach ( $notify_emails as $email ) {
                if ( is_email( $email ) ) {
                    $recipient_emails_array[] = sanitize_email( $email );
                }
            }
        }
        
        $recipient_emails = implode( ',', $recipient_emails_array );
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        $reply_to = '';
        
        if ( $sender_name && is_email( $sender_email ) ) {
            $reply_to = sanitize_text_field( $sender_name ) . ' <' . sanitize_email( $sender_email ) . '>';
        } elseif ( is_email( $sender_email ) ) {
            $reply_to = '<' . sanitize_email( $sender_email ) . '>';
        }
        
        if ( $reply_to ) {
            $headers[] = 'Reply-To: ' . $reply_to;
        }
        $from = '';
        if ( isset( $s['email_sender_contact_card'] ) && is_email( $s['email_sender_contact_card'] ) ) {
            
            if ( isset( $s['email_sender_name_contact_card'] ) && $s['email_sender_name_contact_card'] ) {
                $from = sanitize_text_field( $s['email_sender_name_contact_card'] ) . ' <' . sanitize_email( $s['email_sender_contact_card'] ) . '>';
            } else {
                $from = sanitize_email( $s['email_sender_contact_card'] );
            }
        
        }
        if ( $from ) {
            $headers[] = 'From: ' . $from;
        }
        $all_emails = explode( ',', $recipient_emails );
        $mail_cnt = sizeof( $all_emails );
        // Send the mail
        $resp = wp_mail(
            $recipient_emails,
            $subject,
            $body,
            $headers
        );
        // Write log
        if ( !isset( $s['disable_mail_log'] ) ) {
            
            if ( $resp && $recipient_emails ) {
                $report = 'Mail successfully processed using <strong>wp_mail</strong>.<br /><br /><strong>Mail sent to:</strong><br />' . sanitize_text_field( str_replace( ',', ', ', $recipient_emails ) );
                $wpdb->insert( $wpdb->prefix . 'cl_sent_mail_log', array(
                    'subject'      => $subject,
                    'sender_name'  => $sender_name,
                    'reply_to'     => $reply_to,
                    'report'       => $report,
                    'sender_email' => $from,
                    'mail_cnt'     => $mail_cnt,
                ) );
            } else {
                $report = '<span style="color: crimson;">ERROR processing mail using <strong>wp_mail</strong>.<br /><br /><strong>Mail WAS NOT sent to:</strong><br />' . (( $recipient_emails ? sanitize_text_field( str_replace( ',', ', ', $recipient_emails ) ) : '(no recipient emails defined)' )) . '</span>';
                $wpdb->insert( $wpdb->prefix . 'cl_sent_mail_log', array(
                    'subject'      => $subject,
                    'sender_name'  => $sender_name,
                    'reply_to'     => $reply_to,
                    'report'       => $report,
                    'sender_email' => $from,
                    'mail_cnt'     => 0,
                ) );
            }
        
        }
        // Return message
        
        if ( $resp && $recipient_emails ) {
            echo  'OK' ;
        } else {
            echo  'Error processing mail' ;
        }
        
        die;
    }

}