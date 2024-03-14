<?php

class Af2AjaxTestmail {

    function __construct() {}

    public function fnsf_af2_test_mail() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $smtp = sanitize_text_field($_POST['use_smtp']);
        $wp_mail = sanitize_text_field($_POST['use_wp_mail']);
    
    
        if ($smtp === true || $smtp === 'true') {
            $cc = sanitize_text_field($_POST['cc']);
            $bcc = sanitize_text_field($_POST['bcc']);
            $cc_list = explode(',', $cc);
            $bcc_list = explode(',', $bcc);

            $resp = $this->fnsf_af2_smtp_mail(sanitize_text_field($_POST['host']), sanitize_text_field($_POST['username']), sanitize_text_field($_POST['password']), sanitize_text_field($_POST['port']), sanitize_text_field($_POST['type']), sanitize_text_field($_POST['to']), sanitize_text_field($_POST['from']), sanitize_text_field($_POST['from_name']), __('Testmail'), __('Dies ist eine Testmail vom Funnelforms Pro Plugin per SMTP'), $cc_list, $bcc_list,sanitize_text_field($_POST['reply_to']));
            $arr = [];
    
            if (!$resp)
                $arr = array('status' => 'Error', 'message' => __("Bitte konfiguriere deine SMTP Einstellungen", 'funnelforms-free'), 'resp' => $resp);
    
            $arr = array('status' => $resp->status, 'message' => ( $resp->status == 'Success' ? __('E-mail sent successfully!', 'funnelforms-free') : __("E-Mail konnte nicht gesendet werden!", 'funnelforms-free') ), 'resp' => $resp);
    
            _e($arr['status'] . ' : ' . $arr['message']) ;
        }
        else {
            $from_mail_sb = sanitize_text_field($_POST['from_name']) ;
            $from_sb = sanitize_text_field($_POST['from']) ;
            $from_cc_sb = sanitize_text_field($_POST['cc']) ;
            $from_bcc_sb = sanitize_text_field($_POST['bcc']) ;
            $headers = 'From: ' . $from_mail_sb . ' <' . $from_sb . '>' . "\r\n";
            $headers .= 'CC: '  . $from_cc_sb . "\r\n";
            $headers .= 'BCC: ' .$from_bcc_sb. "\r\n";
    
            if ($wp_mail === true || $wp_mail === 'true') {
                $from_to_sb = sanitize_text_field($_POST['to']) ;
                wp_mail($from_to_sb, __('Testmail', 'funnelforms-free'), __('This is a test mail from the Funnelforms Pro plugin', 'funnelforms-free'), $headers);
    
               
                _e('Testmail sent with WP Mail. Please check your mailbox!', 'funnelforms-free');
            } else {
                mail($from_to_sb, __('Testmail', 'funnelforms-free'), __('This is a test mail from the Funnelforms Pro plugin', 'funnelforms-free'), $headers);
    
                
                _e('Testmail sent without SMTP. Please check your mailbox!', 'funnelforms-free');
            }
        }
    
    
    
        wp_die();
    }
    
    private function fnsf_af2_smtp_mail($host, $user, $password, $port, $type, $to, $from, $from_nam, $subject, $body, $cc, $bcc,$reply_to) {
        //$errors = '';
    
        //$swpsmtp_options = get_option('twm_smtp_options');
    
        require_once( ABSPATH . WPINC . '/class-smtp.php' );
        require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
        
        $mail = new \PHPMailer();
    
        $charset = get_bloginfo('charset');
        $mail->CharSet = $charset;
        $mail->Timeout = 10;
    
        $from_name = $from_nam;
        $from_email = $from;
    
        $mail->IsSMTP();
    
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $password;
    
        $mail->SMTPSecure = $type;
    
        /* PHPMailer 5.2.10 introduced this option. However, this might cause issues if the server is advertising TLS with an invalid certificate. */
        $mail->SMTPAutoTLS = false;
    
        /* Set the other options */
        $mail->Host = $host;
        $mail->Port = $port;
    
        $mail->SetFrom($from_email, $from_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($to);
    
        foreach ($cc as $c) {
            $c = trim($c);
    
            $mail->addCC($c);
        }
    
        foreach ($bcc as $bc) {
            $bc = trim($bc);
    
            $mail->addBCC($bc);
        }
    
        if(!empty($reply_to)){
            $mail->addReplyTo($reply_to);
        }
        
        $mail->SMTPDebug = 4;
    
        /* Send mail and return result */
    
        $error = $mail->Send();
    
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
        $mail->clearCCs();
        $mail->clearBCCs();
    
        return $error;
    }
}
