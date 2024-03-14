<?php
class Mo_lla_FeedbackHandler
{
    function __construct()
    {
        add_action('admin_init', array($this, 'molla_feedback_actions'));
    }

    function molla_feedback_actions()
    {
       
        if (current_user_can('manage_options') && isset($_POST['option'])) { 
            switch (sanitize_text_field($_REQUEST['option'])) {
                case "molla_skip_feedback":
                case "molla_feedback":
                   $this->handle_feedback($_POST);
                    break;
            }
        }
    }
    function handle_feedback($postdata)
    {
         
        if (MO2F_TEST_MODE_LIMIT_LOGIN_LIMIT_LOGIN) {
            deactivate_plugins(dirname(dirname(__FILE__))."\\miniorange_limit_login_widget.php");
            return;
        }

        $nonce = sanitize_text_field($_POST['_wpnonce']);
        if (!wp_verify_nonce($nonce,'mo_lla_feedback')) {
            do_action('lla_show_message','Error while processing request.','ERROR');
        return;
        }
        $user = wp_get_current_user();
        $feedback_option = sanitize_text_field($_POST['option']);
        $message = 'Plugin Deactivated';

        $deactivation_reason = isset($_POST['molla_feedback'])? sanitize_text_field($_POST['molla_feedback']):'NA';

        if($deactivation_reason=='other' || $deactivation_reason == 'specific_feature')
            $deactivate_reason_message =  '['.$deactivation_reason.']-'.sanitize_text_field($_POST['molla_wpns_query_feedback']) ;
        else
            $deactivate_reason_message = $deactivation_reason;

        $activation_date = get_site_option('limitlogin_activated_time');
        $current_date = time();
        $diff = $activation_date - $current_date;
        if ($activation_date == false) {
            $days = 'NA';
        } else {
            $days = abs(round($diff / 86400));
        }

        $reply_required = '';
        if (isset($_POST['get_reply'])) {
            $reply_required = htmlspecialchars(sanitize_text_field($_POST['get_reply']));
        }
        if (empty($reply_required)) 
            $message .= ' &nbsp; [Reply:<b style="color:red";>' ." don't reply  ". '</b> ';
        else 
            $message .= '[Reply:' . "yes  ";
        
        $message .= ' D:' . esc_html($days) ;

        $message .= '    Feedback : ' . esc_html($deactivate_reason_message) . '';

        if (empty($reply_required))
             $message .= Mo_lla_MoWpnsUtility::molla_send_configuration(true);
        else
            $message .= Mo_lla_MoWpnsUtility::molla_send_configuration(true);
        
        $email = isset($_POST['molla_query_mail'])? sanitize_email($_POST['molla_query_mail']): '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email =esc_html(get_option('mo_lla_admin_email'));
            if (empty($email)) {
                $email = $user->user_email;
            }
        }
        $phone = esc_html (get_option('mo_lla_admin_phone'));
        if (!$phone) {
            $phone = '';
            }
        $feedback_reasons = new Mo_lla_MocURL();
        global $mollaUtility;
        if (!is_null($feedback_reasons)) 
        {
                if (!$mollaUtility->is_curl_installed()) 
                    {
                        deactivate_plugins(dirname(dirname(__FILE__))."\\mo_limit_login_widget.php");
                        wp_safe_redirect('plugins.php');
                    }
               else 
               {   

                        $submited = json_decode($feedback_reasons->send_email_alert($email, $phone, $message, $feedback_option), true);
                            if (json_last_error() == JSON_ERROR_NONE) 
                            {
                               if (is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR') 
                                {
                                    do_action('lla_show_message', $submited['message'], 'ERROR');
                                }
                                else 
                                {
                                        if ($submited == false) {
                                        do_action('lla_show_message', 'Error while submitting the query.', 'ERROR');
                                        }
                                }
                            }
                         deactivate_plugins(dirname(dirname(__FILE__))."\\mo_limit_login_widget.php");
                         do_action('lla_show_message', 'Thank you for the feedback.', 'SUCCESS'); 
                }
        }
    }
}new Mo_lla_FeedbackHandler();
