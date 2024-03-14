<?php
if (!defined('ABSPATH')) {
    exit;
}
$inet_wk_options = get_option('inet_wk');
if (!empty($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-active'])) {
    function inet_wk_send_smtp_email($phpmailer)
    {
        $inet_wk_options = get_option('inet_wk');
        $phpmailer->isSMTP();

        $phpmailer->Host = sanitize_text_field($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-host']);
        $phpmailer->Port = sanitize_text_field($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-port']);;
        $phpmailer->SMTPSecure = sanitize_text_field($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-security']);
        $phpmailer->From = sanitize_text_field($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-email']);

        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = sanitize_text_field($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-user']);
        $phpmailer->Password = $inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-password'];
        $phpmailer->FromName = sanitize_text_field($inet_wk_options['inet-webkit-opt-smtp']['inet-webkit-smtp-fromName']);
        $phpmailer->AddReplyTo($phpmailer->From, $phpmailer->FromName);
    }

    add_action('phpmailer_init', 'inet_wk_send_smtp_email');

    function inetwk_catch_phpmailer_error($error)
    {
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    }

    add_action('wp_mail_failed', 'inetwk_catch_phpmailer_error');

    function inet_wk_mail_enqueue_script()
    {
        $data = array(
            'url' => admin_url('admin-ajax.php'),
            'icon' => INET_WK_URL . 'assets/images/inet-webkit-loading.gif'
        );
        wp_enqueue_script('inet-webkit-smtp', INET_WK_URL . 'assets/js/inet-webkit-smtp-mail.js', array('jquery'), '1.0.0', true);
        wp_localize_script('inet-webkit-smtp', 'smtp', $data);
    }

    add_action('admin_enqueue_scripts', 'inet_wk_mail_enqueue_script');

    add_action('wp_ajax_inet_wk_send_mail', array('inetwk_Posts', 'inet_wk_send_mail_ajax'));
    add_action('wp_ajax_nopriv_inet_wk_send_mail', array('inetwk_Posts', 'inet_wk_send_mail_ajax'));
    function inet_wk_send_mail_ajax()
    {
        if (sanitize_text_field($_POST['email'])) {
            $to = sanitize_text_field($_POST['email']);
            $subject = 'iNET Webkit - Cấu hình SMTP thành công';
            $headers = array('Content-Type: text/html; charset=UTF-8');

            ob_start();

            echo 'Xin chúc mừng bạn đã cấu hình máy chủ SMTP thành công.' . PHP_EOL;
            echo 'iNET Webkit Team.' . PHP_EOL;

            $message = ob_get_contents();

            ob_end_clean();

            $mail = wp_mail($to, $subject, $message, $headers);

            if ($mail) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
        exit();
    }

    add_filter('wp_mail_content_type', 'inetwk_mail_content_type');
    function inetwk_mail_content_type()
    {
        return 'text/html';
    }
}
