<?php

use WpLHLAdminUi\Mailer\EmailTemplateHandler;

class TPUL_EmailSender {

    private $emailOptions;
    private $options;

    public function __construct() {
        $this->emailOptions = new TPUL_Email_Options();
        $this->options = $this->emailOptions->get_options();
    }

    public function notify_admins($tpul_visitor_id, $site_name,  $currentURL, $ip = '') {

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';

        $replace_tokens = [
            '[user-name]' => (!empty($first_name)) ? $first_name : '',
            '[website-name]' => get_bloginfo('name'),
            '[website-url]' => get_bloginfo('url'),
        ];

        if (empty($ip)) {
            $ip = "IP tracking not turned on";
        }
        $current_time = date('Y-m-d H:i:s');

        /**
         * Send Copy email to Admins
         */
        if (!empty($this->options['email_send_to_admins'])) {

            $subject = __("An Anonymous Visitor Just Accepted Terms on - {$site_name}",  "terms-popup-on-user-login");

            $body = "\n" . __(" ",  "terms-popup-on-user-login")
                . "\n" . "================================================="
                . "\n" . "Unique Visitor ID: {$tpul_visitor_id}"
                . "\n" . "Site name: {$site_name}"
                . "\n" . "URL: {$currentURL}"
                . "\n" . "IP: {$ip}"
                . "\n" . "Time: {$current_time}"
                . "\n" . "================================================="
                . "\n";

            $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');
            $emailer = new EmailTemplateHandler(
                $to,
                $subject,
                $body,
                $replace_tokens,
                '',
            );

            $emailer->send($tpl_path);
        }
    }
    public function notify_accept_user($user_id) {

        $user_data = get_userdata($user_id);
        $user_email = $user_data->user_email;
        $to = $user_email;
        $subject = (!empty($this->options['email_subject'])) ? $this->options['email_subject'] : "You've Accepted Our Terms and Conditions";
        $body = (!empty($this->options['email_text_content'])) ? $this->options['email_text_content'] : $this->emailOptions->default_options()['email_text_content'];

        $current_user = wp_get_current_user();
        $first_name = $current_user->first_name;
        $username = $current_user->user_login;

        $replace_tokens = [
            '[user-name]' => (!empty($first_name)) ? $first_name : $username,
            '[website-name]' => get_bloginfo('name'),
            '[website-url]' => get_bloginfo('url'),
        ];


        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';

        $emailer = new EmailTemplateHandler(
            $to,
            $subject,
            $body,
            $replace_tokens,
            '',
        );

        $emailer->send($tpl_path);


        /**
         * Send Copy email to Admins
         */
        $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');

        if (!empty($this->options['email_send_to_admins'])) {

            $subject = __("User Notified of Terms - ",  "terms-popup-on-user-login")
                . $username . " : " . $subject;

            $body = "\n" . __("Notification of user acceptance sent to: ",  "terms-popup-on-user-login")
                . "\n" . "================================================="
                . "\n" . "user ID: " . $user_id
                . "\n" . "username: " . $username
                . "\n" . "user email: " . $user_email
                . "\n" . "================================================="
                . "\n" . "Email: "
                . "\n" . $body;
            $emailer = new EmailTemplateHandler(
                $to,
                $subject,
                $body,
                $replace_tokens,
                '',
            );

            $emailer->send($tpl_path);
        }
    }

    public function send_test_email() {
        $to = (!empty($this->options['email_admin_addr'])) ? $this->options['email_admin_addr'] : get_bloginfo('admin_email');
        $subject = (!empty($this->options['email_subject'])) ? $this->options['email_subject'] : "You've Accepted Our Terms and Conditions";
        $body = (!empty($this->options['email_text_content'])) ? $this->options['email_text_content'] : $this->emailOptions->default_options()['email_text_content'];

        $current_user = wp_get_current_user();
        $first_name = $current_user->first_name;
        $username = $current_user->user_login;

        $replace_tokens = [
            '[user-name]' => (!empty($first_name)) ? $first_name : $username,
            '[website-name]' => get_bloginfo('name'),
            '[website-url]' => get_bloginfo('url'),
        ];

        $tpl_path = WP_PLUGIN_DIR . '/terms-popup-on-user-login/email-templates/default.php';

        error_log($body);

        $emailer = new EmailTemplateHandler(
            $to,
            $subject,
            $body,
            $replace_tokens,
        );

        $emailer->send($tpl_path);
    }
}
