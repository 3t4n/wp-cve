<?php
require_once ULTIMATE_SUBSCRIBE_DIR . 'inc/api/MailChimp/MailChimp.php';

function ultimate_subscribe_submit() {
    $visitor_email = (is_email($_POST['email'])) ? sanitize_text_field($_POST['email']) : false;
    $first_name    = isset($_POST['fname']) ? sanitize_text_field($_POST['fname']) : '';
    $last_name     = isset($_POST['lname']) ? sanitize_text_field($_POST['lname']) : '';
    $form_id       = isset($_POST['form_id']) ? absint($_POST['form_id']) : '';
    $visitor_ip    = ultimate_subscribe_get_user_ip();
    if ($visitor_email) {
        $options        = ultimate_subscribe_get_options();
        $opt_in_process = $options['opt_in_process'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_subscribe';

        $is_email_exist = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE email = %s", $visitor_email));
        if ($is_email_exist) {
            $alerady_sucribed_message = (isset($form_messages['already_subscribed'])) ? $form_messages['already_subscribed'] : __('You have already subscribed.', 'ultimate-subscribe');
            echo json_encode(array('status' => 'error', 'message' => $alerady_sucribed_message));
            wp_die();
        } else {
            $form_settings = get_post_meta($form_id, 'ultimate_subscribe_form_settings', true);
            $list_storege  = isset($form_settings['list_storege']) ? $form_settings['list_storege'] : 'database';
            $list_id       = isset($form_settings['list_id']) ? $form_settings['list_id'] : '';
            if ($opt_in_process == 'double') {
                $mail_activation_code = ultimate_subscribe_get_random_string();
                $is_added             = $wpdb->insert(
                    $table_name,
                    array(
                        'form_id'     => $form_id,
                        'list_id'     => $list_id,
                        'email'       => $visitor_email,
                        'first_name'  => $first_name,
                        'last_name'   => $last_name,
                        'ip_address'  => $visitor_ip,
                        'active_code' => $mail_activation_code,
                        'storege'     => $list_storege,
                        'active'      => 0,
                    ),
                    array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
                );
                $site_url             = site_url();
                $from_name            = $options['from_name'];
                $from_email           = $options['from_email'];
                $visitor_name         = $first_name . ' ' . $last_name;
                $confirm_link         = $site_url . '?ultimate-subscribe-confirm=' . $visitor_email . '&code=' . $mail_activation_code;
                $confirm_mail_subject = $options['confirm_mail_subject'];
                $confirm_mail_content = $options['confirm_mail_content'];
                $confirm_mail_content = str_replace("###NAME###", $visitor_name, $confirm_mail_content);
                $confirm_mail_content = str_replace("###LINK###", $confirm_link, $confirm_mail_content);
                $mail_header          = "Content-type: text/html \r\n From:$from_name <$from_email> \r\n Reply-To: $from_email \r\n X-Mailer: PHP/" . phpversion();
                $is_mail              = wp_mail($visitor_email, $confirm_mail_subject, $confirm_mail_content, $mail_header);

                if ($is_added && $is_mail) {
                    $subscribe_success_message = (isset($form_messages['success_double'])) ? $form_messages['success_double'] : __('Thank you, confirmation link has sent to your Email Address', 'ultimate-subscribe');
                    echo json_encode(array('status' => 'success', 'message' => $subscribe_success_message));
                    wp_die();
                } else {
                    $unexpected_error_message = (isset($form_messages['error'])) ? $form_messages['error'] : __('Error: some unexpected error occurred please try again.', 'ultimate-subscribe');
                    echo json_encode(array('status' => 'error', 'message' => $unexpected_error_message));
                    wp_die();
                }

            } else {
                if ($list_storege == 'database') {
                    $is_added = $wpdb->insert(
                        $table_name,
                        array(
                            'form_id'    => $form_id,
                            'list_id'    => $list_id,
                            'email'      => $visitor_email,
                            'first_name' => $first_name,
                            'last_name'  => $last_name,
                            'ip_address' => $visitor_ip,
                            'storege'    => $list_storege,
                            'active'     => 1,
                        ),
                        array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
                    );
                    if ($is_added) {
                        // wp_mail()
                        $subscribe_success_message = (isset($form_messages['success'])) ? $form_messages['success'] : __('Thank you! We will be back with the quote.', 'ultimate-subscribe');
                        echo json_encode(array('status' => 'success', 'message' => $subscribe_success_message));
                        setcookie("ultimate_subscribe_confirmed", 1, time() + (86400 * 60));
                        wp_die();
                    } else {
                        $unexpected_error_message = (isset($form_messages['error'])) ? $form_messages['error'] : __('Error: some unexpected error occurred please try again.', 'ultimate-subscribe');
                        echo json_encode(array('status' => 'error', 'message' => $unexpected_error_message));
                        wp_die();
                    }
                } elseif ($list_storege == 'mailchimp') {
                    $MailChimp_api_key     = isset($options['mailchimp_api']) ? $options['mailchimp_api'] : '';
                    $MailChimp             = new UltimateSubscribeMailChimp($MailChimp_api_key);
                    $MailChimp->verify_ssl = false;
                    // $user_status = ($opt_in_process == 'double')?'pending':'subscribed';
                    $response = $MailChimp->post("lists/$list_id/members", [
                        'email_address' => $visitor_email,
                        'status'        => 'subscribed',
                        'merge_fields'  => [
                            'FNAME' => $first_name,
                            'LNAME' => $last_name,
                        ],
                    ]);
                    if ($response['status'] == 'subscribed') {
                        $subscribe_success_message = (isset($form_messages['success'])) ? $form_messages['success'] : __('Thank You, You have been successfully subscribed to our newsletter.', 'ultimate-subscribe');
                        echo json_encode(array('status' => 'success', 'message' => $subscribe_success_message));
                        setcookie("ultimate_subscribe_confirmed", 1, time() + (86400 * 60));
                        wp_die();
                    } elseif ($response['status'] == '400') {
                        $already_subscribed_message = (isset($form_messages['already_subscribed'])) ? $form_messages['already_subscribed'] : __('You have already subscribed.', 'ultimate-subscribe');
                        echo json_encode(array('status' => 'warning', 'message' => $already_subscribed_message));
                        setcookie("ultimate_subscribe_confirmed", 1, time() + (86400 * 60));
                        wp_die();
                    } else {
                        $error_message = (isset($form_messages['error'])) ? $form_messages['error'] : __('Error: some unexpected error occurred please try again.', 'ultimate-subscribe');
                        echo json_encode(array('status' => 'error', 'message' => $already_subscribed_message));
                        wp_die();
                    }
                }
            }

        }
    }

    wp_die();
}
add_action('wp_ajax_ultimate_subscribe_submit', 'ultimate_subscribe_submit');
add_action('wp_ajax_nopriv_ultimate_subscribe_submit', 'ultimate_subscribe_submit');

function ultimate_subscribe_confirm_subscriber() {

    if (isset($_REQUEST['ultimate-subscribe-confirm']) && isset($_REQUEST['code'])) {
        $email         = sanitize_text_field($_REQUEST['ultimate-subscribe-confirm']);
        $code          = sanitize_text_field($_GET['code']);
        $options       = ultimate_subscribe_get_options();
        $form_messages = $options['form_messages'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_subscribe';
        $data       = $wpdb->get_row($wpdb->prepare("SELECT active, storege, first_name, last_name, list_id FROM $table_name WHERE email = %s", $email));
        if ($data) {
            if (isset($data->active) && $data->active) {
                $status  = 'us-warning';
                $message = $form_messages['already_confirm'];
            } else {
                $update_table = $wpdb->update($table_name, array('active' => 1), array('email' => $email, 'active_code' => $code));
                if (isset($data->storege) && $data->storege == 'mailchimp' && !empty($data->list_id)) {
                    $MailChimp_api_key     = isset($options['mailchimp_api']) ? $options['mailchimp_api'] : '';
                    $MailChimp             = new UltimateSubscribeMailChimp($MailChimp_api_key);
                    $MailChimp->verify_ssl = false;
                    $list_id               = $data->list_id;
                    $response              = $MailChimp->post("lists/$list_id/members", [
                        'email_address' => $email,
                        'status'        => 'subscribed',
                        'merge_fields'  => [
                            'FNAME' => isset($data->first_name)?$data->first_name:'',
                            'LNAME' => isset($data->last_name)?$data->last_name:'',
                        ],
                    ]);
                }
                if ($update_table) {
                    $status  = 'us-success';
                    $message = $form_messages['confirm'];
                    setcookie("ultimate_subscribe_confirmed", 1, time() + (86400 * 60));
                } else {
                    $status  = 'us-error';
                    $message = $form_messages['error'];
                }
            }

            $html = '<div class="ultimate-subscribe-confirm-overlay" style="display:block;">';
            $html .= '<div class="ultimate-subscribe-confirm-con ' . esc_attr($status) . '">';
            $html .= wp_kses_post($message);
            $html .= '<div class="us-confirm-close-btn" title="close" role="button"><i class="fa fa-times"></i> ' . __('Close', 'ultimate_subscribe') . '</div>';
            $html .= '</div>';
            $html .= '</div>';
            global $ultimate_subscribe_confirm_response;
            $ultimate_subscribe_confirm_response = $html;
            add_action("wp_footer", 'ultimate_subscribe_confirm_response');
        }
    }
}
add_action('init', 'ultimate_subscribe_confirm_subscriber');

function ultimate_subscribe_confirm_response() {
    global $ultimate_subscribe_confirm_response;
    echo $ultimate_subscribe_confirm_response;
}

function ultimate_subscribe_get_random_string($length = 10) {
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function ultimate_subscribe_get_lists($list_storege = 'database') {
    $show_list = array();
    if ($list_storege == 'database') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ultimate_subscribe_lists';
        $lists      = $wpdb->get_results("SELECT * FROM $table_name");
        foreach ($lists as $key => $list) {
            $show_list[] = array('id' => $list->id, 'name' => $list->name);
        }
        return $show_list;

    } elseif ($list_storege == 'mailchimp') {
        $options               = get_option('ultimate_subscribe_options');
        $MailChimp_api_key     = isset($options['mailchimp_api']) ? $options['mailchimp_api'] : '';
        $MailChimp             = new UltimateSubscribeMailChimp($MailChimp_api_key);
        $MailChimp->verify_ssl = false;
        $lists                 = $MailChimp->get('lists');
        $lists                 = $lists['lists'];
        foreach ($lists as $key => $list) {
            $show_list[] = array('id' => $list['id'], 'name' => $list['name']);
        }
        return $show_list;
    }
}

function ultimate_subscribe_ajax_get_lists() {
    $list_storege = isset($_POST['list_storege']) ? sanitize_text_field($_POST['list_storege']) : 'database';
    $lists        = ultimate_subscribe_get_lists($list_storege);
    $lists_html   = '';
    if ($lists) {
        foreach ($lists as $key => $list) {
            $lists_html .= '<option value="' . esc_attr($list['id']) . '">' . esc_html($list['name']) . '</option>';
        }
    }
    echo $lists_html;
    wp_die();
}
add_action('wp_ajax_ultimate_subscribe_ajax_get_lists', 'ultimate_subscribe_ajax_get_lists');

// Display User IP in WordPress
function ultimate_subscribe_get_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}